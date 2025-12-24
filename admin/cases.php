<?php
include "function.php";
require_once("../vendor/phpmailer/phpmailer/src/Exception.php");
require_once("../vendor/phpmailer/phpmailer/src/PHPMailer.php");
require_once("../vendor/phpmailer/phpmailer/src/SMTP.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if( $_GET["state"] == "no" )
	$nav = "flag6";
else
	$nav = "cases";
$putdata=file_get_contents('php://input');
$buffa = explode('&',urldecode($putdata));
$buffb = explode('=',$buffa[1]);
if( $buffa[0] == "_method=put" && $buffb != "" && $_GET["id"] != "" ) {
        $pdo->prepare("UPDATE `cases` set `comment`=? where `id`=?")->execute(array($buffb[1],intval($_GET["id"])));
	exit;
}

/*
$str_array = new array('AYY-0129','AMZ-7963','BFF-3318','AFQ-9650','032-LDW');
$white_list = "";
foreach ($str_array as $item)
{
	    $white_list .= $item . ",";
}
$white_list = rtrim($str, ",");
$pdo->query("INSERT INTO `cases_white` SELECT * FROM `cases` WHERE IN ($white_list)"); 
$pdo->query("DELETE FROM `cases` WHERE `full_car_number` IN ($white_list)");
 */

$result = $pdo->query("select `id`,`pic5` from `cases` where state='complete' and (pic5 is not NULL or pic5 != '')");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
	$pic5 = '/var/www/html/CarSystem/';
	$pic5 .= $row["pic5"];
	if( file_exists($pic5) ) {
		unset( $pic5 );
		$pdo->prepare("UPDATE `cases` set `pic5`=? where `id`=?")->execute(array(NULL,$row["id"]));
	}
}

$result = $pdo->query("select `id`,`DetectLocation`,`groupp1`,`groupp2`,`groupp3` from `cases` where `DetectLocation` is NULL");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
$buffa = $row["groupp1"].','.$row["groupp2"].','.$row["groupp3"];
$pdo->prepare("UPDATE `cases` set `DetectLocation`=? where `id`=?")->execute(array($buffa,$row["id"]));
}

$result = $pdo->query("select `id`,`created_at` from `cases` where `create_date` is NULL");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
	        $create_date=substr($row["created_at"],0,10);
		$create_time=substr($row["created_at"],11,8);
$pdo->prepare("UPDATE `cases` set `create_date`=?,`create_time`=? where `id`=?")->execute(array($create_date,$create_time,$row["id"]));
}

$result = $pdo->query("select * from `cases` where `create_date` is NULL");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
	$create_date=substr($row["created_at"],0,10);
	$create_time=substr($row["created_at"],11,8);
	$pdo->prepare("UPDATE `cases` set `create_date`=?,`create_time`=? where `id`=?")->execute(array($create_date,$create_time,$row["id"]));
}

$result = $pdo->query("select `id`,`create_date`,`create_time` from `cases` where `cam_place`='新竹市東區中央路、民生路口' and `cam_reason`='未依標誌標線行駛'");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
	$check_holi = $pdo->query('select * from `holi_date` where `date`="'.$row["create_date"].'"');
	$sn_sum = $check_holi->rowCount();	
	if( $sn_sum == 0 ) {
		$pdo->prepare("DELETE from `cases` where `id`=?")->execute(array($row["id"]));		
	}
}

$result = $pdo->query("select `id`,`create_date`,`create_time` from `cases` where `cam_place`='新竹市東區中央路、民生路口' and `cam_reason`='未依標誌標線行駛' and (`create_time`<='10:59:59' or `create_time`>='20:00:00')");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
	$pdo->prepare("DELETE from `cases` where `id`=?")->execute(array($row["id"]));
}

$result = $pdo->query("select * from `cases` where `cam_place`='新竹市東區中央路、民權路口' and `cam_reason`='違規迴轉' and `create_date` is NULL");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
$create_date=substr($row["created_at"],0,10);
$create_time=substr($row["created_at"],11,8);
$pdo->prepare("UPDATE `cases` set `create_date`=?,`create_time`=? where `id`=?")->execute(array($create_date,$create_time,$row["id"]));
}

$result = $pdo->query("select `id`,`create_date`,`create_time` from `cases` where `cam_place`='新竹市東區中央路、民權路口' and `cam_reason`='違規迴轉'");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
        $check_holi = $pdo->query('select * from `holi_date` where `date`="'.$row["create_date"].'"');
        $sn_sum = $check_holi->rowCount();
        if( $sn_sum == 0 ) {
                $pdo->prepare("DELETE from `cases` where `id`=?")->execute(array($row["id"]));
        }
}

$result = $pdo->query("select `id`,`create_date`,`create_time` from `cases` where `cam_place`='新竹市東區中央路、民權路口' and `cam_reason`='違規迴轉' and (`create_time`<='10:59:59' or `create_time`>='20:00:00')");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
        $pdo->prepare("DELETE from `cases` where `id`=?")->execute(array($row["id"]));
}

$result = $pdo->query("select * from `violation` where `DetectLocation`='中正延平' and `flag`='0' order by `RowNo` asc");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
	$carnumber = explode('-',$row["CarNumber"]);
	$sn_date = str_replace('-','',substr($row["Datetime"],0,10));
	$check_sn = $pdo->query('select * from `cases` where `DetectLocation`="新竹火車站" and `sn` like "%'.$sn_date.'%" order by `sn` desc limit 1');
	$sn_sum = $check_sn->rowCount();
		if( $sn_sum == 0 ) {
			$sn = 'E'.$sn_date.'00001';
		} else {
			$row_sn = $check_sn->fetch(PDO::FETCH_ASSOC);
			$sn = 'E'.(substr($row_sn["sn"],1,13)+1);
		}
	$pdo->prepare("INSERT INTO `cases` (`id`, `car_type_code`, `first_car_number`, `last_car_number`, `sn`, `DetectLocation`, `Location`, `reason_code`, `full_car_number`, `VideoURL`, `PhotoURL`, `comment`, `review_user_id`, `approve_user_id`, `state`, `created_at`, `updated_at`) VALUES (NULL, NULL, ?, ?, ?, ?, ?, NULL, ?, ?, ?, NULL, NULL, NULL, ?,?,?)")->execute(array($carnumber[0],$carnumber[1],$sn,$row["DetectLocation"],$row["Location"],$row["CarNumber"],$row["VideoURL"],$row["PhotoURL"],'ready',$row["Datetime"],$row["Datetime"]));
	$pdo->prepare("UPDATE `violation` set `flag`=? where `RowNo`=?")->execute(array(1,$row["RowNo"]));
}

$result = $pdo->query("select * from `violation` where `DetectLocation`='新竹都城隍廟周邊道路' and `flag`='0' order by `RowNo` asc");
while($row = $result->fetch(PDO::FETCH_ASSOC)){
        $carnumber = explode('-',$row["CarNumber"]);
        $sn_date = str_replace('-','',substr($row["Datetime"],0,10));
        $check_sn = $pdo->query('select * from `cases` where `DetectLocation`="新竹都城隍廟周邊道路" and `sn` like "%'.$sn_date.'%" order by `sn` desc limit 1');
        $sn_sum = $check_sn->rowCount();
                if( $sn_sum == 0 ) {
                        $sn = 'T'.$sn_date.'00001';
                } else {
                        $row_sn = $check_sn->fetch(PDO::FETCH_ASSOC);
                        $sn = 'T'.(substr($row_sn["sn"],1,13)+1);
                }
	$VideoURL=str_replace('/var/www/html/CarSystem/','',$row["VideoURL"]);
	$PhotoURL=str_replace('/var/www/html/CarSystem/','',$row["PhotoURL"]);
        $pdo->prepare("INSERT INTO `cases` (`id`, `car_type_code`, `first_car_number`, `last_car_number`, `sn`, `DetectLocation`, `Location`, `reason_code`, `full_car_number`, `VideoURL`, `PhotoURL`, `comment`, `review_user_id`, `approve_user_id`, `state`, `created_at`, `updated_at`) VALUES (NULL, NULL, ?, ?, ?, ?, ?, NULL, ?, ?, ?, NULL, NULL, NULL, ?,?,?)")->execute(array($carnumber[0],$carnumber[1],$sn,$row["DetectLocation"],$row["Location"],$row["CarNumber"],$VideoURL,$PhotoURL,'ready',$row["Datetime"],$row["Datetime"]));
        $pdo->prepare("UPDATE `violation` set `flag`=? where `RowNo`=?")->execute(array(1,$row["RowNo"]));
}


if( $_GET["act"] == "complete" ) {
if( $_POST["case_ids"] != "" ) {
	foreach( $_POST["case_ids"] as $key => $value ) {
        $cdate = date("Y-m-d H:i:s");
	$complete_date=date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=?,`complete_date`=? where `id`=?")->execute(array('complete',$complete_date,$value));
        $msg = "從案件審核 > 案件結束(".$row_user["name"].")";
        $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array($value,$row_user["id"],$msg,$cdate));
}
}
}

if( $_GET["act"] == "todepartment" ) {
if( $_POST["case_ids"] != "" && $_POST["department_id"] != "" ) {
        foreach( $_POST["case_ids"] as $key => $value ) {
        $cdate = date("Y-m-d H:i:s");
        $complete_date=date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=?,`auto_department`=? where `id`=?")->execute(array('nready',$_POST["department_id"],$value));
        $msg = "案件改派 (".$row_user["name"].")";
        $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array($value,$row_user["id"],$msg,$cdate));
	}
	exit;
}
}

if( $_GET["act"] == "touserid" ) {
if( $_POST["case_ids"] != "" && $_POST["userid"] != "" ) {
        foreach( $_POST["case_ids"] as $key => $value ) {
        $cdate = date("Y-m-d H:i:s");
        $complete_date=date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=?,`userid`=? where `id`=?")->execute(array('ready',$_POST["userid"],$value));
        $msg = "從員警領案 > 案件指派 (".$row_user["name"].")";
        $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array($value,$row_user["id"],$msg,$cdate));
        }
        exit;
}
}

if( $_GET["act"] == "todepartment2" ) {
if( $_POST["case_ids"] != "" ) {
        foreach( $_POST["case_ids"] as $key => $value ) {
        $cdate = date("Y-m-d H:i:s");
        $complete_date=date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array('no',$value));
        $msg = "從員警領案 > 案件改派 (".$row_user["name"].")";
        $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array($value,$row_user["id"],$msg,$cdate));
        }
        exit;
}
}


if( $_GET["act"] == "toready" ) {
if( $_POST["case_ids"] != "" && $_POST["userid"] != "" ) {
        foreach( $_POST["case_ids"] as $key => $value ) {
        $cdate = date("Y-m-d H:i:s");
        $complete_date=date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=?,`userid`=? where `id`=?")->execute(array('ready',$_POST["userid"],$value));
        $msg = "從員警領案 > 案件處理 (".$row_user["name"].")";
        $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array($value,$row_user["id"],$msg,$cdate));
	}
        exit;
}
}

if( $_GET["act"] == "allreview" ) {
if( $_POST["case_ids"] != "" && $_POST["expose"] != "" && $_POST["expose_msg"] != "" && $_POST["review_user_id"] != "" && $_POST["review_department"] != "" ) {
        foreach( $_POST["case_ids"] as $key => $value ) {
        $cdate = date("Y-m-d H:i:s");
        $complete_date=date("Y-m-d H:i:s");
	if( $_POST["expose"] == "舉發" ) {

$resultb = $pdo->query("select * from `cases` where `id`='".$value."'");
$rowb = $resultb->fetch(PDO::FETCH_ASSOC);
$jpg_num = 0;
if( $rowb["pic1_do"] == "1" )
        $jpg_num++;
if( $rowb["pic2_do"] == "1" )
        $jpg_num++;
if( $rowb["pic3_do"] == "1" )
        $jpg_num++;
if( $rowb["pic4_do"] == "1" )
        $jpg_num++;
if( $rowb["pic5_do"] == "1" )
        $jpg_num++;
$result_img = $pdo->query("select * from `cases_img` where `cid`='".$value."' and `flag`='1'");
while($rowimg = $result_img->fetch(PDO::FETCH_ASSOC)) {
        $jpg_num++;
}
	if( $jpg_num >= 1 ) {
        	$pdo->prepare("UPDATE `cases` set `state`=?,`review_user_id`=?,`review_department`=?,`expose`=?,`reason_code`=? where `id`=?")->execute(array('reviewing',$_POST["review_user_id"],$_POST["review_department"],$_POST["expose"],$_POST["expose_msg"],$value));
		}
	} else {
		$pdo->prepare("UPDATE `cases` set `state`=?,`review_user_id`=?,`review_department`=?,`expose`=?,`expose_msg`=? where `id`=?")->execute(array('reviewing',$_POST["review_user_id"],$_POST["review_department"],$_POST["expose"],$_POST["expose_msg"],$value));
	}
        $msg = "從案件處理 > 案件審核 (".$row_user["name"].")";
        $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array($value,$row_user["id"],$msg,$cdate));


$result_mail = $pdo->query("select * from `cases` where `id`='".intval($value)."'");
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



        }
        exit;
}
}

if( $_GET["act"] == "alltonready" ) {
if( $_POST["case_ids"] != "" ) {
        foreach( $_POST["case_ids"] as $key => $value ) {
        $cdate = date("Y-m-d H:i:s");
        $complete_date=date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=?,`userid`=? where `id`=?")->execute(array('nready',NULL,$value));
        $msg = "從案件處理 > 員警領案 (".$row_user["name"].")";
        $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array($value,$row_user["id"],$msg,$cdate));
        }
        exit;
}
}

