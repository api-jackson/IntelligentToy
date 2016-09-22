<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title></title>
<style type="text/css">
div#container {
	width: 100%;
	height: 100%;
}

div#left-side {
	float: left;
	width: 50%;
	height: 50%;
}

div#right-side {
	float: left;
	width: 50%;
	height: 50%;
}

.previousnext li {
	list-style: none;
	float: left;
}
</style>
<script language="JavaScript">
    </script>
<link href="dandan.css" type="text/css" rel="stylesheet">
</head>
<body>
<script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
	<script>
 var $_GET = (function(){
     var url = window.document.location.href.toString();
     var u = url.split("?");
     if(typeof(u[1]) == "string"){
         u = u[1].split("&");
         var get = {};
         for(var i in u){
             var j = u[i].split("=");
             get[j[0]] = j[1];
         }
         return get;
     } else {
         return {};
     }
 })();
 
        window.onload = function(){
            var oPrevious = document.getElementsByClassName('previous')[0];
            var oNext = document.getElementsByClassName('next')[0];
            var oPlay = document.getElementsByClassName('state')[0];
            var isPlay = true;
            var res;
            var oPause;
            var oStart = $_GET['command_rc'];
            
            if($_GET['command_rc'] == "song")
            {
            	oPause = "pause_song";
            }
            if($_GET['command_rc'] == "music")
            {
            	oPause = "pause_music";
            }
            if($_GET['command_rc'] == "story")
            {
            	oPause = "pause_story";
            }
            if($_GET['command_rc'] == "radio")
            {
            	oPause = "pause_broadcast";
            }

            if($_GET['command_rc'] == "song" || 
                    $_GET['command_rc'] == "music" || 
                    $_GET['command_rc'] == "story")
            {
	            res = $.ajax({
	                type:"POST",
	                url:"http://112.74.16.163/ljc/Weixin/html/processHTML.php",
	                data:{code:$_GET['code'],command:$_GET['command_rc']},
	                async:true
	            });
            }

            if($_GET['command_rc'] == "radio")
            {
                var num = $_GET['radioNum'];
	            res = $.ajax({
	                type:"POST",
	                url:"http://112.74.16.163/ljc/Weixin/html/processHTML.php",
	                data:{code:$_GET['code'],command:"broadcast",radioNum:num},
	                async:true
	            });
            }
            
            oPrevious.onclick = function(){
	            res = $.ajax({		
	            	 type:"POST",
	                 url:"http://112.74.16.163/ljc/Weixin/html/processHTML.php",
	                 data:{code:$_GET['code'],command:"previous"},
	                 async:true
           		});
	            alert("上一曲");
            };
            oNext.onclick = function() 
            {
	            res = $.ajax({		
	            	 type:"POST",
	                 url:"http://112.74.16.163/ljc/Weixin/html/processHTML.php",
	                 data:{code:$_GET['code'],command:"next"},
	                 async:true
           		});
	            alert("下一曲");
            };

	        oPlay.onclick = function()
	        {

            	if (isPlay == true) {
                    oPlay.src="image/start.jpg";
                    isPlay = false;
    	            res = $.ajax({		
   	            	 type:"POST",
   	                 url:"http://112.74.16.163/ljc/Weixin/html/processHTML.php",
   	                 data:{code:$_GET['code'],command:oPause},
   	                 async:true
              		});
                 }else{
                    oPlay.src="image/pause.jpg";
                     isPlay = true;
     	            res = $.ajax({		
   	            	 type:"POST",
   	                 url:"http://112.74.16.163/ljc/Weixin/html/processHTML.php",
   	                 data:{code:$_GET['code'],command:oStart},
   	                 async:true
              		});
                 }
                if(isPlay == false)
                {
                	alert("已暂停");
                }
                if(isPlay == true)
                {
                	alert("开始播放");
                }
            	
             };
        }
    </script>
	<div id="container">
		<script type="text/javascript">

                </script>

		<div id="bottom-side">
			<div>
				<ul class=previousnext>
					<li><img class="previous" src="image/previous.jpg" />
						<div>上一曲</div></li>
					<li><img class="state" src="image/pause.jpg" width = "500" high = "500" />
					</li>
					<li><img class="next" src="image/next.jpg" />
						<div>下一曲</div></li>

				</ul>
			</div>
		</div>
	</div>
</body>
</html>