<?php
include "function.php";
if( $_GET["nocache"] == "12345678" ) {
//echo '<script>location.reload(true);</script>';
}
$putdata=file_get_contents('php://input');
$buffa = explode('&',urldecode($putdata));
$buffb = explode('=',$buffa[1]);
//刪除 cases_img
if( $_GET["id"] != "" && $_GET["act"]=="delop" && $_GET["pid"] != "" ) {
	        $result = $pdo->query("select * from `cases_img` where `id`='".$_GET["pid"]."'");
		$row = $result->fetch(PDO::FETCH_ASSOC);
$pdo->prepare("DELETE from `cases_img` where `id`=?")->execute(array($_GET["pid"]));
unlink('../CarSystem/'.$row["img"]);
echo '<script>document.location.href="cases_edit.php?id='.$_GET["id"].'";</script>';
}
//取代主圖片
if( $_GET["id"] != "" && $_GET["act"]=="chjpga" && $_GET["pid"] != "" ) {
$result = $pdo->query("select * from `cases` where `id`='".$_GET["id"]."'");
$row = $result->fetch(PDO::FETCH_ASSOC);
$resultn = $pdo->query("select * from `cases_img` where `id`='".$_GET["pid"]."'");
$rown = $resultn->fetch(PDO::FETCH_ASSOC);
if( $row["pic1"] != "" ) {
unlink('../CarSystem/'.$row["pic1"]);
copy('/var/www/html/CarSystem/'.$rown["img"],'/var/www/html/CarSystem/'.$row["pic1"]);
$pdo->prepare("DELETE from `cases_img` where `id`=?")->execute(array($_GET["pid"]));
unlink('../CarSystem/'.$rown["img"]);
echo '<script>document.location.href="cases_edit.php?id='.$_GET["id"].'";</script>';
}
}
//取代副圖片
if( $_GET["id"] != "" && $_GET["act"]=="chjpgb" && $_GET["pid"] != "" ) {
$result = $pdo->query("select * from `cases` where `id`='".$_GET["id"]."'");
$row = $result->fetch(PDO::FETCH_ASSOC);
$resultn = $pdo->query("select * from `cases_img` where `id`='".$_GET["pid"]."'");
$rown = $resultn->fetch(PDO::FETCH_ASSOC);
if( $row["pic2"] != "" ) {
unlink('../CarSystem/'.$row["pic2"]);
copy('/var/www/html/CarSystem/'.$rown["img"],'/var/www/html/CarSystem/'.$row["pic2"]);
$pdo->prepare("DELETE from `cases_img` where `id`=?")->execute(array($_GET["pid"]));
unlink('../CarSystem/'.$rown["img"]);
echo '<script>document.location.href="cases_edit.php?id='.$_GET["id"].'";</script>';
}
}

if( $buffa[0] == "_method=put" && $buffb[1] != "" && $_GET["id"] != "" ) {
$pdo->prepare("UPDATE `cases` set `comment`=? where `id`=?")->execute(array($buffb[1],intval($_GET["id"])));
}

if( $_POST["format"] == "json" && $_POST["file_name"] != "" && $_POST["base64_attachment"] != "" && $_POST["cases_id"] != "" ) {
	$result = $pdo->query("select * from `cases` where `id`='".$_POST["cases_id"]."'");
	$row = $result->fetch(PDO::FETCH_ASSOC);
	if(!file_exists('../CarSystem/other/')) {
	mkdir('../CarSystem/other',0777);
	}
	if(!file_exists('../CarSystem/other/'.date("Y-m-d").'/')) {
		mkdir('../CarSystem/other/'.date("Y-m-d"),0777);
	}
	$picdir = '/CarSystem/other/'.date("Y-m-d");

$data = $_POST["base64_attachment"];
list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);
file_put_contents('..'.$picdir.'/'.$_POST["file_name"], $data);
$img = 'other/'.date("Y-m-d").'/'.$_POST["file_name"];
$pdo->prepare("INSERT INTO `cases_img` (`id`,`cid`,`img`) VALUES ( NULL, ?, ? )")->execute(array(intval($_POST["cases_id"]),$img));
$arr = array('optimized_file_url'=>$picdir.'/'.$_POST["file_name"],'id'=>'10000','selected'=> True,'file'=>array('url'=>$picdir.'/'.$_POST["file_name"]));
echo json_encode($arr); 
exit;
}

$nav="";
if( $_GET["act"] == "ucomment" && $_GET["id"] != "" ) {
        $pdo->prepare("UPDATE `cases` set `comment`=? where `id`=?")->execute(array($_POST["comment"],intval($_GET["id"])));
}

if( $_POST["_method"] == "put" ) {
	if( $_POST["case"]["expose"] == "true" ) {
	if( $_POST["case"]["car_type_code"] == "" || $_POST["case"]["reason_code"] == "" || $_POST["case"]["car_number"] == "" ) {
		if( $_POST["case"]["car_type_code"] == "" ) {
		        $car_msg="is-invalid";
		        $car_msg2='<div class="invalid-feedback">車種尚未選擇</div>';
		}
                if( $_POST["case"]["reason_code"] == "" ) {
                        $re_msg="is-invalid";
                        $re_msg2='<div class="invalid-feedback">條款尚未選擇</div>';
                }
                if( $_POST["case"]["car_number"] == "" ) {
                        $cnum_msg="is-invalid";
                        $cnum_msg2='<font color="red" size="2">車號尚未填寫</font>';
                }
	} else {
	$cdate = date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array($_POST["state"],intval($_POST["id"])));
	if( $_POST["state"] == "reviewing" ) {
		if( !isset($_POST["case"]["car_place"]) )
			$car_place = null;
		else
			$car_place = $_POST["case"]["car_place"];

		$buffa = explode( "-",$_POST["case"]["car_number"]);
		        $pdo->prepare("UPDATE `cases` set `first_car_number`=?,`last_car_number`=?,`full_car_number`=?,`expose`=?,`car_type_code`=?,`car_place`=?,`reason_code`=?,`review_user_id`=?,`review_department`=?,`state`=?,`car_speed`=?,`car_speed_max`=? where `id`=?")->execute(array($buffa[0],$buffa[1],$_POST["case"]["car_number"],'舉發',$_POST["case"]["car_type_code"],$car_place,$_POST["case"]["reason_code"],$row_user["name"],$row_user["department"].$row_user["unit"],$_POST["state"],$_POST["doc"]["car_real"],$_POST["doc"]["car_max"],intval($_POST["id"])));
		$msg = '從案件處理 > 案件審核('.$row_user["name"].')';
		$pdo->prepare("INSERT INTO `case_history` (`id`,`cases_id`,`user_id`,`message`,`cdate`) VALUES ( NULL, ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
		echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=ready&n";</script>';
	} else if( $_POST["state"] == "complete" ) {
		$msg = "從案件審核 > 案件結束(".$row_user["name"].")";
		$complete_date=date("Y-m-d H:i:s");
	        $pdo->prepare("UPDATE `cases` set `complete_date`=? where `id`=?")->execute(array($complete_date,intval($_POST["id"])));
		$pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
		echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=reviewing&n";</script>';	
	} else {
		echo '<script>document.location.href="cases.php?n";</script>';
	}
	exit;
	}
	} else {
        $cdate = date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array($_POST["state"],intval($_POST["id"])));
        if( $_POST["state"] == "reviewing" ) {
		$buffa = explode( "-",$_POST["case"]["car_number"]);
                        $pdo->prepare("UPDATE `cases` set `first_car_number`=?,`last_car_number`=?,`full_car_number`=?,`expose`=?,`expose_msg`=?,`review_user_id`=?,`review_department`=?,`state`=?,`car_speed`=?,`car_speed_max`=? where `id`=?")->execute(array($buffa[0],$buffa[1],$_POST["case"]["car_number"],'不舉發',$_POST["case"]["expose_msg"],$row_user["name"],$row_user["department"].$row_user["unit"],$_POST["state"],$_POST["doc"]["car_real"],$_POST["doc"]["car_max"],intval($_POST["id"])));
                $msg = '從案件處理 > 案件審核('.$row_user["name"].')';
                $pdo->prepare("INSERT INTO `case_history` (`id`,`cases_id`,`user_id`,`message`,`cdate`) VALUES ( NULL, ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
                echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=ready&n";</script>';
        } else if( $_POST["state"] == "complete" ) {
                $msg = "從案件審核 > 案件結束(".$row_user["name"].")";
		$complete_date=date("Y-m-d H:i:s");
                $pdo->prepare("UPDATE `cases` set `complete_date`=? where `id`=?")->execute(array($complete_date,intval($_POST["id"])));
                $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
                echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=reviewing&n";</script>';
        } else {
                echo '<script>document.location.href="cases.php?n";</script>';
        }
        exit;
	}
}

