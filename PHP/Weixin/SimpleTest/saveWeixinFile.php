<?php

include_once 'downloadVoice.php';
include_once 'downloadWeixinFile.php';
include_once 'getMsg.php';
include_once 'responseMsg.php';
include_once 'responseTextMsg.php';
include_once 'responseVoiceMsg.php';
include_once 'responseImageMsg.php';
include_once 'saveLog.php';

function saveWeixinFile($filename, $filecontent)
{
	$local_file = fopen($filename, 'w');
	if(false !== $local_file)
	{
		if(false !== fwrite($local_file, $filecontent))
		{
			fclose($local_file);
		}
	}
}