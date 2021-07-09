<?php
// File and new size
$filename = '../source/image/heishitong.jpg';
$filename = '../test/35.png';
$percent = 8;

// Content type
header('Content-Type: image/jpeg');

// Get new sizes
list($width, $height) = getimagesize($filename);
$newwidth = $width * $percent;
$newheight = $height * $percent;

// Load
$thumb = imagecreatetruecolor($newwidth, $newheight);
$bg = imagecolorallocate($thumb, 0, 0, 0);
imagefill($thumb, 0, 0 ,$bg);

// $source = imagecreatefromjpeg($filename);
$source = imagecreatefrompng($filename);

// Resize
imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// Output
imagepng($thumb);
?>