<?php
include "function.php";


if( $_POST["report_user"]["title"] == "" ) {
        echo '<script>alert("單位電話尚未輸入!!");</script>';
echo '<script>document.location.href="reason_edit.php?id='.$_POST["id"].'";</script>';
        exit;
}


$pdo->prepare("UPDATE `department` set `dphone`=? where `id`=?")->execute(array($_POST["report_user"]["title"],$_POST["id"]));
        echo '<script>alert("資料已更新!");</script>';
echo '<script>document.location.href="departments.php";</script>';
        exit;
?>
