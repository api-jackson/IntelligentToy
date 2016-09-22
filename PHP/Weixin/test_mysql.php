<?php
$mysqli = new mysqli("localhost", "root", "123456", "android");
$mysqli->init();
echo $mysqli->character_set_name();
// $rs = new mysqli_result();
$mysqli->query("set names utf8");
echo "原始数据：";
$rs = $mysqli->query("SELECT * FROM t_user", MYSQLI_ASSOC);
// $rs->fetch_assoc();
// $mysqli->close();



while($row = $rs->fetch_assoc()){
	echo $row["userName"] ." : " . $row["password"] ."\n";
}

$rs = $mysqli->query("INSERT INTO t_user SET userName='我', password='123'");
echo "插入后的数据：";

$rs = $mysqli->query("SELECT * FROM t_user", MYSQLI_ASSOC);
while($row = $rs->fetch_assoc()){
	echo $row["userName"] ." : " . $row["password"] ."\n";
}