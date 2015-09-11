<?php

namespace JayTaph\TermBox\Terminal;

class Linux extends Terminal {

    public function getKeys()
    {
        return array("\033[[A","\033[[B","\033[[C","\033[[D","\033[[E","\033[17~","\033[18~","\033[19~","\033[20~","\033[21~","\033[23~","\033[24~","\033[2~","\033[3~","\033[1~","\033[4~","\033[5~","\033[6~","\033[A","\033[B","\033[D","\033[C", 0);
    }

    public function getFunctions()
    {
        return array("", "", "\033[?25h\033[?0c", "\033[?25l\033[?1c", "\033[H\033[J", "\033[0;10m", "\033[4m", "\033[1m", "\033[5m", "\033[7m", "", "", "", "");
    }

    public function getName() {
        return "linux";
    }

}
