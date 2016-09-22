<?php
require_once dirname(__FILE__).'/../common/Common.php';
require_once dirname(__FILE__).'/../model/CommonModel.php';
require_once dirname(__FILE__).'/../network/Client_Output.php';

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

$command = $_GET['command'];
/* 遥控公仔：播放广播列表 */
if($command == 'radio')
{
	$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"download_radiolist","filename"=>"/var/www/html/ljc/resource/public/broadcast/radioList.json"));
	clientOutput($str);

	define("HTML_HEAD",
			"<html>
		<head>
		<meta charset='UTF-8'>
		<title>广播列表</title>
		<style>#image{width:600px;height:400px;margin:50px auto;}</style>
		</head>
		<body>");

	define("HTML_TAIL",
			"</body>
		</html>");

	$final = $form;
	$num = 0;
	$filepath = "/var/www/html/ljc/resource/public/broadcast/RadioList.txt";
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
				$form = "暂时未有广播频道可播放";
				$final = $form;
				break;
			}

			$form = "
			<form method='get' action='switch.php'>
			<tr>
			<td align=right>$num</td>
			<td>$line</td>
			<input type='hidden' name='radioNum' value=$num>
			<input type='hidden'  name='code'  value=$code>
			<input type='hidden' name='command_rc' value='radio'>
			<td><input type='submit' name='B1' value='播放'></td>
			</tr>
			</form>";

			$final .=  $form;
		}
		fclose($handle);
	}

	$html = HTML_HEAD . $final . HTML_TAIL;
	echo $html;
}