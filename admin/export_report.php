<?php
include "function.php";
$nav="匯出報表";
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
  

  <link rel="stylesheet" media="all" href="/new/admin/assets/admin-92e995e469ea98c880e61710f498cb7c0dddcd185d591b92bc985fb93e14d29a.css" />

</head>

  <body id="page-top" class="case_wrapper cases index collection">
<?php include "menu.php";?>
    <div id="wrapper">


      <div id="content-wrapper">
        <div class="container-fluid">
          <div class="flashes">
            

          </div>

          <form class="simple_form new_report_user" id="new_report_user" autocomplete="off" action="php/export_report.php" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="authenticity_token" value="XPtXVm/tQsoeHMIWVrQ4Mse296z1CaQD+mz8Y5hlweHkh5ZUmRGaYjiDiCofzvjEtXRYQOmid9nnyq9RMNp+jQ==" />
<div class="card mb-3">

    <div class="card-header">
      <i class="far fa-info-square"></i>
     各單位年度處理件數統計表 
      <div class="float-right">    <!--<a class="btn btn-primary btn-sm" href="reason_list.php">回到列表</a>-->

<span class="divider"></span>
</div>
    </div>
<div class="card-body">
        <div class="row">
          <div class="col-3"></div>
          <div class="col-6">



          <form autocomplete="off" class="simple_form condition" action="/hsin/admin/cases/export_works.xlsx" accept-charset="UTF-8" method="post" novalidate="novalidate"><input name="utf8" type="hidden" value="✓"><input type="hidden" name="authenticity_token" value="7IWy+4UxqCdrKwDXukgTZF8rxXQheHpAVsTBZcH2eyG/+cC4x75HqtsSjl/seKReKahfSoa/QkaF+PK3DOnSIA==">

            <div class="form-group row">
              <label class="col-sm-2 col-form-label col-form-label-md">
                年度
              </label>
              <div class="col-sm-5">
                <select class="form-control select required form-control form-control-sm" autocomplete="off" required="required" aria-required="請選擇年度" name="year" id="condition_year"><option value="">請選擇年度</option>
<option value="2024">2024</option>
<option value="2023">2023</option></select>
              </div>
            </div>


            <div class="form-group row">
              <label class="col-sm-2 col-form-label col-form-label-md">
                上下半年度
              </label>
              <div class="col-sm-5">
                <select class="form-control select required form-control form-control-sm" autocomplete="off" required="required" aria-required="請選擇年度" name="month" id="condition_period"><option value="">請選擇年度</option>
<option value="6">上半年度(1~6月份)</option>
<option value="12">下半年度(7~12月份)</option>
<option value="all">整年度</option></select>
              </div>
            </div>

            <input type="submit" name="commit" value="匯出" class="btn btn-sm btn-primary" data-disable-with="匯出">
</form>




          </div>
          <div class="col-3"></div>
        </div>
      </div>

</div>


<!--<div class="form-actions">
  <input type="submit" name="commit" value="送出" class="btn btn btn-primary" data-disable-with="送出" />
  <a class="btn btn-secondary" href="reason_list.php">取消</a>
</div>-->

</form>


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

    <script src="/hsin/assets/admin-7e641842b7678866dba9f029b1984fc78978fbe3c300f84802bb98e82b1f6905.js"></script>
      <script type="text/javascript">

    function getunits(dep){
      var department = $(dep).val();
      
      unit_json = $.parseJSON('{"礁溪分局":["副隊長","組長","警務員","分隊長","警務佐","小隊長","警員","約僱"],"三星分局":["副隊長","組長","警務員","分隊長","警務佐","小隊長","警員","約僱"],"蘇澳分局":["副隊長","組長","警務員","分隊長","警務佐","小隊長","警員","約僱"]}');
      var unit = $("#report_user_unit");

      var units = unit_json[department];

      unit.empty();
      unit.append("<option value=''>請選擇</option>");

      $.each(units, function (index, item) {
          unit.append("<option value='"+item+"'>" + item + "</option>");
      });

    }

<?php if( $_POST["report_user"]["department_id"] == "礁溪分局" ) { ?>
      unit_json = $.parseJSON('{"礁溪分局":["副隊長","組長","警務員","分隊長","警務佐","小隊長","警員","約僱"]}');
      var unit = $("#report_user_unit");
      var units = unit_json["礁溪分局"];
      var nunit = '<?=$_POST["report_user"]["unit"];?>';
      unit.empty();
      unit.append("<option value=''>請選擇</option>");
      $.each(units, function (index, item) {
	  if( nunit == item ) {
	      unit.append("<option value='"+item+"' selected>" + item + "</option>");
          } else {
              unit.append("<option value='"+item+"'>" + item + "</option>");
          }
      });
<?php } ?>
<?php if( $_POST["report_user"]["department_id"] == "三星分局" ) { ?>
      unit_json = $.parseJSON('{"三星分局":["副隊長","組長","警務員","分隊長","警務佐","小隊長","警員","約僱"]}');
      var unit = $("#report_user_unit");
      var units = unit_json["三星分局"];
      var nunit = '<?=$_POST["report_user"]["unit"];?>';
      unit.empty();
      unit.append("<option value=''>請選擇</option>");
      $.each(units, function (index, item) {
          if( nunit == item ) {
                unit.append("<option value='"+item+"' selected>" + item + "</option>");
          } else {
                unit.append("<option value='"+item+"'>" + item + "</option>");
          }
      });
<?php } ?>
<?php if( $_POST["report_user"]["department_id"] == "蘇澳分局" ) { ?>
      unit_json = $.parseJSON('{"蘇澳分局":["副隊長","組長","警務員","分隊長","警務佐","小隊長","警員","約僱"]}');
      var unit = $("#report_user_unit");
      var units = unit_json["蘇澳分局"];
      var nunit = '<?=$_POST["report_user"]["unit"];?>';
      unit.empty();
      unit.append("<option value=''>請選擇</option>");
      $.each(units, function (index, item) {
	  if( nunit == item ) {
          	unit.append("<option value='"+item+"' selected>" + item + "</option>");
	  } else {
		unit.append("<option value='"+item+"'>" + item + "</option>");
	  }
      });
<?php } ?>
  </script>


  </body>



</html>

