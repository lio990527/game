<?php 

//pub发布
$redis = new Redis();
$redis->connect('127.0.0.1', '6379');
// // var_dump($redis->get('test_serialize'));
$res = $redis->publish('test_1', 'test msg2!');
$res = $redis->publish('test_2', 'test 草草草草哦啊哦草2');
var_dump($res);

// //sub订阅
// echo 'begin';
// try {
// 	$redis = new Redis();
// 	$redis->connect('127.0.0.1', '6379');
// 	$redis->setOption(Redis::OPT_READ_TIMEOUT, -1);
// 	$redis->subscribe(['test_1','test_2'], function(){
// 	    var_dump(func_get_args());
// 	});
// } catch (Exception $e) {
// 	var_dump($e);
// }
// echo 'end';