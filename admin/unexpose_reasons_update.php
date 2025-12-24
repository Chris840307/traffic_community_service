<?php
include "function.php";

if( $_POST["report_user"]["title"] == "" ) {
        echo '<script>alert("內容尚未輸入!!");</script>';
echo '<script>document.location.href="reason_edit.php?id='.$_POST["id"].'";</script>';
        exit;
}

if( $_POST["unexpose_reason"]["active"] == "1" )
	$active = "t";
else
	$active = NULL;
$pdo->prepare("UPDATE `unexpose_reasons` set `name`=?,`active`=? where `id`=?")->execute(array($_POST["report_user"]["title"],$active,$_POST["id"]));
        echo '<script>alert("資料已更新!");</script>';
echo '<script>document.location.href="unexpose_reasons.php";</script>';
        exit;
?>
