<?php

namespace JayTaph\TermBox;

class WcWidth {

    protected $nonSpaceTable = array(
        array(0x0300, 0x034E), array(0x0360, 0x0362), array(0x0483, 0x0486),
        array(0x0488, 0x0489), array(0x0591, 0x05A1), array(0x05A3, 0x05B9),
        array(0x05BB, 0x05BD), array(0x05BF, 0x05BF), array(0x05C1, 0x05C2),
        array(0x05C4, 0x05C4), array(0x064B, 0x0655), array(0x0670, 0x0670),
        array(0x06D6, 0x06E4), array(0x06E7, 0x06E8), array(0x06EA, 0x06ED),
        array(0x070F, 0x070F), array(0x0711, 0x0711), array(0x0730, 0x074A),
        array(0x07A6, 0x07B0), array(0x0901, 0x0902), array(0x093C, 0x093C),
        array(0x0941, 0x0948), array(0x094D, 0x094D), array(0x0951, 0x0954),
        array(0x0962, 0x0963), array(0x0981, 0x0981), array(0x09BC, 0x09BC),
        array(0x09C1, 0x09C4), array(0x09CD, 0x09CD), array(0x09E2, 0x09E3),
        array(0x0A02, 0x0A02), array(0x0A3C, 0x0A3C), array(0x0A41, 0x0A42),
        array(0x0A47, 0x0A48), array(0x0A4B, 0x0A4D), array(0x0A70, 0x0A71),
        array(0x0A81, 0x0A82), array(0x0ABC, 0x0ABC), array(0x0AC1, 0x0AC5),
        array(0x0AC7, 0x0AC8), array(0x0ACD, 0x0ACD), array(0x0B01, 0x0B01),
        array(0x0B3C, 0x0B3C), array(0x0B3F, 0x0B3F), array(0x0B41, 0x0B43),
        array(0x0B4D, 0x0B4D), array(0x0B56, 0x0B56), array(0x0B82, 0x0B82),
        array(0x0BC0, 0x0BC0), array(0x0BCD, 0x0BCD), array(0x0C3E, 0x0C40),
        array(0x0C46, 0x0C48), array(0x0C4A, 0x0C4D), array(0x0C55, 0x0C56),
        array(0x0CBF, 0x0CBF), array(0x0CC6, 0x0CC6), array(0x0CCC, 0x0CCD),
        array(0x0D41, 0x0D43), array(0x0D4D, 0x0D4D), array(0x0DCA, 0x0DCA),
        array(0x0DD2, 0x0DD4), array(0x0DD6, 0x0DD6), array(0x0E31, 0x0E31),
        array(0x0E34, 0x0E3A), array(0x0E47, 0x0E4E), array(0x0EB1, 0x0EB1),
        array(0x0EB4, 0x0EB9), array(0x0EBB, 0x0EBC), array(0x0EC8, 0x0ECD),
        array(0x0F18, 0x0F19), array(0x0F35, 0x0F35), array(0x0F37, 0x0F37),
        array(0x0F39, 0x0F39), array(0x0F71, 0x0F7E), array(0x0F80, 0x0F84),
        array(0x0F86, 0x0F87), array(0x0F90, 0x0F97), array(0x0F99, 0x0FBC),
        array(0x0FC6, 0x0FC6), array(0x102D, 0x1030), array(0x1032, 0x1032),
        array(0x1036, 0x1037), array(0x1039, 0x1039), array(0x1058, 0x1059),
        array(0x1160, 0x11FF), array(0x17B7, 0x17BD), array(0x17C6, 0x17C6),
        array(0x17C9, 0x17D3), array(0x180B, 0x180E), array(0x18A9, 0x18A9),
        array(0x200B, 0x200F), array(0x202A, 0x202E), array(0x206A, 0x206F),
        array(0x20D0, 0x20E3), array(0x302A, 0x302F), array(0x3099, 0x309A),
        array(0xFB1E, 0xFB1E), array(0xFE20, 0xFE23), array(0xFEFF, 0xFEFF),
        array(0xFFF9, 0xFFFB),
    );

    protected function biSearch($c, $nonSpaceTable) {
        $min = 0;
        $max = count($nonSpaceTable) - 1;

        if ($c < $nonSpaceTable[0][0] || $c > $nonSpaceTable[$max][1]) {
            return 0;
        }

        while ($max >= $min) {
            $mid = floor(($min + $max) / 2);
            if ($c > $nonSpaceTable[$mid][1]) {
                $min = $mid + 1;
            } else if ($c < $nonSpaceTable[$mid][0]) {
                $max = $mid - 1;
            } else {
                return 1;
            }
        }

        return 0;
    }

    public function getWidth($c) {
        if ($c == 0) return 0;

        if ($c < 32 || ($c >= 0x7F && $c < 0xA0)) {
            return -1;
        }

        if ($this->biSearch($c, $this->nonSpaceTable)) {
            return 0;
        }

        return 1 +
            ($c >= 0x1100 &&
                ($c <= 0x115F ||
                ($c >= 0x2E80 && $c <= 0xA4CF && ($c & ~0x0011) != 0x300A && $c != 0x303F) ||
                ($c >= 0xAC00 && $c <= 0xD7A3) ||
                ($c >= 0xf900 && $c <= 0xfaff) ||
                ($c >= 0xfe30 && $c <= 0xfe6f) ||
                ($c >= 0xff00 && $c <= 0xff5f) ||
                ($c >= 0xffe0 && $c <= 0xffe6) ||
                ($c >= 0x20000 && $c <= 0x2ffff)));
    }
}
