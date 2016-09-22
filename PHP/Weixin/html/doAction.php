<?php 
	$deviceId = $_POST['code'];

	header('content-type:text/html;charset=utf-8');
	$fileInfo=$_FILES['myfile'];
	$maxSize=4194304;//允许的最大值
	$allowExt=array('wav','wma','mp3','ogg','ape','fla','mid','jpg');
	//1.判断错误号
	if($fileInfo['error'==0]){
		echo "hello";
		//判断上传文件的大小
		if($fileInfo['size']>$maxSize){
			echo('上传文件过大');
		}
		$ext=pathinfo($fileInfo['name'],PATHINFO_EXTENSION);
		if(!in_array($ext, $allowExt)){
			echo('非法文件类型');
		}
		//判断文件是否通过HTTP POST方式上传
		if(!is_uploaded_file($fileInfo['tmp_name'])){
			echo('文件不是通过HTTP POST方式上传');
		}

		/* 查找文件夹是否存在，若不存在，则新建一个文件夹，然后再打开该文件夹 */
		$path = "/var/www/html/ljc/resource/device/" . $deviceId;
		if(!($open = is_dir($path)));
		{
			mkdir($path, 0777);
		}
		$path = "/var/www/html/ljc/resource/device/" . $deviceId . "/uploads";
		if(!($open = is_dir($path)));
		{
			mkdir($path, 0777);
		}

		$dest=$path.'/'.$fileInfo['name'];
		if(move_uploaded_file($fileInfo['tmp_name'], $dest)){
			echo '文件上传成功';
		}else{
			echo'文件上传失败';
		}
	}else {
		//匹配错误信息
		switch ($fileInfo['error']){
			case 1:
				echo '上传文件超过了PHP配置文件中upload_max_filesize选项的值';
				break;
			case 2:
				echo '上传文件超过了MAX_FILE_SIZE限制的大小';
				break;
			case 3:
				echo '上传文件部分被上传';
				break;
			case 4:
				echo '没有选择上传文件';
				break;
			case 6:
				echo '没有找到上传的文件';
				break;
			case 7:
			default:
				echo $fileInfo['error'].'+系统错误';
				break;
		}
	}
?>