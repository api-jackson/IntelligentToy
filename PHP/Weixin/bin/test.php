<?php


//获取客服列表
function getKfList_(){
	$url = 'https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token=KNJB5PSD48lYbC-oiRCmdGdG5cTQQcu2IC2ohG32xvroCk-wWvbF_3mpVrCv3L5Z59gAgCX-H8j7sqVIv8Ryiq6CnMjkkDH-2qU688J-QOvtxComcS5GtMk-LxfTaPQwJFHjAFAMIK';
		try {
			$ret = doHttpsCurlGetRequest($url);
			// $retData = json_decode($ret, true);
			// if(!$retData || $retData['errcode']) {
			// 	if($retData['errcode'] == 40014) {
			// 		$token = tokenStub::getToken(true);
			// 	}
			// } else {
				return $ret;
			// }
		} catch (Exception $e) {
			return "error";
		}
		
		
}


function doHttpsCurlGetRequest($url, $data = array(), $timeout = 10) {
	if($url == "" || $timeout <= 0){
		return false;
	}
	if($data != array()) {
		$url = $url . '?' . http_build_query($data);
	}

	$con = curl_init((string)$url);
	curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($con, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($con, CURLOPT_HEADER, false);
	curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($con, CURLOPT_TIMEOUT, (int)$timeout);

	return curl_exec($con);
}

$result = getKfList_();
echo $result;

//https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token=KNJB5PSD48lYbC-oiRCmdGdG5cTQQcu2IC2ohG32xvroCk-wWvbF_3mpVrCv3L5Z59gAgCX-H8j7sqVIv8Ryiq6CnMjkkDH-2qU688J-QOvtxComcS5GtMk-LxfTaPQwJFHjAFAMIK

?>


