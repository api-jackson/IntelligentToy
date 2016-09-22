<?php

include_once 'downloadVoice.php';
include_once 'downloadWeixinFile.php';
include_once 'getMsg.php';
include_once 'responseMsg.php';
include_once 'responseTextMsg.php';
include_once 'responseVoiceMsg.php';
include_once 'saveLog.php';
include_once 'saveWeixinFile.php';
include_once 'access_token.php';

function responseImageMsg($postObj)
{
	$ACCESS_TOKEN = $GLOBALS["ACCESS_TOKEN"];

	$msgType = "image";

	$toUsername = $postObj->ToUserName;
	$fromUsername = $postObj->FromUserName;
	$createTime = $postObj->CreateTime;
	$content = $postObj->Content;
	$picUrl = $postObj->PicUrl;
	$mediaId = $postObj->MediaId;

	$textTpl = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Image>
				<MediaId><![CDATA[%s]]></MediaId>
				</Image>
				</xml>";
	if(!empty( $mediaId ))
	{
		// 		$contentStr = $content;
		// 		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
		// 		echo $resultStr;

		saveMediaId($mediaId);

		$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$ACCESS_TOKEN&media_id=$mediaId";
		$fileInfo = downloadWeixinFile($url);
		$filename = "/var/www/html/ljc/down_image.jpg";
		saveWeixinFile($filename, $fileInfo["body"]);


	}
	else
	{
		echo "Input something...";
	}
}