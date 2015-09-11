<?php

namespace JayTaph\TermBox;

class Utf8 {

    static public function getLength($c) {
        $utf8_length = array(
            1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,
            1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,
            1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,
            1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,
            1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,
            1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,
            2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,
            3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,4,4,4,4,4,4,4,4,5,5,5,5,6,6,1,1
        );

        if (is_string($c) && strlen($c) != 1) {
            throw new \InvalidArgumentException("Only need a char");
        }

        return $utf8_length[ord($c[0])];
    }

    static function charToUnicode($c) {
        $utf8_mask = array(0x7F, 0x1F, 0x0F, 0x07, 0x03, 0x01);

        $len = self::getLength($c[0]);
        $mask = $utf8_mask[$len-1];

        $result = $c[0] & $mask;
        for ($i=0; $i<$len; $i++) {
            $result <<= 6;
            $result |= $c[$i] & 0x3F;
        }

        return $result;
    }

    static public function unicodeToChar($c) {
        $out = array();

        if ($c < 0x80) {
            $first = 0;
            $len = 1;
        } else if ($c < 0x800) {
            $first = 0xC0;
            $len = 2;
        } else if ($c < 0x10000) {
            $first = 0xE0;
            $len = 3;
        } else if ($c < 0x200000) {
            $first = 0xF0;
            $len = 4;
        } else if ($c < 0x4000000) {
            $first = 0xF8;
            $len = 5;
        } else {
            $first = 0xFC;
            $len = 6;
        }

        for ($i = $len - 1; $i > 0; $i--) {
            array_unshift($out, ($c & 0x3F) | 0x80);
            $c >>= 6;
        }
        array_unshift($out, $c | $first);

        return $out;
    }

}
