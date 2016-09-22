<?php 
require_once dirname(__FILE__).'/../common/Common.php';
require_once dirname(__FILE__).'/../model/CommonModel.php';
require_once dirname(__FILE__).'/../network/Client_Output.php';

function processHTML(){
/* 网页鉴权，获取访问该网页的用户的openid，并查询数据库获取相应的wxUserId，并根据wxUserId获取设备ID */
/**************************************************/
	$code = $_POST['code'];
	$user = new User();
	$wxUserId = $user->getWxUserIdFromWebpageCode($code);
	if($wxUserId == null)
	{
		$wxUserId = oauth($code);
		$user->setWxUserIdFromWebpageCode($wxUserId, $code);
	}
	$device = new Device();
	$deviceId = $device->getDeviceId($wxUserId);
/***************************************************/
	
/* 根据 command 判断接下来的指令 */
	$command = $_POST['command'];
	
	/* 调节音量的页面 */
	if(($command == 'volume_up') || ($command == 'volume_down') || ($command == 'set_volume_4'))
	{
		clientOutput(json_encode(array("deviceNum"=>$deviceId, "command"=>$command)));
		echo $code."\n".$command;
	}
	
	/* 登记生日的页面 */
	if($command == 'register_birthday')
	{
		$userId = $_POST['userId'];
		
		/**
		 * 将用户登记的信息录入数据库
		*/
		$STO = new SingleTableOperation();
		$STO->setTableName("user_birthday");
		
		$Birthday = $_POST['birthday'];
		if($Birthday == false)
		{
			$Birthday = date("Y-m-d");
		}
		
		/* 根据生日计算年龄 */
		$date_now = time();
		$date_user = strtotime($Birthday);
		$Age = ceil(($date_now-$date_user)/3600/24/365);
		
		$dataExist = $STO->addObjectIfNoExist(array("UserId"=>$userId, "NickName"=>$_POST['nickName'], "Sex"=>$_POST['sex'], "Birthday"=>$Birthday, "Age"=>$Age), array("UserId"=>$userId));
		if($dataExist === true)
		{
			$STO->updateObject(array("NickName"=>$_POST['nickName'], "Sex"=>$_POST['sex'], "Birthday"=>$Birthday, "Age"=>$Age), array("UserId" => $userId));
		}
		
		/* 显示“登记成功”的HTML界面 */
		$register ="
					<html>
					<head>
    					<meta charset='UTF-8'>
    					<title>个人信息填写</title>
					</head>
		
		
					<body>
						<font size='6' face='楷体' color='black'><center>登记成功</center></font>
		
					</body>
					</html>";
		
		echo $register;
	}
	
	/* 修改生日的页面 */
	if($command == 'change_birthday')
	{
		$userId = $_POST['userId'];
		
		/* 当用户点击“修改”按钮时，显示的HTML界面 */
		$submit =
		"<html>
		<head>
		<meta charset='UTF-8'>
		<!--	<meta name='viewport' content='width=device-width, initial-scale=1'> -->
		<title>个人信息填写</title>
		<style>#image{width:600px;height:400px;margin:50px auto;}</style>
		<script src='laydate/laydate/laydate.js'></script>
		</head>
		
		
		<body>
		<font size='6' face='楷体' color='black'><center>个人信息填写</center></font>
		<div id='image'><img src='image/child.jpg'></div>
		
		<form method='post' action='processHTML.php'>
		<table align='center' border='1' bordercolor='pink' width='50%' cellpadding='1' cellspacing='0'>
		
		<tr>
		<td align=right>昵     称:</td>
		<td><input type='text' name='nickName'></td>
		</tr>
		
		<tr>
		<td align=right> 性    别:</td>
		<td><input type='radio' name='sex' value='男' checked>男
		<input type='radio' name='sex' value='女' >女</td>
		</tr>
		
		<tr>
		<td align='right'>出生年月:</td>
		<td colspan='3'><input id='hello' class='laydate-icon' name='birthday'></td>
		<script>
		laydate({
		elem: '#hello', //目标元素。由于laydate.js封装了一个轻量级的选择器引擎，因此elem还允许你传入class、tag但必须按照这种方式 '#id .class'
		event: 'focus' //响应事件。如果没有传入event，则按照默认的click
		});
		</script>
		</tr>
		
		<input type='hidden' name='userId' value=$userId>
		<input type='hidden' name='command' value='register_birthday'>
		
		<tr>
		<td colspan='4'>
		<center><input type='submit' name='B1' value='提交'>&nbsp &nbsp &nbsp
		<input type='reset' name='B2' value='重置'></center>
		</td>
		</tr>
		
		</table>
		</form>
		</body>
		</html>";
		
		echo $submit;
	}
	
	/* 云端资源：推送歌曲 */
	if($command == 'download_song')
	{
		$filedir = $_POST['filedir']."/";
		$filename = $_POST['fileName'];
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"download_song","filename"=>$filedir . $filename));
		echo "<html>
				<head>
				<meta charset='UTF-8'>
				<title>歌曲列表</title>
				<style>#image{width:600px;height:400px;margin:50px auto;}</style>
				</head>
				<body>
					推送成功
				</body>
				</html>";
		clientOutput($str);
	}
	
	/* 云端资源：推送故事 */
	if($command == 'download_story')
	{
		$filedir = $_POST['filedir']."/";
		$filename = $_POST['fileName'];
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"download_story","filename"=>$filedir . $filename));
		echo "<html>
				<head>
				<meta charset='UTF-8'>
				<title>故事列表</title>
				<style>#image{width:600px;height:400px;margin:50px auto;}</style>
				</head>
				<body>
					推送成功
				</body>
				</html>";
		clientOutput($str);
	}
	
	/* 云端资源：推送音乐 */
	if($command == 'download_music')
	{
		$filedir = $_POST['filedir']."/";
		$filename = $_POST['fileName'];
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"download_music","filename"=>$filedir . $filename));
		echo "<html>
				<head>
				<meta charset='UTF-8'>
				<title>经典名曲列表</title>
				<style>#image{width:600px;height:400px;margin:50px auto;}</style>
				</head>
				<body>
					推送成功
				</body>
				</html>";
		clientOutput($str);
	}
	
	/* 公仔资源：删除歌曲 */
	if($command == 'delete_song')
	{
		$filename = $_POST['fileName'];
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"delete_song","filename"=>$filename));
		echo "<html>
				<head>
				<meta charset='UTF-8'>
				<title>歌曲列表</title>
				<style>#image{width:600px;height:400px;margin:50px auto;}</style>
				</head>
				<body>
					删除成功
				</body>
				</html>";
		clientOutput($str);
	}
	
	/* 公仔资源：删除经典名曲 */
	if($command == 'delete_music')
	{
		$filename = $_POST['fileName'];
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"delete_music","filename"=>$filename));
		echo "<html>
				<head>
				<meta charset='UTF-8'>
				<title>歌曲列表</title>
				<style>#image{width:600px;height:400px;margin:50px auto;}</style>
				</head>
				<body>
					删除成功
				</body>
				</html>";
		clientOutput($str);
	}
	
	/* 公仔资源：删除故事 */
	if($command == 'delete_story')
	{
		$filename = $_POST['fileName'];
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"delete_story","filename"=>$filename));
		echo "<html>
				<head>
				<meta charset='UTF-8'>
				<title>歌曲列表</title>
				<style>#image{width:600px;height:400px;margin:50px auto;}</style>
				</head>
				<body>
					删除成功
				</body>
				</html>";
		clientOutput($str);
	}
	
	/* 遥控公仔：播放歌曲 */
	if($command == 'song')
	{
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"song"));
		clientOutput($str);
	}
	
	/* 遥控公仔：播放故事 */
	if($command == 'story')
	{
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"story"));
		clientOutput($str);
	}
	
	/* 遥控公仔：播放经典名曲 */
	if($command == 'music')
	{
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"music"));
		clientOutput($str);
	}
	
	/* 遥控公仔：播放广播列表 */
	if($command == 'radio')
	{
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"download_radiolist","filename"=>"/var/www/html/ljc/resource/public/broadcast/radioList.json"));
		clientOutput($str);
		
		define("HTML_HEAD",
				"<html>
		<head>
		<meta charset='UTF-8'>
		<title>广播列表</title>
		<style>#image{width:600px;height:400px;margin:50px auto;}</style>
		</head>
		<body>");
		
		define("HTML_TAIL",
				"</body>
		</html>");
		
		$final = $form;
		$num = 0;
		$filepath = "/var/www/html/ljc/resource/public/broadcast/RadioList.txt";
		$handle = fopen($filepath,"r");
		if($handle)
		{
			while(!feof($handle))
			{
				$num++;
				$line = fgets($handle);
				$filename = $line;
		
				if($num == 1 && $filename == "")
				{
					$form = "暂时未有广播频道可播放";
					$final = $form;
					break;
				}
		
				$form = "
				<form method='get' action='switch.php'>
				<tr>
				<td align=right>$num</td>
				<td>$line</td>
				<input type='hidden' name='radioNum' value=$num>
				<input type='hidden'  name='code'  value=$code>
				<input type='hidden' name='command_rc' value='radio'>
				<td><input type='submit' name='B1' value='播放'></td>
				</tr>
				</form>";
		
				$final .=  $form;
			}
			fclose($handle);
		}
		
		$html = HTML_HEAD . $final . HTML_TAIL;
		echo $html;
	}
	
	/* 遥控公仔：播放广播 */
	if($command == 'broadcast')
	{
		$radioNum = $_POST['radioNum'];
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"broadcast","radioNum"=>$radioNum));
		clientOutput($str);
	}
	if($command == 'pause_broadcast')
	{
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"pause_broadcast","radioNum"=>$radioNum));
		clientOutput($str);
	}
	
	
	if($command == 'previous')
	{
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"previous"));
		clientOutput($str);
	}
	
	if($command == 'next')
	{
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"next"));
		clientOutput($str);
	}
	
	if($command == 'pause_song')
	{
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"pause_song"));
		clientOutput($str);
	}
	
	if($command == 'pause_music')
	{
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"pause_music"));
		clientOutput($str);
	}
	
	if($command == 'pause_story')
	{
		$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"pause_story"));
		clientOutput($str);
	}
	
	if($command == 'upload')
	{
		if ($_FILES["file"]["error"] > 0)
		{
			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
		else
		{
// 			echo "Upload: " . $_FILES["file"]["name"] . "<br />";
// 			echo "Type: " . $_FILES["file"]["type"] . "<br />";
// 			echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
// 			echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
		
			/* 查找文件夹是否存在，若不存在，则新建一个文件夹，然后再打开该文件夹 */
			$filepath = "/var/www/html/ljc/resource/device/".$deviceId;
			if(!($open = is_dir($filePath)));
			{
				mkdir($filePath, 0777);
				chmod($filepath, 0777);
			}
			/* 查找文件夹是否存在，若不存在，则新建一个文件夹，然后再打开该文件夹 */
			$filepath = "/var/www/html/ljc/resource/device/".$deviceId."/upload";
			if(!($open = is_dir($filePath)));
			{
				mkdir($filePath, 0777);
				chmod($filepath, 0777);
			}
			
			if (file_exists($filepath."/" . $_FILES["file"]["name"]))
			{
// 				echo $_FILES["file"]["name"] . " already exists. ";
			}
			else
			{
				move_uploaded_file($_FILES["file"]["tmp_name"],
						$filepath. "/" . $_FILES["file"]["name"]);
// 				echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
				$filename = $filepath. "/" . $_FILES["file"]["name"];
				$str = json_encode(array("deviceNum"=>$deviceId,"command"=>"push_resource","filename"=>$filename));
				echo "<html>
				<head>
				<meta charset='UTF-8'>
				<title>上传歌曲</title>
				<style>#image{width:600px;height:400px;margin:50px auto;}</style>
				</head>
				<body>
					歌曲上传成功
				</body>
				</html>";
				clientOutput($str);
			}
		}
	}
}

processHTML();
?>

