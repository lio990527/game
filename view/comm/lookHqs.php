<?php 
	$ip = trim($_REQUEST['ip']);
	$duan = trim($_REQUEST['duan']);
	$name = trim($_REQUEST['name']);
	if($ip && $duan && $name){
		$strUrl = "http://{$ip}:{$duan}/?name={$name}&opt=status";
		$strRes = file_get_contents($strUrl);
	}
?>
<html>
<head>
<title>���в鿴</title>
</head>
<body>
<div style="text-align:center;margin-top:50px;">
<h2>���в鿴�����Ǹ���</h2>
<form name="" method="post">
IP:<input type="text" name="ip" value="<?php echo empty($ip) ? '192.168.99.36' : $ip; ?>" size="14"/>&nbsp; &nbsp;
�˿�:<input type="text" name="duan" value="<?php echo empty($duan) ? '1218' : $duan; ?>" size="4"/>&nbsp; &nbsp;
������:<input type="text" name="name" value="<?php echo empty($name) ? 'CTRIP_IMAGE_ZERO_2' : $name; ?>" />
<input type="submit" value="����"/>
</form>
<?php if($strRes){ ?>
�����ǽ��:<br/>
<textarea cols="73" rows="20"><?php echo $strRes;?></textarea>
<?php }?>
</div>
</body>
</html>