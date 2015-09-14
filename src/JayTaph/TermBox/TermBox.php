<?php

declare(ticks = 1);

namespace JayTaph\TermBox;

use JayTaph\TermBox\Exception\InitalizationException;
use JayTaph\TermBox\Terminal\TerminalFunc;
use JayTaph\TermBox\Terminal\TerminalInterface;
use JayTaph\TermBox\WcWidth;

class TermBox {

    /** @var resource */
    protected $tty_fd;              // File descriptor of /dev/tty

    /** @var resource[] */
    protected $winch_sockets;       // Socket pair for window change notifications

    /** @var TerminalInterface */
    protected $terminal;            // Current terminal

    /** @var string */
    protected $old_tty_settings;    // Old STTY settings

    /** @var ByteBuffer */
    protected $input_buffer;        // Input buffer

    /** @var ByteBuffer */
    protected $output_buffer;       // Output buffer

    /** @var int */
    protected $foreground = Constants::TB_DEFAULT;      // Current foreground color
    /** @var int */
    protected $background = Constants::TB_DEFAULT;      // Current background color

    /** @var int */
    protected $last_fg = 0xFFFF;        // Last foreground color
    /** @var int */
    protected $last_bg = 0xFFFF;        // Last background color


    /** @var int */
    protected $term_width = -1;     // Current terminal width (columns)
    /** @var int */
    protected $term_height = -1;    // Current terminal height (rows)

    /** @var int */
    protected $cursor_x = -1;       // Current cursor X position (0-based)
    /** @var int */
    protected $cursor_y = -1;       // Current cursor Y position (0-based)

    /** @var int */
    protected $last_x = -1;         // Last known cursor X position (0-based)
    /** @var int */
    protected $last_y = -1;         // Last known cursor X position (0-based)

    /** @var int */
    protected $input_mode = Constants::TB_INPUT_ESC;        // Current input mode
    /** @var int */
    protected $output_mode = Constants::TB_OUTPUT_NORMAL;   // Current output mode

    /** @var bool */
    protected $buffer_size_change_request = false;  // Is a request for buffer size change pending?

    /** @var CellBuffer */
    protected $front_buffer;        // Front cell buffer

    /** @var CellBuffer */
    protected $back_buffer;         // Back cell buffer

    /** @var WcWidth */
    protected $wcWidth;

    /**
     *
     */
    public function __construct()
    {
        $this->tty_fd = fopen("/dev/tty", "r+b");
        if (! $this->tty_fd) {
            throw new InitalizationException("Cannot open /dev/tty");
        }

        $terminalDetector = new TerminalDetector();
        $this->terminal = $terminalDetector->detect();

        socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $this->winch_sockets);

        $this->old_tty_settings = $this->getTtySettings();
        $this->setTTYSettings(array(
            '-ignbrk', '-brkint', '-ignpar', '-parmrk', '-inpck', '-istrip', '-inlcr',
            '-igncr', '-icrnl', '-ixon', '-ixoff', '-iuclc', '-ixany', '-imaxbel', '-opost',
            '-isig', '-icanon', '-iexten', '-parenb', 'cs8', 'time', '0', 'min', '0'
        ));

        $this->input_buffer = new ByteBuffer(128, $this->terminal);
        $this->output_buffer = new ByteBuffer(32 * 1024, $this->terminal);

        $this->output_buffer->putsFunc(TerminalFunc::T_ENTER_CA);
        $this->output_buffer->putsFunc(TerminalFunc::T_ENTER_KEYPAD);
        $this->output_buffer->putsFunc(TerminalFunc::T_HIDE_CURSOR);
        $this->sendClear();

        $this->updateTerminalSize();

        pcntl_signal(SIGWINCH, array($this, 'sigwinchhandler'));


