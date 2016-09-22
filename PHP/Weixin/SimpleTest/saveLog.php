<?php

include_once 'downloadVoice.php';
include_once 'downloadWeixinFile.php';
include_once 'getMsg.php';
include_once 'responseMsg.php';
include_once 'responseTextMsg.php';
include_once 'responseVoiceMsg.php';
include_once 'responseImageMsg.php';
include_once 'saveWeixinFile.php';

function saveMediaId($mediaId)
{
	$local_file = fopen("/var/www/html/ljc/mediaId.txt", 'a');
	if(false !== $local_file)
	{
		if(false !== fwrite($local_file, $mediaId."\r\n"))
		{
			fclose($local_file);
		}
	}
}