if( $_GET["act"] == "fback" && $_GET["state"] == "ready" ) {
	$cdate = date("Y-m-d H:i:s");
	$msg = '從案件審核 > 案件處理('.$row_user["name"].')';
	$pdo->prepare("INSERT INTO `case_history` (`id`,`cases_id`,`user_id`,`message`,`cdate`) VALUES ( NULL, ?, ?, ?, ? )")->execute(array(intval($_GET["id"]),$row_user["id"],$msg,$cdate));
$pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array($_GET["state"],intval($_GET["id"])));
echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=reviewing&n";</script>';
exit;
}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hant">

<head>
<META HTTP-EQUIV="pragma" CONTENT="no-cache"> 
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate"> 
<META HTTP-EQUIV="expires" CONTENT="0">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">


  <title>新竹市警察局交通事故案件便民服務網</title>
<script src="jquery-3.4.1.min.js" crossorigin="anonymous"></script>
  <meta name="csrf-param" content="authenticity_token" />
<meta name="csrf-token" content="dtQ0kLcyBcoTBTfeopTr84sCjzI8L6Vb52QR2RrFbdb/HrWpAx49ZA8tu8Mof/VoQGweX+BmKtHA8TXwslShtw==" />
  

<link rel="stylesheet" media="all" href="/hsinchu/assets/admin-92e995e469ea98c880e61710f498cb7c0dddcd185d591b92bc985fb93e14d29a.css?<?=date("YmdHis");?>" />
<script>
        $(function() {
});
</script>

</head>

  <body id="page-top" class="case_wrapper cases index collection">
<?php include "menu.php";?>
    <div id="wrapper">


      <div id="content-wrapper">
      <div class="container-fluid" style="<?php if( ismobile()) { echo 'padding-left: 1%;padding-right: 1%;'; } else { echo 'padding-left: 20%;padding-right: 20%;'; }?>">
          <div class="flashes">
            

          </div>

          



<?php
$result = $pdo->query("select * from `cases` where `id`='".$_GET["id"]."'");
$row = $result->fetch(PDO::FETCH_ASSOC);


if( $_GET["act"] == "mergpic" ) {
	if( $row["pic1"] != "" ) {
		$buff = array_pop(explode('/', $row["pic1"]));
		$buffa = explode( $buff,$row["pic1"]);
		//建立備份
		if(!file_exists('/var/www/html/CarSystem/'.$buffa[0].'picbak.png')){
		copy('/var/www/html/CarSystem/'.$row["pic1"],'/var/www/html/CarSystem/'.$buffa[0].'picbak.png');
		}
		copy('/var/www/html/CarSystem/'.$buffa[0].'finalpic.png','/var/www/html/CarSystem/'.$row["pic1"]);
		echo '<script>document.location.href="cases_edit.php?id='.$row["id"].'&nocache=12345678"</script>';
	} else {
                $buff = array_pop(explode('/', $row["PhotoURL"]));
                $buffa = explode( $buff,$row["PhotoURL"]);
                //建立備份
                copy('/var/www/html/CarSystem/'.$row["PhotoURL"],'/var/www/html/CarSystem/'.$buffa[0].'picbak.png');
                copy('/var/www/html/CarSystem/'.$buffa[0].'finalpic.png','/var/www/html/CarSystem/'.$row["PhotoURL"]);
	}
}


$ojpg = '/var/www/html/CarSystem/'.str_replace('\\','/',$row["PhotoURL"]); 
$sjpg = '/var/www/html/CarSystem/'.str_replace( 'result','small_result',str_replace('\\','/',$row["PhotoURL"]));
if( !file_exists( $sjpg ) ) {
	//ImageResize($ojpg,$sjpg);
exec("/usr/bin/convert -geometry 165x165 $ojpg $sjpg");

}

$avi = '/var/www/html/CarSystem/'.str_replace('\\','/',$row["VideoURL"]);
$mp4 = '/var/www/html/CarSystem/'.str_replace( 'avi','mp4',str_replace('\\','/',$row["VideoURL"]) );
if( !file_exists( $mp4 ) ) {
        exec("/usr/bin/ffmpeg -i $avi $mp4");
}
?>
	<form class="simple_form edit_case" id="edit_case_199115" autocomplete="off" action="cases_edit.php?id=<?=$row["id"];?>" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="_method" value="put" /><input type="hidden" name="authenticity_token" value="UcPkr/xUVFGzbM/ZV1DoDt1yvvNUz1qYFtalwcoUTXewp4xWHt9bnmFIiGWZ4tG+pLNo8WcEUmqOITYvKcXdDw==" />

  <div class="row">
    <div class="col-lg-12">
      <div class="card mb-3">
        <div class="card-header">
          <b>檢舉案件明細(目前狀態: <?php echo $car_array["state"][$row["state"]];?>)</b>
          <div class="float-right">
	  <a target="_blank" class="btn btn-success" href="card.php?id=<?=$row["id"];?>">列印</a>
	  <a href="cases.php?page=<?=$_SESSION["page"];?>" class="btn btn-primary back2list">回到列表</a>
<?php 
if( $row_user["flag4"] == "1" ) { 
echo '<a href="cases.php?page='.$_SESSION["page"].'&act=del&did='.$row["id"].'" onclick="return confirm(\'確定刪除此筆資料? 刪除後無法恢復!!!\')" class="btn btn-primary" style="background-color:red;border-color:red;">刪除</a>';
} ?>

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
                  <a target="_blank" href="/hsinchu/admin/cases/all?case_id=198896&amp;range=30">0</a>
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
    <td><?php
if( $row["state"] == "ready" ) {
?>
<input class="<?=$cnum_msg;?>" type="text" name="case[car_number]" value="<?=$row["full_car_number"];?>" size="9" onkeyup="this.value = this.value.toUpperCase();"> <?=$cnum_msg2;?>
<?php } else {
echo $row["full_car_number"];
} ?>
</td>
  </tr>
  <tr>
    <td>違規地點</td>
    <td><?php
	if( $row["DetectLocation"] == "固定式測速" )
		echo $row["cam_place"];
	else 
		echo $row["Location"];?></td>
  </tr>
<?php if( $row["DetectLocation"] == "固定式測速" || $row["DetectLocation"] == "巨城" || $row["DetectLocation"] == "慈雲" ) { ?>
  <tr>
      <td>違規事由</td>
          <td><?php
		if( $row["cam_reason"] == "redlight" )
			        echo '闖紅燈';
		else if( $row["cam_reason"] == "turnleft" && $row["DetectLocation"]=="慈雲" )
			        echo '違規迴轉';
		else if( $row["cam_reason"] == "turnleft" && $row["DetectLocation"]=="巨城" )
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
<?php } ?>
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
<div id="nanogallery2_199115" class="ngy2_container nGY2 nanogallery_gallerytheme_dark_nanogallery2_199115" style="overflow: visible;"><div class="nGY2Navigationbar"></div><div class="nanoGalleryLBarOff"><div></div><div></div><div></div><div></div><div></div></div><div class="nGY2Gallery" style="overflow: visible; opacity: 1;"><div class="nGY2GallerySub" style="overflow: visible; touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); transform: none; width: 334px; height: 166px;"><div class="nGY2GThumbnail" style="display: block; top: 0px; left: 0px; width: 166px; height: 166px;"><div class="nGY2GThumbnailSub "><div class="nGY2GThumbnailImage nGY2TnImgBack" style="position: absolute; top: 0px; left: 0px; width: 162px; height: 162px; background-position: center center; background-repeat: no-repeat; background-size: cover; overflow: hidden;"></div><div class="nGY2GThumbnailImage nGY2TnImg" style="opacity: 1; position: absolute; top: 0px; left: 0px; width: 162px; height: 162px; background-image: url(&quot;/CarSystem/<?=str_replace('\\','/',str_replace('result','small_result',$row["PhotoURL"]));?>&quot;); background-position: center center; background-repeat: no-repeat; background-size: cover; overflow: hidden;">  <img class="nGY2GThumbnailImg nGY2TnImg2" src="/CarSystem/<?=str_replace('\\','/',str_replace('result','small_result',$row["PhotoURL"]));?>" alt="" style="opacity:0;" data-idx="1" data-albumidx="0"></div><div class="nGY2GThumbnailCustomLayer"></div>  <div class="nGY2GThumbnailLabel" style="bottom:0; position:absolute;text-align:center;">    <div class="nGY2GThumbnailTitle nGY2GThumbnailImageTitle" style=""></div>    <div class="nGY2GThumbnailDescription" style="display:none;"></div>  </div><div class="nGY2GThumbnailIconsFullThumbnail"></div><div class="thumb_toolbar" style="background-color:#000;opacity: 0.6;width:100%;position:absolute;top:0%;left:0%;padding:10px;"><input disabled="" style="" type="checkbox" name="case_attachment_ids[]" checked="" value="675340" data-ngy2action="select_thumbnail"><a style="float:right" class="ngy2info" data-ngy2action="info" target="_blank" href="/CarSystem/<?=str_replace('\\','/',$row["PhotoURL"]);?>">原檔</a></div></div></div>