        $this->back_buffer = new CellBuffer($this->term_width, $this->term_height, $this->foreground, $this->background);
        $this->front_buffer = new CellBuffer($this->term_width, $this->term_height, $this->foreground, $this->background);
    }

    /**
     *
     */
    public function __destruct()
    {
        if ($this->output_buffer && $this->tty_fd) {
            // If we have an output buffer, send restore characters
            $this->output_buffer->putsFunc(TerminalFunc::T_SHOW_CURSOR);
            $this->output_buffer->putsFunc(TerminalFunc::T_SGR0);
            $this->output_buffer->putsFunc(TerminalFunc::T_CLEAR_SCREEN);
            $this->output_buffer->putsFunc(TerminalFunc::T_EXIT_CA);
            $this->output_buffer->putsFunc(TerminalFunc::T_EXIT_KEYPAD);
            $this->output_buffer->putsFunc(TerminalFunc::T_EXIT_MOUSE);

            $this->output_buffer->flush($this->tty_fd);
        }

        // Restore TTY settings
        if ($this->old_tty_settings) {
            $this->setTtySettingRaw($this->old_tty_settings);
        }

        if ($this->tty_fd) {
            fclose($this->tty_fd);
        }

        if ($this->winch_sockets[0]) {
            socket_close($this->winch_sockets[0]);
            socket_close($this->winch_sockets[1]);
        }

        // Restore pcntl_signal(SIGWINCH, array($this, 'sigwinchhandler'));
    }

    /**
     *
     */
    public function present()
    {
        $this->last_x = -1;
        $this->last_y = -1;

        if ($this->buffer_size_change_request) {
            $this->updateSize();
            $this->buffer_size_change_request = false;
        }

        // Lazy load WcWidth
        if (! $this->wcWidth) {
            $this->wcWidth = new WcWidth();
        }

        // Iterate height x width
        for ($y=0; $y < $this->front_buffer->getHeight(); $y++) {
            for ($x=0; $x < $this->front_buffer->getWidth(); ) {

                // Get front and back cell for coordinate
                $back = $this->back_buffer->getCell($x, $y);
                $front = $this->front_buffer->getCell($x, $y);

                // Check width of character
                $w = $this->wcWidth->getWidth($back->getCh());
                if ($w < 1) $w = 1;

                // Back and front are equal   (@TODO: use reference / hash ID?)
                if ($back->equals($front)) {
                    $x += $w;
                    continue;
                }

                // Set color attributes
                $this->sendColorAttributes($back->getFg(), $back->getBg());

                // Can't set wide character since there isn't enough room left. We send spaces instead
                if ($w > 1 && $x >= $this->front_buffer->getWidth() - ($w - 1)) {
                    for ($i=$x; $i < $this->front_buffer->getWidth(); $i++) {
                        $this->sendChar($i, $y, ' ');
                    }
                } else {
                    // Send character
                    $this->sendChar($x, $y, $back->getCh());
                    // Set the next cells to nothing, as we won't display these cells
                    for ($i=1; $i < $w; $i++) {
                        $cell = new Cell(0, $back->getFg(), $back->getBg());
                        $this->front_buffer->setCell($x + $i, $y, $cell);
                    }
                }

                // Increase X with char width
                $x += $w;
            }
        }

        // Set cursor if needed
        if (! $this->isCursorHidden($this->cursor_x, $this->cursor_y)) {
            $this->writeCursor($this->cursor_x, $this->cursor_y);
        }

        // And flush
        $this->output_buffer->flush($this->tty_fd);
    }


    /**
     * @param $x
     * @param $y
     * @param Cell $cell
     */
    public function putCell($x, $y, Cell $cell)
    {
        // Sanity check to see if we can actually set the character
        if ($x >= $this->back_buffer->getWidth() ||
            $y >= $this->back_buffer->getHeight()) {
            return;
        }

        $this->back_buffer->setCell($x, $y, $cell);
    }


    /**
     * @param $x    X coordinate
     * @param $y    Y coordinate
     * @param $c    Character
     * @param $fg   Foreground color
     * @param $bg   Background color
     *
     * @return Cell
     */
    public function changeCell($x, $y, $c, $fg, $bg)
    {
        $cell = new Cell($c, $fg, $bg);
        $this->putCell($x, $y, $cell);

        return $cell;
    }

    /**
     * Blit an array of cells
     *
     * @param $x    X coordinate
     * @param $y    Y coordinate
     * @param $w    Width
     * @param $h    Height
     * @param array $cells  Cells
     */
    public function blit($x, $y, $w, $h, array $cells)
    {
        if ($x + $w < 0 || $x >= $this->back_buffer->getWidth()) {
            return;
        }

        if ($y + $h < 0 || $y >= $this->back_buffer->getHeight()) {
            return;
        }

        $xo = 0;
        $yo = 0;
        $ww = $w;
        $hh = $h;

        if ($x < 0) {
            $xo = -$x;
            $ww -= $xo;
            $x = 0;
        }

        if ($y < 0) {
            $yo = -$y;
            $hh -= $yo;
            $y =0;
        }

        if ($ww > $this->back_buffer->getWidth() - $x) {
            $ww = $this->back_buffer->getWidth() - $x;
        }
        if ($hh > $this->back_buffer->getHeight() - $y) {
            $hh = $this->back_buffer->getHeight() - $y;
        }

        /*
         * @TODO
                int sy;
                struct tb_cell *dst = &CELL(&back_buffer, x, y);
                const struct tb_cell *src = cells + yo * w + xo;
                size_t size = sizeof(struct tb_cell) * ww;

                for (sy = 0; sy < hh; ++sy) {
                    memcpy(dst, src, size);
                    dst += back_buffer.width;
                    src += w;
                }
         */
    }

    /**
     * Return width of terminal
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->term_width;
    }

    /**
     * Return height of terminal
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->term_height;
    }

    /**
     * Clear back buffer with empty cells in current colors
     */
    public function clear() {
        if ($this->buffer_size_change_request) {
            $this->updateSize();
            $this->buffer_size_change_request = false;
        }

        $this->back_buffer->clear($this->foreground, $this->background);
    }

    /**
     * @return int
     */
    public function getInputMode()
    {
        return $this->input_mode;
    }

    /**
     * @param $mode
     * @return int
     */
    public function selectInputMode($mode)
    {
        // Either we are in ESC or ALT. If none is given, use ESC
        if (($mode & (Constants::TB_INPUT_ESC | Constants::TB_INPUT_ALT)) == 0) {
            $mode |= Constants::TB_INPUT_ESC;
        }

        // If ESC and ALT is given, use ALT
        if (($mode & (Constants::TB_INPUT_ESC | Constants::TB_INPUT_ALT)) == (Constants::TB_INPUT_ESC | Constants::TB_INPUT_ALT)) {
            $mode &= ~Constants::TB_INPUT_ALT;
        }

        $this->input_mode = $mode;

        if ($mode & Constants::TB_INPUT_MOUSE) {
            $this->output_buffer->putsFunc(TerminalFunc::T_ENTER_MOUSE);
        } else {
            $this->output_buffer->putsFunc(TerminalFunc::T_EXIT_MOUSE);
        }


        $this->output_buffer->flush($this->tty_fd);

        return $this->input_mode;
    }

    /**
     * Block until event occurs
     *
     * @return Event
     */
    public function pollEvent()
    {
        return $this->waitFillEvent(0);
    }

    /**
     * Peek for event until event occurs or timeout
     *
     * @param $timeout
     * @return Event|null
     */
    public function peekEvent($timeout)
    {
        return $this->waitFillEvent($timeout);
    }

    /**
     * Set cursor
     *
     * @param $cx
     * @param $cy
     */
    protected function setCursor($cx, $cy)
    {
        // Show cursor if hidden and becomes visible
        if ($this->isCursorHidden($this->cursor_x, $this->cursor_y) && ! $this->isCursorHidden($cx, $cy)) {
            $this->output_buffer->putsFunc(TerminalFunc::T_SHOW_CURSOR);
        }

        // Hide cursor if not hidden and becomes hidden
        if (! $this->isCursorHidden($this->cursor_x, $this->cursor_y) && $this->isCursorHidden($cx, $cy)) {
            $this->output_buffer->putsFunc(TerminalFunc::T_HIDE_CURSOR);
        }

        // Set new cursor offset
        $this->cursor_x = $cx;
        $this->cursor_y = $cy;

        // Write the cursor on the new location
        if (! $this->isCursorHidden($this->cursor_x, $this->cursor_y)) {
            $this->writeCursor($this->cursor_x, $this->cursor_y);
        }
    }

    protected function sigwinchhandler()
    {
        // send a byte to winch_sockets[1], so we can pick up in another loop
        fwrite($this->winch_sockets[1], "1");
    }

    /**
     * Get TTY settings
     *
     * @return string
     */
    protected function getTtySettings()
    {
        $settings = shell_exec('stty -g');
        return $settings;
    }

    /**
     * Set TTY settings
     *
     * @param $settings
     */
    protected function setTTYSettingRaw($settings)
    {
        shell_exec('stty '. $settings);
    }

    /**
     * Set TTY settings
     *
     * @param array $settings
     */
    protected function setTTYSettings(array $settings)
    {
        foreach (array_keys($settings) as $k) {
            $settings[$k] = escapeshellarg($settings[$k]);
        }

        $this->setTTYSettingRaw(join(' ', $settings));
    }

    /**
     * Return true if the cursor is hidden. False if not
     *
     * @param $cx
     * @param $cy
     * @return bool
     */
    protected function isCursorHidden($cx, $cy)
    {
        return ($cx == -1 || $cy == -1);
    }

    /**
     * Send ANSI codes for setting the cursor at the given position
     *
     * @param $cx
     * @param $cy
     */
    protected function writeCursor($cx, $cy)
    {
        $this->output_buffer->append("\033[".($cy+1).";".($cx+1)."H");
    }

    /**
     * Send ANSI codes for foreground color
     *
     * @param $fg
     */
    protected function writeSgrFg($fg)
    {
        $this->output_buffer->append("\033[3".($fg-1)."m");
    }

    /**
     * Send ANSI codes for background color
     *
     * @param $bg
     */
    protected function writeSgrBg($bg)
    {
        $this->output_buffer->append("\033[4".($bg-1)."m");
    }

    /**
     * Write ANSI colors for foreground and background color, based on the given output mode
     * @param $fg
     * @param $bg
     */
    protected function writeSgr($fg, $bg)
    {
        switch ($this->output_mode) {
            // Use extended ANSI colors
            case Constants::TB_OUTPUT_256:
            case Constants::TB_OUTPUT_216:
            case Constants::TB_OUTPUT_GRAYSCALE:
                $this->output_buffer->append("\033[38;5;" . ($fg) . "m\033[48;5;" . ($bg) . "m");
                break;

            // Use default ANSI colors
            case Constants::TB_OUTPUT_NORMAL:
            default:
                $this->output_buffer->append("\033[3" . ($fg-1) . ";4" . ($bg-1) . "m");
                break;
        }
    }


    /**
     * Clears the screen
     */
    protected function sendClear()
    {
        // Set color
        $this->sendColorAttributes($this->foreground, $this->background);

        // Set clear screen
        $this->output_buffer->putsFunc(TerminalFunc::T_CLEAR_SCREEN);

        // Set cursor
        if (! $this->isCursorHidden($this->cursor_x, $this->cursor_y)) {
            $this->writeCursor($this->cursor_x, $this->cursor_y);
        }

        // Output
        $this->output_buffer->flush($this->tty_fd);

        $this->last_x = -1;
        $this->last_y = -1;
    }


    /**
     * Set output mode
     *
     * @param $mode
     * @return int
     */
    protected function selectOutputMode($mode)
    {
        $this->output_mode = $mode;
        return $this->output_mode;
    }

