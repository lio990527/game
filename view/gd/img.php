<?php
// 打开一幅图像
$img = imagecreatefromgif('http://cdn.jandan.net/wp-content/themes/egg/images/logo-2015.gif');

//$img = imagecreatefrompng('http://99ptoadmin.99ptoimg.com/ico.png');
//$img = imagecreatefromjpeg('source/image/heishitong.jpg');
// 取得一点的颜色
$max_x = imagesx($img);
$max_y = imagesy($img); 
// var_dump($max_x,$max_y);exit;
for ($y = 0; $y < $max_y; $y++){
	for($x = 0; $x < $max_x; $x++){
		$color_index = imagecolorat($img, $x, $y);
		$color_tran[] = imagecolorsforindex($img, $color_index);
	}
}
$w = isset($_REQUEST['w']) ? intval($_REQUEST['w']) : 10;
$l = isset($_REQUEST['l']) ? intval($_REQUEST['l']) : 1;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>像素鸡</title>
<style type="text/css">
	body{margin:0;padding:0}
	td{width:<?php echo $w?>px;height:<?php echo $w?>px;min-width:<?php echo $w?>px;min-height:<?php echo $w?>px;}
</style>
</head>
<body onmousemove="moveIt(event)">
	<div>
		<div style="text-align:center;" id="view">
			<table align="center" border="0" cellpadding="0" cellspacing="<?php echo $l;?>" bgcolor="#FFF" style="display:;">
				<?php 
				for ($index = 0;$index < count($color_tran); $index++){
					if($index % $max_x == 0){
						echo "<tr>";
					}
					
					if($_GET['inverse']){
						$color_tran[$index]['red'] = 255 - $color_tran[$index]['red'];
						$color_tran[$index]['green'] = 255 - $color_tran[$index]['green'];
						$color_tran[$index]['blue'] = 255 - $color_tran[$index]['blue'];
					}else if($_GET['gray']){
						$gray = intval($color_tran[$index]['red'] * 0.2989 + $color_tran[$index]['green'] * 0.5870 + $color_tran[$index]['blue'] * 0.1140);
						$color_tran[$index]['red'] = $gray;
						$color_tran[$index]['green'] = $gray;
						$color_tran[$index]['blue'] = $gray;
					}
					
					echo '<td style="background-color:rgb('."{$color_tran[$index]['red']},{$color_tran[$index]['green']},{$color_tran[$index]['blue']}".')"></td>'."\n";
					
					if(($index+1) % $max_x == 0){
						echo "</tr>";
					}
				}
				?>
			</table>
		</div>
		<div id="qiuDiv" style="position:absolute;width:10px;height:10px;border-radius:5px;background-color:red;left:49%;top:96%;"></div>
		<div id="racquet" style="position:absolute;width:100px;height:15px;background-color:#AAA;border:1px solid black;left:49%;top:97%"></div>
	</div>
</body>

<script type="text/javascript">
$ = function(domId){
	return document.getElementById(domId);
}

function moveIt(event){
	var racquet = $('racquet');
	var recquetStyle = racquet.style;
	//console.log(recquetStyle);return false;
	//console.log(parseInt(recquetStyle.borderLeftWidth));
	var left = parseInt(event.layerX - parseInt(recquetStyle.width)/2);
	if(left >= 0 && (left + parseInt(recquetStyle.width) + parseInt(recquetStyle.borderLeftWidth) + parseInt(recquetStyle.borderRightWidth)) <= window.innerWidth){
		recquetStyle.left = left + "px";
	}
}

window.resize = function(){
	alert(1);
	console.log(window);
}
</script>
</html>

