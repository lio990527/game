<?php
// Create a 300x100 image
$im = imagecreatetruecolor(2000, 100);
$red = imagecolorallocate($im, 0xF0, 0xF0, 0xF0);
$black = imagecolorallocate($im, 0x00, 0x00, 0x00);

// Make the background red
imagefill($im, 0, 0, $red);

// Path to our ttf font file
$font_file = 'DENG.TTF';
$chrOther = "§‰∑■◆▲●★【】‘’“”。，、；：？！·『』．〖〗【】☆○●◎◇◆□■△▲※〓＃＆＠±≡≈≠≤≥≮≯∷÷×－＋∞∝∏∈∵∴⊥⊙≌√⊥∧∨℃￠¤￥＄￡αβγδεζηθικλμνξοπρστυφχψωΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩабвгдеёжзийклмнопрстуфхцчшщъыэьюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";

// Draw the text 'PHP Manual' using font size 13
imagefttext($im, 5, 0, 105, 55, $black, $font_file, $chrOther);

// Output image to the browser
header('Content-Type: image/png');

imagepng($im);
imagedestroy($im);
?>