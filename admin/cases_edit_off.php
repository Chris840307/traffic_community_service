<?php
include "function.php";
require_once("../vendor/phpmailer/phpmailer/src/Exception.php");
require_once("../vendor/phpmailer/phpmailer/src/PHPMailer.php");
require_once("../vendor/phpmailer/phpmailer/src/SMTP.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if( $_POST["state"] == "complete" && $_POST["id"] != "" ) {
               $msg = "從案件審核 > 案件結束(".$row_user["name"].")";
               $complete_date=date("Y-m-d H:i:s");
	       $cdate = date("Y-m-d H:i:s");
               $pdo->prepare("UPDATE `cases` set `complete_date`=?,`state`=?,`approve_user_id`=? where `id`=?")->execute(array($complete_date,'complete',$row_user["id"],intval($_POST["id"])));
               $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));


                $result = $pdo->query("select * from `cases` where `id`='".$_POST["id"]."'");
                $row = $result->fetch(PDO::FETCH_ASSOC);
                $resultd = $pdo->query("select * from `department` limit 1");
                $rowd = $resultd->fetch(PDO::FETCH_ASSOC);
if( $row["expose"] == "核發" ) {
$txt = "";
$txt .= "臺端您好：";
$txt .= "<br><br>";
$txt .= '有關您申請的交通事故資料「案件編號：'.$row["sn"].'」已審核完畢，請至 <a href="https://tra2.hccp.gov.tw/traffic/search.php">此處</a> 輸入原查詢密碼下載附加檔案瀏覽。';
$txt .= "<br>";
$txt .= '承辦員警：'.$row["review_username"];
$txt .= "<br>";
$txt .= '連絡電話：'.$rowd["dphone"].' ('.$row["review_unit"].'電話)';
$txt .= "<br>";
$txt .= "新竹市警察局交通警察隊 敬復";
$txt .= "<br>";
$txt .= "注意事項：";
$txt .= "<br>";
$txt .= '開啟雲端檔案前，請先確認是否已下載Acrobat Reader軟體(需要5.0以上版本) 方能閱讀檔案內容。';
$txt .= '<br><br>';
} else {
$txt = "";
$txt .= "臺端您好：";
$txt .= "<br><br>";
$txt .= '有關您申請的交通事故資料「案件編號：'.$row["sn"].'」處理情形如下：';
$txt .= "<br>";
$txt .= '處理結果：退件';
$txt .= "<br>";
$txt .= '退件原因：'.$row["expose_msg"];
$txt .= "<br><br>";
$txt .= '承辦員警：'.$row["review_username"];
$txt .= "<br>";
$txt .= '連絡電話：'.$rowd["dphone"].' ('.$row["review_unit"].'電話)';
$txt .= "<br>";
$txt .= "新竹市警察局交通警察隊 敬復";
}
$mail = new PHPMailer();
$mail->CharSet = 'UTF-8';
$mail= new PHPMailer(); //建立新物件
$mail->IsSMTP(); //設定使用SMTP方式寄信
$mail->SMTPAuth = true; //設定SMTP需要驗證
$mail->Host = "dm.cloudmax.com.tw"; //Gamil的SMTP主機
$mail->Port = 25; //Gamil的SMTP主機的埠號(Gmail為465)。
$mail->CharSet = "utf-8"; //郵件編碼
$mail->Username = 'ct18447@dm.cloudmax.com.tw';
$mail->Password = 'CB!c22y^';
$mail->setFrom('ct18447@dm.cloudmax.com.tw', '新竹市交通事故案件便民服務網');
$mail->ClearAddresses();
$mail->addAddress($row["email"],'系統自動寄發');
$mail->isHTML(true);
$mail->Subject = "申請結果回覆:交通事故案件便民服務網";
$mail->Body = $txt;
$mail->send();

               echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=reviewing&n";</script>';
               exit;
        }
if( $_POST["_method"] == "put" && $_POST["case"]["expose"] == "1" && $_POST["authenticity_token"] == "dtQ0kLcyBcoTBTfeopTr84sCjzI8L6Vb52QR2RrFbdb/HrWpAx49ZA8tu8Mof/VoQGweX+BmKtHA8TXwslShtw==" ) {
        if(!file_exists('../../CarSystem/traffic/')) {
        mkdir('../../CarSystem/traffic',0777);
        }
        if(!file_exists('../../CarSystem/traffic/'.date("Y-m-d").'/')) {
                mkdir('../../CarSystem/traffic/'.date("Y-m-d"),0777);
        }
        if(!file_exists('../../CarSystem/traffic/'.date("Y-m-d").'/'.$_POST["id"].'/')) {
	        mkdir('../../CarSystem/traffic/'.date("Y-m-d").'/'.$_POST["id"],0777);
	}
        $pdo->prepare("UPDATE `cases` set `expose`=?,`state`=?,`review_username`=?,`review_department`=?,`review_unit`=? where `id`=?")->execute(array('核發','reviewing',$row_user["name"],$row_user["department"],$row_user["unit"],intval($_POST["id"])));
               $msg = "從案件處理 > 案件審核(".$row_user["name"].")";
               $cdate = date("Y-m-d H:i:s");
               $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
	if( $_FILES["pic3"]["name"] != "" ) {
		$pic3_name='3_'.$_FILES["pic3"]["name"];
		move_uploaded_file($_FILES["pic3"]["tmp_name"], '/var/www/html/CarSystem/traffic/'.date("Y-m-d").'/'.$_POST["id"].'/'.$pic3_name);
		$pic3='traffic/'.date("Y-m-d").'/'.$_POST["id"].'/'.$pic3_name;
		$pdo->prepare("UPDATE `cases` set `pic3`=? where `id`=?")->execute(array($pic3,intval($_POST["id"])));
	}
        if( $_FILES["pic2"]["name"] != "" ) {
                $pic2_name='2_'.$_FILES["pic2"]["name"];
                move_uploaded_file($_FILES["pic2"]["tmp_name"], '/var/www/html/CarSystem/traffic/'.date("Y-m-d").'/'.$_POST["id"].'/'.$pic2_name);
                $pic2='traffic/'.date("Y-m-d").'/'.$_POST["id"].'/'.$pic2_name;
		$pdo->prepare("UPDATE `cases` set `pic2`=? where `id`=?")->execute(array($pic2,intval($_POST["id"])));
        }
        if( $_FILES["pic1"]["name"] != "" ) {
                $pic1_name='1_'.$_FILES["pic1"]["name"];
                move_uploaded_file($_FILES["pic1"]["tmp_name"], '/var/www/html/CarSystem/traffic/'.date("Y-m-d").'/'.$_POST["id"].'/'.$pic1_name);
                $pic1='traffic/'.date("Y-m-d").'/'.$_POST["id"].'/'.$pic1_name;
		$pdo->prepare("UPDATE `cases` set `pic1`=? where `id`=?")->execute(array($pic1,intval($_POST["id"])));
        }
	echo '<script>document.location.href="cases.php?state=ready&n";</script>';
	exit;
}

if( $_POST["_method"] == "put" && $_POST["case"]["expose"] == "2" && $_POST["authenticity_token"] == "dtQ0kLcyBcoTBTfeopTr84sCjzI8L6Vb52QR2RrFbdb/HrWpAx49ZA8tu8Mof/VoQGweX+BmKtHA8TXwslShtw==" && $_POST["cases"]["expose_msg"] != "" ) {
$pdo->prepare("UPDATE `cases` set `expose`=?,`expose_msg`=?,`state`=?,`review_username`=?,`review_department`=?,`review_unit`=? where `id`=?")->execute(array('退件',$_POST["cases"]["expose_msg"],'reviewing',$row_user["name"],$row_user["department"],$row_user["unit"],intval($_POST["id"])));
               $msg = "從案件處理 > 案件審核(".$row_user["name"].")";
               $cdate = date("Y-m-d H:i:s");
               $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
	echo '<script>document.location.href="cases.php?state=reviewing&n";</script>';
	exit;
}

