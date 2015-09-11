<?php

namespace JayTaph\TermBox;

final class Constants {

    const TB_KEY_F1               = 0xFFFF;
    const TB_KEY_F2               = 0xFFFE;
    const TB_KEY_F3               = 0xFFFD;
    const TB_KEY_F4               = 0xFFFC;
    const TB_KEY_F5               = 0xFFFB;
    const TB_KEY_F6               = 0xFFFA;
    const TB_KEY_F7               = 0xFFF9;
    const TB_KEY_F8               = 0xFFF8;
    const TB_KEY_F9               = 0xFFF7;
    const TB_KEY_F10              = 0xFFF6;
    const TB_KEY_F11              = 0xFFF5;
    const TB_KEY_F12              = 0xFFF4;
    const TB_KEY_INSERT           = 0xFFF3;
    const TB_KEY_DELETE           = 0xFFF2;
    const TB_KEY_HOME             = 0xFFF1;
    const TB_KEY_END              = 0xFFF0;
    const TB_KEY_PGUP             = 0xFFEF;
    const TB_KEY_PGDN             = 0xFFEE;
    const TB_KEY_ARROW_UP         = 0xFFED;
    const TB_KEY_ARROW_DOWN       = 0xFFEC;
    const TB_KEY_ARROW_LEFT       = 0xFFEB;
    const TB_KEY_ARROW_RIGHT      = 0xFFEA;
    const TB_KEY_MOUSE_LEFT       = 0xFFE9;
    const TB_KEY_MOUSE_RIGHT      = 0xFFE8;
    const TB_KEY_MOUSE_MIDDLE     = 0xFFE7;
    const TB_KEY_MOUSE_RELEASE    = 0xFFE6;
    const TB_KEY_MOUSE_WHEEL_UP   = 0xFFE5;
    const TB_KEY_MOUSE_WHEEL_DOWN = 0xFFE4;

    const TB_KEY_CTRL_TILDE       = 0x00;
    const TB_KEY_CTRL_2           = 0x00;
    const TB_KEY_CTRL_A           = 0x01;
    const TB_KEY_CTRL_B           = 0x02;
    const TB_KEY_CTRL_C           = 0x03;
    const TB_KEY_CTRL_D           = 0x04;
    const TB_KEY_CTRL_E           = 0x05;
    const TB_KEY_CTRL_F           = 0x06;
    const TB_KEY_CTRL_G           = 0x07;
    const TB_KEY_BACKSPACE        = 0x08;
    const TB_KEY_CTRL_H           = 0x08;
    const TB_KEY_TAB              = 0x09;
    const TB_KEY_CTRL_I           = 0x09;
    const TB_KEY_CTRL_J           = 0x0A;
    const TB_KEY_CTRL_K           = 0x0B;
    const TB_KEY_CTRL_L           = 0x0C;
    const TB_KEY_ENTER            = 0x0D;
    const TB_KEY_CTRL_M           = 0x0D;
    const TB_KEY_CTRL_N           = 0x0E;
    const TB_KEY_CTRL_O           = 0x0F;
    const TB_KEY_CTRL_P           = 0x10;
    const TB_KEY_CTRL_Q           = 0x11;
    const TB_KEY_CTRL_R           = 0x12;
    const TB_KEY_CTRL_S           = 0x13;
    const TB_KEY_CTRL_T           = 0x14;
    const TB_KEY_CTRL_U           = 0x15;
    const TB_KEY_CTRL_V           = 0x16;
    const TB_KEY_CTRL_W           = 0x17;
    const TB_KEY_CTRL_X           = 0x18;
    const TB_KEY_CTRL_Y           = 0x19;
    const TB_KEY_CTRL_Z           = 0x1A;
    const TB_KEY_ESC              = 0x1B;
    const TB_KEY_CTRL_LSQ_BRACKET = 0x1B;
    const TB_KEY_CTRL_3           = 0x1B;
    const TB_KEY_CTRL_4           = 0x1C;
    const TB_KEY_CTRL_BACKSLASH   = 0x1C;
    const TB_KEY_CTRL_5           = 0x1D;
    const TB_KEY_CTRL_RSQ_BRACKET = 0x1D;
    const TB_KEY_CTRL_6           = 0x1E;
    const TB_KEY_CTRL_7           = 0x1F;
    const TB_KEY_CTRL_SLASH       = 0x1F;
    const TB_KEY_CTRL_UNDERSCORE  = 0x1F;
    const TB_KEY_SPACE            = 0x20;
    const TB_KEY_BACKSPACE2       = 0x7F;
    const TB_KEY_CTRL_8           = 0x7F;

    const TB_MOD_ALT = 0x01;

    const TB_DEFAULT = 0x00;
    const TB_BLACK   = 0x01;
    const TB_RED     = 0x02;
    const TB_GREEN   = 0x03;
    const TB_YELLOW  = 0x04;
    const TB_BLUE    = 0x05;
    const TB_MAGENTA = 0x06;
    const TB_CYAN    = 0x07;
    const TB_WHITE   = 0x08;

    const TB_BOLD      = 0x0100;
    const TB_UNDERLINE = 0x0200;
    const TB_REVERSE   = 0x0400;

    const TB_EVENT_KEY    = 1;
    const TB_EVENT_RESIZE = 2;
    const TB_EVENT_MOUSE  = 3;


    const TB_HIDE_CURSOR = -1;

    const TB_INPUT_CURRENT = 0;
    const TB_INPUT_ESC     = 1;
    const TB_INPUT_ALT     = 2;
    const TB_INPUT_MOUSE   = 4;

    const TB_OUTPUT_CURRENT   = 0;
    const TB_OUTPUT_NORMAL    = 1;
    const TB_OUTPUT_256       = 2;
    const TB_OUTPUT_216       = 3;
    const TB_OUTPUT_GRAYSCALE = 4;


    const TB_EOF = -1;
}
