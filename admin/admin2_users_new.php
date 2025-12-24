<?php
include "function.php";
$nav="admin2";
$n=0;
if( $_POST["report_user"]["name"] == "" && isset( $_POST["report_user"]["name"])) {
	$name_msg="is-invalid";
	$name_msg2='<div class="invalid-feedback">姓名尚未填寫</div>';
	$n = 1;
}

if( isset($_POST["report_user"]["username"]) && $_POST["report_user"]["username"] == "" ) {
        $username_msg="is-invalid";
        $username_msg2='<div class="invalid-feedback">帳號尚未填寫</div>';
	$n = 1;
}

if(!preg_match('/^[a-zA-Z0-9]+$/', $_POST["report_user"]["username"]) && $_POST["report_user"]["username"]!="") {
        $username_msg="is-invalid";
        $username_msg2='<div class="invalid-feedback">帳號只能用英文或數字</div>';
	$n = 1;
}

$result = $pdo->query("select * from `user_list` where `acc`='".$_POST["report_user"]["username"]."'");
$row = $result->fetch(PDO::FETCH_ASSOC);
if( $row["acc"] == $_POST["report_user"]["username"] && $_POST["report_user"]["username"]!="" ) {
        $username_msg="is-invalid";
	$username_msg2='<div class="invalid-feedback">帳號已經存在了</div>';
	$n = 1;
}

if( $_POST["report_user"]["department_id"] == "" && isset($_POST["report_user"]["department_id"])) {
        $dep_msg="is-invalid";
        $dep_msg2='<div class="invalid-feedback">尚未選擇單位</div>';
	$n = 1;
}

/*
if( $_POST["report_user"]["department_id"] == "第一分局" && $_POST["report_user"]["unit"] == "" ) {
        $unit_msg="is-invalid";
        $unit_msg2='<div class="invalid-feedback">尚未選擇次單位</div>';
	$n = 1;
}
if( $_POST["report_user"]["department_id"] == "第二分局" && $_POST["report_user"]["unit"] == "" ) {
        $unit_msg="is-invalid";
        $unit_msg2='<div class="invalid-feedback">尚未選擇次單位</div>';
	$n = 1;
}
if( $_POST["report_user"]["department_id"] == "第三分局" && $_POST["report_user"]["unit"] == "" ) {
        $unit_msg="is-invalid";
        $unit_msg2='<div class="invalid-feedback">尚未選擇次單位</div>';
	$n = 1;
}
 */
if( $_POST["report_user"]["password"] == "" && isset( $_POST["report_user"]["password"])) {
        $pwd_msg="is-invalid";
        $pwd_msg2='<div class="invalid-feedback">密碼尚未輸入</div>';
	$n = 1;
} else {
	if( strlen( $_POST["report_user"]["password"] ) <= 5 && isset( $_POST["report_user"]["password"])) { 
		$pwd_msg="is-invalid";
		$pwd_msg2='<div class="invalid-feedback">密碼最少要六個字元</div>';
		$n = 1;
	}
}

