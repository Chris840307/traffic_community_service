<?php
session_start();
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=car8", "root", "2u6u/ru8");
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
$result = $pdo->prepare("SELECT * FROM `cases_tmp` where `skey`=? and `finish`=?");
$result->execute(array($_SESSION["skey"],1));
$allRecrods = $result->rowCount();
if( $allRecrods >= 1 ) {
echo "1";
} else {
echo "0";
}
?>
