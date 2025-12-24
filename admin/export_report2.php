<?php
include "function.php";
$nav="日誌紀錄";
$n=0;
if( $_POST["report_user"]["title"] == "" && isset( $_POST["report_user"]["title"])) {
	$name_msg="is-invalid";
	$name_msg2='<div class="invalid-feedback">原因未填寫</div>';
	$n = 1;
}

if( isset( $_POST["report_user"]["title"]) && $n == 0 ) {
	$cdate=date("Y-m-d H:i:s");
$pdo->prepare("INSERT INTO `sys_log` (`id`, `acc`, `name`, `class`, `ip`, `content`) VALUES (NULL, ?, ?, ?, ?, ?)")->execute(array($row_user["acc"],$row_user["name"],'不舉發原因',$ip,'新增成功'));

$pdo->prepare("INSERT INTO `reason_code` (`code`,`title`,`cdate`) VALUES ( ?,?, ? )")->execute(array($_POST["report_user"]["code"],$_POST["report_user"]["title"],$cdate));
echo '<script>document.location.href="reason_list.php";</script>';
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
  <link rel="stylesheet" media="all" href="/traffic/admin/assets/admin-92e995e469ea98c880e61710f498cb7c0dddcd185d591b92bc985fb93e14d29a.css" />
</head>
  <body id="page-top" class="case_wrapper cases index collection">
<?php include "menu.php";?>
    <div id="wrapper">
      <div id="content-wrapper">
        <div class="container-fluid">
          <div class="flashes">
          </div>
          <form class="simple_form new_report_user" id="new_report_user" autocomplete="off" action="php/export_report2.php" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="authenticity_token" value="XPtXVm/tQsoeHMIWVrQ4Mse296z1CaQD+mz8Y5hlweHkh5ZUmRGaYjiDiCofzvjEtXRYQOmid9nnyq9RMNp+jQ==" />
<div class="card mb-3">
    <div class="card-header">
      <i class="far fa-info-square"></i>
     日誌紀錄查詢 
      <div class="float-right">    <!--<a class="btn btn-primary btn-sm" href="reason_list.php">回到列表</a>-->
<span class="divider"></span>
</div>
    </div>
<div class="card-body">
        <div class="row">
          <div class="col-3"></div>
          <div class="col-6">
          <form autocomplete="off" class="simple_form condition" action="#" accept-charset="UTF-8" method="post" novalidate="novalidate"><input name="utf8" type="hidden" value="✓"><input type="hidden" name="authenticity_token" value="7IWy+4UxqCdrKwDXukgTZF8rxXQheHpAVsTBZcH2eyG/+cC4x75HqtsSjl/seKReKahfSoa/QkaF+PK3DOnSIA==">
<div class="form-group row">
      <label class="col-sm-2 col-form-label">查詢日期</label>
      <div class="col-sm-10">
        <input class="form-control string optional datetimepicker form-control datetimepicker-input is-valid" data-toggle="datetimepicker" data-target="#case_created_at" type="text" name="year" id="year" aria-invalid="false">
      </div>
    </div>
            <input type="submit" name="commit" value="匯出" class="btn btn-sm btn-primary" data-disable-with="匯出">
</form>
          </div>
          <div class="col-3"></div>
        </div>
      </div>
</div>
</form>
        </div>
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
<script src="assets/admin-7e641842b7678866dba9f029b1984fc78978fbe3c300f84802bb98e82b1f6905.js"></script>
<!--<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>-->
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.0.10/datepicker.min.js"></script>
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.common.min.js"></script>-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.css">
<script>
$(function() {
	        var date = $('#year').datepicker({ format: 'yyyy-mm-dd' }).val();
});
</script>
  </body>
</html>
