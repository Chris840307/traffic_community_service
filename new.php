<?php
ini_set('memory_limit', '4096M');
ini_set('max_input_var',5000);
session_start();
if( isset( $_SESSION["casesok"] ) )
	unset($_SESSION["casesok"]);

    if (isset($_SERVER)) {
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } else {
        $ip = $_SERVER['REMOTE_ADDR'];
      }
    } else {
      if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
      } elseif (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
      } else {
        $ip = getenv('REMOTE_ADDR');
      }
    }

try {
    $pdo = new PDO("mysql:host=localhost;dbname=car8", "root", "2u6u/ru8");
    $pdo->query('set names utf8;');
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

if( $ip == '211.21.98.136' ) {
echo '<script>alert("連線逾時,請重新輸入申請資料!");</script>';
header( "Location: new.php" );
exit;
}

if( isset($_GET["act"]) == "timeout" ) {
echo '<script>alert("連線逾時,請重新輸入申請資料!");</script>';
header( "Location: new.php" );
exit;
}
if( isset($_SESSION["success"]) ) {
	unset( $_SESSION["success"] );
}
if( isset($_SESSION["mailsend"]) ) {
        unset( $_SESSION["mailsend"] );
}

if( !$_SESSION["cstart"] ) {
	$_SESSION["cstart"] = true;
}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hant" manifest="manifest.appcache">

<head>
 <title>交通事故案件便民服務網</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
  <meta name="description" content="">
  <meta name="author" content="">

  <meta name="description" content="新竹市交通事故案件便民服務網">

  <meta name="csrf-param" content="authenticity_token" />
<meta name="csrf-token" content="4JCY4Y+nNeqGQ8BehEkagAeykxD2hfVJEoY7GLW1PjAfRpABZzUAJC+OKeAyzr52lIinvxsJkpF9pf4cOF5NhA==" />



<link rel="stylesheet" media="all" href="application-912f85d8572c3d3084ea384ec7cfca9cc22c50376ec5888ba042bcbcc9cb72b2.css" />

</head>


<noscript>
  <p>此網頁需要支援 JavaScript 才能正確運行，請先至你的瀏覽器設定中開啟 JavaScript。</p>
</noscript>



<body>
  
<a href="#content" id="gotocenter" title="移到主要內容" class="sr-only sr-only-focusable" >跳到主要內容</a>

<nav class="top-menu-bar" aria-label="右上方功能選單"  >
<div class="container">
<a href="#U" id="AU" name="U" title="右上方功能區塊" accesskey="U">:::</a><span class="hide">|</span>
<ul>
     
            <li><a href="https://tra.hccp.gov.tw/" title="回首頁" >首頁</a></li>
            <li><a href="https://www.hccp.gov.tw/" title="連結新竹市警察局" >新竹市警察局</a></li>
            <li><a href="https://tra.hccp.gov.tw/sitemap.html" title="連結網站導覽" >網站導覽</a></li>
     


</ul>
</div>
</nav>



<nav class="navbar navbar-expand-xl home-nav">
  <div class="row main-nav" style="padding-bottom: 20px;">
    <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
      <div class="row site-brand">
        <div style="flex: 0 0 25%; max-width: 20%;">
          <img alt="標題" class="img-fluid float-right" src="/hsin/assets/logo-bd845e53b6ff49bb0d6cd7f768061e3efb817a06a4c46f626dbfcc2dc3589bcb.gif" />
        </div>
        <div style="flex: 0 0 25%; max-width: 80%;">
          <a class="navbar-brand" href="https://tra.hccp.gov.tw/">
            <p class="zh-title">新竹市警察局交通警察隊</p>
            <p class="en-title">Traffic Police Brigade. Hsinchu City Police Bureau</p>
          </a>
        </div>
      </div>
    </div>



    <div class="col-lg-6 col-xl-6 col-md-6" style="margin-top: 15px;">
        <div class="row">
          <div class="col-lg-7 col-xl-7 col-md-12 text-center">
	    <p style="font-size:20px;color:#FFFFFF;">交通事故案件便民服務網</p>
          </div>
          <!--<div class="col-lg-5 col-xl-5 col-md-12 text-center">
            <a class="btn btn-secondary btn-sm" href="https://trs.hccp.gov.tw/HsinChuPubWeb/search.aspx">
              <i class="fa fa-search"></i>
            </a>
            <a class="btn btn-success btn-sm" href="https://trs.hccp.gov.tw/HsinChuPubWeb/search.aspx">
              舊系統案件查詢
            </a>
	    </div>-->
        </div>
    </div>
  </div>
</nav>



  <div class="top-cover"></div>

  <div style="background-color: Grey;height: 6vh">
    <div class="container header_links  d-flex h-100">
        <div class="row justify-content-center align-self-center">
          <!--<a title="開始檢舉" href="new.php"><i class="fas fa-pencil-alt"></i>&nbsp;開始檢舉</a>-->
          <a title="案件查詢" href="search.php"><i class="fas fa-search"></i>&nbsp;案件查詢</a>
        </div>
    </div>
  </div>


    </div>
  </div>

  <div id="wrapper">
    <div id="content-wrapper">
      <div class="container-fluid">
        <div class="flashes">
          

        </div>
        <div class="container">
<div id="content" class="container"></div>
<a href="" title="中央內容區塊" id="AC" accesskey="C" name="C" style="">:::</a>


          
<div class="col-md-12 case-form" style="margin-top: 15px;">

  <form class="simple_form new_case" id="new_case" enctype="multipart/form-data" action="new_w.php" onsubmit="return dosubmit()" method="post" name="new_case">
  <input type="hidden" name="case_created_at_all" id="case_created_at_all" value="<?=date("Y-m-d H:i:s");?>">
<input type="hidden" name="email_chk" id="email_chk" value="0">
<input type="hidden" name="skey" id="skey" value="">
<h1>填寫申請資料</h1>
  <div class="bgc-white p-20 bd">
    <div class="mT-30">
      <div class="alert" style="background-color: orange;text-align: center;">
       申請人 (事故當事或代理、利害關係人)
      </div>

      <!--<div class="form-group row">
        <label for="case_email" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10 input-group">
	<input class="form-control string email optional form-control " type="email" name="case[email]" id="case_email" value="<?=isset($_SESSION["email"])?$_SESSION["email"]:'';?>"/>
          <div class="input-group-append">
            <span class="input-group-text ">
              <a href="javascript:void(0)" class="send_verify_email">驗證</a>
            </span>
          </div>
        </div>
      </div>-->

      <div class="form-group row">
        <label for="case_name" class="col-sm-2 col-form-label">姓名</label>
        <div class="col-sm-10 ">
	<input class="form-control string required form-control " required="required" aria-required="true" type="text" name="case[name]" id="case_name" value="<?=isset($_SESSION["username"])?$_SESSION["username"]:'';?>"/>
        </div>
      </div>
      <div class="form-group row">
        <label for="case_id_number" class="col-sm-2 col-form-label">身分證號/護照號碼/居留證號</label>
        <div class="col-sm-10 ">
	<input class="form-control string required form-control " required="required" aria-required="true" type="text" name="case[id_number]" id="case_id_number" value="<?=isset($_SESSION["idnumber"])?$_SESSION["idnumber"]:'';?>"/>
        </div>
      </div>
      <div class="form-group row">
        <label for="case_phone" class="col-sm-2 col-form-label">聯絡電話</label>
        <div class="col-sm-10">
	<input class="form-control string required form-control " required="required" aria-required="true" type="text" name="case[phone]" id="case_phone" value="<?=isset($_SESSION["phone"])?$_SESSION["phone"]:'';?>"/>
        </div>
      </div>
      <div class="form-group row">
        <label for="case_email" class="col-sm-2 col-form-label">電子信箱:</label>
        <div class="col-sm-10 input-group">
        <input class="form-control string email optional form-control " required="required" aria-required="true" type="email" name="case[email]" id="case_email" value="<?=isset($_SESSION["email"])?$_SESSION["email"]:'';?>"/>
        </div>
      </div>
      <div class="alert" style="background-color: orange;text-align: center;">
       事故當事人  <span style="float:right;"><ul style="list-style-type:none;"><li><input type="checkbox" name="sup" id="sup" style="height:15px;!important">同申請人</li></ul></span>
      </div>
<?php 
$today = date("Y-m-d");
$week=array(
	"0"=>"日",
	"1"=>"一",
	"2"=>"二",
	"3"=>"三",
	"4"=>"四",
	"5"=>"五",
	"6"=>"六"
);
?>
      <div class="form-group row">
        <label for="case_name" class="col-sm-2 col-form-label">姓名</label>
        <div class="col-sm-10 ">
        <input class="form-control string required form-control " required="required" aria-required="true" type="text" name="case[name2]" id="case_name2" value="<?=isset($_SESSION["username2"])?$_SESSION["username2"]:'';?>"/>
        </div>
      </div>
      <div class="form-group row">
        <label for="case_id_number" class="col-sm-2 col-form-label">身分證號/護照號碼/居留證號</label>
        <div class="col-sm-10 ">
        <input class="form-control string required form-control " required="required" aria-required="true" type="text" name="case[id_number2]" id="case_id_number2" value="<?=isset($_SESSION["idnumber2"])?$_SESSION["idnumber2"]:'';?>"/>
        </div>
      </div>
      <div class="form-group row">
        <label for="case_phone" class="col-sm-2 col-form-label">聯絡電話</label>
        <div class="col-sm-10">
        <input class="form-control string required form-control " required="required" aria-required="true" type="text" name="case[phone2]" id="case_phone2" value="<?=isset($_SESSION["phone2"])?$_SESSION["phone2"]:'';?>"/>
        </div>
      </div>

      <div class="form-group row">
        <label for="case_phone" class="col-sm-2 col-form-label">案件類別</label>
        <div class="col-sm-10">
<ul style="list-style-type:none;">
<li style="color:red;"><input style="height:15px;!important" type="checkbox" name="case[group1]" id="case_group1" value="1">A1(當事人死亡)</li>
<li style="color:blue;"><input style="height:15px;!important" type="checkbox" name="case[group2]" id="case_group2" value="1">A2(當事人受傷)</li>
<li style="color:green;"><input style="height:15px;!important" type="checkbox" name="case[group3]" id="case_group3" value="1">A3(當事人未受傷僅財損案件)</li>
</ul>
        </div>
      </div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <div class="form-group row">
      <label class="col-sm-2 col-form-label">發生日期</label>
      <div class="col-sm-5 col-12">
        <input class="form-control string optional datetimepicker form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#case_violated_at" type="text" name="case[violated_at]" id="case_violated_at" />
      </div>
    </div>
    <div class="form-group row">
      <label class="col-sm-2 col-form-label">事故地點</label>
      <span style="line-height:40px">&nbsp;&nbsp;新竹市</span><div class="col-sm-5 col-12" style="float: left;"><input class="form-control string required form-control " style="width:87%" required="required" aria-required="true" type="text" name="case[place]" id="cam_place" value="<?=isset($_SESSION["cam_place"])?$_SESSION["cam_place"]:'';?>" placeholder="限新竹市道路，最多10個字"/>
      </div>
<small class="form-text text-muted alert alert-info">請依事故聯單內容填寫發生地點、路口或其他位置（如中山路1號；中正、中山路口；台68往東5K處等)。</small>
    </div>

      <div class="form-group row">
        <label for="case_phone" class="col-sm-2 col-form-label">申請項目</label>
        <div class="col-sm-10" style="margin-top:10px;">
