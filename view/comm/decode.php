<?php
require_once 'class/comm/class.Xml.php';
if($_REQUEST['data']){
	$_REQUEST['data'] = get_magic_quotes_gpc() ? stripslashes($_REQUEST['data']) : $_REQUEST['data'];
	$arrJson = $_REQUEST['data'];
	$strFunc = $_REQUEST['func'];
	if(empty($strFunc)){
		if(stripos($_REQUEST['data'],'{"')!==false||stripos($_REQUEST['data'],'["')!==false||stripos($_REQUEST['data'],'"\u')!==false){
			$strFunc = "json_decode,";
		}elseif(stripos($_REQUEST['data'],'a:')!==false&&stripos($_REQUEST['data'],'s:')!==false){
			$strFunc = "unSerialize,";
		}elseif(stripos($_REQUEST['data'],'%A')!==false||stripos($_REQUEST['data'],'%B')!==false||stripos($_REQUEST['data'],'%C')!==false||stripos($_REQUEST['data'],'%E')!==false){
			$strFunc = "urldecode,";
		}elseif(stripos($_REQUEST['data'],'==')!==false){
			$strFunc = "base64_decode,";
		}elseif(stripos($_REQUEST['data'],'</')!==false){
			$strFunc = "xmlToArray,";
		}
	}
	
	$arrFunc = array_filter(explode(',', $strFunc));
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
				$arrJson = json_decode(json_encode(simplexml_load_string($arrJson)), true);
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>encode/decode</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<head>
	<style type="text/css">
        body{ margin:0; padding:0; list-style:none;font-size:14px;}
        .centerDiv{width:1020px; margin:20px auto;padding:5px 10px;border:1px solid;border-radius:5px;background:#F0F0F0;}
        .centerBtn{width:800px; border:1px solid;background:#F0F0F0;}
        .centerDiv label{font-size:12px;}
        .centerDiv hr{height:1px;border:none;border-top:1px solid #AAA;}
    </style>
    <script type="text/javascript">
		function checkThis(domId){
			document.getElementById(domId).click();
		}
		function loadVal(ck){
			var func = document.form1.func;
			if(ck.checked){
				func.value+=ck.id+',';
			}else{
				var re = ck.id+',';
				func.value = func.value.replace(re, '');
			}
			loadFunc();
		}
		function loadFunc(){
			var funcVal = document.form1.func.value.split(',');
			var strText = '';
			if( funcVal[0] != ''){
				for(var i = 0; i < funcVal.length; i++){
					if(funcVal[i] == '')continue;
					strText = funcVal[i]+'(' + strText;
					strText+= (i==0) ? 'data' : '';
					strText+= ')';
				}
			}
			strText = strText==''?'data':strText;
			document.getElementById('func').innerHTML = strText+'\n';
		}
		onload = loadFunc;
    </script>
</head>
</head>
<body>
	<form method="post" name="form1">
	<div class="centerDiv">
		<h2 align="center">编码/解码</h2>
		<table>
		<tr>
		<td><textarea style="width:500px;max-width:500px;min-width:500px;height:500px;" name="data"><?php if($_REQUEST['data'])echo $_REQUEST['data'];?></textarea><br/></td>
		<td><textarea style="width:500px;max-width:500px;min-width:500px;height:500px;" readonly="true"><?php if($arrJson){print_r($arrJson);}else{echo '&nbsp;';};?></textarea></td>
		</tr>
		</table>
		<hr/>
		<span id="func">data</span>
		<hr/>
		<table>
			<tr>
				<td><input type="checkbox" name="type" id="json_decode" onclick="loadVal(this)"<?php if(strpos($_REQUEST['func'],'json_decode') !== false)echo 'checked="checked"';?> /></td>
				<td style="cursor:default;" onclick="checkThis(this.innerHTML)"; width="500">json_decode</td>
				<td><input type="checkbox" name="type" id="json_encode" onclick="loadVal(this)"<?php if(strpos($_REQUEST['func'],'json_encode') !== false)echo 'checked="checked"';?> /></td>
				<td style="cursor:default;" onclick="checkThis(this.innerHTML)";>json_encode</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="type" id="unSerialize" onclick="loadVal(this)"<?php if(strpos($_REQUEST['func'],'unSerialize') !== false)echo 'checked="checked"';?>/></td>
				<td style="cursor:default;" onclick="checkThis(this.innerHTML)";>unSerialize</td>
				<td><input type="checkbox" name="type" id="serialize" onclick="loadVal(this)"<?php if(strpos($_REQUEST['func'],'serialize') !== false)echo 'checked="checked"'?>/></td>
				<td style="cursor:default;" onclick="checkThis(this.innerHTML)";>serialize</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="type" id="urldecode" onclick="loadVal(this)"<?php if(strpos($_REQUEST['func'],'urldecode') !== false)echo 'checked="checked"';?>/></td>
				<td style="cursor:default;" onclick="checkThis(this.innerHTML)";>urldecode</td>
				<td><input type="checkbox" name="type" id="urlencode" onclick="loadVal(this)"<?php if(strpos($_REQUEST['func'],'urlencode') !== false)echo 'checked="checked"';?>/></td>
				<td style="cursor:default;" onclick="checkThis(this.innerHTML)";>urlencode</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="type" id="base64_decode" onclick="loadVal(this)"<?php if(strpos($_REQUEST['func'],'base64_decode') !== false)echo 'checked="checked"';?>/></td>
				<td style="cursor:default;" onclick="checkThis(this.innerHTML)";>base64_decode</td>
				<td><input type="checkbox" name="type" id="base64_encode" onclick="loadVal(this)"<?php if(strpos($_REQUEST['func'],'base64_encode') !== false)echo 'checked="checked"';?>/></td>
				<td style="cursor:default;" onclick="checkThis(this.innerHTML)";>base64_encode</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="type" id="xmlToArray" onclick="loadVal(this)"<?php if(strpos($_REQUEST['func'],'xmlToArray') !== false)echo 'checked="checked"';?>/></td>
				<td style="cursor:default;" onclick="checkThis(this.innerHTML)";>xmlToArray</td>
				<td><input type="checkbox" name="type" id="arrayToXml" onclick="loadVal(this)"<?php if(strpos($_REQUEST['func'],'arrayToXml') !== false)echo 'checked="checked"';?>/></td>
				<td style="cursor:default;" onclick="checkThis(this.innerHTML)";>arrayToXml</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="type" id="md5" onclick="loadVal(this)"<?php if(strpos($_REQUEST['func'],'md5')!== false)echo 'checked="checked"';?>/></td>
				<td style="cursor:default;" onclick="checkThis(this.innerHTML)";>md5</td>
				<td></td>
				<td></td>
			</tr>
		</table>
	</div>
	<input type="hidden" name="func" value="<?php echo $_REQUEST['func'];?>"/>
	</form>
	<div class="centerDiv" unselectable="on" onselectstart="return false;" style="text-align:center;font-weight:bold;cursor:pointer;-moz-user-select:none;" onclick="document.form1.submit()" onmousedown="this.style.paddingTop='6px';this.style.paddingBottom='4px';this.style.backgroundColor='#FAFAFA';" onmouseup="this.style.paddingTop='5px';this.style.paddingBottom='5px';this.style.backgroundColor='#F0F0F0';">提交</div>
	
</body>
</html>