<?php
session_start();
include "function2.php";
w_sys_log($_SESSION["acc"],$_SESSION["pwd"],'登出系統','登出成功');

$_SESSION["acc"]="";
$_SESSION["pwd"]="";
unset($_SESSION["acc"]);
unset($_SESSION["pwd"]);
if( $_SESSION["remem"] == 0 ) {
                                        setcookie('login_acc', null, -1 );
					setcookie('login_acc', "", time()-3600);
					unset($_COOKIE['login_acc']);
                                        setcookie('login_passwd', null, -1);
					setcookie('login_passwd', "", time()-3600);
					unset($_COOKIE['login_passwd']);
}
					echo '<script>document.location.href="index.php";</script>';
        exit;
?>