<div class="nGY2GThumbnail" style="display: block; top: 0px; left: 168px; width: 166px; height: 166px;"><div class="nGY2GThumbnailSub "><div class="nGY2GThumbnailImage nGY2TnImgBack" style="position: absolute; top: 0px; left: 0px; width: 162px; height: 162px; background-position: center center; background-repeat: no-repeat; background-size: cover; overflow: hidden;"></div><div class="nGY2GThumbnailImage nGY2TnImg" style="opacity: 1; position: absolute; top: 0px; left: 0px; width: 162px; height: 162px; background-image: url(&quot;/hsinchu/assets/video-player-e07c798bcb9faae5aae7ff042828949e27ff115e3bc8091446cd3a4ce8f0db38.png&quot;); background-position: center center; background-repeat: no-repeat; background-size: cover; overflow: hidden;">  <img class="nGY2GThumbnailImg nGY2TnImg2" src="/hsin/assets/video-player-e07c798bcb9faae5aae7ff042828949e27ff115e3bc8091446cd3a4ce8f0db38.png" alt="video" style="opacity:0;" data-idx="2" data-albumidx="0"></div><div class="nGY2GThumbnailCustomLayer"></div>  <div class="nGY2GThumbnailLabel" style="bottom:0; position:absolute;text-align:center;">    <div class="nGY2GThumbnailTitle nGY2GThumbnailImageTitle" style="">video</div>    <div class="nGY2GThumbnailDescription" style="display:none;"></div>  </div><div class="nGY2GThumbnailIconsFullThumbnail"></div><div class="thumb_toolbar" style="background-color:#000;opacity: 0.6;width:100%;position:absolute;top:0%;left:0%;padding:10px;"><a style="float:right" class="ngy2info" data-ngy2action="info" target="_blank" href="/CarSystem/<?=str_replace('\\','/',$row["VideoURL"]);?>">原檔</a></div></div></div>
<div class="nGY2GalleryBottom"></div></div><div class="nGY2ConsoleParent"></div></div>
        </div>
<?php
$jpgnum=0;
$resultnum = $pdo->query("select * from `cases_img` where `cid`='".$_GET["id"]."' and `flag`=0");
while($rownum = $resultnum->fetch(PDO::FETCH_ASSOC)){
$jpgnum++;
}
if( $jpgnum >= 4 ) {
	echo '<a href="jpgmerg.php?cid='.$_GET["id"].'">進入相片合成系統</a>';
}
?>
    </td>
  </tr>
</table>
<br/>
<br/>

<table border="1" align="center" style="width: 100%">
  <tr>
    <td colspan="2" align="center">案件處理</td>
  </tr>

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
<?php if( $row["expose"] == "" || $row["state"] == "ready" ) { ?>
          <input type="hidden" name="case[expose]" value="" /><div class="form-check-inline"><input class="form-check-input radio_buttons optional" type="radio" value="true" checked="checked" name="case[expose]" id="case_expose_true" /><label class="form-check-label collection_radio_buttons" for="case_expose_true">舉發</label></div><div class="form-check-inline"><input class="form-check-input radio_buttons optional" readonly="readonly" type="radio" value="false" name="case[expose]" id="case_expose_false" /><label class="form-check-label collection_radio_buttons" for="case_expose_false">不舉發</label></div>
<?php } else { ?>
<?php echo $row["expose"];?>
<?php } ?>
        </td>
      </tr>
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
<?php if( $row["state"] == "ready" ) { ?>
<select class="form-control <?=$car_msg;?> select optional form-control select2" name="case[car_type_code]" id="case_expose_reason_code">
<option value="">請選擇車種</option>
<option <?php if( $row["car_type_code"] == "汽車" || $_POST["case"]["car_type_code"]== "汽車" ) echo "selected";?>>汽車</option>
<option <?php if( $row["car_type_code"] == "重型機車(含白紅黃牌)" || $_POST["case"]["car_type_code"]== "重型機車(含白紅黃牌)" ) echo "selected";?>>重型機車(含白紅黃牌)</option>
<option <?php if( $row["car_type_code"] == "輕型機車(綠牌)" || $_POST["case"]["car_type_code"]== "輕型機車(綠牌)"  ) echo "selected";?>>輕型機車(綠牌)</option>
<option <?php if( $row["car_type_code"] == "動力機械" || $_POST["case"]["car_type_code"]== "動力機械" ) echo "selected";?>>動力機械</option>
<option <?php if( $row["car_type_code"] == "臨時牌" || $_POST["case"]["car_type_code"]== "臨時牌" ) echo "selected";?>>臨時牌</option>
<option <?php if( $row["car_type_code"] == "試車牌" || $_POST["case"]["car_type_code"]== "試車牌" ) echo "selected";?>>試車牌</option>
<option <?php if( $row["car_type_code"] == "軍車" || $_POST["case"]["car_type_code"]== "軍車" ) echo "selected";?>>軍車</option>
<option <?php if( $row["car_type_code"] == "領" || $_POST["case"]["car_type_code"]== "領" ) echo "selected";?>>領</option>
<option <?php if( $row["car_type_code"] == "外" || $_POST["case"]["car_type_code"]== "外" ) echo "selected";?>>外</option>
<option <?php if( $row["car_type_code"] == "使" || $_POST["case"]["car_type_code"]== "使" ) echo "selected";?>>使</option>
</select>
<?=$car_msg2;?>
<?php } else { ?>
<?=$row["car_type_code"];?>
<?php } ?>
        </td>
      </tr>
      <tr class="reason_form">
        <td>舉發條款</td>
        <td>
          <!--<div class="form-group expose_fields row">
            <div class="col-sm-10 ">-->
