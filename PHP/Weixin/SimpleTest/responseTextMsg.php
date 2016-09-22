<?php

include_once 'downloadVoice.php';
include_once 'downloadWeixinFile.php';
include_once 'getMsg.php';
include_once 'responseMsg.php';
include_once 'responseVoiceMsg.php';
include_once 'responseImageMsg.php';
include_once 'saveLog.php';
include_once 'saveWeixinFile.php';

function responseTextMsg($postObj)
{
	$msgType = "text";

	$toUsername = $postObj->ToUserName;
	$fromUsername = $postObj->FromUserName;
	$createTime = $postObj->CreateTime;
	$content = $postObj->Content;
	$textTpl = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				</xml>";
	if(!empty( $content ))
	{
		$contentStr = $content;
		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $contentStr);
		echo $resultStr;
	}
	else
	{
		echo "Input something...";
	}
}