if( $_GET["act"] == "alltoreview" ) {
if( $_POST["case_ids"] != "" ) {
        foreach( $_POST["case_ids"] as $key => $value ) {
        $cdate = date("Y-m-d H:i:s");
        $complete_date=date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array('reviewing',$value));
        $msg = "從案件複審 > 案件審核 (".$row_user["name"].")";
        $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array($value,$row_user["id"],$msg,$cdate));
        }
        exit;
}
}


if( $_GET["act"] == "alltoready" ) {
if( $_POST["case_ids"] != "" ) {
        foreach( $_POST["case_ids"] as $key => $value ) {
        $cdate = date("Y-m-d H:i:s");
        $complete_date=date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array('ready',$value));
        $msg = "從案件審核 > 案件處理 (".$row_user["name"].")";
        $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array($value,$row_user["id"],$msg,$cdate));
        }
        exit;
}
}

if( $_GET["act"] == "alltorecheck" ) {
if( $_POST["case_ids"] != "" ) {
        foreach( $_POST["case_ids"] as $key => $value ) {
        $cdate = date("Y-m-d H:i:s");
        $complete_date=date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array('recheck',$value));
        $msg = "從案件審核 > 案件複審 (".$row_user["name"].")";
        $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array($value,$row_user["id"],$msg,$cdate));
        }
        exit;
}
}

if( $_GET["act"] == "alltocomplete" ) {
if( $_POST["case_ids"] != "" ) {
        foreach( $_POST["case_ids"] as $key => $value ) {
        $cdate = date("Y-m-d H:i:s");
        $complete_date=date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array('complete',$value));
        $msg = "從案件複審 > 案件結束 (".$row_user["name"].")";
        $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array($value,$row_user["id"],$msg,$cdate));
        }
        exit;
}
}

if( $_GET["act"] == "ready" ) {
if( $_POST["case_ids"] != "" ) {
        foreach( $_POST["case_ids"] as $key => $value ) {
        $cdate = date("Y-m-d H:i:s");
        $pdo->prepare("UPDATE `cases` set `state`=? where `id`=?")->execute(array('ready',$value));
	$msg = '從案件審核 > 案件處理('.$row_user["name"].')';
        $pdo->prepare("INSERT INTO `case_history` (`cases_id`,`user_id`,`message`,`cdate`) VALUES ( ?, ?, ?, ? )")->execute(array($value,$row_user["id"],$msg,$cdate));
}
}
}

if( $row_user["flag4"] == "1" ) { 
	if( $_GET["act"] == "del" && $_GET["did"] != "" ) {
		$pdo->prepare("DELETE from `cases` where `id`=?")->execute(array($_GET["did"]));
	}
}

if( $_POST["act"] == "cam_place" ) {
        if( $_POST["cam_place"] != "" ) {
                $cam_id = time();
        $_SESSION['formdata'][2]['a'][0]['name']='cam_place';
        $_SESSION['formdata'][2]['p']='eq';
        $_SESSION['formdata'][2]['v'][0]['value']=$_POST["cam_place"];
        } else {
        unset( $_SESSION['formdata'][2] );
        }
}

if( $_POST["act"] == "cam_reason" ) {
        if( $_POST["cam_reason"] != "" ) {
                $cam_id = time();
        $_SESSION['formdata'][3]['a'][0]['name']='cam_reason';
        $_SESSION['formdata'][3]['p']='eq';
        $_SESSION['formdata'][3]['v'][0]['value']=$_POST["cam_reason"];
        } else {
        unset( $_SESSION['formdata'][3] );
        }
}

if( $_POST["act"] == "expose" ) {
        if( $_POST["expose"] != "" ) {
                $cam_id = time();
        $_SESSION['formdata'][5]['a'][0]['name']='expose';
        $_SESSION['formdata'][5]['p']='eq';
        $_SESSION['formdata'][5]['v'][0]['value']=$_POST["expose"];
        } else {
        unset( $_SESSION['formdata'][5] );
        }
}


if( $_POST["act"] == "create_date" ) {
        if( $_POST["create_date"] != "" ) {
                $cam_id = time();
        $_SESSION['formdata'][4]['a'][0]['name']='create_date';
        $_SESSION['formdata'][4]['p']='eq';
        $_SESSION['formdata'][4]['v'][0]['value']=$_POST["create_date"];
        } else {
        unset( $_SESSION['formdata'][4] );
        }
}


