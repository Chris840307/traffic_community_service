<?php
session_start();
include "function.php";

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


<?php
/*
$myfile = fopen("test.txt", "w") or die("Unable to open file!");
fwrite($myfile, json_encode($_POST));
fclose($myfile);
if( substr(str_replace('/','-',$_POST["case"]["created_at"]),0,10).' '.substr($_POST["case"]["created_at"],11,2).':'.substr($_POST["case"]["created_at"],14,2).':01' <= substr(str_replace('/','-',$_POST["case"]["violated_at"]),0,10).' '.substr($_POST["case"]["violated_at"],11,2).':'.substr($_POST["case"]["violated_at"],14,2).':00' ) {
	        echo '<script>alert("檢舉日期不能小於違規日期!");</script>';
		echo '<script>document.location.href="cases_new.php";</script>';
	        exit;
}
 */
$username = $_POST["case"]["name"];
$email = $_POST["case"]["email"];
$idnumber = $_POST["case"]["id_number"];
$addr = $_POST["case"]["contact_address"];
$phone = $_POST["case"]["phone"];
$violated_at_date = substr(str_replace('/','-',$_POST["case"]["violated_at"]),0,10);
$violated_at_hour = substr($_POST["case"]["violated_at"],11,2);
$violated_at_min = substr($_POST["case"]["violated_at"],14,2); 
$created_at = substr(str_replace('/','-',$_POST["case"]["created_at"]),0,10).' '.substr($_POST["case"]["created_at"],11,2).':'.substr($_POST["case"]["created_at"],14,2).':00';
$car_type_code = $_POST["case"]["car_type_code"];
$first_car_number = $_POST["case"]["first_car_number"];
$last_car_number = $_POST["case"]["last_car_number"];
$area_district = $_POST["case"]["area_district"];
$area_code = $_POST["case"]["area_code"];
$addr1 = $_POST["case"]["addr1"];
$addr2 = $_POST["case"]["addr2"];
$addr3 = $_POST["case"]["addr3"];
$addr4 = $_POST["case"]["addr4"];
$addr5 = $_POST["case"]["addr5"];
$addr_detail = $_POST["case"]["addr_detail"];
$illegality_code = $_POST["case"]["illegality_code"];
$illegality_details = $_POST["case"]["illegality_details"];
$file1_name = $_FILES["case"]["name"]["case_attachments_attributes"][0]["file"];
$file1_type = $_FILES["case"]["type"]["case_attachments_attributes"][0]["file"];
$file1_tmp = $_FILES["case"]["tmp_name"]["case_attachments_attributes"][0]["file"];
$file1_size = $_FILES["case"]["size"]["case_attachments_attributes"][0]["file"];
$file2_name = $_FILES["case"]["name"]["case_attachments_attributes"][1]["file"];
$file2_type = $_FILES["case"]["type"]["case_attachments_attributes"][1]["file"];
$file2_tmp = $_FILES["case"]["tmp_name"]["case_attachments_attributes"][1]["file"];
$file2_size = $_FILES["case"]["size"]["case_attachments_attributes"][1]["file"];
$file3_name = $_FILES["case"]["name"]["case_attachments_attributes"][2]["file"];
$file3_type = $_FILES["case"]["type"]["case_attachments_attributes"][2]["file"];
$file3_tmp = $_FILES["case"]["tmp_name"]["case_attachments_attributes"][2]["file"];
$file3_size = $_FILES["case"]["size"]["case_attachments_attributes"][2]["file"];
$file4_name = $_FILES["case"]["name"]["case_attachments_attributes"][3]["file"];
$file4_type = $_FILES["case"]["type"]["case_attachments_attributes"][3]["file"];
$file4_tmp = $_FILES["case"]["tmp_name"]["case_attachments_attributes"][3]["file"];
$file4_size = $_FILES["case"]["size"]["case_attachments_attributes"][3]["file"];
$file5_name = $_FILES["case"]["name"]["case_attachments_attributes"][4]["file"];
$file5_type = $_FILES["case"]["type"]["case_attachments_attributes"][4]["file"];
$file5_tmp = $_FILES["case"]["tmp_name"]["case_attachments_attributes"][4]["file"];
$file5_size = $_FILES["case"]["size"]["case_attachments_attributes"][4]["file"];

