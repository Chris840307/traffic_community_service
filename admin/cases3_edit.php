<?php
include "function.php";

$putdata=file_get_contents('php://input');
$buffa = explode('&',urldecode($putdata));
$buffb = explode('=',$buffa[1]);
if( $buffa[0] == "_method=put" && $buffb != "" && $_GET["id"] != "" ) {
	$pdo->prepare("UPDATE `cases` set `comment`=? where `id`=?")->execute(array($buffb[1],intval($_GET["id"])));
}

if( $_GET["DetectLocation"] == "竹東" )
	        $nav = "flag6";
else if( $_GET["DetectLocation"] == "新竹都城隍廟周邊道路" )
	        $nav = "flag7";
else if( $_GET["DetectLocation"] == "移動式測速" )
        $nav = "flag8";
else if( $_GET["DetectLocation"] == "巨城" )
                $nav = "flag9";
else if( $_GET["DetectLocation"] == "慈雲" )
                $nav = "flag10";
else if( $_GET["DetectLocation"] == "固定式測速" )
                $nav = "flag11";
else if( $_GET["DetectLocation"] == "西大北大" )
                $nav = "flag12";
else
        $nav = "flagx";


if( $_POST["format"] == "json" && $_POST["file_name"] != "" && $_POST["base64_attachment"] != "" ) {
$data = $_POST["base64_attachment"];
file_put_contents('aaaa.txt', $data);
list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);
file_put_contents('aaa.png', $data);
$arr = array('optimized_file_url'=>'test.png');
echo '此功能尚未開放!';
exit;
}

if( $_GET["act"] == "ucomment" && $_GET["id"] != "" ) {
        $pdo->prepare("UPDATE `cases` set `comment`=? where `id`=?")->execute(array($_POST["comment"],intval($_GET["id"])));
}

if( $_POST["_method"] == "put" ) {
	if( $_POST["case"]["expose"] == "true" ) {
	if( $_POST["case"]["car_type_code"] == "" || $_POST["case"]["reason_code"] == "" ) {
		if( $_POST["case"]["car_type_code"] == "" ) {
		        $car_msg="is-invalid";
		        $car_msg2='<div class="invalid-feedback">車種尚未選擇</div>';
		}
                if( $_POST["case"]["reason_code"] == "" ) {
                        $re_msg="is-invalid";
                        $re_msg2='<div class="invalid-feedback">條款尚未選擇</div>';
                }
	} else {
	$cdate = date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array($_POST["state"],intval($_POST["id"])));
	if( $_POST["state"] == "reviewing" ) {
		if( !isset($_POST["case"]["car_place"]) )
			$car_place = null;
		else
			$car_place = $_POST["case"]["car_place"];

		        $pdo->prepare("UPDATE `cases` set `expose`=?,`car_type_code`=?,`car_place`=?,`reason_code`=?,`review_user_id`=?,`review_department`=?,`state`=? where `id`=?")->execute(array('舉發',$_POST["case"]["car_type_code"],$car_place,$_POST["case"]["reason_code"],$row_user["name"],$row_user["department"].$row_user["unit"],$_POST["state"],intval($_POST["id"])));
		$msg = '從案件處理 > 案件審核('.$row_user["name"].')';
		$pdo->prepare("INSERT INTO `case_history` (`id`,`cases_id`,`user_id`,`message`,`cdate`) VALUES ( NULL, ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
		echo '<script>document.location.href="cases3.php?page='.$_SESSION["page"].'&state=ready&n";</script>';
	} else if( $_POST["state"] == "complete" ) {
		$msg = "從案件審核 > 案件結束(".$row_user["name"].")";
		$complete_date=date("Y-m-d H:i:s");
	        $pdo->prepare("UPDATE `cases` set `complete_date`=? where `id`=?")->execute(array($complete_date,intval($_POST["id"])));
		$pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
		echo '<script>document.location.href="cases3.php?page='.$_SESSION["page"].'&state=reviewing&n";</script>';	
	} else {
		echo '<script>document.location.href="cases3.php?n";</script>';
	}
	exit;
	}
	} else {
        $cdate = date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array($_POST["state"],intval($_POST["id"])));
        if( $_POST["state"] == "reviewing" ) {
                        $pdo->prepare("UPDATE `cases` set `expose`=?,`expose_msg`=?,`review_user_id`=?,`review_department`=?,`state`=? where `id`=?")->execute(array('不舉發',$_POST["case"]["expose_msg"],$row_user["name"],$row_user["department"].$row_user["unit"],$_POST["state"],intval($_POST["id"])));
                $msg = '從案件處理 > 案件審核('.$row_user["name"].')';
                $pdo->prepare("INSERT INTO `case_history` (`id`,`cases_id`,`user_id`,`message`,`cdate`) VALUES ( NULL, ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
                echo '<script>document.location.href="cases3.php?page='.$_SESSION["page"].'&state=ready&n";</script>';
        } else if( $_POST["state"] == "complete" ) {
                $msg = "從案件審核 > 案件結束(".$row_user["name"].")";
		$complete_date=date("Y-m-d H:i:s");
                $pdo->prepare("UPDATE `cases` set `complete_date`=? where `id`=?")->execute(array($complete_date,intval($_POST["id"])));
                $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
                echo '<script>document.location.href="cases3.php?page='.$_SESSION["page"].'&state=reviewing&n";</script>';
        } else {
                echo '<script>document.location.href="cases3.php?n";</script>';
        }
        exit;
	}
}

