<?php
$ws = new swoole_websocket_server('0.0.0.0', 9619);

$ws->on('start', function ($ws) {
	echo 'ws start...';
});
$ws->on('open', function ($ws, $request) {
	var_dump($request);
	$ws->push($request->fd, 'welcome');

	swoole_timer_tick(10000, function ($timer_id) use ($ws, $request) {
		$ws->push($request->fd, "");
	});
});

$ws->on('message', function ($ws, $frame) {
	if (trim($frame->data) == 'exit') {
		$ws->push($frame->fd, 'say you:' . $frame->fd);
		swoole_timer_after(1500, function () use ($ws, $frame) {
			echo $ws->disconnect($frame->fd, 1024);
		});
		return;
	}
	$ws->push($frame->fd, $frame->data . ' too');
});

$ws->on('request', function ($request,$response) {
	
	var_dump($request,$response);
	$response->end('ok');
});

$ws->on('close', function ($ws, $fd) {
	var_dump($ws, $fd);
});

$ws->start();