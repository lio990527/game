<?php 
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'default';

$img = imagecreatefromgif('http://cdn.jandan.net/wp-content/themes/egg/images/logo-2015.gif');
for ($c = 0; $c < imagecolorstotal($img); $c++) {
	$col = imagecolorsforindex($img, $c);
	
	if($type == '1'){
		$gray = round(0.299 * $col['red'] + 0.587 * $col['green'] + 0.114 * $col['blue']);
		$r = $gray;
		$g = $gray;
		$b = $gray;
	}else if($type == '2'){
		$r = 255 - $col['red'];
		$g = 255 - $col['green'];
		$b = 255 - $col['blue'];
	}else{
		$r = $col['red'];
		$g = $col['green'];
		$b = $col['blue'];
	}
	imagecolorset($img, $c, $r, $g, $b);
}
imagepng($img,"f0.png");

echo "<img src='f0.png'><br/>";
?>