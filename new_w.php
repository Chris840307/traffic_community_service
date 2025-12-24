<?php
ini_set('memory_limit', '4096M');
ini_set('max_input_var',5000);
session_start();
require_once("vendor/phpmailer/phpmailer/src/Exception.php");
require_once("vendor/phpmailer/phpmailer/src/PHPMailer.php");
require_once("vendor/phpmailer/phpmailer/src/SMTP.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

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

if( $ip == '211.21.98.136' ) {
echo '<script>alert("連線逾時,請重新輸入申請資料!");</script>';
header( "Location: new.php" );
exit;
}

$nowtime = date("Y-m-d H:i:s");

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

<link rel="stylesheet" href="https://accessibility.moda.gov.tw/Content/mystyle.css?v=0004" />
<link rel="stylesheet" href="https://accessibility.moda.gov.tw/css/new_style.css?v=0831" />
<link rel="stylesheet" href="https://accessibility.moda.gov.tw/css/new_version_css.css?v=0831" />
</head>

<?php
$file1_name == "";
$file1_name = $_FILES["case"]["name"]["case_attachments_attributes"][0]["file"];
$file1_type = $_FILES["case"]["type"]["case_attachments_attributes"][0]["file"];
$file1_tmp = $_FILES["case"]["tmp_name"]["case_attachments_attributes"][0]["file"];
$file1_size = $_FILES["case"]["size"]["case_attachments_attributes"][0]["file"];
$file1_err = $_FILES["case"]["error"]["case_attachments_attributes"][0]["file"];
$file2_name == "";
$file2_name = $_FILES["case"]["name"]["case_attachments_attributes"][1]["file"];
$file2_type = $_FILES["case"]["type"]["case_attachments_attributes"][1]["file"];
$file2_tmp = $_FILES["case"]["tmp_name"]["case_attachments_attributes"][1]["file"];
$file2_size = $_FILES["case"]["size"]["case_attachments_attributes"][1]["file"];
$file2_err = $_FILES["case"]["error"]["case_attachments_attributes"][1]["file"];
$username = $_POST["case"]["name"];
$email = trim($_POST["case"]["email"]);
$idnumber = $_POST["case"]["id_number"];
$username2 = $_POST["case"]["name2"];
$idnumber2 = $_POST["case"]["id_number2"];
$cam_place = $_POST["case"]["place"];
$phone = $_POST["case"]["phone"];
$phone2 = $_POST["case"]["phone2"];
$violated_at = $_POST["case"]["violated_at"];
if( $_POST["case"]["group3"] == "1" )
	$category_name = 'A3';
if( $_POST["case"]["group2"] == "1" )
	$category_name = 'A2';
if( $_POST["case"]["group1"] == "1" )
	$category_name = 'A1';

$groupp1=NULL;
$groupp2=NULL;
$groupp3=NULL;
if( $_POST["case"]["groupp1"] == "1" )
	$groupp1 = '現場圖';
if( $_POST["case"]["groupp2"] == "1" )
	$groupp2 = '事故照片';
if( $_POST["case"]["groupp3"] == "1" )
	$groupp3 = '初步分析研判表';

if( !$_SESSION["cstart"] ) {
        echo '<script>document.location.href="new.php";</script>';
        exit;
}

if( $username != $username2 && $file1_name == "" ) {
        echo '<script>alert(\'非當事人申請,請上傳委託書...\');history.go(-1);</script>';
        echo '<script>document.location.href="new.php";</script>';
        exit;
}

if( $username == $username2 && $file2_name == "" ) {
        echo '<script>alert(\'當事人申請,請上傳身分證正面影本...\');history.go(-1);</script>';
        echo '<script>document.location.href="new.php";</script>';
        exit;
}

if( $file1_name != "" && $file2_name != "" ) {
        echo '<script>alert(\'系統異常,請與技術人員聯繫...\');history.go(-1);</script>';
        echo '<script>document.location.href="new.php";</script>';
        exit;
}

if( $groupp1 == "" && $groupp2 == "" && $groupp3 == "" ) {
	echo '<script>alert(\'尚未勾選申請項\');history.go(-1);</script>';
	echo '<script>document.location.href="new.php";</script>';
	exit;
}

if( $groupp1 != NULL && $groupp2 != NULL && $groupp3 != NULL ) {
	        echo '<script>alert(\'無法同時申請3項,請清除電腦暫存,重新申請\');history.go(-1);</script>';
		echo '<script>document.location.href="new.php";</script>';
	        exit;
}



if( !$_SESSION["success"] ) {
$sn_date=date("Ymd");
$check_sn = $pdo->query('select `id`,`sn` from `cases` where `sn` like "%'.$sn_date.'%" order by `sn` desc limit 1');
$sn_sum = $check_sn->rowCount();
if( $sn_sum == 0 ) {
                $sn = 'T'.$sn_date.'00001';
                $pic_sn = '00001';
} else {
                $row_sn = $check_sn->fetch(PDO::FETCH_ASSOC);
                        $sn = 'T'.(substr($row_sn["sn"],1,13)+1);
                $pic_sn = sprintf("%05d",substr($row_sn["sn"],9,5)+1);
}

$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    $pwd = implode($pass);

$data = [
    'id' => NULL,
    'sn' => $sn,
    'pwd' => $pwd,
    'DetectLocation' => NULL, 
    'category_name' => $category_name,
    'username' => $username,
    'email' => $email,
    'idnumber' => $idnumber,
    'phone' => $phone,
    'username2' => $username2,
    'idnumber2' => $idnumber2,
    'phone2' => $phone2,
    'violated_at' => $violated_at,
    'state' => 'nready',
    'fromip' => $ip,
    'groupp1' => $groupp1,
    'groupp2' => $groupp2,
    'groupp3' => $groupp3,
    'cam_place' => $cam_place,
];

    $error=0;
if( mb_strlen($idnumber) != 10 )
	$error = 1;
if( mb_strlen($phone) <= 5 )
	$error = 1;
if( $error == 0 ) {
$sql = "INSERT INTO cases ( id, sn, pwd, DetectLocation, category_name, username, email, idnumber, phone, username2, idnumber2, phone2, violated_at, state, fromip, groupp1,groupp2,groupp3,cam_place ) VALUES (:id, :sn, :pwd, :DetectLocation, :category_name, :username, :email, :idnumber, :phone, :username2, :idnumber2, :phone2, :violated_at, :state, :fromip, :groupp1, :groupp2, :groupp3, :cam_place )";
$pdo->prepare($sql)->execute($data);

        if( $file1_tmp != "" ) {
$media_sub=array(
        "image/jpeg"=>"jpg",
        "image/png"=>"png",
        "image/gif"=>"gif",
        "image/bmp"=>"bmp"
);
                if(!file_exists('/var/www/html/CarSystem/traffic/'.date("Y-m-d")))
                        mkdir('/var/www/html/CarSystem/traffic/'.date("Y-m-d"));
                $pic1_name = date("YmdHis").'_'.$sn.'.'.$media_sub[$file1_type];
                move_uploaded_file($file1_tmp, '/var/www/html/CarSystem/traffic/'.date("Y-m-d").'/'.$pic1_name);
        	$pic1='traffic/'.date("Y-m-d").'/'.$pic1_name;
		$pdo->prepare("UPDATE `cases` set `pic5`=? where `sn`=?")->execute(array($pic1,$sn));
        }

        if( $file2_tmp != "" ) {
	$media_sub=array(
        "image/jpeg"=>"jpg",
        "image/png"=>"png",
        "image/gif"=>"gif",
        "image/bmp"=>"bmp"
	);
                if(!file_exists('/var/www/html/CarSystem/traffic/'.date("Y-m-d")))
                        mkdir('/var/www/html/CarSystem/traffic/'.date("Y-m-d"));
                $pic1_name = date("YmdHis").'_'.$sn.'.'.$media_sub[$file2_type];
		$pic2_name = date("YmdHis").'_'.$sn.'_w.'.$media_sub[$file2_type];
                move_uploaded_file($file2_tmp, '/var/www/html/CarSystem/traffic/'.date("Y-m-d").'/'.$pic1_name);
		$image_path = '/var/www/html/CarSystem/traffic/'.date("Y-m-d").'/'.$pic1_name;
            switch ($media_sub[$file2_type]) {
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($image_path);
                    break;
                case 'png':
                    $image = imagecreatefrompng($image_path);
                    // 保持透明度
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                    break;
                case 'gif':
                    $image = imagecreatefromgif($image_path);
                    break;
                case 'bmp':
                    $image = imagecreatefrombmp($image_path); // 需要 PHP 7.2+ 或安裝相關函式庫
                    break;
                default:
                    die("不支援的圖片格式.");
            }
if ($image) {
$text_to_add='個人資料嚴禁外洩';
$font_path = '/var/www/html/CarSystem/NotoSansTC-VariableFont_wght.ttf';
                $text_color = imagecolorallocatealpha($image, 255, 255, 255, 80); // 黑色，約 63% 透明度

                // 取得圖片尺寸
                $image_width = imagesx($image);
                $image_height = imagesy($image);

                // --- 根據圖片尺寸計算文字大小 ---
                // 這裡我們將文字大小設定為圖片寬度的某個百分比
                // 您可以調整這個百分比來控制文字大小
                $font_size_percentage = 0.05; // 例如，文字大小是圖片寬度的 5%
                $font_size = max(10, $image_width * $font_size_percentage); // 確保文字大小至少為 10 像素

                // 設定文字角度
                $angle = -30; // 文字角度 (例如 -30 度，逆時針方向)

                // 計算文字的邊界框，用於確定文字的實際大小和間距
                // imagettfbbox(size, angle, fontfile, text)
                $text_bbox = imagettfbbox($font_size, $angle, $font_path, $text_to_add);

                // --- 檢查 imagettfbbox 是否成功並獲取有效的邊界框 ---
                if ($text_bbox === false) {
                     die("錯誤：無法計算文字邊界框。請檢查字型檔案是否有效或損壞。");
                }

                $text_width = $text_bbox[2] - $text_bbox[0]; // 右下角 x - 左下角 x
                $text_height = $text_bbox[1] - $text_bbox[7]; // 左下角 y - 左上角 y (注意 Y 軸方向)

                // 設定文字之間的間距因子
                $spacing_factor_x = 1.5; // 水平間距是文字寬度的 1.5 倍
                $spacing_factor_y = 2;   // 垂直間距是文字高度的 2 倍

                // 根據計算出的文字尺寸和間距因子計算步長
                $step_x = $text_width * $spacing_factor_x;
                $step_y = $text_height * $spacing_factor_y;

                // --- 檢查計算出的步長是否有效 ---
                if ($step_x <= 0 || $step_y <= 0) {
                    die("錯誤：計算出的文字步長無效。請檢查字型檔案和設定。Step X: " . $step_x . ", Step Y: " . $step_y . ", Text Width: " . $text_width . ", Text Height: " . $text_height);
                }


                // 從圖片左上角稍微外面開始繪製，並向右下角延伸，以確保覆蓋整個圖片
                // 調整起始點和結束點的範圍，確保所有區域都被文字覆蓋
                // 這裡的偏移量可以根據文字大小和角度進行微調，以確保完全覆蓋
                $offset_x = abs($text_bbox[0]) + abs($text_bbox[6]); // 考慮文字邊界框的左右偏移
                $offset_y = abs($text_bbox[1]) + abs($text_bbox[7]); // 考慮文字邊界框的上下偏移

                $start_x = -$offset_x;
                $start_y = -$offset_y;
                $end_x = $image_width + $offset_x;
                $end_y = $image_height + $offset_y;


                // 迴圈繪製文字
                for ($y = $start_y; $y < $end_y; $y += $step_y) {
                    for ($x = $start_x; $x < $end_x; $x += $step_x) {
                         // imagettftext(image, size, angle, x, y, color, fontfile, text)
                         // 注意：imagettftext 的 Y 座標是文字的基線位置
                        imagettftext($image, $font_size, $angle, $x, $y, $text_color, $font_path, $text_to_add);
                    }
                }
		$output_path = '/var/www/html/CarSystem/traffic/'.date("Y-m-d").'/'.$pic2_name;
                switch ($media_sub[$file2_type]) {
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($image, $output_path);
                        break;
                    case 'png':
                        imagepng($image, $output_path);
                        break;
                    case 'gif':
                        imagegif($image, $output_path);
                        break;
                    case 'bmp':
                         imagebmp($image, $output_path); // 需要 PHP 7.2+ 或安裝相關函式庫
                        break;
                }
		imagedestroy($image);
		$pic1='traffic/'.date("Y-m-d").'/'.$pic2_name;
		} else {
                $pic1='traffic/'.date("Y-m-d").'/'.$pic1_name;
		}
                $pdo->prepare("UPDATE `cases` set `pic5`=? where `sn`=?")->execute(array($pic1,$sn));
        }


$_SESSION["success"]=true;
}
}
?>
<body>
  
<a href="#content" id="gotocenter" title="移到主要內容" class="sr-only sr-onlyp;focusable" >跳到主要內容</a>

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
<a href="" title="中央內容區塊" id="AC" accesskey="C" name="C" style="">:::</a>


          


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
if( !$_SESSION["mailsend"] && $_SESSION["success"] == true ) {
echo '<div class="card mb-3">
  <div class="card-header">
    <b><h1>案件送出成功</h1></b>
  </div>
  <div class="card-body">

      <div class="col-sm-12">
	 <h3> 案件編號&nbsp;: &nbsp;'.$sn.' </h3>
<br>
「確認信中含有密碼，請妥善保存」
      </div>
      <div class="col-sm-12" style="padding-top: 30px;">
      <button type="button" class="btn btn-primary continue" onclick="window.location.href=\'new.php\';">繼續申請</button>
      <button type="button" class="btn btn-success" onclick="window.location.href=\'search.php\';">查詢案件</button>
      </div>
  </div>
</div>';
$txt = "";
$txt .= "臺端您好：";
$txt .= "<br><br>";
$txt .= '此為新竹市警察局-交通事故案件便民服務網案件確認信，您申請的交通事故資料案件編號 : '.$sn.' 已提交成功，我們將儘速處理。另您可依據案件編號 : '.$sn.' , 輸入密碼: '.$pwd.' 與電子郵件地址, 進入<a href="https://tra2.hccp.gov.tw/traffic/search.php">此處</a> 查詢處理進度。';
$txt .= "<br>「確認信中含有密碼，請妥善保存」<br>";
$txt .= '感謝您撥冗確認';
$txt .= "<br><br>";
$txt .= "本信件為自動派送，請勿直接回覆本信件，謝謝！";
$txt .= "<br><br>";
$txt .= "新竹市警察局交通警察隊";
$txt .= '<br><br>';
$txt .= '注意事項:';
$txt .= '<br>';
$txt .= '各警察機關受理前項申請案，處理期限如下:';
$txt .= '<br>';
$txt .= '1.現場圖及現場照片:受理申請後10日內製作完成並通知領件。';
$txt .= '<br>';
$txt .= '2.初步分析研判表：受理申請後25日內製作完成並通知領件；案情複雜者，得延長至30日。';
$txt .= '<br>';
$txt .= '3.同時申請初步分析研判表、現場圖或現場照片資料者，處理期限依申請初步分析研判表期限辦理。';
$txt .= '<br>';
$txt .= '4.案件如「退件」，請重新申請。';

$mail = new PHPMailer();
$mail->IsSMTP(); //設定使用SMTP方式寄信
$mail->SMTPAuth = true; //設定SMTP需要驗證
$mail->Host = "dm.cloudmax.com.tw"; //Gamil的SMTP主機
$mail->Port = 25; //Gamil的SMTP主機的埠號(Gmail為465)。
$mail->CharSet = "utf-8"; //郵件編碼
$mail->Username = 'ct18447@dm.cloudmax.com.tw';
$mail->Password = 'CB!c22y^';
$mail->setFrom('ct18447@dm.cloudmax.com.tw', '新竹市交通事故案件便民服務網');
$mail->ClearAddresses();
$mail->addAddress($email,'系統自動通知');
$mail->addBCC("tra@mani.tw", "系統紀錄");
$mail->isHTML(true);
$mail->Subject = "立案確認信: 交通事故案件便民服務網";
$mail->Body = $txt;
$mail->send();
$_SESSION["mailsend"]=true;
unset($_SESSION["cstart"]);
} else {
	unset( $_SESSION["mailsend"] );
	unset( $_SESSION["success"] );

	echo '<script>document.location.href="new.php";</script>';
	exit;
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
