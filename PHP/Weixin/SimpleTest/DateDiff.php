<?php
require_once dirname(__FILE__).'/../common/Common.php';
require_once dirname(__FILE__).'/../model/CommonModel.php';

// $date1 = strtotime("2010-01-01");
// $date2 = strtotime("2015-01-01");
// $day=round(($date2-$date1)/3600/24);
// echo $day;

$userId=3;
$STO = new SingleTableOperation("user_birthday");
$res = $STO->getOneObject(array("UserId"=>$userId));
// echo $res['Birthday'];
$date1 = time();
$date2 = strtotime($res['Birthday']);
// var_dump($date1);

echo date("Y-m-d", $date1)."\n";
$day = ceil(round(($date1-$date2)/3600/24/365));
$day = (string)$day;
// var_dump($res);
var_dump($day);
?>