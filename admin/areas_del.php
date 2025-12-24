<?php
include "function.php";

if( $_GET["id"] != "" ) {
$pdo->query("DELETE FROM `areas` WHERE `id`='".$_GET["id"]."'");
echo '<script>document.location.href="areas.php";</script>';
        exit;
}
?>