//$myfile = fopen("test.txt", "w") or die("Unable to open file!");
//fwrite($myfile, json_encode($_FILES));
//fwrite($myfile, $file1_tmp);
//fwrite($myfile, $file1_tmp);
//fclose($myfile);

if( $area_district == "北區" ) {
	$auto_department = "第一分局";
} else if( $area_district == "東區" ) {
	$auto_department = "第二分局";
} else if( $area_district == "香山區" ) {
	$auto_department = "第三分局";
} else {
	$auto_department = "交通警察隊";
}

$sn_date=date("Ymd");
$check_sn = $pdo->query('select `id`,`sn` from `cases` where `sn` like "%'.$sn_date.'%" order by `sn` desc limit 1');
$sn_sum = $check_sn->rowCount();
if( $sn_sum == 0 ) {
                $sn = 'C'.$sn_date.'00001';
                $pic_sn = '00001';
} else {
                $row_sn = $check_sn->fetch(PDO::FETCH_ASSOC);
                        $sn = 'C'.(substr($row_sn["sn"],1,13)+1);
                $pic_sn = sprintf("%05d",substr($row_sn["sn"],9,5)+1);
}

if( $_POST["case"]["category_name"] == "民眾親送" )
	$category_name = "民眾親送";
else
	$category_name = "他轄函轉";

$user_id = 'm'.$row_user["id"];

$media_sub=array(
        "video/mp4"=>"mp4",
        "image/jpeg"=>"jpg",
        "image/png"=>"png",
        "video/quicktime"=>"mov",
        "video/avi"=>"avi",
        "image/gif"=>"gif",
        "image/bmp"=>"bmp",
	"video/wmv"=>"wmv"
);

