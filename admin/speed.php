<?php
function GetIP(){
        if (!empty($_SERVER["HTTP_CLIENT_IP"]))
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        elseif (!empty($_SERVER["REMOTE_ADDR"]))
            $cip = $_SERVER["REMOTE_ADDR"];
        else
            $cip = "noip";
        return $cip;
}
function GetDevice(){
                 $out_txt = '';
                 $ua = '';
                 $ua = $_SERVER["HTTP_USER_AGENT"];

                 /* ==== Detect the OS ==== */

                 // ---- Mobile ----

                 // Android
                 $android = strpos($ua, 'Android') ? true : false;

                 // BlackBerry
                 $blackberry = strpos($ua, 'BlackBerry') ? true : false;

                 // iPhone
                 $iphone = strpos($ua, 'iPhone') ? true : false;

                 // Palm
                 $palm = strpos($ua, 'Palm') ? true : false;

                 // ---- Desktop ----

                 // Linux
                 $linux = strpos($ua, 'Linux') ? true : false;

                 // Macintosh
                 $mac = strpos($ua, 'Macintosh') ? true : false;

                 // Windows
                 $win = strpos($ua, 'Windows') ? true : false;

                 /* ============================ */

                 /* ==== Detect the UA ==== */
                 // Chrome
                 $chrome = strpos($ua, 'Chrome') ? true : false; // Google Chrome

                 // Firefox
                 $firefox = strpos($ua, 'Firefox') ? true : false; // All Firefox
                 $firefox_2 = strpos($ua, 'Firefox/2.0') ? true : false; // Firefox 2
                 $firefox_3 = strpos($ua, 'Firefox/3.0') ? true : false; // Firefox 3
                 $firefox_3_6 = strpos($ua, 'Firefox/3.6') ? true : false; // Firefox 3.6


                 // Internet Exlporer
                 $msie = strpos($ua, 'MSIE') ? true : false; // All Internet Explorer
                 $msie_7 = strpos($ua, 'MSIE 7.0') ? true : false; // Internet Explorer 7
                 $msie_8 = strpos($ua, 'MSIE 8.0') ? true : false; // Internet Explorer 8

                 // Opera
                 $opera = preg_match("/bOperab/i", $ua); // All Opera

                 // Safari
                 $safari = strpos($ua, 'Safari') ? true : false; // All Safari
                 $safari_2 = strpos($ua, 'Safari/419') ? true : false; // Safari 2
                 $safari_3 = strpos($ua, 'Safari/525') ? true : false; // Safari 3
                 $safari_3_1 = strpos($ua, 'Safari/528') ? true : false; // Safari 3.1
                 $safari_4 = strpos($ua, 'Safari/531') ? true : false; // Safari 4

                 /* ============================ */
                 if ($ua) {
                         // ---- Test if using a Handheld Device ----
                         if ($android) $out_txt = 'Android';

                         if ($blackbery) $out_txt = 'Blackbery';

                         if ($iphone) $out_txt = 'iPhone';

                         if ($palm) $out_txt = 'Palm';

                         if ($linux) $out_txt = 'Linux';

                         if ($mac) $out_txt = 'Mac';

                         if ($win) $out_txt = 'Windows';
                }
                return $out_txt;
         }

