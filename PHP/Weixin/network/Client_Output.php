<?php
date_default_timezone_set('PRC');

function clientOutput($str) {
	$tcp = getprotobyname ( "tcp" );
	$address = "localhost";
	$port = 8001;
	$socket = socket_create ( AF_INET, SOCK_STREAM, $tcp );
	if (socket_connect ( $socket, $address, $port )) {
// 		echo "connected\n";
		socket_write ( $socket, json_encode(array('id'=>"0")) ); // 此处先写入本地PHP端的连接编号0
// 		while ( true ) { // 每次输出再启动即可，不必在while(true)中循环
// 			$str = trim ( fread ( STDIN, 1024 ) );
		socket_read($socket, 1024);
			socket_write ( $socket, $str );
// 		}
	}
	socket_close($socket);
}

?>