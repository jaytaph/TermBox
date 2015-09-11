<?php

use JayTaph\TermBox\Constants;
use JayTaph\TermBox\Event;
use JayTaph\TermBox\TermBox;

require_once "vendor/autoload.php";

$counter = 0;

$keys = array(
    "K_ESC" => array( array( 1, 1, 'E'), array( 2, 1,'S'), array( 3,1,'C') ),
    "K_F1"  => array( array( 6, 1, 'F'), array( 7, 1, '1') ),
    "K_F2"  => array( array( 9, 1, 'F'), array(10, 1, '2') ),
    "K_F3"  => array( array(12, 1, 'F'), array(13, 1, '3') ),
    "K_F4"  => array( array(15, 1, 'F'), array(16, 1, '4') ),
    "K_F5"  => array( array(19, 1, 'F'), array(20, 1, '5') ),
    "K_F6"  => array( array(22, 1, 'F'), array(23, 1, '6') ),
    "K_F7"  => array( array(25, 1, 'F'), array(26, 1, '7') ),
    "K_F8"  => array( array(28, 1, 'F'), array(29, 1, '8') ),
    "K_F9"  => array( array(33, 1, 'F'), array(34, 1, '9') ),
    "K_F10" => array( array(36, 1, 'F'), array(37, 1, '1'), array(48, 1, '0') ),
    "K_F11" => array( array(40, 1, 'F'), array(41, 1, '1'), array(42, 1, '1') ),
    "K_F12" => array( array(44, 1, 'F'), array(45, 1, '1'), array(46, 1, '2') ),
    "K_PRN" => array( array(50, 1, 'P'), array(51, 1, 'R'), array(52, 1, 'N') ),
    "K_SCR" => array( array(54, 1, 'S'), array(55, 1, 'C'), array(56, 1, 'R') ),
    "K_BRK" => array( array(58, 1, 'B'), array(59, 1, 'R'), array(60, 1, 'K') ),
    "K_LED1" => array( array(66, 1, '-') ),
    "K_LED2" => array( array(70, 1, '-') ),
    "K_LED3" => array( array(74, 1, '-') ),

    "K_TILDE"   => array( array( 1, 4, '`') ),
    "K_1"       => array( array( 4, 4, '1') ),
    "K_2"       => array( array( 7, 4, '2') ),
    "K_3"       => array( array(10, 4, '3') ),
    "K_4"       => array( array(13, 4, '4') ),
    "K_5"       => array( array(16, 4, '5') ),
    "K_6"       => array( array(19, 4, '6') ),
    "K_7"       => array( array(22, 4, '7') ),
    "K_8"       => array( array(25, 4, '8') ),
    "K_9"       => array( array(28, 4, '9') ),
    "K_0"       => array( array(31, 4, '0') ),
    "K_MINUS"       => array( array(34, 4, '-') ),
    "K_EQUALS"      => array( array(37, 4, '=') ),
    "K_BACKSLASH"   => array( array(40, 4, '\\') ),
    "K_BACKSPACE"   => array( array(44, 4, 0x2190), array(45, 4, 0x2500), array(46, 4, 0x2500) ),
    "K_INS"         => array( array(50, 4, 'I'), array(51, 4, 'N'), array(52, 4, 'S') ),
    "K_HOM"         => array( array(54, 4, 'H'), array(55, 4, 'O'), array(56, 4, 'M') ),
    "K_PGU"         => array( array(58, 4, 'P'), array(59, 4, 'G'), array(60, 4, 'U') ),
    "K_K_NUMLOCK"   => array( array(65, 4, 'N') ),
    "K_K_SLASH"     => array( array(68, 4, '/') ),
    "K_K_STAR"      => array( array(71, 4, '*') ),
    "K_K_MINUS"     => array( array(74, 4, '-') ),

    "K_TAB"     => array( array( 1, 6, 'T'), array( 2, 6, 'A'), array( 3, 6, 'B') ),
    "K_Q"       => array( array( 6, 6, 'q') ),
    "K_W"       => array( array( 9, 6, 'w') ),
    "K_E"       => array( array(12, 6, 'e') ),
    "K_R"       => array( array(15, 6, 'r') ),
    "K_T"       => array( array(18, 6, 't') ),
    "K_Y"       => array( array(21, 6, 'y') ),
    "K_U"       => array( array(24, 6, 'u') ),
    "K_I"       => array( array(27, 6, 'i') ),
    "K_O"       => array( array(30, 6, 'o') ),
    "K_P"       => array( array(33, 6, 'p') ),
    "K_LSQB"       => array( array(36, 6, '[') ),
    "K_RSQB"       => array( array(39, 6, ']') ),
    "K_ENTER"       => array( array(43, 6, 0x2591), array(44, 6, 0x2591), array(45, 6, 0x2591), array(46, 6, 0x2591),
                              array(43, 7, 0x2591), array(44, 7, 0x2591), array(45, 7, 0x21B5), array(46, 7, 0x2591),
                              array(41, 8, 0x2591), array(42, 8, 0x2591),
                              array(43, 8, 0x2591), array(44, 8, 0x2591), array(45, 8, 0x2591), array(46, 8, 0x2591),
                        ),
    "K_DEL"       => array( array(50, 6, 'D'), array(51, 6, 'E'), array(52, 6, 'L') ),
    "K_END"       => array( array(54, 6, 'E'), array(55, 6, 'N'), array(56, 6, 'D') ),
    "K_PGD"       => array( array(58, 6, 'P'), array(59, 6, 'G'), array(60, 6, 'D') ),
    "K_K_7"       => array( array(65, 6, '7') ),
    "K_K_8"       => array( array(68, 6, '8') ),
    "K_K_9"       => array( array(71, 6, '9') ),
    "K_K_PLUS"    => array( array(74, 6, ' '), array(74, 7, '+'), array(74, 8, ' ') ),


    "K_CAPS"    => array( array( 1, 8, 'C'), array( 2, 8, 'A'), array( 3, 8, 'P'), array( 4, 8, 'S') ),
    "K_A"       => array( array( 7, 8, 'a') ),
    "K_S"       => array( array(10, 8, 's') ),
    "K_D"       => array( array(13, 8, 'd') ),
    "K_F"       => array( array(16, 8, 'f') ),
    "K_G"       => array( array(19, 8, 'g') ),
    "K_H"       => array( array(22, 8, 'h') ),
    "K_J"       => array( array(25, 8, 'j') ),
    "K_K"       => array( array(28, 8, 'k') ),
    "K_L"       => array( array(31, 8, 'l') ),
    "K_SEMI"    => array( array(34, 8, ';') ),
    "K_QUOTE"   => array( array(37, 8, '\'') ),
    "K_K_4"     => array( array(65, 8, '4') ),
    "K_K_5"     => array( array(68, 8, '5') ),
    "K_K_6"     => array( array(71, 8, '6') ),

    "K_LSHIFT"  => array( array( 1, 10, 'S'), array( 2, 10, 'H'), array( 3, 10, 'I'), array( 4, 10, 'F'), array( 5, 10, 'T') ),
    "K_Z"       => array( array( 9, 10, 'z') ),
    "K_X"       => array( array(12, 10, 'x') ),
    "K_C"       => array( array(15, 10, 'c') ),
    "K_V"       => array( array(18, 10, 'v') ),
    "K_B"       => array( array(21, 10, 'b') ),
    "K_N"       => array( array(24, 10, 'n') ),
    "K_M"       => array( array(27, 10, 'm') ),
    "K_COMMA"     => array( array(30, 10, ',') ),
    "K_PERIOD"    => array( array(33, 10, '.') ),
    "K_SLASH"     => array( array(36, 10, '/') ),
    "K_RSHIFT"    => array( array(42, 10, 'S'), array(43, 10, 'H'), array(44, 10, 'I'), array(45, 10, 'F'), array(46, 10, 'T') ),
    "K_ARROW_UP"  => array( array(54, 10, ' '), array(55, 10, 0x2191), array(56, 10, ' ') ),
    "K_K_1"       => array( array(65, 10, '1') ),
    "K_K_2"       => array( array(68, 10, '2') ),
    "K_K_3"       => array( array(71, 10, '3') ),
    "K_K_ENTER"   => array( array(74, 10, 0x2591), array(74, 11, 0x2591),array(74, 12, 0x2591) ),


    "K_LCTRL"  => array( array( 1, 12, 'C'), array( 2, 12, 'T'), array( 3, 12, 'R'), array( 4, 12, 'L') ),
    "K_LWIN"   => array( array( 6, 12, ' '), array( 7, 12, 0x2318), array( 8, 12, ' ') ),
    "K_LALT"   => array( array(10, 12, 'A'), array(11, 12, 'L'), array(12, 12, 'T') ),
    "K_SPACE"   => array(
                        array(14, 12, ' '), array(15, 12, ' '), array(16, 12, ' '), array(17, 12, ' '), array(18, 12, ' '),
                        array(19, 12, 'S'), array(20, 12, 'P'), array(21, 12, 'A'), array(22, 12, 'C'), array(23, 12, 'E'),
                        array(24, 12, ' '), array(25, 12, ' '), array(26, 12, ' '), array(27, 12, ' '), array(28, 12, ' ')
                    ),
    "K_RALT"   => array( array(30, 12, 'A'), array(31, 12, 'L'), array(32, 12, 'T') ),
    "K_RWIN"   => array( array(34, 12, ' '), array(35, 12, 0x2318), array(36, 12, ' ') ),
    "K_RPROP"  => array( array(38, 12, 'P'), array(39, 12, 'R'), array(40, 12, 'O'), array(41, 12, 'P') ),
    "K_RCTRL"  => array( array(43, 12, 'C'), array(44, 12, 'T'), array(45, 12, 'R'), array(46, 12, 'L') ),

    "K_ARROW_LEFT"  => array( array(50, 12, ' '), array(51, 12, 0x2190), array(52, 12, ' ') ),
    "K_ARROW_DOWN"  => array( array(54, 12, ' '), array(55, 12, 0x2193), array(56, 12, ' ') ),
    "K_ARROW_RIGHT" => array( array(58, 12, ' '), array(59, 12, 0x2192), array(60, 12, ' ') ),

    "K_K_0"         => array( array(65, 12, ' '), array(66, 12, '0'),array(67, 12, ' ') ),
    "K_K_PERIOD"    => array( array(71, 12, '.') ),
);

