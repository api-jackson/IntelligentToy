<?php 
require_once dirname(__FILE__).'/../common/Common.php';
require_once dirname(__FILE__).'/../model/CommonModel.php';
require_once dirname(__FILE__).'/../network/Client_Output.php';

$wxUserId = $_GET['WXUserId'];


$str = "
<html>
<body>
<meta charset='UTF-8'>
<form action='uploadTo.php' method='post'
enctype='multipart/form-data'>
<label for='file'>请选择您要上传的文件</label>
<input type='file' name='file' id='file' />
<input type='hidden'  name='WXUserId'  value=$wxUserId> 
<input type='hidden'  name='command'  value='upload'> 
<br />
<input type='submit' name='submit' value='上传' />
</form>

</body>
</html>
";

echo $str;
?>