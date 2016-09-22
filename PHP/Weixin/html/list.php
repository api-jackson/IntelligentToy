<?php
require_once dirname(__FILE__).'/../common/Common.php';
require_once dirname(__FILE__).'/../model/CommonModel.php';

/* 网页鉴权，获取访问该网页的用户的openid，并查询数据库获取相应的wxUserId，并根据wxUserId获取设备ID */
/**************************************************/
$code = $_GET['code'];
$user = new User();
$wxUserId = $user->getWxUserIdFromWebpageCode($code);
if($wxUserId == null)
{
	$wxUserId = oauth($code);
	$user->setWxUserIdFromWebpageCode($wxUserId, $code);
}
$device = new Device();
$deviceId = $device->getDeviceId($wxUserId);
/***************************************************/


define("HTML_HEAD",
		"<html>
		<head>
		<meta charset='UTF-8'>
		<title>公仔资源列表</title>
		<style>#image{width:600px;height:400px;margin:50px auto;}</style>
		</head>
		<body>");

define("HTML_TAIL",
		"</body>
		</html>");

$final = $form;
$num = 0;

$command = $_GET['command'];
if($command == 'personal_song')
{
	$filepath = "/var/www/html/ljc/resource/device/" . $deviceId . "/songlist.txt";
	$command = 'delete_song';
}
if($command == 'personal_music')
{
	$filepath = "/var/www/html/ljc/resource/device/" . $deviceId . "/musiclist.txt";
	$command = 'delete_music';
}
if($command == 'personal_story')
{
	$filepath = "/var/www/html/ljc/resource/device/" . $deviceId . "/storylist.txt";
	$command = 'delete_story';
}


$handle = fopen($filepath,"r");
if($handle)
{
	while(!feof($handle))
	{
		$num++;
		$line = fgets($handle);
		$filename = $line;
		
		if($num == 1 && $filename == "")
		{
			$form = "你没有任何文件在玩具上";
			$final = $form;
			break;
		}
		
		$form = "
		<form method='post' action='processHTML.php'>
		<tr>
		<td align=right>$num</td>
		<td>$line</td>
		<input type='hidden' name='fileName' value=$filename>
		<input type='hidden'  name='code'  value=$code>
		<input type='hidden' name='command' value=$command>
		<td><input type='submit' name='B1' value='删除'></td>
		</tr>
		</form>";

		$final .=  $form;
	}
	fclose($handle);
}

$html = HTML_HEAD . $final . HTML_TAIL;
echo $html;
?>