$keys_shift = array(
    "K_TILDE_SHIFT" => array( array( 1, 4, '~') ),
    "K_1_SHIFT"     => array( array( 4, 4, '!') ),
    "K_2_SHIFT"     => array( array( 7, 4, '@') ),
    "K_3_SHIFT"     => array( array(10, 4, '#') ),
    "K_4_SHIFT"     => array( array(13, 4, '$') ),
    "K_5_SHIFT"     => array( array(16, 4, '%') ),
    "K_6_SHIFT"     => array( array(19, 4, '^') ),
    "K_7_SHIFT"     => array( array(22, 4, '&') ),
    "K_8_SHIFT"     => array( array(25, 4, '*') ),
    "K_9_SHIFT"     => array( array(28, 4, '(') ),
    "K_0_SHIFT"     => array( array(31, 4, ')') ),
    "K_MINUS_SHIFT"       => array( array(34, 4, '_') ),
    "K_EQUALS_SHIFT"      => array( array(37, 4, '+') ),
    "K_BACKSLASH_SHIFT"   => array( array(40, 4, '|') ),
);





$tb = new TermBox();
$tb->selectInputMode(Constants::TB_INPUT_ESC | Constants::TB_INPUT_MOUSE);

draw_keyboard($tb);
$tb->present();

