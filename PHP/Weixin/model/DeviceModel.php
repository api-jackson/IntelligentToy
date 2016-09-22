<?php
require_once dirname(__FILE__)."/../common/Common.php";
require_once dirname(__FILE__) . '/CommonModel.php';

Class Device
{
	private $_userId;
	private $_deviceId;
	
	/**
	 * 初始化函数
	 * @param string $_wxUserId: 用户的微信ID
	 */
	private function initDevice($_wxUserId)
	{
			
		/* 根据WXUserId查询UserId */		
		$user = new User();
		$this->_userId = $user->getUserIdFromWxUserId($_wxUserId);
		
	}
	
	/**
	 * 获取当前设备的用户ID
	 * @param string $_wxUserId:  用户的微信ID
	 * @param int $_deviceId: 设备ID
	 */
	public function getDeviceOwner($_deviceId)
	{	
		$STO = new SingleTableOperation();
		$STO->setTableName("device_data");
		
		$owner = $STO->getOneObject(array('DeviceId'=>$_deviceId));
		return $owner['DeviceOwner'];
	}
	
	/**
	 * 设置设备的拥有者
	 * @param string $_wxUserId:  用户的微信ID
	 * @param int $_deviceId: 设备ID
	 */
	public function setDeviceOwner($_wxUserId, $_deviceId)
	{
		$this->initDevice($_wxUserId);
		$this->_deviceId = (string)$_deviceId;
		
		$STO = new SingleTableOperation();
		$STO->setTableName("device_data");
		
		/* 检测指定的设备ID是否存在 */
		$deviceExist = $STO->getCount(array('DeviceId'=>$this->_deviceId));
		if(($deviceExist == "0"))
		{
			return WJ_HINT_HARDWARE_NOT_EXISTS;
		}
		
		/* 检测指定的用户是否已有绑定设备ID */
		$ownDevice = $STO->getCount(array('DeviceOwner'=>$this->_userId));		
		if($ownDevice != "0")
		{
			$result = $STO->getOneObject(array('DeviceOwner'=>$this->_userId));
			// 若扫描的硬件是用户已添加的硬件
			if($result['DeviceId'] == $this->_deviceId)
				return WJ_HINT_HAVING_THIS_HARDWARE;
			// 若扫描的硬件不是用户已添加的硬件
			else
				return WJ_HINT_HAVING_OTHER_HARDWARE . $result['DeviceId'];
		}
		
		/* 检测指定的设备ID是否已有用户 */
		$result = $STO->getOneObject(array('DeviceId'=>$this->_deviceId));
		$owner = $result['DeviceOwner'];				
		if(null != $owner)
		{
			return WJ_HINT_ADD_HARDWARE_REPEATED;
		}
	
		$STO->updateObject(array('DeviceOwner'=>$this->_userId),
				array('DeviceId'=>$this->_deviceId));
		return WJ_HINT_ADD_SUCCESSFULLY;
	}
	
	/**
	 * 获取当前微信用户绑定的设备ID
	 * @param unknown $_wxUserId: 用户的微信ID
	 * @return string: 若用户已绑定设备，则返回设备ID，否则提示用户绑定设备
	 */
	public function getDeviceId($_wxUserId)
	{
		$this->initDevice($_wxUserId);
		
		$STO = new SingleTableOperation('device_data');
		$result = $STO->getOneObject(array('DeviceOwner'=>$this->_userId));
		
// 		if(null == $res['DeviceId'])
// 			return null;
// 		else
			return $result['DeviceId'];
	}
	
	/**
	 * 设置玩具的联网状态
	 * @param unknown $_deviceId: 玩具的ID
	 * @param unknown $state: 在线(online) 或 离线(offline)
	 */
	public function setDeviceState($_deviceId, $state)
	{
		$STO = new SingleTableOperation('device_data');
		$STO->updateObject(array('DeviceState'=>$state), array('DeviceId'=>$_deviceId));
		
	}
	
	/**
	 * 获取玩具的自动更新状态
	 * @param unknown $_deviceId: 玩具的ID
	 * @return string: 若玩具设置自动更新，则返回Y，否则返回N
	 */
	public function getAutoUpdate($_deviceId)
	{
		$STO = new SingleTableOperation('device_data');
		$result = $STO->getOneObject(array("DeviceId"=>$_deviceId));
		
		return $result['AutoUpdate'];
	}
	
	/**
	 * 设置玩具的自动更新状态
	 * @param unknown $_deviceId: 玩具的ID
	 * @param unknown $_state: 玩具的自动更新状态：Y为自动更新，N为手动更新
	 */
	public function setAutoUpdate($_deviceId, $_state)
	{
		$STO = new SingleTableOperation('device_data');
		$STO->updateObject(array('AutoUpdate'=>$_state), array("DeviceId"=>$_deviceId));
	}
}

?>