//轉擋
if( intval($_GET["id"]) != "" ) {
//if( intval($_GET["id"]) == '43' ) {
$result = $pdo->query("select * from `cases` where `id`='".$_GET["id"]."'");
//$result = $pdo->query("select * from `cases` where `id`='43'");
$row = $result->fetch(PDO::FETCH_ASSOC);
$media_sub=array(
        "video/avi"=>"avi",
        "video/mp4"=>"mp4",
        "video/quicktime"=>"mov",
	"video/mov"=>"mov",
        "video/wmv"=>"wmv",
);
if( $row["pic1_conver"] == "0" && $media_sub[$row["pic1_type"]] != "" ) {
	$oldmp4 = '/var/www/html/CarSystem/'.$row["pic1"];
	$newpic = date("YmdHis").rand(1000,9999).'.mp4';
        $buff = explode("/",$oldmp4);
        $buaa = $buff[5].'/'.$buff[6].'/'.$buff[7].'/';
        $newmp4 = '/var/www/html/CarSystem/'.$buaa.$newpic;
exec("/usr/bin/ffmpeg -i $oldmp4 -vcodec h264 -y -pix_fmt yuv420p -preset ultrafast -max_muxing_queue_size 1024 ".$newmp4 ); 
	$pdo->prepare("UPDATE `cases` set `pic1_conver`=?,`pic1`=?,`pic1_type`=? where `id`=?")->execute(array('1',$buaa.$newpic,'video/mp4',intval($_GET["id"])));
	unlink( $oldmp4 );
}

if( $row["pic2_conver"] == "0" && $media_sub[$row["pic2_type"]] != "" ) {
        $oldmp4 = '/var/www/html/CarSystem/'.$row["pic2"];
        $newpic = date("YmdHis").rand(1000,9999).'.mp4';
        $buff = explode("/",$oldmp4);
        $buaa = $buff[5].'/'.$buff[6].'/'.$buff[7].'/';
        $newmp4 = '/var/www/html/CarSystem/'.$buaa.$newpic;
exec("/usr/bin/ffmpeg -i $oldmp4 -vcodec h264 -y -pix_fmt yuv420p -preset ultrafast -max_muxing_queue_size 1024 ".$newmp4 );
        $pdo->prepare("UPDATE `cases` set `pic2_conver`=?,`pic2`=?,`pic2_type`=? where `id`=?")->execute(array('1',$buaa.$newpic,'video/mp4',intval($_GET["id"])));
//	unlink( $oldmp4 );
}

if( $row["pic3_conver"] == "0" && $media_sub[$row["pic3_type"]] != "" ) {
        $oldmp4 = '/var/www/html/CarSystem/'.$row["pic3"];
        $newpic = date("YmdHis").rand(1000,9999).'.mp4';
        $buff = explode("/",$oldmp4);
        $buaa = $buff[5].'/'.$buff[6].'/'.$buff[7].'/';
        $newmp4 = '/var/www/html/CarSystem/'.$buaa.$newpic;
exec("/usr/bin/ffmpeg -i $oldmp4 -vcodec h264 -y -pix_fmt yuv420p -preset ultrafast -max_muxing_queue_size 1024 ".$newmp4 );
        $pdo->prepare("UPDATE `cases` set `pic3_conver`=?,`pic3`=?,`pic3_type`=? where `id`=?")->execute(array('1',$buaa.$newpic,'video/mp4',intval($_GET["id"])));
	unlink( $oldmp4 );
}

if( $row["pic4_conver"] == "0" && $media_sub[$row["pic4_type"]] != "" ) {
        $oldmp4 = '/var/www/html/CarSystem/'.$row["pic4"];
        $newpic = date("YmdHis").rand(1000,9999).'.mp4';
	$buff = explode("/",$oldmp4);
	$buaa = $buff[5].'/'.$buff[6].'/'.$buff[7].'/';
        $newmp4 = '/var/www/html/CarSystem/'.$buaa.$newpic;
exec("/usr/bin/ffmpeg -i $oldmp4 -vcodec h264 -y -pix_fmt yuv420p -preset ultrafast -max_muxing_queue_size 1024 ".$newmp4 );
        $pdo->prepare("UPDATE `cases` set `pic4_conver`=?,`pic4`=?,`pic4_type`=? where `id`=?")->execute(array('1',$buaa.$newpic,'video/mp4',intval($_GET["id"])));
	unlink( $oldmp4 );
}

if( $row["pic5_conver"] == "0" && $media_sub[$row["pic5_type"]] != "" ) {
        $oldmp4 = '/var/www/html/CarSystem/'.$row["pic5"];
        $newpic = date("YmdHis").rand(1000,9999).'.mp4';
        $buff = explode("/",$oldmp4);
        $buaa = $buff[5].'/'.$buff[6].'/'.$buff[7].'/';
        $newmp4 = '/var/www/html/CarSystem/'.$buaa.$newpic;
exec("/usr/bin/ffmpeg -i $oldmp4 -vcodec h264 -y -pix_fmt yuv420p -preset ultrafast -max_muxing_queue_size 1024 ".$newmp4 );
        $pdo->prepare("UPDATE `cases` set `pic5_conver`=?,`pic5`=?,`pic5_type`=? where `id`=?")->execute(array('1',$buaa.$newpic,'video/mp4',intval($_GET["id"])));
	unlink( $oldmp4 );
}

}


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
echo '<script>document.location.href="cases_edit.php?id='.$_GET["id"].'&state='.$_GET["state"].'";</script>';
}
//設定舉發圖片 cases_img
if( $_GET["id"] != "" && $_GET["act"]=="jpgdo" && $_GET["pid"] != "" ) {
$pdo->prepare("UPDATE `cases_img` SET `flag`=? where `id`=?")->execute(array('1',$_GET["pid"]));
echo '<script>document.location.href="cases_edit.php?id='.$_GET["id"].'&state='.$_GET["state"].'";</script>';
exit;
}
if( $_GET["id"] != "" && $_GET["act"]=="jpgdoo" && $_GET["spic"] != "" ) {
	$buffa = $_GET["spic"].'_do=?';
	$pdo->prepare("UPDATE `cases` SET $buffa where `id`=?")->execute(array('1',$_GET["id"]));
	echo '<script>document.location.href="cases_edit.php?id='.$_GET["id"].'&state='.$_GET["state"].'";</script>';
	exit;
}

//取消舉發圖片 cases_img
if( $_GET["id"] != "" && $_GET["act"]=="jpgdel" && $_GET["pid"] != "" ) {
$pdo->prepare("UPDATE `cases_img` SET `flag`=? where `id`=?")->execute(array('0',$_GET["pid"]));
echo '<script>document.location.href="cases_edit.php?id='.$_GET["id"].'&state='.$_GET["state"].'";</script>';
exit;
}
if( $_GET["id"] != "" && $_GET["act"]=="jpgdel2" && $_GET["spic"] != "" ) {
        $buffa = $_GET["spic"].'_do=?';
        $pdo->prepare("UPDATE `cases` SET $buffa where `id`=?")->execute(array('0',$_GET["id"]));
        echo '<script>document.location.href="cases_edit.php?id='.$_GET["id"].'&state='.$_GET["state"].'";</script>';
        exit;
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
exit;
}

if( $_POST["state"] == "nready" ) {
$pdo->prepare("UPDATE `cases` set `state`=?,`userid`=? where `id`=?")->execute(array('ready',$row_user["id"],intval($_GET["id"])));
	        $cdate = date("Y-m-d H:i:s");
	        $msg = '從員警領案 > 案件處理('.$row_user["name"].')';
		        $pdo->prepare("INSERT INTO `case_history` (`id`,`cases_id`,`user_id`,`message`,`cdate`) VALUES ( NULL, ?, ?, ?, ? )")->execute(array(intval($_GET["id"]),$row_user["id"],$msg,$cdate));
	header( "Location: cases.php?state=nready" );
	exit;
}



