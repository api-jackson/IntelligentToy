<?php

include_once 'downloadWeixinFile.php';
include_once 'getMsg.php';
include_once 'responseMsg.php';
include_once 'responseTextMsg.php';
include_once 'responseVoiceMsg.php';
include_once 'responseImageMsg.php';
include_once 'saveLog.php';
include_once 'saveWeixinFile.php';
include_once 'access_token.php';

function downloadVoice($mediaId)
{
	$ACCESS_TOKEN = $GLOBALS["ACCESS_TOKEN"];

	$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$ACCESS_TOKEN&media_id=$mediaId";
	$fileInfo = downloadWeixinFile($url);

	$filename = "/var/www/html/ljc/down_voice.amr";
	saveWeixinFile($filename, $fileInfo["body"]);

	saveMediaId($mediaId);
}