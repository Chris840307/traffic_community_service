<?php

function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
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

try {
    $pdo = new PDO("mysql:host=localhost;dbname=car8", "root", "2u6u/ru8");
    $pdo->query('set names utf8;');
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

function w_ip_1() {
$ipinfo=json_encode(ip_info($GLOBALS["ip"]));
$GLOBALS["pdo"]->prepare("INSERT INTO `log_black` (`id`, `ip`,`serverinfo`,`request`,`ipinfo`,`ts`) VALUES (NULL, ?,?,?,?, CURRENT_TIMESTAMP)")->execute(array($GLOBALS["ip"],json_encode($_SERVER),json_encode($_REQUEST),$ipinfo));
}

function w_sys_log($acc = NULL,$passwd = NULL,$class = NULL,$content = NULL){
$GLOBALS["pdo"]->prepare("INSERT INTO `log_sys`(`id`,`acc`, `name`, `class`, `ip`, `content`) VALUES (NULL,?,?,?,?,?)")->execute(array($acc,$passwd,$class,$GLOBALS["ip"],$content));
}

$blacklist = file('blacklist.txt', FILE_IGNORE_NEW_LINES);
$ip = isset($_SERVER['REMOTE_ADDR']) ? trim($_SERVER['REMOTE_ADDR']) : '';

if (($key = array_search($ip, $blacklist)) !== false) {
                echo 'You are forbidden from accessing this resource!';
                exit();
}

$blacklist = file('whitelist.txt', FILE_IGNORE_NEW_LINES);
$ip = isset($_SERVER['REMOTE_ADDR']) ? trim($_SERVER['REMOTE_ADDR']) : '';


/*
if (($key = array_search($ip, $blacklist)) !== false) {
		$nothing = 1;
} else {
              echo '您的 IP 不在允許名單中,請與管理員聯繫!';
              exit();
}
 */


//白名單
$strarray = "";
$strarray = array('125.227.73.73','124.199.99.217');
$white_list = "";
foreach ($strarray as $item)
{
                            $white_list .= "'$item'" . ",";
}
$white_list = rtrim($white_list, ",");
$pdo->query("DELETE FROM `log_black` WHERE `ip` IN ($white_list)");


$cdate=date("Y-m-d");
$result = $pdo->prepare("SELECT * FROM `log_black` where `ip`=? and `ts` like '".$cdate."%'");
$result->execute(array($ip));
$allRecrods = $result->rowCount();
if( $allRecrods>=100 ) {
        echo '<script>document.location.href="404.html";</script>';
        exit;
}

//白名單
$strarray = "";
$strarray = array('AYY-0129','AMZ-7963','BFF-3318','AFQ-9650','032-LDW','NLV-8568','MAA-9988');
$white_list = "";
foreach ($strarray as $item)
{
                            $white_list .= "'$item'" . ",";
}
$white_list = rtrim($white_list, ",");

$pdo->query("INSERT INTO `cases_white` SELECT * FROM `cases` WHERE `full_car_number` IN ($white_list)");
$pdo->query("DELETE FROM `cases` WHERE `full_car_number` IN ($white_list)");
?>
