<?php
include "function.php";

if( $_POST["report_user"]["title"] == "" ) {
        echo '<script>alert("車種名稱尚未輸入!!");</script>';
echo '<script>document.location.href="car_types_edit.php?id='.$_POST["id"].'";</script>';
        exit;
}

if( $_POST["report_user"]["code"] == "" ) {
	        echo '<script>alert("車種代碼尚未輸入!!");</script>';
		echo '<script>document.location.href="car_types_edit.php?id='.$_POST["id"].'";</script>';
		        exit;
}

if( $_POST["unexpose_reason"]["active"] == "1" )
	        $active = "t";
else
	        $active = "f";

$pdo->prepare("UPDATE `car_types` set `name`=?,`code`=?,`active`=? where `id`=?")->execute(array($_POST["report_user"]["title"],$_POST["report_user"]["code"],$active,$_POST["id"]));
        echo '<script>alert("資料已更新!");</script>';
echo '<script>document.location.href="car_types.php";</script>';
        exit;
?>
