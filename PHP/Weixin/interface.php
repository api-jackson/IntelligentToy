<?php
// $startTime = microtime(true);
require_once dirname(__FILE__) . '/common/Common.php';

function checkSignature()
{
	$signature = $_GET["signature"];
	$timestamp = $_GET["timestamp"];
	$nonce = $_GET["nonce"];

	$token = WEIXIN_TOKEN;
	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr);
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );

	if( $tmpStr == $signature ){
		return true;
	}else{
		return false;
	}
}

/*** 以下是检验入口代码 ***/
/*
if(!checkSignature()) {
	//恶意请求：获取来来源ip，并写日志
// 	$ip = getIp();
// 	interface_log(ERROR, EC_OTHER, 'malicious: ' . $ip);
	exit(0);
} 

if($_GET["echostr"]) {
	echo $_GET["echostr"];
// 	exit(0);	
}
*/
/**************************/

function getWeChatObj($toUserName) {
	require_once dirname(__FILE__) . '/class/WeChatCallBackWJ.php';
	return new WeChatCallBackWJ();
}
function exitErrorInput(){
	
	echo 'error input!';
	interface_log(INFO, EC_OK, "***** interface request end *****");
	interface_log(INFO, EC_OK, "*********************************");
	interface_log(INFO, EC_OK, "");
	exit ( 0 );
}

/**
 * 以下是入口文件源码
 * 
 **/

$postStr = $GLOBALS["HTTP_RAW_POST_DATA"]; //什么意思？类似 $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

interface_log(INFO, EC_OK, "");
interface_log(INFO, EC_OK, "***********************************");
interface_log(INFO, EC_OK, "***** interface request start *****");
interface_log(INFO, EC_OK, 'request:' . $postStr);
interface_log(INFO, EC_OK, 'get:' . var_export($_GET, true));

if (empty ( $postStr )) {
	interface_log ( ERROR, EC_OK, "error input!" );
	exitErrorInput();
}
// 获取参数
$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
if(NULL == $postObj) {
	interface_log(ERROR, 0, "can not decode xml");	
	exit(0);
}

// ***  对 $postObj 中的 ToUserName 进行分析  *** //
$toUserName = ( string ) trim ( $postObj->ToUserName );
if (! $toUserName) { 
	interface_log ( ERROR, EC_OK, "error input!" ); 
	exitErrorInput();
} else {
	$wechatObj = getWeChatObj ( $toUserName ); // 通过 getWeChatObj 获取发往对应公众号的处理类
}

// *** 对 $postObj 进行初始化分析 *** //
$ret = $wechatObj->init ( $postObj );
if (! $ret) {
	interface_log ( ERROR, EC_OK, "error input!" );
	exitErrorInput();
}
$retStr = $wechatObj->process ();
interface_log ( INFO, EC_OK, "response:" . $retStr );
echo $retStr;


interface_log(INFO, EC_OK, "***** interface request end *****");
interface_log(INFO, EC_OK, "*********************************");
interface_log(INFO, EC_OK, "");
$useTime = microtime(true) - $startTime;
interface_log ( INFO, EC_OK, "cost time:" . $useTime . " " . ($useTime > 4 ? "warning" : "") );

?>
