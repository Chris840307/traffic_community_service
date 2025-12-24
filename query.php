<?php
session_start();

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=car8", "root", "2u6u/ru8");
        $pdo->query('set names utf8;');
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
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

$nowtime = date("Y-m-d H:i:s");

if( $_POST["search"]["email"] == "" ) {
	header( "Location: search.php" );
	exit;
} else {
	if( $_POST["search"]["email"] != "" && !preg_match("/^[\w\-\.]+@[\w\-]+(\.\w+)+$/", $_POST["search"]["email"])) {
		header( "Location: search.php" );
		exit;
	}
}

if( $_POST["search"]["sn"] == "" ) {
        header( "Location: search.php" );
        exit;
} else {
	$chk = substr( $_POST["search"]["sn"] , 0 , 1 );
	if( strtoupper($chk[0]) != 'T' ) {
		header( "Location: search.php" );
		exit;
	}
	$chk = explode('T',$_POST["search"]["sn"]);
        if( !is_numeric($chk[1]) ) {
                header( "Location: search.php" );
                exit;
        }
}

$result = $pdo->query("select * from `cases` where `email`='".$_POST["search"]["email"]."' and `sn`='".$_POST["search"]["sn"]."' and `pwd`='".$_POST["search"]["pwd"]."' limit 1");
$row = $result->fetch(PDO::FETCH_ASSOC);
$result_d = $pdo->query("select * from `department` where `sname`='".$row["review_unit"]."' limit 1");
$rowd = $result_d->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hant">

<head>
 <title>交通事故案件便民服務網</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <meta name="description" content="新竹市交通事故案件便民服務網">

  <meta name="csrf-param" content="authenticity_token" />
<meta name="csrf-token" content="4JCY4Y+nNeqGQ8BehEkagAeykxD2hfVJEoY7GLW1PjAfRpABZzUAJC+OKeAyzr52lIinvxsJkpF9pf4cOF5NhA==" />
  

  <link rel="stylesheet" media="all" href="application-912f85d8572c3d3084ea384ec7cfca9cc22c50376ec5888ba042bcbcc9cb72b2.css" />




<link rel="stylesheet" href="https://accessibility.moda.gov.tw/Content/mystyle.css?v=0004" />
<link rel="stylesheet" href="https://accessibility.moda.gov.tw/css/new_style.css?v=0831" />
<link rel="stylesheet" href="https://accessibility.moda.gov.tw/css/new_version_css.css?v=0831" />



 




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
          <a title="申請案件" href="new.php"><i class="fas fa-pencil-alt"></i>&nbsp;申請案件</a>
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
<a href="#C" title="中央內容區塊" id="AC" accesskey="C" name="C" style="">:::</a>


          


<!-- Modal -->
<div class="modal show "  id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">重要訊息:交通事故案件便民服務網系統轉換公告</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <p>2019/11/08</p>

        <p>本局將於108年11月15日(星期五)進行「交通事故案件便民服務網作業平台」硬體伺服器及新舊系統轉換作業，新系統已修正驗證機制(當日驗證1次)，並提升網路頻寬，每案上傳總容量提高為80MB(動態影片因受限於手機網路頻寬，建議避免以手機上傳)，上線後發信郵件地址亦一併置換，屆時原舊系統受理案件，本局仍會依規處理，惟無法再發送處理結果通知信(如須查詢，可由首頁捷徑連結至舊系統查詢案件)，不便之處敬請見諒。感謝市民朋友對本市交通秩序的貢獻與努力，如對新系統使用上有任何問題或建議，歡迎不吝來電指教。</p>

        <p>新竹市警察局交通隊</p>

        <p>系統管理員:陳先生</p>

        <p>連絡電話:03-5250382</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">關閉</button>
      </div>
    </div>
  </div>
</div>
<style>.modal {
  }
</style>

<!--<div class="card mb-3">
  <div class="card-header">
    <b><h1>案件查詢</h1></b>
  </div>
  <div class="card-body">

    <form class="simple_form form-inline" action="query.php" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="✓"><input type="hidden" name="authenticity_token" value="i0l4hOWinm/w4zbWAFAnsh7dC3xft6WCdeCoSUiDRF+hmddzcog4b/B48OAL25c5FBTq6WsE6x+hb5v4xD07UQ==">
      <label for="search_email" class="col-sm-1 col-form-label">Email:</label>
      <div class="col-sm-3">
	<label for="search_email" hidden="hidden">編號 </label>
        <input class="form-control string required form-control form-control-sm" value="" autocomplete="off" autofocus="autofocus" required="required" aria-required="true" type="text" name="search[email]" id="search_email">
      </div>
      <label for="search_sn" class="col-sm-2 col-form-label">案件編號:</label>
      <div class="col-sm-3">
	<label for="search_sn" hidden="hidden"> 案件</label>
        <input class="form-control string required form-control form-control-sm" value="" autocomplete="off" required="required" aria-required="true" type="text" name="search[sn]" id="search_sn">
      </div>
      <div class="col-sm-2">
        <input type="submit" name="commit" value="查詢" class="btn btn-primary btn-sm" data-disable-with="查詢">
      </div>
</form>  </div>
</div>-->
<style>
table {
  border-collapse: collapse;
  border: 1px solid #cdcdcd;
  font: normal 12px arial;
  width: 100%;
}
td,
th {
  border: 1px solid #cdcdcd;
  padding: 8px;
}

