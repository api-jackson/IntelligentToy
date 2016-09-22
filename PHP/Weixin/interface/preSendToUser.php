<?php
require_once dirname(__FILE__) . '/../common/Common.php';
require_once dirname(__FILE__) . '/../model/CommonModel.php';
require_once dirname(__FILE__) . '/sendToUser.php';

function preSendToUser($codeStr)
{
	$localInfo = json_decode($codeStr, true);
	$deviceNum = $localInfo['deviceNum'];
	$command = $localInfo['command'];
	
	$device = new Device();
	$userId = $device->getDeviceOwner($deviceNum);
	if($userId == "" || $userId == null || $userId == false)
	{return ;}
	
	if($command == "0x27")
	{
		$filename = $localInfo['filename'];
	
		$device = new Device();
		$userId = $device->getDeviceOwner($deviceNum);
		echo $userId."\n";
		return sendToUser($userId, "image", array('fileName'=>$filename));
	}
	
	if($command == "0x2b")
	{
		$filename = $localInfo['filename'];
		$device = new Device();
		$userId = $device->getDeviceOwner($deviceNum);
		echo $userId."\n";
		return sendToUser($userId, "voice", array('fileName'=>$filename));
	}
	
	if($command == "0x29")
	{
		$filename = $localInfo['filename'];
		$device = new Device();
		$userId = $device->getDeviceOwner($deviceNum);
		echo $userId."\n";
		return sendToUser($userId, "video", array('fileName'=>$filename));
	}
	
	if($command == "0x15")
	{}
	
	if($command == "0x33")
	{
		$device = new Device();
		$device->setDeviceState($deviceNum, "online");
		$userId = $device->getDeviceOwner($deviceNum);
		doCurlPostRequest("http://lab404.cn/ljc/Weixin/interface/auto_update.php", "deviceId=$deviceNum");
		return sendToUser($userId, "text", array("content"=>"玩具上线"));
	}
	
	if($command == "0x35")
	{
		$device = new Device();
		$device->setDeviceState($deviceNum, "offline");
		$userId = $device->getDeviceOwner($deviceNum);
		return sendToUser($userId, "text", array("content"=>"玩具离线"));
	}
}
$data = $_POST["data"];
preSendToUser($data);
?>