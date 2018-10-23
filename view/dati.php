<?php 
require_once 'class/comm/class.file.php';

$info = File::getContent(SCRIPT_ROOT,'tiku.txt');
$info = unserialize($info);

//print_r($info);exit;
$objCurl = curl_init('http://apps.game.qq.com/xlx/act/paper/php/paperList.php');
curl_setopt($objCurl, CURLOPT_SSL_VERIFYHOST, 1);
curl_setopt($objCurl, CURLOPT_REFERER, "http://mail.qq.com/cgi-bin/frame_html?sid=Aj3wQVJZ9WJcVHci&url=%2Fcgi-bin%2Fmail_list%3Fsid%3DAj3wQVJZ9WJcVHci%26topmails%3D0&r=299de85a0f620f0db9a8b25c545bad3d");
// curl_setopt($objCurl, CURLOPT_HEADER, TRUE);    //表示需要response header
curl_setopt($objCurl, CURLOPT_NOBODY, false); 	//表示需要response body
curl_setopt($objCurl, CURLOPT_HTTPHEADER, array('Remote Address:101.227.130.22:80','Request URL:http://apps.game.qq.com/xlx/act/paper/php/paperList.php','Request Method:GET','Status Code:200 OK'));
curl_setopt($objCurl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11");
curl_setopt($objCurl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($objCurl, CURLOPT_COOKIEFILE, SCRIPT_ROOT.'cook.tmp');
// curl_setopt($objCurl, CURLOPT_POST, 1);
// curl_setopt($objCurl, CURLOPT_POSTFIELDS, $data_string);
$content = curl_exec($objCurl);
curl_close($objCurl);
$json = substr($content,22);
$json = json_decode($json,true);
//var_dump($json);exit;
$question = array();
if($json['data']){
	foreach ($json['data']['questionKey'] as $k=>$key){
		$options = '';
		foreach ($json['data']['options'][$k] as $ok=>$ov){
			$options .= "({$ok}){$ov}\n";
		}
		$question[$key] = $json['data']['question'][$k]."\n".$options;
	}
}else{
	print_r($json);
}
if($info){
	foreach ($info as $ik=>$iv){
		$question[$ik] = $iv;
	}
}
ksort($question);

File::writeFile(SCRIPT_ROOT,'tiku.txt', serialize($question),true);
$tiku = '';
foreach ($question as $qk=>$qv){
	$tiku .= "{$qk}.{$qv}\n"; 
}
File::writeFile(SCRIPT_ROOT,'shiti.txt', $tiku,true);
echo count($question)."\n";
print_r($question);
?>