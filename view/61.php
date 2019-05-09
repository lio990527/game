<?php 
require '../lib/class/comm/class.file.php';
$path = '../lib/conf';
$file = 'mothers.json';
$mothers = File::getContent($path, $file);
$mothers = empty($mothers) ? [] : json_decode($mothers, true);
if(isset($_COOKIE['name'])){
	$mother = $mothers[$_COOKIE['name']];
}
if(isset($_REQUEST['name']) && !isset($_COOKIE['name'])){
	$name = $_REQUEST['name'];
	if(isset($mothers[$name])){
		if (isset($_REQUEST['word']) && !empty($_REQUEST['word'])) {
			if(trim($_REQUEST['word']) !== $mothers[$name]['pass']){
				echo '<script>alert("密码错误");history.go(-1)</script>';
			}else{
				setcookie('name', $name);
				header("Location:{$_SERVER['REQUEST_URI']}");
			}
		} elseif (isset($_REQUEST['pass'])) {
			if (! empty($_REQUEST['pass'])) {
				$mothers[$name]['pass'] = $_REQUEST['pass'];
				setcookie('name', $name);
				File::writeFile($path, $name, json_encode($mothers), true);
			} else {
				$_REQUEST['action'] = 'reg';
			}
		} else {
			$_REQUEST['action'] = 'login';
		}
	} elseif (! empty($_REQUEST['name'])) {
		$_REQUEST['action'] = 'reg';
		$mothers[$name] = [];
		File::writeFile($path, $name, json_encode($mothers), true);
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>宝宝的六一</title>
<style>
.lg-component-img {
	background-repeat: no-repeat;
	background-position: center center;
	box-sizing: border-box;
	vertical-align: top;
	position: relative;
	left: 0;
	top: 0
}

.lg-component-img-mask {
	position: absolute;
	overflow: hidden
}

.lg-component-img-border-item {
	width: 100%;
	height: 100%;
	box-sizing: border-box;
	overflow: hidden;
	position: relative
}

.lg-component-label {
	font-size: 24px;
	color: #000;
	line-height: 1.2;
	box-sizing: content-box;
	font-family: "Helvetica Neue", Helvetica, STHeiTi, sans-serif
}

.lg-component-label, .lg-component-label a, .lg-component-label font,
	.lg-component-label p, .lg-component-label span {
	-webkit-font-smoothing: antialiased;
	-webkit-text-stroke-width: .2px;
	-moz-osx-font-smoothing: grayscale
}

.lg-component-label div, .lg-component-label p {
	margin: 0
}

.lg-component-input {
	margin: 0;
	display: block;
	padding: 0 5px;
	box-sizing: border-box;
	overflow: visible;
	border: 1px solid #ced4da;
	transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out
}

.lg-component-input:focus {
	color: #7b8a8b;
	border-color: #2196F3 !important;
	outline: 0;
	box-shadow: inset 0 0 0 .15rem rgba(33, 149, 243, .301)
}

.lg-backface, .lg-page {
	background-size: cover !important
}

body, html {
	width: 100%;
	height: 100%
}

article, body, div, h1, h2, h3, h4, h5, html, p, section {
	margin: 0;
	padding: 0;
	outline: 0;
	-webkit-tap-highlight-color: transparent
}

.lg-icon {
	position: absolute;
	width: 0;
	height: 0;
	left: -50px;
	z-index: -1
}

.lg-backface, .lg-loading-page {
	pointer-events: none;
	height: 100%
}

.lg-container, .lg-page-container {
	width: 100%;
	min-height: 500px;
	position: relative;
	z-index: 1
}

.lg-container {
	overflow: hidden;
	margin: 0 auto;
	padding: 0;
	font-family: "Hiragino Sans GB", "Microsoft YaHei",
		"WenQuanYi Micro Hei", Arial, sans-serif;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	-webkit-text-size-adjust: 100%;
	opacity: 0
}

.lg-container.ready {
	opacity: 1
}

.lg-page-container {
	overflow: visible
}

.lg-back-stage, .lg-front-stage {
	overflow: visible;
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	pointer-events: none
}

.lg-back-stage .lg-surface, .lg-front-stage .lg-surface {
	pointer-events: all
}

.lg-back-stage {
	z-index: 0
}

.lg-front-stage {
	z-index: 2
}

.lg-loading-page {
	z-index: 3;
	position: absolute;
	top: 0;
	left: 0;
	width: 100%
}

.lg-page {
	width: 100%;
	height: 100%;
	position: relative;
	overflow: hidden
}

.lg-backface {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%
}

.lg-surface {
	overflow: hidden;
	box-sizing: content-box
}

.lg-surface, .lg-trailer {
	position: absolute;
	z-index: 1
}

.lg-container .lg-back-stage {
	background-color: #FFF
}

.lg-container.strip .lg-back-stage, .lg-container.strip .lg-front-stage
	{
	position: fixed
}

.lg-container.strip.pc {
	height: 100%
}

.lg-container.strip.pc .lg-page-container {
	height: 100%;
	overflow-x: hidden;
	overflow-y: scroll
}

.lg-container.multi {
	height: 100%;
	background-color: #000
}

.lg-container.multi .lg-page-container {
	height: 100%
}

.lg-container.expand {
	height: 100%;
	max-height: 100%;
	overflow: scroll
}

</style>
<script type="text/javascript" src="source/js/jquery-1.7.2.min.js"></script>
</head>
<body>
	<div class="lg-container strip pc ready" data-limit-x="0">
		<?php if(empty($_COOKIE['name'])):?>
		<div style="background-color: #aed5fa;width: 100%;height: 100%;position: relative;overflow: hidden;">
			<form method="post">
				<div class="lg-trailer" style="width:100%;height:100%">
					<img class="lg-component-img" src="/source/image/wz.png" style="width:99%;top: 0px; left: 0px;">
				</div>
				<div class="lg-trailer" style="left:20em; top:25em;">
					<p style="text-align:left;font-size:5em;"><span style="font-size:1em;">快乐六一</span></p>
				</div>
				<div class="lg-trailer" style="left:12em; top:36em;">
					<?php if(empty($_REQUEST['action'])):?>
					<input name="name" class="lg-component-input" type="text" placeholder="请妈妈填写自己的名字" style="text-align:center;line-height: 1em;font-size: 3em;color: rgb(123, 138, 139);background: white;border-radius: 5px;border-width: 1px;border-color: rgb(206, 212, 218);width: 12em;height: 2em;">
					<?php elseif($_REQUEST['action'] == 'reg'):?>
					<input type="hidden" name="name" value="<?php echo $_REQUEST['name'];?>">
					<input name="pass" class="lg-component-input" type="text" placeholder="请填写一个自己的密码" style="text-align:center;line-height: 1em;font-size: 3em;color: rgb(123, 138, 139);background: white;border-radius: 5px;border-width: 1px;border-color: rgb(206, 212, 218);width: 12em;height: 2em;">
					<?php elseif($_REQUEST['action'] == 'login'):?>
					<input type="hidden" name="name" value="<?php echo $_REQUEST['name'];?>">
					<input name="word" class="lg-component-input" type="text" placeholder="请输入自己设置的密码" style="text-align:center;line-height: 1em;font-size: 3em;color: rgb(123, 138, 139);background: white;border-radius: 5px;border-width: 1px;border-color: rgb(206, 212, 218);width: 12em;height: 2em;">
					<?php endif;?>
				</div>
				<div class="lg-trailer" style="left:22em; top:45em;">
					<button style="font-size: 3em;background-color: #96aaf1;width: 5em;height: 2em;border-radius: 50px;" onclick="">确定</button>
				</div>
			</form>
		</div>
		<?php else:?>
		<div style="background-image:url('/source/image/edj.jpg');background-size: auto 100%;background-position-x: -800px;width: 100%;height: 100%;position: relative;overflow: hidden;">
			<div class="lg-trailer" style="left: 5em;top: 5em;width: 75%;height: 80%;background-color: #FFF;padding: 3em;opacity: .8;">
				<p style="text-align:left;font-size:4em;"><span style="font-size:1em;">欢迎妈妈 <b style="color:blue;"><?php echo $_COOKIE['name'];?></b></span></p>
				<div>
					<p style="text-align:left;font-size:3em;">
						<button id="show_create" style="font-size:.5em;background-color: #96aaf1;width: 5em;height: 2em;border-radius: 50px;">+</button>
						<span style="font-size:.5em;color:#666;">点击添加参加活动的宝宝(自己的)</span>
					</p>
					<p style="text-align:left;font-size:3em;">
						<ul style="font-size:2em;margin:.5em;padding-left:3.5em;">
							<li>
								<span>琪琪</span>
								<button style="font-size:.7em;background-color: #96aaf1;width:5em;height: 2em;border-radius: 50px;margin-left:5em;" onclick="">编辑&gt;</button>
							</li>
							<?php if(isset($mother['childs'])):?>
							<?php foreach ($mother['childs'] as $childName):?>
							<li>
								<span><?php echo $childName;?></span>
								<button style="font-size:.7em;background-color: #96aaf1;width:5em;height: 2em;border-radius: 50px;margin-left:5em;" onclick="change_child('<?php echo $childName;?>')">编辑&gt;</button>
							</li>
							<?php endforeach;?>
							<?php endif;?>
						</ul>
					</p>
				</div>
				<div>
					<p style="text-align:left;font-size:3em;">
						<button style="font-size:.5em;background-color: #96aaf1;width: 5em;height: 2em;border-radius: 50px;" onclick="">+</button>
						<span style="font-size:.5em;color:#666;">点击抽取赠送礼物的宝宝(别人的)</span>
					</p>
					<p style="text-align:left;font-size:3em;">
						<ul style="font-size:2em;margin:.5em;padding-left:3.5em;">
							<li>
								<span>琪琪</span>
								<button style="font-size:.7em;background-color: #96aaf1;width:5em;height: 2em;border-radius: 50px;margin-left:5em;" onclick="">查看&gt;</button>
							</li>
							<?php if(isset($mother['baobaos'])):?>
							<?php foreach ($mother['baobaos'] as $baoName):?>
							<li>
								<span><?php echo $baoName;?></span>
								<button style="font-size:.7em;background-color: #96aaf1;width:5em;height: 2em;border-radius: 50px;margin-left:5em;" onclick="change_child('<?php echo $baoName;?>')">编辑&gt;</button>
							</li>
							<?php endforeach;?>
							<?php endif;?>
						</ul>
					</p>
				</div>
			</div>
		</div>
		<?php endif;?>
		<div id="create_child" style="display:none;background-image:url('/source/image/edj.jpg');background-size: auto 100%;background-position-x: -800px;width: 100%;height: 100%;position:absolute;overflow: hidden;top:0;left:0;z-index:999">
			<div style="background-color:#FFF;width: 100%;height: 100%;position:absolute;overflow: hidden;top:0;left:0;opacity:.6"></div>
			<div style="position:absolute;top:30em;left:14.5em;">
				<fieldset style="border-radius:1em;padding:1em;">
				<legend style="font-size:2em;">添加宝宝</legend>
				<table style="width: 100%;height:100%" cellpadding="3">
					<tr>
						<td style="text-align:right;"><label style="font-size:2em;">姓名:</label></td>
						<td><input name="child" class="lg-component-input" type="text" placeholder="宝宝的姓名" style="display:inline-block;width: 10em;height: 1.5em;line-height: 1em;font-size: 2em;color: rgb(123, 138, 139);background: white;border-radius: 5px;border-width: 1px;border-color: rgb(206, 212, 218);"></td>
					</tr>
					<tr>
						<td style="text-align:right;"><label style="font-size:2em;">期望的玩具:</label></td>
						<td><input name="expect" class="lg-component-input" type="text" placeholder="期待收到的礼物" style="display:inline-block;width: 10em;height: 1.5em;line-height: 1em;font-size: 2em;color: rgb(123, 138, 139);background: white;border-radius: 5px;border-width: 1px;border-color: rgb(206, 212, 218);"></td>
					</tr>
					<tr>
						<td style="text-align:right;vertical-align:top;"><label style="font-size:2em;">邮寄地址:</label></td>
						<td><textarea name="address" rows="3" placeholder="礼物邮寄的地址包含电话和收件人哦" style="width:100%;font-size: 2em;color: rgb(123, 138, 139);background: white;border-radius: 5px;border-width: 1px;border-color: rgb(206, 212, 218);"></textarea></td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:center;">
							<button style="font-size:2em;background-color: #96aaf1;width: 5em;height: 2em;border-radius: 50px;" onclick="">确定</button>
							<button id="hide_create" style="font-size:2em;background-color: #96aaf1;width: 5em;height: 2em;border-radius: 50px;">&lt;返回</button>
						</td>
					</tr>
				</table>
			</fieldset>
			</div>
		</div>
	</div>
</body>

<script type="text/javascript">
	$(function(){
		$('#show_create').click(function(){
			$('#create_child').fadeIn();
		});

		$('#hide_create').click(function(){
			$('#create_child').fadeOut();
		})
	});
</script>
</html>