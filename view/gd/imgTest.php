<?php
$pathFile = 'style/song/';
$dp = dir($pathFile);
while($file = $dp->read()){
	if(is_dir($file)){
		continue;
	}
// 	$list[] = $file;
	$img = imagecreatefrompng($pathFile.$file);
	$max_x = imagesx($img);
	$max_y = imagesy($img);
	for ($y = 0; $y < $max_y; $y++){
		for($x = 0; $x < $max_x; $x++){
			$name = substr($file, 0, strpos($file, '.'));
			$list[$name] += imagecolorat($img, $x, $y);
		}
	}
}
ksort($list);
asort($list);
var_dump($list);
$string = implode(',', array_keys($list));
$file = fopen('style/song.txt', 'w');
fwrite($file, $string);
exit;

$ffile = "font/MSYH.TTC";
for($i = 32; $i < 127; $i++){
	$x = 0;
	$y = 10;
	$im = @imagecreate(13, 13);
	$bg = imagecolorallocate($im, 255, 255, 255);
	$textcolor = imagecolorallocate($im, 0, 0, 0);
// 	imagestring($im, 2, $x, $y, chr($i), $textcolor);
	imagefttext($im, 8, 0, $x, $y, $textcolor, $ffile, chr($i));
// 	imagepng($im);
	imagepng($im,$pathFile.$i.'.png');
	// 	break;
}
$ochr = '☆○◇□※±≈≠∷÷×∵∴⊥⊙√∧∨￡';
for ($i = 0; $i < mb_strlen($ochr, 'utf8'); $i++) {
	$x = 0;
	$y = 10;
	$char = mb_substr($ochr, $i, 1, 'utf8');
	$im = @imagecreate(13, 13);
	$bg = imagecolorallocate($im, 255, 255, 255);
	$textcolor = imagecolorallocate($im, 0, 0, 0);
// 	imagestring($im, 2, $x, $y, chr($i), $textcolor);
	imagefttext($im, 8, 0, $x, $y, $textcolor, $ffile, $char);
// 	imagepng($im);
	imagepng($im,$pathFile.'oc'.$i.'.png');
}
exit;
$im = imagecreatetruecolor(1300, 900);
$bgcolor = imagecolorallocate($im, 0xF0, 0xF0, 0xF0);
$fcolor = imagecolorallocate($im, 0x00, 0x00, 0x00);
imagefill($im, 0, 0, $bgcolor);
$ffile = 'font/ARIAL.TTF';
$ffile = 'font/MSYH.TTC';

$chrOther = "§‰∑■◆▲●★【】‘’“”。，、；：？！·『』．〖〗【】☆○●◎◇◆□■△▲※〓＃＆＠±≡≈≠≤≥≮≯∷÷×－＋∞∝∏∈∵∴⊥⊙≌√⊥∧∨℃￠¤￥＄￡αβγδεζηθικλμνξοπρστυφχψωΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩабвгдеёжзийклмнопрстуфхцчшщъыэьюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";

imagefttext($im, 8, 0, 0, 10, $fcolor, $ffile, $chrOther);
for($i = 32; $i < 127; $i++){
	imagefttext($im, 8, 0, ($i-32)*13, 23, $fcolor, $ffile, chr($i));
	imagestring($im, 2, ($i-32)*13, 23, chr($i), $fcolor);
}
header("Content-type: image/png");
imagepng($im);
imagedestroy($im);
exit;


// 白色背景和蓝色文本
$chrOther = "§‰∑■◆▲●★【】‘’“”。，、；：？！·『』．〖〗【】☆○●◎◇◆□■△▲※〓＃＆＠±≡≈≠≤≥≮≯∷÷×－＋∞∝∏∈∵∴⊥⊙≌√⊥∧∨℃￠¤￥＄￡αβγδεζηθικλμνξοπρστυφχψωΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩабвгдеёжзийклмнопрстуфхцчшщъыэьюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";
// $chrOther = "§‰∑";

$im = @imagecreate(2000, 130);
$bg = imagecolorallocate($im, 255, 255, 255);
$textcolor = imagecolorallocate($im, 0, 0, 0);
$rate = 13;
for($i=0;$i<mb_strlen($chrOther, 'utf8');$i++){
	$char = mb_substr($chrOther, $i, 1, 'utf8');
// 	var_dump($char.'_'.ord($char));

	imagestring($im, 2, $i*$rate, 0, $char, $textcolor);
}
imagepng($im);
header("Content-type: image/png");
exit;
for ($i=0;$i<1000;$i++){
	var_dump($i.'_'.chr(base_convert($i,10,16)));
}
exit;
$res = array();
for ($i = 32;$i<127;$i++){
	echo chr($i);
	$img = imagecreatefrompng('../test/'.$i.'.png');
	$max_x = imagesx($img);
	$max_y = imagesy($img);
	for ($y = 0; $y < $max_y; $y++){
		for($x = 0; $x < $max_x; $x++){
			$res[$i.chr($i)] += imagecolorat($img, $x, $y);
		}
	}
}
//asort($res);

$r = array_flip($res);
ksort($r);
var_dump($res,$r,count(array_flip($r)));
exit;
$str= '';
for($i = 32; $i < 127; $i++){
	$x = 4;
	$y = 0;
	$im = @imagecreate(13, 13);
	$bg = imagecolorallocate($im, 255, 255, 255);
	$textcolor = imagecolorallocate($im, 0, 0, 0);
	imagestring($im, 2, $x, $y, chr($i), $textcolor);
// 	imagepng($im);
	imagepng($im,'../test/'.$i.'.png');
// 	break;
}


// 把字符串写在图像左上角

// 输出图像
// header("Content-type: image/png");

exit;

$img = imagecreatefromgif('http://cdn.jandan.net/wp-content/themes/egg/images/logo-2015.gif');
$max_x = imagesx($img);
$max_y = imagesy($img);

$im = imagecreate($max_x, $max_y);
$bg = imagecolorallocate($im, 255, 255, 255);
// var_dump($max_x,$max_y);exit;
for ($y = 0; $y < $max_y; $y++){
	for($x = 0; $x < $max_x; $x++){
		$color_index = imagecolorat($img, $x, $y);
		$color_tran = imagecolorsforindex($img, $color_index);
		$r = 255 - $color_tran['red'];
		$g = 255 - $color_tran['green'];
		$b = 255 - $color_tran['blue'];
		
		$color_index2 = imagecolorat($im, $x, $y);
		imagecolorset($im, $color_index2, $r, $g, $b);
	}
}
header("Content-type: image/png");
imagepng($im);
exit;
$indexs = imagecolorstotal($img);
var_dump($img,$indexs);exit;

?>