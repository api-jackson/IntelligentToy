<?php 
$code = $_GET['code'];

$str = "
<html>
<body>
<meta charset='UTF-8'>
<form action='processHTML.php' method='post'
enctype='multipart/form-data'>
<label for='file'>请选择您要上传的文件</label>
<input type='file' name='file' id='file' />
<input type='hidden'  name='code'  value=$code> 
<input type='hidden'  name='command'  value='upload'> 
<br />
<input type='submit' name='submit' value='上传' />
</form>

</body>
</html>
";

echo $str;
?>