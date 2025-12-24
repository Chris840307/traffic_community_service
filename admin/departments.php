<?php
include "function.php";
$nav = "單位";
if( $_POST["_method"] == "delete" ) {
	//$pdo->prepare("delete from `departments` where `id`=?")->execute(array($_GET["id"]));
	echo '<script>document.location.href="departments.php";</script>';
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
        <div class="container-fluid">
          <div class="flashes">
            

          </div>

<?php
$result = $pdo->query("select * from `department`");
$allRecrods = $result->rowCount();
?>
<form class="index-form" action="reason_list.php" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="authenticity_token" value="K3Vdn+pD1LWok+ZP4ZVAz0c+BCAiAdLMr7U+yVQD2CFL+YuUshqCTUBY7XKKx+l2e37/KSooDVBN3D02Qt5ojQ==" />
  <div class="card mb-3">
    <div class="card-header">
      <b>單位 列表</b>
      (共 <?=($allRecrods);?> 筆記錄)

      <!--<a class="btn btn-success btn-sm float-right" style="margin-left: 20px;" href="reason_add.php">新增單位</a>-->

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
                 名稱 

                </th>
                <th>
                電話 

                </th>
                <th>
                 建立時間 

                </th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
<?php
$i=0;
$result = $pdo->query("select * from `department` order by `ts` desc");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
        $i++;
?>
                <tr id="admin_user_<?=$row["id"];?>">
                    <td width="2%" id="id_<?=$row["id"];?>">
<?=$i;?>
                    </td>
                    <td width="10%" id="username_<?=$row["id"];?>">
		    <?=$row["dname"];?> / <?=$row["sname"];?>                      
                    </td>
                    <td width="60%" id="username_<?=$row["id"];?>">
<?=$row["dphone"];?>
                    </td>
                    <td width="10%" id="created_at_<?=$row["id"];?>">
<?php
	echo $row["ts"];	
?>
                    </td>
                  <td width="20%">
                    
		  <a href="departments_edit.php?id=<?=$row["id"];?>"><i class="far fa-edit"></i>編輯</a>&nbsp;<!--|&nbsp;<a data-confirm="確定刪除?" rel="nofollow" data-method="delete" href="departments.php?id=<?=$row["id"];?>"><i class="fas fa-trash-alt"></i>刪除</a>-->
  
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

 <script src="/hsin/assets/admin-7e641842b7678866dba9f029b1984fc78978fbe3c300f84802bb98e82b1f6905.js"></script>
  </body>



</html>

