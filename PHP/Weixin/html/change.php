<?php

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
				<div id='image'><img src='img/child.jpg'></div>
	
				<form method='post' action='redirect.php'>
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