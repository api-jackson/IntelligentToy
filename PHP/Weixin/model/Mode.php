<?php
require_once dirname(__FILE__) . '/../common/Common.php';
require_once dirname(__FILE__) . '/CommonModel.php';

class Mode
{
	private $_userId;
	private $_userMode;

	/**
	 * 初始化Mode类参数 
	 * @param unknown $_wxUserId
	 * @param unknown $_userMode
	 */
	public function initUserMode($_wxUserId)
	{
		
		/* 根据WXUserId查询UserId */		
		$user = new User();
		$this->_userId = $user->getUserIdFromWxUserId($_wxUserId);
	}
	
	/**
	 * 设置用户的状态
	 * @param string $_wxUserId: 将要改变状态的微信用户ID
	 * @param string $_userMode: 用户要转换的状态
	 * @return string $_userMode: 用户已转换的状态
	 */
	public function setUserMode($_wxUserId, $_userMode)
	{
		$this->initUserMode($_wxUserId);
		$this->_userMode = $_userMode;
		
		$STO = new SingleTableOperation();		
		$STO->setTableName("user_mode");
		
		/* 判断user_mode表中是否存在此UserId，若不存在，则添加；否则更新该表信息 */
		if(!($STO->getCount(array('UserId'=>$this->_userId))))
		{
			$this->addUserMode();
		}
		else 
		{
			$STO->updateObject(array('Mode'=>$this->_userMode, 'addTimestamp'=>time()),
					array('UserId'=>$this->_userId));
		}
		return $this->_userMode;
	}
	
	/**
	 * 添加UserId到user_mode表中
	 */
	private function addUserMode()
	{
		$STO = new SingleTableOperation();
		$STO->setTableName("user_mode");
		$STO->addObject(array('UserId'=>$this->_userId, 
				'Mode'=>$this->_userMode, 'addTimestamp'=>time()));
	}
	
	/**
	 * 获取当前用户的状态
	 * @param string $_wxUserId: 微信的用户ID
	 * @return Ambigous <>: 当前用户的状态
	 */
	public function getUserMode($_wxUserId)
	{
		$this->initUserMode($_wxUserId);
		
		$STO = new SingleTableOperation();
		$STO->setTableName("user_mode");
		
		$mode = $STO->getOneObject(array('UserId'=>$this->_userId));
		
		if(($mode['addTimestamp']+300) < time())
		{
			$this->setUserMode($_wxUserId, 'normal');
			$mode = $STO->getOneObject(array('UserId'=>$this->_userId));
		}
		return $mode['Mode'];
	}
}