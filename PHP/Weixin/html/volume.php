<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>音量控制</title>
    <script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
   
    <style>
        body{
            width: 100%;
        }
        .content{
            width: 50%;
            margin: 0 20%;
        }
        .minus,.add{
            margin-top: 5px;
            width: 100px;
            height: 130px;
            float: left;
        }
        .add{
            margin-left: 20px;
        }
        .minus{
            margin-right: 20px;
        }
        .jindu li{
            list-style: none;
            width: 20px;
            height: 156px;
            background-color: #cccccc;
            float: left;
            margin: 5px;
        }
        .jindu img{
            width: 20px;
            height: 156px;
            margin: 5px;
        }
    </style>
    <script type="application/javascript">
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


    </script>
    <script>
        window.onload = function(){


            var oLi = document.getElementsByTagName('li');
            var oMinus = document.getElementsByClassName('minus')[0];
            var oAdd = document.getElementsByClassName('add')[0];
            var i = 4;
            for(var j=0;j<i;j++){
            oLi[j].style.backgroundColor = 'lightskyblue';
            }
            
            res = $.ajax({
                type:"POST",
                url:"http://112.74.16.163/ljc/Weixin/html/processHTML.php",
                data:{code:$_GET['code'],command:"set_volume_4"},
                async:false
            });
            
            oMinus.onclick = function(){
                if(i>0)
                {
                	i--;
                    oLi[i].style.backgroundColor = '#CCC';
		            res = $.ajax({		
		            	 type:"POST",
		                 url:"http://112.74.16.163/ljc/Weixin/html/processHTML.php",
		                 data:{code:$_GET['code'],command:"volume_down"},
		                 async:false
             		});
            	}
            };
            oAdd.onclick = function() 
            {
                if (i<7) {
                    oLi[i].style.backgroundColor = 'lightskyblue';
                    i++;
		            res = $.ajax({		
		            	 type:"POST",
		                 url:"http://112.74.16.163/ljc/Weixin/html/processHTML.php",
		                 data:{code:$_GET['code'],command:'volume_up'},
		                 async:false
            		});
                }
            };

        };
    </script>
</head>
<body>
<div class="content">
    <img class="minus" src="image/vol_sub_d.png" />
        <ul class="jindu">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    <img class="add" src="image/vol_add_d.png" />
</div>
</body>
</html>