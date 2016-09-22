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
            <form action='switch.php' method='get' class='image'>
                <input type='image'  src='image/song.jpg'  width='300'height='300'>
				<input type='hidden' name='code' value=$code>
				<input type='hidden' name='command_rc' value='song'>
                <div>播放儿歌</div>
            </form>
            <form action='switch.php' method='get' class='image'>
                <input type='image' src='image/classic.jpg'  width='300'height='300'>
                <input type='hidden' name='code' value=$code>
                <input type='hidden' name='command_rc' value='music'>
                <div>播放经典名曲</div>
            </form>
        </div>
        <div id='right-side'>
            <form action='switch.php' method='get' class='image'>
                <input type='image' src='image/story.jpg'  width='300'height='300'>
                <input type='hidden' name='code' value=$code>
                <input type='hidden' name='command_rc' value='story'>
                <div>播放故事</div>
            </form>
            <form action='radio.php' method='get' class='image'>
                <input type='image' src='image/broadcast.jpg'  width='300'height='300'>
                <input type='hidden' name='code' value=$code>
                <input type='hidden' name='command' value='radio'>
                <div>播放广播</div>
            </form>
        </div>
    </div>
</body>
</html>
";

echo $pageinfo;
?>