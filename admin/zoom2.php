<?php include "function.php"; 
if( $_SESSION["reload"] == "1" ) {
	$_SESSION["reload"]="0";
echo '<script>location.reload(true);</script>';
exit;
}
?>
<script>
function doCookieSetup(name, value) {
  var expires = new Date();
  //有效時間保存 2 天 2*24*60*60*1000
  expires.setTime(expires.getTime() + 172800000);
  document.cookie = name + "=" + escape(value) + ";expires=" + expires.toGMTString()
}


function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function getCookieValueByIndex(startIndex) {
  var endIndex = document.cookie.indexOf(";", startIndex);
  if (endIndex == -1) endIndex = document.cookie.length;
  return unescape(document.cookie.substring(startIndex, endIndex));
}

function delCookie(name) {
  var exp = new Date();
  exp.setTime(exp.getTime() - 1);
  var cval = getCookie(name);
  document.cookie = escape(name) + "=" + cval + "; expires=" + exp.toGMTString();
}
if( getCookie('reload')=='1' ) { 
	alert('偵測到圖片暫存,自動重新整理!');
	setTimeout(function(){ 
	delCookie('reload');
	location.reload(true);
	}, 3000);

}
</script>
<?php
$result = $pdo->query("select * from `cases_img` where `id`='".$_GET["id"]."'");
$row = $result->fetch(PDO::FETCH_ASSOC);
if( $_GET["act"] == "reback" ) {
	echo "<script>doCookieSetup('reload','1');</script>";
	$_SESSION["reload"]="1";
        if( $row["img"] != "" ) {
                $buff = array_pop(explode('/', $row["img"]));
                $buffa = explode( $buff,$row["img"]);
                if(file_exists('/var/www/html/CarSystem/'.$buffa[0].'picbak'.$row["id"].'.png')){
		unlink( '/var/www/html/CarSystem/'.$row["img"] );
                copy('/var/www/html/CarSystem/'.$buffa[0].'picbak'.$row["id"].'.png','/var/www/html/CarSystem/'.$row["img"]);
		unlink( '/var/www/html/CarSystem/'.$buffa[0].'picbak'.$row["id"].'.png' );
                }
        } else {
                $buff = array_pop(explode('/', $row["PhotoURL"]));
                $buffa = explode( $buff,$row["PhotoURL"]);
		if(file_exists('/var/www/html/CarSystem/'.$buffa[0].'picbak.png')){
		unlink( '/var/www/html/CarSystem/'.$row["PhotoURL"]);
		copy('/var/www/html/CarSystem/'.$buffa[0].'picbak.png','/var/www/html/CarSystem/'.$row["PhotoURL"]);
		unlink( '/var/www/html/CarSystem/'.$buffa[0].'picbak.png' );
		}
        }
echo '<script>window.location.assign(\'zoom2.php?cases_id='.$_GET["cases_id"].'&id='.$_GET["id"].'&nocache='.date("YmdHis").'\');</script>';
exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<META HTTP-EQUIV="pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
<META HTTP-EQUIV="expires" CONTENT="0">
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="A simple jQuery image cropping plugin.">
  <meta name="author" content="Chen Fengyuan">
<meta http-equiv="cache-control" content="no-cache" />
 <meta http-equiv="expires" content="0" /> 
  <link rel="shortcut icon" href="favicon.ico">
  <link rel="icon" href="favicon.ico">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/cropper.css">
  <link rel="stylesheet" href="assets/main.css">
</head>
<body>
  <!--[if lt IE 9]>
  <div class="alert alert-warning alert-dismissible fade show m-0 rounded-0" role="alert">
    You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <![endif]-->

  <!-- Header -->
  <!-- Jumbotron -->

  <!-- Content -->
  <div class="container">
    <div class="row">
<?php
if( $_GET["act"] != "ok" ) {
?>
      <div class="col-md-9">
        <!-- <h3>Demo:</h3> -->
        <div class="img-container">
	<img id="image" src="/CarSystem/<?php
	if( $row["img"] != "" ) {
		echo  $row["img"];
	} else {
		echo $row["PhotoURL"];
	}
?>
" alt="Picture">
        </div>
      </div>
      <div class="col-md-3">
        <!-- <h3>Preview:</h3> -->
        <div class="docs-preview clearfix">
<font color="red"><b>請調整左邊大圖,虛線框框大小,並且移動到要放大的車牌位置,預覽將被合成的圖片,如果照片沒問題,按下確定合成圖片!</font>
          <div class="img-preview preview-lg"></div>
        </div>
<?php } ?>

        <!-- <h3>Data:</h3> -->
        <div class="docs-data" style="display:none;">
          <div class="input-group input-group-sm">
            <span class="input-group-prepend">
              <label class="input-group-text" for="dataX">X</label>
            </span>
            <input type="text" class="form-control" id="dataX" placeholder="x">
            <span class="input-group-append">
              <span class="input-group-text">px</span>
            </span>
          </div>
          <div class="input-group input-group-sm">
            <span class="input-group-prepend">
              <label class="input-group-text" for="dataY">Y</label>
            </span>
            <input type="text" class="form-control" id="dataY" placeholder="y">
            <span class="input-group-append">
              <span class="input-group-text">px</span>
            </span>
          </div>
          <div class="input-group input-group-sm">
            <span class="input-group-prepend">
              <label class="input-group-text" for="dataWidth">Width</label>
            </span>
            <input type="text" class="form-control" id="dataWidth" placeholder="width" value="100">
            <span class="input-group-append">
              <span class="input-group-text">px</span>
            </span>
          </div>
          <div class="input-group input-group-sm">
            <span class="input-group-prepend">
              <label class="input-group-text" for="dataHeight">Height</label>
            </span>
            <input type="text" class="form-control" id="dataHeight" placeholder="height">
            <span class="input-group-append">
              <span class="input-group-text">px</span>
            </span>
          </div>
          <div class="input-group input-group-sm">
            <span class="input-group-prepend">
              <label class="input-group-text" for="dataRotate">Rotate</label>
            </span>
            <input type="text" class="form-control" id="dataRotate" placeholder="rotate">
            <span class="input-group-append">
              <span class="input-group-text">deg</span>
            </span>
          </div>
          <div class="input-group input-group-sm">
            <span class="input-group-prepend">
              <label class="input-group-text" for="dataScaleX">ScaleX</label>
            </span>
            <input type="text" class="form-control" id="dataScaleX" placeholder="scaleX">
          </div>
          <div class="input-group input-group-sm">
            <span class="input-group-prepend">
              <label class="input-group-text" for="dataScaleY">ScaleY</label>
            </span>
            <input type="text" class="form-control" id="dataScaleY" placeholder="scaleY">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-9 docs-buttons">
        <!-- <h3>Toolbar:</h3> -->
        <!--<div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="move" title="Move">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;setDragMode&quot;, &quot;move&quot;)">
              <span class="fa fa-arrows-alt"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="crop" title="Crop">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;setDragMode&quot;, &quot;crop&quot;)">
              <span class="fa fa-crop-alt"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;zoom&quot;, 0.1)">
              <span class="fa fa-search-plus"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;zoom&quot;, -0.1)">
              <span class="fa fa-search-minus"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;move&quot;, -10, 0)">
              <span class="fa fa-arrow-left"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;move&quot;, 10, 0)">
              <span class="fa fa-arrow-right"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;move&quot;, 0, -10)">
              <span class="fa fa-arrow-up"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;move&quot;, 0, 10)">
              <span class="fa fa-arrow-down"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;rotate&quot;, -45)">
              <span class="fa fa-undo-alt"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;rotate&quot;, 45)">
              <span class="fa fa-redo-alt"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Flip Horizontal">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;scaleX&quot;, -1)">
              <span class="fa fa-arrows-alt-h"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Flip Vertical">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;scaleY&quot;, -1)">
              <span class="fa fa-arrows-alt-v"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="crop" title="Crop">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;crop&quot;)">
              <span class="fa fa-check"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="clear" title="Clear">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;clear&quot;)">
              <span class="fa fa-times"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="disable" title="Disable">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;disable&quot;)">
              <span class="fa fa-lock"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="enable" title="Enable">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;enable&quot;)">
              <span class="fa fa-unlock"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="reset" title="Reset">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;reset&quot;)">
              <span class="fa fa-sync-alt"></span>
            </span>
          </button>
          <label class="btn btn-primary btn-upload" for="inputImage" title="Upload image file">
            <input type="file" class="sr-only" id="inputImage" name="file" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Import image with Blob URLs">
              <span class="fa fa-upload"></span>
            </span>
          </label>
          <button type="button" class="btn btn-primary" data-method="destroy" title="Destroy">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;destroy&quot;)">
              <span class="fa fa-power-off"></span>
            </span>
          </button>
        </div>-->
        <div class="btn-group btn-group-crop">