//    /**
//     * Set default attributes
//     *
//     * @param $fg
//     * @param $bg
//     */
//    protected function clearAttributes($fg, $bg) {
//        $this->foreground = $fg;
//        $this->background = $bg;
//    }

    protected function getTerminalSize()
    {
        $w = exec('/usr/bin/tput cols');
        $h = exec('/usr/bin/tput lines');

        return array($w, $h);
    }

    protected function updateTerminalSize()
    {
        list($w, $h) = $this->getTerminalSize();

        $this->term_width = $w;
        $this->term_height = $h;
    }

    protected function sendColorAttributes($fg, $bg)
    {
        if ($fg == $this->last_fg && $bg != $this->last_bg) {
            return;
        }

        $this->output_buffer->putsFunc(TerminalFunc::T_SGR0);

        switch ($this->output_mode) {
            case Constants::TB_OUTPUT_256 :
                $fgcol = $fg & 0xFF;
                $bgcol = $bg & 0xFF;
                break;
            case Constants::TB_OUTPUT_216 :
                $fgcol = $fg & 0xFF; if ($fgcol > 215) $fgcol = 7;
                $bgcol = $bg & 0xFF; if ($bgcol > 215) $bgcol = 0;
                $fgcol += 0x10;
                $bgcol += 0x10;
                break;
            case Constants::TB_OUTPUT_GRAYSCALE :
                $fgcol = $fg & 0xFF; if ($fgcol > 23) $fgcol = 23;
                $bgcol = $bg & 0xFF; if ($bgcol > 23) $bgcol = 0;
                $fgcol += 0xe8;
                $bgcol += 0xe8;
                break;

            case Constants::TB_OUTPUT_NORMAL:
            default:
                $fgcol = $fg & 0x0F;
                $bgcol = $bg & 0x0F;
                break;
        }

        if ($fg & Constants::TB_BOLD) {
            $this->output_buffer->putsFunc(TerminalFunc::T_BOLD);
        }
        if ($bg & Constants::TB_BOLD) {
            $this->output_buffer->putsFunc(TerminalFunc::T_BLINK);
        }
        if ($fg & Constants::TB_UNDERLINE) {
            $this->output_buffer->putsFunc(TerminalFunc::T_UNDERLINE);
        }
        if ( ($fg & Constants::TB_REVERSE) || ($bg & Constants::TB_REVERSE)) {
            $this->output_buffer->putsFunc(TerminalFunc::T_REVERSE);
        }

        switch ($this->output_mode) {
            case Constants::TB_OUTPUT_256 :
            case Constants::TB_OUTPUT_216 :
            case Constants::TB_OUTPUT_GRAYSCALE :
                $this->writeSgr($fgcol, $bgcol);
                break;
            case Constants::TB_OUTPUT_NORMAL :
            default:
                if ($fgcol != Constants::TB_DEFAULT) {
                    if ($bgcol != Constants::TB_DEFAULT) {
                        $this->writeSgr($fgcol, $bgcol);
                    } else {
                        $this->writeSgrFg($fgcol);
                    }
                } else {
                    if ($bgcol != Constants::TB_DEFAULT) {
                        $this->writeSgrBg($bgcol);
                    }
                }
        }

        $this->last_fg = $fg;
        $this->last_bg = $bg;
    }

    protected function sendChar($x, $y, $c)
    {
        if ($x-1 != $this->last_x || $y != $this->last_y) {
            $this->writeCursor($x, $y);
        }

        $this->last_x = $x;
        $this->last_y = $y;

        $this->output_buffer->puts(Utf8::unicodeToChar($c));
    }

    protected function updateSize()
    {
        $this->updateTerminalSize();

        $this->back_buffer->resize($this->term_width, $this->term_height, $this->foreground, $this->background);
        $this->front_buffer->resize($this->term_width, $this->term_height, $this->foreground, $this->background);
        $this->front_buffer->clear($this->foreground, $this->background);

        $this->sendClear();
    }


