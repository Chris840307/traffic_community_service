<?php
include "function.php";
$nav = "舉發條款";
if( $_POST["_method"] == "delete" ) {
	$pdo->prepare("delete from `expose_reasons` where `id`=?")->execute(array($_GET["id"]));
	echo '<script>document.location.href="expose_reasons.php";</script>';
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
$result = $pdo->query("select * from `expose_reasons` where `id`='".$_GET["id"]."'");
$row = $result->fetch(PDO::FETCH_ASSOC);
?>

          <form class="simple_form edit_report_user" id="edit_report_user_71" autocomplete="off" action="expose_reasons_update.php" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="_method" value="patch" /><input type="hidden" name="authenticity_token" value="/wTPxqg+6zcxzuHxwZP1+3OPowhW0MjTdLHN5Y8POkC5ZI3HV2cY7jn5v4wc1Li4n1XutES3czZYxOTwdmOvbw==" />
	  <input type="hidden" name="id" value="<?=$row["id"]?>">
<div class="card mb-3">

    
    

    <div class="card-header">
      <i class="far fa-info-square"></i>
      編輯舉發條款
      <div class="float-right">    <a class="btn btn-primary btn-sm" href="expose_reasons.php">回到列表</a>

<span class="divider"></span>
</div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-12">
          <div class="bgc-white p-20 bd">
            <div class="mT-30">
              
    <div class="form-group row">
</div>
    <div class="form-group row">
  <label class="col-sm-2 col-form-label col-form-label-md">違規法條名稱</label>
  <div class="col-sm-5">
      <input class="form-control is-valid required string optional form-control form-control-sm" autocomplete="off" type="text" value="<?=$row["name"];?>" name="report_user[title]" id="report_user_title"/>
  </div>
</div>
    <div class="form-group row">
  <label class="col-sm-2 col-form-label col-form-label-md">違規法條代碼</label>
  <div class="col-sm-5">
      <input class="form-control is-valid required string optional form-control form-control-sm" autocomplete="off" type="text" value="<?=$row["code"];?>" name="report_user[code]" id="report_user_code"/>
  </div>
  </div>
<div class="form-group row">
  <label class="col-sm-2 col-form-label col-form-label-md">可見</label>
  <div class="col-sm-5">
  <input name="unexpose_reason[active]" type="hidden" value="0"><input class="form-check-input is-valid boolean optional form-control form-control-sm" autocomplete="off" type="checkbox" value="1" <?php
if( $row["active"] == "t" )
	echo 'checked="checked"';
?>
 name="unexpose_reason[active]" id="unexpose_reason_active">
  </div>
</div>

              
            </div>
          </div>

        </div>
      </div>
    </div>


</div>


<div class="form-actions">
  <input type="submit" name="commit" value="送出" class="btn btn btn-primary" data-disable-with="送出" />
  <a class="btn btn-secondary" href="javascript:history.go(-1);">取消</a>
</div>

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

<?php if( $row["department"] == "礁溪分局" ) { ?>
      unit_json = $.parseJSON('{"礁溪分局":["副隊長","組長","警務員","分隊長","警務佐","小隊長","警員","約僱"]}');
      var unit = $("#report_user_unit");
      var units = unit_json["礁溪分局"];
      var nunit = '<?=$row["unit"];?>';
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
<?php if( $row["department"] == "三星分局" ) { ?>
      unit_json = $.parseJSON('{"三星分局":["副隊長","組長","警務員","分隊長","警務佐","小隊長","警員","約僱"]}');
      var unit = $("#report_user_unit");
      var units = unit_json["三星分局"];
      var nunit = '<?=$row["unit"];?>';
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
<?php if( $row["department"] == "蘇澳分局" ) { ?>
      unit_json = $.parseJSON('{"蘇澳分局":["副隊長","組長","警務員","分隊長","警務佐","小隊長","警員","約僱"]}');
      var unit = $("#report_user_unit");
      var units = unit_json["蘇澳分局"];
      var nunit = '<?=$row["unit"];?>';
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