$input_mode = 0;
$ctrlxpressed = false;

while ($event = $tb->pollEvent()) {
    switch ($event->getType()) {

        // Did we press a key?
        case Constants::TB_EVENT_KEY :
            if ($event->getKey() == Constants::TB_KEY_CTRL_Q && $ctrlxpressed) {
                return 0;
            }
            if ($event->getKey() == Constants::TB_KEY_CTRL_C && $ctrlxpressed) {
                $chmap = array(
                    Constants::TB_INPUT_ESC | Constants::TB_INPUT_MOUSE,
                    Constants::TB_INPUT_ALT | Constants::TB_INPUT_MOUSE,
                    Constants::TB_INPUT_ESC,
                    Constants::TB_INPUT_ALT,
                );
                $input_mode++;
                if ($input_mode >= 4) {
                    $input_mode = 0;
                }
                $tb->selectInputMode($input_mode);
            }
            if ($event->getKey() == Constants::TB_KEY_CTRL_X) {
                $ctrlxpressed = true;
            } else {
                $ctrlxpressed = false;
            }

            $tb->clear();
            draw_keyboard($tb);
            dispatch_press($event);
            pretty_print_press($event);
            $tb->present();
            break;

        // Did we resize the terminal?
        case Constants::TB_EVENT_RESIZE :
            $tb->clear();
            draw_keyboard($tb);
            pretty_print_resize($event);
            $tb->present();
            break;

        // Did we jiggly the mouse?
        case Constants::TB_EVENT_MOUSE :
            $tb->clear();
            draw_keyboard($tb);
            pretty_print_mouse($event);
            $tb->present();
            break;

        default:
            break;
    }
}