if( $_GET["act"] == "fback" && $_GET["state"] == "ready" ) {
	$cdate = date("Y-m-d H:i:s");
	$msg = '從案件審核 > 案件處理('.$row_user["name"].')';
	$pdo->prepare("INSERT INTO `case_history` (`id`,`cases_id`,`user_id`,`message`,`cdate`) VALUES ( NULL, ?, ?, ?, ? )")->execute(array(intval($_GET["id"]),$row_user["id"],$msg,$cdate));
$pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array($_GET["state"],intval($_GET["id"])));
echo '<script>document.location.href="cases3.php?page='.$_SESSION["page"].'&state=reviewing&n";</script>';
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
  

<link rel="stylesheet" media="all" href="/wos/assets/admin-92e995e469ea98c880e61710f498cb7c0dddcd185d591b92bc985fb93e14d29a.css" />
<script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
<script>
$(function() {
	$("#full_car_number").change(function(){
$.ajax({
	type: 'POST', 
    	url: '/wos/php/chfullcarnumber.php', 
	data: {
		id: '<?=$_GET["id"];?>',
		full_car_number: $('#full_car_number').val()
	},
	success: function (response) { 
		alert('車牌號碼已更新');
        },
    	error:function(xhr){
		alert("發生錯誤: " + xhr.status + " " + xhr.statusText);
	}
});
	});
});
</script>
</head>
  <body id="page-top" class="case_wrapper cases index collection">
<?php include "menu.php";?>
    <div id="wrapper">


      <div id="content-wrapper">
        <div class="container-fluid" style="padding-left: 20%;padding-right: 20%;">
          <div class="flashes">
            

          </div>

          



<?php
$result = $pdo->query("select * from `cases` where `id`='".$_GET["id"]."'");
$row = $result->fetch(PDO::FETCH_ASSOC);

$ojpg = '/var/www/html/CarSystem/'.str_replace('\\','/',$row["PhotoURL"]); 
$sjpg = '/var/www/html/CarSystem/'.str_replace( 'result','small_result',str_replace('\\','/',$row["PhotoURL"]));
if( !file_exists( $sjpg ) ) {
	//ImageResize($ojpg,$sjpg);
exec("/usr/bin/convert -geometry 165x165 $ojpg $sjpg");

}

$nvi = '/var/www/html/CarSystem/'.$row["VideoURL"];
if (filesize($nvi)==0){
	exec("/bin/rm ".$nvi);
	$buffv = explode('/',$row["VideoURL"]);
	if( $buffv[0] == "big2" ) {
		$ovi=glob('/var/www/html/CarSystem/'.$buffv[0].'/'.$buffv[1].'/'.$buffv[2].'/'.$buffv[3].'/*.AVI');
		exec("/usr/bin/ffmpeg -i ".$ovi[0]." -vcodec h264 -y -max_muxing_queue_size 1024 ".$nvi);
	}
//	exec("/usr/bin/ffmpeg -i ".$ovi." -vcodec h264 -y -max_muxing_queue_size 1024 ".$nvi);
}


