<?php
include "function.php";
$nav = "admin";
if( $_POST["_method"] == "delete" ) {
	$pdo->prepare("delete from `user_list` where `id`=?")->execute(array($_GET["id"]));
	echo '<script>document.location.href="admin_users.php";</script>';
	exit;
}

if( $_POST["commit"] == "更新權限" ) {
$result = $pdo->query("select * from `user_list` where `acc`!='admin'");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
$flag1 = $_POST["admin_user"][$row["id"]]["flag1"]?$_POST["admin_user"][$row["id"]]["flag1"]:0;
$flag2 = $_POST["admin_user"][$row["id"]]["flag2"]?$_POST["admin_user"][$row["id"]]["flag2"]:0;
$flag3 = $_POST["admin_user"][$row["id"]]["flag3"]?$_POST["admin_user"][$row["id"]]["flag3"]:0;
$flag4 = $_POST["admin_user"][$row["id"]]["flag4"]?$_POST["admin_user"][$row["id"]]["flag4"]:0;
$flag5 = $_POST["admin_user"][$row["id"]]["flag5"]?$_POST["admin_user"][$row["id"]]["flag5"]:0;
$flag6 = $_POST["admin_user"][$row["id"]]["flag6"]?$_POST["admin_user"][$row["id"]]["flag6"]:0;
$flag7 = $_POST["admin_user"][$row["id"]]["flag7"]?$_POST["admin_user"][$row["id"]]["flag7"]:0;
$flag8 = $_POST["admin_user"][$row["id"]]["flag8"]?$_POST["admin_user"][$row["id"]]["flag8"]:0;
$flag9 = $_POST["admin_user"][$row["id"]]["flag9"]?$_POST["admin_user"][$row["id"]]["flag9"]:0;
$flag10 = $_POST["admin_user"][$row["id"]]["flag10"]?$_POST["admin_user"][$row["id"]]["flag10"]:0;
$flag11 = $_POST["admin_user"][$row["id"]]["flag11"]?$_POST["admin_user"][$row["id"]]["flag11"]:0;
$flag12 = $_POST["admin_user"][$row["id"]]["flag12"]?$_POST["admin_user"][$row["id"]]["flag12"]:0;
$pdo->prepare("UPDATE `user_list` set `flag1`=?,`flag2`=?,`flag3`=?,`flag4`=?,`flag5`=?,`flag6`=?,`flag7`=?,`flag8`=?,`flag9`=?,`flag10`=?,`flag11`=?,`flag12`=? where `id`=?")->execute(array($flag1,$flag2,$flag3,$flag4,$flag5,$flag6,$flag7,$flag8,$flag9,$flag10,$flag11,$flag12,$row["id"]));
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
  

  <link rel="stylesheet" media="all" href="/hsinchu/assets/admin-92e995e469ea98c880e61710f498cb7c0dddcd185d591b92bc985fb93e14d29a.css" />

</head>

  <body id="page-top" class="case_wrapper cases index collection">
<?php include "menu2.php";?>

    <div id="wrapper">


      <div id="content-wrapper">
        <div class="container-fluid">
          <div class="flashes">
            

          </div>

<?php
$result = $pdo->query("select * from `user_list`");
$allRecrods = $result->rowCount();
?>
<form class="index-form" action="admin_users.php" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="authenticity_token" value="K3Vdn+pD1LWok+ZP4ZVAz0c+BCAiAdLMr7U+yVQD2CFL+YuUshqCTUBY7XKKx+l2e37/KSooDVBN3D02Qt5ojQ==" />
  <div class="card mb-3">
    <div class="card-header">
      <b>員警 列表</b>
      (共 <?=$allRecrods;?> 筆記錄)

      <a class="btn btn-success btn-sm float-right" style="margin-left: 20px;" href="admin_users_new.php">新增員警</a>

      <input type="submit" name="commit" value="更新權限" class="btn btn-primary btn-sm float-right" data-disable-with="更新權限" />
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-sm text-nowrap table-condensed">
          <thead>
            <tr>
                <th>
                  ID

                </th>
                <th>
                  帳號

                </th>
                <th>
                  姓名

                </th>
                <th>
                  單位
                </th>
                <th>
                  次單位
                </th>
                <th>
                  登入次數

                </th>
                <th>
                  建立時間

                </th>
              <th>權限</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
<?php
$i=0;
$result = $pdo->query("select * from `user_list` where `acc`!='admin' order by `cdate` desc");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
        $i++;
?>
                <tr id="admin_user_<?=$row["id"];?>">
                    <td id="id_<?=$row["id"];?>">
<?=$i;?>
                    </td>
                    <td width="6%" id="username_<?=$row["id"];?>">
<?=$row["acc"];?>                      
                    </td>
                    <td width="6%" id="name_<?=$row["id"];?>">
<?=$row["name"];?>
                    </td>
                    <td id="department_name_<?=$row["id"];?>">
<?=$row["department"];?>
                    </td>
                    <td id="unit_name_<?=$row["id"];?>">
<?=$row["unit"];?>
                    </td>
                    <td width="4%" id="sign_in_count_<?=$row["id"];?>">
<?=$row["sign_in_count"];?>
                    </td>
                    <td width="10%" id="created_at_<?=$row["id"];?>">
<?=$row["cdate"];?>
                    </td>
                  <td>
		  <label data-value="1" for="admin_user_<?=$row["id"];?>_permission_ids_1"><input type="checkbox" value="1" <?php if( $row["flag1"] == 1 ) echo 'checked="checked"';?> name="admin_user[<?=$row["id"];?>][flag1]" id="admin_user_<?=$row["id"];?>_permission_ids_1" />案件處理</label><label data-value="2" for="admin_user_<?=$row["id"];?>_permission_ids_2"><input type="checkbox" value="1" <?php if( $row["flag2"] == 1 ) echo 'checked="checked"';?> name="admin_user[<?=$row["id"];?>][flag2]" id="admin_user_<?=$row["id"];?>_permission_ids_2" />案件審核</label><label data-value="3" for="admin_user_<?=$row["id"];?>_permission_ids_3"><input type="checkbox" value="1" <?php if( $row["flag3"] == 1 ) echo 'checked="checked"';?> name="admin_user[<?=$row["id"];?>][flag3]" id="admin_user_<?=$row["id"];?>_permission_ids_3" />案件傳送</label><label data-value="4" for="admin_user_<?=$row["id"];?>_permission_ids_4"><input type="checkbox" value="1" <?php if( $row["flag4"] == 1 ) echo 'checked="checked"';?> name="admin_user[<?=$row["id"];?>][flag4]" id="admin_user_<?=$row["id"];?>_permission_ids_4" />綜合查詢</label><label data-value="5" for="admin_user_<?=$row["id"];?>_permission_ids_5"><input type="checkbox" value="1" <?php if( $row["flag5"] == 1 ) echo 'checked="checked"';?> name="admin_user[<?=$row["id"];?>][flag5]" id="admin_user_<?=$row["id"];?>_permission_ids_5" />管理員</label><!--<label data-value="6" for="admin_user_197_permission_ids_6"><input type="checkbox" value="6" name="admin_user[197][permission_ids][]" id="admin_user_197_permission_ids_6" />案件改派</label><label data-value="7" for="admin_user_197_permission_ids_7"><input type="checkbox" value="7" name="admin_user[197][permission_ids][]" id="admin_user_197_permission_ids_7" />案件追蹤</label><label data-value="9" for="admin_user_197_permission_ids_9"><input type="checkbox" value="9" checked="checked" name="admin_user[197][permission_ids][]" id="admin_user_197_permission_ids_9" />新增案件</label><label data-value="10" for="admin_user_197_permission_ids_10"><input type="checkbox" value="10" name="admin_user[197][permission_ids][]" id="admin_user_197_permission_ids_10" />分局管理員</label>-->
<div style="font-size:16px;"><label data-value="6" for="admin_user_<?=$row["id"];?>_permission_ids_6"><input type="checkbox" value="1" <?php if( $row["flag6"] == 1 ) echo 'checked="checked"';?> name="admin_user[<?=$row["id"];?>][flag6]" id="admin_user_<?=$row["id"];?>_permission_ids_6" />新竹火車站</label><label data-value="7" for="admin_user_<?=$row["id"];?>_permission_ids_7"><input type="checkbox" value="1" <?php if( $row["flag7"] == 1 ) echo 'checked="checked"';?> name="admin_user[<?=$row["id"];?>][flag7]" id="admin_user_<?=$row["id"];?>_permission_ids_7" />城隍廟</label>
<label data-value="8" for="admin_user_<?=$row["id"];?>_permission_ids_8"><input type="checkbox" value="1" <?php if( $row["flag8"] == 1 ) echo 'checked="checked"';?> name="admin_user[<?=$row["id"];?>][flag8]" id="admin_user_<?=$row["id"];?>_permission_ids_8" />移動式測速</label>
<label data-value="9" for="admin_user_<?=$row["id"];?>_permission_ids_9"><input type="checkbox" value="1" <?php if( $row["flag9"] == 1 ) echo 'checked="checked"';?> name="admin_user[<?=$row["id"];?>][flag9]" id="admin_user_<?=$row["id"];?>_permission_ids_9" />巨城</label>
<label data-value="10" for="admin_user_<?=$row["id"];?>_permission_ids_10"><input type="checkbox" value="1" <?php if( $row["flag10"] == 1 ) echo 'checked="checked"';?> name="admin_user[<?=$row["id"];?>][flag10]" id="admin_user_<?=$row["id"];?>_permission_ids_10" />慈雲</label>
<label data-value="11" for="admin_user_<?=$row["id"];?>_permission_ids_11"><input type="checkbox" value="1" <?php if( $row["flag11"] == 1 ) echo 'checked="checked"';?> name="admin_user[<?=$row["id"];?>][flag11]" id="admin_user_<?=$row["id"];?>_permission_ids_11" />固定式測速</label>
<label data-value="12" for="admin_user_<?=$row["id"];?>_permission_ids_12"><input type="checkbox" value="1" <?php if( $row["flag12"] == 1 ) echo 'checked="checked"';?> name="admin_user[<?=$row["id"];?>][flag12]" id="admin_user_<?=$row["id"];?>_permission_ids_12" />西大北大</label>
</div>


                  </td>
                  <td>
                    
		  <a href="admin_users_view.php?id=<?=$row["id"];?>"><i class="fa fa-info"></i>檢視</a>&nbsp;|&nbsp;<a href="admin_users_edit.php?id=<?=$row["id"];?>"><i class="far fa-edit"></i>編輯</a>&nbsp;|&nbsp;<a data-confirm="確定刪除?" rel="nofollow" data-method="delete" href="admin_users.php?id=<?=$row["id"];?>"><i class="fas fa-trash-alt"></i>刪除</a>
  
                                        </td>

                </tr>
<?php } ?>
          </tbody>
        </table>
      </div>


    </div>

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
  </body>



</html>

