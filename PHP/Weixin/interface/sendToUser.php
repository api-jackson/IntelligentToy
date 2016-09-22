<?php
require_once dirname(__FILE__) . '/../common/Common.php';
require_once dirname(__FILE__) . '/../class/tokenStub.php';
require_once dirname(__FILE__) . '/../model/CommonModel.php';
require_once dirname(__FILE__) . '/../class/WeChatCallBackWJ.php';

function uploadMedia($type, $fileName)
{
	$ACCESS_TOKEN = tokenStub::getToken();
	
	/**
	 * 上传多媒体文件至微信服务器，获取media_id
	 */
	$fileUploadURL = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$ACCESS_TOKEN&type=$type";
	$filedata = array("media"=>"@".$fileName);
	$fileCode = doHttpsCurlUploadRequest($fileUploadURL, $filedata);
	interface_log(INFO, EC_OK, $fileCode);
	interface_log(INFO, EC_OK, "\n----------|".substr($fileCode, 11,2)."|-------\n");
	interface_log(INFO, EC_OK, "requst wx to get mediaId");
	while(substr($fileCode, 11,2) == "-1")
	{
		$fileUploadURL = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$ACCESS_TOKEN&type=$type";
		$filedata = array("media"=>"@".$fileName);
		$fileCode = doHttpsCurlUploadRequest($fileUploadURL, $filedata);
		interface_log(INFO, EC_OK, "\n----------|".substr($fileCode, 11,2)."|-------\n");
		interface_log(INFO, EC_OK, "requst wx server busy");
	}
	$fileRes = json_decode($fileCode, true);
	$mediaId = $fileRes['media_id'];
	return $mediaId;
}

/**
 *  * 向用户发送多媒体文件
 * @param int $userId: 用户在数据库中的ID，不是微信的openid
 * @param string $type: 发送文件的类型，有 voice, video
 * @param string $fileName: 准备发送文件的全路径名
 * @param array $data: 当传输文件时，$data['fileName'] 是准备发送文件的全路径名；当发送消息时，$data['content'] 是准备发送给用户的内容
 * @return Ambigous <boolean, mixed>
 */
function sendToUser($userId, $type, $data)
{
	$ACCESS_TOKEN = tokenStub::getToken();
	$toUserURL = WX_API_HTTPS_URL . "message/custom/send?access_token=$ACCESS_TOKEN";
	
	/**
	 * 向用户发送消息
	 */
	$user = new User();
	$wxUserId = $user->getWxUserIdFromUserId($userId);
	/* 发送多媒体文件消息 */
	if($type=="voice" || $type=="image")
	{
		$mediaId = uploadMedia($type, $data['fileName']);
		$info = array("touser"=>$wxUserId, "msgtype"=>$type, $type=>array("media_id"=>$mediaId));
		$codeInfo = json_encode($info);
		var_dump($codeInfo);	
		$res = doHttpsCurlPostRequest($toUserURL, $codeInfo);
		var_dump($res);
	}
	/* 发送视频消息 */
	if($type=="video")
	{
		$mediaId = uploadMedia($type, $data['fileName']);
		$info = array("touser"=>$wxUserId, "msgtype"=>$type, $type=>array("media_id"=>$mediaId));
		$codeInfo = json_encode($info);
		var_dump($codeInfo);
		$res = doHttpsCurlPostRequest($toUserURL, $codeInfo);
		var_dump($res);
	}
	/* 发送文本消息 */
	if($type=="text")
	{
		$info = array("touser"=>$wxUserId, "msgtype"=>$type, $type=>array("content"=>$data['content']));
		$codeInfo = ch_json_encode($info);
		var_dump($codeInfo);
		$res = doHttpsCurlPostRequest($toUserURL, $codeInfo);
		var_dump(json_decode($res)['errmsg']);
		break;
	}	
}

// echo sendToUser(1, "image", array('fileName'=>"/var/www/html/ljc/resource/device/101/101.jpg"));