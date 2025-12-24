<?php
include "function.php";
if( $_POST["report_user"]["current_password"] == "" ) {
        echo '<script>alert("請輸入目前密碼!");</script>';
echo '<script>document.location.href="edit_password.php";</script>';
        exit;
} else {
if( $row_user["pwd"] != $_POST["report_user"]["current_password"] ) {
        echo '<script>alert("目前密碼錯誤!");</script>';
echo '<script>document.location.href="edit_password.php";</script>';
        exit;
}
}

if(strlen($_POST["report_user"]["password"]) <= 5 ) {
        echo '<script>alert("新密碼最少要六個字元");</script>';
echo '<script>document.location.href="edit_password.php";</script>';
        exit;
} else {
if( $_POST["report_user"]["password"] != $_POST["report_user"]["password_confirmation"] ) {
        echo '<script>alert("新密碼兩次輸入不一樣!");</script>';
echo '<script>document.location.href="edit_password.php";</script>';
        exit;
}}

$pdo->prepare("UPDATE `user_list` set `pwd`=? where `acc`=? and `pwd`=?")->execute(array($_POST["report_user"]["password"],$row_user["acc"],$row_user["pwd"]));
$_SESSION["acc"]="";
$_SESSION["pwd"]="";
unset($_SESSION["acc"]);
unset($_SESSION["pwd"]);
                                        unset($_COOKIE['acc']);
                                        setcookie('acc', null, -1 );
                                        setcookie('acc', "", time()-3600);
                                        unset($_COOKIE['pwd']);
                                        setcookie('pwd', null, -1);
                                        setcookie('pwd', "", time()-3600);
        echo '<script>alert("密碼已更新,請重新登入!");</script>';
echo '<script>document.location.href="index.php";</script>';
        exit;
?>
