<?php

date_default_timezone_set("PRC");

include_once 'downloadVoice.php';
include_once 'downloadWeixinFile.php';
include_once 'getMsg.php';
include_once 'responseMsg.php';
include_once 'responseTextMsg.php';
include_once 'responseVoiceMsg.php';
include_once 'responseImageMsg.php';
include_once 'saveLog.php';
include_once 'saveWeixinFile.php';

function checkSignature()
{
	define("TOKEN", "WeChat");

	//获取GET参数
	$signature = $_GET["signature"];
	$nonce = $_GET["nonce"];
	$timestamp = $_GET["timestamp"];


	//把nonce, timestamp 和 TOKEN 组装到数组里并做排序
	$tmpArr = array($nonce, $timestamp, TOKEN);
	sort($tmpArr);

	//把数组中的元素合并成字符串
	$tmpStr = implode($tmpArr);

	//sha1加密
	$tmpStr = sha1($tmpStr);

	//判断加密后的字符串是否和signature相等
	if($tmpStr != $signature)
	{
		exit(0);
	}

	$echostr = $_GET["echostr"];
	if($echostr)
	{
		echo $echostr;
		exit(0);
	}

	getMsg();
}



checkSignature();

?>