if( $area_district != "" && $user_id != "" ) {
	$error="";
	if( $file1_tmp != "" ) {
        //$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        //$detectedType = exif_imagetype($file1_tmp);
        //$error = !in_array($detectedType, $allowedTypes);
        if( $error == "" ) {
                if(!file_exists('/var/www/html/CarSystem/new/'.date("Y-m-d")))
                        mkdir('/var/www/html/CarSystem/new/'.date("Y-m-d"));
                if(!file_exists('/var/www/html/CarSystem/new/'.date("Y-m-d").'/0'))
                        mkdir('/var/www/html/CarSystem/new/'.date("Y-m-d").'/0');
		$pic1_name = date("YmdHis").$user_id.'_1.'.$media_sub[$file1_type];
                move_uploaded_file($file1_tmp, '/var/www/html/CarSystem/new/'.date("Y-m-d").'/0/'.$pic1_name);
        $pic1='new/'.date("Y-m-d").'/0/'.$pic1_name;
	}} else {
	$pic1 = NULL;
	}

        if( $file2_tmp != "" ) {
        //$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        //$detectedType = exif_imagetype($file2_tmp);
        //$error = !in_array($detectedType, $allowedTypes);
        if( $error == "" ) {
                if(!file_exists('/var/www/html/CarSystem/new/'.date("Y-m-d")))
                        mkdir('/var/www/html/CarSystem/new/'.date("Y-m-d"));
                if(!file_exists('/var/www/html/CarSystem/new/'.date("Y-m-d").'/0'))
                        mkdir('/var/www/html/CarSystem/new/'.date("Y-m-d").'/0');
                $pic2_name = date("YmdHis").$user_id.'_2.'.$media_sub[$file2_type];
                move_uploaded_file($file2_tmp, '/var/www/html/CarSystem/new/'.date("Y-m-d").'/0/'.$pic2_name);
        $pic2='new/'.date("Y-m-d").'/0/'.$pic2_name;
        }} else {
	$pic2=NULL;
	}

        if( $file3_tmp != "" ) {
        //$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        //$detectedType = exif_imagetype($file3_tmp);
        //$error = !in_array($detectedType, $allowedTypes);
        if( $error == "" ) {
                if(!file_exists('/var/www/html/CarSystem/new/'.date("Y-m-d")))
                        mkdir('/var/www/html/CarSystem/new/'.date("Y-m-d"));
                if(!file_exists('/var/www/html/CarSystem/new/'.date("Y-m-d").'/0'))
                        mkdir('/var/www/html/CarSystem/new/'.date("Y-m-d").'/0');
                $pic3_name = date("YmdHis").$user_id.'_3.'.$media_sub[$file3_type];
                move_uploaded_file($file3_tmp, '/var/www/html/CarSystem/new/'.date("Y-m-d").'/0/'.$pic3_name);
        $pic3='new/'.date("Y-m-d").'/0/'.$pic3_name;
        }} else {
	$pic3=NULL;
	}

        if( $file4_tmp != "" ) {
        //$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        //$detectedType = exif_imagetype($file4_tmp);
        //$error = !in_array($detectedType, $allowedTypes);
        if( $error == "" ) {
                if(!file_exists('/var/www/html/CarSystem/new/'.date("Y-m-d")))
                        mkdir('/var/www/html/CarSystem/new/'.date("Y-m-d"));
                if(!file_exists('/var/www/html/CarSystem/new/'.date("Y-m-d").'/0'))
                        mkdir('/var/www/html/CarSystem/new/'.date("Y-m-d").'/0');
                $pic4_name = date("YmdHis").$user_id.'_4.'.$media_sub[$file4_type];
                move_uploaded_file($file4_tmp, '/var/www/html/CarSystem/new/'.date("Y-m-d").'/0/'.$pic4_name);
        $pic4='new/'.date("Y-m-d").'/0/'.$pic4_name;
        }} else {
	$pic4=NULL;
	}

        if( $file5_tmp != "" ) {
        //$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        //$detectedType = exif_imagetype($file5_tmp);
        //$error = !in_array($detectedType, $allowedTypes);
        if( $error == "" ) {
                if(!file_exists('/var/www/html/CarSystem/new/'.date("Y-m-d")))
                        mkdir('/var/www/html/CarSystem/new/'.date("Y-m-d"));
                if(!file_exists('/var/www/html/CarSystem/new/'.date("Y-m-d").'/0'))
                        mkdir('/var/www/html/CarSystem/new/'.date("Y-m-d").'/0');
                $pic5_name = date("YmdHis").$user_id.'_5.'.$media_sub[$file5_type];
                move_uploaded_file($file5_tmp, '/var/www/html/CarSystem/new/'.date("Y-m-d").'/0/'.$pic5_name);
        $pic5='new/'.date("Y-m-d").'/0/'.$pic5_name;
        }} else {
	$pic5=NULL;
	}

$state = 'nready';
$data = [
    'id' => NULL,
    'sn' => $sn,
    'username' => $username,
    'email' => $email,
    'idnumber' => $idnumber,
    'addr' => $addr,
    'phone' => $phone,
    'violated_at_date' => $violated_at_date,
    'violated_at_hour' => $violated_at_hour,
    'violated_at_min' => $violated_at_min,
    'violated_at' => $violated_at_date.' '.$violated_at_hour.':'.$violated_at_min.':00',
    'car_type_code' => $car_type_code,
    'first_car_number' => mb_strtoupper(trim($first_car_number)),
    'last_car_number' => mb_strtoupper(trim($last_car_number)),
    'full_car_number' => mb_strtoupper(trim($first_car_number)).'-'.mb_strtoupper(trim($last_car_number)),
    'area_district' => $area_district,
    'area_code' => $area_code,
    'addr1' => $addr1,
    'addr2' => $addr2,
    'addr3' => $addr3,
    'addr4' => $addr4,
    'addr5' => $addr5,
    'addr_detail' => $addr_detail,
    'illegality_code' => $illegality_code,
    'illegality_details' => $illegality_details,
    'auto_department' => $auto_department,
    'category_name' => $category_name,
    'pic1' => $pic1,
    'pic2' => $pic2,
    'pic3' => $pic3,
    'pic4' => $pic4,
    'pic5' => $pic5,
    'pic1_type' => $file1_type,
    'pic2_type' => $file2_type,
    'pic3_type' => $file3_type,
    'pic4_type' => $file4_type,
    'pic5_type' => $file5_type,
    'pic1_size' => $file1_size,
    'pic2_size' => $file2_size,
    'pic3_size' => $file3_size,
    'pic4_size' => $file4_size,
    'pic5_size' => $file5_size,
    'state' => $state,
    'fromip' => $ip,
    'created_at' => $created_at,
    'cid' => $row_user["id"],
];

$sql = "INSERT INTO cases ( id, sn, username, email, idnumber, addr, phone, violated_at_date, violated_at_hour, violated_at_min, violated_at, car_type_code, first_car_number, last_car_number, full_car_number, area_district, area_code, addr1, addr2, addr3, addr4, addr5, addr_detail, illegality_code, illegality_details, auto_department, category_name, pic1, pic2, pic3, pic4, pic5, pic1_type, pic2_type, pic3_type, pic4_type, pic5_type, pic1_size, pic2_size, pic3_size, pic4_size, pic5_size, state, fromip, created_at, cid ) VALUES (:id, :sn, :username, :email, :idnumber, :addr,  :phone,  :violated_at_date,  :violated_at_hour,  :violated_at_min, :violated_at, :car_type_code,  :first_car_number,  :last_car_number, :full_car_number, :area_district,  :area_code,  :addr1,  :addr2,  :addr3,  :addr4,  :addr5,  :addr_detail,  :illegality_code,  :illegality_details, :auto_department, :category_name, :pic1, :pic2, :pic3, :pic4, :pic5, :pic1_type, :pic2_type, :pic3_type, :pic4_type, :pic5_type, :pic1_size, :pic2_size, :pic3_size, :pic4_size, :pic5_size, :state, :fromip, :created_at, :cid )";
$buff = $pdo->prepare($sql)->execute($data);
}
if( $buff ) {
                echo '<script>alert("案件新增成功!");</script>';
                echo '<script>document.location.href="cases_new.php";</script>';
                exit;
}
?>
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
          <a title="開始檢舉" href="/new/new.php"><i class="fas fa-pencil-alt"></i>&nbsp;開始檢舉</a>
          <a title="案件查詢" href="/new/search.php"><i class="fas fa-search"></i>&nbsp;案件查詢</a>
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

<?php
if( $_GET["email"] != "" ) {
$result = $pdo->query("select `id`,`email`,`sn` from `cases` where `email`='".$_GET["email"]."' order by created_at desc limit 1");
$row = $result->fetch(PDO::FETCH_ASSOC);
if( $row["id"] != "" ) {
echo '<div class="card mb-3">
  <div class="card-header">
    <b><h1>案件送出成功</h1></b>
  </div>
  <div class="card-body">

      <div class="col-sm-12">
	 <h3> 案件編號&nbsp;: &nbsp;'.$row["sn"].' </h3>
      </div>
      <div class="col-sm-12" style="padding-top: 30px;">
      <button type="button" class="btn btn-primary continue" onclick="window.location.href=\'new.php\';">繼續檢舉</button>
      <button type="button" class="btn btn-success" onclick="window.location.href=\'search.php\';">查詢案件</button>
      </div>
  </div>
</div>';
}
}
?>


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
