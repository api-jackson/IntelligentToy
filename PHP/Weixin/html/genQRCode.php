<?php
require_once dirname(__FILE__).'/../common/Common.php';
require_once dirname(__FILE__).'/../model/CommonModel.php';
require_once dirname(__FILE__).'/../network/Client_Output.php';

$wifiName = $_GET['WiFiName'];
$wifiPassword = $_GET['WiFiPassword'];

/* 网页鉴权，获取访问该网页的用户的openid，并查询数据库获取相应的userId */
$code = $_GET['code'];

$input = "<html>
		<head>
			<meta charset='UTF-8'>
			<title>二维码生成</title>
			<style>#image{width:600px;height:400px;margin:50px auto;}</style>
		</head>

			<body>
			<font size='6' face='楷体' color='black'><center>WiFi二维码生成器</center></font>
			<form method='get' action='genQRCode.php'>
				<table align='center' border='1' bordercolor='pink' width='50%' cellpadding='1' cellspacing='0'>

				<tr>
					<td align=right>WiFi名称:</td>
					<td><input type='text' name='WiFiName'></td>
				</tr>
				<tr>
					<td align=right>WiFi密码:</td>
					<td><input type='text' name='WiFiPassword'></td>
				</tr>
				<tr>
                	<td align=right> 更新资源:</td>
                	<td><input type='radio' name='update' value='N' checked>手动
                	<input type='radio' name='update' value='Y' >自动</td>
                </tr>
				<input type='hidden' name='code' value=$code>
				<tr>
					<td colspan='4'>
					<center><input type='submit' value='提交'>&nbsp &nbsp &nbsp
					<input type='reset' name='B2' value='重置'></center>
					</td>
				</tr>
				</form>
			</body>
			</html>";
			

if($wifiName && $wifiPassword)
{
	$wifi = urlencode("WIFI:T:WPA;S:$wifiName;P:$wifiPassword;");
	$wifiDC = urldecode($wifi);
	
	/* 网页鉴权，获取访问该网页的用户的openid，并查询数据库获取相应的userId */
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
	
	$autoUpdate = $_GET['update'];
	/* 设置玩具自动更新状态 */
	$device->setAutoUpdate($deviceId, $autoUpdate);
	
	$gen = "
			<html>
	<head>
	<meta charset='UTF-8'>
	<title>二维码生成</title>
	<style>#image{width:600px;height:400px;margin:50px auto;}</style>
	</head>
	
	<body>
	<font size='6' face='楷体' color='black'><center>你即将要生成的二维码</center></font>
	
	<table align='center' border='1' bordercolor='pink' width='50%' cellpadding='1' cellspacing='0'>
	
	<tr>
	<td align=right>WiFi名称:</td>
	<td><input type='text' name='WiFiName' value=$wifiName></td>
	</tr>
	<tr>
	<td align=right>WiFi密码:</td>
	<td><input type='text' name='WiFiPassword' value=$wifiPassword></td>
	</tr>
	
	<tr>
	<td colspan='4'>
	<center><a href='http://qr.liantu.com/api.php?text=$wifiDC'>马上生成二维码</a>&nbsp &nbsp &nbsp	
	</td>
	</tr>
	</body>
	</html>";
	
	echo $gen;
}
else
{
	echo $input;
}
