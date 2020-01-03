<?php 
$redis = new redis();
// 		$ks1 = $redis->geoadd('test', '128.124564', '79.646512', 't1');
// 		$ks2 = $redis->geoadd('test', '126.124321', '71.544457', 't2');
// 		$ks3 = $redis->geoadd('test', '128.124564', '79.6465126', 't3');
// 		$ks4 = $redis->geoadd('test', '128.124564', '79.6465126', 't4');
// 		var_dump($ks1,$ks2, $ks3, $ks4);exit;

// 		$d1 = $redis->zrem('test', 't4');
// 		var_dump($d1);exit;

// 		$rn = $redis->rename('test', 'testgeo');
// 		var_dump($rn);exit;


$dis = $redis->geodist('testgeo', 't1', 't2');
$poss = $redis->geopos('testgeo', ['t1']);
$hash = $redis->geohash('testgeo', ['t1']);
$list = $redis->georadius('testgeo', 127.124564, 73.646512, 2000, 'km', [
	'WITHCOORD' => true,
	'WITHDIST' => true,
	'ASC' => true
]);
$list2 = $redis->georadiusbymember('testgeo', 't2', 2000, 'km');
var_dump($dis, $poss, $hash, $list, $list2);exit;