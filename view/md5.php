<?php
// exec('ipconfig',$return_array);

$con = new mysqli('localhost','root','','test');
$sql = 'select count(1) as sum from tbl_csdn_info';
$res = $con->query($sql);
if($res->num_rows > 0){
	while ($row = $res->fetch_array()){
		$intSum = $row['sum'];
	}
}
$intSize = 200000;
$pageSum = ceil($intSum/$intSize);echo $pageSum;exit;
for ($page = 1; $page<=$pageSum; $page++){
	$sql = sprintf('select user_pass from tbl_csdn_info limit %d,%d',($page-1)*$intSize,$intSize);
	$res = $con->query($sql);
	$pas = array();
	while ($row = $res->fetch_array()){
		$pass = $row['user_pass'];
// 		echo ($pass.'_'.strlen($pass))."<br>";
		if(strlen($pass) > 5 && strlen($pass) < 17 ){
			$pas[$pass] = isset($pas[$pass]) ? intval($pas[$pass])+1 : 1;
		}
	}
	arsort($pas);
	print_r($pas);exit;
}

$res->close();
$con->close();
?>