<?php
	if( $_GET["act"] != "ok" ) {
?>

          <button id="picsubmitb" type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;maxWidth&quot;: 4096, &quot;maxHeight&quot;: 4096 }">
            <span id="picsubmit" class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="進行圖片合成">進行圖片合成</span>
          </button>
	  　<button type="button" class="btn btn-success" style="background-color:#ff0000;border-color:#FF0000;" onclick="javascript:window.location.assign('cases_edit.php?id=<?=$_GET["cases_id"];?>'); return false;">返回案件處理</button>
<?php
        if( $row["img"] != "" ) {
                $buff = array_pop(explode('/', $row["img"]));
                $buffa = explode( $buff,$row["img"]);
        } else {
                $buff = array_pop(explode('/', $row["PhotoURL"]));
                $buffa = explode( $buff,$row["PhotoURL"]);
        }
if(file_exists('/var/www/html/CarSystem/'.$buffa[0].'picbak.png')){
	/*
?>
	　<button type="button" class="btn btn-success" style="background-color:#ffCC00;border-color:#FFCC00;" onclick="javascript:window.location.assign('zoom2.php?cases_id=<?=$_GET["cases_id"];?>&id=<?=$_GET["id"];?>&act=reback&nocache=<?=date("YmdHis");?>'); return false;">回復原始圖片</button>
<?php */} ?>

          <!--<button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 160, &quot;height&quot;: 90 }">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;getCroppedCanvas&quot;, { width: 160, height: 90 })">
              160&times;90
            </span>
          </button>
          <button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 320, &quot;height&quot;: 180 }">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;getCroppedCanvas&quot;, { width: 320, height: 180 })">
              320&times;180
            </span>
          </button>-->