$avi = '/var/www/html/CarSystem/'.str_replace('\\','/',$row["VideoURL"]);
$mp4 = '/var/www/html/CarSystem/'.str_replace( 'avi','mp4',str_replace('\\','/',$row["VideoURL"]) );
if( !file_exists( $mp4 ) ) {
        exec("/usr/bin/ffmpeg -i $avi $mp4");
}
?>
	<form class="simple_form edit_case" id="edit_case_199115" autocomplete="off" action="cases3_edit.php?DetectLocation=<?=$_GET["DetectLocation"];?>&id=<?=$row["id"];?>" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="_method" value="put" /><input type="hidden" name="authenticity_token" value="UcPkr/xUVFGzbM/ZV1DoDt1yvvNUz1qYFtalwcoUTXewp4xWHt9bnmFIiGWZ4tG+pLNo8WcEUmqOITYvKcXdDw==" />

  <div class="row">
    <div class="col-lg-12">
      <div class="card mb-3">
        <div class="card-header">
          <b>檢舉案件明細(目前狀態: <?php echo $car_array["state"][$row["state"]];?>)</b>
          <div class="float-right">
<?php if( $_SESSION["acc"] == "admin" ) { ?>
	  <a target="_blank" class="btn" style="border-color: #ff0000;background-color: #ff0000;color: #fff;" href="/wos/php/cases_del.php?id=<?=$row["id"];?>&DetectLocation=<?=$_GET["DetectLocation"];?>" onclick="return confirm('請確認是否刪除? 刪除後無法恢復!');">刪除</a>
<?php } ?>
	  <a target="_blank" class="btn btn-success" href="card.php?id=<?=$row["id"];?>">列印</a>
	  <a href="cases3.php?DetectLocation=<?=$_GET["DetectLocation"];?>&page=<?=$_SESSION["page"];?>" class="btn btn-primary back2list">回到列表</a>

<span class="divider"></span>

          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <!--<dl class="row">
                <dt class="col-md-2">
                  重覆篩選：
                </dt>
                <dd class="col-sm-10">
                  <a target="_blank" href="/wos/admin/cases/all?case_id=198896&amp;range=30">0</a>
                </dd>
              </dl>-->
                <table border="1" align="center" style="width: 100%">
  <tr>
    <td colspan="2" align="center">案件資料</td>
  </tr>
  <tr>
    <td>案件編號</td>
    <td><?=$row["sn"];?></td>
  </tr>

  <tr>
    <td width="20%">違規日期/時間</td>
    <td width="80%"><?=$row["created_at"];?></td>
  </tr>

  <!--<tr>
    <td>違規車種</td>
    <td><?=$row["car_type_code"];?></td>
  </tr>-->

  <tr>
    <td>違規車號</td>
    <td><input type="text" name="full_car_number" value="<?=$row["full_car_number"];?>" size="9" id="full_car_number"></td>
  </tr>

  <tr>
    <td>違規地點</td>
    <td><?=$row["cam_place"];?></td>
  </tr>
  <tr>
    <td>違規事由</td>
    <td><?php
if( $row["cam_reason"] == "redlight" )
	echo '闖紅燈';
else if( $row["cam_reason"] == "turnleft" && $row["DetectLocation"]=="慈雲" )
	echo '違規迴轉';
else if( $row["cam_reason"] == "turnleft" && $row["DetectLocation"]=="巨城" )
	echo '違規左轉';
else if( $row["cam_reason"] == "turnleft" )
	echo '違規左轉';
else if( $row["cam_reason"] == "4" )
	echo '未依標誌標線行駛';
else if( $row["cam_reason"] == "straightgoing" )
	echo '未依標誌標線行駛';
else if( $row["cam_reason"] == "turnright" )
	echo '未依標誌標線行駛';
else if( $row["cam_reason"] == "detention" )
	echo '未保持路口淨空';
else if( $row["cam_reason"] == "Utuen" )
	echo '違規迴轉';
else
	echo $row["cam_reason"];?></td>
  </tr>
<?php if( $row["cam_reason"] == "超速" ) { ?>
<tr>
<td>限速/測速</td>
<td>
        <div class="row" style="margin-top:0px;">
          <div class="col-xs" style="padding-right:0px;float:left; display:inline;">
         &nbsp; （限速&nbsp; <input class="form-control string required form-control" style="text-transform:uppercase;width:54px;display:initial;height: 29px;" required="required" aria-required="true" placeholder="" type="text" name="doc[car_max]" id="doc_first_car_number" value="<?php if( $car_max != "" ) echo $car_max; if( $row["car_speed_max"] !="" ) echo $row["car_speed_max"];?>" required/> 公里 ,</div>
          <div class="col-xs" style="padding-left:0px;">實際測速 <input class="form-control string required form-control" style="text-transform:uppercase;width:54px;display:initial;height: 29px;" required="required" aria-required="true" placeholder="" type="text" name="doc[car_real]" id="doc_last_car_number" value="<?php if( $car_real != "" ) echo $car_real; if( $row["car_speed"] !="" ) echo $row["car_speed"];?>" required /> 公里 )
          </div>
        </div>
