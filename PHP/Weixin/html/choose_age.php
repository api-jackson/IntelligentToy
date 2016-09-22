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
if($command == "download_song")
{
	$handler = opendir ("/var/www/html/ljc/resource/public/song");
}
// if($command == "download_story")
// {
// 	$handler = opendir ("/var/www/html/ljc/resource/public/story");
// }
// if($command == "download_music")
// {
// 	$handler = opendir ("/var/www/html/ljc/resource/public/music");
// }

while(($fileName = readdir($handler)) !== false)
{
	if($fileName[0] != "."){
		$num++;
		$submitStr = $num."岁";
		$dir = "/var/www/html/ljc/resource/public/song/Age_".$num;
		$form = "
		<form method='GET' action='download.php'>
		<tr>
		<input type='hidden' name='fileName' value=$fileName>
		<input type='hidden'  name='code'  value=$code>
		<input type='hidden' name='command' value=$command>
		<input type='hidden' name='age' value=$num>
		<td><input type='submit' name='B1' value=$submitStr></td>
		</tr>
		</form>";

		$final .=  $form;}
}

$html = HTML_HEAD . $final . HTML_TAIL;
echo $html;

?>