if( $_POST["format"] == "json" && $_POST["file_name"] != "" && $_POST["base64_attachment"] != "" && $_POST["cases_id"] != "" ) {
	$result = $pdo->query("select * from `cases` where `id`='".$_POST["cases_id"]."'");
	$row = $result->fetch(PDO::FETCH_ASSOC);
	if(!file_exists('../../CarSystem/other/')) {
	mkdir('../../CarSystem/other',0777);
	}
	if(!file_exists('../../CarSystem/other/'.date("Y-m-d").'/')) {
		mkdir('../../CarSystem/other/'.date("Y-m-d"),0777);
	}
	$picdir = '/CarSystem/other/'.date("Y-m-d");

$data = $_POST["base64_attachment"];
list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);
file_put_contents('../..'.$picdir.'/'.$_POST["file_name"], $data);
$img = 'other/'.date("Y-m-d").'/'.$_POST["file_name"];
$pdo->prepare("INSERT INTO `cases_img` (`id`,`cid`,`img`) VALUES ( NULL, ?, ? )")->execute(array(intval($_POST["cases_id"]),$img));
$arr = array('optimized_file_url'=>$picdir.'/'.$_POST["file_name"],'id'=>'10000','selected'=> True,'file'=>array('url'=>$picdir.'/'.$_POST["file_name"]));
echo json_encode($arr); 
exit;
}

$nav = "綜合查詢";
if( $_GET["state"] == "no" )
	$nav = "g6";
else
	$nav="綜合查詢";
if( $_GET["act"] == "ucomment" && $_GET["id"] != "" ) {
        $pdo->prepare("UPDATE `cases` set `comment`=? where `id`=?")->execute(array($_POST["comment"],intval($_GET["id"])));
}

if( $_POST["_method"] == "put" ) {
	if( $_POST["case"]["expose"] == "true" ) {
	if( $_POST["case"]["reason_code"] == "" ) {
                if( $_POST["case"]["reason_code"] == "" ) {
                        $re_msg="is-invalid";
                        $re_msg2='<div class="invalid-feedback">條款尚未選擇</div>';
                }
	} else {
	$cdate = date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=?,`expose`=?,`reason_code`=?,`expose_msg`=? where `id`=?")->execute(array($_POST["state"],'舉發',$_POST["case"]["reason_code"],NULL,intval($_POST["id"])));
	if( $_POST["state"] == "reviewing" ) {
		        $pdo->prepare("UPDATE `cases` SET `state`=?,`expose`=?,`reason_code`=?,`review_user_id`=?,`review_department`=? where `id`=?")->execute(array('reviewing','舉發',$_POST["case"]["reason_code"],$row_user["id"],$row_user["department"],intval($_POST["id"])));
		$msg = '從案件處理 > 案件審核('.$row_user["name"].')';
		$pdo->prepare("INSERT INTO `case_history` (`id`,`cases_id`,`user_id`,`message`,`cdate`) VALUES ( NULL, ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));

$result_mail = $pdo->query("select * from `cases` where `id`='".intval($_POST["id"])."'");
$rowmail = $result_mail->fetch(PDO::FETCH_ASSOC);
$result_d = $pdo->query("select * from `department` where `id`='".$row_user["department_id"]."'");
$rowd = $result_d->fetch(PDO::FETCH_ASSOC);
$txt = "";
$txt .= "臺端您好：";
$txt .= "<br><br>";
$txt .= '有關您檢舉'.$rowmail["full_car_number"].'號車涉交通違規ㄧ案(案號：'.$rowmail["sn"].')，其處理情形如下：';
$txt .= "<br>";
$txt .= '處理結果：'.$rowmail["expose"];
$txt .= "<br>";
$txt .= '承辦員警：'.$rowmail["review_department"].' ('.$row_user["name"].')';
$txt .= "<br>";
$txt .= '連絡電話：'.$rowd["dphone"];
$txt .= "<br>";
$txt .= "如對以上答復內容尚有疑義，歡迎來電洽詢，謝謝您來信與指教，並祝您平安順心 萬事如意";
$txt .= "<br><br>";
$txt .= '新竹市警察局 '.$rowmail["review_department"].' 敬復';
$txt .= '<br><br>';
$mail = new PHPMailer();
$mail->CharSet = 'UTF-8';
$mail= new PHPMailer(); //建立新物件
$mail->IsSMTP(); //設定使用SMTP方式寄信
$mail->SMTPAuth = true; //設定SMTP需要驗證
$mail->Host = "dm.cloudmax.com.tw"; //Gamil的SMTP主機
$mail->Port = 25; //Gamil的SMTP主機的埠號(Gmail為465)。
$mail->CharSet = "utf-8"; //郵件編碼
$mail->Username = 'ct18447@dm.cloudmax.com.tw';
$mail->Password = 'CB!c22y^';
$mail->setFrom('ct18447@dm.cloudmax.com.tw', '新竹市交通事故案件便民服務網');
$mail->ClearAddresses();
$mail->addAddress($rowmail["email"],'系統自動寄發');
$mail->isHTML(true);
$mail->Subject = "結案回復信: 新竹市交通事故案件便民服務網";
$mail->Body = $txt;
$mail->send();

echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=ready&n";</script>';


        } else if( $_POST["state"] == "recheck" ) {
                echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=reviewing&n";</script>';
	} else if( $_POST["state"] == "complete" ) {
		$msg = "從案件審核 > 案件結束(".$row_user["name"].")";
		$complete_date=date("Y-m-d H:i:s");
	        $pdo->prepare("UPDATE `cases` set `complete_date`=? where `id`=?")->execute(array($complete_date,intval($_POST["id"])));
		$pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
		echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=reviewing&n";</script>';	
		exit;
	} else {
		echo '<script>document.location.href="cases.php?n";</script>';
	}
	exit;
	}
	} else {
        $cdate = date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=?,`expose`=?,`expose_msg`=?,`reason_code`=? where `id`=?")->execute(array($_POST["state"],'不舉發',$_POST["cases"]["expose_msg"],NULL,intval($_POST["id"])));
        if( $_POST["state"] == "reviewing" ) {
                        $pdo->prepare("UPDATE `cases` set `state`=?,`expose`=?,`expose_msg`=?,`expose_note`=?,`review_user_id`=?,`review_department`=? where `id`=?")->execute(array('reviewing','不舉發',$_POST["cases"]["expose_msg"],$_POST["cases"]["expose_note"],$row_user["id"],$row_user["department"],intval($_POST["id"])));
                $msg = '從案件處理 > 案件審核('.$row_user["name"].')';
                $pdo->prepare("INSERT INTO `case_history` (`id`,`cases_id`,`user_id`,`message`,`cdate`) VALUES ( NULL, ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));

$result_mail = $pdo->query("select * from `cases` where `id`='".intval($_POST["id"])."'");
$rowmail = $result_mail->fetch(PDO::FETCH_ASSOC);
$result_d = $pdo->query("select * from `department` where `id`='".$row_user["department_id"]."'");
$rowd = $result_d->fetch(PDO::FETCH_ASSOC);
$txt = "";
$txt .= "臺端您好：";
$txt .= "<br><br>";
$txt .= '有關您檢舉'.$rowmail["full_car_number"].'號車涉交通違規ㄧ案(案號：'.$rowmail["sn"].')，其處理情形如下：';
$txt .= "<br>";
$txt .= '處理結果：'.$rowmail["expose"].' ( '.$rowmail["expose_msg"].$rowmail["expose_note"].' )';
$txt .= "<br>";
$txt .= '承辦員警：'.$rowmail["review_department"].' ('.$row_user["name"].')';
$txt .= "<br>";
$txt .= '連絡電話：'.$rowd["dphone"];
$txt .= "<br>";
$txt .= "如對以上答復內容尚有疑義，歡迎來電洽詢，謝謝您來信與指教，並祝您平安順心 萬事如意";
$txt .= "<br><br>";
$txt .= '新竹市警察局 '.$rowmail["review_department"].' 敬復';
$txt .= '<br><br>';
$mail = new PHPMailer();
$mail->CharSet = 'UTF-8';
$mail= new PHPMailer(); //建立新物件
$mail->IsSMTP(); //設定使用SMTP方式寄信
$mail->SMTPAuth = true; //設定SMTP需要驗證
$mail->Host = "dm.cloudmax.com.tw"; //Gamil的SMTP主機
$mail->Port = 25; //Gamil的SMTP主機的埠號(Gmail為465)。
$mail->CharSet = "utf-8"; //郵件編碼
$mail->Username = 'ct18447@dm.cloudmax.com.tw';
$mail->Password = 'CB!c22y^';
$mail->setFrom('ct18447@dm.cloudmax.com.tw', '新竹市交通事故案件便民服務網');
$mail->ClearAddresses();
$mail->addAddress($rowmail["email"],'系統自動寄發');
$mail->isHTML(true);
$mail->Subject = "結案回復信: 新竹市交通事故案件便民服務網";
$mail->Body = $txt;
$mail->send();

                echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=ready&n";</script>';
	} else if( $_POST["state"] == "recheck" ) {
		echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=reviewing&n";</script>';
        } else if( $_POST["state"] == "complete" ) {
                $msg = "從案件審核 > 案件結束(".$row_user["name"].")";
		$complete_date=date("Y-m-d H:i:s");
                $pdo->prepare("UPDATE `cases` set `complete_date`=? where `id`=?")->execute(array($complete_date,intval($_POST["id"])));
                $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array(intval($_POST["id"]),$row_user["id"],$msg,$cdate));
                echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=recheck&n";</script>';
        } else {
                echo '<script>document.location.href="cases.php?n";</script>';
        }
        exit;
	}
}

if( $_GET["act"] == "rback" && $_GET["state"] == "nready" ) {
        $cdate = date("Y-m-d H:i:s");
        $msg = '從案件處理 > 案件退回('.$row_user["name"].')';
        $pdo->prepare("INSERT INTO `case_history` (`id`,`cases_id`,`user_id`,`message`,`cdate`) VALUES ( NULL, ?, ?, ?, ? )")->execute(array(intval($_GET["id"]),$row_user["id"],$msg,$cdate));
$pdo->prepare("UPDATE `cases` set `state`=?,`userid`=? where `id`=?")->execute(array($_GET["state"],NULL,intval($_GET["id"])));
echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=ready&n";</script>';
exit;
}

if( $_GET["act"] == "fback" && $_GET["state"] == "ready" ) {
	$cdate = date("Y-m-d H:i:s");
	//$msg = '從案件審核 > 案件處理('.$row_user["name"].')';
	//$pdo->prepare("INSERT INTO `case_history` (`id`,`cases_id`,`user_id`,`message`,`cdate`) VALUES ( NULL, ?, ?, ?, ? )")->execute(array(intval($_GET["id"]),$row_user["id"],$msg,$cdate));
//$pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array($_GET["state"],intval($_GET["id"])));
echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=reviewing&n";</script>';
exit;
}

if( $_GET["act"] == "fback" && $_GET["state"] == "reviewing" ) {
        $cdate = date("Y-m-d H:i:s");
        $msg = '從案件複審 > 案件審核('.$row_user["name"].')';
        $pdo->prepare("INSERT INTO `case_history` (`id`,`cases_id`,`user_id`,`message`,`cdate`) VALUES ( NULL, ?, ?, ?, ? )")->execute(array(intval($_GET["id"]),$row_user["id"],$msg,$cdate));
$pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array($_GET["state"],intval($_GET["id"])));
echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=recheck&n";</script>';
exit;
}