</td>
</tr>
<?php } ?>
  <tr>
    <td>舉證/舉發資料</td>
    <td>
<div id="thumbnails_199115">
<div id="nanogallery2_199115" class="ngy2_container nGY2 nanogallery_gallerytheme_dark_nanogallery2_199115" style="overflow: visible;"><div class="nGY2Navigationbar"></div><div class="nanoGalleryLBarOff"><div></div><div></div><div></div><div></div><div></div></div><div class="nGY2Gallery" style="overflow: visible; opacity: 1;"><div class="nGY2GallerySub" style="overflow: visible; touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); transform: none; width: 334px; height: 166px;"><div class="nGY2GThumbnail" style="display: block; top: 0px; left: 0px; width: 166px; height: 166px;"><div class="nGY2GThumbnailSub ">

<?php
	if( file_exists('/var/www/html/CarSystem/'.$row["PhotoURL"])) { ?>
<div class="nGY2GThumbnailImage nGY2TnImgBack" style="position: absolute; top: 0px; left: 0px; width: 162px; height: 162px; background-position: center center; background-repeat: no-repeat; background-size: cover; overflow: hidden;"></div><div class="nGY2GThumbnailImage nGY2TnImg" style="opacity: 1; position: absolute; top: 0px; left: 0px; width: 162px; height: 162px; background-image: url(&quot;/CarSystem/<?=str_replace('\\','/',str_replace('result','small_result',$row["PhotoURL"]));?>&quot;); background-position: center center; background-repeat: no-repeat; background-size: cover; overflow: hidden;">  <img class="nGY2GThumbnailImg nGY2TnImg2" src="/CarSystem/<?=str_replace('\\','/',str_replace('result','small_result',$row["PhotoURL"]));?>" alt="" style="opacity:0;" data-idx="1" data-albumidx="0"></div><div class="nGY2GThumbnailCustomLayer"></div>  <div class="nGY2GThumbnailLabel" style="bottom:0; position:absolute;text-align:center;">    <div class="nGY2GThumbnailTitle nGY2GThumbnailImageTitle" style=""></div>    <div class="nGY2GThumbnailDescription" style="display:none;"></div>  </div><div class="nGY2GThumbnailIconsFullThumbnail"></div><div class="thumb_toolbar" style="background-color:#000;opacity: 0.6;width:100%;position:absolute;top:0%;left:0%;padding:10px;"><input disabled="" style="" type="checkbox" name="case_attachment_ids[]" checked="" value="675340" data-ngy2action="select_thumbnail"><a style="float:right" class="ngy2info" data-ngy2action="info" target="_blank" href="/CarSystem/<?=str_replace('\\','/',$row["PhotoURL"]);?>">原檔</a></div></div></div>
<?php } else {?>
<div class="nGY2GThumbnailImage nGY2TnImgBack" style="position: absolute; top: 0px; left: 0px; width: 162px; height: 162px; background-position: center center; background-repeat: no-repeat; background-size: cover; overflow: hidden;"></div><div class="nGY2GThumbnailImage nGY2TnImg" style="opacity: 1; position: absolute; top: 0px; left: 0px; width: 162px; height: 162px; background-image: url(&quot;/CarSystem/<?=$row["pic1"];?>&quot;); background-position: center center; background-repeat: no-repeat; background-size: cover; overflow: hidden;">  <img class="nGY2GThumbnailImg nGY2TnImg2" src="/CarSystem/<?=$row["pic1"];?>" alt="" style="opacity:0;" data-idx="1" data-albumidx="0"></div><div class="nGY2GThumbnailCustomLayer"></div>  <div class="nGY2GThumbnailLabel" style="bottom:0; position:absolute;text-align:center;">    <div class="nGY2GThumbnailTitle nGY2GThumbnailImageTitle" style=""></div>    <div class="nGY2GThumbnailDescription" style="display:none;"></div>  </div><div class="nGY2GThumbnailIconsFullThumbnail"></div><div class="thumb_toolbar" style="background-color:#000;opacity: 0.6;width:100%;position:absolute;top:0%;left:0%;padding:10px;"><input disabled="" style="" type="checkbox" name="case_attachment_ids[]" checked="" value="675340" data-ngy2action="select_thumbnail"><a style="float:right" class="ngy2info" data-ngy2action="info" target="_blank" href="/CarSystem/<?=$row["pic1"];?>">原檔</a></div></div></div>
<?php } ?>
<?php
if( $row["VideoURL"] != "" ) {
?>
<div class="nGY2GThumbnail" style="display: block; top: 0px; left: 168px; width: 166px; height: 166px;"><div class="nGY2GThumbnailSub "><div class="nGY2GThumbnailImage nGY2TnImgBack" style="position: absolute; top: 0px; left: 0px; width: 162px; height: 162px; background-position: center center; background-repeat: no-repeat; background-size: cover; overflow: hidden;"></div><div class="nGY2GThumbnailImage nGY2TnImg" style="opacity: 1; position: absolute; top: 0px; left: 0px; width: 162px; height: 162px; background-image: url(&quot;/wos/assets/video-player-e07c798bcb9faae5aae7ff042828949e27ff115e3bc8091446cd3a4ce8f0db38.png&quot;); background-position: center center; background-repeat: no-repeat; background-size: cover; overflow: hidden;">  <img class="nGY2GThumbnailImg nGY2TnImg2" src="/hsin/assets/video-player-e07c798bcb9faae5aae7ff042828949e27ff115e3bc8091446cd3a4ce8f0db38.png" alt="video" style="opacity:0;" data-idx="2" data-albumidx="0"></div><div class="nGY2GThumbnailCustomLayer"></div>  <div class="nGY2GThumbnailLabel" style="bottom:0; position:absolute;text-align:center;">    <div class="nGY2GThumbnailTitle nGY2GThumbnailImageTitle" style="">video</div>    <div class="nGY2GThumbnailDescription" style="display:none;"></div>  </div><div class="nGY2GThumbnailIconsFullThumbnail"></div><div class="thumb_toolbar" style="background-color:#000;opacity: 0.6;width:100%;position:absolute;top:0%;left:0%;padding:10px;"><a style="float:right" class="ngy2info" data-ngy2action="info" target="_blank" href="/CarSystem/<?=str_replace('\\','/',$row["VideoURL"]);?>">原檔</a></div></div></div>
<?php } ?>
<div class="nGY2GalleryBottom"></div></div><div class="nGY2ConsoleParent"></div></div>

        </div>
    </td>
  </tr>
