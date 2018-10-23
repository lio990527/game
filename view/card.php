<?php 
$con = new mysqli('localhost','root','','test');
$con->set_charset("utf8");
$sql = "SELECT card_num,name FROM tbl_hotel_info WHERE card_type='ID' LIMIT 20000";
$res = $con->query($sql);
if($res->num_rows > 0){
	$c = 'abcdefghigklmnopqrstuvwxyz';
	$i = '13200000001';
	while ($row = $res->fetch_array()){
		if(strlen($row['card_num'])<18||!preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $row['name']))continue;
		$f = mt_rand(0,23);
		$s = mt_rand(0,23);
		$key = $c[$f].$c[$s];
		echo $row['name'].',ID:'.$row['card_num'].','.$key.substr($row['card_num'],11, 7).'@163.com,1q2w3e4r,'.$i."\n";
		$i++;
	}
}

?>