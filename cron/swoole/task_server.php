<?php
$task = new swoole_server('127.0.0.1', 9001);
$task->set([
	'task_worker_num' => 4
]);

$task->on('receive', function($task, $fd, $from_id, $data){
	var_dump($fd, $from_id, $data);
	
	$task->task($data);
});

$task->on('task', function ($task, $tid, $from_id, $data) {
	var_dump($tid, $from_id, $data);
	sleep(3);
	$task->finish('ok');
});

$task->on('finish', function ($task, $tid, $data) {
	var_dump($data);
});

$task->start();