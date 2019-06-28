<?php

$http = new swoole_http_server('0.0.0.0', '9527');

$http->on('request', function($request, $response){
	
	if($request->server['request_uri'] === '/favicon.ico'){
		$response->end('1');
		return;
	}
	
	$response->header('Content-Type', "text/html; charset=utf-8");
	$response->end("the http server");
});

$http->start();