</table>

<br/>
<br/>

<table border="1" align="center" style="width: 100%">
  <tr>
    <td colspan="2" align="center">案件處理</td>
  </tr>

<?php
if( $row["DetectLocation"] != "移動式測速" ) {
?>
  <tr>
    <td width="20%">限辦日期</td>
    <td><?php echo date('Y-m-d',strtotime("$row[created_at] +14 day"));?></td>
  </tr>



      <!--<tr>
        <td>結案方式</td>
        <td>
          <input type="hidden" name="case[expose]" value="" /><div class="form-check-inline"><input class="form-check-input radio_buttons optional" type="radio" value="true" checked="checked" name="case[expose]" id="case_expose_true" /><label class="form-check-label collection_radio_buttons" for="case_expose_true">舉發</label></div>--><!--<div class="form-check-inline"><input class="form-check-input radio_buttons optional" readonly="readonly" type="radio" value="false" name="case[expose]" id="case_expose_false" /><label class="form-check-label collection_radio_buttons" for="case_expose_false">不舉發</label></div>
        </td>
      </tr>-->
      <tr>
        <td>結案方式</td>
        <td>
<?php echo $row["expose"];?>
        </td>
      </tr>
<?php } ?>
<?php if( $row["DetectLocation"] == "新竹都城隍廟周邊道路" ) { ?>
      <tr class="car_form">
        <td>地點</td>
        <td>
<?php if( $row["state"] == "ready" ) { ?>
<select class="form-control <?=$place_msg;?> select optional form-control select2" name="case[car_place]" id="case_place">
<option value="">請選擇地點</option>
<option <?php if( $row["car_place"] == "新竹縣北區中山路72號前(新竹第一信用合作社)" || $_POST["case"]["car_place"] == "新竹縣北區中山路72號前(新竹第一信用合作社)" ) echo "selected";?>>新竹縣北區中山路72號前(新竹第一信用合作社)</option>
<option <?php if( $row["car_place"] == "新竹縣北區中山路73號前(新復珍)" || $_POST["case"]["car_place"] == "新竹縣北區中山路73號前(新復珍)" ) echo "selected";?>>新竹縣北區中山路73號前(新復珍)</option>
<option <?php if( $row["car_place"] == "新竹縣北區中山路65號前" || $_POST["case"]["car_place"] == "新竹縣北區中山路65號前" ) echo "selected";?>>新竹縣北區中山路65號前</option>
<option <?php if( $row["car_place"] == "新竹縣北區中山路84號前(新光銀行)" || $_POST["case"]["car_place"] == "新竹縣北區中山路84號前(新光銀行)" ) echo "selected";?>>新竹縣北區中山路84號前(新光銀行)</option>
<option <?php if( $row["car_place"] == "新竹縣北區中山路90號前(全家便利商店)" || $_POST["case"]["car_place"] == "新竹縣北區中山路90號前(全家便利商店)" ) echo "selected";?>>新竹縣北區中山路90號前(全家便利商店)</option>
<option <?php if( $row["car_place"] == "新竹縣北區中山路城隍廟廣場前" || $_POST["case"]["car_place"] == "新竹縣北區中山路城隍廟廣場前" ) echo "selected";?>>新竹縣北區中山路城隍廟廣場前</option>
</select>
<?=$place_msg2;?>
<?php } else { ?>
<?=$row["car_place"];?>
<?php } ?>
        </td>
      </tr>
<?php } ?>
      <tr class="car_form">
        <td>車種</td>
        <td>