<ul style="list-style-type:none;">
<li style="display:inline;"><input style="height:15px;!important" type="checkbox" name="case[groupp1]" id="case_groupp1" value="1">現場圖</li>
<li style="display:inline;"><input style="height:15px;!important" type="checkbox" name="case[groupp2]" id="case_groupp2" value="1">事故照片</li>
<li style="display:inline;"><input style="height:15px;!important" type="checkbox" name="case[groupp3]" id="case_groupp3" value="1">初步分析研判表</li>
</ul>
        </div>
      </div>

      <div class="form-group row">
        <label for="case_phone" class="col-sm-2 col-form-label">委託書</label>
        <div class="col-sm-10" style="margin-top:10px;">
<ul style="list-style-type:none;">
<li style="display:inline;">
      <input autocomplete="off" onchange="Filevalidation()" accept=".jpg,.png,.gif,.bmp" required="required" aria-required="true" type="file" name="case[case_attachments_attributes][0][file]" id="case_case_attachments_attributes_0_file" />
</li>
</ul>
<font color="red">*申請人如非當事人僅需上傳委託書，無需上傳身分證正面影本*</font>
  </div>
</div>

      <div class="form-group row">
        <label for="case_phone" class="col-sm-2 col-form-label">身分證(正面影本)</label>
        <div class="col-sm-10" style="margin-top:10px;">
<ul style="list-style-type:none;">
<li style="display:inline;">
      <input autocomplete="off" onchange="Filevalidation()" accept=".jpg,.png,.gif,.bmp" required="required" aria-required="true" type="file" name="case[case_attachments_attributes][1][file]" id="case_case_attachments_attributes_1_file" />
</li>
</ul>
<font color="red">*申請人同為當事人僅需上傳身分證正面影本*</font>
  </div>
</div>


      <!--<div class="form-group row">
        <div class="custom-control custom-checkbox">
          <input name="case[read_statement]" type="hidden" value="0" /><input class="form-check-input boolean optional custom-control-input" type="checkbox" value="1" name="case[read_statement]" id="case_read_statement" />
          <label for="case_read_statement" class="custom-control-label"><p class="text-danger">(必填)</p>我已閱讀並同意新竹市警察局<a href="http://www2.hccp.gov.tw/hccp/personal_information2.asp" title="個人資料收集聲明及服務條款">【個人資料收集聲明及服務條款】</a>暨相關<a href="http://www2.hccp.gov.tw/hccp/privacy.asp" title="隱私權政策">【隱私權政策】</a>
            <br/>經確認所有檢舉資料皆為真實，若相關資料(照片、車號及違規時間等) ，經查證為不實或偽造之違法行為，願負起相關民事或刑事責任</label>
        </div>
      </div>-->

      <div class="row justify-content-md-center justify-content-center" style="margin: 0px auto; line-height: 23px; font-size: 18px;">
        <!--<a class="btn btn-lg btn-success preview_btn disabled" onclick="preview()" data-remote="true" href="#">預覽</a>
        <input type="submit" name="commit" value="預覽" class="btn btn btn-lg btn-success hide" disabled="disabled" data-disable-with="預覽" />-->
        <input type="submit" id="submit_send" name="commit" value="送出" class="btn btn-lg btn-success"/>
      </div>
    </div>
  </div>

</form>
</div>

<div class="modal" id="case_preview" data-backdrop="static">

</div>

        </div>
      </div>
    </div>
  </div>

<br>
<footer>
  <div class='container' style="width: 100%;margin:0;max-width: 100%;">
    <div class="row" style="background-color: #2B83FE;padding-top: 20px;margin-bottom: 0px;">
      <div class="col-sm-3 text-center">
            <a href="#Z" id="AZ" title="下方功能區塊" accesskey="Z" name="Z">:::</a>


        <img alt="新竹市警察局LOGO" width="90%" src="/hsin/assets/footer_logo-fe30c1ad1c7fd1f6ffccc5f14a5d812d8c8651e359bdb7ea26c7562d4a8625e7.png" />
      </div>

      <div class="col-sm-5">
        <ul class="list-unstyled ">
          <li>
            <p style="font-weight:bold; color: white">
              服務時間：第一組 / 8:00~12:00、13:00~17:00　第二、三組 / 24小時<br/>
              交通隊地址：300016 新竹市北區經國路二段20號　服務電話：03-5250382<br />
交通隊交安組地址：300077 新竹市北區東大路二段561號　服務電話：03-5424658<br>


            新竹市警察局 版權所有 未經同意請勿轉載 
            <a href="https://www.hccg.gov.tw/ch/home.jsp?id=10049&amp;parentpath=0,19" title="隱私權及安全政策" target="_blank"  style="font-weight:bold; color: white">【隱私權及安全政策】「另開新視窗」</a> 
            
            <a href="https://www.hccg.gov.tw/ch/home.jsp?id=10051&amp;parentpath=0,19" title="政府網站資料開放宣告" target="_blank"  style="font-weight:bold; color: white">【政府網站資料開放宣告】「另開新視窗」</a>


            </p>
          </li>
        </ul>
      </div>
      <div class="col-sm-4">
        <ul class="list-unstyled ">
          <li class='text-center'>
         


<a class="text-muted" href="https://accessibility.moda.gov.tw/Applications/Detail?category=20230830005908" target="_blank">
                <img alt="無障礙標章2.0「另開新視窗」" height="30" src="A.png" />
              </a>
              <a class="text-muted" href="http://www.gov.tw/" target="_blank">
                <img alt="我的E政府「另開新視窗」" src="/hsin/assets/icon_gov-41d405150b9f88a10baea7e26765b78e04f22cc0fadc461d911971257ef3dadf.png" width="30" height="30" />
              </a>


 


            <img alt="image" src="/hsin/assets/fbqr-d378d2bfb898a98574a3b8d13c38a191d7850c26e4f029661512046462466cb1.png" width="80" height="80" />
            <img alt="image" src="/hsin/assets/webqr-b72d8175bda7e99e814c649d4db688439a4f39a48f8940bdcb9edfe5661a7227.png" width="80" height="80" />
          </li>
        </ul>
      </div>

    </div>
  </div>


