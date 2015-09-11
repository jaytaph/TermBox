<?php

namespace JayTaph\TermBox;

class CellBuffer {

    function __construct($w, $h, $fg, $bg)
    {
        $this->cells = array();
        $this->width = $w;
        $this->height = $h;

        $this->clear($fg, $bg);
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    public function clear($fg, $bg) {
        $this->cells = array();

        // Create one cell, and just reference it
        $empty = new Cell(' ', $fg, $bg);

        for ($i = 0; $i != $this->width * $this->height; $i++) {
            $this->cells[] = $empty;
        }
    }

    public function resize($w, $h, $fg, $bg) {
        if ($this->width = $w && $this->height == $h) {
            return;
        }

        $old = clone $this;

        $this->width = $w;
        $this->height = $h;
        $this->cells = array();
        $this->clear($fg, $bg);

        for ($y = 0; $y!=$this->getHeight(); $y++) {
            for ($x = 0; $x!=$this->getWidth(); $x++) {
                $this->setCell($x, $y, $old->getCell($x, $y));
            }
        }
    }

    public function setCell($x, $y, Cell $cell) {
        $this->cells[$y * $this->getWidth() + $x] = $cell;
    }

    /**
     * @param $x
     * @param $y
     * @return Cell
     */
    public function getCell($x, $y) {
        return $this->cells[$y * $this->getWidth() + $x];
    }

}
