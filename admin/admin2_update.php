<?php
include "function.php";

if(strlen($_POST["report_user"]["password"]) <= 5 && $_POST["report_user"]["password"] != "" ) {
        echo '<script>alert("新密碼最少要六個字元");</script>';
echo '<script>document.location.href="admin2_users_edit.php?id=8";</script>';
        exit;
} else {
if( $_POST["report_user"]["password"] != $_POST["report_user"]["password_confirmation"] ) {
        echo '<script>alert("新密碼兩次輸入不一樣!");</script>';
echo '<script>document.location.href="admin2_users_edit.php?id=8";</script>';
        exit;
}}

if( strlen($_POST["report_user"]["password"]) >= 6 ) {
$pdo->prepare("UPDATE `user_list` set `pwd`=? where `id`=?")->execute(array($_POST["report_user"]["password"],$_POST["id"]));
}

$resultd = $pdo->query("select * from `department` where `id`='".$_POST["report_user"]["department_id"]."'");
$rowd = $resultd->fetch(PDO::FETCH_ASSOC);

$pdo->prepare("UPDATE `user_list` set `name`=?,`department_id`=?,`department`=? where `id`=?")->execute(array($_POST["report_user"]["name"],$_POST["report_user"]["department_id"],$rowd["dname"],$_POST["id"]));
        echo '<script>alert("資料已更新!");</script>';
echo '<script>document.location.href="admin2_users.php";</script>';
        exit;
?>