<?php
if( $row["DetectLocation"] == "移動式測速" ) { 
	echo $row["car_type_code"];
}
?>
<?=$car_msg2;?>
        </td>
      </tr>
      <tr class="reason_form">
        <td>舉發條款</td>
        <td>
          <!--<div class="form-group expose_fields row">
            <div class="col-sm-10 ">-->
<?=$row["reason_code"];?>
<?php
if( $row["car_speed_max"] != "" ) {
	echo '（限速  '.$row["car_speed_max"].' 公里 , 實際測速 '.$row["car_speed"].' 公里 )';
}
?>
            <!--</div>
          </div>-->

          <div class="form-group unexpose_fields row" style="display: none;">
            <div class="col-sm-10 ">
              <input class="form-control string optional form-control " type="text" name="case[unexpose_reason_note]" id="case_unexpose_reason_note" />
            </div>
          </div>

        </td>
      </tr>
<tr class="unexpose_fields" style="display: none;">
        <td>不舉發原因</td>
<td>
          <div class="row"> 
            <div class="col-sm-10 ">
	    <?=$row["expose_msg"];?>
            </div>
          </div>
</td></tr>





  <tr>
    <td>結案附加訊息</td>
    <td>
    <span data-bip-type="input" data-bip-attribute="comment" data-bip-placeholder="<?php
                if( $row["comment"] != "" )
			                        echo $row["comment"];
		                else
					echo '點選新增備註';?>" data-bip-object="case" data-bip-ok-button="儲存" data-bip-cancel-button="取消" data-bip-skip-blur="false" data-bip-url="cases3_edit.php?id=<?=$_GET["id"];?>" class="best_in_place" id="best_in_place_case_198896_comment"></span>
    </td>
  </tr>

  <tr>
    <td>舉發人員</td>
    <td>
    <?php if( $row["review_user_id"] == "" ) echo $row["review_user_id"]?$row["review_user_id"]:$row_user["name"]; else echo $row["review_user_id"];?> ( <?=$row["review_department"];?> )
    </td>
  </tr>

</table>

            </div>
          </div>
        </div>
        <div class="card-footer small text-muted">
          <div class="row">
              <div class="form-actions">
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>


  <div class="row">
