<?php
include "function.php";

if( $_POST["report_user"]["title"] == "" ) {
        echo '<script>alert("法條名稱尚未輸入!!");</script>';
echo '<script>document.location.href="expose_reasons_edit.php?id='.$_POST["id"].'";</script>';
        exit;
}

if( $_POST["report_user"]["code"] == "" ) {
	        echo '<script>alert("法條代碼尚未輸入!!");</script>';
		echo '<script>document.location.href="expose_reasons_edit.php?id='.$_POST["id"].'";</script>';
		        exit;
}

if( $_POST["unexpose_reason"]["active"] == "1" )
	$active = "t";
else
	$active = "f";
$pdo->prepare("UPDATE `expose_reasons` set `name`=?,`code`=?,`active`=? where `id`=?")->execute(array($_POST["report_user"]["title"],$_POST["report_user"]["code"],$active,$_POST["id"]));
        echo '<script>alert("資料已更新!");</script>';
echo '<script>document.location.href="expose_reasons.php";</script>';
        exit;
?>
