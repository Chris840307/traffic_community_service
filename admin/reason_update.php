<?php
include "function.php";

if( $_POST["report_user"]["title"] == "" ) {
        echo '<script>alert("說明尚未輸入!!");</script>';
echo '<script>document.location.href="reason_edit.php?id='.$_POST["id"].'";</script>';
        exit;
}

if( $_POST["report_user"]["code"] == "" ) {
        echo '<script>alert("代碼尚未輸入!!");</script>';
echo '<script>document.location.href="reason_edit.php?id='.$_POST["id"].'";</script>';
        exit;
}


$pdo->prepare("UPDATE `reason_code` set `title`=?,`code`=? where `id`=?")->execute(array($_POST["report_user"]["title"],$_POST["report_user"]["code"],$_POST["id"]));
        echo '<script>alert("資料已更新!");</script>';
echo '<script>document.location.href="reason_list.php";</script>';
        exit;
?>
