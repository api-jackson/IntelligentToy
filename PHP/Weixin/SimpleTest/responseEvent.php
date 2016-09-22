<?php

function responseEvent($postObj)
{
	$msgType = "text";
	
	$toUsername = $postObj->ToUserName;
	$fromUsername = $postObj->FromUserName;
	$createTime = $postObj->CreateTime;
	$content = $postObj->Content;
	$eventKey = $postObj->EventKey;
	
	$textTpl = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				</xml>";
	
	switch ($eventKey)
	{
		case "guide":
			$contentStr = "指南开发中...";
			break;
		default:
			$contentStr = "更多功能，敬请期待！";
			break;
	}
	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $contentStr);
	echo $resultStr;
}