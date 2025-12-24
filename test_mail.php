<?php
require_once("vendor/phpmailer/phpmailer/src/Exception.php");
require_once("vendor/phpmailer/phpmailer/src/PHPMailer.php");
require_once("vendor/phpmailer/phpmailer/src/SMTP.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

/*
$servers = array(
    array("smtp.gmail.com", 465),
    array("smtp.gmail.com", 587),
);

foreach ($servers as $server) {
    list($server, $port) = $server;
    echo "<h1>Attempting connect to <tt>$server:$port</tt></h1>\n";
    flush();
    $socket = fsockopen($server, $port, $errno, $errstr, 10);
    if(!$socket) {
      echo "<p>ERROR: $server:$portsmtp - $errstr ($errno)</p>\n";
    } else {
      echo "<p>SUCCESS: $server:$port - ok</p>\n";
    }
    flush();
}
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
$email = 'savinna@gmail.com';
$txt = "AAAAAAAAAAA";
$mail->ClearAddresses();
$mail->addAddress($email,'信箱驗證');
$mail->isHTML(true);
$mail->Subject = "認證信件: 新竹市交通違規檢舉系統";
$mail->Body = $txt;
if (!$mail->send()) {

	    echo "Mailer Error: " . $mail->ErrorInfo;

} else {

	    echo "Message sent!";

}
?>
