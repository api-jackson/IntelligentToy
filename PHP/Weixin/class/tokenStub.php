<?php
require_once dirname(__FILE__) . '/../common/Common.php';

class tokenStub {
	/**
	 * getToken, 获取$account所指公众账号的 access_token
	 * @param string $force
	 * @return unknown|boolean|mixed
	 */
	public static function getToken($force = false) {		
		try {
			$STO = new SingleTableOperation();
			$STO->setTableName("weixin_token");
			/*
			 * $force 为 true 表示不检查表 weixin_token 中是否有未过期的 access_token，
			 * 相反，$force 为 false，则先检查表 weixin_token 中是否有未过期的 access_token，
			 * 如果有则直接返回，否则在请求公众平台接口获取
			 * */
			if($force == false) {
				$ret = $STO->getObject();
				interface_log(DEBUG, 0, "token data get from ctoken: " . json_encode($ret));
				if(count($ret) == 1) {
					$token = $ret[0]['Access_Token'];
					$expire = $ret[0]['expire'];
					$addTimestamp = $ret[0]['addTimestamp'];
					$current = time();
					if($addTimestamp + $expire - 300 > $current) {
						return $token;
					}	
				}
			}
			/* 组装获取access_token 的请求参数 */
			$para = array(
				"grant_type" => "client_credential",
				"appid" => WX_API_APPID,
				"secret" => WX_API_APPSECRET
			);
			/* 拼装 URL */
			$url = WX_API_HTTPS_URL . "token";
			interface_log(DEBUG, 0, "url:" . $url . "  req data:" . json_encode($para));
			
			/* 使用 https 协议发送 GET 请求 */
			$ret = doHttpsCurlGetRequest($url, $para);
			interface_log(DEBUG, 0, "response data:" . $ret);
			
			$retData = json_decode($ret, true);
			if(!$retData || $retData['errcode']) {
				interface_log(ERROR, EC_OTHER, "requst wx to get token error");
				return false;
			}
			
			/* 从返回的数据中获取得到的 access_token 和它的过期时间，更新 weixin_token 表 */
			$token = $retData['access_token'];
			$expire = $retData['expires_in'];
			$STO->updateObject(array('Access_Token' => $token, 'expire' => $expire, 'addTimeStamp' => time()), array('AppId' => WX_API_APPID));
			
			return $token;
			
		} catch (DB_Exception $e) {
			interface_log(ERROR, EC_DB_OP_EXCEPTION, "operate ctoken error! msg:" . $e->getMessage());
			return false;
		}
		
		
	}
}

// $token = tokenStub::getToken();
// echo "token: " .$token."\n";