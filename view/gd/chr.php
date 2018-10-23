<?php 
if(!empty($_POST) || !empty($_FILES['imgPath'])){
	$imgUrl = trim($_POST['imgUrl']);
	$imgType = '';
	if(!empty($_FILES['imgPath']['tmp_name'])){
		$imgUrl = $_FILES['imgPath']['tmp_name'];
	}
	
	$file = getimagesize($imgUrl);
	$imgType = $file[2];
	
	if(!$file){
		echo '1';exit;
	}
	
	$img = loadImgByType($imgUrl, $imgType);
	if(!$img){
		echo '2';exit;
	}
	$maxSize = 120;
	if($file[0] > $maxSize || $file[1] > $maxSize){
		$srate = max($file[0], $file[1])/$maxSize;
		$width = $file[0]/$srate;
		$height = $file[1]/$srate;
		$imgnew = imagecreatetruecolor($width, $height);
		imagefill($imgnew, 0, 0, imagecolorallocate($imgnew, 255, 255, 255));
		imagecopyresized($imgnew, $img, 0, 0, 0, 0, $width, $height, $file[0], $file[1]);
		$img = $imgnew;
// 		header('Content-Type:image/png');
// 		imagepng($imgnew);exit;
	}
	
	$chrs = array(' ','.','^','"','~',':','+','=','v','c','s','o','*','&','%','A','8','#','$','@');
	$max_x = imagesx($img);
	$max_y = imagesy($img);
	
	$rate = isset($_POST['rate']) ? intval($_POST['rate']) : 10;
	$size = round($rate/5);
	
	$new_x = $max_x * $rate;
	$new_y = $max_y * $rate;
	
	$imchr = imagecreate($new_x, $new_y);
	$bg = imagecolorallocate($imchr, 255, 255, 255);
	
	$textcolor = imagecolorallocate($imchr, 0, 0, 0);
	$ffile = 'font/MSYH.TTC';
	for ($y = 0; $y < $max_y; $y++){
		for($x = 0; $x < $max_x; $x++){
			$col = imagecolorsforindex($img, imagecolorat($img, $x, $y));
			$index = intval((1 - round(0.2989 * $col['red'] + 0.5870 * $col['green'] + 0.1140 * $col['blue'])/255)*(count($chrs)-1));
// 			imagestring($imchr, $size, $x*$rate, $y*$rate, $chrs[$index], $textcolor);
			
			imagefttext($imchr, $rate-1, 0, $x*$rate, $y*$rate+$rate, $textcolor, $ffile, $chrs[$index]);
		}
	}
	
	ob_start();
	imagepng($imchr);
	$image_data = ob_get_contents();
	ob_end_clean();
	$strImg = base64_encode ($image_data);
	echo "data:image/png;base64,{$strImg}";exit;
	
// 	header('Content-Type:image/png');
// 	imagepng($imchr);exit;
}

function loadImgByType($imgUrl, $intType) {
	switch ($intType) {
		case 1:
			return imagecreatefromgif($imgUrl);
			break;
		case 2:
			return imagecreatefromjpeg($imgUrl);
			break;
		case 3:
			return imagecreatefrompng($imgUrl);
			break;
		default:
			return false;
			break;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>图片字符化</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script type="text/javascript" src="/source/js/jquery-1.7.2.min.js"></script>
</head>
<body>
	<div>
		<form method="post" id="form" enctype="multipart/form-data">
		<select name="type">
			<option value="1">在线图片</option>
			<option value="2">本地上传</option>
		</select>
		<span class="imgUrl">图片地址:<input name="imgUrl" style="width:383px;"/></span>
		<span class="imgPath" style="display:none;">图片路径:<input name="imgPath" type="file" style="width:383px;background-color:#FBFBFB;"/></span>
		倍率:<select name="rate" onchange="createImgByChr()">
			<option>5</option>
			<option>6</option>
			<option>7</option>
			<option>8</option>
			<option>9</option>
			<option>10</option>
			<option>11</option>
			<option>12</option>
			<option selected>13</option>
		</select>
		<input onclick="createImgByChr()" type="button" value="生成"/>
<!-- 		<input type="submit" value="生成"/> -->
		</form>
	</div>
	<div>
		<img id="chrImg" style="border:1px solid #CCC;margin-top:5px;"/>
	</div>
</body>
<script type="text/javascript">
	$(function(){
		$('select[name="type"]').change(function(){
			if(this.value == '1'){
				$('span.imgPath').hide();
				$('span.imgPath').find('input').val('');
				$('span.imgUrl').show();
			}else{
				$('span.imgUrl').hide();
				$('span.imgUrl').find('input').val('');
				$('span.imgPath').show();
			}
		});

		$('#chrImg').load(function(){
			console.log(window);
			console.log(window.innerWidth);
			console.log(parseInt($(this).css('width')) +'_'+ parseInt(window.innerWidth));
			if(parseInt($(this).css('width')) > parseInt(window.innerWidth)){
				this.style.width = '100%';
			}else{
				this.style.width = '';
			}
		});
		$('#chrImg').click(function(){
			var objA = document.createElement('a');
			objA.target = '_blank';
			objA.href = this.src;
			document.body.appendChild(objA);
			objA.click();
			document.body.removeChild(objA);
		});
	});
	function createImgByChr(){
		var form = new FormData(document.getElementById('form'));
		console.log(form);

		$.ajax({
            url:"",
            type:"post",
            data:form,
            processData:false,
            contentType:false,
            success:function(data){
            	console.log(data);
            	var img = document.getElementById('chrImg');
            	img.src = data;
            	img.style.display = 'block';
            },
            error:function(e){
                alert("错误！！");
            }
        });        
	}
</script>
</html>