</footer>


  <script src="application-b063375bbdcb33e1912282f5eec73dfc2ffd036e4854867f245b12caf99fa036.js"></script>
<script src="/hsin/assets/admin-7e641842b7678866dba9f029b1984fc78978fbe3c300f84802bb98e82b1f6905.js"></script>
    <script type="text/javascript">
    $(function(){

    $('body').on('click','#submit_send',function() {
    if(!$("#case_groupp1").is(":checked") && !$("#case_groupp2").is(":checked") && !$("#case_groupp3").is(":checked")) {
        toastr.error('申請項目尚未選擇....');
        return false;
    }
    if(!$("#case_group1").is(":checked") && !$("#case_group2").is(":checked") && !$("#case_group3").is(":checked")) {
        toastr.error('案件類別尚未選擇....');
        return false;
    }
    if( !$("#case_name").val()) {
        toastr.error('申請人姓名尚未填寫....');
        return false;
    }
    if( !$("#case_name2").val()) {
        toastr.error('事故當事人姓名尚未填寫....');
        return false;
    }
    if( $("#case_name").val() != $("#case_name2").val() && !$("#case_case_attachments_attributes_0_file").val()) {
	toastr.error('非事故當事人請上傳委託書....');
	return false;
    }

    if( $("#case_name").val() == $("#case_name2").val() && $("#case_case_attachments_attributes_0_file").val() && $("#case_case_attachments_attributes_1_file").val()) {
	toastr.error('申請人同為當事人僅需上傳身分證正面影本....');
	return false;
    }

    if( $("#case_name").val() != $("#case_name2").val() && $("#case_case_attachments_attributes_0_file").val() && $("#case_case_attachments_attributes_1_file").val()) {
        toastr.error('申請人如非當事人僅需上傳委託書，無需上傳身分證正面影本....');
	return false;
    }

    if( $("#case_name").val() == $("#case_name2").val() && !$("#case_case_attachments_attributes_1_file").val()) {
        toastr.error('當事人請上傳身分證正面影本....');
        return false;
    }
    if( !$("#case_id_number").val()) {
        toastr.error('申請人身分證號尚未填寫....');
        return false;
    }
    if( !$("#case_id_number2").val()) {
        toastr.error('事故當事人身分證號尚未填寫....');
        return false;
    }
    if( !$("#case_phone").val()) {
        toastr.error('申請人聯絡電話尚未填寫....');
        return false;
    }
    if( !$("#case_email").val()) {
        toastr.error('申請人電子信箱尚未填寫....');
        return false;
    }
    if( !$("#case_phone2").val()) {
        toastr.error('事故當事人聯絡電話尚未填寫....');
        return false;
    }
    if( !$("#cam_place").val()) {
	toastr.error('事故地點尚未填寫....');
	return false;
    }
    });

    $('body').on('change','#sup',function() {
        if($("input[name='sup']").is(":checked")==true) {
		 $('input[name="case[name2]"').val($('input[name="case[name]"').val());
		 $('input[name="case[id_number2]"').val($('input[name="case[id_number]"').val());
		 $('input[name="case[phone2]"').val($('input[name="case[phone]"').val());
	} else {
		$('input[name="case[name2]"').val('');
		$('input[name="case[id_number2]"').val('');
		$('input[name="case[phone2]"').val('');
	}
    });

    $('body').on('change','#case_group1',function() {
        if($("input[name='case[group1]").is(":checked")==true) {
		$("#case_group2").prop("disabled",true);
		$("#case_group3").prop("disabled",true);
        } else {
                $("#case_group2").prop("disabled",false);
                $("#case_group3").prop("disabled",false);
	}
    });
    $('body').on('change','#case_group2',function() {
        if($("input[name='case[group2]").is(":checked")==true) {
                $("#case_group1").prop("disabled",true);
                $("#case_group3").prop("disabled",true);
        } else {
                $("#case_group1").prop("disabled",false);
                $("#case_group3").prop("disabled",false);
        }
    });
    $('body').on('change','#case_group3',function() {
        if($("input[name='case[group3]").is(":checked")==true) {
                $("#case_group1").prop("disabled",true);
                $("#case_group2").prop("disabled",true);
        } else {
                $("#case_group1").prop("disabled",false);
                $("#case_group2").prop("disabled",false);
        }
    });
    $('body').on('change','#case_groupp3',function() {
	if($("input[name='case[groupp3]").is(":checked")==true) {
		if( $('#case_violated_at').val() == "" ) {
			alert('請先選擇發生時間');
			$("#case_groupp3").prop("checked",false);
		} else {
			var suptime = Date.parse(moment($('#case_violated_at').val()).format('YYYY-MM-DD'));
			var ttime = Date.parse(moment(new Date()).format('YYYY-MM-DD'));
			var iday = parseInt((ttime-suptime)/1000/60/60/24);
			if( iday<30) {
				alert('道路交通事故初步分析研判表須於事故發生30日後始可申請')
				$("#case_groupp3").prop("checked",false);
			} else {
                		if($("input[name='case[groupp1]").is(":checked")==true) {
                                	alert('現場圖無法與初步分析研判表一同申請')
                                	$("#case_groupp3").prop("checked",false);
                		}
                		if($("input[name='case[groupp2]").is(":checked")==true) {
                                	alert('事故照片無法與初步分析研判表一同申請')
                                	$("#case_groupp3").prop("checked",false);
                		}
			} 
		}


	}
    });
    $('body').on('change','#case_groupp2',function() {
        if($("input[name='case[groupp2]").is(":checked")==true) {
                if( $('#case_violated_at').val() == "" ) {
                        alert('請先選擇發生時間');
                        $("#case_groupp2").prop("checked",false);
                } else {
                        var suptime = Date.parse(moment($('#case_violated_at').val()).format('YYYY-MM-DD'));
                        var ttime = Date.parse(moment(new Date()).format('YYYY-MM-DD'));
                        var iday = parseInt((ttime-suptime)/1000/60/60/24);
                        if( iday<7) {
                                alert('事故照片須於事故發生7日後始可申請')
                                $("#case_groupp2").prop("checked",false);
                        } else {
                                if($("input[name='case[groupp3]").is(":checked")==true) {
                                        alert('事故照片無法與初步分析研判表一同申請')
                                        $("#case_groupp2").prop("checked",false);
                                }
			}
                }
        }
    });
    $('body').on('change','#case_groupp1',function() {
        if($("input[name='case[groupp1]").is(":checked")==true) {
                if( $('#case_violated_at').val() == "" ) {
                        alert('請先選擇發生時間');
                        $("#case_groupp1").prop("checked",false);
                } else {
                        var suptime = Date.parse(moment($('#case_violated_at').val()).format('YYYY-MM-DD'));
                        var ttime = Date.parse(moment(new Date()).format('YYYY-MM-DD'));
                        var iday = parseInt((ttime-suptime)/1000/60/60/24);
                        if( iday<7) {
                                alert('現場圖須於事故發生7日後始可申請')
                                $("#case_groupp1").prop("checked",false);
                        } else {
                                if($("input[name='case[groupp3]").is(":checked")==true) {
                                        alert('現場圖無法與初步分析研判表一同申請')
                                        $("#case_groupp1").prop("checked",false);
                                }
			}
                }
        }
    });
    $('body').on('change.datetimepicker','.datetimepicker',function() {
        if($("input[name='case[groupp1]").is(":checked")==true) {
                var suptime = Date.parse(moment($('#case_violated_at').val()).format('YYYY-MM-DD'));
                var ttime = Date.parse(moment(new Date()).format('YYYY-MM-DD'));
                var iday = parseInt((ttime-suptime)/1000/60/60/24);
                        if( iday<7) {
                                alert('現場圖須於事故發生7日後始可申請')
                                $("#case_groupp1").prop("checked",false);
                        }
        }
        if($("input[name='case[groupp2]").is(":checked")==true) {
                var suptime = Date.parse(moment($('#case_violated_at').val()).format('YYYY-MM-DD'));
                var ttime = Date.parse(moment(new Date()).format('YYYY-MM-DD'));
                var iday = parseInt((ttime-suptime)/1000/60/60/24);
                        if( iday<7) {
                                alert('事故照片須於事故發生7日後始可申請')
                                $("#case_groupp2").prop("checked",false);
                        }
        }
	if($("input[name='case[groupp3]").is(":checked")==true) {
		var suptime = Date.parse(moment($('#case_violated_at').val()).format('YYYY-MM-DD'));
		var ttime = Date.parse(moment(new Date()).format('YYYY-MM-DD'));
		var iday = parseInt((ttime-suptime)/1000/60/60/24);
                        if( iday<30) {
                                alert('道路交通事故初步分析研判表須於事故發生30日後始可申請')
                                $("#case_groupp3").prop("checked",false);
                        }
	}
    });

      $('input[name="agree"]').change(function() {
        if($(this).is(':checked')){
          $('.submit_statement').removeAttr('disabled')
        }else{
          $('.submit_statement').attr('disabled', 'disabled')
        }
      });
  $('#case_case_attachments_attributes_0_file').on('change', function() {
    var fileInput = $(this);
    var files = fileInput[0].files;

    // Check if any files were selected
    if (files.length > 0) {
      var validExtensions = ['jpg', 'bmp', 'png' ,'gif','jpeg'];

      // Iterate over the selected files
      for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var fileExtension = file.name.split('.').pop().toLowerCase();

        // Check if the file extension is valid
        if (validExtensions.indexOf(fileExtension) === -1) {
          alert('僅支援圖片檔案: ' + file.name);
          fileInput.val(''); // Clear the file input
          return;
        }
      }
    }
  });
  $('#case_case_attachments_attributes_1_file').on('change', function() {
    var fileInput = $(this);
    var files = fileInput[0].files;

    // Check if any files were selected
    if (files.length > 0) {
      var validExtensions = ['jpg', 'jpeg','bmp', 'png' ,'gif','mp4','avi','mov','wmv'];

      // Iterate over the selected files
      for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var fileExtension = file.name.split('.').pop().toLowerCase();

        // Check if the file extension is valid
        if (validExtensions.indexOf(fileExtension) === -1) {
          alert('僅支援影片或者圖片檔案: ' + file.name);
          fileInput.val(''); // Clear the file input
          return;
        }
      }
    }
  });
  $('#case_case_attachments_attributes_2_file').on('change', function() {
    var fileInput = $(this);
    var files = fileInput[0].files;

    // Check if any files were selected
    if (files.length > 0) {
      var validExtensions = ['jpg', 'jpeg','bmp', 'png' ,'gif','mp4','avi','mov','wmv'];

      // Iterate over the selected files
      for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var fileExtension = file.name.split('.').pop().toLowerCase();

        // Check if the file extension is valid
        if (validExtensions.indexOf(fileExtension) === -1) {
          alert('僅支援影片或者圖片檔案: ' + file.name);
          fileInput.val(''); // Clear the file input
          return;
        }
      }
    }
  });

