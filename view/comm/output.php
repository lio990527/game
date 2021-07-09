<?php
header("Content-type:text/html;charset=utf-8");
$strInput = $_REQUEST['input'];
if($strInput){
	$strInput = get_magic_quotes_gpc() ? stripslashes($strInput) : $strInput;
	$strFunc = $_REQUEST['func'];
	if(!$_REQUEST['func']){
		if(stripos($strInput,'{"')!==false||stripos($strInput,'["')!==false||stripos($strInput,'"\u')!==false){
			$strFunc = "json_decode";
		}elseif(stripos($strInput,'a:')!==false&&stripos($strInput,'s:')!==false){
			$strFunc = "unSerialize";
		}elseif(stripos($strInput,'%A')!==false||stripos($strInput,'%B')!==false||stripos($strInput,'%C')!==false||stripos($strInput,'%E')!==false){
			$strFunc = "urldecode";
		}elseif(stripos($strInput,'==')!==false){
			$strFunc = "base64_decode";
		}elseif(stripos($strInput,'</')!==false){
			$strFunc = "xmlToArray";
		}
	}
	$arrFunc = array_filter(explode(',', $strFunc));
	$arrJson = $strInput;
	foreach ($arrFunc as $func){
		switch($func){
			case 'json_encode':
				$arrJson = json_encode($arrJson);
				break;
			case 'unSerialize':
				$arrJson = unserialize($arrJson);
				break;
			case 'serialize':
				$arrJson = serialize($arrJson);
				break;
			case 'urldecode':
				$arrJson = urldecode($arrJson);
				break;
			case 'urlencode':
				$arrJson = urlencode($arrJson);
				break;
			case 'base64_encode':
				$arrJson = base64_encode($arrJson);
				break;
			case 'base64_decode':
				$arrJson = base64_decode($arrJson);
				break;
			case 'xmlToArray':
				$arrJson = json_decode(str_replace(':{}', ':null', json_encode(@simplexml_load_string($arrJson, 'SimpleXMLElement', LIBXML_NOBLANKS))), true);
				break;
			case 'arrayToXml':
				$arrJson = is_array($arrJson) ? Xml::array2xml($arrJson) : '数据格式不是数组';
				break;
			case 'md5':
				$arrJson = md5($arrJson);
				break;
			default:
				$arrJson = json_decode($arrJson,true);
				break;
		}
	}
 	$arrJson = is_string ($arrJson) ? htmlspecialchars($arrJson) : $arrJson;
}

if($strInput){
	//$output = json_decode($strInput,true);
	//echo digui($output);
}

function digui($arr){
	$mes = '<pre>array：{<br/>';
	foreach ($arr as $k => $v){
		if(is_array($v)){
			$mes .= digui($v);
		}else{
			$mes .= '	"'.$k.'"<label>'.gettype($v).'</label>  : '.$v."<br/>";
		}
	}
	$mes.= '</pre>';
	return $mes;
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
<title>encode/decode</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="/source/css/menu.css"/>

<head>
	<style type="text/css">
	body {
		margin: 0;
		padding: 0;
		font-size: 14px;
		color: #444;
		font-family: Courier New;
		word-wrap: break-word;
		border: 0;
		resize: none;
	}
	
	#code {
		width: calc(100% -30px);
		height: 100%;
		border: 0px solid;
		font-family: Courier New;
		line-height: 152%;
		word-wrap: break-word;
		padding: 20px;
	}
	
	label {
		padding: 2px 2px;
		color: #d14;
		background-color: #f7f7f9;
		border: 1px solid #e1e1e8;
		line-height: 25px;
		font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
		font-size: 12px;
		border-radius: 3px;
	}
	
	.toolbox {
		font-family: sans-serif;
		font-size: 13px;
		background-color: #d2d2f6;
		position: fixed;
		right: 0px;
		top: 0px;
		border-width: 0;
		border-bottom-width: 1px #c2c2c2 solid;
		border-left-width: 1px #c2c2c2 solid;
		border-bottom-left-radius: 4px;
		padding: 2px;
		transition: opacity .2s ease-out;
		-webkit-transition: opacity .2s ease-out;
		cursor: default;
		user-select: none;
		-webkit-user-select: none;
		padding-left: 2px;
	}
	
	.status {
		position: fixed;
		right: 0px;
		bottom: 0px;
		height: 16px;
		border-width: 1px;
		border-bottom-width: 0px;
		border-right-width: 0px;
		border-color: #c2c2c2;
		border-style: solid;
		border-top-left-radius: 4px;
		opacity: 0;
		padding: 2px 7px 2px 4px;
		font-family: sans-serif;
		font-size: 12px;
		background-color: #d2d2f6;
		color: #696969;
		text-align: right;
		transition: opacity .2s ease-out;
		-webkit-transition: opacity .2s ease-out;
		user-select: none;
		-webkit-user-select: none;
	}
	.status:not(:empty){opacity:1;}
</style>

<script type="text/javascript">
	$ = function(id){
		return document.getElementById(id);
	}
</script>
</head>
</head>
<body>
	<div id="code">
		<pre><?php print_r($arrJson);?></pre>
	</div>
	<div class="toolbox">
		<form name="form0" method="post">
			<textarea name="input" style="display:none;"><?php echo $strInput;?></textarea>
			<ul id="decodeType" style="display:inline;list-style:none;display:none;padding:2px;">
				<li><input type="radio" name="func" value="json_decode" onchange="document.form0.submit()" <?php if($strFunc=='json_decode')echo "checked";?>>json_decode</li>
				<li><input type="radio" name="func" value="json_encode" onchange="document.form0.submit()" <?php if($strFunc=='json_encode')echo "checked";?>>json_encode</li>
				<li><input type="radio" name="func" value="base64_decode" onchange="document.form0.submit()" <?php if($strFunc=='base64_decode')echo "checked";?>>base64_decode</li>
				<li><input type="radio" name="func" value="base64_encode" onchange="document.form0.submit()" <?php if($strFunc=='base64_encode')echo "checked";?>>base64_encode</li>
				<li><input type="radio" name="func" value="unserialize" onchange="document.form0.submit()" <?php if($strFunc=='unserialize')echo "checked";?>>unserialize</li>
				<li><input type="radio" name="func" value="serialize" onchange="document.form0.submit()" <?php if($strFunc=='serialize')echo "checked";?>>serialize</li>
				<li><input type="radio" name="func" value="urldecode" onchange="document.form0.submit()" <?php if($strFunc=='urldecode')echo "checked";?>>urldecode</li>
				<li><input type="radio" name="func" value="urlencode" onchange="document.form0.submit()" <?php if($strFunc=='urlencode')echo "checked";?>>urlencode</li>
				<li><input type="radio" name="func" value="xmlToArray" onchange="document.form0.submit()" <?php if($strFunc=='xmlToArray')echo "checked";?>>xmlToArray</li>
				<li><input type="radio" name="func" value="md5" onchange="document.form0.submit()" <?php if($strFunc=='md5')echo "checked";?>>md5</li>
			</ul>
			<span class="btn btn_opt" title="设置" style="float:right;" onclick="$('decodeType').style.display=($('decodeType').style.display=='none')?'inline-block':'none';">&nbsp;</span>
		</form>
	</div>
	<div class="status"><?php echo $strFunc;?></div>
</body>
</html>