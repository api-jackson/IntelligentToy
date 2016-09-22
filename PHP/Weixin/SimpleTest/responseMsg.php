<?php

include_once 'downloadVoice.php';
include_once 'downloadWeixinFile.php';
include_once 'getMsg.php';
include_once 'responseTextMsg.php';
include_once 'responseVoiceMsg.php';
include_once 'responseImageMsg.php';
include_once 'saveLog.php';
include_once 'saveWeixinFile.php';
include_once 'responseEvent.php';

function responseMsg($postObj)
{

	$msgType = $postObj->MsgType;

	if($msgType == "text")
	{
		responseTextMsg($postObj);
	}

	if($msgType == "voice")
	{
		responseVoiceMsg($postObj);
	}

	if($msgType == "image")
	{
		responseImageMsg($postObj);
	}
	
	if($msgType == "event")
	{
		responseEvent($postObj);
	}
}