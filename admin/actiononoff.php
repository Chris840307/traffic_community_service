<?php
include "function.php";
$id = $_POST['cid'];
$db = $_POST['db'];
$type = $_POST['onofftype'];
if( $id != "" ) {
if($type==1){ //关闭
	$pdo->prepare("UPDATE `$db` set `active`=? where `id`=?")->execute(array('f',$id));
}else{ //开启
	$pdo->prepare("UPDATE `$db` set `active`=? where `id`=?")->execute(array('t',$id));
}
echo '1';
} else {
echo 'Error';
}
?>