function GetUA(){
                 $out_txt = '';
                 $ua = '';
                 $ua = $_SERVER["HTTP_USER_AGENT"];

                 /* ==== Detect the UA ==== */

                 // Chrome
                 $chrome = strpos($ua, 'Chrome') ? true : false; // Google Chrome

                 // Firefox
                 $firefox = strpos($ua, 'Firefox') ? true : false; // All Firefox
                 $firefox_2 = strpos($ua, 'Firefox/2.0') ? true : false; // Firefox 2
                 $firefox_3 = strpos($ua, 'Firefox/3.0') ? true : false; // Firefox 3
                 $firefox_3_6 = strpos($ua, 'Firefox/3.6') ? true : false; // Firefox 3.6


                 // Internet Exlporer
                 $msie = strpos($ua, 'MSIE') ? true : false; // All Internet Explorer
                 $msie_7 = strpos($ua, 'MSIE 7.0') ? true : false; // Internet Explorer 7
                 $msie_8 = strpos($ua, 'MSIE 8.0') ? true : false; // Internet Explorer 8

                 // Opera
                 $opera = preg_match("/bOperab/i", $ua); // All Opera

                 // Safari
                 $safari = strpos($ua, 'Safari') ? true : false; // All Safari
                 $safari_2 = strpos($ua, 'Safari/419') ? true : false; // Safari 2
                 $safari_3 = strpos($ua, 'Safari/525') ? true : false; // Safari 3
                 $safari_3_1 = strpos($ua, 'Safari/528') ? true : false; // Safari 3.1
                 $safari_4 = strpos($ua, 'Safari/531') ? true : false; // Safari 4

                 /* ============================ */
                 if ($ua) {
                         // ---- Test if using a Handheld Device ----
                         if ($chrome) $out_txt = 'Chrome';
                         elseif ($firefox || $firefox_2 || $firefox_3 || $firefox_3_6) $out_txt = 'firefox';
                         elseif ($msie || $msie_7 || $msie_8) $out_txt = 'msie';
                         elseif ($opera) $out_txt = 'opera';
                         elseif ($safari || $safari_2 || $safari_3 || $safari_3_1 || $safari_4) $out_txt = 'safari';
                }
                return $out_txt;
         }

                $kb=1024;
                echo "<!-";
                flush();
                $time = explode(" ",microtime());
                $start = $time[0] + $time[1];
                for($x=0;$x<$kb;$x++){
                        echo str_pad('', 1024, '.');
                        flush();
                }
                $time = explode(" ",microtime());
                $finish = $time[0] + $time[1];
                $deltat = $finish - $start;
                echo "->";
                $time_s = round($deltat,4);
                $kbs_t = intval((round($kb / $deltat, 0)/1000));

                ($kbs_t - 5) > 0 ? $kbs_d = ($kbs_t - 5) : $kbs_d = $kbs_t;

                $d_txt = '';

                $GetDevice = GetDevice();
                if(!empty($GetDevice))$d_txt .= '<b>您的系統為:</b>'.$GetDevice.'+'.GetUA();
                $GetIP = GetIP();
                if(!empty($GetIP))$d_txt .= ' <b>IP:</b>'.$GetIP;


$s_txt = '';
if($kbs_t < 20 ){
        $s_txt = '<font color="#ef4655">網路速度不夠快,請提升您的網路已提升撥放流暢度!</font>';
}elseif($kbs_t < 40 ){
        $s_txt = '<font color="#f7aa38">頻寬不太足夠!</font>';
}elseif($kbs_t < 60 ){
        $s_txt = '<font color="#fffa50">網路順暢!</font>';
}else{
        $s_txt = '<font color="#5ee432">網路順暢!</font>';
}
?>
        <link rel="stylesheet" type="text/css" href="/css/normalize.css" /><!--CSS RESET-->
    <style>
.h-c-div > .c-div{
        margin: 5px;

}
        .gauge-container{
                width:300px;
                margin-left: calc( 50% - 150px );
        }
.gauge-container > .gauge > .dial {
  stroke-width: 7;
}
.gauge-container > .gauge > .value {
  stroke-width: 5;
}
.h-c-div{
        width:100%;
        text-align:center;
}
.home-main {
    margin-right: 0;
}
        </style>
                            <div class="col-container" data-v-eb306074="">
                                <main class="home-main" data-v-eb306074="">
                                    <div aria-label="主題企劃欄，共有10項" data-v-eb306074="">
                                        <div id="topic0" class="oa-card-program-wrap" style="display: ;">
                                            <div class="oa-card-program">
                                            <article class="htmleaf-container">
                <div class="h-c-div">
        <div class="c-div" style="font-weight:bold; font-size:22px;">新竹縣伺服器端測速結果</div>
        <div id="gauge2" class="gauge-container two"></div>

        <div style="color:rgb(153,153,153); font-size:14px;">MB/s</div>
        <div class="c-div" style="background-color: #eee;font-size: 32px; font-weight: bold;"><?=$s_txt?></div>
        <div class="c-div"><?=$d_txt?></div>
        </div>
        </article>
                                            </div>
                                        </div>
                                    </div>
                                </main>
                            </div>
        <script type="text/javascript" src="https://learninggo.com.tw/js/gauge.js"> </script>
        <script>
        function getRandomArbitrary(min, max) {
  return parseInt(Math.random() * (max - min) + min);
}
        var gauge2 = Gauge(
        document.getElementById("gauge2"),
                    {
          min: 0,
          max: 200,
          dialStartAngle: 180,
          dialEndAngle: 0,
          value: parseInt('<?=$kbs_t?>'),
          viewBox: "0 0 100 57",
          color: function(value) {
            if(value < 20) {
              return "#ef4655";
            }else if(value < 40) {
              return "#f7aa38";
            }else if(value < 60) {
              return "#fffa50";
            }else {
              return "#5ee432";
            }
          }
        }
      );
	(function loop() {


		        var value1 = getRandomArbitrary(　parseInt('<?=$kbs_d?>'), parseInt('<?=$kbs_t?>')),
				                value2 = Math.random();

			                console.log('<?=$kbs_t?>'+" MB/s");
			                console.log(value1+" MB/s");
					        gauge2.setValueAnimated(value1, 3);

					        window.setTimeout(loop, 4000);
						      })();
	        </script>