/*
      $('.datetimepicker').each(function(i, e){
        var picker = $(e);
        var date = moment(picker.val(), format).toDate();
        var tomorrow = moment(new Date()).add(-7,'days').format("YYYY-MM-DD");
	picker.datetimepicker({format: format,startDate: "-30d",endDate:new Date()});
        picker.datetimepicker({format: format, date: null, startDate:tomorrow, endDate: new Date()});
        picker.datetimepicker('date', date);
      })
*/

  $('#case_case_attachments_attributes_3_file').on('change', function() {
    var fileInput = $(this);
    var files = fileInput[0].files;

    // Check if any files were selected
    if (files.length > 0) {
      var validExtensions = ['jpg', 'bmp', 'png' ,'gif','mp4','avi','mov','wmv'];

      // Iterate over the selected files
      for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var fileExtension = file.name.split('.').pop().toLowerCase();

        // Check if the file extension is valid
        if (validExtensions.indexOf(fileExtension) === -1) {
          alert('僅支援影片或者圖片檔案: ' + file.name);
          fileInput.val(''); // Clear the file input
          return;
        }
      }
    }
  });
  $('#case_case_attachments_attributes_4_file').on('change', function() {
    var fileInput = $(this);
    var files = fileInput[0].files;

    // Check if any files were selected
    if (files.length > 0) {
      var validExtensions = ['jpg', 'bmp', 'png' ,'gif','mp4','avi','mov','wmv'];

      // Iterate over the selected files
      for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var fileExtension = file.name.split('.').pop().toLowerCase();

        // Check if the file extension is valid
        if (validExtensions.indexOf(fileExtension) === -1) {
          alert('僅支援影片或者圖片檔案: ' + file.name);
          fileInput.val(''); // Clear the file input
          return;
        }
      }
    }
  });

    })
    $(window).on('load',function(){
        //$('#exampleModalCenter').modal('show');
    });
  </script>

<style type="text/css">



  footer{
    position: absolute;
    bottom: 0;
    width: 100%;
    clear:both;
    /*background-color: #E5E4E2;*/
  }
  footer p{
    color: #000;
    font-size: 14px;
  }

  *{
    margin: 0; padding: 0
  };

  html,body{
    height: 100%;
  };



