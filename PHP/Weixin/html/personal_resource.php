<?php

$code = $_GET['code'];

$pageinfo = "
	<!DOCTYPE html>
	<html lang='en'>
	<head>
		<meta charset='UTF-8'>
		<title></title>
		<style type='text/css'>
		div#container{
		width:100%;
		height:100%;
		}
		div#left-side{
		float:left;
		width:50%;
		height: 50%;
		}
		div#right-side{
		float: left;
		width:50%;
		height: 50%;
		}
		</style>
		<link href='dandan.css' type='text/css' rel='stylesheet'>
	</head>
		<body>
			<div id='container'>
				<div id='left-side' >
					<form action='list.php' method='get' class='image'>
						<input type='image'  src='image/song.jpg'  width='300'height='300'>
						<input type='hidden' name='code' value=$code>
						<input type='hidden' name='command' value='personal_song'>
						<div>儿歌</div>
					</form>
					<form action='list.php' method='get' class='image'>
						<input type='image' src='image/classic.jpg'  width='300'height='300'>
						<input type='hidden' name='code' value=$code>
						<input type='hidden' name='command' value='personal_music'>
						<div>经典名曲</div>
					</form>
				</div>
				<div id='right-side'>
					<form action='list.php' method='get' class='image'>
						<input type='image' src='image/story.jpg'  width='300'height='300'>
						<input type='hidden' name='code' value=$code>
						<input type='hidden' name='command' value='personal_story'>
						<div>故事</div>
					</form>
				</div>
			</div>
		</body>
	</html>
";

echo $pageinfo;
?>