if( $_GET["act"] == "aback" && $_GET["state"] == "nready" ) {
        $cdate = date("Y-m-d H:i:s");
        $msg = '從員警領案 > 案件改派('.$row_user["name"].')';
        $pdo->prepare("INSERT INTO `case_history` (`id`,`cases_id`,`user_id`,`message`,`cdate`) VALUES ( NULL, ?, ?, ?, ? )")->execute(array(intval($_GET["id"]),$row_user["id"],$msg,$cdate));
$pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array('no',intval($_GET["id"])));
echo '<script>document.location.href="cases.php?page='.$_SESSION["page"].'&state=nready&n";</script>';
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
  

<link rel="stylesheet" media="all" href="assets/admin-92e995e469ea98c880e61710f498cb7c0dddcd185d591b92bc985fb93e14d29a.css?<?=date("YmdHis");?>" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
<script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox-plus-jquery.min.js"></script>
<script>
$(function() {

$('body').bind('change keyup input keydown keyup','input[name="comment"]',function(e) {
	if( $('input[name="comment"]').val().length > 11 ) {
		toastr.error('結案附加訊息超過10個字,請重新輸入!');
		$('input[name="comment"]').val('');
	}
});
        $("#full_car_number").change(function(){
$.ajax({
        type: 'POST',
        url: '/new/admin/php/chfullcarnumber.php',
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

$( "#case_expose_false" ).on( "click", function() {
	$( "#chkexpose" ).val("1");
});
$( "#case_expose_true" ).on( "click", function() {
        $( "#chkexpose" ).val("0");
});

});
</script>
<style>
.myway {
        width:200px;
}
.myway span:first-child {
        float:left;
}
.myway span:last-child {
        float:right;
}
</style>
</head>

  <body id="page-top" class="case_wrapper cases index collection">
<?php include "menu.php";?>
    <div id="wrapper">


      <div id="content-wrapper">
      <div class="container-fluid" style="<?php if( ismobile()) { echo 'padding-left: 1%;padding-right: 1%;'; } else { echo 'padding-left: 20%;padding-right: 20%;'; }?>">
          <div class="flashes">
            

          </div>

          



<?php
$media_sub=array(
        "image/jpeg"=>"jpg",
        "image/png"=>"png",
        "image/gif"=>"gif",
        "image/bmp"=>"bmp",
);
$result = $pdo->query("select * from `cases` where `id`='".$_GET["id"]."'");
$row = $result->fetch(PDO::FETCH_ASSOC);

$jpg_num = 0;
/*
if( $media_sub[$row["pic1_type"]] != "" )
	$jpg_num++;
if( $media_sub[$row["pic2_type"]] != "" )
        $jpg_num++;
if( $media_sub[$row["pic3_type"]] != "" )
        $jpg_num++;
if( $media_sub[$row["pic4_type"]] != "" )
        $jpg_num++;
if( $media_sub[$row["pic5_type"]] != "" )
        $jpg_num++;
 */
if( $row["pic1_do"] == "1" )
	$jpg_num++;
if( $row["pic2_do"] == "1" )
        $jpg_num++;
if( $row["pic3_do"] == "1" )
        $jpg_num++;
if( $row["pic4_do"] == "1" )
        $jpg_num++;
if( $row["pic5_do"] == "1" )
        $jpg_num++;

$result_img = $pdo->query("select * from `cases_img` where `cid`='".$row["id"]."' and `flag`='1'");
while($rowimg = $result_img->fetch(PDO::FETCH_ASSOC)) {
	$jpg_num++;
}
?>
<input type="hidden" id="chkexpose" name"chkexpose" value="<?php 
if( $row["expose"] == "退件") 
	echo "1"; 
else if( $row["expose"] == "核發")
	echo "0";
else
	echo "";
?>
">
<?php
echo '<input type="hidden" name="jpg_num" id="jpg_num" value="'.$jpg_num.'">';
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
	<form class="simple_form edit_case" id="edit_case_199115" name="edit_case_199115" enctype="multipart/form-data" autocomplete="off" action="#cases_edit.php?id=<?=$row["id"];?>" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="_method" value="put" /><input type="hidden" name="authenticity_token" value="UcPkr/xUVFGzbM/ZV1DoDt1yvvNUz1qYFtalwcoUTXewp4xWHt9bnmFIiGWZ4tG+pLNo8WcEUmqOITYvKcXdDw==" />
  <div class="row">
    <div class="col-lg-12">
      <div class="card mb-3">
        <div class="card-header">
          <b><!--檢舉-->案件明細<!--(目前狀態: <?php echo $car_array["state"][$row["state"]];?>)</b>-->
          <div class="float-right">
	  <!--<a target="_blank" class="btn btn-success" href="card.php?id=<?=$row["id"];?>">列印</a>-->
	  <a href="cases.php?page=<?=$_SESSION["page"];?>&state=<?=$_GET["state"];?>" class="btn btn-primary back2list">回到列表</a>
<?php 
/*
if( $row_user["id"] == "1" ) { 
echo '<a href="cases.php?page='.$_SESSION["page"].'&act=del&did='.$row["id"].'" onclick="return confirm(\'確定刪除此筆資料? 刪除後無法恢復!!!\')" class="btn btn-primary" style="background-color:red;border-color:red;">刪除(Admin專用)</a>';
}*/ ?>

<span class="divider"></span>

          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <dl class="row">
                <!--<dt class="col-md-2">
                  重複篩選：
                </dt>
                <dd class="col-sm-10">
<?php
        $vio = $row["violated_at_date"].' '.$row["violated_at_hour"].':'.$row["violated_at_min"].':00';
        $day30 = date('Y-m-d H:i:s',strtotime($vio . "-30 days"));
$resultm = $pdo->query("select * from `cases` where `full_car_number`='".$row["full_car_number"]."' and `violated_at`>='".$day30."' and `violated_at`<='".$row["violated_at"]."'");
$mul=0;
while($rowm = $resultm->fetch(PDO::FETCH_ASSOC)){
        $mul++;
}
?>
        <?php
if( $mul != 0 && $row["full_car_number"]!="") {
        echo '<a href="cases.php?full_car_number='.$row["full_car_number"].'&min='.$row["violated_at"].'&max='.$day30.'">'.$mul.'</a>';
} else {
        echo '';
}
?>
                </dd>-->
              </dl>
                <table border="1" align="center" style="width: 100%">
  <tr>
    <td colspan="2" align="center">案件資料</td>
  </tr>
  <tr>
    <td>案件編號</td>
    <td><?=$row["sn"];?></td>
  </tr>
  <tr>
    <td>申請日期</td>
    <td><?=$row["created_at"];?></td>
  </tr>
  <tr>
    <td colspan="2" align="center">申請人</td>
  </tr>
  <tr>
    <td>姓名</td>
    <td><?=$row["username"];?></td>
  </tr>
  <tr>
    <td>身分證字號</td>
    <td><?=$row["idnumber"];?></td>
  </tr>
  <tr>
    <td>聯絡電話</td>
    <td><?=$row["phone"];?></td>
  </tr>
  <tr>
    <td>電子信箱</td>
    <td><?=$row["email"];?></td>
  </tr>
<?php
if( $row["pic5"] != "" ) {
	echo '<tr>
		    <td>上傳資料</td>
		        <td><a href="../../CarSystem/'.$row["pic5"].'" data-lightbox="image-1"><img src="../../CarSystem/'.$row["pic5"].'" width="100"></a></td>
			  </tr>';
}
?>
  <tr>
    <td colspan="2" align="center">事故當事人</td>
  </tr>
  <tr>
    <td>姓名</td>
    <td><?=$row["username2"];?></td>
  </tr>
  <tr>
    <td>身分證字號</td>
    <td><?=$row["idnumber2"];?></td>
  </tr>
  <tr>
    <td>聯絡電話</td>
    <td><?=$row["phone2"];?></td>
  </tr>
  <tr>
    <td>案件類別</td>
    <td><?=$row["category_name"];?></td>
  </tr>
  <tr>
    <td>發生時間</td>
    <td><?=substr($row["violated_at"],0,16);?></td>
  </tr>
  <tr>
    <td>事故地點</td>
    <td><?php if( $row["cam_place"] != "" ) echo '新竹市';?><?=$row["cam_place"];?></td>
  </tr>
  <tr>
    <td>申請項目</td>
    <td>
<ul>
<?php
if( !empty($row["groupp1"]))
	if( $row["state"] == "ready" || $row["state"] == "nready" ) {
		echo '<li style="margin:10px;"><span style="float:left;width:150px;">'.$row["groupp1"].'</span>';
if( $row["state"] != "nready" )		
	echo '<span style="flaot:right;"><!--<input class="" style="color: red;" autocomplete="off" accept=".pdf" required="required" aria-required="true" type="file" name="pic1" id="pic1" />--></span>';
echo '</li>';
	} else if( $row["state"] == "reviewing" || $row["state"] == "complete" ) { 
		if( $row["pic1"] != "" ) {
echo '<li style="margin:10px;"><span style="float:left;width:150px;">'.$row["groupp1"].'</span><span style="flaot:right;"><a href="../../CarSystem/'.$row["pic1"].'" download target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 144-208 0c-35.3 0-64 28.7-64 64l0 144-48 0c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zM176 352l32 0c30.9 0 56 25.1 56 56s-25.1 56-56 56l-16 0 0 32c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-48 0-80c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24l-16 0 0 48 16 0zm96-80l32 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-32 0c-8.8 0-16-7.2-16-16l0-128c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16l0-64c0-8.8-7.2-16-16-16l-16 0 0 96 16 0zm80-112c0-8.8 7.2-16 16-16l48 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 32 32 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 48c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-64 0-64z"/></svg></a></span>';
		} else {
			echo '<li style="margin:10px;"><span style="float:left;width:150px;">'.$row["groupp1"].'</span><span style="flaot:right;"></span>';
		}
	}
if( !empty($row["groupp2"]))
	if( $row["state"] == "ready" || $row["state"] == "nready" ) {
		echo '<li style="margin:10px;"><span style="float:left;width:150px;">'.$row["groupp2"].'</span>';
if( $row["state"] != "nready" )		
	echo '<span style="flaot:right;"><!--<input class="" style="color:red;" autocomplete="off" accept=".pdf" required="required" aria-required="true" type="file" name="pic2" id="pic2" />--></span>';
echo '</li>';
	} else if( $row["state"] == "reviewing" || $row["state"] == "complete" ) { 
		if( $row["pic2"] != "" ) {
		echo '<li style="margin:10px;"><span style="float:left;width:150px;">'.$row["groupp2"].'</span><span style="flaot:right;"><a href="../../CarSystem/'.$row["pic2"].'" download target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 144-208 0c-35.3 0-64 28.7-64 64l0 144-48 0c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zM176 352l32 0c30.9 0 56 25.1 56 56s-25.1 56-56 56l-16 0 0 32c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-48 0-80c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24l-16 0 0 48 16 0zm96-80l32 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-32 0c-8.8 0-16-7.2-16-16l0-128c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16l0-64c0-8.8-7.2-16-16-16l-16 0 0 96 16 0zm80-112c0-8.8 7.2-16 16-16l48 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 32 32 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 48c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-64 0-64z"/></svg></a></span>';
		} else {
			echo '<li style="margin:10px;"><span style="float:left;width:150px;">'.$row["groupp2"].'</span><span style="flaot:right;"></span>';
		}
	}
if( !empty($row["groupp3"]))
	if( $row["state"] == "ready" || $row["state"] == "nready" ) {
		echo '<li style="margin:10px;"><span style="float:left;width:150px;">'.$row["groupp3"].'</span>';
if( $row["state"] != "nready" )		
	echo '<span style="flaot:right;"><!--<input class="" style="color:red;" autocomplete="off" accept=".pdf" required="required" aria-required="true" type="file" name="pic3" id="pic3" />--></span>';
	echo '</li>';
	} else if( $row["state"] == "reviewing" || $row["state"] == "complete" ) { 
		if( $row["pic3"] != "" ) {
echo '<li style="margin:10px;"><span style="float:left;width:150px;">'.$row["groupp3"].'</span><span style="flaot:right;"><a href="../../CarSystem/'.$row["pic3"].'" download target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 144-208 0c-35.3 0-64 28.7-64 64l0 144-48 0c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zM176 352l32 0c30.9 0 56 25.1 56 56s-25.1 56-56 56l-16 0 0 32c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-48 0-80c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24l-16 0 0 48 16 0zm96-80l32 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-32 0c-8.8 0-16-7.2-16-16l0-128c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16l0-64c0-8.8-7.2-16-16-16l-16 0 0 96 16 0zm80-112c0-8.8 7.2-16 16-16l48 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 32 32 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 48c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-64 0-64z"/></svg></a></span>';
		} else {
			echo '<li style="margin:10px;"><span style="float:left;width:150px;">'.$row["groupp3"].'</span><span style="flaot:right;"></span>';
		}
	}
?></ul></td>
  </tr>
  <tr>
    <td colspan="2" align="center">案件處理</td>
  </tr>


  <tr>
    <td width="20%">限辦日期</td>
    <td><?php 
if( !empty($row["groupp3"]))
	        $qq=25;
else
        $qq=10;
echo date('Y-m-d',strtotime("$row[created_at] +$qq day"));?></td>
  </tr>



      <tr>
        <td>結案方式</td>
        <td>
<?php
if( $row["expose"] == "核發" )
	$expose_chk = 'checked="checked"';
else
	$expose_chk = '';
if( $row["expose"] == "退件" )
	$expose_chk2 = 'checked="checked"';
else
	$expose_chk2 = '';
if( empty($_GET["min"]) && empty($_GET["max"])) {
if( $row["state"] == "ready" ) {
echo '<input type="hidden" name="case[expose]" value="" disbaled/><div class="form-check-inline"><input class="form-check-input radio_buttons optional" type="radio" value="1" name="case[expose]" id="case_expose_true" disabled/><label class="form-check-label collection_radio_buttons" for="case_expose_true">核發</label></div><div class="form-check-inline"><input class="form-check-input radio_buttons optional" readonly="readonly" type="radio" value="2" name="case[expose]" id="case_expose_false" disabled/><label class="form-check-label collection_radio_buttons" for="case_expose_false">退件</label></div>';
} else if( $row["state"] == "reviewing" || $row["state"] == "recheck" ) {
	if( $row["expose"] == "核發" )
		$ex_ch2 = "disabled";
	else
		$ex_ch1 = "disbaled";
echo '<input type="hidden" name="case[expose]" value="" disabled/><div class="form-check-inline"><input class="form-check-input radio_buttons optional" type="radio" value="1" '.$expose_chk.' name="case[expose]" id="case_expose_true" '.$ex_ch1.' disabled/><label class="form-check-label collection_radio_buttons" for="case_expose_true">核發</label></div><div class="form-check-inline"><input class="form-check-input radio_buttons optional" readonly="readonly" type="radio" value="2" '.$expose_chk2.' name="case[expose]" id="case_expose_false" '.$ex_ch2.' disabled/><label class="form-check-label collection_radio_buttons" for="case_expose_false">退件</label></div>';
        if( $row["expose"] == "退件" )
		                echo '</td></tr><tr><td>退件理由</td><td>'.$row["expose_msg"];

} else {
	echo $row["expose"];
	if( $row["pwd"] != "" )
		echo ' 　　　　密碼: '.$row["pwd"];
	if( $row["expose"] == "退件" )
       		echo '</td></tr><tr><td>退件理由</td><td>'.$row["expose_msg"];
}
} else {
	echo $row["expose"];
}
?>
        </td>
      </tr>
      <!--<tr class="reason_form">
        <td>退件原因</td>
        <td>
<?php
if( $_GET["min"] == "" && $_GET["max"] == "" ) {
	if( $_GET["state"] == "ready" || $row["state"] == "reviewing" || $row["state"] == "recheck" ) {
?>
	<select class="form-control <?=$re_msg;?> select optional form-control select2" name="case[reason_code]" id="case_expose_reason_code">
<option value="">請選擇退件原因</option>
<?php
$reasonr = $pdo->query("select * from `expose_reasons` where active='t'");
while($rowr = $reasonr->fetch(PDO::FETCH_ASSOC)){
        if( $row["reason_code"] == $rowr["code"].' - '.$rowr["name"] )
                $buffa = 'selected';
        else
                $buffa = '';
echo '<option value="'.$rowr["code"].' - '.$rowr["name"].'" '.$buffa.'>'.$rowr["code"].' - '.$rowr["name"].'</option>';
}
?>
</select>
<?=$re_msg2;?>

          <div class="form-group unexpose_fields row" style="display: none;">
            <div class="col-sm-10 ">
              <input class="form-control string optional form-control " type="text" name="case[unexpose_reason_note]" id="case_unexpose_reason_note2" />
            </div>
          </div>
<?php } else {
if( $row["state"] == "complete" ) {
	        echo $row["reason_code"];
}
} 

} else {  echo $row["reason_code"]; } ?>
        </td>
      </tr>-->
<tr class="unexpose_fields" style="display: none;">
        <td>退件原因</td>
<td>
          <div class="row">
            <div class="col-sm-10 ">
<?php 
if( $_GET["state"] == "ready" || $row["state"] == "reviewing" || $row["state"] == "recheck" ) {
?>
<select name="cases[expose_msg]" id="case_unexpose_reason" class="frm-control select2" tabindex="-1" aria-hidden="true" size="1" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:300px;">
<option value="">請選擇退件原因</option>
<?php
	$reasonr = $pdo->query("select * from `unexpose_reasons` where active='t'");
while($rowr = $reasonr->fetch(PDO::FETCH_ASSOC)){
	if( $row["expose_msg"] == $rowr["name"] )
		$buffa = 'selected';
	else
		$buffa = '';
	echo '<option value="'.$rowr["name"].'" '.$buffa.'>';
	if( mb_strlen( $rowr["name"] ) >= 50 )
		        echo mb_substr($rowr["name"],0,50,"utf-8").'....';
	else
		        echo $rowr["name"];

		echo '</option>';
}
?>
</select>
            <!--<input class="form-control string optional form-control" type="text" name="cases[expose_note]" id="case_unexpose_reason_note" value="<?=$row["expose_note"];?>"/>-->
<?php } else { 
	echo $row["expose_msg"].$row["expose_note"];
}
?>
            </div>
          </div>
</td></tr>
<!--<tr>
    <td>結案附加訊息</td>
    <td>
<span data-bip-type="input" data-bip-attribute="comment" data-bip-placeholder="限填如....保二、新竹縣或其他分局轄區案件備註訊息，最多10個字" data-bip-object="case" data-bip-ok-button="儲存" data-bip-cancel-button="取消" data-bip-original-content="<?=$row["comment"];?>" data-bip-skip-blur="false" data-bip-url="cases.php?id=<?=$row["id"];?>" data-bip-value="<?=$row["comment"];?>" class="best_in_place" id="best_in_place_case_838354_comment"><?php
        if( mb_strlen( $row["comment"] ) >= 18 )
                        echo mb_substr($row["comment"],0,18,"utf-8").'....';
        else
                        echo $row["comment"];
	if( $row["comment"] == "" )
		echo NULL;
?>
</span>
    </td>
  </tr>-->

  <tr>
    <td>承辦人員</td>
    <td>
    <?php 
			if( $row["userid"] != "" ) {
$resultu = $pdo->query("select * from `user_list` where `id`='".$row["userid"]."'");
$rowu = $resultu->fetch(PDO::FETCH_ASSOC);
        echo $rowu["name"].' ('.$rowu["department"].') ('.$rowu["unit"].')';
			} 
?>
    </td>
  </tr>

</table>

            </div>
          </div>
        </div>
<script>
function transform_cases(event) {
        if( event == 'readytoreviewing' ) {
		if(!$("#case_expose_false").is(":checked") && !$("#case_expose_true").is(":checked")){
                                toastr.error('請先勾選結案方式....');
                                return false;
		}
                if($("#case_expose_false").is(":checked")) {
                        if( $('#case_unexpose_reason').val()=='' ) {
                                toastr.error('請選擇退件原因....');
                                return false;
                        }
                }
                if($("#case_expose_true").is(":checked")) {
		<?php if( $row["groupp1"] != "" ) { ?>
                if(!$('#pic1').val()) {
                        toastr.error('現場圖 PDF 尚未選取....');
                        return false;
		} 
		<?php } ?>
                <?php if( $row["groupp2"] != "" ) { ?>
                if(!$('#pic2').val()) {
                        toastr.error('事故照片 PDF 尚未選取....');
                        return false;
                }
                <?php } ?>
                <?php if( $row["groupp3"] != "" ) { ?>
                if(!$('#pic3').val()) {
                        toastr.error('初步分析研判表 PDF 尚未選取....');
                        return false;
                }
                <?php } ?>
		}
		document.edit_case_199115.submit();
	} else if( event == 'reviewingtorecheck' ) {
                if($("#case_expose_false").is(":checked")) {
                        if( !$('#case_unexpose_reason_note').val() && $('#case_unexpose_reason').val()=='' ) {
                                toastr.error('請選擇或者輸入不舉發原因');
                                return false;
                        }
                }
                if($('#chkexpose').val()=='0' && $('#jpg_num').val()=='0') {
                        toastr.error('舉發資料最少需要一張圖片!!');
                        return false;
                } else {
                        if( $('#case_expose_reason_code').val()=='' && $('#chkexpose').val()=='0' ) {
                                toastr.error('請選擇舉發條款');
                                return false;
                        }

                }
                document.edit_case_199115.submit();
	}
}
</script>
        <div class="card-footer small text-muted">
          <div class="row">
              <div class="form-actions">
<?php if( $_GET["min"] == "" && $_GET["max"] == "" ) { ?>
<?php if( $row["state"] == "ready" ) { ?>
       <input type="hidden" name="state" value="reviewing">
        <input type="hidden" name="id" value="<?=$row["id"];?>">
       <!--<input type="submit" name="commit" value="案件審核" onclick="transform_cases(&#39;readytoreviewing&#39;)" class="btn btn btn-primary" data-disable-with="案件審核" />-->
	<!--<input type="button" name="commit" value="案件審核" onclick="transform_cases(&#39;readytoreviewing&#39;)" class="btn btn btn-primary" data-disable-with="案件審核" />-->
<?php } else if( $row["state"] == "reviewing" ) { ?>
<input type="hidden" name="state" value="complete">
<input type="hidden" name="id" value="<?=$row["id"];?>">
<!--<input type="submit" name="commit" value="案件複審" class="btn btn btn-primary" data-disable-with="案件複審" />-->
<!--<input type="button" name="commit" value="案件複審" onclick="transform_cases(&#39;reviewingtorecheck&#39;)" class="btn btn btn-primary" data-disable-with="案件複審" />-->
<!--<input type="submit" name="commit" value="案件結束" class="btn btn btn-primary" data-disable-with="案件結束" />
<a class="btn btn-danger  float-right" rel="nofollow" data-method="post" href="cases_edit.php?id=<?=$row["id"];?>&act=fback&state=ready">案件退回</a>-->
<?php } else if( $row["state"] == "recheck" ) { ?>
<input type="hidden" name="state" value="complete">
<input type="hidden" name="id" value="<?=$row["id"];?>">
<input type="submit" name="commit" value="案件結束" class="btn btn btn-primary" data-disable-with="案件結束" />
<a class="btn btn-danger  float-right" rel="nofollow" data-method="post" href="cases_edit.php?id=<?=$row["id"];?>&act=fback&state=reviewing">案件退回</a>
<?php } else if( $row["state"] == "nready" ) { ?>
       <input type="hidden" name="state" value="nready">
        <input type="hidden" name="id" value="<?=$row["id"];?>">
       <!--<input type="submit" name="commit" value="案件處理" class="btn btn btn-primary" data-disable-with="案件處理" />
       <a class="btn btn-danger  float-right" rel="nofollow" data-method="post" href="cases_edit.php?id=<?=$row["id"];?>&act=aback&state=nready">案件改派</a>-->
<?php } else { ?>

<?php } ?>
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


    <script src="/hsin/assets/admin-7e641842b7678866dba9f029b1984fc78978fbe3c300f84802bb98e82b1f6905.js?<?=date("YmdHis");?>"></script>
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
  var spic = item.title
  var st = 'float:right'
  var st1 = ''
  var st2 = 'background-color:#000;opacity: 0.6;width:100%;position:absolute;top:0%;left:0%;padding:10px;'

  if(item.mediaKind == 'img'){
    if(jQuery.isEmptyObject(item.customData)){
      var checked = '';
      var ckbox = '<!--<input onchange="check_images(this, 199115)" style="'+st1+'" type="checkbox" name="base64_attachments[]" ' + checked + ' value="' + item.src + '" data-ngy2action="select_thumbnail">-->'

    }else{
      var item_id = item.customData.id
      var selected = item.customData.selected
      var checked = selected ? 'checked' : ''
      var ckbox = '<!--<input onchange="check_images(this, 199115)" style="'+st1+'" type="checkbox" name="case_attachment_ids[]" ' + checked + ' value="' + item_id + '" data-ngy2action="select_thumbnail">-->'
    }
  }else{
    var ckbox = ''
  }

    //ckbox = '<input style="'+st1+'" type="checkbox" name="case_attachment_ids[]" ' + checked + ' value="' + item_id + '" data-ngy2action="select_thumbnail">'
    ckbox = ''
    if(source_url.match('_sc_')==null){
    ckbox = '<!--<input disabled style="'+st1+'" type="checkbox" name="case_attachment_ids[]" value="' + item_id + '" data-ngy2action="select_thumbnail">-->'
    } 

  if(source_url != undefined){
	  if(source_url.match('_sc_')!=null){
		  var pid = item_id.toString().replace('<?=$_GET["id"];?>', '')
<?php if( $row["state"] != "complete" ) { ?>
			  source_link = '<a style="'+st+'" class="ngy2info" data-ngy2action="info" target="_self" href="cases_edit.php?act=jpgdo&id=<?=$_GET["id"];?>&state=<?=$_GET["state"];?>&min=<?=$_GET["min"];?>&max=<?=$_GET["max"];?>&full_car_number=<?=$_GET["full_car_number"];?>&pid='+pid+'">設定為舉發資料</a><br><!--<a style="'+st+'" class="ngy2info" data-ngy2action="info" target="_self" href="cases_edit.php?id=<?=$_GET["id"];?>&act=chjpga&pid='+pid+'" onclick="return confirm(\'確定取代主圖片? 取代後無法恢復!!!\')">取代主圖片</a><br><a style="'+st+'" class="ngy2info" data-ngy2action="info" target="_self" href="cases_edit.php?id=<?=$_GET["id"];?>&act=chjpgb&pid='+pid+'" onclick="return confirm(\'確定取代副圖片? 取代後無法恢復!!!\')">取代副圖片</a><br>--><a style="'+st+'" class="ngy2info" data-ngy2action="info" href="cases_edit.php?id=<?=$_GET["id"];?>&act=delop&pid='+pid+'&state=<?=$_GET["state"];?>" onclick="return confirm(\'確定刪除此張圖片? 刪除後無法恢復!!!\')"><font color="red">刪除</font></a>'
<?php } else { ?>
		source_link = '';
<?php } ?>
	  } else {
                  var picqq = ''
                  if( item.customData.id.toString() == "675340")
                        picqq="圖片"
                  if( item.customData.id.toString() == "675342")
                        picqq="圖片"
		//source_link = '<a style="'+st+'" class="ngy2info" data-ngy2action="info" target="_blank" href="' + source_url + '">'+picqq+'</a>'
		  if( source_url.match('jpg') || source_url.match('png') )
			  source_link = '<a style="'+st+'" class="ngy2info" data-ngy2action="info" target="_self" href="cases_edit.php?act=jpgdoo&id=<?=$_GET["id"];?>&state=<?=$_GET["state"];?>&min=<?=$_GET["min"];?>&max=<?=$_GET["max"];?>&full_car_number=<?=$_GET["full_car_number"];?>&spic='+spic+'">設定為舉發資料</a>'
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
    $.post("cases_edit.php", {format: 'json', file_name: file_name + ".jpg", base64_attachment: d,cases_id}, function(data){
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
      location.href = 'cases_edit.php?id=<?=$_GET["id"];?>&state=<?=$_GET["state"];?>';
    })
  }else if( customElementName != "custom2" ) {
    toastr.error("只能在影片中截圖");
  }
}
<?php
$media_pic=array(
        "image/jpeg"=>"jpg",
        "image/png"=>"png",
        "image/gif"=>"gif",
        "image/bmp"=>"bmp",
);
$media_video=array(
        "video/mp4"=>"mp4",
        "video/quicktime"=>"mov",
        "video/avi"=>"avi",
        "video/wmv"=>"wmv"
);
?>
$("#nanogallery2_199115").nanogallery2({
<?php
$i=0;
if( $row["pic1"] != "" && $media_pic[$row["pic1_type"]] != "" ) {
	if( $i == 0 ) {
		echo 'items: [';	
		$i++;
	}		
?>
{"src":"/CarSystem/<?=$row["pic1"];?>","srct":"/CarSystem/<?=$row["pic1"];?>","title":"pic1","customData":{"id":675340,"selected":true,"origin_url":"/CarSystem/<?=$row["pic1"];?>"}}
<?php } ?>
<?php
if( $row["pic2"] != "" && $media_pic[$row["pic2_type"]] != "" ) {
        if( $i == 0 ) {
                echo 'items: [';
                $i++;
        } else {
		echo ',';
	}
?>
{"src":"/CarSystem/<?=$row["pic2"];?>","srct":"/CarSystem/<?=$row["pic2"];?>","title":"pic2","customData":{"id":675340,"selected":true,"origin_url":"/CarSystem/<?=$row["pic2"];?>"}}
<?php } ?>
<?php
if( $row["pic3"] != "" && $media_pic[$row["pic3_type"]] != "" ) {
        if( $i == 0 ) {
                echo 'items: [';
                $i++;
        } else {
                echo ',';
        }
?>
{"src":"/CarSystem/<?=$row["pic3"];?>","srct":"/CarSystem/<?=$row["pic3"];?>","title":"pic3","customData":{"id":675340,"selected":true,"origin_url":"/CarSystem/<?=$row["pic3"];?>"}}
<?php } ?>
<?php
if( $row["pic4"] != "" && $media_pic[$row["pic4_type"]] != "" ) {
        if( $i == 0 ) {
                echo 'items: [';
                $i++;
        } else {
                echo ',';
        }
?>
{"src":"/CarSystem/<?=$row["pic4"];?>","srct":"/CarSystem/<?=$row["pic4"];?>","title":"pic4","customData":{"id":675340,"selected":true,"origin_url":"/CarSystem/<?=$row["pic4"];?>"}}
<?php } ?>
<?php
if( $row["pic5"] != "" && $media_pic[$row["pic5_type"]] != "" ) {
        if( $i == 0 ) {
                echo 'items: [';
                $i++;
        } else {
                echo ',';
        }
?>
{"src":"/CarSystem/<?=$row["pic5"];?>","srct":"/CarSystem/<?=$row["pic5"];?>","title":"pic5","customData":{"id":675340,"selected":true,"origin_url":"/CarSystem/<?=$row["pic5"];?>"}}
<?php } ?>

<?php
if( $row["pic1"] != "" && $media_video[$row["pic1_type"]] != "" ) {
        if( $i == 0 ) {
                echo 'items: [';
                $i++;
        } else {
		echo ',';
	}
?>
{"src":"/CarSystem/<?=$row["pic1"];?>","srct":"/hsinchu/assets/video-player-e07c798bcb9faae5aae7ff042828949e27ff115e3bc8091446cd3a4ce8f0db38.png","thumbnailOpenImage":false,"title":"video","customData":{"id":675341,"selected":false,"origin_url":"/CarSystem/<?=$row["pic1"];?>"}}
<?php } ?>
<?php
if( $row["pic2"] != "" && $media_video[$row["pic2_type"]] != "" ) {
        if( $i == 0 ) {
                echo 'items: [';
                $i++;
        } else {
                echo ',';
        }
?>
{"src":"/CarSystem/<?=$row["pic2"];?>","srct":"/hsinchu/assets/video-player-e07c798bcb9faae5aae7ff042828949e27ff115e3bc8091446cd3a4ce8f0db38.png","thumbnailOpenImage":false,"title":"video","customData":{"id":675341,"selected":false,"origin_url":"/CarSystem/<?=$row["pic2"];?>"}}
<?php } ?>
<?php
if( $row["pic3"] != "" && $media_video[$row["pic3_type"]] != "" ) {
        if( $i == 0 ) {
                echo 'items: [';
                $i++;
        } else {
                echo ',';
        }
?>
{"src":"/CarSystem/<?=$row["pic3"];?>","srct":"/hsinchu/assets/video-player-e07c798bcb9faae5aae7ff042828949e27ff115e3bc8091446cd3a4ce8f0db38.png","thumbnailOpenImage":false,"title":"video","customData":{"id":675341,"selected":false,"origin_url":"/CarSystem/<?=$row["pic3"];?>"}}
<?php } ?>
<?php
if( $row["pic4"] != "" && $media_video[$row["pic4_type"]] != "" ) {
        if( $i == 0 ) {
                echo 'items: [';
                $i++;
        } else {
                echo ',';
        }
?>
{"src":"/CarSystem/<?=$row["pic4"];?>","srct":"/hsinchu/assets/video-player-e07c798bcb9faae5aae7ff042828949e27ff115e3bc8091446cd3a4ce8f0db38.png","thumbnailOpenImage":false,"title":"video","customData":{"id":675341,"selected":false,"origin_url":"/CarSystem/<?=$row["pic4"];?>"}}
<?php } ?>
<?php
if( $row["pic5"] != "" && $media_video[$row["pic5_type"]] != "" ) {
        if( $i == 0 ) {
                echo 'items: [';
                $i++;
        } else {
                echo ',';
        }
?>
{"src":"/CarSystem/<?=$row["pic5"];?>","srct":"/hsinchu/assets/video-player-e07c798bcb9faae5aae7ff042828949e27ff115e3bc8091446cd3a4ce8f0db38.png","thumbnailOpenImage":false,"title":"video","customData":{"id":675341,"selected":false,"origin_url":"/CarSystem/<?=$row["pic5"];?>"}}
<?php } ?>
<?php
	$resultpic = $pdo->query("select * from `cases_img` where `cid`='".$_GET["id"]."' order by `ts` asc");
	while($rowpic = $resultpic->fetch(PDO::FETCH_ASSOC)){
?>
	,{"src":"/CarSystem/<?=$rowpic["img"];?>","srct":"/CarSystem/<?=$rowpic["img"];?>","title":"","customData":{"id":<?php echo $_GET["id"].$rowpic["id"];?>,"selected":true,"origin_url":"/CarSystem/<?=$rowpic["img"];?>"}}
<?php } 
if( $i==0 )
	echo 'items:[';
?>
],

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
      $.post("/new/admin/cases_edit.php#", {case_ids: case_id, department_id: department_id})
    };

    $(function(){
<?php
if( $_GET["commit"] == "搜尋" ) { ?>
	$('.back2list').attr('href', "casesall.php?min=<?=$_GET["min"];?>&max=<?=$_GET["max"];?>&page=<?=$_SESSION["page"];?>&full_car_number=<?=$_GET["full_car_number"];?>&commit=<?=$_GET["commit"];?>")
<?php } else { ?>
	$('.back2list').attr('href', "casesall.php?min=<?=$_GET["min"];?>&max=<?=$_GET["max"];?>&page=<?=$_SESSION["page"];?>&full_car_number=<?=$_GET["full_car_number"];?>")
<?php } ?>
      $('nav li.cases_ready').addClass('active');
      $('input[name="case[expose]"]').change(function() {

        if($(this).val() === '1' ){
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
<?php if( $row["state"] = "ready" ) { ?>
                        $('.car_form').hide()
	                $('.reason_form').hide()
	                $('.unexpose_fields').hide()
<?php } ?>
<?php /*if( $row["expose"] == "核發" && $row["state"] != "ready" ) { ?>
                  $('.car_form').hide()
                  $('.reason_form').hide()
		  $('.unexpose_fields').show()
<?php }*/ ?>

<?php /*if( $row["expose"] == "退件" && $row["state"] = "ready" ) { ?>
                  $('.car_form').hide()
                  $('.reason_form').hide()
                  $('.unexpose_fields').show()
<?php }*/ ?>

    })
	   // location.reload(true);
  </script>

  </body>



</html>

