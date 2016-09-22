<?php
require_once dirname(__FILE__) . '/../class/tokenStub.php';
require_once dirname(__FILE__) . '/../common/Common.php';

$startDeviceId = 121;
$endDeviceId = 130;
$filePath = dirname(__FILE__) . "/../resource/temp";

/**
 * 此函数用于获取带参数的二维码图片
 * @param int $startDeviceId: 开始的参数编号
 * @param int $endDeviceId: 结束的参数编号
 * @param string $filePath: 图片保存的路径
 */
function getQRCode($startDeviceId, $endDeviceId, $filePath)
{
	// 	/* 查找文件夹是否存在，若不存在，则新建一个文件夹  */
	if(!($open = is_dir($filePath)));
	{
		mkdir($filePath);
	}
	
	$ACCESS_TOKEN = tokenStub::getToken();
	
	for($deviceId=$startDeviceId; $deviceId<=$endDeviceId; $deviceId++)
	{
		$url = WX_API_HTTPS_URL . "qrcode/create?access_token=$ACCESS_TOKEN";
		$para=array("action_name"=>"QR_LIMIT_SCENE",
				"action_info"=>array("scene"=>array("scene_id"=>$deviceId)));
		$codePara = json_encode($para);
		$res = doHttpsCurlPostRequest($url, $codePara);
		$resArray = json_decode($res, true);
		$getCodepara = array("ticket"=>$resArray['ticket']);
		
		$url = "mp.weixin.qq.com/cgi-bin/showqrcode";
		
	// 	/* 下载文件信息，保存在 $fileInfo 变量中 */
		$fileInfo = downloadWeixinFile($url, $getCodepara);
	
	// 	/* 设置文件名为设备ID，格式为jpg，如：101.jpg */
		$filename = $filePath . "/" . $deviceId . ".jpg";
	// 	/* 保存文件 */
		saveWeixinFile($filename, $fileInfo["body"]);
	}
}

getQRCode($startDeviceId, $endDeviceId, $filePath);