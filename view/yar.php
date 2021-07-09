<?php 
error_reporting(E_ALL);
$client = new \Yar_Client('http://127.0.0.1:8000/api/service');
var_dump($client->test('test', 'get', ['test' => 1, 'res' => '2']));
