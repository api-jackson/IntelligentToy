<?php
require_once dirname(__FILE__).'/../common/Common.php';
require_once dirname(__FILE__).'/../model/CommonModel.php';

/* 网页鉴权，获取访问该网页的用户的openid，并查询数据库获取相应的userId */

$code = $_GET['code'];
// $wxUserId = oauth($code);
// $user = new User();
// $userId = $user->getUserIdFromWxUserId($wxUserId);


define("HTML_HEAD",
		"<html>
		<head>
		<meta charset='UTF-8'>
		<title>推送列表</title>
		<style>#image{width:600px;height:400px;margin:50px auto;}</style>
		</head>
		<body>");

define("HTML_TAIL",
		"</body>
		</html>");

$final = $form;
$command = $_GET['command'];

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
/***************************************************/

/* 获取年龄 */
$age = $user->getUserAge($wxUserId);

if($age == null)
{
	$final = "请先设置宝宝生日";
}
else 
{
	if($command == "download_song")
	{
		$dir = "/var/www/html/ljc/resource/public/song/Age_".$age;
		$handler = opendir ($dir);
	}
	if($command == "download_story")
	{
		$dir = "/var/www/html/ljc/resource/public/story/Age_".$age;
		$handler = opendir ($dir);
	}
	if($command == "download_music")
	{
		$dir = "/var/www/html/ljc/resource/public/music/Age_".$age;
		$handler = opendir ($dir);
	}
	
	while(($fileName = readdir($handler)) !== false)
	{
		if($fileName[0] != "."){
			$num++;
	
			$form = "
			<form method='post' action='processHTML.php'>
			<tr>
			<td align=right>$num</td>
			<td>$fileName</td>
			<input type='hidden' name='fileName' value=$fileName>
			<input type='hidden' name='filedir' value=$dir>
			<input type='hidden'  name='code'  value=$code>
			<input type='hidden' name='command' value=$command>
			<td><input type='submit' name='B1' value='推送'></td>
			</tr>
			</form>";
	
			$final .=  $form;}
	}
}


$html = HTML_HEAD . $final . HTML_TAIL;
echo $html;

?>