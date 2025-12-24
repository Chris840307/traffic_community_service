<?php
include "function.php";

if( $_POST["report_user"]["title"] == "" ) {
        echo '<script>alert("內容尚未輸入!!");</script>';
echo '<script>document.location.href="unexpose_reasons_add.php";</script>';
        exit;
}

if( $_POST["unexpose_reason"]["active"] == "1" )
	$active = "t";
else
	$active = NULL;

if( isset( $_POST["report_user"]["title"]) && $n == 0 ) {
        $cdate=date("Y-m-d H:i:s");
$pdo->prepare("INSERT INTO `sys_log` (`id`, `acc`, `name`, `class`, `ip`, `content`) VALUES (NULL, ?, ?, ?, ?, ?)")->execute(array($row_user["acc"],$row_user["name"],'不舉發原因',$ip,'新增成功'));

$pdo->prepare("INSERT INTO `unexpose_reasons` (`name`,`active`,`created_at`) VALUES ( ?,?,? )")->execute(array($_POST["report_user"]["title"],$active,$cdate));
echo '<script>document.location.href="unexpose_reasons.php";</script>';
        exit;
}

        echo '<script>alert("新增完成!");</script>';
echo '<script>document.location.href="unexpose_reasons.php";</script>';
?>