if( is_null( $_SESSION['formdata'] ) ) {
if( $_GET["state"] == "ready" ) {
	$_SESSION['formdata'][0]['a'][0]['name']='state';
	$_SESSION['formdata'][0]['p']='eq';
	$_SESSION['formdata'][0]['v'][0]['value']='ready';

	/*
	if( $row_user["department"] == "第一分局" ) {
        $_SESSION['formdata'][1]['a'][0]['name']='DetectLocation';
        $_SESSION['formdata'][1]['p']='eq';
        $_SESSION['formdata'][1]['v'][0]['value']='新竹都城隍廟周邊道路';
	} else if( $row_user["department"] == "第二分局" ) {
        $_SESSION['formdata'][1]['a'][0]['name']='DetectLocation';
        $_SESSION['formdata'][1]['p']='eq';
        $_SESSION['formdata'][1]['v'][0]['value']='新竹火車站';
	}
	 */
} else if( $_GET["state"] == "nready" ) {
        $_SESSION['formdata'][0]['a'][0]['name']='state';
        $_SESSION['formdata'][0]['p']='eq';
        $_SESSION['formdata'][0]['v'][0]['value']='nready';
} else if( $_GET["state"] == "no" ) {
        $_SESSION['formdata'][0]['a'][0]['name']='state';
        $_SESSION['formdata'][0]['p']='eq';
        $_SESSION['formdata'][0]['v'][0]['value']='no';
} else if( $_GET["state"] == "recheck" ) {
        $_SESSION['formdata'][0]['a'][0]['name']='state';
        $_SESSION['formdata'][0]['p']='eq';
        $_SESSION['formdata'][0]['v'][0]['value']='recheck';
} else if( $_GET["state"] == "reviewing" ) {
        $_SESSION['formdata'][0]['a'][0]['name']='state';
        $_SESSION['formdata'][0]['p']='eq';
        $_SESSION['formdata'][0]['v'][0]['value']='reviewing';

	/*	
        if( $row_user["department"] == "第一分局" ) {
        $_SESSION['formdata'][1]['a'][0]['name']='DetectLocation';
        $_SESSION['formdata'][1]['p']='eq';
        $_SESSION['formdata'][1]['v'][0]['value']='新竹都城隍廟周邊道路';
        } else if( $row_user["department"] == "第二分局" ) {
        $_SESSION['formdata'][1]['a'][0]['name']='DetectLocation';
        $_SESSION['formdata'][1]['p']='eq';
        $_SESSION['formdata'][1]['v'][0]['value']='新竹火車站';
        }
	*/
}
} else {
if( $_GET["state"] == "ready" ) {
        $_SESSION['formdata'][]=array('a'=>array(0=>array('name'=>'state')),'p'=>'eq','v'=>array(0=>array('value'=>'ready')));
} else if( $_GET["state"] == "reviewing" ) {
	$_SESSION['formdata'][]=array('a'=>array(0=>array('name'=>'state')),'p'=>'eq','v'=>array(0=>array('value'=>'reviewing')));
}
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
</head>

  <body id="page-top" class="case_wrapper cases index collection">
<?php include "menu.php";?>



    <div id="wrapper">


      <div id="content-wrapper">
        <div class="container-fluid">
          <div class="flashes">
            

          </div>

          








<?php //if( $row_user["flag4"] == "1" ) { ?>
<!--<div class="card mb-3 search">
  <div class="card-header">
    篩選條件
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-sm-6 col-12">
<?php if( !isset($_GET["state"]) ) { ?>
<form class="search-form" id="case_search" autocomplete="off" action="cases.php" accept-charset="UTF-8" method="post">
<?php } else { ?>
<form class="search-form" id="case_search" autocomplete="off" action="cases.php?state=<?=$_GET["state"];?>" accept-charset="UTF-8" method="post">
<?php } ?>
	<input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="authenticity_token" value="j3/lL1eQpbJQX2nsNmnYnB0YepKTccVgjYBPfbJ6zdZF7S3stVLHykb5++JvY6jaYeGtFHmJr1TA+b305mNN9w==" />
          
	  <!--<a class="add_search_fields btn btn-sm btn-success" data-id="new_condition" data-fields="&lt;div class=&quot;input-group mb-3 field&quot;&gt;  &lt;div class=&quot;input-group-prepend&quot;&gt;          &lt;select class=&quot;custom-select attribute-select&quot; name=&quot;q[c][new_condition][a][0][name]&quot; id=&quot;q_c_new_condition_a_0_name&quot;&gt;&lt;option value=&quot;&quot;&gt;請選擇欄位&lt;/option&gt;&lt;optgroup label=&quot;科技執法案件&quot;&gt;&lt;option value=&quot;full_car_number&quot;&gt;違規車號&lt;/option&gt;&lt;option value=&quot;sn&quot;&gt;案件編號&lt;/option&gt;&lt;option value=&quot;contact_address&quot;&gt;聯絡地址&lt;/option&gt;&lt;option value=&quot;expose&quot;&gt;是否舉發&lt;/option&gt;&lt;option value=&quot;illegality_details&quot;&gt;違規事實說明&lt;/option&gt;&lt;option value=&quot;created_at&quot;&gt;違規日期／時間&lt;/option&gt;&lt;option value=&quot;phone&quot;&gt;聯絡電話&lt;/option&gt;&lt;option value=&quot;addr1&quot;&gt;巷&lt;/option&gt;&lt;option value=&quot;addr2&quot;&gt;弄&lt;/option&gt;&lt;option value=&quot;addr3&quot;&gt;號&lt;/option&gt;&lt;option value=&quot;addr4&quot;&gt;之號&lt;/option&gt;&lt;option value=&quot;addr5&quot;&gt;公里處&lt;/option&gt;&lt;option value=&quot;area_district&quot;&gt;違規區域&lt;/option&gt;&lt;option value=&quot;addr_detail&quot;&gt;詳細地址&lt;/option&gt;&lt;option value=&quot;email&quot;&gt;Email&lt;/option&gt;&lt;option value=&quot;name&quot;&gt;檢舉人姓名&lt;/option&gt;&lt;option value=&quot;id_number&quot;&gt;身分證字號&lt;/option&gt;&lt;option value=&quot;state&quot;&gt;狀態&lt;/option&gt;&lt;option value=&quot;created_at&quot;&gt;檢舉日期／時間&lt;/option&gt;&lt;/optgroup&gt;&lt;optgroup label=&quot;違規事實&quot;&gt;&lt;option value=&quot;illegality_code&quot;&gt;違規項目代碼&lt;/option&gt;&lt;option value=&quot;illegality_name&quot;&gt;違規事實名稱&lt;/option&gt;&lt;/optgroup&gt;&lt;optgroup label=&quot;違規街道&quot;&gt;&lt;option value=&quot;area_code&quot;&gt;違規街道代碼&lt;/option&gt;&lt;option value=&quot;area_name&quot;&gt;街道名稱&lt;/option&gt;&lt;/optgroup&gt;&lt;optgroup label=&quot;違規法條&quot;&gt;&lt;option value=&quot;expose_reason_code&quot;&gt;違規法條代碼&lt;/option&gt;&lt;option value=&quot;expose_reason_name&quot;&gt;違規法條名稱&lt;/option&gt;&lt;/optgroup&gt;&lt;optgroup label=&quot;單位&quot;&gt;&lt;option value=&quot;department_name&quot;&gt;單位名稱&lt;/option&gt;&lt;/optgroup&gt;&lt;optgroup label=&quot;員警&quot;&gt;&lt;option value=&quot;approved_admin_user_name&quot;&gt;姓名&lt;/option&gt;&lt;/optgroup&gt;&lt;/select&gt;  &lt;/div&gt;  &lt;div class=&quot;input-group-prepend&quot;&gt;    &lt;select class=&quot;custom-select search-condition&quot; name=&quot;q[c][new_condition][p]&quot; id=&quot;q_c_new_condition_p&quot;&gt;&lt;option value=&quot;eq&quot;&gt;等於&lt;/option&gt;&lt;option value=&quot;not_eq&quot;&gt;不等於&lt;/option&gt;&lt;option value=&quot;lteq&quot;&gt;小於或等於&lt;/option&gt;&lt;option value=&quot;gteq&quot;&gt;大於或等於&lt;/option&gt;&lt;option selected=&quot;selected&quot; value=&quot;cont&quot;&gt;包含&lt;/option&gt;&lt;/select&gt;  &lt;/div&gt;  &lt;div class=&quot;search-input-wrapper&quot;&gt;          &lt;input class=&quot;form-control search-value-input input-sm&quot; type=&quot;text&quot; name=&quot;q[c][new_condition][v][0][value]&quot; id=&quot;q_c_new_condition_v_0_value&quot; /&gt;  &lt;/div&gt;  &lt;div class=&quot;input-group-append&quot;&gt;    &lt;a class=&quot;remove_search_fields btn btn-sm btn-danger&quot; href=&quot;#&quot;&gt;&lt;i class=&quot;fas fa-minus-circle&quot;&gt;&lt;/i&gt;&lt;/a&gt;  &lt;/div&gt;&lt;/div&gt;" href="#">-->
<!--<a class="add_search_fields btn btn-sm btn-success" data-id="new_condition" data-fields="&lt;div class=&quot;input-group mb-3 field&quot;&gt;  &lt;div class=&quot;input-group-prepend&quot;&gt;          &lt;select class=&quot;custom-select attribute-select&quot; name=&quot;q[c][new_condition][a][0][name]&quot; id=&quot;q_c_new_condition_a_0_name&quot;&gt;&lt;option value=&quot;&quot;&gt;請選擇欄位&lt;/option&gt;&lt;optgroup label=&quot;檢舉案件&quot;&gt;&lt;option value=&quot;DetectLocation&quot;&gt;檢舉地點&lt;/option&gt;&lt;option value=&quot;cam_ip&quot;&gt;違規地點&lt;/option&gt;&lt;option value=&quot;illegality_code&quot;&gt;違規事實&lt;/option&gt;&lt;option value=&quot;full_car_number&quot;&gt;違規車號&lt;/option&gt;&lt;option value=&quot;sn&quot;&gt;案件編號&lt;/option&gt;&lt;option value=&quot;created_at&quot;&gt;違規日期／時間&lt;/option&gt;&lt;option value=&quot;expose_reason_code&quot;&gt;違規法條代碼&lt;/option&gt;&lt;option value=&quot;state&quot;&gt;狀態&lt;/option&gt;&lt;/select&gt;  &lt;/div&gt;  &lt;div class=&quot;input-group-prepend&quot;&gt;    &lt;select class=&quot;custom-select search-condition&quot; name=&quot;q[c][new_condition][p]&quot; id=&quot;q_c_new_condition_p&quot;&gt;&lt;option value=&quot;eq&quot;&gt;等於&lt;/option&gt;&lt;option value=&quot;not_eq&quot;&gt;不等於&lt;/option&gt;&lt;option value=&quot;lteq&quot;&gt;小於或等於&lt;/option&gt;&lt;option value=&quot;gteq&quot;&gt;大於或等於&lt;/option&gt;&lt;option selected=&quot;selected&quot; value=&quot;cont&quot;&gt;包含&lt;/option&gt;&lt;/select&gt;  &lt;/div&gt;  &lt;div class=&quot;search-input-wrapper&quot;&gt;          &lt;input class=&quot;form-control search-value-input input-sm&quot; type=&quot;text&quot; name=&quot;q[c][new_condition][v][0][value]&quot; id=&quot;q_c_new_condition_v_0_value&quot; /&gt;  &lt;/div&gt;  &lt;div class=&quot;input-group-append&quot;&gt;    &lt;a class=&quot;remove_search_fields btn btn-sm btn-danger&quot; href=&quot;#&quot;&gt;&lt;i class=&quot;fas fa-minus-circle&quot;&gt;&lt;/i&gt;&lt;/a&gt;  &lt;/div&gt;&lt;/div&gt;" href="#"><i class="fas fa-plus-circle"></i></a>

          <!--<input type="submit" name="commit" value="搜尋" class="btn btn-sm btn-primary" data-disable-with="搜尋" />
</form>      </div>
    </div>
  </div>
</div>-->
<?php //} ?>

<?php
$sqlx="";
$camchk=0;
if( !is_null($_SESSION['formdata'])){ 
        $i=0;
foreach( $_SESSION['formdata'] as $key => $value ) {
        $i++;
        if( $i == 1 ) {
		if( $value["a"][0]["name"] == "cam_ip" ) {
			$sqlx .= "where ( ";
			$camchk=1;
		} else {
			$sqlx .= "where ";
		}
	}
        if( $i >= 2 ) {
		if( $camchk == 1 && $value["a"][0]["name"] != "cam_ip" ) {
		        $sqlx .= " ) ";
			$camchk = 0;
		}

		if( $value["a"][0]["name"] == "cam_ip" && $camchk==0 ) {
			$sqlx .= " and ( ";
			$camchk=1;
		} else if( $value["a"][0]["name"] == "cam_ip" && $camchk==1 ) {
			$sqlx .= " or ";
		} else {
                	$sqlx .= " and ";
		}

	}
        if( $value["p"] == "cont" ) {
        $sqlx .= "".$value["a"][0]["name"]." like '%".$value["v"][0]["value"]."%'";
        } else if( $value["p"] == "eq" ) {
        $sqlx .= "".$value["a"][0]["name"]." = '".$value["v"][0]["value"]."'";
	} else if( $value["p"] == "gteq" ) {
	$sqlx .= "".$value["a"][0]["name"]." >= '".$value["v"][0]["value"]." 00:00:00'";
        } else if( $value["p"] == "lteq" ) {
        $sqlx .= "".$value["a"][0]["name"]." <= '".$value["v"][0]["value"]." 23:59:59'";
        } else {
        $sqlx .= "".$value["a"][0]["name"]." != '".$value["v"][0]["value"]."'";
        }
}}

if( is_null($_SESSION['formdata'])){
	$sqlx .= "where ";	
}  else {
}
	
$orcheck=0;
if( $_POST["commit"] == "" ) {
        if( $orcheck == 0 ) {
		if( $_GET["state"] != "no" ) {
                $orcheck++;
		}
        } else {
		if( $_GET["state"] != "no" ) {
                $sqlx .= " or ";
		}
        }

	if( $_GET["state"] == "nready" ) {
                $sqlx .= " and (`userid`='' or `userid` is NULL)";
	}	

        if( $_GET["state"] == "nready" ) {
                $sqlx .= " and (`userid`='' or `userid` is NULL)";
        }

        if( $_GET["state"] == "ready" ) {
                if( $row_user["id"] != "1" )
                        $sqlx .= " and (`userid`='".$row_user["id"]."' )";
        }

}
        if( !is_null($_SESSION['formdata'])){
        }

if( $sqlx == "where " ) {
	$sqlx .= "`id`!='0'";
}

/*
if( $orcheck == 0 && $row_user["flag4"] == 0 )
	$sqlx .= " `DetectLocation`='none' )";
 */
//if( $orcheck == 0 && $row_user["flag10"] == "1" )
//	$sqlx .= " )";

$buffc = substr_count($sqlx, '(');
$buffd = substr_count($sqlx, ')');
if( $buffd < $buffc )
	$sqlx .= " )";

$limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 20;
$page = (isset($_GET['page']) && is_numeric($_GET['page']) ) ? $_GET['page'] : 1;
$_SESSION["page"] = $page;
$paginationStart = ($page - 1) * $limit;

$result = $pdo->query("select * from `cases` $sqlx");
$allRecrods = $result->rowCount();
$totoalPages = ceil($allRecrods / $limit);

$prev = $page - 1;
$next = $page + 1;
?>
<form class="index-form" action="" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="authenticity_token" value="3yAWcqu002vwKAtMYmM/Xblkq1H2ogMYmF2lVxwdoN16DvVaWK7La0flBTsL+g7PS3fiHmegEEri4iPu+9z0Hw==" />
<input type="hidden" name="event" id="event" />
<div class="card mb-3">
  <div class="card-header">
    <b>申請案件 列表</b>
    (共 <?php echo $allRecrods;?> 筆記錄)
<?php /*if( $row_user["flag4"] == "1" ) { ?>
    <input class="btn btn-warning btn-sm" type="button" value="匯出 Excel" onclick="csv_btn();" />
<?php }*/ ?>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-sm text-wrap table-condensed">
        <thead>
          <tr>
            <th>項次</th>
<?php if( $_GET["state"] == "nready" ) { ?>
<th>選擇</th>
<?php } ?>
<th>重覆篩選</th>
            <th>
              案件編號
            </th>
            <th>
             申請人姓名 
            </th>
            <th>
             申請人證號
            </th>
            <th>
             申請人電話
            </th>
            <th>
             案件類別 
            </th>
            <th>
             申請項目 
            </th>
            <th>
            發生日期 
            </th>
            <th>
              案件狀態
            </th>
            <th>
              限辦日期
            </th>
            <th>
              結案方式 
            </th>
            <th>
              結案日期
            </th>
          </tr>
        </thead>
        <tbody>
<?php
$i=0;
if( $_GET["min"] != "" && $_GET["max"] != "" && $_GET["username2"] != "" ) {
	        $result = $pdo->query("select * from `cases` where `username2`='".$_GET["username2"]."' and `created_at`>='".$_GET["max"]."' and `created_at`<='".$_GET["min"]."' order by `created_at` desc LIMIT $paginationStart, $limit");
} else {
$result = $pdo->query("select * from `cases` $sqlx $usersql order by `created_at` desc LIMIT $paginationStart, $limit");
}
while($row = $result->fetch(PDO::FETCH_ASSOC)){
$i++;
	$buff2= strtotime($row["created_at"]) - 60*60*2;
	$buff2= date('Y-m-d H:i:s', $buff2);
        $buff4= strtotime($row["created_at"]) - 60*60*4;
	$buff4= date('Y-m-d H:i:s', $buff4);
	if( $row["full_car_number"] != "" ) {
	$result2hr = $pdo->query("select `full_car_number`,`created_at` from `cases` where `full_car_number`='".$row["full_car_number"]."' and `created_at`<'".$row["created_at"]."' and `created_at`>='".$buff2."'"); 
	$num_rows = $result2hr->rowCount();
	$result4hr = $pdo->query("select `full_car_number`,`created_at` from `cases` where `full_car_number`='".$row["full_car_number"]."' and `created_at`<'".$row["created_at"]."' and `created_at`>='".$buff4."'");
	$num_rows4 = $result4hr->rowCount();
	}
	//$row2hr = $result2hr->fetch(PDO::FETCH_ASSOC);
?>
  <tr id="case_<?php echo $row["id"];?>" class="normal">
  <td><?php 
	echo $i;
?></td>
<?php if( $_GET["state"] == "nready" ) { ?>
           <td onclick="(function(e, obj){ if(e.target.type !== 'checkbox') {$(':checkbox', obj).trigger('click'); if($(':checkbox', obj).is(':checked')){$(obj).addClass('active')}else{$(obj).removeClass('active')};} })(event, this)">
	      <input type="checkbox" name="case_ids[]" value="<?=$row["id"];?>" class="select_checkbox" />
            </td>
<?php
	if( $num_rows >= 1 && $num_rows4 == 1 ) {
	//	echo '<br><font size="1" color="red">(2小時內重複)</font>';
	} 

?>
</td>
<?php } ?>
<?php
$day7 = date('Y-m-d H:i:s',strtotime($row["created_at"] . "-60 days"));
	$resultm = $pdo->query("select * from `cases` where `username2`='".$row["username2"]."' and `id`!='".$row["id"]."' and `created_at`>='".$day7."' and `created_at`<='".$row["created_at"]."'");
$mul=0;
while($rowm = $resultm->fetch(PDO::FETCH_ASSOC)){
        $mul++;
}
?>
        <td><?php
if( $mul != 0 && $row["username2"]!="" && $row["username2"]!=" ") {
	        echo '<a href="cases.php?username2='.$row["username2"].'&min='.$row["created_at"].'&max='.$day7.'">'.$mul.'</a>';
} else {
	        echo '';
}
?></td>

<!--<td width="10%">
<span data-bip-type="input" data-bip-attribute="comment" data-bip-placeholder="           " data-bip-object="case" data-bip-ok-button="儲存" data-bip-cancel-button="取消" data-bip-original-content="<?=$row["comment"];?>" data-bip-skip-blur="false" data-bip-url="cases.php?id=<?=$row["id"];?>" data-bip-value="" class="best_in_place" id="best_in_place_case_838354_comment"><?php
        if( mb_strlen( $row["comment"] ) >= 18 )
                        echo mb_substr($row["comment"],0,18,"utf-8").'....';
        else
                        echo $row["comment"];
	if( $row["comment"] == "" )
		echo '　　　　　';
?>
</span>
</td>
<?php if( $_GET["min"] == "" && $_GET["max"] == "" ) { ?>
<td>
<?php
	/*
$resultm = $pdo->query("select * from `cases` where `violated_at` IS NULL");
while($rowm = $resultm->fetch(PDO::FETCH_ASSOC)){
$vio = $rowm["violated_at_date"].' '.$rowm["violated_at_hour"].':'.$rowm["violated_at_min"].':00';
$pdo->prepare("UPDATE `cases` set `violated_at`=? where `id`=?")->execute(array($vio,intval($rowm["id"])));
}
	 */
	$vio = $row["violated_at_date"].' '.$row["violated_at_hour"].':'.$row["violated_at_min"].':00';
        $day30 = date('Y-m-d H:i:s',strtotime($vio . "-30 days"));
$resultm = $pdo->query("select * from `cases` where `full_car_number`='".$row["full_car_number"]."' and `violated_at`>='".$day30."' and `violated_at`<='".$row["violated_at"]."'");
$mul=0;
while($rowm = $resultm->fetch(PDO::FETCH_ASSOC)){
        $mul++;
}
?>
        <?php
if( $mul >= 2 && $row["full_car_number"]!="") {
        echo '<a href="cases.php?full_car_number='.$row["full_car_number"].'&min='.$row["violated_at"].'&max='.$day30.'">'.$mul.'</a>';
} else {
        echo '';
}
?></td>
<?php } ?>
<td><?php echo $row["category_name"];?></td>-->
<td class="case_sn"><a href="cases_edit.php?id=<?php echo $row["id"];?>&state=<?=$row["state"];?>&min=<?=$_GET["min"];?>&max=<?=$_GET["max"];?>&full_car_number=<?=$_GET["full_car_number"];?>"><?php echo $row["sn"];?></a>
<br>
<?php
if( $row["userid"] != "" && $row_user["flag5"] == "1" ) {
	$res_u=$pdo->query("select * from `user_list` where `id`='".$row["userid"]."'");
	$row_u = $res_u->fetch(PDO::FETCH_ASSOC);
	echo '<font size="2" color="red">指派給:'.$row_u["name"].'</font>';
}
?>
</td>
            <td id="cam_reason_<?php echo $row["id"];?>" class="cam_reason">
<?php echo $row["username"];?>
            </td>
            <td id="cam_reason_<?php echo $row["id"];?>" class="cam_reason">
<?php echo $row["idnumber"];?>
            </td>
            <td id="car_type_code_<?php echo $row["id"];?>" class="case_full_car_number">
<?php echo $row["phone"];?>
            </td>
            <td id="full_car_number_<?php echo $row["id"];?>" class="case_full_car_number">
<?php echo $row["category_name"];?>
            </td>
            <td id="created_at_<?php echo $row["id"];?>" class="case_created_at">
<?php
if( !empty($row["groupp1"]))
	echo '<li>'.$row["groupp1"].'</li>';
if( !empty($row["groupp2"]))
	        echo '<li>'.$row["groupp2"].'</li>';
if( !empty($row["groupp3"]))
	        echo '<li>'.$row["groupp3"].'</li>';
?>
            </td>
            <td id="created_at_<?php echo $row["id"];?>" class="case_created_at">
<?php echo substr($row["violated_at"],0,16);?>
            </td>
<td>
<?php echo $row["review_department"].' > ';
if( $row["state"] == "no" )
	echo '案件改派';
else if( $row["state"] == "nready" )
	echo '員警領案';
else if( $row["state"] == "ready" )
	echo '案件處理';
else if( $row["state"] == "reviewing" )
	echo '案件審核';
else if( $row["state"] == "recheck" )
	echo '案件複審';
else if( $row["state"] == "complete" )
	echo '案件結束';
else
	echo 'error';
if( $row["review_user_id"] != "" ) {
	$result_i = $pdo->query("select * from `user_list` where `id`='".$row["review_user_id"]."'");
	$rowi = $result_i->fetch(PDO::FETCH_ASSOC);
	echo ' ( '.$rowi["name"].' )';
}
?>
            </td>
            <td id="deadline_date_<?php echo $row["id"];?>" class="case_deadline_date">
<?php 
if( !empty($row["groupp3"]))
	$qq=25;
else
	$qq=10;
echo date('Y-m-d',strtotime("$row[created_at] +$qq day"));?>
            </td>
<td id="deadline_date_<?php echo $row["id"];?>" class="case_deadline_date">
<?php
if( $row["state"] == "complete" ) {
	echo $row["expose"];
}
?>
	</td>
<td id="deadline_date_<?php echo $row["id"];?>" class="case_deadline_date">
<?=$row["complete_date"];?>
        </td>
	  </tr>
<?php
}
?>
        </tbody>
      </table>
    </div>
</div>
<?php if( $_GET["min"] == "" || $_GET["max"] == "" ) { ?>
<?php if( $_GET["state"] == "no" ) { ?>
<div class="card-footer">
<select size="1" name="cases[department_id]">
<option value="">請選擇分局</option>
<option>交通警察隊</option>
<option>第一分局</option>
<option>第二分局</option>
<option>第三分局</option>
</select>
<a onclick="transform_cases(&#39;todepartment&#39;)" class="btn btn-primary btn-sm" data-remote="true" href="#">案件改派</a>
</div>
<?php } ?>

<?php if( $_GET["state"] == "nready" && $row_user["flag5"] == "1" ) { ?>
<div class="card-footer">
<select size="1" name="cases[userid]">
<option value="">請選擇人員</option>
<?php
if( $row_user["id"] != "1" )
	$resultm = $pdo->query("select * from `user_list` where `department_id`='".$row_user["department_id"]."' and `flag1`='1'");
else
	$resultm = $pdo->query("select * from `user_list` where `flag1`='1' order by department_id,id");
while($rowm = $resultm->fetch(PDO::FETCH_ASSOC)){
	echo '<option value="'.$rowm["id"].'">'.$rowm["name"].' ( '.$rowm["department"].' )</option>';
}
?>
</select>
<a onclick="transform_cases(&#39;touserid&#39;)" class="btn btn-primary btn-sm" data-remote="true" href="#">案件指派</a>
</div>
<?php } ?>

<?php if( $_GET["state"] == "nready" ) { ?>
      <!--<div class="card-footer">
  <a onclick="transform_cases(&#39;toready&#39;)" class="btn btn-primary btn-sm" data-remote="true" href="#">案件處理</a>
  <a onclick="transform_cases(&#39;todepartment2&#39;)" class="btn btn-danger btn-sm" data-remote="true" href="#">案件改派</a>
</div>-->
<?php } ?>
<?php if( $_GET["state"] == "ready" ) { ?>
      <!--<div class="card-footer">
<div class="review-form">-->
    <!--<h4>統一把勾選案件以下列選項送出：</h4>
    <div class="form-group row">
      <div class="col-sm-12">
        <div class="form-check-inline">
<input type="radio" name="cases[expose]" id="cases_expose_true" value="true" class="form-check-input expose_radio_buttons optional">
          <label class="form-check-label collection_radio_buttons" for="cases_expose_true">舉發</label>
        </div>
        <div class="form-check-inline">
<input type="radio" name="cases[expose]" id="cases_expose_false" value="false" class="form-check-input expose_radio_buttons optional" checked="checked">
          <label class="form-check-label collection_radio_buttons" for="cases_expose_false">不舉發</label>
        </div>
      </div>
    </div>-->

    <!--<div class="form-group row expose_fields" style="display:none;">
      <div class="col-sm-8">-->
        <!--<select name="cases[expose_reason_code]" id="cases_expose_reason_code" class="form-control select2" tabindex="-1" aria-hidden="true">
<option value="">請選擇舉發條款</option>
<?php
$reasonr = $pdo->query("select * from `expose_reasons` where active='t'");
while($rowr = $reasonr->fetch(PDO::FETCH_ASSOC)){
echo '<option value="'.$rowr["code"].' - '.$rowr["name"].'">'.$rowr["code"].' - '.$rowr["name"].'</option>';
}
?>
</select>-->
      <!--</div>
    </div>-->
    <!--<div class="form-group row unexpose_fields">
      <div class="col-sm-8">-->
<!--<select name="cases[unexpose_reason_id]" id="cases_unexpose_reason_id" class="form-control select2" tabindex="-1" aria-hidden="true">
<option value="">請選擇不舉發原因</option>
<?php
$reasonr = $pdo->query("select * from `unexpose_reasons` where active='t'");
while($rowr = $reasonr->fetch(PDO::FETCH_ASSOC)){
echo '<option value="'.$rowr["name"].'">'.$rowr["name"].'</option>';
}
?>
</select>-->
      <!--</div>
    </div>-->
    <!--<div class="form-group row unexpose_fields">
      <div class="col-sm-8">
        <input type="text" name="unexpose_reason_note" id="cases_unexpose_reason_note" value="" class="form-control">
      </div>
    </div>-->
<!--
<?php if( $_GET["state"] == "ready" ) { ?>
  <a onclick="transform_cases(&#39;allreview&#39;)" id="allreview" class="btn btn-primary btn-sm" data-remote="true" href="#">案件審核</a>
  <a onclick="transform_cases(&#39;alltonready&#39;)" class="btn btn-danger btn-sm" data-remote="true" href="#">案件退回</a>
<?php } ?>
-->
  <!--</div>
</div>-->
<?php } ?>
<?php if( $_GET["state"] == "reviewing" || $_SESSION['formdata'][0]['v'][0]['value'] == "reviewing" ) { ?>
      <!--<div class="card-footer">
  <a onclick="transform_cases(&#39;alltorecheck&#39;)" class="btn btn-primary btn-sm" data-remote="true" href="#">案件複審</a>
  <a onclick="transform_cases(&#39;alltoready&#39;)" class="btn btn-danger btn-sm" data-remote="true" href="#">案件退回</a>
</div>-->
<?php } ?>
<?php if( $_GET["state"] == "recheck" || $_SESSION['formdata'][0]['v'][0]['value'] == "recheck" ) { ?>
      <div class="card-footer">
  <a onclick="transform_cases(&#39;alltocomplete&#39;)" class="btn btn-primary btn-sm" data-remote="true" href="#">案件結束</a>
  <a onclick="transform_cases(&#39;alltoreview&#39;)" class="btn btn-danger btn-sm" data-remote="true" href="#">案件退回</a>
</div>
<?php } ?>
<?php } ?>
</div>
</form>

<canvas id="canvas" style="display: none"></canvas>


  <nav>
    <ul class="pagination">


  <!--<li class="page-item active">
    <a data-remote="false" class="page-link">1</a>
  </li>-->
<?php if( $page > 1 ) { ?>
      <li class="page-item">
      <a class="page-link" href="cases.php?state=<?=$_GET["state"];?>">&laquo;</a>
</li>

      <li class="page-item">
      <a rel="prev" class="page-link" href="cases.php?page=<?= $prev;?>&state=<?=$_GET["state"];?>">&lsaquo;</a>
</li>

<?php if( $page - 4 > 1 ) { ?>
          <li class='page-item disabled'>
  <a class="page-link" href="#">&hellip;</a>
</li>
<?php } ?>
<?php 
if( $page - 4 > 1 ) 
	$spage = $page - 4;
else 
	$spage = 1;
for($i=$spage; $i<=$page-1; $i++ ): ?>
    <li class="page-item <?php if($page == $i) {echo 'active'; } ?>">
    <a rel="next" class="page-link" href="cases.php?page=<?= $i; ?>&state=<?=$_GET["state"];?>"><?= $i; ?></a>
  </li>
<?php endfor; ?>
<?php } ?>

<?php 
if( $totoalPages - $page >= 4 )
        $canpage = 4;
else
        $canpage = $totoalPages - $page;
for($i = $page; $i <= $page+$canpage; $i++ ): ?>
            <li class="page-item <?php if($page == $i) {echo 'active'; } ?>">
    <a rel="next" class="page-link" href="cases.php?page=<?= $i; ?>&state=<?=$_GET["state"];?>"><?= $i; ?></a>
  </li>
<?php endfor; ?>

<?php if( $page < $totoalPages ) { ?>
<?php if( $totoalPages > 5 ) { ?>
<li class='page-item disabled'>
<a class="page-link" href="#">&hellip;</a>
</li>
<?php } ?>

      <li class="page-item">
      <a rel="next" class="page-link" href="cases.php?page=<?= $next; ?>&state=<?=$_GET["state"];?>">&rsaquo;</a>
</li>

      <li class="page-item">
      <a class="page-link" href="cases.php?page=<?= $totoalPages;?>&state=<?=$_GET["state"];?>">&raquo;</a>
</li>
<?php  } ?>

    </ul>
  </nav>

  </div>
</form>

<canvas id="canvas" style="display: none"></canvas>

<div class="hover_content" style="display: none;">

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

    <script src="/new/admin/assets/admin-7e641842b7678866dba9f029b1984fc78978fbe3c300f84802bb98e82b1f6905.js"></script>
    <script type="text/javascript">
  function response_search_value_input(d, e) {
    var field = $(d).val();
    var value_input = $(d).closest('.field').find('.search-value-input')
    var search_condition = $(d).closest('.field').find('.search-condition')
    var wrapper = $(d).closest('.field').find('.search-input-wrapper')
    search_condition.find('option').removeAttr('hidden')

    switch (field) {
      case 'expose':
        var checked_true = value_input.val() === '1' ? 'checked' : ''
        var checked_false = value_input.val() === '0' ? 'checked' : ''
        var input_html = '<div class="custom-control custom-radio custom-control-inline" style="margin-left:20px;"><input class="custom-control-input search-value-input" type="radio" ' + checked_true + ' name="' + value_input.attr('name') + '" value="1" id="' + value_input.attr('id') + '_1' + '"><label class="custom-control-label" for="' + value_input.attr('id') + '_1' + '">已舉發</label></div>' +

          '<div class="custom-control custom-radio custom-control-inline" style="margin-left:20px;"><input class="custom-control-input search-value-input" type="radio" ' + checked_false + ' name="' + value_input.attr('name') + '" value="0" id="' + value_input.attr('id') + '_0' + '"><label class="custom-control-label" for="' + value_input.attr('id') + '_0' + '">未舉發</label></div>'
        search_condition.val('eq').change();
        search_condition.find('option').not('[value="eq"]').attr('hidden', '')
        wrapper.html(input_html);
        break;
      case 'created_at':
      case 'created_at':
        var sc = search_condition.val();
        if (sc === 'lteq' || sc === 'gteq') {
          search_condition.val(sc).change();
        } else {
          search_condition.val('lteq').change();
        }
        search_condition.find('option').not('[value="lteq"], [value="gteq"]').attr('hidden', '')
        if (e && e.type == 'change') {
          var input = $('<input class="form-control search-value-input input-sm" type="text" name="' + value_input.attr('name') + '" id="' + value_input.attr('id') + '">')
          wrapper.html('').append(input)
          input.datepicker({
            language: 'zh-TW',
            autoHide: true,
            debug: false,
            format: 'yyyy-mm-dd',
          });
        } else {
          value_input.datepicker({
            language: 'zh-TW',
            autoHide: true,
            debug: false,
            format: 'yyyy-mm-dd',
          });
        }



        break;
      case 'area_code':
        var h = {"東區":[["台68線(東區)","D0002"],["八德路","A0001"],["三民路","A0010"],["千甲路","A0011"],["大同路","A0012"],["大成街","A0013"],["大學路","A0014"],["公道五路一段","A0021"],["中央路","A0022"],["中正路","A0023"],["中南街","A0024"],["中華路一段","A0025"],["中華路二段","A0026"],["仁愛街","A0027"],["仁義街","A0028"],["介壽路","A0029"],["公園路","A0030"],["公道五路二段","A0031"],["公道五路三段","A0032"],["太原路","A0033"],["文化街","A0034"],["文昌街","A0035"],["水利路","A0037"],["水源街","A0038"],["世界街","A0039"],["世傑路","A0040"],["北大路","A0041"],["占梅路","A0042"],["四維路","A0043"],["平和街","A0044"],["民主路","A0045"],["民生路","A0046"],["民有一街","A0047"],["民有二街","A0048"],["民有街","A0049"],["民享一街","A0050"],["民享街","A0051"],["民治街","A0052"],["民族路","A0053"],["民權路","A0054"],["田美一街","A0055"],["田美二街","A0056"],["田美三街","A0057"],["立鵬路","A0058"],["仲生路","A0059"],["仰德路","A0060"],["光明新村","A0061"],["光復路一段","A0062"],["光復路二段","A0063"],["光復路二段清大西院","A0064"],["光復路二段清大東院","A0065"],["光華東街","A0066"],["光華南街","A0067"],["光華街","A0068"],["安和街","A0069"],["安康街","A0070"],["安富街","A0071"],["竹蓮街","A0077"],["自由路","A0078"],["西大路","A0079"],["西門街","A0080"],["志平路","A0081"],["育民街","A0082"],["府後街","A0083"],["忠孝路","A0084"],["明湖路","A0085"],["東大路一段","A0086"],["東大路二段","A0087"],["東山街","A0088"],["東光路","A0089"],["東明街","A0090"],["東門市場","A0091"],["東門街","A0092"],["東前街","A0093"],["東南街","A0094"],["東美路","A0095"],["東勝路","A0096"],["東進路","A0097"],["東勢街","A0098"],["東園街","A0099"],["林森路","A0100"],["武昌街","A0101"],["花園街","A0102"],["花園新城街","A0103"],["金山一街","A0104"],["金山二街","A0105"],["金山三街","A0106"],["金山五街","A0107"],["金山六街","A0108"],["金山七街","A0109"],["金山八街","A0110"],["金山九街","A0111"],["金山十街","A0112"],["金山十一街","A0113"],["金山十二街","A0114"],["金山十三街","A0115"],["金山十五街","A0116"],["金山十六街","A0117"],["金山十七街","A0118"],["金山十八街","A0119"],["金山十九街","A0120"],["金山二十街","A0121"],["金山二十一街","A0122"],["金山二十二街","A0123"],["金山二十三街","A0124"],["金山二十五街","A0125"],["金山二十六街","A0126"],["金山二十七街","A0127"],["金山二十八街","A0128"],["金山北一街","A0129"],["金山北二街","A0130"],["金山北三街","A0131"],["金山東一街","A0132"],["金山東二街","A0133"],["金山東三街","A0134"],["金山東街","A0135"],["金山街","A0136"],["金城一路","A0137"],["金城二路","A0138"],["長春街","A0139"],["信義街","A0140"],["南大路","A0141"],["南外街","A0142"],["南門街","A0143"],["南城街","A0144"],["建中一路","A0145"],["建中路","A0146"],["建功一路","A0147"],["建功二路","A0148"],["建功路","A0149"],["建美路","A0150"],["建華街","A0151"],["建新路","A0152"],["柏川一路","A0153"],["柏川二路","A0154"],["柏川三路","A0155"],["科學園路","A0166"],["風空街","A0167"],["食品路","A0168"],["原興路","A0169"],["埔頂一路","A0170"],["埔頂二路","A0171"],["埔頂三路","A0172"],["埔頂路","A0173"],["振興一街","A0176"],["振興路","A0177"],["柴橋路","A0178"],["草湖街","A0179"],["高峰路","A0180"],["高翠路","A0181"],["國華街","A0182"],["培英街","A0183"],["崇和路","A0184"],["勝利路","A0186"],["博愛街","A0187"],["復興路","A0188"],["惠民街","A0189"],["湖濱一路","A0190"],["湖濱二路","A0191"],["湖濱三路","A0192"],["園後街","A0193"],["慈祥路","A0197"],["慈雲路","A0198"],["慈濟路","A0199"],["愛民街","A0200"],["新光路","A0201"],["新香街","A0203"],["新莊街","A0204"],["新源街","A0205"],["溪州路","A0206"],["溪埔路","A0207"],["瑞麟路","A0208"],["經國路一段","A0209"],["裕民街","A0210"],["福德街","A0211"],["綠水路","A0212"],["德成街","A0213"],["德高街","A0214"],["學府路","A0215"],["澤藩路","A0216"],["興中街","A0219"],["興竹街","A0220"],["興達街","A0222"],["興學街","A0223"],["錦華街","A0224"],["龍山西路","A0225"],["龍山東一街","A0226"],["龍山東路","A0227"],["藝術路","A0228"],["關東路","A0229"],["關新一街","A0230"],["關新二街","A0231"],["關新北路","A0232"],["關新西街","A0233"],["關新東路","A0234"],["關新路","A0235"],["寶山路","A0236"],["鐵道路一段","A0237"],["鐵道路二段","A0238"],["體育街","A0239"],["赤土崎一街","A0240"],["赤土崎二街","A0242"],["金山南街","A0243"],["東新路","A0244"]],"北區":[["台68線(北區)","D0001"],["大同路","B0001"],["中山路","B0002"],["中央路","B0003"],["中正路","B0004"],["中光路","B0005"],["中和路","B0006"],["中清一路","B0007"],["中清路","B0008"],["中清路一段","B0009"],["中華路二段","B0010"],["中華路三段","B0011"],["中福路","B0012"],["中興路","B0013"],["仁化街","B0014"],["仁和路","B0015"],["仁德街","B0016"],["公道五路四段","B0017"],["公道五路五段","B0018"],["天府路一段","B0019"],["天府路二段","B0020"],["少年街","B0021"],["文雅街","B0022"],["水田街","B0023"],["世界街","B0024"],["北大路","B0025"],["北門街","B0026"],["北新街","B0027"],["古賢","B0028"],["四維路","B0029"],["平和街","B0030"],["民富街","B0031"],["田美三街","B0032"],["石坊街","B0033"],["光華一街","B0034"],["光華二街","B0035"],["光華北街","B0036"],["光華東一街","B0037"],["光華東街","B0038"],["光華南街","B0039"],["光華街","B0040"],["吉羊路","B0041"],["成功路","B0042"],["成德一街","B0043"],["成德二街","B0044"],["成德路","B0045"],["江山街","B0046"],["竹文街","B0047"],["竹光路","B0048"],["西大路","B0049"],["西安街","B0050"],["西門街","B0051"],["西濱路一段","B0052"],["孝賢路","B0053"],["育英路","B0054"],["和平路","B0055"],["和福街","B0056"],["尚濱路","B0057"],["府後街","B0058"],["延平路一段","B0059"],["延平路三段","B0060"],["延濱路","B0061"],["忠信路","B0062"],["東大路二段","B0063"],["東大路三段","B0064"],["東大路四段","B0065"],["東門街","B0066"],["東濱街","B0067"],["林森路","B0068"],["武勇街","B0069"],["武陵西二路","B0070"],["武陵西三路","B0071"],["武陵西四路","B0072"],["武陵路","B0073"],["河北街","B0074"],["金竹路","B0075"],["金雅一街","B0076"],["金雅二街","B0077"],["金雅三街","B0078"],["金雅五街","B0079"],["金雅西街","B0080"],["金雅路","B0081"],["金農路","B0082"],["長安街","B0083"],["長和街","B0084"],["南大路","B0085"],["南勢二街","B0086"],["南勢六街","B0087"],["南勢八街","B0088"],["南勢十街","B0089"],["南勢十二街","B0090"],["南勢街","B0091"],["南寮街","B0092"],["城北街","B0093"],["建台街","B0094"],["建國街","B0095"],["建興街","B0096"],["英明街","B0097"],["凌雲街","B0098"],["海濱路","B0099"],["草湖街","B0100"],["國光街","B0101"],["國華街","B0102"],["崧嶺路","B0103"],["勝利路","B0104"],["富美路","B0105"],["港北一街","B0106"],["港北二街","B0107"],["港北三街","B0108"],["港北六街","B0109"],["集和街","B0110"],["集福街","B0111"],["集賢街","B0112"],["湳中街","B0113"],["湳雅街","B0114"],["愛文街","B0115"],["愛國路","B0116"],["新民街","B0117"],["新香街","B0118"],["新港三路","B0119"],["新港北路","B0120"],["新港南路","B0121"],["經國路一段","B0122"],["經國路二段","B0123"],["聖軍路","B0124"],["嘉濱路","B0125"],["境福街","B0126"],["榮濱南路","B0127"],["榮濱路","B0128"],["演藝路","B0129"],["廣州街","B0130"],["德成街","B0131"],["磐石路","B0132"],["衛民街","B0133"],["興南街","B0134"],["興濱路","B0135"],["聯興路","B0136"],["警光路","B0137"],["鐵道路二段","B0138"],["鐵道路三段","B0139"],["士林東路","B0140"],["士林西路","B0141"],["士林北路","B0142"],["士林一街","B0143"],["士林二街","B0144"],["新港四路","B0145"]],"香山區":[["大湖路","C0001"],["大庄路","C0002"],["中山路","C0003"],["中華路四段","C0004"],["中華路五段","C0005"],["中華路六段","C0006"],["五福路一段","C0007"],["五福路二段","C0008"],["元培街","C0009"],["內湖路","C0010"],["牛埔北路","C0011"],["牛埔東路","C0012"],["牛埔南路","C0013"],["牛埔路","C0014"],["古車路","C0015"],["玄奘路","C0016"],["竹香北路","C0017"],["竹香南路","C0018"],["至善街","C0019"],["西濱路二段","C0020"],["西濱路三段","C0021"],["西濱路四段","C0022"],["西濱路六段","C0023"],["吳厝街","C0024"],["育德街","C0025"],["那魯灣街","C0026"],["延平路二段","C0027"],["明德路","C0028"],["東香東街","C0029"],["東香南街","C0030"],["東香路一段","C0031"],["東香路二段","C0032"],["東華路","C0033"],["芝柏一街","C0034"],["芝柏二街","C0035"],["芝柏三街","C0036"],["芝柏五街","C0037"],["花園新城一街","C0038"],["花園新城二街","C0039"],["虎林街","C0040"],["長福街","C0041"],["長興街","C0042"],["南港街","C0043"],["南湖路","C0044"],["南隘路一段","C0045"],["南隘路二段","C0046"],["柯湳一街","C0047"],["柯湳二街","C0048"],["美山路","C0049"],["美之城","C0050"],["美之城一街","C0051"],["美森街","C0052"],["茄苳北街","C0053"],["茄苳東街","C0054"],["茄苳路","C0055"],["香北一路","C0056"],["香北路","C0057"],["香村路","C0058"],["香檳一街","C0059"],["香檳二街","C0060"],["香檳三街","C0061"],["香檳五街","C0062"],["香檳東街","C0063"],["香檳南街","C0064"],["埔前路","C0065"],["宮口街","C0066"],["柴橋路","C0067"],["浸水北街","C0068"],["浸水南街","C0069"],["浸水街","C0070"],["浸樹街","C0071"],["海山港十街","C0072"],["海山港路","C0073"],["海埔一街","C0074"],["海埔二街","C0075"],["海埔三街","C0076"],["海埔五街","C0077"],["海埔路","C0078"],["草漯街","C0079"],["國中街","C0080"],["崧嶺路","C0081"],["彩虹路","C0082"],["祥園街","C0083"],["莊敬街","C0084"],["頂美街","C0085"],["頂埔路","C0086"],["富群街","C0087"],["富禮街","C0088"],["景觀大道","C0089"],["港南一街","C0090"],["港南二街","C0091"],["港南三街","C0092"],["港南五街","C0093"],["華北路","C0094"],["華江街","C0095"],["閑谷一街","C0096"],["閑谷二街","C0097"],["閑谷街","C0098"],["新香街","C0099"],["瑞光街","C0100"],["經國路三段","C0101"],["遊樂街","C0102"],["福樹街","C0103"],["墩豐路","C0104"],["樹下街","C0105"],["麗山街","C0106"]]}
          // var optgroups = ''
          var opts = ''
        $.each(h, function (district, streets) {
          // var optgroup = '<optgroup label="' + district + '">'
          // var opts = ''
          $.each(streets, function (i, str) {
            if (str[1] == value_input.val()) {
              opts += '<option selected value=' + str[1] + '>' + str[1] + '-' + str[0] + '</option>'
            } else {
              opts += '<option value=' + str[1] + '>' + str[1] + '-' + str[0] + '</option>'
            }
          })
          // optgroup += opts
          // optgroup += '</optgroup>'

          // optgroups += optgroup
        });

        var s = '<select class="search-value-input custom-select" name="' + value_input.attr('name') + '" id="' + value_input.attr('id') + '">' + opts + '</select>'

        search_condition.val('eq').change();
        search_condition.find('option').not('[value="eq"]').attr('hidden', '')
        wrapper.html(s);
        wrapper.find('.search-value-input').select2();
        break;
      case 'area_name':
        var h = {"東區":[["台68線(東區)","D0002"],["八德路","A0001"],["三民路","A0010"],["千甲路","A0011"],["大同路","A0012"],["大成街","A0013"],["大學路","A0014"],["公道五路一段","A0021"],["中央路","A0022"],["中正路","A0023"],["中南街","A0024"],["中華路一段","A0025"],["中華路二段","A0026"],["仁愛街","A0027"],["仁義街","A0028"],["介壽路","A0029"],["公園路","A0030"],["公道五路二段","A0031"],["公道五路三段","A0032"],["太原路","A0033"],["文化街","A0034"],["文昌街","A0035"],["水利路","A0037"],["水源街","A0038"],["世界街","A0039"],["世傑路","A0040"],["北大路","A0041"],["占梅路","A0042"],["四維路","A0043"],["平和街","A0044"],["民主路","A0045"],["民生路","A0046"],["民有一街","A0047"],["民有二街","A0048"],["民有街","A0049"],["民享一街","A0050"],["民享街","A0051"],["民治街","A0052"],["民族路","A0053"],["民權路","A0054"],["田美一街","A0055"],["田美二街","A0056"],["田美三街","A0057"],["立鵬路","A0058"],["仲生路","A0059"],["仰德路","A0060"],["光明新村","A0061"],["光復路一段","A0062"],["光復路二段","A0063"],["光復路二段清大西院","A0064"],["光復路二段清大東院","A0065"],["光華東街","A0066"],["光華南街","A0067"],["光華街","A0068"],["安和街","A0069"],["安康街","A0070"],["安富街","A0071"],["竹蓮街","A0077"],["自由路","A0078"],["西大路","A0079"],["西門街","A0080"],["志平路","A0081"],["育民街","A0082"],["府後街","A0083"],["忠孝路","A0084"],["明湖路","A0085"],["東大路一段","A0086"],["東大路二段","A0087"],["東山街","A0088"],["東光路","A0089"],["東明街","A0090"],["東門市場","A0091"],["東門街","A0092"],["東前街","A0093"],["東南街","A0094"],["東美路","A0095"],["東勝路","A0096"],["東進路","A0097"],["東勢街","A0098"],["東園街","A0099"],["林森路","A0100"],["武昌街","A0101"],["花園街","A0102"],["花園新城街","A0103"],["金山一街","A0104"],["金山二街","A0105"],["金山三街","A0106"],["金山五街","A0107"],["金山六街","A0108"],["金山七街","A0109"],["金山八街","A0110"],["金山九街","A0111"],["金山十街","A0112"],["金山十一街","A0113"],["金山十二街","A0114"],["金山十三街","A0115"],["金山十五街","A0116"],["金山十六街","A0117"],["金山十七街","A0118"],["金山十八街","A0119"],["金山十九街","A0120"],["金山二十街","A0121"],["金山二十一街","A0122"],["金山二十二街","A0123"],["金山二十三街","A0124"],["金山二十五街","A0125"],["金山二十六街","A0126"],["金山二十七街","A0127"],["金山二十八街","A0128"],["金山北一街","A0129"],["金山北二街","A0130"],["金山北三街","A0131"],["金山東一街","A0132"],["金山東二街","A0133"],["金山東三街","A0134"],["金山東街","A0135"],["金山街","A0136"],["金城一路","A0137"],["金城二路","A0138"],["長春街","A0139"],["信義街","A0140"],["南大路","A0141"],["南外街","A0142"],["南門街","A0143"],["南城街","A0144"],["建中一路","A0145"],["建中路","A0146"],["建功一路","A0147"],["建功二路","A0148"],["建功路","A0149"],["建美路","A0150"],["建華街","A0151"],["建新路","A0152"],["柏川一路","A0153"],["柏川二路","A0154"],["柏川三路","A0155"],["科學園路","A0166"],["風空街","A0167"],["食品路","A0168"],["原興路","A0169"],["埔頂一路","A0170"],["埔頂二路","A0171"],["埔頂三路","A0172"],["埔頂路","A0173"],["振興一街","A0176"],["振興路","A0177"],["柴橋路","A0178"],["草湖街","A0179"],["高峰路","A0180"],["高翠路","A0181"],["國華街","A0182"],["培英街","A0183"],["崇和路","A0184"],["勝利路","A0186"],["博愛街","A0187"],["復興路","A0188"],["惠民街","A0189"],["湖濱一路","A0190"],["湖濱二路","A0191"],["湖濱三路","A0192"],["園後街","A0193"],["慈祥路","A0197"],["慈雲路","A0198"],["慈濟路","A0199"],["愛民街","A0200"],["新光路","A0201"],["新香街","A0203"],["新莊街","A0204"],["新源街","A0205"],["溪州路","A0206"],["溪埔路","A0207"],["瑞麟路","A0208"],["經國路一段","A0209"],["裕民街","A0210"],["福德街","A0211"],["綠水路","A0212"],["德成街","A0213"],["德高街","A0214"],["學府路","A0215"],["澤藩路","A0216"],["興中街","A0219"],["興竹街","A0220"],["興達街","A0222"],["興學街","A0223"],["錦華街","A0224"],["龍山西路","A0225"],["龍山東一街","A0226"],["龍山東路","A0227"],["藝術路","A0228"],["關東路","A0229"],["關新一街","A0230"],["關新二街","A0231"],["關新北路","A0232"],["關新西街","A0233"],["關新東路","A0234"],["關新路","A0235"],["寶山路","A0236"],["鐵道路一段","A0237"],["鐵道路二段","A0238"],["體育街","A0239"],["赤土崎一街","A0240"],["赤土崎二街","A0242"],["金山南街","A0243"],["東新路","A0244"]],"北區":[["台68線(北區)","D0001"],["大同路","B0001"],["中山路","B0002"],["中央路","B0003"],["中正路","B0004"],["中光路","B0005"],["中和路","B0006"],["中清一路","B0007"],["中清路","B0008"],["中清路一段","B0009"],["中華路二段","B0010"],["中華路三段","B0011"],["中福路","B0012"],["中興路","B0013"],["仁化街","B0014"],["仁和路","B0015"],["仁德街","B0016"],["公道五路四段","B0017"],["公道五路五段","B0018"],["天府路一段","B0019"],["天府路二段","B0020"],["少年街","B0021"],["文雅街","B0022"],["水田街","B0023"],["世界街","B0024"],["北大路","B0025"],["北門街","B0026"],["北新街","B0027"],["古賢","B0028"],["四維路","B0029"],["平和街","B0030"],["民富街","B0031"],["田美三街","B0032"],["石坊街","B0033"],["光華一街","B0034"],["光華二街","B0035"],["光華北街","B0036"],["光華東一街","B0037"],["光華東街","B0038"],["光華南街","B0039"],["光華街","B0040"],["吉羊路","B0041"],["成功路","B0042"],["成德一街","B0043"],["成德二街","B0044"],["成德路","B0045"],["江山街","B0046"],["竹文街","B0047"],["竹光路","B0048"],["西大路","B0049"],["西安街","B0050"],["西門街","B0051"],["西濱路一段","B0052"],["孝賢路","B0053"],["育英路","B0054"],["和平路","B0055"],["和福街","B0056"],["尚濱路","B0057"],["府後街","B0058"],["延平路一段","B0059"],["延平路三段","B0060"],["延濱路","B0061"],["忠信路","B0062"],["東大路二段","B0063"],["東大路三段","B0064"],["東大路四段","B0065"],["東門街","B0066"],["東濱街","B0067"],["林森路","B0068"],["武勇街","B0069"],["武陵西二路","B0070"],["武陵西三路","B0071"],["武陵西四路","B0072"],["武陵路","B0073"],["河北街","B0074"],["金竹路","B0075"],["金雅一街","B0076"],["金雅二街","B0077"],["金雅三街","B0078"],["金雅五街","B0079"],["金雅西街","B0080"],["金雅路","B0081"],["金農路","B0082"],["長安街","B0083"],["長和街","B0084"],["南大路","B0085"],["南勢二街","B0086"],["南勢六街","B0087"],["南勢八街","B0088"],["南勢十街","B0089"],["南勢十二街","B0090"],["南勢街","B0091"],["南寮街","B0092"],["城北街","B0093"],["建台街","B0094"],["建國街","B0095"],["建興街","B0096"],["英明街","B0097"],["凌雲街","B0098"],["海濱路","B0099"],["草湖街","B0100"],["國光街","B0101"],["國華街","B0102"],["崧嶺路","B0103"],["勝利路","B0104"],["富美路","B0105"],["港北一街","B0106"],["港北二街","B0107"],["港北三街","B0108"],["港北六街","B0109"],["集和街","B0110"],["集福街","B0111"],["集賢街","B0112"],["湳中街","B0113"],["湳雅街","B0114"],["愛文街","B0115"],["愛國路","B0116"],["新民街","B0117"],["新香街","B0118"],["新港三路","B0119"],["新港北路","B0120"],["新港南路","B0121"],["經國路一段","B0122"],["經國路二段","B0123"],["聖軍路","B0124"],["嘉濱路","B0125"],["境福街","B0126"],["榮濱南路","B0127"],["榮濱路","B0128"],["演藝路","B0129"],["廣州街","B0130"],["德成街","B0131"],["磐石路","B0132"],["衛民街","B0133"],["興南街","B0134"],["興濱路","B0135"],["聯興路","B0136"],["警光路","B0137"],["鐵道路二段","B0138"],["鐵道路三段","B0139"],["士林東路","B0140"],["士林西路","B0141"],["士林北路","B0142"],["士林一街","B0143"],["士林二街","B0144"],["新港四路","B0145"]],"香山區":[["大湖路","C0001"],["大庄路","C0002"],["中山路","C0003"],["中華路四段","C0004"],["中華路五段","C0005"],["中華路六段","C0006"],["五福路一段","C0007"],["五福路二段","C0008"],["元培街","C0009"],["內湖路","C0010"],["牛埔北路","C0011"],["牛埔東路","C0012"],["牛埔南路","C0013"],["牛埔路","C0014"],["古車路","C0015"],["玄奘路","C0016"],["竹香北路","C0017"],["竹香南路","C0018"],["至善街","C0019"],["西濱路二段","C0020"],["西濱路三段","C0021"],["西濱路四段","C0022"],["西濱路六段","C0023"],["吳厝街","C0024"],["育德街","C0025"],["那魯灣街","C0026"],["延平路二段","C0027"],["明德路","C0028"],["東香東街","C0029"],["東香南街","C0030"],["東香路一段","C0031"],["東香路二段","C0032"],["東華路","C0033"],["芝柏一街","C0034"],["芝柏二街","C0035"],["芝柏三街","C0036"],["芝柏五街","C0037"],["花園新城一街","C0038"],["花園新城二街","C0039"],["虎林街","C0040"],["長福街","C0041"],["長興街","C0042"],["南港街","C0043"],["南湖路","C0044"],["南隘路一段","C0045"],["南隘路二段","C0046"],["柯湳一街","C0047"],["柯湳二街","C0048"],["美山路","C0049"],["美之城","C0050"],["美之城一街","C0051"],["美森街","C0052"],["茄苳北街","C0053"],["茄苳東街","C0054"],["茄苳路","C0055"],["香北一路","C0056"],["香北路","C0057"],["香村路","C0058"],["香檳一街","C0059"],["香檳二街","C0060"],["香檳三街","C0061"],["香檳五街","C0062"],["香檳東街","C0063"],["香檳南街","C0064"],["埔前路","C0065"],["宮口街","C0066"],["柴橋路","C0067"],["浸水北街","C0068"],["浸水南街","C0069"],["浸水街","C0070"],["浸樹街","C0071"],["海山港十街","C0072"],["海山港路","C0073"],["海埔一街","C0074"],["海埔二街","C0075"],["海埔三街","C0076"],["海埔五街","C0077"],["海埔路","C0078"],["草漯街","C0079"],["國中街","C0080"],["崧嶺路","C0081"],["彩虹路","C0082"],["祥園街","C0083"],["莊敬街","C0084"],["頂美街","C0085"],["頂埔路","C0086"],["富群街","C0087"],["富禮街","C0088"],["景觀大道","C0089"],["港南一街","C0090"],["港南二街","C0091"],["港南三街","C0092"],["港南五街","C0093"],["華北路","C0094"],["華江街","C0095"],["閑谷一街","C0096"],["閑谷二街","C0097"],["閑谷街","C0098"],["新香街","C0099"],["瑞光街","C0100"],["經國路三段","C0101"],["遊樂街","C0102"],["福樹街","C0103"],["墩豐路","C0104"],["樹下街","C0105"],["麗山街","C0106"]]}
          // var optgroups = ''
          var opts = ''
        $.each(h, function (district, streets) {
          // var optgroup = '<optgroup label="' + district + '">'
          // var opts = ''
          $.each(streets, function (i, str) {
            if (str[0] == value_input.val()) {
              opts += '<option selected value=' + str[0] + '>' + str[1] + '-' + str[0] + '</option>'
            } else {
              opts += '<option value=' + str[0] + '>' + str[1] + '-' + str[0] + '</option>'
            }
          })
          // optgroup += opts
          // optgroup += '</optgroup>'

          // optgroups += optgroup
        });

        var s = '<select class="search-value-input custom-select" name="' + value_input.attr('name') + '" id="' + value_input.attr('id') + '">' + opts + '</select>'

        search_condition.val('eq').change();
        search_condition.find('option').not('[value="eq"]').attr('hidden', '')
        wrapper.html(s);
        wrapper.find('.search-value-input').select2();
        break;
      case 'area_district':
        var h = {"東區":[["台68線(東區)","D0002"],["八德路","A0001"],["三民路","A0010"],["千甲路","A0011"],["大同路","A0012"],["大成街","A0013"],["大學路","A0014"],["公道五路一段","A0021"],["中央路","A0022"],["中正路","A0023"],["中南街","A0024"],["中華路一段","A0025"],["中華路二段","A0026"],["仁愛街","A0027"],["仁義街","A0028"],["介壽路","A0029"],["公園路","A0030"],["公道五路二段","A0031"],["公道五路三段","A0032"],["太原路","A0033"],["文化街","A0034"],["文昌街","A0035"],["水利路","A0037"],["水源街","A0038"],["世界街","A0039"],["世傑路","A0040"],["北大路","A0041"],["占梅路","A0042"],["四維路","A0043"],["平和街","A0044"],["民主路","A0045"],["民生路","A0046"],["民有一街","A0047"],["民有二街","A0048"],["民有街","A0049"],["民享一街","A0050"],["民享街","A0051"],["民治街","A0052"],["民族路","A0053"],["民權路","A0054"],["田美一街","A0055"],["田美二街","A0056"],["田美三街","A0057"],["立鵬路","A0058"],["仲生路","A0059"],["仰德路","A0060"],["光明新村","A0061"],["光復路一段","A0062"],["光復路二段","A0063"],["光復路二段清大西院","A0064"],["光復路二段清大東院","A0065"],["光華東街","A0066"],["光華南街","A0067"],["光華街","A0068"],["安和街","A0069"],["安康街","A0070"],["安富街","A0071"],["竹蓮街","A0077"],["自由路","A0078"],["西大路","A0079"],["西門街","A0080"],["志平路","A0081"],["育民街","A0082"],["府後街","A0083"],["忠孝路","A0084"],["明湖路","A0085"],["東大路一段","A0086"],["東大路二段","A0087"],["東山街","A0088"],["東光路","A0089"],["東明街","A0090"],["東門市場","A0091"],["東門街","A0092"],["東前街","A0093"],["東南街","A0094"],["東美路","A0095"],["東勝路","A0096"],["東進路","A0097"],["東勢街","A0098"],["東園街","A0099"],["林森路","A0100"],["武昌街","A0101"],["花園街","A0102"],["花園新城街","A0103"],["金山一街","A0104"],["金山二街","A0105"],["金山三街","A0106"],["金山五街","A0107"],["金山六街","A0108"],["金山七街","A0109"],["金山八街","A0110"],["金山九街","A0111"],["金山十街","A0112"],["金山十一街","A0113"],["金山十二街","A0114"],["金山十三街","A0115"],["金山十五街","A0116"],["金山十六街","A0117"],["金山十七街","A0118"],["金山十八街","A0119"],["金山十九街","A0120"],["金山二十街","A0121"],["金山二十一街","A0122"],["金山二十二街","A0123"],["金山二十三街","A0124"],["金山二十五街","A0125"],["金山二十六街","A0126"],["金山二十七街","A0127"],["金山二十八街","A0128"],["金山北一街","A0129"],["金山北二街","A0130"],["金山北三街","A0131"],["金山東一街","A0132"],["金山東二街","A0133"],["金山東三街","A0134"],["金山東街","A0135"],["金山街","A0136"],["金城一路","A0137"],["金城二路","A0138"],["長春街","A0139"],["信義街","A0140"],["南大路","A0141"],["南外街","A0142"],["南門街","A0143"],["南城街","A0144"],["建中一路","A0145"],["建中路","A0146"],["建功一路","A0147"],["建功二路","A0148"],["建功路","A0149"],["建美路","A0150"],["建華街","A0151"],["建新路","A0152"],["柏川一路","A0153"],["柏川二路","A0154"],["柏川三路","A0155"],["科學園路","A0166"],["風空街","A0167"],["食品路","A0168"],["原興路","A0169"],["埔頂一路","A0170"],["埔頂二路","A0171"],["埔頂三路","A0172"],["埔頂路","A0173"],["振興一街","A0176"],["振興路","A0177"],["柴橋路","A0178"],["草湖街","A0179"],["高峰路","A0180"],["高翠路","A0181"],["國華街","A0182"],["培英街","A0183"],["崇和路","A0184"],["勝利路","A0186"],["博愛街","A0187"],["復興路","A0188"],["惠民街","A0189"],["湖濱一路","A0190"],["湖濱二路","A0191"],["湖濱三路","A0192"],["園後街","A0193"],["慈祥路","A0197"],["慈雲路","A0198"],["慈濟路","A0199"],["愛民街","A0200"],["新光路","A0201"],["新香街","A0203"],["新莊街","A0204"],["新源街","A0205"],["溪州路","A0206"],["溪埔路","A0207"],["瑞麟路","A0208"],["經國路一段","A0209"],["裕民街","A0210"],["福德街","A0211"],["綠水路","A0212"],["德成街","A0213"],["德高街","A0214"],["學府路","A0215"],["澤藩路","A0216"],["興中街","A0219"],["興竹街","A0220"],["興達街","A0222"],["興學街","A0223"],["錦華街","A0224"],["龍山西路","A0225"],["龍山東一街","A0226"],["龍山東路","A0227"],["藝術路","A0228"],["關東路","A0229"],["關新一街","A0230"],["關新二街","A0231"],["關新北路","A0232"],["關新西街","A0233"],["關新東路","A0234"],["關新路","A0235"],["寶山路","A0236"],["鐵道路一段","A0237"],["鐵道路二段","A0238"],["體育街","A0239"],["赤土崎一街","A0240"],["赤土崎二街","A0242"],["金山南街","A0243"],["東新路","A0244"]],"北區":[["台68線(北區)","D0001"],["大同路","B0001"],["中山路","B0002"],["中央路","B0003"],["中正路","B0004"],["中光路","B0005"],["中和路","B0006"],["中清一路","B0007"],["中清路","B0008"],["中清路一段","B0009"],["中華路二段","B0010"],["中華路三段","B0011"],["中福路","B0012"],["中興路","B0013"],["仁化街","B0014"],["仁和路","B0015"],["仁德街","B0016"],["公道五路四段","B0017"],["公道五路五段","B0018"],["天府路一段","B0019"],["天府路二段","B0020"],["少年街","B0021"],["文雅街","B0022"],["水田街","B0023"],["世界街","B0024"],["北大路","B0025"],["北門街","B0026"],["北新街","B0027"],["古賢","B0028"],["四維路","B0029"],["平和街","B0030"],["民富街","B0031"],["田美三街","B0032"],["石坊街","B0033"],["光華一街","B0034"],["光華二街","B0035"],["光華北街","B0036"],["光華東一街","B0037"],["光華東街","B0038"],["光華南街","B0039"],["光華街","B0040"],["吉羊路","B0041"],["成功路","B0042"],["成德一街","B0043"],["成德二街","B0044"],["成德路","B0045"],["江山街","B0046"],["竹文街","B0047"],["竹光路","B0048"],["西大路","B0049"],["西安街","B0050"],["西門街","B0051"],["西濱路一段","B0052"],["孝賢路","B0053"],["育英路","B0054"],["和平路","B0055"],["和福街","B0056"],["尚濱路","B0057"],["府後街","B0058"],["延平路一段","B0059"],["延平路三段","B0060"],["延濱路","B0061"],["忠信路","B0062"],["東大路二段","B0063"],["東大路三段","B0064"],["東大路四段","B0065"],["東門街","B0066"],["東濱街","B0067"],["林森路","B0068"],["武勇街","B0069"],["武陵西二路","B0070"],["武陵西三路","B0071"],["武陵西四路","B0072"],["武陵路","B0073"],["河北街","B0074"],["金竹路","B0075"],["金雅一街","B0076"],["金雅二街","B0077"],["金雅三街","B0078"],["金雅五街","B0079"],["金雅西街","B0080"],["金雅路","B0081"],["金農路","B0082"],["長安街","B0083"],["長和街","B0084"],["南大路","B0085"],["南勢二街","B0086"],["南勢六街","B0087"],["南勢八街","B0088"],["南勢十街","B0089"],["南勢十二街","B0090"],["南勢街","B0091"],["南寮街","B0092"],["城北街","B0093"],["建台街","B0094"],["建國街","B0095"],["建興街","B0096"],["英明街","B0097"],["凌雲街","B0098"],["海濱路","B0099"],["草湖街","B0100"],["國光街","B0101"],["國華街","B0102"],["崧嶺路","B0103"],["勝利路","B0104"],["富美路","B0105"],["港北一街","B0106"],["港北二街","B0107"],["港北三街","B0108"],["港北六街","B0109"],["集和街","B0110"],["集福街","B0111"],["集賢街","B0112"],["湳中街","B0113"],["湳雅街","B0114"],["愛文街","B0115"],["愛國路","B0116"],["新民街","B0117"],["新香街","B0118"],["新港三路","B0119"],["新港北路","B0120"],["新港南路","B0121"],["經國路一段","B0122"],["經國路二段","B0123"],["聖軍路","B0124"],["嘉濱路","B0125"],["境福街","B0126"],["榮濱南路","B0127"],["榮濱路","B0128"],["演藝路","B0129"],["廣州街","B0130"],["德成街","B0131"],["磐石路","B0132"],["衛民街","B0133"],["興南街","B0134"],["興濱路","B0135"],["聯興路","B0136"],["警光路","B0137"],["鐵道路二段","B0138"],["鐵道路三段","B0139"],["士林東路","B0140"],["士林西路","B0141"],["士林北路","B0142"],["士林一街","B0143"],["士林二街","B0144"],["新港四路","B0145"]],"香山區":[["大湖路","C0001"],["大庄路","C0002"],["中山路","C0003"],["中華路四段","C0004"],["中華路五段","C0005"],["中華路六段","C0006"],["五福路一段","C0007"],["五福路二段","C0008"],["元培街","C0009"],["內湖路","C0010"],["牛埔北路","C0011"],["牛埔東路","C0012"],["牛埔南路","C0013"],["牛埔路","C0014"],["古車路","C0015"],["玄奘路","C0016"],["竹香北路","C0017"],["竹香南路","C0018"],["至善街","C0019"],["西濱路二段","C0020"],["西濱路三段","C0021"],["西濱路四段","C0022"],["西濱路六段","C0023"],["吳厝街","C0024"],["育德街","C0025"],["那魯灣街","C0026"],["延平路二段","C0027"],["明德路","C0028"],["東香東街","C0029"],["東香南街","C0030"],["東香路一段","C0031"],["東香路二段","C0032"],["東華路","C0033"],["芝柏一街","C0034"],["芝柏二街","C0035"],["芝柏三街","C0036"],["芝柏五街","C0037"],["花園新城一街","C0038"],["花園新城二街","C0039"],["虎林街","C0040"],["長福街","C0041"],["長興街","C0042"],["南港街","C0043"],["南湖路","C0044"],["南隘路一段","C0045"],["南隘路二段","C0046"],["柯湳一街","C0047"],["柯湳二街","C0048"],["美山路","C0049"],["美之城","C0050"],["美之城一街","C0051"],["美森街","C0052"],["茄苳北街","C0053"],["茄苳東街","C0054"],["茄苳路","C0055"],["香北一路","C0056"],["香北路","C0057"],["香村路","C0058"],["香檳一街","C0059"],["香檳二街","C0060"],["香檳三街","C0061"],["香檳五街","C0062"],["香檳東街","C0063"],["香檳南街","C0064"],["埔前路","C0065"],["宮口街","C0066"],["柴橋路","C0067"],["浸水北街","C0068"],["浸水南街","C0069"],["浸水街","C0070"],["浸樹街","C0071"],["海山港十街","C0072"],["海山港路","C0073"],["海埔一街","C0074"],["海埔二街","C0075"],["海埔三街","C0076"],["海埔五街","C0077"],["海埔路","C0078"],["草漯街","C0079"],["國中街","C0080"],["崧嶺路","C0081"],["彩虹路","C0082"],["祥園街","C0083"],["莊敬街","C0084"],["頂美街","C0085"],["頂埔路","C0086"],["富群街","C0087"],["富禮街","C0088"],["景觀大道","C0089"],["港南一街","C0090"],["港南二街","C0091"],["港南三街","C0092"],["港南五街","C0093"],["華北路","C0094"],["華江街","C0095"],["閑谷一街","C0096"],["閑谷二街","C0097"],["閑谷街","C0098"],["新香街","C0099"],["瑞光街","C0100"],["經國路三段","C0101"],["遊樂街","C0102"],["福樹街","C0103"],["墩豐路","C0104"],["樹下街","C0105"],["麗山街","C0106"]]}
          // var optgroups = ''
          var opts = ''
        $.each(h, function (district, streets) {
          if (district === value_input.val()) {
            opts += '<option selected value=' + district + '>' + district + '</option>'
          } else {
            opts += '<option value=' + district + '>' + district + '</option>'
          }
        });

        var s = '<select class="search-value-input custom-select" name="' + value_input.attr('name') + '" id="' + value_input.attr('id') + '">' + opts + '</select>'

        search_condition.val('eq').change();
        search_condition.find('option').not('[value="eq"]').attr('hidden', '')
        wrapper.html(s);
        wrapper.find('.search-value-input').select2();
        break;


	/*
      case 'illegality_code':
        var h = [["1", "違規臨時停車"], ["2", "違規停車"], ["3", "變換車道未使用方向燈"], ["4", "轉彎未使用方向燈"], ["5", "闖紅燈"], ["6", "紅燈時佔用機車停等區"], ["7", "紅燈越線"], ["8", "機車行駛禁行車道"], ["9", "汽車行駛機(慢)車專用道"], ["10", "跨越雙白線行駛"], ["11", "跨越雙黃線行駛"], ["12", "違規左右轉"], ["13", "其他"]]
          // var optgroups = ''
          var opts = ''
    $.each(h, function (i, e) {
      if (e[0] === value_input.val()) {
        opts += '<option selected value=' + e[0] + '>' + e[0] + '-' + e[1] + '</option>'
      } else {
        opts += '<option value=' + e[0] + '>' + e[0] + '-' + e[1] + '</option>'
      }
    });

    var s = '<select class="search-value-input custom-select" name="' + value_input.attr('name') + '" id="' + value_input.attr('id') + '">' + opts + '</select>'
    search_condition.val('eq').change();
    search_condition.find('option').not('[value="eq"]').attr('hidden', '')
    wrapper.html(s);
    wrapper.find('.search-value-input').select2();
    break;
        case 'illegality_name':
    var h = [["1", "違規臨時停車"], ["2", "違規停車"], ["3", "變換車道未使用方向燈"], ["4", "轉彎未使用方向燈"], ["5", "闖紅燈"], ["6", "紅燈時佔用機車停等區"], ["7", "紅燈越線"], ["8", "機車行駛禁行車道"], ["9", "汽車行駛機(慢)車專用道"], ["10", "跨越雙白線行駛"], ["11", "跨越雙黃線行駛"], ["12", "違規左右轉"], ["13", "其他"]]
          // var optgroups = ''
          var opts = ''
  $.each(h, function (i, e) {
    if (e[1] === value_input.val()) {
      opts += '<option selected value=' + e[1] + '>' + e[0] + '-' + e[1] + '</option>'
    } else {
      opts += '<option value=' + e[1] + '>' + e[0] + '-' + e[1] + '</option>'
    }
  });

  var s = '<select class="search-value-input custom-select" name="' + value_input.attr('name') + '" id="' + value_input.attr('id') + '">' + opts + '</select>'
  search_condition.val('eq').change();
  search_condition.find('option').not('[value="eq"]').attr('hidden', '')
  wrapper.html(s);
  wrapper.find('.search-value-input').select2();
  break;
     */
        case 'state':
  var h = {"ready":"案件就緒","reassigning":"案件改派","approving":"案件處理","reviewing":"案件審核","rechecking":"案件複審","complete":"案件結束"}
	  var h = {"ready":"案件處理","reviewing":"案件審核","complete":"案件結束"}
          var opts = ''


  $.each(h, function (k, v) {
    if (k === value_input.val()) {
      opts += '<option selected value=' + k + '>' + v + '</option>'
    } else {
      opts += '<option value=' + k + '>' + v + '</option>'
    }
  });

  var input_html = '<select class="search-value-input custom-select" name="' + value_input.attr('name') + '" id="' + value_input.attr('id') + '">' + opts + '</select>'
  search_condition.val('eq').change();
  search_condition.find('option').not('[value="eq"]').attr('hidden', '')
  wrapper.html(input_html);
  wrapper.find('.search-value-input').select2();
  break;
	case 'DetectLocation':
		var input_html = '<select class="search-value-input custom-select" name="' + value_input.attr('name') + '" id="' + value_input.attr('id') + '"><?php 
if( $row_user["flag6"] == "1" )
	echo '<option>崇德八路口</option>';
if( $row_user["flag7"] == "1" )
	echo '<option>漢口中清</option>';
if( $row_user["flag8"] == "1" )
        echo '<option>五權西一街</option>';
if( $row_user["flag9"] == "1" )
        echo '<option>福人街口</option>';
if( $row_user["flag10"] == "1" )
        echo '<option>文心五權</option>';
if( $row_user["flag11"] == "1" )
        echo '<option>清水臨港路</option>';
if( $row_user["flag12"] == "1" )
        echo '<option>大雅中清</option>';
if( $row_user["flag13"] == "1" )
        echo '<option>西勢路口</option>';
if( $row_user["flag14"] == "1" )
        echo '<option>國豐路口</option>';
if( $row_user["flag15"] == "1" )
        echo '<option>東勢大橋</option>';
if( $row_user["flag16"] == "1" )
        echo '<option>民權承德</option>';
if( $row_user["flag17"] == "1" )
        echo '<option>市民建國2</option>';
?></select>'
  search_condition.val('eq').change();
  search_condition.find('option').not('[value="eq"]').attr('hidden', '')
  wrapper.html(input_html);
  wrapper.find('.search-value-input').select2();
	break;
        case 'expose':
	     var input_html = '<select class="search-value-input custom-select" name="' + value_input.attr('name') + '" id="' + value_input.attr('id') + '"><option>舉發</option><option>不舉發</option></select>'
  search_condition.val('eq').change();
				    search_condition.find('option').not('[value="eq"]').attr('hidden', '')
			      wrapper.html(input_html);
				    wrapper.find('.search-value-input').select2();
        break;
        case 'illegality_code':
                var input_html = '<select class="search-value-input custom-select" name="' + value_input.attr('name') + '" id="' + value_input.attr('id') + '"><?php
$resultil = $pdo->query("SELECT * FROM `illegalities` order by id");
while($rowil = $resultil->fetch(PDO::FETCH_ASSOC)){
	echo '<option value="'.$rowil["name"].'" title="'.$rowil["name"].'">';
        if( mb_strlen( $rowil["name"] ) >= 20 )
                        echo mb_substr($rowil["name"],0,20,"utf-8").'....';
        else
                        echo $rowil["name"];
	echo '</option>';
}
?></select>'
  search_condition.val('eq').change();
  search_condition.find('option').not('[value="eq"]').attr('hidden', '')
  wrapper.html(input_html);
  wrapper.find('.search-value-input').select2();
        break;
        default:
  if (e && e.type == 'change') {
    search_condition.val('cont').change();
  }
  search_condition.find('[value="lteq"], [value="gteq"]').attr('hidden', '')
  if (e && e.type === 'change') {
    var v = ''
  } else {
    var v = value_input.val()
  }
  wrapper.html('<input class="form-control search-value-input" type="text" value="' + v + '" name="' + value_input.attr('name') + '" id="' + value_input.attr('id') + '">')

  break;
      }
      // $('.search-value-input')
    }

  function sync_attribute_change() {
    $('.attribute-select').change(function (e) {
      response_search_value_input(this, e)
    });
  }
  $(function () {
    sync_attribute_change()

    $('.attribute-select').each(function () {
      response_search_value_input(this);
    })
    $('.expose_radio_buttons').change(function () {
      if ($(this).val() === 'true') {
        $(this).parents('.review-form').find('.expose_fields').show()
        $(this).parents('.review-form').find('.unexpose_fields').hide()

      } else {
        $(this).parents('.review-form').find('.expose_fields').hide()
        $(this).parents('.review-form').find('.unexpose_fields').show()
      }
    });
  });

  function review_case(id) {
    var data = {
      "case_ids": [id],
      "case": {}
    }

    data["case"][id] = {
      "expose": $('[name="case[' + id + '][expose]"]:checked').val(),
      "expose_reason_code": $('select[name="case[' + id + '][expose_reason_code]"]').val(),
      "unexpose_reason_id": $('select[name="case[' + id + '][unexpose_reason_id]"]').val(),
      "unexpose_reason_note": $('input[name="case[' + id + '][unexpose_reason_note]"]').val()
    }

    $.post("/cases.php", data)
  }
  function transform_cases(event) {
    if ($('.select_checkbox:checked').length > 0) {
      if (event == 'review') {
        $.post('/tc/admin/cases/batch_expose', $('.index-ftransform_casesorm').serialize())
      } else if (event == 'assign') {
        if ($('[name="cases[department_id]"]').val() == '') {
          toastr.error('請選擇分局');
        } else {
          var ids = [];
          var department_id = $('[name="cases[department_id]"]').val();
          $.each($('.select_checkbox:checked'), function () {
            ids.push($(this).val());
          });
          $('.select_checkbox:checked')
          $.post("cases.php?act=assign", { case_ids: ids, department_id: department_id })
        }
      } else if( event == 'allreview' ) {
	if( $('#cases_expose_false:checked').val() ) {
		if( !$('#cases_unexpose_reason_id').val() ) {
			toastr.error('請選擇不舉發原因');
		} else {
          var ids = [];
          var review_user_id = '<?=$row_user["id"];?>';
          var review_department = '<?=$row_user["department"];?>';
          var expose = '不舉發';
          var expose_msg = $('#cases_unexpose_reason_id').val();
          $.each($('.select_checkbox:checked'), function () {
            ids.push($(this).val());
          });
          $('.select_checkbox:checked')
		  $('#allreview').attr('disabled', 'disabled').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>正在處理中，請耐心等候...');
          $.post("cases.php?act=allreview", { case_ids: ids, expose: expose, expose_msg: expose_msg,review_user_id: review_user_id,review_department: review_department }).done(function(date){ location.reload(true); });
		}
        } else {
                if( !$('#cases_expose_reason_code').val() ) {
                        toastr.error('請選擇舉發條款');
                } else {
          var ids = [];
          var review_user_id = '<?=$row_user["id"];?>';
          var review_department = '<?=$row_user["department"];?>';
          var expose = '舉發';
          var expose_msg = $('#cases_expose_reason_code').val();
          $.each($('.select_checkbox:checked'), function () {
            ids.push($(this).val());
          });
          $('.select_checkbox:checked')
	    $('#allreview').attr('disabled', 'disabled').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>正在處理中，請耐心等候...');
          $.post("cases.php?act=allreview", { case_ids: ids, expose: expose, expose_msg: expose_msg,review_user_id: review_user_id,review_department: review_department }).done(function(date){ location.reload(true); });
		}
	} 
      } else if( event == 'todepartment' ) {
        if ($('[name="cases[department_id]"]').val() == '') {
          toastr.error('請選擇分局');
        } else {
          var ids = [];
          var department_id = $('[name="cases[department_id]"]').val();
          $.each($('.select_checkbox:checked'), function () {
            ids.push($(this).val());
          });
          $('.select_checkbox:checked')
          $.post("cases.php?act=todepartment", { case_ids: ids, department_id: department_id }).done(function(date){ location.reload(true); });
        }
      } else if( event == 'touserid' ) {
          var ids = [];
          var userid = $('[name="cases[userid]"]').val();
          $.each($('.select_checkbox:checked'), function () {
            ids.push($(this).val());
          });
          $('.select_checkbox:checked')
          $.post("cases.php?act=touserid", { case_ids: ids, userid: userid }).done(function(date){ location.reload(true); });
      } else if( event == 'todepartment2' ) {
          var ids = [];
          var department_id = $('[name="cases[department_id]"]').val();
          $.each($('.select_checkbox:checked'), function () {
            ids.push($(this).val());
          });
          $('.select_checkbox:checked')
          $.post("cases.php?act=todepartment2", { case_ids: ids, department_id: department_id }).done(function(date){ location.reload(true); });
      } else if( event == 'toready' ) {
          var ids = [];
          var userid = '<?=$row_user["id"];?>';
          $.each($('.select_checkbox:checked'), function () {
            ids.push($(this).val());
          });
          $('.select_checkbox:checked')
          $.post("cases.php?act=toready", { case_ids: ids, userid: userid }).done(function(date){ location.reload(true); });
      } else if( event == 'alltonready' ) {
          var ids = [];
          var userid = '<?=$row_user["id"];?>';
          $.each($('.select_checkbox:checked'), function () {
            ids.push($(this).val());
          });
          $('.select_checkbox:checked')
          $.post("cases.php?act=alltonready", { case_ids: ids, userid: userid }).done(function(date){ location.reload(true); });
      } else if( event == 'alltoready' ) {
          var ids = [];
          var userid = '<?=$row_user["id"];?>';
          $.each($('.select_checkbox:checked'), function () {
            ids.push($(this).val());
          });
          $('.select_checkbox:checked')
          $.post("cases.php?act=alltoready", { case_ids: ids, userid: userid }).done(function(date){ location.reload(true); });
      } else if( event == 'alltoreview' ) {
          var ids = [];
          var userid = '<?=$row_user["id"];?>';
          $.each($('.select_checkbox:checked'), function () {
            ids.push($(this).val());
          });
          $('.select_checkbox:checked')
          $.post("cases.php?act=alltoreview", { case_ids: ids, userid: userid }).done(function(date){ location.reload(true); });
      } else if( event == 'alltorecheck' ) {
          var ids = [];
          var userid = '<?=$row_user["id"];?>';
          $.each($('.select_checkbox:checked'), function () {
            ids.push($(this).val());
          });
          $('.select_checkbox:checked')
          $.post("cases.php?act=alltorecheck", { case_ids: ids, userid: userid }).done(function(date){ location.reload(true); });
      } else if( event == 'alltocomplete' ) {
          var ids = [];
          var userid = '<?=$row_user["id"];?>';
          $.each($('.select_checkbox:checked'), function () {
            ids.push($(this).val());
          });
          $('.select_checkbox:checked')
          $.post("cases.php?act=alltocomplete", { case_ids: ids, userid: userid }).done(function(date){ location.reload(true); });
      } else if( event == 'complete' ) {
        $('input[name="event"]').val(event);
	$('.index-form').attr('action', "cases.php?act=complete&page=<?=$_GET["page"];?>&state=<?=$_GET["state"];?>").submit();
      } else if( event == 'ready' ) {
        $('input[name="event"]').val(event);
	$('.index-form').attr('action', "cases.php?act=ready&page=<?=$_GET["page"];?>&state=<?=$_GET["state"];?>").submit();
      } else {
        $('input[name="event"]').val(event);
	$('.index-form').attr('action', "cases.php?page=<?=$_GET["page"];?>&state=<?=$_GET["state"];?>").submit();
      }

    } else {
      toastr.error('請先選擇案件');
    }
  }

  function assign_case(d, case_id) {
    var department_id = $(d).val();

    $.post("/tc/admin/cases/assign", { case_ids: case_id, department_id: department_id })
  }
</script>
  <script type="text/javascript">
    $(function(){


$('body').bind('change keyup input keydown keyup','input[name="comment"]',function(e) {
        if( $('input[name="comment"]').val().length > 11 ) {
                toastr.error('結案附加訊息超過10個字,請重新輸入!');
                $('input[name="comment"]').val('');
        }
});

      jQuery.validator.addClassRules('attribute-select', {
        required: true
      });
      jQuery.validator.addClassRules('search-value-input', {
        required: true
      });

      $( ".search-form" ).validate( {
        focusInvalid: false,
        invalidHandler: function() {
          $(this).find(".is-invalid:first").focus();
        },

        errorElement: "div",
        errorPlacement: function ( error, element ) {
          // error.addClass( "invalid-feedback d-block" );

          // if ( element.prop( "type" ) === "checkbox" ) {
          //   error.insertAfter( element.parent( "label" ) );
          // }else if(element.next().hasClass('input-group-append')){
          //   error.appendTo(element.parent());
          // }else {
          //   error.insertAfter( element );
          // }
        },
        highlight: function ( element, errorClass, validClass ) {
          $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        },
        unhighlight: function (element, errorClass, validClass) {
          $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
      });


      $('.search-form').on('click', '.remove_search_fields', function(event) {
        $(this).parents('.field').remove();
        return event.preventDefault();
      });
      return $('form').on('click', '.add_search_fields', function(event) {
        var regexp, time;
        time = new Date().getTime();
        regexp = new RegExp($(this).data('id'), 'g');
        $(this).before($(this).data('fields').replace(regexp, time));
          sync_attribute_change()
        return event.preventDefault();
      });
    })

          //匯出csv
      function csv_btn() {
        var filename = "案件處理.csv";
        var url =
          "./php/GetExportCSVCar2.php?" +
          "txt_date1=" +
          $("#txt_date1").val() +
          "&txt_date2=" +
          $("#txt_date2").val();
        var downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        downloadLink.href = url;
        downloadLink.download = filename;
        downloadLink.click();

        document.body.removeChild(downloadLink);
      }

  </script>



  </body>


</html>