</style>


    <script type="text/javascript">
    $(function() {
      $( ".select2" ).select2({
  theme: "bootstrap",
  language: "zh-TW"
});

$('.select2').change(function(){
  if($(this).valid() == true){

  }
});



$('form').find(".is-invalid:first").focus();


$('select.form-control.is-invalid.select2').each(function(){
  $(this).siblings('.select2-container').find(".select2-selection").addClass('is-invalid')
});

Filevalidation();
$('.invalid-feedback').each(function(){
  if($(this).next().hasClass('input-group-append')){
    $(this).next().after($(this));
  }
})
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

var namespace = "cases"

if(namespace != 'admin'){
  if($('input[name="case[read_statement]"]').is(':checked')){
    $('input[name="commit"]').removeAttr('disabled')
    $('.preview_btn').removeClass('disabled')
  }else{
    $('input[name="commit"]').attr('disabled', 'disabled')
    $('.preview_btn').addClass('disabled')
  }

$('input[name="commit"]').removeAttr('disabled')
$('.preview_btn').removeClass('disabled')

  $('input[name="case[read_statement]"]').change(function() {
    if($(this).is(':checked')){
      $('input[name="commit"]').removeAttr('disabled')
      $('.preview_btn').removeClass('disabled')
    }else{
      $('input[name="commit"]').attr('disabled', 'disabled')
      $('.preview_btn').addClass('disabled')
    }
  });
}

$("#case_illegality_code option").each(function() {
if( $(this).val() == "1" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "2" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "3" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "4" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "5" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "6" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "7" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "8" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "9" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "10" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "11" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "12" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "13" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "14" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "15" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "16" ){
$(this).wrap('<span/>');
}
if( $(this).val() == "31" ){
$(this).wrap('<span/>');
}
});

$('select[name="case[illegality_code]"]').change(function () {
  $('.illegality_details_filed').val('');
  //alert(JSON.stringify($(this).val()))
  //if($(this).val() == "13"){
  //  $('.illegality_details_filed').removeClass('hide')
  //}else{
  //  $('.illegality_details_filed').addClass('hide')
  //}
  $('.illegality_details_filed').addClass('hide')
});

var datas = {"東區":[["台68線(東區)","D0002"],["八德路","A0001"],["三民路","A0010"],["千甲路","A0011"],["大同路","A0012"],["大成街","A0013"],["大學路","A0014"],["公道五路一段","A0021"],["中央路","A0022"],["中正路","A0023"],["中南街","A0024"],["中華路一段","A0025"],["中華路二段","A0026"],["仁愛街","A0027"],["仁義街","A0028"],["介壽路","A0029"],["公園路","A0030"],["公道五路二段","A0031"],["公道五路三段","A0032"],["太原路","A0033"],["文化街","A0034"],["文昌街","A0035"],["水利路","A0037"],["水源街","A0038"],["世界街","A0039"],["世傑路","A0040"],["北大路","A0041"],["占梅路","A0042"],["四維路","A0043"],["平和街","A0044"],["民主路","A0045"],["民生路","A0046"],["民有一街","A0047"],["民有二街","A0048"],["民有街","A0049"],["民享一街","A0050"],["民享街","A0051"],["民治街","A0052"],["民族路","A0053"],["民權路","A0054"],["田美一街","A0055"],["田美二街","A0056"],["田美三街","A0057"],["立鵬路","A0058"],["仲生路","A0059"],["仰德路","A0060"],["光明新村","A0061"],["光復路一段","A0062"],["光復路二段","A0063"],["光復路二段清大西院","A0064"],["光復路二段清大東院","A0065"],["光華東街","A0066"],["光華南街","A0067"],["光華街","A0068"],["安和街","A0069"],["安康街","A0070"],["安富街","A0071"],["竹蓮街","A0077"],["自由路","A0078"],["西大路","A0079"],["西門街","A0080"],["志平路","A0081"],["育民街","A0082"],["府後街","A0083"],["忠孝路","A0084"],["明湖路","A0085"],["東大路一段","A0086"],["東大路二段","A0087"],["東山街","A0088"],["東光路","A0089"],["東明街","A0090"],["東門市場","A0091"],["東門街","A0092"],["東前街","A0093"],["東南街","A0094"],["東美路","A0095"],["東勝路","A0096"],["東進路","A0097"],["東勢街","A0098"],["東園街","A0099"],["林森路","A0100"],["武昌街","A0101"],["花園街","A0102"],["花園新城街","A0103"],["金山一街","A0104"],["金山二街","A0105"],["金山三街","A0106"],["金山五街","A0107"],["金山六街","A0108"],["金山七街","A0109"],["金山八街","A0110"],["金山九街","A0111"],["金山十街","A0112"],["金山十一街","A0113"],["金山十二街","A0114"],["金山十三街","A0115"],["金山十五街","A0116"],["金山十六街","A0117"],["金山十七街","A0118"],["金山十八街","A0119"],["金山十九街","A0120"],["金山二十街","A0121"],["金山二十一街","A0122"],["金山二十二街","A0123"],["金山二十三街","A0124"],["金山二十五街","A0125"],["金山二十六街","A0126"],["金山二十七街","A0127"],["金山二十八街","A0128"],["金山北一街","A0129"],["金山北二街","A0130"],["金山北三街","A0131"],["金山東一街","A0132"],["金山東二街","A0133"],["金山東三街","A0134"],["金山東街","A0135"],["金山街","A0136"],["金城一路","A0137"],["金城二路","A0138"],["長春街","A0139"],["信義街","A0140"],["南大路","A0141"],["南外街","A0142"],["南門街","A0143"],["南城街","A0144"],["建中一路","A0145"],["建中路","A0146"],["建功一路","A0147"],["建功二路","A0148"],["建功路","A0149"],["建美路","A0150"],["建華街","A0151"],["建新路","A0152"],["柏川一路","A0153"],["柏川二路","A0154"],["柏川三路","A0155"],["科學園路","A0166"],["風空街","A0167"],["食品路","A0168"],["原興路","A0169"],["埔頂一路","A0170"],["埔頂二路","A0171"],["埔頂三路","A0172"],["埔頂路","A0173"],["振興一街","A0176"],["振興路","A0177"],["柴橋路","A0178"],["草湖街","A0179"],["高峰路","A0180"],["高翠路","A0181"],["國華街","A0182"],["培英街","A0183"],["崇和路","A0184"],["勝利路","A0186"],["博愛街","A0187"],["復興路","A0188"],["惠民街","A0189"],["湖濱一路","A0190"],["湖濱二路","A0191"],["湖濱三路","A0192"],["園後街","A0193"],["園區二路356巷口(新竹市轄)","A0195"],["慈祥路","A0197"],["慈雲路","A0198"],["慈濟路","A0199"],["愛民街","A0200"],["新光路","A0201"],["新香街","A0203"],["新莊街","A0204"],["新源街","A0205"],["溪州路","A0206"],["溪埔路","A0207"],["瑞麟路","A0208"],["經國路一段","A0209"],["裕民街","A0210"],["福德街","A0211"],["綠水路","A0212"],["德成街","A0213"],["德高街","A0214"],["學府路","A0215"],["澤藩路","A0216"],["興中街","A0219"],["興竹街","A0220"],["興達街","A0222"],["興學街","A0223"],["錦華街","A0224"],["龍山西路","A0225"],["龍山東一街","A0226"],["龍山東路","A0227"],["藝術路","A0228"],["關東路","A0229"],["關新一街","A0230"],["關新二街","A0231"],["關新北路","A0232"],["關新西街","A0233"],["關新東路","A0234"],["關新路","A0235"],["寶山路","A0236"],["鐵道路一段","A0237"],["鐵道路二段","A0238"],["體育街","A0239"],["赤土崎一街","A0240"],["赤土崎二街","A0242"],["金山南街","A0243"],["東新路","A0244"],["東竹路","A0245"],["金城三路","A0246"],["新科一街","A0247"],["客雅大道","A0248"],["公竹路","A0249"],["金山忠街","A250"]],"北區":[["台68線(北區)","D0001"],["大同路","B0001"],["中山路","B0002"],["中央路","B0003"],["中正路","B0004"],["中光路","B0005"],["中和路","B0006"],["中清一路","B0007"],["中清路","B0008"],["中清路一段","B0009"],["中華路二段","B0010"],["中華路三段","B0011"],["中福路","B0012"],["中興路","B0013"],["仁化街","B0014"],["仁和路","B0015"],["仁德街","B0016"],["公道五路四段","B0017"],["公道五路五段","B0018"],["天府路一段","B0019"],["天府路二段","B0020"],["少年街","B0021"],["文雅街","B0022"],["水田街","B0023"],["世界街","B0024"],["北大路","B0025"],["北門街","B0026"],["北新街","B0027"],["古賢","B0028"],["四維路","B0029"],["平和街","B0030"],["民富街","B0031"],["田美三街","B0032"],["石坊街","B0033"],["光華一街","B0034"],["光華二街","B0035"],["光華北街","B0036"],["光華東一街","B0037"],["光華東街","B0038"],["光華南街","B0039"],["光華街","B0040"],["吉羊路","B0041"],["成功路","B0042"],["成德一街","B0043"],["成德二街","B0044"],["成德路","B0045"],["江山街","B0046"],["竹文街","B0047"],["竹光路","B0048"],["西大路","B0049"],["西安街","B0050"],["西門街","B0051"],["西濱路一段","B0052"],["孝賢路","B0053"],["育英路","B0054"],["和平路","B0055"],["和福街","B0056"],["尚濱路","B0057"],["府後街","B0058"],["延平路一段","B0059"],["延平路三段","B0060"],["延濱路","B0061"],["忠信路","B0062"],["東大路二段","B0063"],["東大路三段","B0064"],["東大路四段","B0065"],["東門街","B0066"],["東濱街","B0067"],["林森路","B0068"],["武勇街","B0069"],["武陵西二路","B0070"],["武陵西三路","B0071"],["武陵西四路","B0072"],["武陵路","B0073"],["河北街","B0074"],["金竹路","B0075"],["金雅一街","B0076"],["金雅二街","B0077"],["金雅三街","B0078"],["金雅五街","B0079"],["金雅西街","B0080"],["金雅路","B0081"],["金農路","B0082"],["長安街","B0083"],["長和街","B0084"],["南大路","B0085"],["南勢二街","B0086"],["南勢六街","B0087"],["南勢八街","B0088"],["南勢十街","B0089"],["南勢十二街","B0090"],["南勢街","B0091"],["南寮街","B0092"],["城北街","B0093"],["建台街","B0094"],["建國街","B0095"],["建興街","B0096"],["英明街","B0097"],["凌雲街","B0098"],["海濱路","B0099"],["草湖街","B0100"],["國光街","B0101"],["國華街","B0102"],["崧嶺路","B0103"],["勝利路","B0104"],["富美路","B0105"],["港北一街","B0106"],["港北二街","B0107"],["港北三街","B0108"],["港北六街","B0109"],["集和街","B0110"],["集福街","B0111"],["集賢街","B0112"],["湳中街","B0113"],["湳雅街","B0114"],["愛文街","B0115"],["愛國路","B0116"],["新民街","B0117"],["新香街","B0118"],["新港三路","B0119"],["新港北路","B0120"],["新港南路","B0121"],["經國路一段","B0122"],["經國路二段","B0123"],["聖軍路","B0124"],["嘉濱路","B0125"],["境福街","B0126"],["榮濱南路","B0127"],["榮濱路","B0128"],["演藝路","B0129"],["廣州街","B0130"],["德成街","B0131"],["磐石路","B0132"],["衛民街","B0133"],["興南街","B0134"],["興濱路","B0135"],["聯興路","B0136"],["警光路","B0137"],["鐵道路二段","B0138"],["鐵道路三段","B0139"],["士林東路","B0140"],["士林西路","B0141"],["士林北路","B0142"],["士林一街","B0143"],["士林二街","B0144"],["新港四路","B0145"],["金雅八街","B0146"],["金雅東街","B0148"],["金雅七街","B0147"],["金雅六街","B0149"],["台61西濱快速公路","B0150"],["公道三路","B0151"],["南寮大道","B0152"],["仁愛街","B0153"],["大雅路","B0154"]],"香山區":[["大湖路","C0001"],["大庄路","C0002"],["中山路","C0003"],["中華路四段","C0004"],["中華路五段","C0005"],["中華路六段","C0006"],["五福路一段","C0007"],["五福路二段","C0008"],["元培街","C0009"],["內湖路","C0010"],["牛埔北路","C0011"],["牛埔東路","C0012"],["牛埔南路","C0013"],["牛埔路","C0014"],["古車路","C0015"],["玄奘路","C0016"],["竹香北路","C0017"],["竹香南路","C0018"],["至善街","C0019"],["西濱路二段","C0020"],["西濱路三段","C0021"],["西濱路四段","C0022"],["西濱路六段","C0023"],["吳厝街","C0024"],["育德街","C0025"],["那魯灣街","C0026"],["延平路二段","C0027"],["明德路","C0028"],["東香東街","C0029"],["東香南街","C0030"],["東香路一段","C0031"],["東香路二段","C0032"],["東華路","C0033"],["芝柏一街","C0034"],["芝柏二街","C0035"],["芝柏三街","C0036"],["芝柏五街","C0037"],["花園新城一街","C0038"],["花園新城二街","C0039"],["虎林街","C0040"],["長福街","C0041"],["長興街","C0042"],["南港街","C0043"],["南湖路","C0044"],["南隘路一段","C0045"],["南隘路二段","C0046"],["柯湳一街","C0047"],["柯湳二街","C0048"],["美山路","C0049"],["美之城","C0050"],["美之城一街","C0051"],["美森街","C0052"],["茄苳北街","C0053"],["茄苳東街","C0054"],["茄苳路","C0055"],["香北一路","C0056"],["香北路","C0057"],["香村路","C0058"],["香檳一街","C0059"],["香檳二街","C0060"],["香檳三街","C0061"],["香檳五街","C0062"],["香檳東街","C0063"],["香檳南街","C0064"],["埔前路","C0065"],["宮口街","C0066"],["柴橋路","C0067"],["浸水北街","C0068"],["浸水南街","C0069"],["浸水街","C0070"],["浸樹街","C0071"],["海山港十街","C0072"],["海山港路","C0073"],["海埔一街","C0074"],["海埔二街","C0075"],["海埔三街","C0076"],["海埔五街","C0077"],["海埔路","C0078"],["草漯街","C0079"],["國中街","C0080"],["崧嶺路","C0081"],["彩虹路","C0082"],["祥園街","C0083"],["莊敬街","C0084"],["頂美街","C0085"],["頂埔路","C0086"],["富群街","C0087"],["富禮街","C0088"],["景觀大道","C0089"],["港南一街","C0090"],["港南二街","C0091"],["港南三街","C0092"],["港南五街","C0093"],["華北路","C0094"],["華江街","C0095"],["閑谷一街","C0096"],["閑谷二街","C0097"],["閑谷街","C0098"],["新香街","C0099"],["瑞光街","C0100"],["經國路三段","C0101"],["遊樂街","C0102"],["福樹街","C0103"],["墩豐路","C0104"],["樹下街","C0105"],["麗山街","C0106"],["草原路","C0107"],["公道三路","C0108"],["西濱路五段","C0109"],["台61西濱快速公路","C0110"],["三姓橋路","C0111"]]};
var attrs = jQuery.parseJSON('{"id":null,"email":null,"name":null,"id_number":null,"contact_address":null,"phone":null,"created_at":null,"updated_at":null,"addr1":null,"addr2":null,"addr3":null,"addr4":null,"addr5":null,"area_code":null,"illegality_code":null,"illegality_details":null,"car_type_code":null,"first_car_number":null,"last_car_number":null,"sn":null,"area_district":null,"addr_detail":null,"violated_at":null,"real_ip":null,"read_statement":null,"state":"ready","department_id":null,"approve_admin_user_id":null,"review_admin_user_id":null,"recheck_admin_user_id":null,"finish_admin_user_id":null,"expose":false,"expose_reason_code":null,"unexpose_reason_id":null,"unexpose_reason_note":null,"fake":false,"full_car_number":null,"finished_date":null,"comment":null,"reassign_count":0,"category_name":null,"created_admin_user_id":null,"violated_at_date":null,"violated_at_hour":null,"violated_at_min":null}')
var select = $('select.district');
select.change(function () {
  var streets = datas[$(this).val()];
  $('select[name="case[area_code]"]').empty();
  // $('<option>' + '區' + '</option>').appendTo('select[name="case[area_code]"]');
  $('<option value>' + '請輸入關鍵字搜尋' + '</option>').appendTo('select[name="case[area_code]"]');
  $.each(streets, function(index,item) {
    $('<option value=' + item[1] + '>' + item[0] + '</option>').appendTo('select[name="case[area_code]"]');
  });
});
// $('select.district option').remove();
// $('<option>' + '路名' + '</option>').appendTo('select[name="case[area_code]"]');
// $('<option>' + '區' + '</option>').appendTo('select[name="case[area_code]"]');
$.each(datas, function (key, value) {
  $('<option value="' + key + '">' + key + '</option>').appendTo(select);
});

if(!isEmpty(attrs['area_district'])){
  select.val(attrs['area_district']).change();
}


var pro = $('select.district').val();

var streets = datas[pro];

// $('select[name="case[area_code]"] option').remove();
$.each(streets, function(index, item) {
  $('<option value=' + item[1] + '>' + item[0] + '</option>').appendTo('select[name="case[area_code]"]');
});
setTimeout(function(){ 
  if(!isEmpty(attrs['area_code'])){
    $('select[name="case[area_code]"]').val(attrs['area_code']).change();
  }
}, 1000);




  $(".send_verify_email").click(function(){
    // var reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

    var reg = /\S+@\S+\.\S+/
    var email =$('#case_email').val() 
    var name = $('input[name="case[name]"]').val()
    var id_number = $('input[name="case[id_number]"]').val()
    if(reg.test(email)){
      $.post( "s_v_e.php", { email: email, name: name, id_number: id_number },function(resultt){
	      if(resultt.match('ok')) { 
		var strAry = resultt.split(',');
		var skey = strAry[1]
                        $('.send_verify_email').html('已驗證通過');
                        $('.send_verify_email').attr('disabled', 'disabled');
                        $('.send_verify_email').append()
                        $('.send_verify_email').addClass('disabled');
                        $('#email_chk').val('1');
			$('#skey').val(skey);
	      }
	      if( resultt == "1" ) {
			$('.send_verify_email').html('已驗證通過');
			$('.send_verify_email').attr('disabled', 'disabled');
			$('.send_verify_email').append()
			$('.send_verify_email').addClass('disabled');
			$('#email_chk').val('1');
	      } 
              if( resultt == "2" ) {
		      	alert('請收信驗證.....');
                        //$('.send_verify_email').html('請收信驗證');
                        //$('.send_verify_email').attr('disabled', 'disabled');
                        //$('.send_verify_email').append()
                        //$('.send_verify_email').addClass('disabled');
			$('#email_chk').val('0');
              }
      })

      setTimeout(function(){
        $('.send_verify_email').text('驗證')
        $('.send_verify_email').removeClass('disabled')
        clearInterval(i)
      }, 5 * 60 * 1000);

    }else{
      toastr.error('輸入正確的Email地址');
    }
  });



  // $('input[type="submit"]').click(function(event){
  //   var email = $('input[name="case[email]"]').val()
  //   event.preventDefault();
  //   $.post("/hsin/verifications/check_verify", {email: email}, function(data){
  //     var v = data.verified
  //     if(v){
  //       $("form").submit()
  //     }else{
  //       // $('input[type="submit"]').removeAttr('data-disable-with')
  //       $('input[name="case[email]"]').removeClass( "is-valid" ).addClass( "is-invalid" )
  //       $('input[name="case[email]"]').focus();
  //       $('#case_email-error').remove();
  //       $('input[name="case[email]"]').parent().append('<div id="case_email-error" class="error invalid-feedback d-block">請先驗證Email地址</div>')

  //       return false;
  //     }
  //   })
  // });



validate_form();


$('form').on('cocoon:after-insert', function() {
  check_to_hide_or_show_add_link();
});

$('form').on('cocoon:after-remove', function() {
  check_to_hide_or_show_add_link();
});

$('.nested-fields').each(function() {
  $(this).insertBefore($(this).parent());
})
check_to_hide_or_show_add_link();
assign_attrs();


    })
    $(window).on('load',function(){
	    //alert('本局檢舉系統修護中，可將檢舉資料(含檢舉人姓名、身分證字號、住址、聯絡電話、違規影像、影片、違規車輛號牌、違規行為等資料)寄至gov415654@gmail.com信箱，本局將會建案處理，造成不便之處請諒察。\n\n 非常感謝您對警政交通問題的關心，敬祝萬事如意。\n\n 業務承辦人：交通警察隊分隊長 陳柏原 \n\n 聯絡電話：03-5250382');
            //window.location.replace("/");
    });
    function clear_file_input(t){
  var f_input = $('input[name="case[case_attachments_attributes][' + t + '][file]"]')
  f_input.val('').change();
  f_input.removeClass('is-valid');
  $('input[name="case[case_attachments_attributes][' + t + '][file_cache]').val('').change();
  $('.file-label-' + t).text('選擇檔案');
}
function validate_form(){
  $.validator.addMethod('addrCheck', function(value, element, params) {
    var a1 = $('input[name="case[addr1]"]').val();
    var a2 = $('input[name="case[addr2]"]').val();
    var a3 = $('input[name="case[addr3]"]').val();
    var a4 = $('input[name="case[addr4]"]').val();
    var a5 = $('input[name="case[addr5]"]').val();
    var a6 = $('input[name="case[addr_detail]"]').val();
    return !(isEmpty(a1) && isEmpty(a2) && isEmpty(a3) && isEmpty(a4) && isEmpty(a5) && isEmpty(a6));
  }, "巷、弄、號、之號、KM，及地點備註，至少要填其中一個");


  $.validator.addMethod('zeroCheck', function(value, element, params) {
    sum = Filevalidation();
    return sum != 0;
  }, "檔案不能是 0KB");

  $.validator.addMethod('totalCheck', function(value, element, params) {
    sum = Filevalidation();
    
    return sum >= 0 && sum <= 85;
  }, "上傳檔案之檔案大小總和已超出範圍限制");

  $.validator.addMethod('illegality_check', function (value, element, param) {
    return !($('#case_illegality_code').val() == 13 && isEmpty(value));
  }, '請填寫違規明細');


  $.validator.addMethod('filesize', function (value, element, param) {
    if(element.files.length > 0){
      var fs = element.files[0].size  / Math.pow(1024,2);
      return (fs <= param && element.files[0].size >= 1000)
    }else{
      return true
    }
  }, '單個檔案必須小於 {0} MB，並大於 1 KB');

  $.validator.addMethod(
    "regex",
    function(value, element, regexp) {
        var re = new RegExp(regexp);
        return this.optional(element) || re.test(value);
    },
    "格式不正確"
  );


  $( "form" ).validate( {
    focusInvalid: false,
    invalidHandler: function() {
      $(this).find(".is-invalid:first").focus();
    },
    rules: {
      "case[name]":{
        required: true,
        //regex: /^[\u4E00-\u9FA5]+$/,
        maxlength: 30
      },

      "case[email]":{
        required: true,
        regex: /^([-!#-'*+/-9=?A-Z^-~]+(\.[-!#-'*+/-9=?A-Z^-~]+)*|"([]!#-[^-~ \t]|(\\[\t -~]))+")@([0-9A-Za-z]([0-9A-Za-z-]{0,61}[0-9A-Za-z])?(\.[0-9A-Za-z]([0-9A-Za-z-]{0,61}[0-9A-Za-z])?)*|\[((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|IPv6:((((0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}):){6}|::((0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}):){5}|[0-9A-Fa-f]{0,4}::((0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}):){4}|(((0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}):)?(0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}))?::((0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}):){3}|(((0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}):){0,2}(0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}))?::((0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}):){2}|(((0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}):){0,3}(0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}))?::(0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}):|(((0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}):){0,4}(0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}))?::)((0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}):(0|[1-9A-Fa-f][0-9A-Fa-f]{0,3})|(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3})|(((0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}):){0,5}(0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}))?::(0|[1-9A-Fa-f][0-9A-Fa-f]{0,3})|(((0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}):){0,6}(0|[1-9A-Fa-f][0-9A-Fa-f]{0,3}))?::)|(?!IPv6:)[0-9A-Za-z-]*[0-9A-Za-z]:[!-Z^-~]+)])$/,
      },

      "case[id_number]":{
        required: true,
        //regex: /^[a-zA-Z][0-9]{9}$/,
      },

      "case[name2]":{
        required: true,
        //regex: /^[\u4E00-\u9FA5]+$/,
        maxlength: 30
      },

      "case[id_number2]":{
        required: true,
        //regex: /^[a-zA-Z][0-9]{9}$/,
      },

      "case[contact_address]":{
        required: true,
        minlength: 10,
        maxlength:50
      },

      "case[phone]":{
        required: true,
        maxlength: 20,
        regex: /^[0-9\-\#]*$/,
      },

      "case[addr1]":{
        required: false,
        digits: true,
      },

      "case[addr2]":{
        required: false,
        digits: true,
      },

      "case[addr3]":{
        required: false,
        digits: true,
      },

      "case[addr4]":{
        required: false,
        digits: true,
      },

      "case[addr5]":{
        required: false,
        digits: true,
      },
      "case[addr_detail]":{
        required: false,
        maxlength: 10,
        addrCheck: []
      },

      "case[violated_at_date]":{
        required: true
      },

      "case[violated_at_hour]":{
        required: true
      },

      "case[violated_at_min]":{
        required: true
      },

      "case[car_type_code]":{
        required: true
      },

      "case[area_district]":{
        required: true
      },
      "case[area_code]":{
        required: true
      },



      "case[first_car_number]":{
        required: true,
        regex: /^[0-9a-zA-Z]+$/,
        minlength: 2,
        maxlength: 4
      },

      "case[last_car_number]":{
        required: true,
        regex: /^[0-9a-zA-Z]+$/,
        minlength: 2,
        maxlength: 4
      },
      "case[illegality_code]":{
        required: true
      },

      "case[category_name]":{
        required: true
      },


      "case[illegality_details]":{
        illegality_check: true,
        maxlength: 100
      },


      "case[email]": {
        email: true,
      },
      "case[case_attachments_attributes][4][file]": {
        totalCheck: [],
        zeroCheck: []
      },
    },
    messages: {
      // "case[email]": {remote: "請先驗證Email地址"}
      "case[name]": {regex: '請填寫真實中文姓名'},
      "case[id_number]": {regex: '請輸入真實身份證格式'},
      "case[name2]": {regex: '請填寫真實中文姓名'},
      "case[id_number2]": {regex: '請輸入真實身份證格式'},
      "case[last_car_number]": {regex: '請輸入英文或數字'},
      "case[first_car_number]": {regex: '請輸入英文或數字'},
    },
    errorElement: "div",
    errorPlacement: function ( error, element ) {
      error.addClass( "invalid-feedback d-block" );

      if ( element.prop( "type" ) === "checkbox" ) {
        error.insertAfter( element.parent( "label" ) );
      }else if(element.next().hasClass('input-group-append')){
        error.appendTo(element.parent());
      }else {
        error.insertAfter( element );
      }
    },
    highlight: function ( element, errorClass, validClass ) {

      if($(element).attr("type") == 'file' && $(element)[0].files.length == 0){
      }else if($(element).hasClass('select2')){
        $(element).siblings('.select2-container').find(".select2-selection").addClass('is-invalid').removeClass('is-valid');
      }else{
        $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
      }

    },
    unhighlight: function (element, errorClass, validClass) {
      if($(element).attr("type") == 'file' && $(element)[0].files.length == 0){
      }else if($(element).hasClass('select2')){
        $(element).siblings('.select2-container').find(".select2-selection").addClass( "is-valid" ).removeClass( "is-invalid" );
      }else{
        $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
      }

    }
  });

  $('input[name^="case[case_attachments_attributes]"]').each(function () {
    $(this).rules('add', {
        required: false,
        filesize: 100 
    })
  });
}
function preview(){
  var valid = $('form').valid();
  if(valid){

    $('#case_preview').html("<div class=\"modal-dialog\">\n  <div class=\"modal-content\">\n    <div class=\"modal-header\">\n      <h2 class=\"modal-title\">申請資料預覽<\/h2>\n      <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;<\/button>\n    <\/div>\n\n    <!-- Modal body -->\n    <div class=\"modal-body\">\n      <h2>申請人<\/h2>\n      <dl class=\"row\">\n\n\n          <dt class=\"col-sm-4\">Email<\/dt>\n          <dd class=\"col-sm-8\" data-name=\"case[email]\">\n\n          <\/dd>\n\n          <dt class=\"col-sm-4\">檢舉人姓名<\/dt>\n          <dd class=\"col-sm-8\" data-name=\"case[name]\">\n\n          <\/dd>\n\n          <dt class=\"col-sm-4\">身分證字號<\/dt>\n          <dd class=\"col-sm-8\" data-name=\"case[id_number]\">\n\n          <\/dd>\n\n          <!--<dt class=\"col-sm-4\">聯絡地址<\/dt>-->\n          <dd class=\"col-sm-8\" data-name=\"case[contact_address]\">\n\n          <\/dd>\n\n          <dt class=\"col-sm-4\">聯絡電話<\/dt>\n          <dd class=\"col-sm-8\" data-name=\"case[phone]\">\n\n          <\/dd>\n\n          <dt class=\"col-sm-4\">送件時間<\/dt>\n          <dd class=\"col-sm-8\" data-name=\"case[created_at]\">\n\n          <\/dd>\n      <\/dl>\n\n      <!--<h2>檢舉內容<\/h2>\n      <dl class=\"row\">\n          <dt class=\"col-sm-4\">違規日期／時間<\/dt>\n          <dd class=\"col-sm-8\"  data-name=\"case[violated_at]\">\n\n          <\/dd>\n          <dt class=\"col-sm-4\">違規車號<\/dt>\n          <dd class=\"col-sm-8\"  data-name=\"case[car_number]\">\n\n          <\/dd>\n          <dt class=\"col-sm-4\">違規地點<\/dt>\n          <dd class=\"col-sm-8\"  data-name=\"case[full_address]\">\n\n          <\/dd>\n          <dt class=\"col-sm-4\">違規事實<\/dt>\n          <dd class=\"col-sm-8\"  data-name=\"case[illegality_name]\">\n\n          <\/dd>\n\n\n        <dt class=\"col-sm-4 illegality_details\" style=\"display: none;\">違規事實說明<\/dt>\n            <dd class=\"col-sm-8 illegality_details\" style=\"display: none;\"  data-name=\"case[illegality_details]\">\n        <\/dl>\n      <h2>檔案上傳<\/h2>\n      <div class=\"attachments\">\n          <p id=\"attachment_0\"><\/p>\n          <p id=\"attachment_1\"><\/p>\n          <p id=\"attachment_2\"><\/p>\n          <p id=\"attachment_3\"><\/p>\n          <p id=\"attachment_4\"><\/p>\n          <p id=\"attachment_5\"><\/p>\n      <\/div>\n    <\/div>\n\n    \n    <div class=\"modal-footer\">\n	    <button type=\"button\" class=\"btn btn-primary submit-form\" onclick=\"submit_form()\">確認送出<\/button>-->\n	    <button type=\"button\" class=\"btn btn-primary submit-form\" onclick=\"submit_form()\">確認送出<\/button>\n      <button type=\"button\" class=\"btn btn-danger edit-form-btn\" data-dismiss=\"modal\">繼續編輯<\/button>\n    <\/div>\n\n  <\/div>\n<\/div>\n")
    var formData = $('form').serializeArray();
    var namespace = "cases"
    if(namespace != 'admin'){
      formData.push({name: 'case[violated_at]', value: get_violated_at()});
      formData.push({name: 'case[created_at]', value: get_created_at()});
    }
    


    
    $.each(formData, function(i, e){
      $("#case_preview [data-name='" + e.name + "']").text(e.value);
    })

    $('#case_preview').modal('show')

    $('.submit-form').click(function(){
      $('.case_submit').click();
      if(!$("form").valid()){
        $('#case_preview').modal('hide');
      };
    })
  }else{
    $('form').find(".is-invalid:first").focus();
  }
}


function readURL(input, target) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      target.append('<img src="' + e.target.result + '">');
    }
    reader.readAsDataURL(input.files[0]);
  }
}

