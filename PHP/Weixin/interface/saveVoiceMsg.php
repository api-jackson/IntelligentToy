<?php
require_once dirname(__FILE__) . '/../common/Common.php';
require_once dirname(__FILE__) . '/../model/CommonModel.php';
require_once dirname(__FILE__) . '/../class/tokenStub.php';

/**
 * 保存用户的留言信息到 /resource/UserId 文件夹下
 * @param string $mediaId: 微信发送过来的 MediaId
 * @param array() $data: 数组：fromUserName: 发送留言消息的用户的微信ID
 * @return string $mediaId: 微信发送过来的 MediaId
 */
function saveVoiceMsg($mediaId, $data = array())
{
	/* 获取 access_token，拼装 URL 地址 */
	$ACCESS_TOKEN = tokenStub::getToken();
	$para = array(
			"access_token" => $ACCESS_TOKEN,
			"media_id" => $mediaId
	);
	
	$url = WX_API_HTTP_URL."media/get";
	
	/* 下载文件信息，保存在 $fileInfo 变量中 */
	$fileInfo = downloadWeixinFile($url, $para);
	
	
	/**
	 * 设置保存的文件夹名为用户的 UserId
	 */
	/* 根据WXUserId查询UserId */	
	$user = new User();
	$userId = $user->getUserIdFromWxUserId($data['fromUserName']);
	
	$filePath = "/var/www/html/ljc/resource/user/" . $userId;
	
	/* 查找文件夹是否存在，若不存在，则新建一个文件夹，然后再打开该文件夹 */
	if(!($open = is_dir($filePath)));
	{
		mkdir($filePath, 0777);
	}
	
	/* 设置文件名为时间，格式为amr，如：20150819_121751.amr */
	$filename = $filePath . "/" . date("Ymd_His") . ".amr";
	
	/* 保存文件 */
	saveWeixinFile($filename, $fileInfo["body"]);
	return $filename;
}