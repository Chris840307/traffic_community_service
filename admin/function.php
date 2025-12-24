<?php
session_start();
include "ipinfo.php";

$result = $pdo->prepare("SELECT * FROM `user_list` where `acc`=? and `pwd`=?");
$result->execute(array($_SESSION["acc"], $_SESSION["pwd"]));
$row_user = $result->fetch(PDO::FETCH_ASSOC);
if( $row_user["id"] == "" ) {
echo '<script>alert("請先登入會員!");</script>';
echo '<script>document.location.href="index.php";</script>';
exit;
}

$car_array['state'] = array("ready"=>"案件處理","reviewing"=>"案件審核","complete"=>"案件結束");

if( isset( $_POST['q']['c'] )) 
       		$_SESSION['formdata'] = $_POST['q']['c'];

if( isset( $_GET["n"] ) )
	$_SESSION['formdata'] = null;

function ImageResize($from_filename, $save_filename, $in_width=165, $in_height=165, $quality=100)
{
    $allow_format = array('jpeg', 'png', 'gif');
    $sub_name = $t = '';

    // Get new dimensions
    $img_info = getimagesize($from_filename);
    $width    = $img_info['0'];
    $height   = $img_info['1'];
    $imgtype  = $img_info['2'];
    $imgtag   = $img_info['3'];
    $bits     = $img_info['bits'];
    $channels = $img_info['channels'];
    $mime     = $img_info['mime'];

    list($t, $sub_name) = explode('/', $mime);
    if ($sub_name == 'jpg') {
        $sub_name = 'jpeg';
    }

    if (!in_array($sub_name, $allow_format)) {
        return false;
    }

    // 取得縮在此範圍內的比例
    $percent = getResizePercent($width, $height, $in_width, $in_height);
    $new_width  = $width * $percent;
    $new_height = $height * $percent;

    // Resample
    $image_new = imagecreatetruecolor($new_width, $new_height);

    // $function_name: set function name
    //   => imagecreatefromjpeg, imagecreatefrompng, imagecreatefromgif
    /*
    // $sub_name = jpeg, png, gif
    $function_name = 'imagecreatefrom'.$sub_name;
    $image = $function_name($filename); //$image = imagecreatefromjpeg($filename);
    */
    $image = imagecreatefromjpeg($from_filename);

    imagecopyresampled($image_new, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    return imagejpeg($image_new, $save_filename, $quality);
}

/**
 * 抓取要縮圖的比例
 * $source_w : 來源圖片寬度
 * $source_h : 來源圖片高度
 * $inside_w : 縮圖預定寬度
 * $inside_h : 縮圖預定高度
 *
 * Test:
 *   $v = (getResizePercent(1024, 768, 400, 300));
 *   echo 1024 * $v . "\n";
 *   echo  768 * $v . "\n";
 */
function getResizePercent($source_w, $source_h, $inside_w, $inside_h)
{
    if ($source_w < $inside_w && $source_h < $inside_h) {
        return 1; // Percent = 1, 如果都比預計縮圖的小就不用縮
    }

    $w_percent = $inside_w / $source_w;
    $h_percent = $inside_h / $source_h;

    return ($w_percent > $h_percent) ? $h_percent : $w_percent;
}

function ismobile() {
	    // 如果有HTTP_X_WAP_PROFILE則一定是移動裝置
	    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
	        return true;
	    
	    //此條摘自TPM智慧切換模板引擎，適合TPM開發
	    if(isset ($_SERVER['HTTP_CLIENT']) &&'PhoneClient'==$_SERVER['HTTP_CLIENT'])
	        return true;
	    //如果via資訊含有wap則一定是移動裝置,部分服務商會遮蔽該資訊
	    if (isset ($_SERVER['HTTP_VIA']))
	        //找不到為flase,否則為true
	        return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
	    //判斷手機發送的客戶端標誌,相容性有待提高
	    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
	        $clientkeywords = array(
	            'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
	        );
	        //從HTTP_USER_AGENT中查詢手機瀏覽器的關鍵字
	        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
	            return true;
	        }
	    }
	    //協議法，因為有可能不準確，放到最後判斷
	    if (isset ($_SERVER['HTTP_ACCEPT'])) {
	        // 如果只支援wml並且不支援html那一定是移動裝置
	        // 如果支援wml和html但是wml在html之前則是移動裝置
	        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
	            return true;
	        }
	    }
	    return false;
	 }

?>
