<?php
include "function.php";
$nav="";
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
		        $pdo->prepare("UPDATE `cases` set `expose`=?,`car_type_code`=?,`reason_code`=?,`review_user_id`=?,`state`=? where `id`=?")->execute(array('舉發',$_POST["case"]["car_type_code"],$_POST["case"]["reason_code"],$row_user["name"],$_POST["state"],intval($_POST["id"])));
		$msg = '從案件處理 > 案件審核('.$row_user["name"].')';
		$pdo->prepare("INSERT INTO `case_history` (`id`,`cases_id`,`user_id`,`message`,`cdate`) VALUES ( NULL, ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
		echo '<script>document.location.href="cases.php?state=ready&n";</script>';
	} else if( $_POST["state"] == "complete" ) {
		$msg = "從案件審核 > 案件結束(".$row_user["name"].")";
		$pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
		echo '<script>document.location.href="cases.php?state=reviewing&n";</script>';	
	} else {
		echo '<script>document.location.href="cases.php?n";</script>';
	}
	exit;
	}
	} else {
        $cdate = date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array($_POST["state"],intval($_POST["id"])));
        if( $_POST["state"] == "reviewing" ) {
                        $pdo->prepare("UPDATE `cases` set `expose`=?,`expose_msg`=?,`review_user_id`=?,`state`=? where `id`=?")->execute(array('不舉發',$_POST["case"]["expose_msg"],$row_user["name"],$_POST["state"],intval($_POST["id"])));
                $msg = '從案件處理 > 案件審核('.$row_user["name"].')';
                $pdo->prepare("INSERT INTO `case_history` (`id`,`cases_id`,`user_id`,`message`,`cdate`) VALUES ( NULL, ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
                echo '<script>document.location.href="cases.php?state=ready&n";</script>';
        } else if( $_POST["state"] == "complete" ) {
                $msg = "從案件審核 > 案件結束(".$row_user["name"].")";
                $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
                echo '<script>document.location.href="cases.php?state=reviewing&n";</script>';
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
echo '<script>document.location.href="cases.php?state=reviewing&n";</script>';
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
  <a href="javascript:history.go(-1);" class="btn btn-primary back2list">回到列表</a>

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
    <td><?=$row["full_car_number"];?></td>
  </tr>

  <tr>
    <td>違規地點</td>
    <td><?=$row["Location"];?></td>
  </tr>

  <tr>
    <td>舉證/舉發資料</td>
    <td>
<div id="thumbnails_199115">
<div id="nanogallery2_199115" class="ngy2_container nGY2 nanogallery_gallerytheme_dark_nanogallery2_199115" style="overflow: visible;"><div class="nGY2Navigationbar"></div><div class="nanoGalleryLBarOff"><div></div><div></div><div></div><div></div><div></div></div><div class="nGY2Gallery" style="overflow: visible; opacity: 1;"><div class="nGY2GallerySub" style="overflow: visible; touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); transform: none; width: 334px; height: 166px;"><div class="nGY2GThumbnail" style="display: block; top: 0px; left: 0px; width: 166px; height: 166px;"><div class="nGY2GThumbnailSub "><div class="nGY2GThumbnailImage nGY2TnImgBack" style="position: absolute; top: 0px; left: 0px; width: 162px; height: 162px; background-position: center center; background-repeat: no-repeat; background-size: cover; overflow: hidden;"></div><div class="nGY2GThumbnailImage nGY2TnImg" style="opacity: 1; position: absolute; top: 0px; left: 0px; width: 162px; height: 162px; background-image: url(&quot;/CarSystem/<?=str_replace('\\','/',str_replace('result','small_result',$row["PhotoURL"]));?>&quot;); background-position: center center; background-repeat: no-repeat; background-size: cover; overflow: hidden;">  <img class="nGY2GThumbnailImg nGY2TnImg2" src="/CarSystem/<?=str_replace('\\','/',str_replace('result','small_result',$row["PhotoURL"]));?>" alt="" style="opacity:0;" data-idx="1" data-albumidx="0"></div><div class="nGY2GThumbnailCustomLayer"></div>  <div class="nGY2GThumbnailLabel" style="bottom:0; position:absolute;text-align:center;">    <div class="nGY2GThumbnailTitle nGY2GThumbnailImageTitle" style=""></div>    <div class="nGY2GThumbnailDescription" style="display:none;"></div>  </div><div class="nGY2GThumbnailIconsFullThumbnail"></div><div class="thumb_toolbar" style="background-color:#000;opacity: 0.6;width:100%;position:absolute;top:0%;left:0%;padding:10px;"><input disabled="" style="" type="checkbox" name="case_attachment_ids[]" checked="" value="675340" data-ngy2action="select_thumbnail"><a style="float:right" class="ngy2info" data-ngy2action="info" target="_blank" href="/CarSystem/<?=str_replace('\\','/',$row["PhotoURL"]);?>">原檔</a></div></div></div>

<div class="nGY2GThumbnail" style="display: block; top: 0px; left: 168px; width: 166px; height: 166px;"><div class="nGY2GThumbnailSub "><div class="nGY2GThumbnailImage nGY2TnImgBack" style="position: absolute; top: 0px; left: 0px; width: 162px; height: 162px; background-position: center center; background-repeat: no-repeat; background-size: cover; overflow: hidden;"></div><div class="nGY2GThumbnailImage nGY2TnImg" style="opacity: 1; position: absolute; top: 0px; left: 0px; width: 162px; height: 162px; background-image: url(&quot;/hsinchu/assets/video-player-e07c798bcb9faae5aae7ff042828949e27ff115e3bc8091446cd3a4ce8f0db38.png&quot;); background-position: center center; background-repeat: no-repeat; background-size: cover; overflow: hidden;">  <img class="nGY2GThumbnailImg nGY2TnImg2" src="/hsin/assets/video-player-e07c798bcb9faae5aae7ff042828949e27ff115e3bc8091446cd3a4ce8f0db38.png" alt="video" style="opacity:0;" data-idx="2" data-albumidx="0"></div><div class="nGY2GThumbnailCustomLayer"></div>  <div class="nGY2GThumbnailLabel" style="bottom:0; position:absolute;text-align:center;">    <div class="nGY2GThumbnailTitle nGY2GThumbnailImageTitle" style="">video</div>    <div class="nGY2GThumbnailDescription" style="display:none;"></div>  </div><div class="nGY2GThumbnailIconsFullThumbnail"></div><div class="thumb_toolbar" style="background-color:#000;opacity: 0.6;width:100%;position:absolute;top:0%;left:0%;padding:10px;"><a style="float:right" class="ngy2info" data-ngy2action="info" target="_blank" href="/CarSystem/<?=str_replace('\\','/',$row["VideoURL"]);?>">原檔</a></div></div></div>
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
      <tr class="car_form">
        <td>車種</td>
        <td>
<?php if( $row["state"] == "ready" ) { ?>
<select class="form-control <?=$car_msg;?> select optional form-control select2" name="case[car_type_code]" id="case_expose_reason_code">
<option value="">請選擇車種</option>
<option <?php if( $row["car_type_code"] == "汽車" || $_POST["case"]["car_type_code"]== "汽車" ) echo "selected";?>>汽車</option>
<option <?php if( $row["car_type_code"] == "重型機車(含白紅黃牌)" || $_POST["case"]["car_type_code"]== "重型機車(含白紅黃牌)" ) echo "selected";?>>重型機車(含白紅黃牌)</option>
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
<option <?php if( $row["reason_code"] == "5510404 - 併排臨時停車" || $_POST["case"]["reason_code"] == "5510404 - 併排臨時停車" ) echo "selected";?>>5510404 - 併排臨時停車</option>
<option <?php if( $row["reason_code"] == "5610102 - 在禁止臨時停車處所停車" || $_POST["case"]["reason_code"] == "5610102 - 在禁止臨時停車處所停車" ) echo "selected";?>>5610102 - 在禁止臨時停車處所停車</option>
<option <?php if( $row["reason_code"] == "5610402 - 在設有禁止停車標線之處所停車" || $_POST["case"]["reason_code"] == "5610402 - 在設有禁止停車標線之處所停車" ) echo "selected";?>>5610402 - 在設有禁止停車標線之處所停車</option>
<option <?php if( $row["reason_code"] == "5620002 - 併排停車（104年7月1日以後適用）" || $_POST["case"]["reason_code"] == "5620002 - 併排停車（104年7月1日以後適用）" ) echo "selected";?>>5620002 - 併排停車（104年7月1日以後適用）</option>
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





  <tr>
    <td>結案附加訊息</td>
    <td>
        <span data-bip-type="input" data-bip-attribute="comment" data-bip-placeholder="點選新增備註" data-bip-object="case" data-bip-ok-button="儲存" data-bip-cancel-button="取消" data-bip-skip-blur="false" data-bip-url="/hsinchu/admin/cases/198896" class="best_in_place" id="best_in_place_case_198896_comment"></span>
    </td>
  </tr>

  <tr>
    <td>舉發人員</td>
    <td>
    <?php if( $row["review_user_id"] == "" ) echo $row_user["name"]; else echo $row["review_user_id"];?> ( <?=$row_user["department"];?> )
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



    <script src="/hsinchu/assets/admin-7e641842b7678866dba9f029b1984fc78978fbe3c300f84802bb98e82b1f6905.js"></script>
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
    $.post("cases_edit.php", {format: 'json', file_name: file_name + ".png", base64_attachment: d}, function(data){
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
    })
  }else{
    toastr.error("只能在影片中截圖");
  }
}
$("#nanogallery2_199115").nanogallery2({
  items: [{"src":"/CarSystem/<?=str_replace('\\','/',$row["PhotoURL"]);?>","srct":"/CarSystem/<?=str_replace('\\','/',str_replace('result','small_result',$row["PhotoURL"]));?>","title":"","customData":{"id":675340,"selected":true,"origin_url":"/CarSystem/<?=str_replace('\\','/',$row["PhotoURL"]);?>"}},{"src":"/CarSystem/<?=str_replace('avi','mp4',str_replace('\\','/',$row["VideoURL"]));?>","srct":"/hsinchu/assets/video-player-e07c798bcb9faae5aae7ff042828949e27ff115e3bc8091446cd3a4ce8f0db38.png","thumbnailOpenImage":false,"title":"video","customData":{"id":675341,"selected":false,"origin_url":"/CarSystem/<?=str_replace('\\','/',$row["VideoURL"]);?>"}}],
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
  icons: { viewerCustomTool1:''},
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
      $('.back2list').attr('href', "cases.php")
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