//    protected function setColorAttributes($fg, $bg) {
//        $this->foreground = $fg;
//        $this->background = $bg;
//    }

    protected function waitFillEvent($timeout = 0)
    {
        $event = new Event();
        $event->setType(Constants::TB_EVENT_KEY);

        if ($event = $this->extractEvent($event, $this->input_buffer, $this->input_mode)) {
            return $event;
        }

        $n = $this->readUpTo(64);
        if ($n < 0) return -1;
        if ($n > 0 && $this->extractEvent($event, $this->input_buffer, $this->input_mode)) {
            return $event->getType();
        }

        while (1) {
            $read = array($this->tty_fd, $this->winch_sockets[0]);
            $write = null;
            $except = null;
            $result = socket_select($read, $write, $except, 0, $timeout);
            if ($result === false) {
                return 0;
            }

            foreach ($read as $s) {
                if ($s == $this->tty_fd) {
                    $event->type = Constants::TB_EVENT_KEY;
                    $n = $this->readUpTo(64);
                    if ($n < 0) {
                        return -1;
                    }
                    if ($n == 0) {
                        continue;
                    }

                    if ($this->extractEvent($event, $this->input_buffer, $this->input_mode)) {
                        return $event->getType();
                    }
                }
                if ($s == $this->winch_sockets[0]) {
                    $event->type = Constants::TB_EVENT_RESIZE;
                    $s = fread($this->winch_sockets[0], 1);

                    $this->buffer_size_change_request = true;
                    list($w, $h) = $this->getTerminalSize();
                    $event->w = $w;
                    $event->h = $h;
                    return Constants::TB_EVENT_RESIZE;
                }
            }
        }

        return $event->getType();
    }


    /**
     * Read at maximum $n characters from the TTY into the input buffer
     *
     * @param $n
     * @return int
     */
    protected function readUpTo($n)
    {
        $s = fread($this->tty_fd, $n);

        $this->input_buffer->append($s);

        return strlen($s);
    }


    /**
     * @param Event $event
     * @param $buf
     * @return int
     */
    protected function parseEscapeSequence(Event $event, $buf)
    {
        if (strlen($buf) >= 6 && strpos($buf, "\033[M") === 0) {
            switch ($buf[3] & 3) {
                case 0 :
                    if ($buf[3] == 0x60) {
                        $event->setKey(Constants::TB_KEY_MOUSE_WHEEL_UP);
                    } else {
                        $event->setKey(Constants::TB_KEY_MOUSE_LEFT);
                    }
                    break;
                case 1:
                    if ($buf[3] == 0x61) {
                        $event->setKey(Constants::TB_KEY_MOUSE_WHEEL_DOWN);
                    } else {
                        $event->setKey(Constants::TB_KEY_MOUSE_MIDDLE);
                    }
                    break;
                case 2:
                    $event->setKey(Constants::TB_KEY_MOUSE_WHEEL_DOWN);
                    break;
                case 3:
                    $event->setKey(Constants::TB_KEY_MOUSE_RIGHT);
                    break;
                case 4:
                    $event->setKey(Constants::TB_KEY_MOUSE_RELEASE);
                    break;
                default:
                    return -6;
            }

            $event->setType(Constants::TB_EVENT_MOUSE);

            $event->setX($buf[4] - 1 - 32);
            $event->setY($buf[5] - 1 - 32);

            return 6;
        }

        for ($i = 0; $i != $keys[$i]; $i++) {
            if (strpos($buf)) {
                $event->setChar(0);
                $event->getKey(0xFFFF - $i);
                return strlen($keys[$i]);
            }
        }

        return 0;
    }

    /**
     * @param Event $event
     * @param ByteBuffer $buffer
     * @param $mode
     * @return bool
     */
    protected function extractEvent(Event $event, ByteBuffer $buffer, $mode)
    {
        if ($buffer->getLength() == 0) {
            return false;
        }

        $buf = $buffer->getBuffer();
        if ($buf[0] == "\033") {
            $len = $this->parseEscapeSequence($event, $buf);
            if ($len > 0) {
                $buffer->truncate($len);
                return true;
            }

            if ($this->input_mode & Constants::TB_INPUT_ESC) {
                $event->setChar(0);
                $event->setKey(Constants::TB_KEY_ESC);
                $event->setMode(0);
                $buffer->truncate(1);
                return true;
            }
            if ($this->input_mode & Constants::TB_INPUT_ALT) {
                $event->setMode(Constants::TB_MOD_ALT);
                $buffer->truncate(1);
                return $this->extractEvent($event, $buffer, $this->input_mode);
            }

            throw new \RuntimeException("This should be unreachable");
        }

        if ($buf[0] <= Constants::TB_KEY_SPACE ||
            $buf[0] == Constants::TB_KEY_BACKSPACE2) {
            $event->setChar(0);
            $event->setKey($buf[0]);
            $buffer->truncate(1);
            return true;
        }

        // UTF8 char, check if we have enough bytes to create this character
        $utf8len = Utf8::getLength($buf[0]);
        if (strlen($buf) >= $utf8len) {
            $event->setKey(0);
            $buffer->truncate($utf8len);
            return true;
        }

        // Seems we haven't got enough bytes for UTF8 or something else happened
        return false;
    }

}