if( $_POST["report_user"]["password"] != $_POST["report_user"]["password_confirmation"] ) {
        $conf_msg="is-invalid";
        $conf_msg2='<div class="invalid-feedback">確認密碼跟上面的密碼不一樣</div>';
	$n = 1;
}
if( isset( $_POST["report_user"]["username"]) && $n == 0 ) {
	$cdate=date("Y-m-d H:i:s");
$pdo->prepare("INSERT INTO `user_list` (`name`,`acc`,`pwd`,`department_id`,`cdate`) VALUES ( ?, ?, ?, ?,? )")->execute(array($_POST["report_user"]["name"],$_POST["report_user"]["username"],$_POST["report_user"]["password"],$_POST["report_user"]["department_id"],$cdate));
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

  <title>新竹市交通警察隊系統</title>

  <meta name="csrf-param" content="authenticity_token" />
<meta name="csrf-token" content="dtQ0kLcyBcoTBTfeopTr84sCjzI8L6Vb52QR2RrFbdb/HrWpAx49ZA8tu8Mof/VoQGweX+BmKtHA8TXwslShtw==" />
  

  <link rel="stylesheet" media="all" href="/qr/admin/assets/admin-92e995e469ea98c880e61710f498cb7c0dddcd185d591b92bc985fb93e14d29a.css" />

</head>

  <body id="page-top" class="case_wrapper cases index collection">
<?php include "menu.php";?>
    <div id="wrapper">


      <div id="content-wrapper">
        <div class="container-fluid">
          <div class="flashes">
            

          </div>

          <form class="simple_form new_report_user" id="new_report_user" autocomplete="off" action="admin2_users_new.php" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="authenticity_token" value="XPtXVm/tQsoeHMIWVrQ4Mse296z1CaQD+mz8Y5hlweHkh5ZUmRGaYjiDiCofzvjEtXRYQOmid9nnyq9RMNp+jQ==" />
<div class="card mb-3">

    <div class="card-header">
      <i class="far fa-info-square"></i>
      新增員警
      <div class="float-right">    <a class="btn btn-primary btn-sm" href="admin2_users.php">回到列表</a>

<span class="divider"></span>
</div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-8">
          
          
          <div class="bgc-white p-20 bd">
            <div class="mT-30">
              
    <div class="form-group row">
  <label class="col-sm-2 col-form-label col-form-label-md">帳號</label>
  <div class="col-sm-5">
  <input class="form-control <?=$username_msg;?> string required form-control form-control-sm" autocomplete="off" required="required" aria-required="true" type="text" name="report_user[username]" id="report_user_username" value="<?=$_POST["report_user"]["username"];?>"/>
<?=$username_msg2;?>
  </div>
</div>

    <div class="form-group row">
  <label class="col-sm-2 col-form-label col-form-label-md">姓名</label>
  <div class="col-sm-5">
  <input class="form-control <?=$name_msg;?> string optional form-control form-control-sm" autocomplete="off" type="text" name="report_user[name]" id="report_user_name" required="required" value="<?=$_POST["report_user"]["name"];?>"/>
<?=$name_msg2;?>
  </div>
</div>

    <div class="form-group row">
      <label class="col-sm-2 col-form-label col-form-label-md">單位</label>
      <div class="col-sm-5">
        <select class="form-control <?=$dep_msg;?> select optional form-control form-control-sm select2" onchange="getunits(this)" autocomplete="off" name="report_user[department_id]" id="report_user_department_id">
	<option value="<?=$row_user["department_id"];?>" selected><?=$row_user["department"];?></option>
</select>
<?=$dep_msg2;?>
      </div>
    </div>
<!--    <div class="form-group row">
      <label class="col-sm-2 col-form-label col-form-label-md">職位</label>
      <div class="col-sm-5">
        
        <select class="form-control <?=$unit_msg;?> select optional form-control form-control-sm select2" autocomplete="off" name="report_user[unit]" id="report_user_unit"><option value="">請選擇</option>
</select>
<?=$unit_msg2;?>
      </div>
    </div>-->


    <div class="form-group row">
  <label class="col-sm-2 col-form-label col-form-label-md">密碼</label>
  <div class="col-sm-5">
      <input class="form-control <?=$pwd_msg;?> password optional form-control form-control-sm" autocomplete="off" type="password" name="report_user[password]" id="report_user_password" required="required" value="<?=$_POST["report_user"]["password"];?>"/><small class="form-text text-muted">至少 6 個字元</small>
<?=$pwd_msg2;?>
  </div>
</div>

    <div class="form-group row">
  <label class="col-sm-2 col-form-label col-form-label-md">確認密碼</label>
  <div class="col-sm-5">
      <input class="form-control <?=$conf_msg;?> password optional form-control form-control-sm" autocomplete="off" type="password" name="report_user[password_confirmation]" id="report_user_password_confirmation" required="required" value="<?=$_POST["report_user"]["password_confirmation"];?>" />
<?=$conf_msg2;?>
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
  <a class="btn btn-secondary" href="admin2_users.php">取消</a>
</div>

</form>


        </div>
        <!-- /.container-fluid -->

        <!-- Sticky Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>新竹市交通警察隊系統</span>
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
      
      unit_json = $.parseJSON('{"新竹市交通警察隊":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"],"六家所":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"],"竹北所":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"],"竹北分局":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"],"第三分局":["中華所","南門所","朝山所","第五組","警備隊","青草湖所","香山所"],"第二分局":["埔頂所","文華所","東勢所","東門所","第五組","警備隊","關東橋所"]}');
      var unit = $("#report_user_unit");

      var units = unit_json[department];

      unit.empty();
      unit.append("<option value=''>請選擇</option>");

      $.each(units, function (index, item) {
          unit.append("<option value='"+item+"'>" + item + "</option>");
      });

    }

<?php if( $_POST["report_user"]["department_id"] == "六家所" ) { ?>
      unit_json = $.parseJSON('{"六家所":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"]}');
      var unit = $("#report_user_unit");
      var units = unit_json["六家所"];
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
<?php if( $_POST["report_user"]["department_id"] == "竹北所" ) { ?>
      unit_json = $.parseJSON('{"竹北所":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"]}');
      var unit = $("#report_user_unit");
      var units = unit_json["竹北所"];
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
<?php if( $_POST["report_user"]["department_id"] == "竹北分局" ) { ?>
      unit_json = $.parseJSON('{"竹北分局":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"]}');
      var unit = $("#report_user_unit");
      var units = unit_json["竹北分局"];
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
<?php if( $_POST["report_user"]["department_id"] == "交通警察隊" ) { ?>
      unit_json = $.parseJSON('{"交通警察隊":["副隊長","組長","警務員","分隊長","警務佐","巡佐","巡官","小隊長","警員","約僱"]}');
      var unit = $("#report_user_unit");
      var units = unit_json["交通警察隊"];
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

