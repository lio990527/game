<?php
//确保在连接客户端时不会超时
set_time_limit(0);

$ip = '127.0.0.1';
$port = 9527;

/*
 +-------------------------------
 *    @socket通信整个过程
 +-------------------------------
 *    @socket_create
 *    @socket_bind
 *    @socket_listen
 *    @socket_accept
 *    @socket_read
 *    @socket_write
 *    @socket_close
 +--------------------------------
 */

/*----------------    以下操作都是手册上的    -------------------*/
if(($sock = socket_create(AF_INET,SOCK_STREAM,SOL_TCP)) < 0) {
    echo "socket_create() 失败的原因是:".socket_strerror($sock)."\n";
}

if(($ret = socket_bind($sock,$ip,$port)) < 0) {
    echo "socket_bind() 失败的原因是:".socket_strerror($ret)."\n";
}

if(($ret = socket_listen($sock,4)) < 0) {
    echo "socket_listen() 失败的原因是:".socket_strerror($ret)."\n";
}

$count = 0;

do {
    if (($msgsock = socket_accept($sock)) < 0) {
        echo "socket_accept() failed: reason: " . socket_strerror($msgsock) . "\n";
        break;
    } else {
        echo "sokect client success!\n";
        $res = socket_read($msgsock,8192);
		$buf = explode('|',$res);
		if($buf[0] == 'Search'){
			$msg = "SUSearch|1065|$buf[4]|603|1332|1009|纺织城站|1066|渭南|1066|渭南|35|32|63.00|1005|0|,|1066|$buf[4]|803|1806|1009|纺织城站|1066|渭南|1067|白水|35|30|65.00|1005|0|,|1067|$buf[4]|503|1012|1009|纺织城站|1066|渭南|1069|汉中|35|15|60.00|1005|0|END";
			socket_write($msgsock, $msg, strlen($msg));
		}else if($buf[0] == 'Lock'){
			$msg = "SULock|XA0011451|1009|纺织城客运站|1305|608|$buf[9]|$buf[10]|威海|380|大型高一级客车|1|108|216|12|06|16|1005|0|,|XA0011451|1009|纺织城客运站|1305|608|$buf[9]|$buf[10]|威海|380|大型高一级客车|1|108|216|13|06|19|1005|0|END";
			socket_write($msgsock, $msg, strlen($msg));
		}else if($buf[0] == 'OutPut'){
			$msg = "SUOutPut|1009|纺织城客运站|608|20140805|0809|威海|380|大型高一级客车|1|12|06|110.00|1.00|1.00|108.00|5|10251468|0|0|,|1009|纺织城客运站|608|20140805|0809|威海|380|大型高一级客车|1|12|06|110.00|1.00|1.00|108.00|5|10251469|0|0|END";
			socket_write($msgsock, $msg, strlen($msg));
		}else if($buf[0] == 'ModStatus'){
			$msg = "SUModStatus|$buf[4]|1009|纺织城客运站|20140805|0809|威海|110.00|1|同步状态成功|4411200226|0|END";
			socket_write($msgsock, $msg, strlen($msg));
		}
        //发到客户端
		$talkback = "get info :$res\n";
        $talkback.= "put info :$buf[0]SC\n";
        echo $talkback;
        
        if(++$count >= 5){
            break;
        };
    }
    //echo $buf;
    socket_close($msgsock);
} while (true);

socket_close($sock);
?>