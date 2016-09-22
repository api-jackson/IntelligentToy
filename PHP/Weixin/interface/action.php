<?php
require_once dirname(__FILE__) . '/../common/Common.php';
require_once dirname(__FILE__) . '/../model/CommonModel.php';
require_once dirname(__FILE__) . '/../network/Client_Output.php';
require_once dirname(__FILE__) . '/saveVoiceMsg.php';

/**
 * 此函数根据状态的不同，进行不同的处理
 * @param string $actionMode: 当前用户的状态
 * @param array() $data: 数组：fromUserName: 微信用户名；content: 用户发送的内容
 * @return string $res: 状态处理的结果
 */
function actionProcess($actionMode, $data = array())
{
	if($actionMode == 'normal')
	{
		$res = "normal";
	}
	
	if($actionMode == 'hardware')
	{
		$device = new Device();
		$res = $device->setDeviceOwner($data['fromUserName'], $data['content']);
	}
	
	if($actionMode == 'take_photo')
	{
		
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else 
		{
			$res = WJ_HINT_PLEASE_WAIT;
			$command = json_encode(array('deviceNum'=>$deviceId,'command'=>"take_photo"));
			/***************************/
			/* 测试专用，command非json格式时 */
// 			$command = $deviceId . ":" . $actionMode;
			/***************************/
			clientOutput($command);
		}

	}
	
	if($actionMode == 'tell_story')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"tell_story"));
			/***************************/
			/* 测试专用，command非json格式时 */
// 			$command = $deviceId . ":" . $actionMode;
			/***************************/
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	
	if($actionMode == 'voice')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$filename = saveVoiceMsg($data['mediaId'], array('fromUserName'=>$data['fromUserName']));
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"leave_message", 'filename'=>$filename));
			
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	
	if($actionMode == 'video')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"video"));
				
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	/* 遥控公仔：播放歌曲 */
	if($actionMode == 'song')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"song"));
	
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	/* 遥控公仔：播放歌曲 */
	if($actionMode == 'pause_song')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"pause_song"));
	
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	/* 遥控公仔：播放故事 */
	if($actionMode == 'story')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"story"));
	
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	/* 遥控公仔：播放故事 */
	if($actionMode == 'pause_story')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"pause_story"));
	
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	/* 遥控公仔：播放经典名曲 */
	if($actionMode == 'music')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"music"));
	
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	/* 遥控公仔：播放经典名曲 */
	if($actionMode == 'pause_music')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"pause_music"));
	
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	/* 遥控公仔：播放广播列表 */
	if($actionMode == 'radio')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"download_radiolist","filename"=>"/var/www/html/ljc/resource/public/broadcast/radioList.json"));
	
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	/* 遥控公仔：播放广播列表 */
	if($actionMode == 'broadcast')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"broadcast", "radioNum"=>1));
	
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	/* 遥控公仔：播放广播列表 */
	if($actionMode == 'pause_broadcast')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"pause_broadcast", "radioNum"=>1));
	
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	/* 调节音量：增大音量 */
	if($actionMode == 'volume_up')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"volume_up"));
	
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	/* 调节音量：减小音量 */
	if($actionMode == 'volume_down')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"volume_down"));
	
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	

	/* 遥控公仔：上一首 */
	if($actionMode == 'previous')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"previous"));
	
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	/* 遥控公仔：下一首 */
	if($actionMode == 'next')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"next"));
	
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	if($actionMode == 'text')
	{
		$device = new Device();
		$deviceId = $device->getDeviceId($data['fromUserName']);
		/* 检查是否已添加硬件 */
		if(null == $deviceId)
			$res = WJ_HINT_HAVE_NO_DEVICE;
		else 
		{
			$command = json_encode(array('deviceNum'=>$deviceId, 'command'=>"content", 'content'=>$data['content']));
			
			clientOutput($command);
			$res = WJ_HINT_PLEASE_WAIT;
		}
	}
	
	return $res;
}