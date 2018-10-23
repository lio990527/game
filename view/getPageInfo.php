<?php 
set_time_limit(0);
ini_set('display_error','Off');
define('PATH', 'E:/image');
$type = trim($_SERVER["argv"][1]) ? trim($_SERVER["argv"][1]) : 'kyo';
$host = 'http://oss.emugif.com/picture2014/nbc';
if($type){
	print_ln("抓取名称为：{$type}");
}else{
	die('没有输入要抓的类型');
}
$i = 1;
if (true){
	print_ln(">>正在抓取{$type}的动作图片");
	$url = $host.'/'.$type.'.htm';
	$objCurl = curl_init($url);
	curl_setopt($objCurl, CURLOPT_SSL_VERIFYHOST, 1);
	curl_setopt($objCurl, CURLOPT_REFERER, "http://oss.emugif.com/");
// 	curl_setopt($objCurl, CURLOPT_HEADER, TRUE);    //表示需要response header
// 	curl_setopt($objCurl, CURLOPT_NOBODY, false); 	//表示需要response body
	curl_setopt($objCurl, CURLOPT_HTTPHEADER, array('Host:oss.emugif.com','Referer:'.$url));
	curl_setopt($objCurl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11");
	curl_setopt($objCurl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1);
	$contents = curl_exec($objCurl);
	$statusCode = curl_getinfo($objCurl, CURLINFO_HTTP_CODE);
// 	var_dump($url,$contents,$statusCode);exit;
	if($statusCode != 200){
		var_dump($url,$statusCode);
		die('抓取失败，');
	}
	curl_close($objCurl);
	$strText = substr($contents, stripos($contents, '<div id="notice"'), stripos($contents, '</body>')-stripos($contents, '<div id="notice"'));
	
	$pregReg = '/([^>]*)<a.*onClick=\"changeimg\(\'([\w]+)\'\)\">([^<]*)<\/a>/Ui'; //changeimg('b14')
	$arrTemp = array();
	preg_match_all($pregReg, $strText, $arrTemp);
// 	var_dump($contents,$arrTemp[1]);exit;
	if(empty($arrTemp[1])){
		die('抓取失败,未获取到任何信息');
	}
	$count = count($arrTemp[1]);
// 	print_r($contents);exit;
	echo ">>抓取图片总数：{$count}.\r\n";
	$arrName = array();
	foreach ($arrTemp[2] as $k=>$v){
		$arrTemp[1][$k] = trim($arrTemp[1][$k]);
		if($arrTemp[1][$k] == '　'||$arrTemp[1][$k] == '/'){
			$arrTemp[1][$k] = $arrTemp[1][$k-1];
		}else if(stripos($arrTemp[1][$k], '┗') !== false || stripos($arrTemp[1][$k], '┣') !== false || stripos($arrTemp[1][$k], '┃') !== false){
			$conact = $conact ? $conact: $arrTemp[3][$k-1];
			$arrTemp[1][$k] = $conact;
		}else{
			$conact = '';
		}
		$sub = str_replace(array('：','　','<br>'),'', trim($arrTemp[1][$k]));
		$sub = empty($sub) ? '' : $sub.'_';
		$arrName[$v] = "{$sub}{$arrTemp[3][$k]}_{$v}";
	}
// 	var_dump($arrName);exit;
	$pregReg = '/<title>([^<]*)<\/title>/Us'; //changeimg('b14')
	$arrTamp = array();
	preg_match($pregReg, $contents, $arrTamp);
	
	$fileName = $arrTamp[1] ? trim(str_replace(array('\\','\/',':','*','?','"','<','>','|'),'',$arrTamp[1])) : $type;
	if(count($arrName)){
		echo ">>开始下载图片\r\n";
		$path = PATH.'/'.$fileName;
		if(!file_exists($path)){
			if(!mkdir($path, '0777')){
				die(">>目录创建失败\r\n");
			}
		}
		
		foreach ($arrName as $img=>$name){
			$url = $host.'/'.$type.'/'.$img.'.gif';
			$name = getImage($url, $name.'.gif', $path.'/'.$type);
 			print_ln(">>>>>>图片{$name}下载完成...");
			$file = fopen($path.'/'.$type.'/'.$type.'.txt', "a");
			fwrite($file,"{$name}\r\n");
			fclose($file);
		}
	}
	print_ln(">>下载完成");exit;
}
die($host);

function print_ln($msg){
	echo $msg."\n";
}

/*
 *功能：php多种方式完美实现下载远程图片保存到本地
 *参数：文件url,保存文件名称，使用的下载方式
 *当保存文件名称为空时则使用远程文件原来的名称
*/
function getImage($url,$name='', $save_dir, $type=0){
	if($url==''){
		return false;
	}
	if($name==''){
		$ext=strrchr($url,'.');
		if($ext!='.gif' && $ext!='.jpg'){
			return false;
		}
		$name=time().$ext;
	}
	//文件保存路径
	if($type){
		$ch=curl_init();
		$timeout=5;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$img=curl_exec($ch);
		curl_close($ch);
	}else{
		ob_start();
		readfile($url);
		$img=ob_get_contents();
		ob_end_clean();
	}
	$size=strlen($img);
	//文件大小
	if(!file_exists($save_dir) && !mkdir($save_dir,0777, true)){
		die('文件创建失败');
	}
	$name = fileExists($save_dir,$name);
	$file = $save_dir.'/'.$name;
	$fp2=@fopen($file,'a');
	fwrite($fp2,$img);
	fclose($fp2);
	return $name;
}

function fileExists($path, $name, $index = 2){
	if(file_exists($path.'/'.$name)){
		$name = preg_replace('/\_[0-9]/', '', $name);
		$name = substr($name, 0, strrpos($name,'.')).'_'.$index.substr($name,strrpos($name,'.'));
		$name = fileExists($path, $name, ++$index);
	}
	return $name;
}
// print_r($list);exit;
?>