<?php
	}
?>

<?php
if( $_GET["act"] == "ok" ) {
        if( $row["img"] != "" ) {
                $buff = array_pop(explode('/', $row["img"]));
                $buffa = explode( $buff,$row["img"]);
                //建立備份
                if(!file_exists('/var/www/html/CarSystem/'.$buffa[0].'picbak'.$row["id"].'.png')){
                copy('/var/www/html/CarSystem/'.$row["img"],'/var/www/html/CarSystem/'.$buffa[0].'picbak'.$row["id"].'.png');
                }
                copy('/var/www/html/CarSystem/'.$buffa[0].'final_sc_pic.png','/var/www/html/CarSystem/'.$row["img"]);
                //echo '<script>document.location.href="cases_edit.php?id='.$row["id"].'&nocache=12345678"</script>';
        } else {
                $buff = array_pop(explode('/', $row["PhotoURL"]));
                $buffa = explode( $buff,$row["PhotoURL"]);
                //建立備份
                copy('/var/www/html/CarSystem/'.$row["PhotoURL"],'/var/www/html/CarSystem/'.$buffa[0].'picbak.png');
                copy('/var/www/html/CarSystem/'.$buffa[0].'finalpic.png','/var/www/html/CarSystem/'.$row["PhotoURL"]);
        }
}
?>
        </div>

