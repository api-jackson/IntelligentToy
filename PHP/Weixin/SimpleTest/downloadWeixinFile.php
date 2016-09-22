<?php

include_once 'downloadVoice.php';
include_once 'getMsg.php';
include_once 'responseMsg.php';
include_once 'responseTextMsg.php';
include_once 'responseVoiceMsg.php';
include_once 'responseImageMsg.php';
include_once 'saveLog.php';
include_once 'saveWeixinFile.php';

function downloadWeixinFile($url)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_NOBODY, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$package = curl_exec($ch);
	$httpinfo = curl_getinfo($ch);
	curl_close($ch);
	$imageAll = array_merge(array('header'=>$httpinfo),array('body'=>$package));
	return $imageAll;
}