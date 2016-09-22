<?php

// echo date("Ymd_His");

$code = $_GET['code'];

$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx3309b26898b21a67&secret=3130556e5ef4925f1474af1538929867&code=$code&grant_type=authorization_code";

$timeout = 10;
$con = curl_init((string)$url);
curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($con, CURLOPT_HEADER, false);
curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
curl_setopt($con, CURLOPT_TIMEOUT, (int)$timeout);

$codeInfo = curl_exec($con);

$info = json_decode($codeInfo, true);

curl_close($con);

echo $info['openid'];
?>