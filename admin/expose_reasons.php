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

<style>
.list{padding:12px 1px; border-bottom:0px dotted #d3d3d3; position:relative}
.fun_title{height:28px; line-height:28px}
.fun_title span{width:82px; height:34px; background:url(switch2.jpg) no-repeat;
cursor:pointer; position:absolute; right:10px; top:10px}
.fun_title span.ad_on{background-position:0 -47px}
.fun_title span.ad_off{background-position:0 -2px}
.fun_title h3{font-size:14px; font-family:'microsoft yahei';}
.list p{line-height:20px}
.list p span{color:#f60}
.cur_select{background:#00A}
</style>

</head>

  <body id="page-top" class="case_wrapper cases index collection">
<?php include "menu.php";?>

    <div id="wrapper">


      <div id="content-wrapper">
        <div class="container-fluid">
          <div class="flashes">
            

          </div>

<?php
$result = $pdo->query("select * from `expose_reasons`");
$allRecrods = $result->rowCount();
?>
<form class="index-form" action="expose_reasons.php" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="authenticity_token" value="K3Vdn+pD1LWok+ZP4ZVAz0c+BCAiAdLMr7U+yVQD2CFL+YuUshqCTUBY7XKKx+l2e37/KSooDVBN3D02Qt5ojQ==" />
  <div class="card mb-3">
    <div class="card-header">
      <b>舉發條款 列表</b>
      (共 <?=($allRecrods);?> 筆記錄)

      <a class="btn btn-success btn-sm float-right" style="margin-left: 20px;" href="expose_reasons_add.php">新增條款</a>

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
                 法條代碼 

                </th>
                <th>
                 法說明條

                </th>
                <th>
                 顯示狀態 

                </th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
<?php
$i=0;
$result = $pdo->query("select * from `expose_reasons` order by `created_at` desc");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
        $i++;
?>
                <tr id="admin_user_<?=$row["id"];?>">
                    <td width="2%" id="id_<?=$row["id"];?>">
<?=$i;?>
                    </td>
                    <td width="10%" id="username_<?=$row["id"];?>">
<?=$row["code"];?>                      
                    </td>
                    <td width="60%" id="username_<?=$row["id"];?>">
<?=$row["name"];?>
                    </td>
                    <td width="6%" id="created_at_<?=$row["id"];?>">
<div class="list">
     <div class="fun_title">
            <span rel="<?php echo $row['id'];?>" <?php if($row['active']=="t"){ ?>
            class="ad_on" title="點擊不顯示"<?php }else{?>class="ad_off" title="點擊顯示"<?php }?>></span>
    </div>
</div>
                    </td>
                  <td width="20%">
                    
		  <a href="expose_reasons_edit.php?id=<?=$row["id"];?>"><i class="far fa-edit"></i>編輯</a>&nbsp;|&nbsp;<a data-confirm="確定刪除?" rel="nofollow" data-method="delete" href="expose_reasons.php?id=<?=$row["id"];?>"><i class="fas fa-trash-alt"></i>刪除</a>
  
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
<script type="text/javascript">
$(function(){
        $(".list").hover(function(){
                $(this).addClass("cur_select");
        },function(){
                $(this).removeClass("cur_select");
        });

	$("body").on("click", ".ad_on", function() {
                var add_on = $(this);
                var status_id = $(this).attr("rel");
		var db = 'expose_reasons'
                $.post("actiononoff.php",{cid:status_id,db:db,onofftype:1},function(data){
                        if(data==1){
                                add_on.removeClass("ad_on").addClass("ad_off").attr("title","不顯示");
                        }
                });
        });
	$("body").on("click", ".ad_off", function() {
                var add_off = $(this);
                var status_id = $(this).attr("rel");
		var db = 'expose_reasons'
                $.post("actiononoff.php",{cid:status_id,db:db,onofftype:2},function(data){
                        if(data==1){
                                add_off.removeClass("ad_off").addClass("ad_on").attr("title","顯示");
                        }
                });
        });
});
</script>
  </body>



</html>

