<?php
require_once dirname(__FILE__) . '/../common/Common.php';
require_once dirname(__FILE__) . '/../model/CommonModel.php';
require_once dirname(__FILE__) . '/../network/Client_Output.php';

	$deviceId = $_POST['deviceId'];	
	$user = new User();
	$device = new Device();
	$auto_update = $device->getAutoUpdate($deviceId);
	if($auto_update == "Y")
	{
		$userId = $device->getDeviceOwner($deviceId);
		$wxUserId = $user->getWxUserIdFromUserId($userId);
		$age = $user->getUserAge($wxUserId);
		$dir = "/var/www/html/ljc/resource/public/song/Age_".$age;
		$server_handler = opendir ($dir);
		
		// 提取服务器的一个文件名
		while(($server_fileName = readdir($server_handler)) !== false)
		{
			if($server_fileName[0] != "."){
				$exist = false;
				// 提取玩具的文件名
				$filepath = "/var/www/html/ljc/resource/device/" . $deviceId . "/songlist.txt";
				$device_handler = fopen($filepath,"r");
				$server_fileName_exp = explode(".", $server_fileName);
				
				if($device_handler)
				{
					while(!feof($device_handler))
					{
						$device_filename = fgets($device_handler);
						$device_filename_exp = explode(".", $device_filename);
						
						if($server_fileName_exp[0] != $device_filename_exp[0])
						{
							continue;
						}
							
						// 当玩具存在此文件时，退出比较
						if($server_fileName_exp[0] == $device_filename_exp[0])
						{
							$exist = true;
							break;
						}
					}
					fclose($device_handler);
				} // 结束玩具文件的一次比较
					
				// 此处对玩具不存在的文件进行处理
				if($exist == false)
				{
					$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"download_song","filename"=>$dir ."/". $server_fileName));
					clientOutput($str);
				}
			}
		}
	}

?>