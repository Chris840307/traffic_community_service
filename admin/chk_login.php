<?php
session_start();
include "function2.php";

$result = $pdo->prepare("SELECT * FROM `user_list` where `acc`=? and `pwd`=?");
$result->execute(array($_POST["report_user"]["username"], $_POST["report_user"]["password"])); 
$row = $result->fetch(PDO::FETCH_ASSOC);
if( $row["id"] == "" ) {
w_ip_1();
w_sys_log($_POST["report_user"]["username"],$_POST["report_user"]["password"],'登入系統','登入失敗');
	echo '<script>alert("帳號或密碼錯誤!");</script>';
	echo '<script>document.location.href="index.php";</script>';
        exit;
} else {
w_sys_log($_POST["report_user"]["username"],$_POST["report_user"]["password"],'登入系統','登入成功');

$cdate = date("Y-m-d H:i:s");
$pdo->prepare("UPDATE `user_list` set `sign_in_count`=`sign_in_count`+1,`last_sign_in_at`=?,`last_sign_in_ip`=? where `acc`=? and `pwd`=?")->execute(array($cdate,$ip,$row["acc"],$row["pwd"]));
$_SESSION["acc"]=$row["acc"];
$_SESSION["pwd"]=$row["pwd"];
if( $_POST["admin_user"]["remember_me"] == 1 ) {
	$_SESSION["remem"] = 1;
unset($_COOKIE['login_acc']);
setcookie('login_acc', null, -1, '/');
unset($_COOKIE['login_passwd']);
setcookie('login_passwd', null, -1, '/');
setcookie("login_acc",$row["acc"],time()+ (10 * 365 * 24 * 60 * 60));
setcookie("login_passwd",$row["pwd"],time()+ (10 * 365 * 24 * 60 * 60));
} else {
	$_SESSION["remem"] = 0;
                                        setcookie('login_acc', null, -1 );
                                        setcookie('login_acc', "", time()-3600);
                                        unset($_COOKIE['login_acc']);
                                        setcookie('login_passwd', null, -1);
                                        setcookie('login_passwd', "", time()-3600);
                                        unset($_COOKIE['login_passwd']);
}

if( $row["flag1"] == 1 )
	echo '<script>document.location.href="cases.php?state=ready&n";</script>';
else if( $row["flag2"] == 1 )
	echo '<script>document.location.href="cases.php?state=reviewing&n";</script>';
else if( $row["flag3"] == 1 )
	echo '<script>document.location.href="complete.php";</script>';
else if( $row["flag4"] == 1 )
	echo '<script>document.location.href="cases.php?n";</script>';
else if( $row["flag5"] == 1 )
	echo '<script>document.location.href="admin_users.php";</script>';
else
	echo '<script>document.location.href="edit_password.php";</script>';
exit;
}
?>