<?php
$result = $pdo->query("select * from `case_history` where `cases_id`='".$row["id"]."' order by `cdate` asc");
$rowh = $result->fetch(PDO::FETCH_ASSOC);
if( $result->rowCount() >= 1 ) {
?>
    案件歷程
    <ul>
<?php 
$result = $pdo->query("select * from `case_history` where `cases_id`='".$row["id"]."' order by `cdate` asc");
while($rowh = $result->fetch(PDO::FETCH_ASSOC)){
?>
	<li><?=$rowh["cdate"];?>&nbsp;<?=$rowh["message"];?></li>
<?php } ?>
    </ul>
<?php } ?>
  </div>



    
  </div>

</div>
</form>

<canvas id="canvas" style="display: none"></canvas>

<div class="hover_content" style="display: none;">

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



    <script src="/wos/assets/admin-7e641842b7678866dba9f029b1984fc78978fbe3c300f84802bb98e82b1f6905.js"></script>
        <script type="text/javascript">
      $(function(){
        function myTnTool( action, item ) {
  switch( action ) {
    case 'info':
      break;
    case 'select_thumbnail':
      break;
  }
}

function myTnInit( $e, item, GOMidx ) {
  var source_url = item.customData.origin_url
  var source_link = ''
  var st = 'float:right'
  var st1 = ''
  var st2 = 'background-color:#000;opacity: 0.6;width:100%;position:absolute;top:0%;left:0%;padding:10px;'

  if(item.mediaKind == 'img'){
    if(jQuery.isEmptyObject(item.customData)){
      var checked = '';
      var ckbox = '<input onchange="check_images(this, 199115)" style="'+st1+'" type="checkbox" name="base64_attachments[]" ' + checked + ' value="' + item.src + '" data-ngy2action="select_thumbnail">'

    }else{
      var item_id = item.customData.id
      var selected = item.customData.selected
      var checked = selected ? 'checked' : ''
      var ckbox = '<input onchange="check_images(this, 199115)" style="'+st1+'" type="checkbox" name="case_attachment_ids[]" ' + checked + ' value="' + item_id + '" data-ngy2action="select_thumbnail">'
    }
  }else{
    var ckbox = ''
  }

   // ckbox = '<input disabled style="'+st1+'" type="checkbox" name="case_attachment_ids[]" ' + checked + ' value="' + item_id + '" data-ngy2action="select_thumbnail">'

  if(source_url != undefined){
    source_link = '<a style="'+st+'" class="ngy2info" data-ngy2action="info" target="_blank" href="' + source_url + '">原檔</a>'

  }
  $e.find('.nGY2GThumbnailSub').append('<div class="thumb_toolbar" style="' + st2 + '">' + ckbox + source_link + '</div>');
}
var t;
var hover_thumbnail = function($thumbnail, item, GOMidx){
  t = setTimeout(function() {
    if(item.mediaKind == 'img'){

      $('.hover_content').html(item.mediaMarkup).show()

    }else if(item.mediaKind == 'video'){

      $('.hover_content').html(item.mediaMarkup).show()
      $(".hover_content video")[0].autoplay = true
    }
  }, 1500);


}

var hoverout_thumbnail = function($thumbnail, item, GOMidx){
  // $('.hover_content').html('').hide()
  clearTimeout(t)
}

var snapshot = function( customElementName, $customIcon, item ) {
  if(item.mediaKind == 'video'){
    var video = document.querySelector('.imgCurrent video');
    var canvas = document.querySelector('canvas');
    var context = canvas.getContext('2d');
    var w, h, ratio;
    ratio = video.videoWidth / video.videoHeight;
    w = video.videoWidth - 100;
    h = parseInt(w / ratio, 10);
    canvas.width = w;
    canvas.height = h;
    context.fillRect(0, 0, w, h);
    context.drawImage(video, 0, 0, w, h);
    var d = canvas.toDataURL();
    var ngy2data = $("#nanogallery2_199115").nanogallery2('data');
    var instance = $("#nanogallery2_199115").nanogallery2('instance');

    var ID = ngy2data.items.length + 1;
    var date = moment(); //Get the current date
    var file_name = date.format("YYYYMMDD") + "_sc_" + ID; //2014-07-10
    var albumID = '0';
    var newItem = NGY2Item.New(instance, file_name, '', ID, albumID, 'image', '' );
    $.post("/wos/cases3_edit.php", {format: 'json', file_name: file_name + ".png", base64_attachment: d}, function(data){
      var image_url = data.optimized_file_url
	      alert( data );
      // create the new item

      newItem.thumbSet(image_url, 200, 200);
      newItem.setMediaURL(image_url, 'img');
      newItem.customData = {
        id: data.id,
        selected: data.selected,
        origin_url: data.file.url
      }


      newItem.addToGOM();
      $("#nanogallery2_199115").nanogallery2('refresh');
      toastr.success('截圖成功');
    })
  }else{
    toastr.error("只能在影片中截圖");
  }
}

$("#nanogallery2_199115").nanogallery2({
items: [{"src":"/CarSystem/<?php if( file_exists('/var/www/html/CarSystem/'.$row["PhotoURL"])) { 
echo str_replace('\\','/',$row["PhotoURL"]); } else {
echo str_replace('\\','/',$row["pic1"]);
}?>","srct":"/CarSystem/<?php if( file_exists('/var/www/html/CarSystem/'.$row["PhotoURL"])) {
echo str_replace('\\','/',str_replace('result','small_result',$row["PhotoURL"])); } else {
echo str_replace('\\','/',$row["pic1"]);
}?>","title":"","customData":{"id":675340,"selected":true,"origin_url":"/CarSystem/<?php
if( file_exists('/var/www/html/CarSystem/'.$row["PhotoURL"])) {
echo str_replace('\\','/',$row["PhotoURL"]); } else { echo str_replace('\\','/',$row["pic1"]);}?>"}}
<?php if( !file_exists('/var/www/html/CarSystem/'.$row["PhotoURL"]) && $row["pic2"] != null ) { ?>
,{"src":"/CarSystem/<?=str_replace('\\','/',$row["pic2"]);?>","srct":"/CarSystem/<?=str_replace('\\','/',$row["pic2"]);?>","title":"","customData":{"id":675342,"selected":true,"origin_url":"/CarSystem/<?=str_replace('\\','/',$row["pic2"]);?>"}}
<?php }?>
<?php if( $row["VideoURL"] == "" ) { ?>,{"src":"/CarSystem/<?=str_replace('avi','mp4',str_replace('\\','/',$row["VideoURL"]));?>","srct":"/wos/assets/video-player-e07c798bcb9faae5aae7ff042828949e27ff115e3bc8091446cd3a4ce8f0db38.png","thumbnailOpenImage":false,"title":"video","customData":{"id":675341,"selected":false,"origin_url":"/CarSystem/<?=str_replace('\\','/',$row["VideoURL"]);?>"}}<?php } ?>],
  thumbnailWidth:           200,
  thumbnailHeight:          200,
  allowHTMLinData:          true,
  viewerImageDisplay:       'upscale',
  displayDescription:       true,
  thumbnailGutterWidth: 2,
  thumbnailLabel:  { "position": "overImageOnBottom", "hideIcons": true },
  fnThumbnailInit:            myTnInit,
  fnThumbnailToolCustAction:  myTnTool,
  displayDescription: true,
  viewerHideToolsDelay: 999999999,
  viewerToolbar:    {
    display: true,
    standard:  'label',
  },
  viewerTools: {
    topRight:  'custom1, rotateLeft, rotateRight, closeButton' },
  icons: { viewerCustomTool1:'<i class="fas fa-camera"></i>'},
  fnImgToolbarCustClick: snapshot,
  // fnThumbnailHover: hover_thumbnail,
  // fnThumbnailHoverOut: hoverout_thumbnail
});
      })
    </script>
  <script type="text/javascript">
    function assign_case(d, case_id){
      var department_id = $(d).val();
      $.post("/wos/admin/cases/assign", {case_ids: case_id, department_id: department_id})
    };

    $(function(){
	    $('.back2list').attr('href', "cases3.php?DetectLocation=<?=$_GET["DetectLocation"];?>&page=<?=$_SESSION["page"];?>")
      $('nav li.cases_ready').addClass('active');
      $('input[name="case[expose]"]').change(function() {

        if($(this).val() === 'true' ){
 //         $('.expose_fields').show()
          $('.unexpose_fields').hide()
		  $('.car_form').show()
		  $('.reason_form').show()
        }else{
//          $('.expose_fields').hide()
          $('.unexpose_fields').show()
	          $('.car_form').hide()
                  $('.reason_form').hide()
        }
      });
<?php if( $row["expose"] == "不舉發" && $row["state"] != "ready" ) { ?>
                  $('.car_form').hide()
                  $('.reason_form').hide()
		  $('.unexpose_fields').show()
<?php } ?>
    })
  </script>

  </body>



</html>

