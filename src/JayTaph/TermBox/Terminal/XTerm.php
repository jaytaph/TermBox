<?php

namespace JayTaph\TermBox\Terminal;

class XTerm extends Terminal {

    public function getKeys()
    {
        return array("\033OP","\033OQ","\033OR","\033OS","\033[15~","\033[17~","\033[18~","\033[19~","\033[20~","\033[21~","\033[23~","\033[24~","\033[2~","\033[3~","\033OH","\033OF","\033[5~","\033[6~","\033OA","\033OB","\033OD","\033OC", 0);
    }

    public function getFunctions()
    {
        return array("\033[?1049h", "\033[?1049l", "\033[?12l\033[?25h", "\033[?25l", "\033[H\033[2J", "\033(B\033[m", "\033[4m", "\033[1m", "\033[5m", "\033[7m", "\033[?1h\033=", "\033[?1l\033>", "\033[?1000h", "\033[?1000l");
    }

    public function getName() {
        return "xterm";
    }

}
