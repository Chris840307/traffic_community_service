<?php
include "function.php";


if( $_POST["report_user"]["title"] == "" ) {
        echo '<script>alert("事由名稱尚未輸入!!");</script>';
echo '<script>document.location.href="search_reason_edit.php?id='.$_POST["id"].'";</script>';
        exit;
}


$pdo->prepare("UPDATE `search_reason` set `name`=? where `id`=?")->execute(array($_POST["report_user"]["title"],$_POST["id"]));
        echo '<script>alert("資料已更新!");</script>';
echo '<script>document.location.href="search_reason.php";</script>';
        exit;
?>
