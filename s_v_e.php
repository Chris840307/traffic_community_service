<?php
session_start();
require_once("vendor/phpmailer/phpmailer/src/Exception.php");
require_once("vendor/phpmailer/phpmailer/src/PHPMailer.php");
require_once("vendor/phpmailer/phpmailer/src/SMTP.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

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

if( $_POST["email"] != "" && preg_match("/^[\w\-\.]+@[\w\-]+(\.\w+)+$/", $_POST["email"])) {
$email = $_POST["email"];
$result = $pdo->prepare("SELECT * FROM `verifications` where `email`=? limit 1");
$result->execute(array($email));
$allRecrods = $result->rowCount();
if( $allRecrods == 0 ) {

$buff = "";
for ($i=0; $i<33; $i++) {

    $d=rand(1,30)%2;

    $buff .= $d ? chr(rand(65,90)) : chr(rand(48,57));
}
$cdate = date("Y-m-d H:i:s");
$pdo->query("INSERT INTO verifications ( id, email, token, cdate ,vdate, ip) VALUES (NULL,'".$email."','".$buff."','".$cdate."',NULL,'".$ip."')");
}

$result = $pdo->prepare("SELECT * FROM `verifications` where `email`=? and `vdate` IS NULL");
$result->execute(array($email));
$allRecrods = $result->rowCount();
if( $allRecrods >= 1 ) {
$row = $result->fetch(PDO::FETCH_ASSOC);
$txt = "";
$txt .= "臺端您好：";
$txt .= "<br><br>";
$txt .= "此為新竹市警察局-交通事故案件便民服務網電子信箱(E-MAIL)認證信，為確保您的電子信箱未遭他人冒（盜）用，確實可以收到我們後續回復的處理>情形，請您點選下列連結進行認證：";
$txt .= "<br><br>";
$txt .= '<a href="https://tra2.hccp.gov.tw/new/verf.php?token='.$row["token"].'">請點選此連結認證您的信箱</a>';
$txt .= "<br><br>";
$txt .= "本信件為自動派送，請勿直接回覆本信件，謝謝！";
$txt .= "<br><br>";
$txt .= "新竹市警察局關心您！";
$txt .= '<br><br>';

$mail = new PHPMailer();
$mail->CharSet = 'UTF-8';
/*
$mail->Host = 'dm.cloudmax.com.tw';
$mail->Port = 25;
$mail->SMTPAuth = true;
$mail->SMTPSecure = "none";
$mail->SMTPAutoTLS = false;
$mail->Username = 'ct18447@dm.cloudmax.com.tw';
$mail->Password = 'CB!c22y^';
*/

$mail= new PHPMailer(); //建立新物件
$mail->IsSMTP(); //設定使用SMTP方式寄信
$mail->SMTPAuth = true; //設定SMTP需要驗證
$mail->Host = "dm.cloudmax.com.tw"; //Gamil的SMTP主機
$mail->Port = 25; //Gamil的SMTP主機的埠號(Gmail為465)。
$mail->CharSet = "utf-8"; //郵件編碼
$mail->Username = 'ct18447@dm.cloudmax.com.tw';
$mail->Password = 'CB!c22y^';
$mail->setFrom('ct18447@dm.cloudmax.com.tw', '新竹市交通事故案件便民服務網');
$mail->ClearAddresses();
$mail->addAddress($email,'信箱驗證');
$mail->isHTML(true);
$mail->Subject = "認證信件: 新竹市交通違規檢舉系統";
$mail->Body = $txt;
$mail->send();
echo "2";
exit;
}

function generateRandomString($length = 32) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	        $charactersLength = strlen($characters);
	        $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
			            $randomString .= $characters[random_int(0, $charactersLength - 1)];
				        }
		    return $randomString;
}

$result = $pdo->prepare("SELECT * FROM `verifications` where `email`=? and `vdate` IS NOT NULL");
$result->execute(array($email));
$allRecrods = $result->rowCount();
if( $allRecrods >= 1 ) {
                $udate = date("Y-m-d H:i:s");
                $pdo->prepare("UPDATE `verifications` set `udate`=?,`uip`=? where `email`=?")->execute(array($udate,$ip,$email));
		if( !isset($_SESSION["skey"] )) {
			$_SESSION["skey"] = generateRandomString();
			$skey = $_SESSION["skey"];
			$cdate = date("Y-m-d H:i:s");
			$pdo->query("INSERT INTO cases_tmp ( skey,email,ip,finish ) VALUES ('".$skey."','".$email."','".$ip."',0)");
		} else {
			$_SESSION["skey"] = generateRandomString();
			$skey = $_SESSION["skey"];
			$pdo->prepare("UPDATE `cases_tmp` set `skey`=?,`ip`=?,`finish`=? where `email`=?")->execute(array($skey,$ip,0,$email));
		}

	echo 'ok,'.$skey;
	exit;
}
}
?>
