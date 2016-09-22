<?php

include_once 'downloadVoice.php';
include_once 'downloadWeixinFile.php';
include_once 'getMsg.php';
include_once 'responseMsg.php';
include_once 'responseTextMsg.php';
include_once 'responseImageMsg.php';
include_once 'saveLog.php';
include_once 'saveWeixinFile.php';

function responseVoiceMsg($postObj)
{
	$msgType = "voice";

	$toUsername = $postObj->ToUserName;
	$fromUsername = $postObj->FromUserName;
	$createTime = $postObj->CreateTime;

	$mediaId = $postObj->MediaId;
	$voiceTpl = "<xml>
				 <ToUserName><![CDATA[%s]]></ToUserName>
				 <FromUserName><![CDATA[%s]]></FromUserName>
				 <CreateTime>%s</CreateTime>
				 <MsgType><![CDATA[%s]]></MsgType>
				 <Voice>
				 <MediaId><![CDATA[%s]]></MediaId>
				 </Voice>
				 </xml>";
	if(!empty( $mediaId ))
	{
		$mediaIdResp = $mediaId;
		$resultStr = sprintf($voiceTpl, $fromUsername, $toUsername, time(), $msgType, $mediaIdResp);

		downloadVoice($mediaId);

		echo $resultStr;
	}
	else
	{
		echo "Say something...";
	}
}