@media only screen and (max-width: 760px),
  (min-device-width: 768px) and (max-device-width: 1024px) {
  table,
  thead,
  tbody,
  th,
  td,
  tr {
    display: block;
  }

  thead tr {
    position: absolute;
    top: -9999px;
    left: -9999px;
  }

  tr {
    border-bottom: 2px solid #690461;
  }

  td {
    border: none;
    border-bottom: 1px solid #eee;
    position: relative;
    padding-left: 30% !important;
    text-align: left !important;
  }

  td:before {
    position: absolute;
    top: 6px;
    left: 6px;
    width: 25%;
    padding-right: 10px;
    white-space: nowrap;
    font-weight: bold;
  }

  td:nth-of-type(1):before {
    content: "案件編號";
    color: #0e9893;
  }
  td:nth-of-type(2):before {
    content: "發生日期";
    color: #0e9893;
  }
  td:nth-of-type(3):before {
    content: "處理進度";
    color: #0e9893;
  }
  td:nth-of-type(4):before {
    content: "處理單位";
    color: #0e9893;
  }
  td:nth-of-type(5):before {
    content: "連絡資訊";
    color: #0e9893;
  }
}
</style>
<div class="table-responsive">
    <table border="1" style="width: 100%;margin:0;max-width: 100%;">
<?php
if( $row["id"] != "" ) { ?>
      <thead>
        <tr>
          <th scope="col">案件編號</th>
          <th scope="col">發生日期</th>
          <th scope="col">處理進度</th>
          <th scope="col">處理單位</th>
          <th scope="col">連絡資訊</th>
        </tr>
      </thead>
      <tbody>
          <tr>
	  <td><?=$row["sn"];?></td>
	  <td><?=$row["violated_at"];?></td>
	  <td><?php
	if( $row["state"] == "ready" || $row["state"] == "nready" )
		echo '處理中';
	else if( $row["state"] == "reviewing" )
		echo '審核中';
	else if( $row["state"] == "complete" )
		echo '已完成';
?></td>
	<td><?=$row["review_department"];?> / <?=$row["review_unit"];?></td>
	  <td><?=$rowd["dphone"];?></td>
          </tr>
<?php
if( $row["state"] == "complete" && $row["expose"] == "核發" ) { ?>
<tr><td colspan="5">
檔案下載如下:<br>
<?php
if( $row["pic1"] != "" ) {
echo '<li style="margin:10px;list-style-type:none;"><span style="float:left;width:150px;color:red;">'.$row["groupp1"].'</span><span style="flaot:right;"><a href="../../CarSystem/'.$row["pic1"].'" download target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 144-208 0c-35.3 0-64 28.7-64 64l0 144-48 0c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zM176 352l32 0c30.9 0 56 25.1 56 56s-25.1 56-56 56l-16 0 0 32c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-48 0-80c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24l-16 0 0 48 16 0zm96-80l32 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-32 0c-8.8 0-16-7.2-16-16l0-128c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16l0-64c0-8.8-7.2-16-16-16l-16 0 0 96 16 0zm80-112c0-8.8 7.2-16 16-16l48 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 32 32 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 48c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-64 0-64z"/></svg></a></span>';
                }
if( $row["pic2"] != "" ) {
                echo '<li style="margin:10px;list-style-type:none;"><span style="float:left;width:150px;color:blue;">'.$row["groupp2"].'</span><span style="flaot:right;"><a href="../../CarSystem/'.$row["pic2"].'" download target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 144-208 0c-35.3 0-64 28.7-64 64l0 144-48 0c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zM176 352l32 0c30.9 0 56 25.1 56 56s-25.1 56-56 56l-16 0 0 32c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-48 0-80c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24l-16 0 0 48 16 0zm96-80l32 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-32 0c-8.8 0-16-7.2-16-16l0-128c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16l0-64c0-8.8-7.2-16-16-16l-16 0 0 96 16 0zm80-112c0-8.8 7.2-16 16-16l48 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 32 32 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 48c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-64 0-64z"/></svg></a></span>';
                }
if( $row["pic3"] != "" ) {
echo '<li style="margin:10px;list-style-type:none;"><span style="float:left;width:150px;color:green;">'.$row["groupp3"].'</span><span style="flaot:right;"><a href="../../CarSystem/'.$row["pic3"].'" download target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 144-208 0c-35.3 0-64 28.7-64 64l0 144-48 0c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zM176 352l32 0c30.9 0 56 25.1 56 56s-25.1 56-56 56l-16 0 0 32c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-48 0-80c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24l-16 0 0 48 16 0zm96-80l32 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-32 0c-8.8 0-16-7.2-16-16l0-128c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16l0-64c0-8.8-7.2-16-16-16l-16 0 0 96 16 0zm80-112c0-8.8 7.2-16 16-16l48 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 32 32 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 48c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-64 0-64z"/></svg></a></span>';
                }
?>
</td></tr>
<?php } else { ?>
<tr><td colspan="5">
處理結果：<?=$row["expose"];?>
<br>
退件原因：<?=$row["expose_msg"];?>
</td></tr>
<?php } ?>
      </tbody>
<?php } else { ?>
	<p><h3>Email: <?=$_POST["search"]["email"];?> , 案件編號: <?=$_POST["search"]["sn"];?>!</p><p> <font color="red" size="4">您輸入的資料有誤 , 系統無法查詢 , 請重新輸入!</font></h3></p>
<?php } ?>
    </table>
  </div>

    </div>
  </div>
</div>
</div>

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
              地址：300016 新竹市北區經國路二段20號　服務電話：03-5250382<br />


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


  <script src="application-b063375bbdcb33e1912282f5eec73dfc2ffd036e4854867f245b12caf99fa036.js"></script>
    <script type="text/javascript">
    $(function(){
      $('input[name="agree"]').change(function() {
        if($(this).is(':checked')){
          $('.submit_statement').removeAttr('disabled')
        }else{
          $('.submit_statement').attr('disabled', 'disabled')
        }
      });
    })
    $(window).on('load',function(){
        //$('#exampleModalCenter').modal('show');
    });
  </script>



</body>




</html>
