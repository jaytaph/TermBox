<?php

use JayTaph\TermBox\Constants;
use JayTaph\TermBox\TermBox;

require_once "vendor/autoload.php";


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


//       struct key K_TAB[] = {{1,6,'T'},{2,6,'A'},{3,6,'B'},STOP};
//       struct key K_q[] = {{6,6,'q'},STOP};
//       struct key K_Q[] = {{6,6,'Q'},STOP};
//       struct key K_w[] = {{9,6,'w'},STOP};
//       struct key K_W[] = {{9,6,'W'},STOP};
//       struct key K_e[] = {{12,6,'e'},STOP};
//       struct key K_E[] = {{12,6,'E'},STOP};
//       struct key K_r[] = {{15,6,'r'},STOP};
//       struct key K_R[] = {{15,6,'R'},STOP};
//       struct key K_t[] = {{18,6,'t'},STOP};
//       struct key K_T[] = {{18,6,'T'},STOP};
//       struct key K_y[] = {{21,6,'y'},STOP};
//       struct key K_Y[] = {{21,6,'Y'},STOP};
//       struct key K_u[] = {{24,6,'u'},STOP};
//       struct key K_U[] = {{24,6,'U'},STOP};
//       struct key K_i[] = {{27,6,'i'},STOP};
//       struct key K_I[] = {{27,6,'I'},STOP};
//       struct key K_o[] = {{30,6,'o'},STOP};
//       struct key K_O[] = {{30,6,'O'},STOP};
//       struct key K_p[] = {{33,6,'p'},STOP};
//       struct key K_P[] = {{33,6,'P'},STOP};
//       struct key K_LSQB[] = {{36,6,'['},STOP};
//       struct key K_LCUB[] = {{36,6,'{'},STOP};
//       struct key K_RSQB[] = {{39,6,']'},STOP};
//       struct key K_RCUB[] = {{39,6,'}'},STOP};
//       struct key K_ENTER[] = {
//       	{43,6,0x2591},{44,6,0x2591},{45,6,0x2591},{46,6,0x2591},
//       	{43,7,0x2591},{44,7,0x2591},{45,7,0x21B5},{46,7,0x2591},
//       	{41,8,0x2591},{42,8,0x2591},{43,8,0x2591},{44,8,0x2591},
//       	{45,8,0x2591},{46,8,0x2591},STOP
//       };
//       struct key K_DEL[] = {{50,6,'D'},{51,6,'E'},{52,6,'L'},STOP};
//       struct key K_END[] = {{54,6,'E'},{55,6,'N'},{56,6,'D'},STOP};
//       struct key K_PGD[] = {{58,6,'P'},{59,6,'G'},{60,6,'D'},STOP};
//       struct key K_K_7[] = {{65,6,'7'},STOP};
//       struct key K_K_8[] = {{68,6,'8'},STOP};
//       struct key K_K_9[] = {{71,6,'9'},STOP};
//       struct key K_K_PLUS[] = {{74,6,' '},{74,7,'+'},{74,8,' '},STOP};
//
//       struct key K_CAPS[] = {{1,8,'C'},{2,8,'A'},{3,8,'P'},{4,8,'S'},STOP};
//       struct key K_a[] = {{7,8,'a'},STOP};
//       struct key K_A[] = {{7,8,'A'},STOP};
//       struct key K_s[] = {{10,8,'s'},STOP};
//       struct key K_S[] = {{10,8,'S'},STOP};
//       struct key K_d[] = {{13,8,'d'},STOP};
//       struct key K_D[] = {{13,8,'D'},STOP};
//       struct key K_f[] = {{16,8,'f'},STOP};
//       struct key K_F[] = {{16,8,'F'},STOP};
//       struct key K_g[] = {{19,8,'g'},STOP};
//       struct key K_G[] = {{19,8,'G'},STOP};
//       struct key K_h[] = {{22,8,'h'},STOP};
//       struct key K_H[] = {{22,8,'H'},STOP};
//       struct key K_j[] = {{25,8,'j'},STOP};
//       struct key K_J[] = {{25,8,'J'},STOP};
//       struct key K_k[] = {{28,8,'k'},STOP};
//       struct key K_K[] = {{28,8,'K'},STOP};
//       struct key K_l[] = {{31,8,'l'},STOP};
//       struct key K_L[] = {{31,8,'L'},STOP};
//       struct key K_SEMICOLON[] = {{34,8,';'},STOP};
//       struct key K_PARENTHESIS[] = {{34,8,':'},STOP};
//       struct key K_QUOTE[] = {{37,8,'\''},STOP};
//       struct key K_DOUBLEQUOTE[] = {{37,8,'"'},STOP};
//       struct key K_K_4[] = {{65,8,'4'},STOP};
//       struct key K_K_5[] = {{68,8,'5'},STOP};
//       struct key K_K_6[] = {{71,8,'6'},STOP};
//
//       struct key K_LSHIFT[] = {{1,10,'S'},{2,10,'H'},{3,10,'I'},{4,10,'F'},{5,10,'T'},STOP};
//       struct key K_z[] = {{9,10,'z'},STOP};
//       struct key K_Z[] = {{9,10,'Z'},STOP};
//       struct key K_x[] = {{12,10,'x'},STOP};
//       struct key K_X[] = {{12,10,'X'},STOP};
//       struct key K_c[] = {{15,10,'c'},STOP};
//       struct key K_C[] = {{15,10,'C'},STOP};
//       struct key K_v[] = {{18,10,'v'},STOP};
//       struct key K_V[] = {{18,10,'V'},STOP};
//       struct key K_b[] = {{21,10,'b'},STOP};
//       struct key K_B[] = {{21,10,'B'},STOP};
//       struct key K_n[] = {{24,10,'n'},STOP};
//       struct key K_N[] = {{24,10,'N'},STOP};
//       struct key K_m[] = {{27,10,'m'},STOP};
//       struct key K_M[] = {{27,10,'M'},STOP};
//       struct key K_COMMA[] = {{30,10,','},STOP};
//       struct key K_LANB[] = {{30,10,'<'},STOP};
//       struct key K_PERIOD[] = {{33,10,'.'},STOP};
//       struct key K_RANB[] = {{33,10,'>'},STOP};
//       struct key K_SLASH[] = {{36,10,'/'},STOP};
//       struct key K_QUESTION[] = {{36,10,'?'},STOP};
//       struct key K_RSHIFT[] = {{42,10,'S'},{43,10,'H'},{44,10,'I'},{45,10,'F'},{46,10,'T'},STOP};
//       struct key K_ARROW_UP[] = {{54,10,'('},{55,10,0x2191},{56,10,')'},STOP};
//       struct key K_K_1[] = {{65,10,'1'},STOP};
//       struct key K_K_2[] = {{68,10,'2'},STOP};
//       struct key K_K_3[] = {{71,10,'3'},STOP};
//       struct key K_K_ENTER[] = {{74,10,0x2591},{74,11,0x2591},{74,12,0x2591},STOP};
//
//       struct key K_LCTRL[] = {{1,12,'C'},{2,12,'T'},{3,12,'R'},{4,12,'L'},STOP};
//       struct key K_LWIN[] = {{6,12,'W'},{7,12,'I'},{8,12,'N'},STOP};
//       struct key K_LALT[] = {{10,12,'A'},{11,12,'L'},{12,12,'T'},STOP};
//       struct key K_SPACE[] = {
//       	{14,12,' '},{15,12,' '},{16,12,' '},{17,12,' '},{18,12,' '},
//       	{19,12,'S'},{20,12,'P'},{21,12,'A'},{22,12,'C'},{23,12,'E'},
//       	{24,12,' '},{25,12,' '},{26,12,' '},{27,12,' '},{28,12,' '},
//       	STOP
//       };
//       struct key K_RALT[] = {{30,12,'A'},{31,12,'L'},{32,12,'T'},STOP};
//       struct key K_RWIN[] = {{34,12,'W'},{35,12,'I'},{36,12,'N'},STOP};
//       struct key K_RPROP[] = {{38,12,'P'},{39,12,'R'},{40,12,'O'},{41,12,'P'},STOP};
//       struct key K_RCTRL[] = {{43,12,'C'},{44,12,'T'},{45,12,'R'},{46,12,'L'},STOP};
//       struct key K_ARROW_LEFT[] = {{50,12,'('},{51,12,0x2190},{52,12,')'},STOP};
//       struct key K_ARROW_DOWN[] = {{54,12,'('},{55,12,0x2193},{56,12,')'},STOP};
//       struct key K_ARROW_RIGHT[] = {{58,12,'('},{59,12,0x2192},{60,12,')'},STOP};
//       struct key K_K_0[] = {{65,12,' '},{66,12,'0'},{67,12,' '},{68,12,' '},STOP};
//       struct key K_K_PERIOD[] = {{71,12,'.'},STOP};
//);


$tb = new TermBox();
$tb->selectInputMode(Constants::TB_INPUT_ESC | Constants::TB_INPUT_MOUSE);

$tb->clear();
//$tb->present();

draw_keyboard($tb);
$tb->present();

sleep(3);

print "Done\n";
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