sleep(3);

exit();


function draw_keyboard(TermBox $tb) {
    $tb->changeCell( 0,  0, 0x250C, Constants::TB_WHITE | Constants::TB_BOLD, Constants::TB_DEFAULT);
    $tb->changeCell(79,  0, 0x2510, Constants::TB_WHITE | Constants::TB_BOLD, Constants::TB_DEFAULT);
    $tb->changeCell( 0, 23, 0x2514, Constants::TB_WHITE | Constants::TB_BOLD, Constants::TB_DEFAULT);
    $tb->changeCell(79, 23, 0x2518, Constants::TB_WHITE | Constants::TB_BOLD, Constants::TB_DEFAULT);

    for ($i = 1; $i < 79; $i++) {
        $tb->changeCell($i,  0, 0x2500, Constants::TB_WHITE | Constants::TB_BOLD, Constants::TB_DEFAULT);
        $tb->changeCell($i, 23, 0x2500, Constants::TB_WHITE | Constants::TB_BOLD, Constants::TB_DEFAULT);
        $tb->changeCell($i, 17, 0x2500, Constants::TB_WHITE | Constants::TB_BOLD, Constants::TB_DEFAULT);
        $tb->changeCell($i,  4, 0x2500, Constants::TB_WHITE | Constants::TB_BOLD, Constants::TB_DEFAULT);
    }
    for ($i = 1; $i < 23; $i++) {
        $tb->changeCell( 0, $i, 0x2502, Constants::TB_WHITE | Constants::TB_BOLD, Constants::TB_DEFAULT);
        $tb->changeCell(79, $i, 0x2502, Constants::TB_WHITE | Constants::TB_BOLD, Constants::TB_DEFAULT);
    }

    $tb->changeCell( 0, 17, 0x251C, Constants::TB_WHITE | Constants::TB_BOLD, Constants::TB_DEFAULT);
    $tb->changeCell(79, 17, 0x2524, Constants::TB_WHITE | Constants::TB_BOLD, Constants::TB_DEFAULT);
    $tb->changeCell( 0,  4, 0x251C, Constants::TB_WHITE | Constants::TB_BOLD, Constants::TB_DEFAULT);
    $tb->changeCell(79,  4, 0x2524, Constants::TB_WHITE | Constants::TB_BOLD, Constants::TB_DEFAULT);

    for ($i = 5; $i < 17; $i++) {
        $tb->changeCell( 1, $i, 0x2588, Constants::TB_YELLOW | Constants::TB_BOLD, Constants::TB_YELLOW);
        $tb->changeCell(78, $i, 0x2588, Constants::TB_YELLOW | Constants::TB_BOLD, Constants::TB_YELLOW);
    }

    global $keys;
    foreach (array_keys($keys) as $key) {
        draw_key($tb, $key, Constants::TB_WHITE, Constants::TB_BLUE);
    }

    printf_tb($tb, 33, 1, Constants::TB_MAGENTA | Constants::TB_BOLD, Constants::TB_DEFAULT, "Keyboard demo!");
    printf_tb($tb, 21, 2, Constants::TB_MAGENTA, Constants::TB_DEFAULT, "(press CTRL+X and then CTRL+Q to exit)");
    printf_tb($tb, 15, 3, Constants::TB_MAGENTA, Constants::TB_DEFAULT, "(press CTRL+X and then CTRL+C to change input mode)");

    $inputmode = $tb->selectInputMode(0);
    $modes = array();
    if ($inputmode & Constants::TB_INPUT_ESC) {
        $modes[] = "TB_INPUT_ESC";
    }
    if ($inputmode & Constants::TB_INPUT_ALT) {
        $modes[] = "TB_INPUT_ALT";
    }
    if ($inputmode & Constants::TB_INPUT_MOUSE) {
        $modes[] = "TB_INPUT_MOUSE";
    }
    printf_tb($tb, 3, 18, Constants::TB_WHITE, Constants::TB_DEFAULT, "Input mode: ".join(" | ", $modes));
}

function printf_tb(TermBox $tb, $x, $y, $fg, $bg, $s) {
    for ($i=0; $i!=strlen($s); $i++) {
        $tb->changeCell($x + $i, $y, $s[$i], $fg, $bg);
    }
}

