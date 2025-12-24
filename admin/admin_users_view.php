<?php
include "function.php";
$nav = "admin";
if( $_POST["_method"] == "delete" ) {
	$pdo->prepare("delete from `user_list` where `id`=?")->execute(array($_GET["id"]));
	echo '<script>document.location.href="admin_users.php";</script>';
	exit;
}

if( $_POST["commit"] == "更新權限" ) {
$result = $pdo->query("select * from `user_list`");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
$flag1 = $_POST["admin_user"][$row["id"]]["flag1"]?$_POST["admin_user"][$row["id"]]["flag1"]:0;
$flag2 = $_POST["admin_user"][$row["id"]]["flag2"]?$_POST["admin_user"][$row["id"]]["flag2"]:0;
$flag3 = $_POST["admin_user"][$row["id"]]["flag3"]?$_POST["admin_user"][$row["id"]]["flag3"]:0;
$flag4 = $_POST["admin_user"][$row["id"]]["flag4"]?$_POST["admin_user"][$row["id"]]["flag4"]:0;
$flag5 = $_POST["admin_user"][$row["id"]]["flag5"]?$_POST["admin_user"][$row["id"]]["flag5"]:0;
$pdo->prepare("UPDATE `user_list` set `flag1`=?,`flag2`=?,`flag3`=?,`flag4`=?,`flag5`=? where `id`=?")->execute(array($flag1,$flag2,$flag3,$flag4,$flag5,$row["id"]));
}
        echo '<script>document.location.href="admin_users.php";</script>';
        exit;
}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hant">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>新竹市警察局交通事故案件便民服務網</title>

  <meta name="csrf-param" content="authenticity_token" />
<meta name="csrf-token" content="dtQ0kLcyBcoTBTfeopTr84sCjzI8L6Vb52QR2RrFbdb/HrWpAx49ZA8tu8Mof/VoQGweX+BmKtHA8TXwslShtw==" />
  

  <link rel="stylesheet" media="all" href="assets/admin-92e995e469ea98c880e61710f498cb7c0dddcd185d591b92bc985fb93e14d29a.css" />

</head>

  <body id="page-top" class="case_wrapper cases index collection">
<?php include "menu.php";?>

    <div id="wrapper">


      <div id="content-wrapper">
        <div class="container-fluid" style="padding-left: 20%;padding-right: 20%">
          <div class="flashes">
            

          </div>

          
<?php
$result = $pdo->query("select * from `user_list` where `id`='".$_GET["id"]."'");
$row = $result->fetch(PDO::FETCH_ASSOC);
?>
<div class="row">
  <div class="col-lg-12">
    <div class="card mb-3">
      <div class="card-header">
        <b>員警明細</b>
        <div class="float-right">
            <a class="btn btn-primary btn-sm" href="admin_users.php">回到列表</a>
    <a class="btn btn-info btn-sm" href="admin_users_edit.php?id=<?=$row["id"];?>">編輯</a>
    <a data-confirm="確定刪除?" class="btn btn-danger btn-sm" rel="nofollow" data-method="delete" href="admin_users.php?id=<?=$row["id"];?>">刪除</a>

<span class="divider"></span>

        </div>
      </div>
      <div class="card-body">
        
        <dl class="row">
            <dt class="col-sm-2">
              ID：
            </dt>
            <dd class="col-sm-10">
<?=$row["id"];?>
            </dd>
            <dt class="col-sm-2">
              單位：
            </dt>
            <dd class="col-sm-10">
<?=$row["department"];?>
            </dd>
            <dt class="col-sm-2">
              次單位：
            </dt>
            <dd class="col-sm-10">
<?=$row["unit"];?>
            </dd>
            <dt class="col-sm-2">
              帳號：
            </dt>
            <dd class="col-sm-10">
<?=$row["acc"];?>
            </dd>
            <dt class="col-sm-2">
              姓名：
            </dt>
            <dd class="col-sm-10">
<?=$row["name"];?>
            </dd>
            <dt class="col-sm-2">
              登入次數：
            </dt>
            <dd class="col-sm-10">
<?=$row["sign_in_count"];?>
            </dd>
            <dt class="col-sm-2">
              建立時間：
            </dt>
            <dd class="col-sm-10">
<?=$row["cdate"];?>
            </dd>
        </dl>

      </div>
      <div class="card-footer small text-muted">
        
      </div>
    </div>
  </div>
</div>

        </div>
	<!-- /.container-fluid -->

        <!-- Sticky Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>新竹市警察局交通事故案件便民服務網</span>
            </div>
          </div>
        </footer>

      </div>
      <!-- /.content-wrapper -->

    </div>
    <!-- /#wrapper -->

 <script src="/hsinchu/assets/admin-7e641842b7678866dba9f029b1984fc78978fbe3c300f84802bb98e82b1f6905.js"></script>
  </body>



</html>