function getcasestmp() {
 $.ajax({
	url: 'chk_tmp.php',
	type: 'get',
	dataType: 'json',
	success: function (data) {
		if( data == 1 ) {
			window.location.href = 'new_w.php?email='+$('#case_email').val()+'&username='+$('#case_name').val()+'&phone='+$('#case_phone').val()+'&addr='+$('#case_contact_address').val()+'&idnumber='+$('#case_id_number').val()
		} else {
			setTimeout(getcasestmp,1000);
		}
	}
 })
}

function submit_form(){
//alert('檢舉系統維護中!');
  $('.submit-form').attr('disabled', 'disabled').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>正在送出中，請勿關閉...');
  $('.edit-form-btn').attr('disabled', 'disabled');
  $('.edit-form-btn').addClass('disabled');
  //$('.case_submit').click();
  getcasestmp();
  /*
  $('.case_submit').bind("click",function(){ 
	  setTimeout(function(){
		  window.location.href = 'new_w.php?email='+$('#case_email').val()+'&username='+$('#case_name').val()+'&phone='+$('#case_phone').val()+'&addr='+$('#case_contact_address').val()+'&idnumber='+$('#case_id_number').val() 
	  }, 10000);
  });
  */

  //$('#case_preview').modal('hide');
//setTimeout(function(){
//	window.location.href = 'new_w.php?email='+$('#case_email').val()+'&username='+$('#case_name').val()+'&phone='+$('#case_phone').val()+'&addr='+$('#case_contact_address').val()+'&idnumber='+$('#case_id_number').val()
//}, 5000);
}



