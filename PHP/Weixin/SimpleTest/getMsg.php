<?php

include_once 'downloadVoice.php';
include_once 'downloadWeixinFile.php';
include_once 'responseMsg.php';
include_once 'responseTextMsg.php';
include_once 'responseVoiceMsg.php';
include_once 'responseImageMsg.php';
include_once 'saveLog.php';
include_once 'saveWeixinFile.php';

function getMsg()
{
	$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

	if (!empty($postStr)){

		libxml_disable_entity_loader(true);
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

		responseMsg($postObj);
	}

	else
	{
		echo "";
		exit;
	}
}