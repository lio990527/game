<?php
error_reporting(E_ALL);
set_time_limit(0);
echo "TCP/IP Connection\n";

$port = 9527;
$ip = "127.0.0.1";

/*
 +-------------------------------
 *    @socket连接整个过程
 +-------------------------------
 *    @socket_create
 *    @socket_connect
 *    @socket_write
 *    @socket_read
 *    @socket_close
 +--------------------------------
 */

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket < 0) {
    echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
}else {
    echo "OK.\n";
}

echo "Host: '$ip' Part: '$port'...\n";
$result = socket_connect($socket, $ip, $port);
if ($result < 0) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
}else {
    echo "Connection OK\n";
}

$in = "Search|127.0.0.1|9527|01|2014-07-30|威海|1007|END";
$out = '';

if(!socket_write($socket, $in, strlen($in))) {
    echo "socket_write() failed: reason: " . socket_strerror($socket) . "\n";
}else {
    echo "push message Success!\n";
    echo "push message:$in\n";
}

while($out = socket_read($socket, 8192)) {
    echo "get Message Success!\n";
    echo "get Message:$out\n";
}


echo "Close SOCKET...\n";
socket_close($socket);
echo "Close OK\n";
?>