function get_violated_at(){
  return $('#case_violated_at_date').val() + ' ' +$('#case_violated_at_hour').val() + ':' + $('#case_violated_at_min').val()
}

function get_created_at(){
  return $('#case_created_at_all').val()
}

function get_car_number(){
  return $('#case_car_type_code option:selected').text() + ' ' + $('#case_first_car_number').val().toUpperCase() + '-' + $('#case_last_car_number').val().toUpperCase()
}

function get_address(){
  var s = $('#case_area_district option:selected').text() +  $('#case_area_code option:selected').text()

  if(!isEmpty($('#case_addr1').val())){
    s += $('#case_addr1').val() + '巷'
  }

  if(!isEmpty($('#case_addr2').val())){
    s += $('#case_addr2').val() + '弄'
  }

  if(!isEmpty($('#case_addr3').val())){
    s += $('#case_addr3').val() + '號'
  }

  if(!isEmpty($('#case_addr4').val())){
    s += "之" + $('#case_addr4').val()
  }

  if(!isEmpty($('#case_addr5').val())){
    s += "  " + $('#case_addr5').val() + '公里'
  }

  if(!isEmpty($('#case_addr_detail').val())){
    s += " (" + $('#case_addr_detail').val() + ")"
  }

  return s
}
function assign_attrs(){
  var attrs = jQuery.parseJSON('{"id":null,"email":null,"name":null,"id_number":null,"contact_address":null,"phone":null,"created_at":null,"updated_at":null,"addr1":null,"addr2":null,"addr3":null,"addr4":null,"addr5":null,"area_code":null,"illegality_code":null,"illegality_details":null,"car_type_code":null,"first_car_number":null,"last_car_number":null,"sn":null,"area_district":null,"addr_detail":null,"violated_at":null,"real_ip":null,"read_statement":null,"state":"ready","department_id":null,"approve_admin_user_id":null,"review_admin_user_id":null,"recheck_admin_user_id":null,"finish_admin_user_id":null,"expose":false,"expose_reason_code":null,"unexpose_reason_id":null,"unexpose_reason_note":null,"fake":false,"full_car_number":null,"finished_date":null,"comment":null,"reassign_count":0,"category_name":null,"created_admin_user_id":null,"violated_at_date":null,"violated_at_hour":null,"violated_at_min":null}')
  $.each(attrs,function(name,value) {
    if(!isEmpty(value)){
      $('[name="case[' + name + ']"]').val(value).change();
    }
  });
  if(!isEmpty(attrs.area_district)){
    $('[name="case[' + "area_district" + ']"]').val(attrs.area_district).change();
    $('[name="case[' + "area_code" + ']"]').val(attrs.area_code).change();
  }




}

