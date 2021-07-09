<?php 
require_once '../lib/class/comm/class.file.php';

$ufile = 'user.ini';
$cfile = 'child.ini';
$users = File::getFileInfo("", $ufile, '|');
$child = File::getFileInfo("", $cfile, '|');
$msg = '';
$info = null;
if(!empty($users) && !empty($child)){
	if(!empty($_COOKIE['name'])){
		$info = null;
		foreach ($users as $user) {
			if ($user[0] == $_COOKIE['name']) {
				$info = $user;
				break;
			}
		}
		$info[2] = explode('_', $info[2]);
		$info[3] = explode('_', $info[3]);
		
		if($_REQUEST['chou'] && in_array('0', $info[2], true)){
			$cc = array();
			foreach ($child as $k => $c){
				if($c[1] === '1' || in_array($c[0], $info[3])){
					continue;
				}
				$cc[] = $c[0];
			}
			
			$cz = $cc[array_rand($cc)];
			foreach ($child as $k => $c){
				if($c[0] == $cz){
					$c[1] = 1;
				}
				$child[$k] = implode('|', $c);
			}
			foreach ($info[2] as $k => $ic){
				if($ic === '0'){
					$info[2][$k] = $cz;
					break;
				}
			}
			
			$infonew = $info;
			$infonew[2] = implode('_', $infonew[2]);
			$infonew[3] = implode('_', $infonew[3]);
			
			foreach ($users as $k => $user){
				if($user[0] == $infonew[0]){
					$users[$k] = implode("|", $infonew);
				}else{
					$users[$k] = implode("|", $user);
				}
			}
			File::writeFile("", $ufile, implode("\r\n", $users), true);
			File::writeFile("", $cfile, implode("\r\n", $child), true);
		}
		
		
	}else if (!empty($_REQUEST['name'])) {
		$flag = false;
		foreach ($users as $user) {
			if ($user[0] == $_REQUEST['name'] && $user[1] == $_REQUEST['pass']) {
				$flag = true;
				setcookie('name', $_REQUEST['name']);
				break;
			}
		}
		if (!$flag) {
			$msg = '帐号或密码错误.';
		}else{
			echo "<script>location.reload();</script>";exit;
		}
	}
	
}else{
	exit('功能异常了');
}


?>

<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<head>
<style type="text/css">
	body{margin:10% auto;
    text-align:center;
    font-size:3em;}
    input{font-size:0.9em;}
</style>

</head>
<body>
		<?php if(empty($_COOKIE['name'])){?>
		<form method="post" action="">
		帐号:<input name="name" /><br /><br />  
		密码:<input name="pass" type="password"/><br /><br /> 
		<button style="font-size:1em;width:6em;">登录</button>
		<br /> <label style="font-size:1em; color: red;"><?php if(!empty($msg)){ echo $msg."<br/>";}?></label>
	</form>
		<?php }else{?>
		欢迎:<b style="color: blue"><?php echo $_COOKIE['name'];?></b><br/><br/>
			<?php foreach ($info[2] as $v){?>
				<?php if($v === '0'){?>
				<form method="post"><button name="chou" value="chou" style="font-size:0.9em;">&gt;点击抽取要送礼物的宝贝&lt;</button></form>
				<?php }else{?>
				<span style="font-size:0.7em">你抽中的宝贝是<b style="color:red;font-size:1.5em;"><?php echo $v;?></b>哦,快点给他/她买礼物去吧!</span><br/><br/>
				<?php }?>
			<?php }?>
		<?php }?>
	</body>
</html>