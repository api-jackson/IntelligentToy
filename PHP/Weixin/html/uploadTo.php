<?php
require_once dirname(__FILE__).'/../common/Common.php';
require_once dirname(__FILE__).'/../model/CommonModel.php';
require_once dirname(__FILE__).'/../network/Client_Output.php';

if ($_FILES["file"]["error"] > 0)
{
	echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
}
else
{
	// 			echo "Upload: " . $_FILES["file"]["name"] . "<br />";
	// 			echo "Type: " . $_FILES["file"]["type"] . "<br />";
	// 			echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
	// 			echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
	
	$id = $_POST['WXUserId'];
	$device = new Device();
	$deviceId = $device->getDeviceId($id);
	
	/* 查找文件夹是否存在，若不存在，则新建一个文件夹，然后再打开该文件夹 */
	$filepath = "/var/www/html/ljc/resource/device/".$deviceId;
	if(!($open = is_dir($filePath)));
	{
		mkdir($filePath, 0777);
		chmod($filepath, 0777);
	}
	/* 查找文件夹是否存在，若不存在，则新建一个文件夹，然后再打开该文件夹 */
	$filepath = "/var/www/html/ljc/resource/device/".$deviceId."/upload";
	if(!($open = is_dir($filePath)));
	{
		mkdir($filePath, 0777);
		chmod($filepath, 0777);
	}
		
	if (file_exists($filepath."/" . $_FILES["file"]["name"]))
	{
		// 				echo $_FILES["file"]["name"] . " already exists. ";
	}
	else
	{
		move_uploaded_file($_FILES["file"]["tmp_name"],
				$filepath. "/" . $_FILES["file"]["name"]);
		// 				echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
		$filename = $filepath. "/" . $_FILES["file"]["name"];
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"push_resource","filename"=>$filename));
		echo "<html>
				<head>
				<meta charset='UTF-8'>
				<title>上传歌曲</title>
				<style>#image{width:600px;height:400px;margin:50px auto;}</style>
				</head>
				<body>
					$deviceId 歌曲上传成功
				</body>
				</html>";
		clientOutput($str);
	}
}