function isEmpty(value) {
  return typeof value == 'string' && !value.trim() || typeof value == 'undefined' || value === null;
}

function check_to_hide_or_show_add_link() {
  if ($('.nested-fields').length >= 5) {
    $('.add-attachment-btn').hide();
  } else {
    $('.add-attachment-btn').show();
  }
}

function Filevalidation(){
  var sum = 0;
  $('.case_attachment_input').each(function(i){
    if($(this)[0] && $(this)[0].files[0]){
      var fi = $(this)[0]
      var totalSize = fi.files[0].size;
      sum += totalSize
    }else if($(this).siblings('.case_attachment_cache').data('file-size') > 0){
      var cache = $(this).siblings('.case_attachment_cache')
      var s1 = cache.data('file-size')
      sum += s1
    }
  })
  total = (parseFloat(sum)  / Math.pow(1024,2)).toFixed(1)
  $('.filesize').text(total);
  return total;
}

function dosubmit(){
     //获取表单提交按钮
     var btnSubmit = document.getElementById("submit_send");
     //将表单提交按钮设置为不可用，这样就可以避免用户再次点击提交按钮
     btnSubmit.disabled= "disabled";
     btnSubmit.value="資料送出中,請稍後...........";
     //返回true让表单可以正常提交
     return true;
 }
  </script>



</body>




</html>
