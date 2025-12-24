<?php
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=car6", "root", "2u6u/ru8");
        $pdo->query('set names utf8;');
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
    if (isset($_SERVER)) {
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } else {
        $ip = $_SERVER['REMOTE_ADDR'];
      }
    } else {
      if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
      } elseif (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
      } else {
        $ip = getenv('REMOTE_ADDR');
      }
    }

$nowtime = date("Y-m-d H:i:s");
$sn = $_REQUEST["sn"];
$car = $_REQUEST["car"];
$cdate = $_REQUEST["cdate"];
$msg = $sn.' - '.$car.' - '.$cdate;

$result = $pdo->query("select * from `cases` where `sn`='".$sn."' and `full_car_number`='".$car."' and `cam_date`='".$cdate."' and `flag`='顯示'");
$row = $result->fetch(PDO::FETCH_ASSOC);
if( $row["id"] != "" ) {
	$pdo->query("INSERT INTO hotsum ( msg, cdate,flag,ip) VALUES ('".$msg."','".$nowtime."',1,'".$ip."')");
	$act = 'ok';
	$pic1 = $row["pic1"];
	$pic2 = $row["pic2"];
	if( trim($pic2) != "" && $pic2 != NULL )
		$pic2 = $row["pic2"];
	else
		$pic2 = '';
} else {
	$pdo->query("INSERT INTO hotsum ( msg, cdate,flag,ip) VALUES ('".$msg."','".$nowtime."',0,'".$ip."')");
	$act = 'nook';
	$pic1 = '';
	$pic2 = '';
}
    $arr = array ('act'=>$act,'pic1'=>$pic1,'pic2'=>$pic2);
    echo json_encode($arr);
?>