<?php if( $row["state"] == "ready" ) { ?>
<select class="form-control <?=$re_msg;?> select optional form-control select2" name="case[reason_code]" id="case_expose_reason_code">
<option value="">請選擇舉發條款</option>
<?php if( $row["DetectLocation"] == "新竹火車站" ) { ?>
<option <?php if( $row["reason_code"] == "5510404 - 併排臨時停車" || $_POST["case"]["reason_code"] == "5510404 - 併排臨時停車" ) echo "selected";?>>5510404 - 併排臨時停車</option>
<option <?php if( $row["reason_code"] == "5610102 - 在禁止臨時停車處所停車" || $_POST["case"]["reason_code"] == "5610102 - 在禁止臨時停車處所停車" ) echo "selected";?>>5610102 - 在禁止臨時停車處所停車</option>
<option <?php if( $row["reason_code"] == "5610402 - 在設有禁止停車標線之處所停車" || $_POST["case"]["reason_code"] == "5610402 - 在設有禁止停車標線之處所停車" ) echo "selected";?>>5610402 - 在設有禁止停車標線之處所停車</option>
<option <?php if( $row["reason_code"] == "5610302 - 在車站出入口停車" || $_POST["case"]["reason_code"] == "5610302 - >在車站出入口停車" ) echo "selected";?>>5610302 - 在車站出入口停車</option>
<option <?php if( $row["reason_code"] == "5610901 - 停車時間不依規定" || $_POST["case"]["reason_code"] == "5610901 - >停車時間不依規定" ) echo "selected";?>>5610901 - 停車時間不依規定</option>
<option <?php if( $row["reason_code"] == "5620002 - 併排停車" || $_POST["case"]["reason_code"] == "5620002 - 併排停車" ) echo "selected";?>>5620002 - 併排停車</option>
<option <?php if( $row["reason_code"] == "6020201 - 不遵守公路機關依處罰條例第五條規定所發布之命令(在臨停上下客區停車)" || $_POST["case"]["reason_code"] == "6020201 - 不遵守公路機關依處罰條例第五條規定所發布之命令(在臨停上下客區停車)" ) echo "selected";?>>6020201 - 不遵守公路機關依處罰條例第五條規定所發布之命令(在臨停上下客區停車)</option>
<?php } else if( $row["DetectLocation"] == "新竹都城隍廟周邊道路" ) { ?>
<option <?php if( $row["reason_code"] == "5510404 - 併排臨時停車" || $_POST["case"]["reason_code"] == "5510404 - 併排>臨時停車" ) echo "selected";?>>5510404 - 併排臨時停車</option>
<option <?php if( $row["reason_code"] == "5610103 - 在公共汽車招呼站十公尺內停車" || $_POST["case"]["reason_code"] == "5610103 - 在公共汽車招呼站十公尺內停車" ) echo "selected";?>>5610103 - 在公共汽車招呼站十公尺內停車</option>
<option <?php if( $row["reason_code"] == "5610904 - 停車車種不依規定" || $_POST["case"]["reason_code"] == "5610904 - 停車車種不依規定" ) echo "selected";?>>5610904 - 停車車種不依規定</option> 
<option <?php if( $row["reason_code"] == "5610102 - 在禁止臨時停車處所停車" || $_POST["case"]["reason_code"] == "5610102 - 在禁止臨時停車處所停車" ) echo "selected";?>>5610102 - 在禁止臨時停車處所停車</option>
<option <?php if( $row["reason_code"] == "5610402 - 在設有禁止停車標線之處所停車" || $_POST["case"]["reason_code"] == "5610402 - 在設有禁止停車標線之處所停車" ) echo "selected";?>>5610402 - 在設有禁止停車標線之處所停車</option>
<option <?php if( $row["reason_code"] == "5610602 - 不緊靠道路右側停車" || $_POST["case"]["reason_code"] == "5610602 - 不緊靠道路右側停車" ) echo "selected";?>>5610602 - 不緊靠道路右側停車</option>
<option <?php if( $row["reason_code"] == "5620002 - 併排停車" || $_POST["case"]["reason_code"] == "5620002 - 併排停車" ) echo "selected";?>>5620002 - 併排停車</option>
<?php } else if( $row["DetectLocation"] == "固定式測速" ) { ?>
<option <?php if( $row["reason_code"] == "4800201 - 轉彎或變換車道不依標誌、標線、號誌指示" || $_POST["case"]["reason_code"] == "4810201 - 轉彎或變換車道不依標誌、標線、號誌指示" ) echo "selected";?>>4810201 - 轉彎或變換車道不依標誌、標線、號誌指示</option>
<option <?php if( $row["reason_code"] == "4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉" || $_POST["case"]["reason_code"] == "4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉" ) echo "selected";?>>4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉</option>
<option <?php if( $row["reason_code"] == "4900201 - 在設有禁止迴車標誌路段迴車" || $_POST["case"]["reason_code"] == "4900201 - 在設有禁止迴車標誌路段迴車" ) echo "selected";?>>4900201 - 在設有禁止迴車標誌路段迴車</option>
<option <?php if( $row["reason_code"] == "4900202 - 在劃有分向限制線路段迴車" || $_POST["case"]["reason_code"] == "4900202 - 在劃有分向限制線路段迴車" ) echo "selected";?>>4900202 - 在劃有分向限制線路段迴車</option>
<option <?php if( $row["reason_code"] == "4900301 - 在禁止左轉路段迴車" || $_POST["case"]["reason_code"] == "4900301 - 在禁止左轉路段迴車" ) echo "selected";?>>4900301 - 在禁止左轉路段迴車</option>
<option <?php if( $row["reason_code"] == "5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈" || $_POST["case"]["reason_code"] == "5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈") echo "selected";?>>5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈</option>
<option <?php if( $row["reason_code"] == "5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為" || $_POST["case"]["reason_code"] == "5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為") echo "selected";?>>5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為</option>
<option <?php if( $row["reason_code"] == "6020302 - 不遵守道路交通標線之指示" || $_POST["case"]["reason_code"] == "6020302 - 不遵守道路交通標線之指示" ) echo "selected";?>>6020302 - 不遵守道路交通標線之指示</option>
<option <?php if( $row["reason_code"] == "6020303 - 不遵守道路交通號誌之指示" || $_POST["case"]["reason_code"] == "6020303 - 不遵守道路交通號誌之指示") echo "selected";?>>6020303 - 不遵守道路交通號誌之指示</option>
<?php } else if( $row["DetectLocation"] == "巨城" ) { ?>
<option <?php if( $row["reason_code"] == "4000005 - 汽車駕駛人行車速度，超過規定之最高時速20公里以內" || $_POST["case"]["reason_code"] == "4000005 - 汽車駕駛人行車速度，超過規定之最高時速20公里以內" ) echo "selected";?>>4000005 - 汽車駕駛人行車速度，超過規定之最高時速20公里以內</option>
<option <?php if( $row["reason_code"] == "4000006 - 汽車駕駛人行車速度，超過規定之最高時速逾20公里至40公里以內" || $_POST["case"]["reason_code"] == "4000006 - 汽車駕駛人行車速度，超過規定之最高時速逾20公里至40公里以內" ) echo "selected";?>>4000006 - 汽車駕駛人行車速度，超過規定之最高時速逾20公里至40公里以內</option>
<option <?php if( $row["reason_code"] == "4000007 - 汽車駕駛人行車速度，超過規定之最高時速逾40公里至60公里以內" || $_POST["case"]["reason_code"] == "4000007 - 汽車駕駛人行車速度，超過規定之最高時速逾40公里至60公里以內" ) echo "selected";?>>4000007 - 汽車駕駛人行車速度，超過規定之最高時速逾40公里至60公里以內</option>
<option <?php if( $row["reason_code"] == "4310210 - 行車速度，超過規定之最高時速逾60公里至80公里以內" || $_POST["case"]["reason_code"] == "4310210 - 行車速度，超過規定之最高時速逾60公里至80公里以內" ) echo "selected";?>>4310210 - 行車速度，超過規定之最高時速逾60公里至80公里以內</option>
<option <?php if( $row["reason_code"] == "4310211 - 行車速度，超過規定之最高時速逾80公里至100公里以內" || $_POST["case"]["reason_code"] == "4310211 - 行車速度，超過規定之最高時速逾80公里至100公里以內" ) echo "selected";?>>4310211 - 行車速度，超過規定之最高時速逾80公里至100公里以內</option>
<option <?php if( $row["reason_code"] == "4310212 - 行車速度，超過規定之最高時速逾100公里" || $_POST["case"]["reason_code"] == "4310212 - 行車速度，超過規定之最高時速逾100公里" ) echo "selected";?>>4310212 - 行車速度，超過規定之最高時速逾100公里</option>
<option <?php if( $row["reason_code"] == "4340044 - 行車速度超過規定之最高時速60公里(處車主)" || $_POST["case"]["reason_code"] == "4340044 - 行車速度超過規定之最高時速60公里(處車主)" ) echo "selected";?>>4340044 - 行車速度超過規定之最高時速60公里(處車主)</option>
<option <?php if( $row["reason_code"] == "4510301 - 不依規定駛入來車道" || $_POST["case"]["reason_code"] == "4510301 - 不依規定駛入來車道" ) echo "selected";?>>4510301 - 不依規定駛入來車道</option>
<option <?php if( $row["reason_code"] == "4800201 - 轉彎或變換車道不依標誌、標線、號誌指示" || $_POST["case"]["reason_code"] == "4810201 - 轉彎或變換車道不依標誌、標線、號誌指示" ) echo "selected";?>>4810201 - 轉彎或變換車道不依標誌、標線、號誌指示</option>
<option <?php if( $row["reason_code"] == "4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉" || $_POST["case"]["reason_code"] == "4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉" ) echo "selected";?>>4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉</option>
<option <?php if( $row["reason_code"] == "4900201 - 在設有禁止迴車標誌路段迴車" || $_POST["case"]["reason_code"] == "4900201 - 在設有禁止迴車標誌路段迴車" ) echo "selected";?>>4900201 - 在設有禁止迴車標誌路段迴車</option>
<option <?php if( $row["reason_code"] == "4900202 - 在劃有分向限制線路段迴車" || $_POST["case"]["reason_code"] == "4900202 - 在劃有分向限制線路段迴車" ) echo "selected";?>>4900202 - 在劃有分向限制線路段迴車</option>
<option <?php if( $row["reason_code"] == "4900301 - 在禁止左轉路段迴車" || $_POST["case"]["reason_code"] == "4900301 - 在禁止左轉路段迴車" ) echo "selected";?>>4900301 - 在禁止左轉路段迴車</option>
<option <?php if( $row["reason_code"] == "5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈" || $_POST["case"]["reason_code"] == "5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈" ) echo "selected";?>>5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈</option>
<option <?php if( $row["reason_code"] == "5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為" || $_POST["case"]["reason_code"] == "5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為" ) echo "selected";?>>5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為</option>
<option <?php if( $row["reason_code"] == "5510404 - 併排臨時停車" || $_POST["case"]["reason_code"] == "5510404 - 併排臨時停車" ) echo "selected";?>>5510404 - 併排臨時停車</option>
<option <?php if( $row["reason_code"] == "5610102 - 在禁止臨時停車處所停車" || $_POST["case"]["reason_code"] == "5610102 - 在禁止臨時停車處所停車" ) echo "selected";?>>5610102 - 在禁止臨時停車處所停車</option>
<option <?php if( $row["reason_code"] == "5610402 - 在設有禁止停車標線之處所停車" || $_POST["case"]["reason_code"] == "5610402 - 在設有禁止停車標線之處所停車" ) echo "selected";?>>5610402 - 在設有禁止停車標線之處所停車</option>
<option <?php if( $row["reason_code"] == "5610601 - 不依順行方向停車" || $_POST["case"]["reason_code"] == "5610601 - 不依順行方向停車" ) echo "selected";?>>5610601 - 不依順行方向停車</option>
<option <?php if( $row["reason_code"] == "5610602 - 不緊靠道路右側停車" || $_POST["case"]["reason_code"] == "5610602 - 不緊靠道路右側停車" ) echo "selected";?>>5610602 - 不緊靠道路右側停車</option>
<option <?php if( $row["reason_code"] == "5610801 - 自用汽車在營業汽車招呼站停車" || $_POST["case"]["reason_code"] == "5610801 - 自用汽車在營業汽車招呼站停車" ) echo "selected";?>>5610801 - 自用汽車在營業汽車招呼站停車</option>
<option <?php if( $row["reason_code"] == "5610901 - 停車時間不依規定" || $_POST["case"]["reason_code"] == "5610901 - 停車時間不依規定" ) echo "selected";?>>5610901 - 停車時間不依規定</option>
<option <?php if( $row["reason_code"] == "5620002 - 併排停車" || $_POST["case"]["reason_code"] == "5620002 - 併排停車" ) echo "selected";?>>5620002 - 併排停車</option>
<option <?php if( $row["reason_code"] == "5800201 - 行至有號誌之交岔路口，遇紅燈不依車道連貫暫停而逕行插入車道間" || $_POST["case"]["reason_code"] == "5800201 - 行至有號誌之交岔路口，遇紅燈不依車道連貫暫停而逕行插入車道間" ) echo "selected";?>>5800201 - 行至有號誌之交岔路口，遇紅燈不依車道連貫暫停而逕行插入車道間</option>
<option <?php if( $row["reason_code"] == "5800301 - 行至有號誌路口前行車道擁塞仍逕行駛入妨礙他車通行" || $_POST["case"]["reason_code"] == "5800301 - 行至有號誌路口前行車道擁塞仍逕行駛入妨礙他車通行" ) echo "selected";?>>5800301 - 行至有號誌路口前行車道擁塞仍逕行駛入妨礙他車通行</option>
<option <?php if( $row["reason_code"] == "6020302 - 不遵守道路交通標線之指示" || $_POST["case"]["reason_code"] == "6020302 - 不遵守道路交通標線之指示" ) echo "selected";?>>6020302 - 不遵守道路交通標線之指示</option>
<option <?php if( $row["reason_code"] == "6020303 - 不遵守道路交通號誌之指示" || $_POST["case"]["reason_code"] == "6020303 - 不遵守道路交通號誌之指示" ) echo "selected";?>>6020303 - 不遵守道路交通號誌之指示</option>
<?php } else if( $row["DetectLocation"] == "慈雲" ) { ?>
<option <?php if( $row["reason_code"] == "4000005 - 汽車駕駛人行車速度，超過規定之最高時速20公里以內" || $_POST["case"]["reason_code"] == "4000005 - 汽車駕駛人行車速度，超過規定之最高時速20公里以內" ) echo "selected";?>>4000005 - 汽車駕
駛人行車速度，超過規定之最高時速20公里以內</option>
<option <?php if( $row["reason_code"] == "4000006 - 汽車駕駛人行車速度，超過規定之最高時速逾20公里至40公里以內" || $_POST["case"]["reason_code"] == "4000006 - 汽車駕駛人行車速度，超過規定之最高時速逾20公里至40公里以內" ) echo "selected";?>>4000006 - 汽車駕駛人行車速度，超過規定之最高時速逾20公里至40公里以內</option>
<option <?php if( $row["reason_code"] == "4000007 - 汽車駕駛人行車速度，超過規定之最高時速逾40公里至60公里以內" || $_POST["case"]["reason_code"] == "4000007 - 汽車駕駛人行車速度，超過規定之最高時速逾40公里至60公里以內" ) echo "selected";?>>4000007 - 汽車駕駛人行車速度，超過規定之最高時速逾40公里至60公里以內</option>
<option <?php if( $row["reason_code"] == "4310210 - 行車速度，超過規定之最高時速逾60公里至80公里以內" || $_POST["case"]["reason_code"] == "4310210 - 行車速度，超過規定之最高時速逾60公里至80公里以內" ) echo "selected";?>>4310210 - 行車速度，超過規定之最高時速逾60公里至80公里以內</option>
<option <?php if( $row["reason_code"] == "4310211 - 行車速度，超過規定之最高時速逾80公里至100公里以內" || $_POST["case"]["reason_code"] == "4310211 - 行車速度，超過規定之最高時速逾80公里至100公里以內" ) echo "selected";?>>4310211 - 行車速度，超過規定之最高時速逾80公里至100公里以內</option>
<option <?php if( $row["reason_code"] == "4310212 - 行車速度，超過規定之最高時速逾100公里" || $_POST["case"]["reason_code"] == "4310212 - 行車速度，超過規定之最高時速逾100公里" ) echo "selected";?>>4310212 - 行車速度，超過規定之最高時速逾100公里</option>
<option <?php if( $row["reason_code"] == "4340044 - 行車速度超過規定之最高時速60公里(處車主)" || $_POST["case"]["reason_code"] == "4340044 - 行車速度超過規定之最高時速60公里(處車主)" ) echo "selected";?>>4340044 - 行車速度超過規定之最高時速60公里(處車主)</option>
<option <?php if( $row["reason_code"] == "4800201 - 轉彎或變換車道不依標誌、標線、號誌指示" || $_POST["case"]["reason_code"] == "4810201 - 轉彎或變換車道不依標誌、標線、號誌指示" ) echo "selected";?>>4810201 - 轉彎或變換車道不依標誌、標線、號誌指示</option>
<option <?php if( $row["reason_code"] == "4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉" || $_POST["case"]["reason_code"] == "4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉" ) echo "selected";?>>4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉</option>
<option <?php if( $row["reason_code"] == "4810701 - 直行車佔用最內側轉彎專用車道" || $_POST["case"]["reason_code"] == "4810701 - 直行車佔用最內側轉彎專用車道" ) echo "selected";?>>4810701 - 直行車佔用最內側轉彎專用車道</option>
<option <?php if( $row["reason_code"] == "4800702 - 直行車佔用最外側轉彎專用車道" || $_POST["case"]["reason_code"] == "4810702 - 直行車佔用最外側轉彎專用車道" ) echo "selected";?>>4810702 - 直行車佔用最外側轉彎專用車道</option>
<option <?php if( $row["reason_code"] == "4800703 - 直行車佔用轉彎專用車道" || $_POST["case"]["reason_code"] == "4810703 - 直行車佔用轉彎專用車道" ) echo "selected";?>>4810703 - 直行車佔用轉彎專用車道</option>
<option <?php if( $row["reason_code"] == "4900201 - 在設有禁止迴車標誌路段迴車" || $_POST["case"]["reason_code"] == "4900201 - 在設有禁止迴車標誌路段迴車" ) echo "selected";?>>4900201 - 在設有禁止迴車標誌路段迴車</option>
<option <?php if( $row["reason_code"] == "4900301 - 在禁止左轉路段迴車" || $_POST["case"]["reason_code"] == "4900301 - 在禁止左轉路段迴車" ) echo "selected";?>>4900301 - 在禁止左轉路段迴車</option>
<option <?php if( $row["reason_code"] == "5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈" || $_POST["case"]["reason_code"] == "5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈") echo "selected";?>>5310001 - 駕車行經有燈光號誌管制之交>岔路口闖紅燈</option>
<option <?php if( $row["reason_code"] == "5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為" || $_POST["case"]["reason_code"] == "5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為") echo "selected";?>>5320001 - 駕車行經有燈光>號誌管制之交岔路口紅燈右轉行為</option>
<option <?php if( $row["reason_code"] == "5800201 - 行至有號誌之交岔路口，遇紅燈不依車道連貫暫停而逕行插入車道間" || $_POST["case"]["reason_code"] == "5800201 - 行至有號誌之交岔路口，遇紅燈不依車道連貫暫停而逕行插入車道間" ) echo "selected";?>>5800201 - 行至有號誌之交岔路口，遇紅燈不依車道連貫暫停而逕行插入車道間</option>
<option <?php if( $row["reason_code"] == "5800301 - 行至有號誌路口前行車道擁塞仍逕行駛入妨礙他車通行" || $_POST["case"]["reason_code"] == "5800301 - 行至有號誌路口前行車道擁塞仍逕行駛入妨礙他車通行" ) echo "selected";?>>5800301 - 行至有號誌路口前行車道擁塞仍逕行駛入妨礙他車通行</option>
<option <?php if( $row["reason_code"] == "6020302 - 不遵守道路交通標線之指示" || $_POST["case"]["reason_code"] == "6020302 - 不遵守道路交通標線之指示" ) echo "selected";?>>6020302 - 不遵守道路交通標線之指示</option>
<option <?php if( $row["reason_code"] == "6020303 - 不遵守道路交通號誌之指示" || $_POST["case"]["reason_code"] == "6020303 - 不遵守道路交通號誌之指示" ) echo "selected";?>>6020303 - 不遵守道路交通號誌之指示</option>
<?php } else if( $row["DetectLocation"] == "西大北大" ) { ?>
<option <?php if( $row["reason_code"] == "4000005 - 汽車駕駛人行車速度，超過規定之最高時速20公里以內" || $_POST["case"]["reason_code"] == "4000005 - 汽車駕駛人行車速度，超過規定之最高時速20公里以內" ) echo "selected";?>>4000005 - 汽車駕駛人行車速度，超過規定之最高時速20公里以內</option>
<option <?php if( $row["reason_code"] == "4000006 - 汽車駕駛人行車速度，超過規定之最高時速逾20公里至40公里以內" || $_POST["case"]["reason_code"] == "4000006 - 汽車駕駛人行車速度，超過規定之最高時速逾20公里至40公里以內" ) echo "selected";?>>4000006 - 汽車駕駛人行車速度，
超過規定之最高時速逾20公里至40公里以內</option>
<option <?php if( $row["reason_code"] == "4000007 - 汽車駕駛人行車速度，超過規定之最高時速逾40公里至60公里以內" || $_POST["case"]["reason_code"] == "4000007 - 汽車駕駛人行車速度，超過規定之最高時速逾40公里至60公里以內" ) echo "selected";?>>4000007 - 汽車駕駛人行車速度，
超過規定之最高時速逾40公里至60公里以內</option>
<option <?php if( $row["reason_code"] == "4310210 - 行車速度，超過規定之最高時速逾60公里至80公里以內" || $_POST["case"]["reason_code"] == "4310210 - 行車速度，超過規定之最高時速逾60公里至80公里以內" ) echo "selected";?>>4310210 - 行車速度，超過規定之最高時速逾60公里至80公里以內</option>
<option <?php if( $row["reason_code"] == "4310211 - 行車速度，超過規定之最高時速逾80公里至100公里以內" || $_POST["case"]["reason_code"] == "4310211 - 行車速度，超過規定之最高時速逾80公里至100公里以內" ) echo "selected";?>>4310211 - 行車速度，超過規定之最高時速逾80公里至
100公里以內</option>
<option <?php if( $row["reason_code"] == "4310212 - 行車速度，超過規定之最高時速逾100公里" || $_POST["case"]["reason_code"] == "4310212 - 行車速度，超過規定之最高時速逾100公里" ) echo "selected";?>>4310212 - 行車速度，超過規定之最高時速逾100公里</option>
<option <?php if( $row["reason_code"] == "4340044 - 行車速度超過規定之最高時速60公里(處車主)" || $_POST["case"]["reason_code"] == "4340044 - 行車速度超過規定之最高時速60公里(處車主)" ) echo "selected";?>>4340044 - 行車速度超過規定之最高時速60公里(處車主)</option>
<option <?php if( $row["reason_code"] == "4420002 - 駕駛汽車行經行人穿越道有行人穿越時，不暫停讓行人先行通過" || $_POST["case"]["reason_code"] == "4420002 - 駕駛汽車行經行人穿越道有行人穿越時，不暫停讓行人先行通過" ) echo "selected";?>>4420002 - 駕駛汽車行經行人穿越道有行人穿越時，不暫停讓行人先行通過</option>
<option <?php if( $row["reason_code"] == "4510301 - 不依規定駛入來車道" || $_POST["case"]["reason_code"] == "4510301 - 不依規定駛入來車
道" ) echo "selected";?>>4510301 - 不依規定駛入來車道</option>
<option <?php if( $row["reason_code"] == "4800201 - 轉彎或變換車道不依標誌、標線、號誌指示" || $_POST["case"]["reason_code"] == "4810201 - 轉彎或變換車道不依標誌、標線、號誌指示" ) echo "selected";?>>4810201 - 轉彎或變換車道不依標誌、標線、號誌指示</option>
<option <?php if( $row["reason_code"] == "4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉" || $_POST["case"]["reason_code"] == "4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉" ) echo "selected";?>>4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉</option>
<option <?php if( $row["reason_code"] == "4820002 - 汽車駕駛人轉彎時，除禁止行人穿越路段外，不暫停讓行人優先通行" || $_POST["case"]["reason_code"] == "4820002 - 汽車駕駛人轉彎時，除禁止行人穿越路段外，不暫停讓行人優先通行" ) echo "selected";?>>4820002 - 汽車駕駛人轉彎時，除禁止行人穿越路段外，不暫停讓行人優先通行</option>
<option <?php if( $row["reason_code"] == "5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈" || $_POST["case"]["reason_code"] == "5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈") echo "selected";?>>5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈</option>
<option <?php if( $row["reason_code"] == "5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為" || $_POST["case"]["reason_code"] == "5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為") echo "selected";?>>5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為</option>
<option <?php if( $row["reason_code"] == "5510404 - 併排臨時停車" || $_POST["case"]["reason_code"] == "5510404 - 併排臨時停車" ) echo "selected";?>>5510404 - 併排臨時停車</option>
<option <?php if( $row["reason_code"] == "5610102 - 在禁止臨時停車處所停車" || $_POST["case"]["reason_code"] == "5610102 - 在禁止臨時停
車處所停車" ) echo "selected";?>>5610102 - 在禁止臨時停車處所停車</option>
<option <?php if( $row["reason_code"] == "5610402 - 在設有禁止停車標線之處所停車" || $_POST["case"]["reason_code"] == "5610402 - 在設有
禁止停車標線之處所停車" ) echo "selected";?>>5610402 - 在設有禁止停車標線之處所停車</option>
<option <?php if( $row["reason_code"] == "5610601 - 不依順行方向停車" || $_POST["case"]["reason_code"] == "5610601 - 不依順行方向停車" ) echo "selected";?>>5610601 - 不依順行方向停車</option>
<option <?php if( $row["reason_code"] == "5610602 - 不緊靠道路右側停車" || $_POST["case"]["reason_code"] == "5610602 - 不緊靠道路右側停
車" ) echo "selected";?>>5610602 - 不緊靠道路右側停車</option>
<option <?php if( $row["reason_code"] == "5610801 - 自用汽車在營業汽車招呼站停車" || $_POST["case"]["reason_code"] == "5610801 - 自用汽
車在營業汽車招呼站停車" ) echo "selected";?>>5610801 - 自用汽車在營業汽車招呼站停車</option>
<option <?php if( $row["reason_code"] == "5610904 - 停車車種不依規定" || $_POST["case"]["reason_code"] == "5610904 - 停車車種不依規定" ) echo "selected";?>>5610904 - 停車車種不依規定</option>
<option <?php if( $row["reason_code"] == "5620002 - 併排停車" || $_POST["case"]["reason_code"] == "5620002 - 併排停車" ) echo "selected";?>>5620002 - 併排停車</option>
<option <?php if( $row["reason_code"] == "6020302 - 不遵守道路交通標線之指示" || $_POST["case"]["reason_code"] == "6020302 - 不遵守道路
交通標線之指示" ) echo "selected";?>>6020302 - 不遵守道路交通標線之指示</option>
<option <?php if( $row["reason_code"] == "6020303 - 不遵守道路交通號誌之指示" || $_POST["case"]["reason_code"] == "6020303 - 不遵守道路
交通號誌之指示") echo "selected";?>>6020303 - 不遵守道路交通號誌之指示</option>
<?php } else if( $row["DetectLocation"] == "經國中正" ) { ?>
<option <?php if( $row["reason_code"] == "4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉" || $_POST["case"]["reason_code"] == "4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉" ) echo "selected";?>>4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉</option>
<option <?php if( $row["reason_code"] == "4810701 - 直行車佔用最內側轉彎專用車道" || $_POST["case"]["reason_code"] == "4810701 - 直行車佔用最內側轉彎專用車道" ) echo "selected";?>>4810701 - 直行車佔用最內側轉彎專用車道</option>
<option <?php if( $row["reason_code"] == "4800702 - 直行車佔用最外側轉彎專用車道" || $_POST["case"]["reason_code"] == "4810702 - 直行車佔用最外側轉彎專用車道" ) echo "selected";?>>4810702 - 直行車佔用最外側轉彎專用車道</option>
<option <?php if( $row["reason_code"] == "4800703 - 直行車佔用轉彎專用車道" || $_POST["case"]["reason_code"] == "4810703 - 直行車佔用轉彎專用車道" ) echo "selected";?>>4810703 - 直行車佔用轉彎專用車道</option>
<option <?php if( $row["reason_code"] == "5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈" || $_POST["case"]["reason_code"] == "5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈") echo "selected";?>>5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈</option>
<option <?php if( $row["reason_code"] == "5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為" || $_POST["case"]["reason_code"] == "5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為") echo "selected";?>>5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為</option>
<option <?php if( $row["reason_code"] == "5510404 - 併排臨時停車" || $_POST["case"]["reason_code"] == "5510404 - 併排臨時停車" ) echo "selected";?>>5510404 - 併排臨時停車</option>
<option <?php if( $row["reason_code"] == "5610102 - 在禁止臨時停車處所停車" || $_POST["case"]["reason_code"] == "5610102 - 在禁止臨時停
車處所停車" ) echo "selected";?>>5610102 - 在禁止臨時停車處所停車</option>
<option <?php if( $row["reason_code"] == "5610402 - 在設有禁止停車標線之處所停車" || $_POST["case"]["reason_code"] == "5610402 - 在設有
禁止停車標線之處所停車" ) echo "selected";?>>5610402 - 在設有禁止停車標線之處所停車</option>
<option <?php if( $row["reason_code"] == "5610601 - 不依順行方向停車" || $_POST["case"]["reason_code"] == "5610601 - 不依順行方向停車" ) echo "selected";?>>5610601 - 不依順行方向停車</option>
<option <?php if( $row["reason_code"] == "5610602 - 不緊靠道路右側停車" || $_POST["case"]["reason_code"] == "5610602 - 不緊靠道路右側停
車" ) echo "selected";?>>5610602 - 不緊靠道路右側停車</option>
<option <?php if( $row["reason_code"] == "5610904 - 停車車種不依規定" || $_POST["case"]["reason_code"] == "5610904 - 停車車種不依規定" ) echo "selected";?>>5610904 - 停車車種不依規定</option>
<option <?php if( $row["reason_code"] == "5620002 - 併排停車" || $_POST["case"]["reason_code"] == "5620002 - 併排停車" ) echo "selected";?>>5620002 - 併排停車</option>
<option <?php if( $row["reason_code"] == "6020302 - 不遵守道路交通標線之指示" || $_POST["case"]["reason_code"] == "6020302 - 不遵守道路
交通標線之指示" ) echo "selected";?>>6020302 - 不遵守道路交通標線之指示</option>
<option <?php if( $row["reason_code"] == "6020303 - 不遵守道路交通號誌之指示" || $_POST["case"]["reason_code"] == "6020303 - 不遵守道路
交通號誌之指示") echo "selected";?>>6020303 - 不遵守道路交通號誌之指示</option>
<?php } else if( $row["DetectLocation"] == "經國公道五"||$row["DetectLocation"] == "光復學府"||$row["DetectLocation"] == "慈雲公道五" ) { ?>
<option <?php if( $row["reason_code"] == "4510301 - 不依規定駛入來車道" || $_POST["case"]["reason_code"] == "4510301 - 不依規定駛入來車道" ) echo "selected";?>>4510301 - 不依規定駛入來車道</option>
<option <?php if( $row["reason_code"] == "4800201 - 轉彎或變換車道不依標誌、標線、號誌指示" || $_POST["case"]["reason_code"] == "4810201 - 轉彎或變換車道不依標誌、標線、號誌指示" ) echo "selected";?>>4810201 - 轉彎或變換車道不依標誌、標線、號誌指示</option>
<option <?php if( $row["reason_code"] == "4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉" || $_POST["case"]["reason_code"] == "4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉" ) echo "selected";?>>4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉</option>
<option <?php if( $row["reason_code"] == "4810701 - 直行車佔用最內側轉彎專用車道" || $_POST["case"]["reason_code"] == "4810701 - 直行車佔用最內側轉彎專用車道" ) echo "selected";?>>4810701 - 直行車佔用最內側轉彎專用車道</option>
<option <?php if( $row["reason_code"] == "4800702 - 直行車佔用最外側轉彎專用車道" || $_POST["case"]["reason_code"] == "4810702 - 直行車佔用最外側轉彎專用車道" ) echo "selected";?>>4810702 - 直行車佔用最外側轉彎專用車道</option>
<option <?php if( $row["reason_code"] == "4800703 - 直行車佔用轉彎專用車道" || $_POST["case"]["reason_code"] == "4810703 - 直行車佔用轉彎專用車道" ) echo "selected";?>>4810703 - 直行車佔用轉彎專用車道</option>
<option <?php if( $row["reason_code"] == "5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈" || $_POST["case"]["reason_code"] == "5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈") echo "selected";?>>5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈</option>
<option <?php if( $row["reason_code"] == "5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為" || $_POST["case"]["reason_code"] == "5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為") echo "selected";?>>5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為</option>
<option <?php if( $row["reason_code"] == "6020302 - 不遵守道路交通標線之指示" || $_POST["case"]["reason_code"] == "6020302 - 不遵守道路
交通標線之指示" ) echo "selected";?>>6020302 - 不遵守道路交通標線之指示</option>
<option <?php if( $row["reason_code"] == "6020303 - 不遵守道路交通號誌之指示" || $_POST["case"]["reason_code"] == "6020303 - 不遵守道路
交通號誌之指示") echo "selected";?>>6020303 - 不遵守道路交通號誌之指示</option>
<?php } else { ?>
<option <?php if( $row["reason_code"] == "4510301 - 不依規定駛入來車道" || $_POST["case"]["reason_code"] == "4510301 - 不依規定駛入來車道" ) echo "selected";?>>4510301 - 不依規定駛入來車道</option>
<option <?php if( $row["reason_code"] == "4800201 - 轉彎或變換車道不依標誌、標線、號誌指示" || $_POST["case"]["reason_code"] == "4810201 - 轉彎或變換車道不依標誌、標線、號誌指示" ) echo "selected";?>>4810201 - 轉彎或變換車道不依標誌、標線、號誌指示</option>
<option <?php if( $row["reason_code"] == "4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉" || $_POST["case"]["reason_code"] == "4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉" ) echo "selected";?>>4810301 - 行經交岔路口未達中心處，佔用來車道搶先左轉</option>
<option <?php if( $row["reason_code"] == "4800402 - 在多車道左轉彎，不先駛入內側車道" || $_POST["case"]["reason_code"] == "4810402 - 在多車道左轉彎，不先駛入內側車道" ) echo "selected";?>>4810402 - 在多車道左轉彎，不先駛入內側車道</option>
<option <?php if( $row["reason_code"] == "5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈" || $_POST["case"]["reason_code"] == "5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈" ) echo "selected";?>>5310001 - 駕車行經有燈光號誌管制之交岔路口闖紅燈</option>
<option <?php if( $row["reason_code"] == "5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為" || $_POST["case"]["reason_code"] == "5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為" ) echo "selected";?>>5320001 - 駕車行經有燈光號誌管制之交岔路口紅燈右轉行為</option>
<option <?php if( $row["reason_code"] == "5510404 - 併排臨時停車" || $_POST["case"]["reason_code"] == "5510404 - 併排臨時停車" ) echo "selected";?>>5510404 - 併排臨時停車</option>
<option <?php if( $row["reason_code"] == "5610102 - 在禁止臨時停車處所停車" || $_POST["case"]["reason_code"] == "5610102 - 在禁止臨時停車處所停車" ) echo "selected";?>>5610102 - 在禁止臨時停車處所停車</option>
<option <?php if( $row["reason_code"] == "5610402 - 在設有禁止停車標線之處所停車" || $_POST["case"]["reason_code"] == "5610402 - 在設有禁止停車標線之處所停車" ) echo "selected";?>>5610402 - 在設有禁止停車標線之處所停車</option>
<option <?php if( $row["reason_code"] == "5610601 - 不依順行方向停車" || $_POST["case"]["reason_code"] == "5610601 - 不依順行方向停車" ) echo "selected";?>>5610601 - 不依順行方向停車</option>
<option <?php if( $row["reason_code"] == "5610602 - 不緊靠道路右側停車" || $_POST["case"]["reason_code"] == "5610602 - 不緊靠道路右側停車" ) echo "selected";?>>5610602 - 不緊靠道路右側停車</option>
<option <?php if( $row["reason_code"] == "5610904 - 停車車種不依規定" || $_POST["case"]["reason_code"] == "5610904 - 停車車種不依規定" ) echo "selected";?>>5610904 - 停車車種不依規定</option>
<option <?php if( $row["reason_code"] == "5620002 - 併排停車" || $_POST["case"]["reason_code"] == "5620002 - 併排停車" ) echo "selected";?>>5620002 - 併排停車</option>
<option <?php if( $row["reason_code"] == "6020302 - 不遵守道路交通標線之指示" || $_POST["case"]["reason_code"] == "6020302 - 不遵守道路交通標線之指示" ) echo "selected";?>>6020302 - 不遵守道路交通標線之指示</option>
<option <?php if( $row["reason_code"] == "6020303 - 不遵守道路交通號誌之指示" || $_POST["case"]["reason_code"] == "6020303 - 不遵守道路交通號誌之指示") echo "selected";?>>6020303 - 不遵守道路交通號誌之指示</option>
<?php } ?>
</select>
<?=$re_msg2;?>
<?php } else { ?>
<?=$row["reason_code"];?>
<?php } ?>
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
	    <input class="form-control string optional form-control " type="text" name="case[expose_msg]" id="case_unexpose_reason_note" value="<?=$row["expose_msg"];?>"/>
            </div>
          </div>
</td></tr>





<?php
	if( $row["DetectLocation"] == "固定式測速" ) {
?>

  <tr>
    <td>限速 / 測速 <font color="red" size="2">(*超速案件必填)</font></td>
    <td>
<div class="col-sm-10">

        <div class="row" style="margin-top:0px;">
          <div class="col-xs" style="padding-right:0px;float:left; display:inline;">
        （限速&nbsp; <input class="form-control string required form-control" style="text-transform:uppercase;width:54px;display:initial;" placeholder="" type="text" name="doc[car_max]" id="doc_first_car_number" value="<?php if( $car_max != "" ) echo $car_max;?>" /> 公里 ,</div>
          <div class="col-xs" style="padding-left:0px;">實際測速 <input class="form-control string required form-control" style="text-transform:uppercase;width:54px;display:initial;" placeholder="" type="text" name="doc[car_real]" id="doc_last_car_number" value="<?php if( $car_real != "" ) echo $car_real;?>" /> 公里 )
          </div>
        </div>


</div>
    </td>
  </tr>
<?php } else { ?>
<tr>
    <td>結案附加訊息</td>
    <td>
    <span data-bip-type="input" data-bip-attribute="comment" data-bip-placeholder="<?php
		if( $row["comment"] != "" )
			echo $row["comment"];
		else
			echo '點選新增備註';?>" data-bip-object="case" data-bip-ok-button="儲存" data-bip-cancel-button="取消" data-bip-skip-blur="false" data-bip-url="cases_edit.php?id=<?=$_GET["id"];?>" class="best_in_place" id="best_in_place_case_198896_comment"></span>
    </td>
  </tr>
<?php } ?>

  <tr>
    <td>舉發人員</td>
    <td>
    <?php if( $row["review_user_id"] == "" ) echo $row["review_user_id"]?$row["review_user_id"]:$row_user["name"]; else echo $row_user["review_user_id"];?> ( <?=$row_user["department"].' '.$row_user["unit"];?> )
    </td>
  </tr>

</table>

            </div>
          </div>
        </div>
        <div class="card-footer small text-muted">
          <div class="row">
              <div class="form-actions">
<?php if( $row["state"] == "ready" ) { ?>
       <input type="hidden" name="state" value="reviewing">
	<input type="hidden" name="id" value="<?=$row["id"];?>">
       <input type="submit" name="commit" value="案件審核" class="btn btn btn-primary" data-disable-with="案件審核" />
<?php } else if( $row["state"] == "reviewing" ) { ?>
<input type="hidden" name="state" value="complete">
<input type="hidden" name="id" value="<?=$row["id"];?>">
<input type="submit" name="commit" value="案件結束" class="btn btn btn-primary" data-disable-with="案件結束" />
<a class="btn btn-danger  float-right" rel="nofollow" data-method="post" href="cases_edit.php?id=<?=$row["id"];?>&act=fback&state=ready">案件退回</a>
<?php } else { ?>

<?php } ?>
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



	<script src="/hsinchu/assets/admin-7e641842b7678866dba9f029b1984fc78978fbe3c300f84802bb98e82b1f6905.js?<?=date("YmdHis");?>"></script>
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

    //ckbox = '<input disabled style="'+st1+'" type="checkbox" name="case_attachment_ids[]" ' + checked + ' value="' + item_id + '" data-ngy2action="select_thumbnail">'
    if(source_url.match('_sc_')==null){
    ckbox = '<input disabled style="'+st1+'" type="checkbox" name="case_attachment_ids[]" value="' + item_id + '" data-ngy2action="select_thumbnail">'
    } 

  if(source_url != undefined){
	  if(source_url.match('_sc_')!=null){
		  var pid = item_id.toString().replace('<?=$_GET["id"];?>', '')
		  source_link = '<a style="'+st+'" class="ngy2info" data-ngy2action="info" target="_blank" href="cases_edit.php?id=<?=$_GET["id"];?>&act=chjpga&pid='+pid+'" onclick="return confirm(\'確定取代主圖片? 取代後無法恢復!!!\')">取代主圖片</a><br><a style="'+st+'" class="ngy2info" data-ngy2action="info" target="_blank" href="cases_edit.php?id=<?=$_GET["id"];?>&act=chjpgb&pid='+pid+'" onclick="return confirm(\'確定取代副圖片? 取代後無法恢復!!!\')">取代副圖片</a><br><a style="'+st+'" class="ngy2info" data-ngy2action="info" href="cases_edit.php?id=<?=$_GET["id"];?>&act=delop&pid='+pid+'" onclick="return confirm(\'確定刪除此張圖片? 刪除後無法恢復!!!\')"><font color="red">刪除</font></a>'
	  } else {
                  var picqq = ''
                  if( item.customData.id.toString() == "675340")
                        picqq="主圖片"
                  if( item.customData.id.toString() == "675342")
                        picqq="副圖片"
		source_link = '<a style="'+st+'" class="ngy2info" data-ngy2action="info" target="_blank" href="' + source_url + '">'+picqq+'</a>'
	  }

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
  if( customElementName == "custom2" ) {
	  if(item.customData.origin_url.match('_sc_')!=null){
		  location.href='zoom2.php?cases_id=<?=$_GET["id"];?>&id='+item.customData.id.toString().replace('<?=$_GET["id"];?>', '');
	  } else {
	  	location.href='zoom.php?id=<?=$_GET["id"];?>';
	  }
 }
  if(item.mediaKind == 'video' && customElementName == "custom1" ){
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
    var file_name = date.format("YYYYMMDDhhmmss") + "_sc_" + ID; //2014-07-10
    var cases_id = '<?=$_GET["id"];?>';
    var albumID = '0';
    var newItem = NGY2Item.New(instance, file_name, '', ID, albumID, 'image', '' );
    $.post("/hsinchu/cases_edit.php", {format: 'json', file_name: file_name + ".png", base64_attachment: d,cases_id}, function(data){
      var data = JSON.parse(data);
      var image_url = data.optimized_file_url
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
      $("#nanogallery2_199115").nanogallery2('closeViewe');
      location.href = 'cases_edit.php?id=<?=$_GET["id"];?>';
    })
  }else if( customElementName != "custom2" ) {
    toastr.error("只能在影片中截圖");
  }
}
$("#nanogallery2_199115").nanogallery2({
items: [{"src":"/CarSystem/<?php if( file_exists('/var/www/html/CarSystem/'.str_replace('\\','/',$row["PhotoURL"]))) {
echo str_replace('\\','/',$row["PhotoURL"]); } else {
echo str_replace('\\','/',$row["pic1"]);
}?>","srct":"/CarSystem/<?php if( file_exists('/var/www/html/CarSystem/'.str_replace('\\','/',$row["PhotoURL"]))) {
echo str_replace('\\','/',str_replace('result','small_result',$row["PhotoURL"])); } else {
echo str_replace('\\','/',$row["pic1"]);
}?>","title":"","customData":{"id":675340,"selected":true,"origin_url":"/CarSystem/<?php
	if( file_exists('/var/www/html/CarSystem/'.str_replace('\\','/',$row["PhotoURL"]))) {
		echo str_replace('\\','/',$row["PhotoURL"]); } else { echo str_replace('\\','/',$row["pic1"]);}?>"}}
<?php if( !file_exists('/var/www/html/CarSystem/'.str_replace('\\','/',$row["PhotoURL"])) && $row["pic2"] != null ) { ?>
,{"src":"/CarSystem/<?=str_replace('\\','/',$row["pic2"]);?>","srct":"/CarSystem/<?=str_replace('\\','/',$row["pic2"]);?>","title":"","customData":{"id":675342,"selected":true,"origin_url":"/CarSystem/<?=str_replace('\\','/',$row["pic2"]);?>"}}
<?php }?>
<?php
$resultpic = $pdo->query("select * from `cases_img` where `cid`='".$_GET["id"]."' order by `ts` asc");
while($rowpic = $resultpic->fetch(PDO::FETCH_ASSOC)){
?>
,{"src":"/CarSystem/<?=str_replace('\\','/',$rowpic["img"]);?>","srct":"/CarSystem/<?=str_replace('\\','/',$rowpic["img"]);?>","title":"","customData":{"id":<?php echo $_GET["id"].$rowpic["id"];?>,"selected":true,"origin_url":"/CarSystem/<?=str_replace('\\','/',$rowpic["img"]);?>"}}
<?php } ?>
<?php if( $row["VideoURL"] != "" ) { ?>,{"src":"/CarSystem/<?=str_replace('avi','mp4',str_replace('\\','/',$row["VideoURL"]));?>","srct":"/hsinchu/assets/video-player-e07c798bcb9faae5aae7ff042828949e27ff115e3bc8091446cd3a4ce8f0db38.png","thumbnailOpenImage":false,"title":"video","customData":{"id":675341,"selected":false,"origin_url":"http://220.130.189.235/CarSystem/<?=str_replace('\\','/',$row["VideoURL"]);?>"}}<?php } ?>],

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
    topRight:  'custom1, custom2, rotateLeft, rotateRight, closeButton' },
  icons: { viewerCustomTool1:'<i class="fas fa-camera"></i>', viewerCustomTool2:'<i class="fas fa-expand" title="車牌放大截圖"></i>'},
  fnImgToolbarCustClick: snapshot,
  // fnThumbnailHover: hover_thumbnail,
  // fnThumbnailHoverOut: hoverout_thumbnail
});

      })
    </script>
  <script type="text/javascript">
    function assign_case(d, case_id){
      var department_id = $(d).val();
      $.post("/hsinchu/admin/cases/assign", {case_ids: case_id, department_id: department_id})
    };

    $(function(){
	    $('.back2list').attr('href', "cases.php?page=<?=$_SESSION["page"];?>")
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
	   // location.reload(true);
  </script>

  </body>



</html>

