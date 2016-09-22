<?php
require_once dirname(__FILE__) . '/WeChatCallBack.php';
require_once dirname(__FILE__) . '/../model/CommonModel.php';
require_once dirname(__FILE__) . '/../interface/action.php';
require_once dirname(__FILE__) . '/../interface/saveVoiceMsg.php';

class WeChatCallBackWJ extends WeChatCallBack
{
	private $_event;
	private $_content;
	private $_eventKey;
	private $_mediaId;
	private $_scanResult;
	
	public function init($postObj)
	{
		if(false == parent::init($postObj))
		{
			interface_log(ERROR, EC_OTHER, "init fail!");
			return false;
		}
		
		if($this->_msgType == 'event')
		{
			$this->_event = (string)$postObj->Event;
			$this->_eventKey = $postObj->EventKey;

			if($this->_event == 'CLICK')
			{
				$this->_eventKey = (string)$postObj->EventKey; 
			}
			
			if($this->_event == 'SCAN')
			{
				$this->_eventKey = $postObj->EventKey;

			}
			
			if($this->_event == 'scancode_push')
			{
				$this->_eventKey = $postObj->EventKey;
				$this->_scanResult = $postObj->ScanResult;
			}
		}
		
		if($this->_msgType == 'text')
		{
			$this->_content = (string)$postObj->Content;
		}
		
		if($this->_msgType == 'voice')
		{
			$this->_mediaId = (string)$postObj->MediaId;
		}
		
		return true;
	}
	
	/**
	 * 此类的处理函数，重载父类的处理函数
	 * 
	 */
	public function process()
	{
		if($this->_event == 'subscribe')
		{
			$user = new User();
			if(($user->addUser($this->_fromUserName)) === true)
			{
				if($this->_eventKey != false)
				{
					list($qrscene, $qrcode) = explode("_", $this->_eventKey);
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'hardware'))
					{
						return $this->makeText("other:非法二维码");
					}
					else
					{
						if(null == $qrcode)
							return $this->makeText("欢迎关注!");
						
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$qrcode));
							
						return $this->makeText("硬件编号：" . $qrcode . "\n" . $str);
					}
				}
				else 
					return $this->makeText("欢迎关注!");
			}
			else 
				return $this->makeText("初始化失败");
		}
		if($this->_event == 'CLICK')
		{
			switch ($this->_eventKey)
			{
				/**
				 * 此处根据自定义菜单的key值，选择不同的操作
				 */
				
				case "hardware":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, $this->_eventKey))
					{
						return $this->makeText("非法状态");
					}
					else
					{
						return $this->makeText($mode->getUserMode($this->_fromUserName));
					}
					break;
					
				case "take_photo":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'take_photo'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
				case "tell_story":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'tell_story'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
				case "video":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'video'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
				/* 遥控公仔：播放歌曲 */
				case "song":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'song'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
				/* 遥控公仔：播放歌曲 */
				case "pause_song":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'pause_song'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
				/* 遥控公仔：播放故事 */
				case "story":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'story'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
				/* 遥控公仔：播放故事 */
				case "pause_story":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'pause_story'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
				/* 遥控公仔：播放经典名曲 */
				case "music":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'music'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
				/* 遥控公仔：播放经典名曲 */
				case "pause_music":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'pause_music'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
				/* 遥控公仔：播放广播列表 */
				case "radio":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'radio'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
				/* 遥控公仔：播放广播列表 */
				case "broadcast":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'broadcast'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
				/* 遥控公仔：播放广播列表 */
				case "pause_broadcast":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'pause_broadcast'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
				/* 调节音量：增大音量 */
				case "volume_up":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'volume_up'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
				/* 调节音量：减小音量 */
				case "volume_down":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'volume_down'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
				/* 遥控公仔：上一首 */
				case "previous":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'previous'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
				/* 遥控公仔：下一首 */
				case "next":
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, 'next'))
					{
						return $this->makeText("other:非法操作");
					}
					else
					{
						$action = $mode->getUserMode($this->_fromUserName);
						$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
							
						return $this->makeText($str);
					}
					break;
					
					
				default:
					$mode = new Mode();
					if(true != $mode->setUserMode($this->_fromUserName, $this->_eventKey))
					{
						return $this->makeText("other:非法状态");
					}
					else
					{
						return $this->makeText("other:" . $mode->getUserMode($this->_fromUserName));
					}
					break;
			}
		}
		
		if($this->_event == 'SCAN')
		{
			$mode = new Mode();
 			if(true != $mode->setUserMode($this->_fromUserName, 'hardware'))
			{
				return $this->makeText("other:非法二维码");
			}
			else
			{
				$action = $mode->getUserMode($this->_fromUserName);
				$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_eventKey));
					
				return $this->makeText("硬件编号：" . $this->_eventKey . "\n" . $str);
			}

		}
		
		if($this->_event == 'scancode_push')
		{
			$mode = new Mode();
			if(true != $mode->setUserMode($this->_fromUserName, 'hardware'))
			{
				return $this->makeText("other:非法二维码");
			}
			else
			{
				$action = $mode->getUserMode($this->_fromUserName);
				$str = actionProcess($action, array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_scanResult));
					
				return $this->makeText("硬件编号：" . $this->_eventKey . "\n" . $str);
			}
		}
		
		if($this->_msgType == 'text')
		{
			$mode = new Mode();
			$action = $mode->getUserMode($this->_fromUserName);
			
			$str = actionProcess('text', array('fromUserName'=>$this->_fromUserName, 'content'=>$this->_content));
			
			return $this->makeText($str);
		}
		
		if($this->_msgType == 'voice')
		{
			$str = actionProcess('voice', array('fromUserName'=>$this->_fromUserName, 'mediaId'=>$this->_mediaId));
			
// 			saveVoiceMsg($this->_mediaId, array());
			return $this->makeText($str);
			

		}
		

		
	}
}