function draw_key(TermBox $tb, $key, $fg, $bg) {
    global $keys;

    if (! isset($keys[$key])) {
        return;
    }

    foreach ($keys[$key] as $cell) {
        $tb->changeCell($cell[0] + 2, $cell[1] + 4, $cell[2], $fg, $bg);
    }
}

function dispatch_press(Event $event, TermBox $tb) {
    if ($event->getMode() & Constants::TB_MOD_ALT) {
        draw_key($tb, "K_LALT", Constants::TB_WHITE, Constants::TB_RED);
        draw_key($tb, "K_RALT", Constants::TB_WHITE, Constants::TB_RED);
    }
}
/*
	struct combo *k = 0;
	if (ev->key >= TB_KEY_ARROW_RIGHT)
		k = &func_combos[0xFFFF-ev->key];
	else if (ev->ch < 128) {
		if (ev->ch == 0 && ev->key < 128)
			k = &combos[ev->key];
		else
			k = &combos[ev->ch];
	}
	if (!k)
		return;

	struct key **keys = k->keys;
	while (*keys) {
		draw_key(*keys, TB_WHITE, TB_RED);
		keys++;
	}
*/

function pretty_print_mouse(TermBox $tb, Event $event) {
    printf_tb($tb, 3, 19, Constants::TB_WHITE, Constants::TB_DEFAULT, "Mouse event: ".$event->getX()." x ".$event->getY());

    $btn = "";
    switch ($event->getKey()) {
        case Constants::TB_KEY_MOUSE_LEFT :
            $btn = "MouseLeft: ";
            break;
        case Constants::TB_KEY_MOUSE_MIDDLE :
            $btn = "MouseMiddle: ";
            break;
        case Constants::TB_KEY_MOUSE_RIGHT :
            $btn = "MouseRight: ";
            break;
        case Constants::TB_KEY_MOUSE_WHEEL_UP :
            $btn = "MouseWheelUp: ";
            break;
        case Constants::TB_KEY_MOUSE_WHEEL_DOWN :
            $btn = "MouseWheelDown: ";
            break;
        case Constants::TB_KEY_MOUSE_RELEASE :
            $btn = "MouseRelease: ";
            break;
    }

    global $counter;
    $counter++;
    printf_tb($tb, 43, 19, Constants::TB_WHITE, Constants::TB_DEFAULT, "Key: ");
    printf_tb($tb, 48, 19, Constants::TB_YELLOW, Constants::TB_DEFAULT, $btn." ".$counter);
}

function pretty_print_resize(TermBox $tb, Event $event)
{
    printf_tb($tb, 3, 19, Constants::TB_WHITE, Constants::TB_DEFAULT, "Resize event: ".$event->getWidth()." x ".$event->getHeight());
}

function pretty_print_press(TermBox $tb, Event $event)
{
    printf_tb($tb, 3, 19, Constants::TB_WHITE , Constants::TB_DEFAULT, "Key: ");
    printf_tb($tb, 8, 19, Constants::TB_YELLOW, Constants::TB_DEFAULT, sprintf("decimal: %d", $event->getKey()));
    printf_tb($tb, 8, 20, Constants::TB_GREEN , Constants::TB_DEFAULT, sprintf("hex:     0x%X", $event->getKey()));
    printf_tb($tb, 8, 21, Constants::TB_CYAN  , Constants::TB_DEFAULT, sprintf("octal:   0%o", $event->getKey()));
    //printf_tb($tb, 8, 22, Constants::TB_RED   , Constants::TB_DEFAULT, sprintf("string:  %s", funckeymap(ev->key)));

    printf_tb($tb, 54, 19, Constants::TB_WHITE , Constants::TB_DEFAULT, "Char: ");
    printf_tb($tb, 60, 19, Constants::TB_YELLOW, Constants::TB_DEFAULT, sprintf("decimal: %d", $event->getChar()));
    printf_tb($tb, 60, 20, Constants::TB_GREEN , Constants::TB_DEFAULT, sprintf("hex:     0x%X", $event->getChar()));
    printf_tb($tb, 60, 21, Constants::TB_CYAN  , Constants::TB_DEFAULT, sprintf("octal:   0%o", $event->getChar()));
    printf_tb($tb, 60, 22, Constants::TB_RED   , Constants::TB_DEFAULT, sprintf("string:  %s", $event->getChar()));

    printf_tb($tb, 54, 18, Constants::TB_WHITE, Constants::TB_DEFAULT, "Modifier: ".($event->getMode() ? "TB_MOD_ALT" : "none"));
}
