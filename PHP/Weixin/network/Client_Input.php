<?php
require_once dirname(__FILE__) . "/../common/Common.php";
// require_once dirname(__FILE__) . "/../interface/preSendToUser.php";

function ClientInput()
{
	$tcp = getprotobyname ( "tcp" );
	$address = "localhost";
	$port = 8000;
	$socket = socket_create ( AF_INET, SOCK_STREAM, $tcp );
	if (socket_connect ( $socket, $address, $port )) {
		echo "connected\n";
		socket_write ( $socket, json_encode(array('id'=>"0")) ); // 此处先写入本地PHP端的连接编号0
		$str = socket_read ( $socket, 1024 );
		while ( true ) { // 因为要检测服务器的输出，所以必须在while(true)中循环
			$str = socket_read ( $socket, 1024 );
			fwrite ( STDOUT, (trim($str))."\n");
			echo trim($str);
			
// 			preSendToUser($str);
			doCurlPostRequest("http://lab404.cn/ljc/Weixin/interface/preSendToUser.php", "data=$str");
			echo "\nHTTP Request successfully!\n";
		}
	}
}

ClientInput();
?>