<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require_once 'Service.php';

$server = new swoole_server('127.0.0.1', 9501);
$s = [];

$server->on('connect', function ($server, $fd)use($s) {
	echo "Client: Connect.\n";
	var_dump($s);
});
// 监听数据接收事件
$server->on('receive', function ($server, $fd, $from_id, $data) {
	$server->send($fd, "Server: " . trim($data) . " YOU!" . PHP_EOL);
});
$server->on('close', function ($server, $fd) {
	echo "Client: Close.\n";
});

$server->on('start', function ($server)use($s) {
	echo "Client: Start.\n";
	$ss = new ReflectionClass('Service');
	$ms = $ss->getMethods(ReflectionMethod::IS_PUBLIC);
	foreach ($ms as $m){
		$s[] = $m->name;
	}
});

$server->start();

exit();