<?php

namespace JayTaph\TermBox;

/*
 *  Cell Value Object
 */

class Cell {
    protected $ch;      // Char
    protected $fg;      // Foreground color
    protected $bg;      // Background color

    function __construct($ch, $fg, $bg)
    {
        // Convert characters to actual numerical values
        if (is_string($ch)) {
            $ch = ord($ch[0]);
        }

        $this->ch = $ch;
        $this->fg = $fg;
        $this->bg = $bg;
    }

    /**
     * @return mixed
     */
    public function getBg()
    {
        return $this->bg;
    }

    /**
     * @return mixed
     */
    public function getCh()
    {
        return $this->ch;
    }

    /**
     * @return mixed
     */
    public function getFg()
    {
        return $this->fg;
    }

    public function equals(Cell $dst) {
        return (
            $this->ch == $dst->getCh() &&
            $this->fg == $dst->getFg() &&
            $this->bg == $dst->getBg()
        );
    }

}
