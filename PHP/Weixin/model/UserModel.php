<?php
require_once dirname(__FILE__) . '/../common/Common.php';
require_once dirname(__FILE__) . '/../class/tokenStub.php';

Class User
{
	private $_userId = null;
	private $_userName = null;
	private $_wxUserId = null;
	private $_password = null;
	
	/**
	 * 用户关注时，添加用户
	 * @param unknown $_wxUserId: 用户的微信OpenId
	 * @return boolean: 成功添加，返回true; 失败时，返回false
	 */
	public function addUser($_wxUserId)
	{
		try 
		{
			/* 设置数据库信息 */
			$STO = new SingleTableOperation();
			$STO->setTableName("user_data");
			
			/* 从微信获取用户昵称 */
			$nickName = $this->getUserNickName($_wxUserId);
			if($nickName === false)
			{
				interface_log(ERROR, EC_OTHER, "UserInfo Access Failed");
				return false;
			}
			
			/* 当用户不存在时，添加用户。否则，更新用户昵称 */
			$userExist = $STO->addObjectIfNoExist(array("WXUserId" => $_wxUserId, "UserName" => $nickName),
					array("WXUserId" => $_wxUserId));
			if($userExist === true)
			{
				$STO->updateObject(array("UserName" => $nickName), array("WXUserId" => $_wxUserId));
			}
			
			return true;
		} 
		catch (Exception $e) 
		{
			interface_log(ERROR, EC_DB_OP_EXCEPTION, "operate ctoken error! msg:" . $e->getMessage());
			return false;
		}

	}
	
	/**
	 * 获取微信用户的昵称
	 * @param String $_wxUserId:微信用户的OpenId
	 * @return boolean|mixed:微信用户的昵称
	 */
	private function getUserNickName($_wxUserId) {
		
		$ACCESS_TOKEN = tokenStub::getToken();
		
		/* 组装参数 */
		$para = array(
			"access_token" => $ACCESS_TOKEN,
			"openid" => $_wxUserId
		);
		
		/* 拼装 URL */
		$url = WX_API_HTTPS_URL . "user/info";
		interface_log(DEBUG, 0, "url:" . $url . "  req data:" . json_encode($para));
			
		/* 使用 https 协议发送 GET 请求 */
		$ret = doHttpsCurlGetRequest($url, $para);
		interface_log(DEBUG, 0, "response data:" . $ret);
		
		/* 解析json数据包 */
		$retData = json_decode($ret, true);
		if(!$retData || $retData['errcode']) {
			interface_log(ERROR, EC_OTHER, "requst wx to get user's nickname error");
			return false;
		}
		
		/* 返回用户的昵称 */
		return $retData['nickname'];
	}
	
	/**
	 * 根据wxUserId查询userId 
	 * @param string $wxUserId: 微信用户的openId
	 * @return int $userId: 用户的ID
	 */
	public function getUserIdFromWxUserId($wxUserId)
	{
		$STO = new SingleTableOperation();
		$STO->setTableName("user_data");
		$result = $STO->getOneObject(array("WXUserId"=>$wxUserId));
		$userId = $result['UserId'];
		
		return $userId;
	}
	
	/**
	 * 根据用户的 userId 获取用户的微信 openId
	 */
	public function getWxUserIdFromUserId($userId) 
	{
		$STO = new SingleTableOperation();
		$STO->setTableName("user_data");
		$result = $STO->getOneObject(array("UserId"=>$userId));
		$wxUserId = $result['WXUserId'];
		
		return $wxUserId;
	}
	
	/**
	 * 根据 code 获取用户的微信 openId
	 * @param unknown $code
	 * @return NULL|Ambigous <>
	 */
	public function getWxUserIdFromWebpageCode($code)
	{
		$STO = new SingleTableOperation();
		$STO->setTableName("user_data");
		$result = $STO->getOneObject(array("WebpageCode"=>$code));
		if($result == null)
			return null;
		else 
			return $result['WXUserId'];
	}
	
	/**
	 * 设置用户的微信 openId 的 code
	 * @param unknown $wxUserId
	 * @param unknown $code
	 */
	public function setWxUserIdFromWebpageCode($wxUserId, $code)
	{
		$STO = new SingleTableOperation();
		$STO->setTableName("user_data");
		$STO->updateObject(array("WebpageCode"=>$code), array("WXUserId"=>$wxUserId));
	}
	
	/**
	 * 根据用户的微信 openId 获取用户的年龄，若用户未登记生日信息，则返回 null
	 * @param unknown $wxUserId
	 */
	public function getUserAge($wxUserId)
	{
		$user = new User();
		$userId = $user->getUserIdFromWxUserId($wxUserId);
		$STO = new SingleTableOperation("user_birthday");
		$res = $STO->getOneObject(array("UserId"=>$userId));
		if($res == null)
		{
			return null;
		}
		else 
		{
			$date_now = time();
			$date_user = strtotime($res['Birthday']);
			$age = ceil(($date_now-$date_user)/3600/24/365);
			$age = (string)$age;
			return $age;
		}

	}
}
