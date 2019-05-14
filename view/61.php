<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>宝宝的六一</title>
<style>
body, html {width: 100%;height: 100%}
article, body, div, h1, h2, h3, h4, h5, html, p, section {margin: 0;padding: 0;outline: 0;-webkit-tap-highlight-color: transparent}
.lg-component-img {background-repeat: no-repeat;background-position: center center;box-sizing: border-box;vertical-align: top;position: relative;left: 0;top: 0}
.lg-component-img-mask {position: absolute;overflow: hidden}
.lg-component-img-border-item {width: 100%;height: 100%;box-sizing: border-box;overflow: hidden;position: relative}
.lg-component-label {font-size: 24px;color: #000;line-height: 1.2;box-sizing: content-box;font-family: "Helvetica Neue", Helvetica, STHeiTi, sans-serif}
.lg-component-label, .lg-component-label a, .lg-component-label font,.lg-component-label p, .lg-component-label span {-webkit-font-smoothing: antialiased;-webkit-text-stroke-width: .2px;-moz-osx-font-smoothing: grayscale}
.lg-component-label div, .lg-component-label p {margin: 0}
.lg-component-input {margin: 0;display: block;padding: 0 5px;box-sizing: border-box;overflow: visible;border: 1px solid #ced4da;transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out}
.lg-component-input:focus {color: #7b8a8b;border-color: #2196F3 !important;outline: 0;box-shadow: inset 0 0 0 .15rem rgba(33, 149, 243, .301)}
.lg-backface, .lg-page {background-size: cover !important}
.lg-icon {position: absolute;width: 0;height: 0;left: -50px;z-index: -1}
.lg-backface, .lg-loading-page {pointer-events: none;height: 100%}
.lg-container, .lg-page-container {width: 100%;min-height: 500px;position: relative;z-index: 1}
.lg-container {overflow: hidden;margin: 0 auto;padding: 0;font-family: "Hiragino Sans GB", "Microsoft YaHei","WenQuanYi Micro Hei", Arial, sans-serif;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;-webkit-text-size-adjust: 100%;opacity: 0}
.lg-container.ready {opacity: 1}
.lg-page-container {overflow: visible}
.lg-back-stage, .lg-front-stage {overflow: visible;position: absolute;top: 0;left: 0;right: 0;bottom: 0;pointer-events: none}
.lg-back-stage .lg-surface, .lg-front-stage .lg-surface {pointer-events: all}
.lg-back-stage {z-index: 0}
.lg-front-stage {z-index: 2}
.lg-loading-page {z-index: 3;position: absolute;top: 0;left: 0;width: 100%}
.lg-page {width: 100%;height: 100%;position: relative;overflow: hidden}
.lg-backface {position: absolute;top: 0;left: 0;width: 100%}
.lg-surface {overflow: hidden;box-sizing: content-box}
.lg-surface, .lg-trailer {position: absolute;z-index: 1}
.lg-container .lg-back-stage {background-color: #FFF}
.lg-container.strip .lg-back-stage, .lg-container.strip .lg-front-stage{position: fixed}
.lg-container.strip.pc {height: 100%}
.lg-container.strip.pc .lg-page-container {height: 100%;overflow-x: hidden;overflow-y: scroll}
.lg-container.multi {height: 100%;background-color: #000}
.lg-container.multi .lg-page-container {height: 100%}
.lg-container.expand {height: 100%;max-height: 100%;overflow: scroll}
</style>
<script type="text/javascript" src="source/js/jquery-1.7.2.min.js"></script>
</head>
<?php 
require '../lib/class/comm/class.file.php';
$path = '../lib/conf';
$file = 'mothers.json';

$mothers = File::getContent($path, $file);
$mothers = empty($mothers) ? [] : json_decode($mothers, true);

if(isset($_COOKIE['name'])){
	$mother = $mothers[$_COOKIE['name']];

	$cfile = 'childs.json';
	$childs = File::getContent($path, $cfile);
	$childs = empty($childs) ? [] : json_decode($childs, true);

	if ($_GET['do'] == 'assign') {
		$mothers = array_filter($mothers, function($m){
			return $m['times'] > 0 ? true : false;
		});
		array_multisort(array_combine(array_keys($mothers), array_column($mothers, 'times')), SORT_DESC, $mothers);
		$childs = array_keys($childs);
		foreach ($mothers as $mn => $mm){
			$baobao = array_rand(array_flip(array_diff($childs, $mm['childs'])), $mm['times']);
			$baobao = is_array($baobao) ? $baobao : [$baobao];
			if (empty($baobao)) {
				die('error try again!');
			}
			$childs = array_values(array_diff($childs, $baobao));
			$mothers[$mn]['baobaos'] = $baobao;
		}
		File::writeFile($path, $file, json_encode($mothers), true);
		die('success');
	}
	if (! empty($_POST)) {
		if (! empty($_POST['do'])) {
			if ($_POST['do'] == 'times') {
				$mothers[$_COOKIE['name']]['times'] -= 1;
				File::writeFile($path, $file, json_encode($mothers), true);
				header("Location:{$_SERVER['REQUEST_URI']}");
			}
		}

		if(empty($_POST['child'])){
			echo '<script>alert("请输入宝宝的姓名");</script>';
			$_REQUEST['action'] = 'show';
		}elseif(empty($_POST['address'])){
			echo '<script>alert("请输入邮寄的地址");</script>';
			$_REQUEST['action'] = 'show';
		} else {
			$childs[$_POST['child']] = [
				'expect' => $_POST['expect'],
				'address' => $_POST['address'],
				'mother' => $_COOKIE['name'],
			];

			if (! array_key_exists($_POST['child'], $childs)) {
				$mother['childs'][] = $_POST['child'];
				$mother['times'] = isset($mother['times']) ? $mother['times'] + 1 : 1;
				$mothers[$_COOKIE['name']] = $mother;
			}

			File::writeFile($path, $cfile, json_encode($childs), true);
			File::writeFile($path, $file, json_encode($mothers), true);

			header("Location:{$_SERVER['REQUEST_URI']}");
		}
	}

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
				File::writeFile($path, $file, json_encode($mothers), true);

				setcookie('name', $name);
				header("Location:{$_SERVER['REQUEST_URI']}");
			} else {
				$_REQUEST['action'] = 'reg';
			}
		} else {
			$_REQUEST['action'] = 'login';
		}
	} elseif (! empty($_REQUEST['name'])) {
		$_REQUEST['action'] = 'reg';
		$mothers[$name] = [];
		File::writeFile($path, $file, json_encode($mothers), true);
	}
}

?>
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
				<p style="text-align:left;font-size:2em;color:#333;"><span style="font-size:1em;">当前共<b style="color:red;"><?php echo count($childs);?></b>位宝宝参加活动</span></p>
				<div>
					<p style="text-align:left;font-size:3em;">
						<button id="show_create" style="font-size:.5em;background-color: #96aaf1;width: 5em;height: 2em;border-radius: 50px;">+</button>
						<span style="font-size:.5em;color:#666;">点击添加参加活动的宝宝(自己的)</span>
					</p>
					<p style="text-align:left;font-size:3em;">
						<ul style="font-size:2em;margin:.5em;padding-left:3.5em;">
							<?php if(isset($mother['childs'])):?>
							<?php foreach ($mother['childs'] as $childName):?>
							<li>
								<label style="float: left;width:3em;text-overflow:ellipsis;overflow: hidden;white-space: nowrap;"><?php echo $childName;?></label>
								<button style="font-size:.7em;background-color: #96aaf1;width:5em;height: 2em;border-radius: 50px;margin-left:5em;" onclick="change_child('<?php echo $childName;?>')">编辑&gt;</button>
							</li>
							<?php endforeach;?>
							<?php endif;?>
						</ul>
					</p>
				</div>
				<div style="display:;">
					<?php if($mother['times'] > 0):?>
					<p style="text-align:left;font-size:3em;">
						<button style="font-size:.5em;background-color: #96aaf1;width: 5em;height: 2em;border-radius: 50px;" onclick="show_cj()">+</button>
						<span style="font-size:.5em;color:#666;">点击抽取赠送礼物的宝宝(别人的)</span>
					</p>
					<?php endif;?>
					<?php if(count($mother['baobaos']) - $mother['times'] > 0):?>
					<p>
						<hr/>
						<span style="font-size:1.5em;color:#666;">即将收到你的礼物的宝宝</span>
					</p>
					<?php endif;?>
					<p style="text-align:left;font-size:3em;">
						<ul style="font-size:2em;margin:.5em;padding-left:3.5em;">
							<?php foreach ($mother['baobaos'] as $k => $baoName):?>
							<?php if(count($mother['baobaos']) - $k - $mother['times'] > 0):?>
							<li>
								<label style="float: left;width:3em;text-overflow:ellipsis;overflow: hidden;white-space: nowrap;"><?php echo $baoName;?></label>
								<button style="font-size:.7em;background-color: #96aaf1;width:5em;height: 2em;border-radius: 50px;margin-left:5em;" onclick="view_child('<?php echo $baoName;?>')">查看&gt;</button>
							</li>
							<?php endif;?>
							<?php endforeach;?>
						</ul>
					</p>
				</div>
			</div>
		</div>
		<?php endif;?>
		<div class="child_view" style="display:<?php echo $_REQUEST['action'] == 'show'?'':'none'; ?>;background-image:url('/source/image/edj.jpg');background-size: auto 100%;background-position-x: -800px;width: 100%;height: 100%;position:absolute;overflow: hidden;top:0;left:0;z-index:999">
			<div style="background-color:#FFF;width: 100%;height: 100%;position:absolute;overflow: hidden;top:0;left:0;opacity:.6"></div>
			<div class="form_view add_view" style="position:absolute;top:30em;left:10.5em;">
				<form method="post">
				<fieldset style="border-radius:1em;padding:1em;">
					<legend style="font-size:2em;">添加宝宝</legend>
					<table style="width: 40em;height:100%;background-color:#FFF;" cellpadding="3">
						<tr>
							<td style="text-align:right;"><label style="font-size:2em;">姓名:</label></td>
							<td><input name="child" value="<?php echo $_POST['child'];?>" class="lg-component-input" placeholder="-宝宝的姓名-" style="display:inline-block;width: 10em;height: 1.5em;line-height: 1em;font-size: 1.8em;background: white;border-radius: 5px;border-width: 1px;border-color: rgb(206, 212, 218);"></td>
						</tr>
						<tr>
							<td style="text-align:right;"><label style="font-size:2em;">期望的玩具:</label></td>
							<td><input name="expect" value="<?php echo $_POST['expect'];?>" class="lg-component-input" placeholder="-期待的礼物-" style="display:inline-block;width: 10em;height: 1.5em;line-height: 1em;font-size: 1.8em;background: white;border-radius: 5px;border-width: 1px;border-color: rgb(206, 212, 218);"></td>	
						</tr>
						<tr>
							<td style="text-align:right;vertical-align:top;"><label style="font-size:2em;">邮寄地址:</label></td>
							<td><textarea name="address" rows="3" placeholder="-收件地址-" style="width:100%;font-size: 2em;background: white;border-radius: 5px;border-width: 1px;"><?php echo $_POST['address'];?></textarea></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align:center;">
								<button name="method" value="add" style="font-size:2em;background-color: #96aaf1;width: 5em;height: 2em;border-radius: 50px;">确定</button>
								<button class="hide_view" style="font-size:2em;background-color: #96aaf1;width: 5em;height: 2em;border-radius: 50px;">&lt;返回</button>
							</td>
						</tr>
					</table>
				</fieldset>
				</form>
			</div>
			<div class="form_view update_view" style="display:none;position:absolute;top:30em;left:10.5em;">
				<form method="post">
				<fieldset style="border-radius:1em;padding:1em;">
					<legend style="font-size:2em;">修改信息</legend>
					<table style="width: 40em;height:100%;background-color:#FFF;" cellpadding="3">
						<tr>
							<td style="text-align:right;"><label style="font-size:2em;">姓名:</label></td>
							<td><input name="child" type="hidden"><label class="child" style="font-size:2em"></label></td>
						</tr>
						<tr>
							<td style="text-align:right;"><label style="font-size:2em;">期望的玩具:</label></td>
							<td><input name="expect" class="lg-component-input" placeholder="-期待的礼物-" style="display:inline-block;width: 10em;height: 1.5em;line-height: 1em;font-size: 1.8em;background: white;border-radius: 5px;border-width: 1px;border-color: rgb(206, 212, 218);"></td>	
						</tr>
						<tr>
							<td style="text-align:right;vertical-align:top;"><label style="font-size:2em;">邮寄地址:</label></td>
							<td><textarea name="address" rows="3" placeholder="-收件地址-" style="width:100%;font-size: 2em;background: white;border-radius: 5px;border-width: 1px;"></textarea></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align:center;">
								<button name="method" value="update" style="font-size:2em;background-color: #96aaf1;width: 5em;height: 2em;border-radius: 50px;">修改</button>
								<button class="hide_view" style="font-size:2em;background-color: #96aaf1;width: 5em;height: 2em;border-radius: 50px;">&lt;返回</button>
							</td>
						</tr>
					</table>
				</fieldset>
				</form>
			</div>
			<div class="form_view info_view" style="display:none;position:absolute;top:30em;left:10.5em;">
				<fieldset style="border-radius:1em;padding:1em;">
					<legend style="font-size:2em;">宝宝的信息</legend>
					<table style="width: 40em;height:100%;background-color:#FFF;" cellpadding="3">
						<tr>
							<td style="width:12em;text-align:right;"><label style="font-size:2em;">姓名:</label></td>
							<td><label class="child" style="font-size:2em"></label></td>
						</tr>
						<tr>
							<td style="text-align:right;"><label style="font-size:2em;">期望的玩具:</label></td>
							<td><label class="expect" style="font-size:2em"></label></td>	
						</tr>
						<tr>
							<td style="text-align:right;vertical-align:top;"><label style="font-size:2em;">邮寄地址:</label></td>
							<td><label class="address" style="font-size:2em"></label></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align:center;">
								<button class="hide_view" style="font-size:2em;background-color: #96aaf1;width: 5em;height: 2em;border-radius: 50px;">&lt;返回</button>
							</td>
						</tr>
					</table>
				</fieldset>
			</div>
		</div>
		<div class="cj_view" style="display:none;background-color:#000;width: 100%;height: 100%;position:absolute;overflow: hidden;top:0;left:0;z-index:999">
			<div style="top: 25%; left:20%;position:absolute;">
				<div class="lg-trailer" style="">
					<img class="lg-component-img" src="/source/image/snow.png" style="width:35em;height:auto;">
				</div>
				<div class="lg-trailer" style="top: 10.7em; left: 4.4em;width:26.2em;height:14.3em;background-color:#333;">
					<div class="lg-trailer" style="left:.1em;top:5em;width:26em;text-align: center;height:5em;overflow:hidden;background-color:#FFF;">
						<p class="cj_begin" style="font-size:3em;"> -点击摇杆开始- </p>
						<ul class="child_list" style="margin:0;padding:0;font-size:3em;margin-top:2em;"></ul>
					</div>
				</div>
				<div class="lg-trailer" style="top:11em; left: 35.2em;background-color:#000;">
					<form method="post">
						<input type="hidden" name="do" value="times">
						<button type="button" class="cj_button" style="background-image:url('/source/image/yg.png');width:6em;height:36em;border:0;background-size:6em auto;background-repeat:no-repeat;background-position:bottom;background-color:#000;" onclick="choujiang(this)">&nbsp;</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>

<script type="text/javascript">
	var childs = <?php echo json_encode($childs);?>;
	$(function(){
		$('#show_create').click(function(){
			$('.form_view').hide();
			$('.add_view').show();
			$('.child_view').fadeIn();
		});

		$('.hide_view').click(function(){
			$('.child_view').fadeOut();
			return false;
		});
	});

	function view_child(child){
		if(childs[child] != undefined){
			$('.form_view').hide();
			$('.info_view').show();
			$('.info_view label.child').text(child);
			$('.info_view label.expect').text(childs[child].expect);
			$('.info_view label.address').text(childs[child].address);
			$('.child_view').fadeIn();
		}
	}

	function change_child(child){
		if(childs[child] != undefined){
			$('.form_view').hide();
			$('.update_view').show();
			$('.update_view label.child').text(child);
			$('.update_view input[name=child]').val(child);
			$('.update_view input[name=expect]').val(childs[child].expect);
			$('.update_view textarea[name=address]').val(childs[child].address);
			$('.child_view').fadeIn();
		}
	}

	function show_cj(){
		$('.cj_view').fadeIn();
	}
	
	function choujiang(btn){
		var childs = <?php echo json_encode($childs);?>;
		var child = '<?php echo $mother['baobaos'][count($mother['baobaos']) - $mother['times']];?>';
		var list = $('.child_list');
		for(var c in childs){
			list.append('<li>'+c+'</li>');
		}

		$('.cj_button').css('backgroundImage', "url('/source/image/yg2.png')");
		$('.cj_begin').hide();

		var height = parseInt($('.child_list').css('height'));
		$('.child_list').css('marginTop', -height+'px');

		var time = 500;
		var move = setInterval(function() {
			time = time > 100 ? time - 100 : time;  
			list.animate({
				"marginTop": (-height + list.find('li:last').height() * 2) + 'px'
			}, time, function() {
				list.css({
					marginTop: (-height+list.find('li:last').height()) + 'px'
				}).find("li:last").prependTo(list);
			});
		}, 100);

		setTimeout(function() {
			clearInterval(move);
			$('.cj_button').css('backgroundImage', "url('/source/image/yg.png')");
			list.find("li:last").text(child);
			list.animate({
				"marginTop": (-height + list.find('li:last').height() * 2) + 'px'
			}, 1000,function(){
				alert('恭喜你抽中的宝宝为['+child+']~');
				btn.form.submit();
			});
		}, 3000);
	}
</script>
</html>