<?php
include "function.php";

if( $_POST["report_user"]["title"] == "" ) {
        echo '<script>alert("違規事實名稱尚未輸入!!");</script>';
echo '<script>document.location.href="illegalities_edit.php?id='.$_POST["id"].'";</script>';
        exit;
}


if( $_POST["unexpose_reason"]["active"] == "1" )
	        $active = "t";
else
	        $active = "f";


$pdo->prepare("UPDATE `illegalities` set `name`=?,`active`=? where `id`=?")->execute(array($_POST["report_user"]["title"],$active,$_POST["id"]));
        echo '<script>alert("資料已更新!");</script>';
echo '<script>document.location.href="illegalities.php";</script>';
        exit;
?>
