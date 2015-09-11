<?php

namespace JayTaph\TermBox;

use JayTaph\TermBox\Terminal\Terminal;
use JayTaph\TermBox\Terminal\TerminalInterface;

class ByteBuffer {

    protected $buf;

    /** @var Terminal */
    protected $terminal;

    public function __construct($cap, TerminalInterface $terminal = null) {
        $this->buf = "";
        $this->terminal = $terminal;
    }

    public function clear() {
        $this->buf = "";
    }

    public function append($data) {
        if (is_integer($data)) {
            $data = chr($data);
        }
        $this->buf .= $data;
    }

    public function puts($data) {
        if (! is_array($data)) {
            $data = array($data);
        }

        foreach ($data as $item) {
            $this->append($item);
        }
    }

    public function flush($fd) {
        fwrite($fd, $this->buf, strlen($this->buf));
        $this->clear();
    }

    public function truncate($n) {
        $this->buf = substr($this->buf, 0, $n);
    }

    public function putsFunc($func) {
        if ($this->terminal) {
            $this->puts($this->terminal->getFunction($func));
        }
    }

    public function getLength() {
        return strlen($this->buf);
    }

    public function getBuffer() {
        return $this->buf;
    }
}
