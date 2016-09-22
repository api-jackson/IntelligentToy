<?php 
require_once dirname(__FILE__).'/../common/Common.php';
require_once dirname(__FILE__).'/../model/CommonModel.php';

/* 网页鉴权，获取访问该网页的用户的openid，并查询数据库获取相应的userId */
$code = $_GET['code'];
$user = new User();
$wxUserId = $user->getWxUserIdFromWebpageCode($code);
if($wxUserId == null)
{
	$wxUserId = oauth($code);
	$user->setWxUserIdFromWebpageCode($wxUserId, $code);
}
$userId = $user->getUserIdFromWxUserId($wxUserId);

/* $submit:注册信息的HTML界面 */
$submit =
		"<html>
			<head>
				<meta charset='UTF-8'>
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





$STO = new SingleTableOperation();
$STO->setTableName('user_birthday');

/**
 * 若数据库中存在该用户登记的信息，则显示该信息
 */
if(($STO->getCount(array('UserId'=>$userId))) && (!$_GET["command"]))
{
	$res = $STO->getOneObject(array('UserId'=>$userId));
	$nickName = $res['NickName'];
	$sex = $res['Sex'];
	$birthday = $res['Birthday'];
	
	/* $show:显示信息的HTML界面 */
	$show =
		"<html>
			<head>
				<meta charset='UTF-8'>
				<title>个人信息填写</title>
				<style>#image{width:600px;height:400px;margin:50px auto;}</style>
			</head>
	
	
			<body>
				<font size='6' face='楷体' color='black'><center>个人信息填写</center></font>
				<div id='image'><img src='image/child.jpg'></div>
				<form method='get' action='birthday.php'>
				<table align='center' border='1' bordercolor='pink' width='50%' cellpadding='1' cellspacing='0'>
					
				<tr>
					<td align=right>昵     称:</td>
					<td>$nickName</td>
				</tr>
				<tr>
					<td align=right> 性    别:</td>
					<td>$sex</td>
				</tr>
	
				<tr>
					<td align='right'>出生年月:</td>
					<td colspan='3'>$birthday</td>					
				</tr>
				
				<input type='hidden' name='code' value=$code>
				<input type='hidden' name='command' value='change_birthday'>

				<tr>
					<td colspan='4'>
					<center><input type='submit' name='B1' value='修改'>&nbsp &nbsp &nbsp</center>
					</td>
				</tr>
	
				</table>
				</form>
			</body>
		</html>";
	echo $show;
}

/**
 * 否则，显示登记信息
 */
if(!($STO->getCount(array('UserId'=>$userId))) || ($_GET["command"] == "change_birthday"))
{	
	echo $submit;
}


?>