<?php
if( $_GET["act"] == "ok" ) {
        $buff = array_pop(explode('/', $row["img"]));
        $buffa = explode( $buff,$row["img"]);
 $new_img = '/var/www/html/CarSystem/'.$buffa[0].'final_sc_pic.png';
if( $row["img"] != "" ) {
	echo '<center><p><img src="/CarSystem/'.$row["img"].'" width="80%" height="80%"></p>';
} else {
	echo '<center><p><img src="/CarSystem/'.$row["PhotoURL"].'" width="80%" height="80%"></p>';
}
//        echo '<center><p><img src="/CarSystem/'.$buffa[0].'finalpic.png" width="80%" height="80%"></p>';
	echo '<button type="button" class="btn btn-success" onclick="javascript:window.location.assign(\'cases_edit.php?id='.$_GET["cases_id"].'\'); return false;">返回案件處理</button>　
		<button type="button" class="btn btn-success" style="background-color:#ff0000;border-color:#FF0000;" onclick="javascript:window.location.assign(\'zoom2.php?cases_id='.$_GET["cases_id"].'&id='.$_GET["id"].'&nocache='.date("YmdHis").'\'); return false;">返回合成模式</button>';
}

?>


        <!-- Show the cropped image in modal -->
        <div class="modal fade docs-cropped" id="getCroppedCanvasModal" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="getCroppedCanvasTitle">合成範圍確認</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body"></div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消再調整</button>
                <a class="btn btn-primary" id="download" href="javascript:void(0);" download="cropped.jpg">下載此圖片</a>
              </div>
            </div>
          </div>
        </div><!-- /.modal -->

        <!--<button type="button" class="btn btn-secondary" data-method="getData" data-option data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;getData&quot;)">
            Get Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="setData" data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;setData&quot;, data)">
            Set Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="getContainerData" data-option data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;getContainerData&quot;)">
            Get Container Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="getImageData" data-option data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;getImageData&quot;)">
            Get Image Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="getCanvasData" data-option data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;getCanvasData&quot;)">
            Get Canvas Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="setCanvasData" data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;setCanvasData&quot;, data)">
            Set Canvas Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="getCropBoxData" data-option data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;getCropBoxData&quot;)">
            Get Crop Box Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="setCropBoxData" data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;setCropBoxData&quot;, data)">
            Set Crop Box Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="moveTo" data-option="0">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="cropper.moveTo(0)">
            Move to [0,0]
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="zoomTo" data-option="1">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="cropper.zoomTo(1)">
            Zoom to 100%
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="rotateTo" data-option="180">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="cropper.rotateTo(180)">
            Rotate 180°
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="scale" data-option="-2" data-second-option="-1">
          <span class="docs-tooltip" data-toggle="tooltip" title="cropper.scale(-2, -1)">
            Scale (-2, -1)
          </span>
        </button>-->
        <!--<textarea type="text" class="form-control" id="putData" placeholder="Get data to here or set data with this value"></textarea>-->
      </div><!-- /.docs-buttons -->

      <div class="col-md-3 docs-toggles" style="display:none;">
        <!-- <h3>Toggles:</h3> -->
        <div class="btn-group d-flex flex-nowrap" data-toggle="buttons">
          <label class="btn btn-primary active">
            <input type="radio" class="sr-only" id="aspectRatio0" name="aspectRatio" value="1.7777777777777777">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="aspectRatio: 16 / 9">
              16:9
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="aspectRatio1" name="aspectRatio" value="1.3333333333333333">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="aspectRatio: 4 / 3">
              4:3
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="aspectRatio2" name="aspectRatio" value="1">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="aspectRatio: 1 / 1">
              1:1
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="aspectRatio3" name="aspectRatio" value="0.6666666666666666">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="aspectRatio: 2 / 3">
              2:3
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="aspectRatio4" name="aspectRatio" value="NaN">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="aspectRatio: NaN">
              Free
            </span>
          </label>
        </div>

        <div class="btn-group d-flex flex-nowrap" data-toggle="buttons">
          <label class="btn btn-primary active">
            <input type="radio" class="sr-only" id="viewMode0" name="viewMode" value="0" checked>
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="View Mode 0">
              VM0
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="viewMode1" name="viewMode" value="1">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="View Mode 1">
              VM1
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="viewMode2" name="viewMode" value="2">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="View Mode 2">
              VM2
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="viewMode3" name="viewMode" value="3">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="View Mode 3">
              VM3
            </span>
          </label>
        </div>

        <div class="dropdown dropup docs-options">
          <button type="button" class="btn btn-primary btn-block dropdown-toggle" id="toggleOptions" data-toggle="dropdown" aria-expanded="true">
            Toggle Options
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" aria-labelledby="toggleOptions" role="menu">
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="responsive" type="checkbox" name="responsive" checked>
                <label class="form-check-label" for="responsive">responsive</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="restore" type="checkbox" name="restore" checked>
                <label class="form-check-label" for="restore">restore</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="checkCrossOrigin" type="checkbox" name="checkCrossOrigin" checked>
                <label class="form-check-label" for="checkCrossOrigin">checkCrossOrigin</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="checkOrientation" type="checkbox" name="checkOrientation" checked>
                <label class="form-check-label" for="checkOrientation">checkOrientation</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="modal" type="checkbox" name="modal" checked>
                <label class="form-check-label" for="modal">modal</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="guides" type="checkbox" name="guides" checked>
                <label class="form-check-label" for="guides">guides</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="center" type="checkbox" name="center" checked>
                <label class="form-check-label" for="center">center</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="highlight" type="checkbox" name="highlight" checked>
                <label class="form-check-label" for="highlight">highlight</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="background" type="checkbox" name="background" checked>
                <label class="form-check-label" for="background">background</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="autoCrop" type="checkbox" name="autoCrop" checked>
                <label class="form-check-label" for="autoCrop">autoCrop</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="movable" type="checkbox" name="movable" checked>
                <label class="form-check-label" for="movable">movable</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="rotatable" type="checkbox" name="rotatable" checked>
                <label class="form-check-label" for="rotatable">rotatable</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="scalable" type="checkbox" name="scalable" checked>
                <label class="form-check-label" for="scalable">scalable</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="zoomable" type="checkbox" name="zoomable" checked>
                <label class="form-check-label" for="zoomable">zoomable</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="zoomOnTouch" type="checkbox" name="zoomOnTouch" checked>
                <label class="form-check-label" for="zoomOnTouch">zoomOnTouch</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="zoomOnWheel" type="checkbox" name="zoomOnWheel" checked>
                <label class="form-check-label" for="zoomOnWheel">zoomOnWheel</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="cropBoxMovable" type="checkbox" name="cropBoxMovable" checked>
                <label class="form-check-label" for="cropBoxMovable">cropBoxMovable</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="cropBoxResizable" type="checkbox" name="cropBoxResizable" checked>
                <label class="form-check-label" for="cropBoxResizable">cropBoxResizable</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="toggleDragModeOnDblclick" type="checkbox" name="toggleDragModeOnDblclick" checked>
                <label class="form-check-label" for="toggleDragModeOnDblclick">toggleDragModeOnDblclick</label>
              </div>
            </li>
          </ul>
        </div><!-- /.dropdown -->

        <a class="btn btn-success btn-block" data-toggle="tooltip" data-animation="false" href="https://fengyuanchen.github.io/cropperjs" title="The non-jQuery version of Cropper (recommended)">Cropper.js</a>

      </div><!-- /.docs-toggles -->
    </div>
  </div>

  <!-- Footer -->

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="https://fengyuanchen.github.io/shared/google-analytics.js" crossorigin="anonymous"></script>
  <script src="assets/cropper.js"></script>
  <script src="assets/main.js"></script>
<script>
function pictofile(imagebase64) {
			$("#picsubmit").html('圖片合成中,請稍後.......!');
			$("#picsubmitb").attr('disabled', true);
                      var requestURL = "/hsinchu/php/pictofile2.php";
                      $.ajax({
                                  url: requestURL,
					  data: {id:"<?=$_GET["id"];?>",image:imagebase64},
                                  type: "POST",
                                  dataType: "text",
				  cache: false,
                                  success: function(returnData){
					  var as = JSON.parse(returnData);
					  if( as.act == "ok" ) {
							doCookieSetup('reload','1');
						  location.href='zoom2.php?cases_id=<?=$_GET["cases_id"];?>&act=ok&id=<?=$_GET["id"];?>&nocache=<?=date("YmdHis");?>';
				          }
                                          console.log(as.act);
                                              },
                                  error: function(xhr, ajaxOptions, thrownError){
                                                  console.log(xhr.status);
                                                  console.log(thrownError);
                                              }
                      });

}
</script>

</body>
</html>
