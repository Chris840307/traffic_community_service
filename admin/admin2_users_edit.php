<?php
include "function.php";
$nav = "admin2";
if( $_POST["_method"] == "delete" ) {
	$pdo->prepare("delete from `user_list` where `id`=?")->execute(array($_GET["id"]));
	echo '<script>document.location.href="admin2_users.php";</script>';
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
        echo '<script>document.location.href="admin2_users.php";</script>';
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
  

  <link rel="stylesheet" media="all" href="/qr/admin/assets/admin-92e995e469ea98c880e61710f498cb7c0dddcd185d591b92bc985fb93e14d29a.css" />

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

          <form class="simple_form edit_report_user" id="edit_report_user_71" autocomplete="off" action="admin2_update.php" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="_method" value="patch" /><input type="hidden" name="authenticity_token" value="/wTPxqg+6zcxzuHxwZP1+3OPowhW0MjTdLHN5Y8POkC5ZI3HV2cY7jn5v4wc1Li4n1XutES3czZYxOTwdmOvbw==" />
	  <input type="hidden" name="id" value="<?=$row["id"]?>">
<div class="card mb-3">

    
    

    <div class="card-header">
      <i class="far fa-info-square"></i>
      編輯員警
      <div class="float-right">    <a class="btn btn-primary btn-sm" href="admin2_users.php">回到列表</a>
      <a class="btn btn-success btn-sm" href="admin2_users_view.php?id=<?=$row["id"];?>">檢視</a>

<span class="divider"></span>
</div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-12">
          <div class="bgc-white p-20 bd">
            <div class="mT-30">
              
    <div class="form-group row">
  <label class="col-sm-2 col-form-label col-form-label-md">帳號</label>
  <div class="col-sm-5">
  <input class="form-control is-valid string required form-control form-control-sm" autocomplete="off" required="required" aria-required="true" type="text" value="<?=$row["acc"];?>" name="report_user[username]" id="report_user_username" readonly />
  </div>
</div>

    <div class="form-group row">
  <label class="col-sm-2 col-form-label col-form-label-md">姓名</label>
  <div class="col-sm-5">
      <input class="form-control is-valid string optional form-control form-control-sm" autocomplete="off" type="text" value="<?=$row["name"];?>" name="report_user[name]" id="report_user_name" />
  </div>
</div>

    <div class="form-group row">
      <label class="col-sm-2 col-form-label col-form-label-md">單位</label>
      <div class="col-sm-5">
        <select class="form-control is-valid select optional form-control form-control-sm select2" onchange="getunits(this)" autocomplete="off" name="report_user[department_id]" id="report_user_department_id">
<?php
$resultd = $pdo->query("select * from `department` where `id`='".$row_user["department_id"]."'");
while($rowd = $resultd->fetch(PDO::FETCH_ASSOC)){
	if( $rowd["id"] == $row["department_id"] )
		$buff = 'selected';
	else
		$buff = '';
echo '<option value="'.$rowd["id"].'" '.$buff.'>'.$rowd["dname"].'</option>';
}
?>
</select>
      </div>
    </div>

    <!--<div class="form-group row">
      <label class="col-sm-2 col-form-label col-form-label-md">職位</label>
      <div class="col-sm-5">

        <select class="form-control select optional form-control form-control-sm select2" autocomplete="off" name="report_user[unit]" id="report_user_unit"><option value="">請選擇</option>
</select>
      </div>
    </div>-->


    <div class="form-group row">
  <label class="col-sm-2 col-form-label col-form-label-md">密碼</label>
  <div class="col-sm-5">
      <input class="form-control password optional form-control form-control-sm" autocomplete="off" type="password" name="report_user[password]" id="report_user_password" /><small class="form-text text-muted">至少 6 個字元</small>
  </div>
</div>

    <div class="form-group row">
  <label class="col-sm-2 col-form-label col-form-label-md">確認密碼</label>
  <div class="col-sm-5">
      <input class="form-control password optional form-control form-control-sm" autocomplete="off" type="password" name="report_user[password_confirmation]" id="report_user_password_confirmation" />
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

<script src="/hsinchu/assets/admin-7e641842b7678866dba9f029b1984fc78978fbe3c300f84802bb98e82b1f6905.js"></script>
      <script type="text/javascript">

    function getunits(dep){
      var department = $(dep).val();

      unit_json = $.parseJSON('{"新竹市警察局交通警察隊":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"],"六家所":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"],"竹北所":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"],"竹北分局":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"],"第三分局":["中華所","南門所","朝山所","第五組","警備隊","青草湖所","香山所"],"第二分局":["埔頂所","文華所","東勢所","東門所","第五組","警備隊","關東橋所"]}');
      var unit = $("#report_user_unit");

      var units = unit_json[department];

      unit.empty();
      unit.append("<option value=''>請選擇</option>");

      $.each(units, function (index, item) {
          unit.append("<option value='"+item+"'>" + item + "</option>");
      });

    }

<?php if( $row["department"] == "六家所" ) { ?>
      unit_json = $.parseJSON('{"六家所":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"]}');
      var unit = $("#report_user_unit");
      var units = unit_json["六家所"];
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
<?php if( $row["department"] == "竹北所" ) { ?>
      unit_json = $.parseJSON('{"竹北所":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"]}');
      var unit = $("#report_user_unit");
      var units = unit_json["竹北所"];
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
<?php if( $row["department"] == "竹北分局" ) { ?>
      unit_json = $.parseJSON('{"竹北分局":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"]}');
      var unit = $("#report_user_unit");
      var units = unit_json["竹北分局"];
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
<?php if( $row["department"] == "新竹市警察局交通警察隊" ) { ?>
      unit_json = $.parseJSON('{"新竹市警察局交通警察隊":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"]}');
            var unit = $("#report_user_unit");
            var units = unit_json["新竹市警察局交通警察隊"];
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

