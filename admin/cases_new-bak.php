<?php
include "function.php";
$nav="complete";
if( $_POST["commit"] == "案件傳送" ) {

$sn_date = str_replace('-','',substr(date("Y-m-d H:i:s"),0,10));
$check_sn = $pdo->query('select * from `cases` where `DetectLocation`="移動式測速" and `sn` like "%'.$sn_date.'%" order by `sn` desc limit 1');
$sn_sum = $check_sn->rowCount();
if( $sn_sum == 0 ) {
	$sn = 'L'.$sn_date.'00001';
} else {
	$row_sn = $check_sn->fetch(PDO::FETCH_ASSOC);
	$sn = 'L'.(substr($row_sn["sn"],1,13)+1);
}
$first_car_number=strtoupper($_POST["doc"]["first_car_number"]);
$last_car_number=strtoupper($_POST["doc"]["last_car_number"]);
$car_max=$_POST["doc"]["car_max"];
$car_real=$_POST["doc"]["car_real"];
$full_car_number=$first_car_number.'-'.$last_car_number;
$car_type_code=$_POST["doc"]["car_type_code"];
$DetectLocation="移動式測速";
$Location=$_POST["doc"]["area_district"];
$reason_code=$_POST["doc"]["expose_reason_code"];
$reason_code .= ' ( 限速'.$car_max.'公里, 實際測速'.$car_real.'公里 )。';
$created_at=str_replace('/','-',$_POST["doc"]["violated_at"]).':00';
$state="complete";
$VideoURL="";
$PhotoURL='l/'.date("Y").'/'.date("m").'/'.date("Ym").'/'.$full_car_number.'/result.jpg';
$review_user_id=$row_user["name"];
$review_department=$row_user["department"];
$file_no="";
$expose='舉發';
if( $_FILES["doc"]["name"]["doc_attachments_attributes"][0]["file"] != "" ) {
	$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
	$detectedType = exif_imagetype($_FILES['doc']['tmp_name']['doc_attachments_attributes'][0]['file']);
	$error = !in_array($detectedType, $allowedTypes);
	if( $error == "" ) {
		if(!file_exists('../CarSystem/l/'.date("Y")))
			mkdir('../CarSystem/l/'.date("Y"));
		if(!file_exists('../CarSystem/l/'.date("Y").'/'.date("m")))
			mkdir('../CarSystem/l/'.date("Y").'/'.date("m"));	
		if(!file_exists('../CarSystem/l/'.date("Y").'/'.date("m").'/'.date("Ym")))
			mkdir('../CarSystem/l/'.date("Y").'/'.date("m").'/'.date("Ym"));
		if(!file_exists('../CarSystem/l/'.date("Y").'/'.date("m").'/'.date("Ym").'/'.$full_car_number))
			mkdir('../CarSystem/l/'.date("Y").'/'.date("m").'/'.date("Ym").'/'.$full_car_number);
		move_uploaded_file($_FILES['doc']['tmp_name']['doc_attachments_attributes'][0]['file'], '../CarSystem/l/'.date("Y").'/'.date("m").'/'.date("Ym").'/'.$full_car_number.'/result.jpg');
	} else {
		$file_no = 1;
	}
} else {
	$file_no = 1;
}

if( $file_no == "" ) {
	$today_date = date("Y-m-d H:i:s");
$pdo->prepare("INSERT INTO `cases` (`id`, `car_type_code`,`first_car_number`, `last_car_number`, `sn`, `DetectLocation`, `Location`, `reason_code`, `full_car_number`, `VideoURL`, `PhotoURL`, `review_user_id`, `review_department`, `expose`, `state`, `created_at`, `updated_at`,`complete_date`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)")->execute(array($car_type_code,$first_car_number,$last_car_number,$sn,$DetectLocation,$Location,$reason_code,$full_car_number,$VideoURL,$PhotoURL,$review_user_id,$review_department,$expose,$state,$created_at,$created_at,$today_date));
        echo '<script>document.location.href="cases2.php?DetectLocation=移動式測速&page=1";</script>';
        exit;
} else {
	$error_msg='<font color="red">請上傳一張圖片!</font>';
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
  

  <link rel="stylesheet" media="all" href="/hsinchu/assets/admin-92e995e469ea98c880e61710f498cb7c0dddcd185d591b92bc985fb93e14d29a.css" />

</head>

  <body id="page-top" class="case_wrapper cases index collection">
<?php include "menu.php";?>
    <div id="wrapper">


      <div id="content-wrapper">
        <div class="container-fluid">
          <div class="flashes">
            

          </div>


          <form class="simple_form new_doc" id="new_doc" autocomplete="off" enctype="multipart/form-data" action="cases_new.php" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="authenticity_token" value="kqPzjBUO044JJldQLoM7blGvOnORJ7HdfL3nSAm0+clKTdNrPmzAnvjRuXtL6cxnbfzSBB6fDTl6uwSWTcNTEg==" />
<div class="card mb-3">

    <div class="card-header">
      <i class="far fa-info-square"></i>
      新增移動案件
      <div class="float-right">    <a class="btn btn-primary btn-sm" href="cases2.php?DetectLocation=移動式測速&n">回到列表</a>

<span class="divider"></span>
</div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-8">
          
          
          <div class="bgc-white p-20 bd">
            <div class="mT-30">
              
<div class="bgc-white p-20 bd">
  <div class="mT-30">
    <div class="alert" style="background-color: orange;text-align: center;">
     違規資料 
    </div>

    <div class="form-group row">
      <label class="col-sm-2 col-form-label">違規日期</label>
      <div class="col-sm-10 col-12">
      <input class="form-control string optional datetimepicker form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#doc_violated_at" type="text" name="doc[violated_at]" id="doc_violated_at" value="<?php if( $created_at != "" ) echo $created_at;?>" required />
      </div>
    </div>

    <div class="form-group row">
      <label class="col-sm-2 col-form-label">違規車號</label>
      <div class="col-sm-10">
        <div class="row">
          <div class="col-12">
	    <select class="form-control select optional form-control  select2" name="doc[car_type_code]" id="doc_car_type_code" required >
<?php if( $car_type_code != "" ) {
echo '<option value="'.$car_type_code.'" selected>'.$car_type_code.'</option>';
} else {
?>
<option value="">請選擇</option>
<?php } ?>
<option value="汽車">汽車</option>
<option value="重型機車(含白紅黃牌)">重型機車(含白紅黃牌)</option>
<option value="輕型機車(綠牌)">輕型機車(綠牌)</option>
<option value="動力機械">動力機械</option>
<option value="臨時牌">臨時牌</option>
<option value="試車牌">試車牌</option>
<option value="軍車">軍車</option>
<option value="領">領</option>
<option value="外">外</option>
<option value="使">使</option></select>
          </div>
        </div>

        <div class="row" style="margin-top:10px;">
          <div class="col-sm-2 col-5">
	  <input class="form-control string required form-control" style="text-transform:uppercase" required="required" aria-required="true" placeholder="" type="text" name="doc[first_car_number]" id="doc_first_car_number" value="<?php if( $first_car_number != "" ) echo $first_car_number;?>" required/>
          </div>
          &mdash;
          <div class="col-sm-2 col-5">
		  <input class="form-control string required form-control" style="text-transform:uppercase" required="required" aria-required="true" placeholder="" type="text" name="doc[last_car_number]" id="doc_last_car_number" value="<?php if( $last_car_number != "" ) echo $last_car_number;?>" required />
          </div>
        </div>
      </div>
    </div>

    <div class="form-group row">
      <label class="col-sm-2 col-form-label">違規地點</label>
      <div class="col-sm-10">
        <div class="row">
          <div class="col-sm-6">
	    <select class="form-control select required form-control district  select2" required="required" aria-required="請選擇" name="doc[area_district]" id="doc_area_district">
<?php if( $Location != "" ) {
echo '<option value="'.$Location.'" selected>'.$Location.'</option>';
} else {
?>
<option value="">請選擇</option>
<?php } ?>
</select>
          </div>

          <!--<div class="col-sm-3">
            <select class="form-control select required form-control  select2" required="required" aria-required="請選擇" name="doc[area_code]" id="doc_area_code"><option value="">請選擇</option>
</select>

          </div>-->
        </div>

        <!--<div class="row" style="margin-top:10px;padding-left:15px;">
          <div class="col-sm-2 input-group" style="padding-left:0px">
            <input class="form-control string optional form-control " type="text" name="doc[addr1]" id="doc_addr1" />
            <div class="input-group-append">
              <span class="input-group-text ">
                巷
              </span>
            </div>
          </div>
          <div class="col-sm-2 input-group" style="padding-left:2px">
            <input class="form-control string optional form-control " type="text" name="doc[addr2]" id="doc_addr2" />
            <div class="input-group-append">
              <span class="input-group-text ">
                弄
              </span>
            </div>
          </div>

          <div class="col-sm-2 input-group" style="padding-left:2px">
            <input class="form-control string optional form-control " type="text" name="doc[addr3]" id="doc_addr3" />
            <div class="input-group-append">
              <span class="input-group-text ">
                號
              </span>
            </div>
          </div>

          <div class="col-sm-2 input-group" style="padding-left:2px">
            <input class="form-control string optional form-control " type="text" name="doc[addr4]" id="doc_addr4" />
            <div class="input-group-append">
              <span class="input-group-text ">
                之號
              </span>
            </div>
          </div>

          <div class="col-sm-2 input-group" style="padding-left:2px">
            <input class="form-control string optional form-control " type="text" name="doc[addr5]" id="doc_addr5" />
            <div class="input-group-append">
              <span class="input-group-text ">
                公里
              </span>
            </div>
          </div>
        </div>-->

        <!--<div class="row" style="margin-top: 10px;">
          <div class="col-sm-12">
            <small class="form-text text-muted alert alert-info">選擇區域及路段後，若無法於上述欄位填寫詳細地址(巷、弄、號、KM等)，請於此欄位填寫說明(如匝道口、隧道東往西出口、高架橋南往北方向等)。</small>
            <input class="form-control string optional form-control " placeholder="選填，地點備註，最多50個字" type="text" name="doc[addr_detail]" id="doc_addr_detail" />

          </div>
        </div>-->
      </div>
    </div>

    <div class="form-group row">
      <label class="col-sm-2 col-form-label">違規事實</label>
      <div class="col-sm-10">
	<select class="form-control select optional form-control select2" name="doc[expose_reason_code]" id="doc_expose_reason_code" required>
<?php if( $reason_code != "" ) {
echo '<option value="'.$reason_code.'" selected>'.$reason_code.'</option>';
} else {
?>
<option value="">請選擇舉發條款</option>
<?php } ?>

<option value="4000005 - 汽車駕駛人行車速度，超過規定之最高時速20公里以內">4000005 - 汽車駕駛人行車速度，超過規定之最高時速20公里以內</option>
<option value="4000006 - 汽車駕駛人行車速度，超過規定之最高時速逾20公里至40公里以內">4000006 - 汽車駕駛人行車速度，超過規定之最高時速逾20公里至40公里以內</option>
<option value="4000007 - 汽車駕駛人行車速度，超過規定之最高時速逾40公里至60公里以內">4000007 - 汽車駕駛人行車速度，超過規定之最高時速逾40公里至60公里以內</option>
<option value="4310210 - 行車速度，超過規定之最高時速逾60公里至80公里以內">4310210 - 行車速度，超過規定之最高時速逾60公里至80公里以內</option>
<option value="4310211 - 行車速度，超過規定之最高時速逾80公里至100公里以內">4310211 - 行車速度，超過規定之最高時速逾80公里至100公里以內</option>
<option value="4310212 - 行車速度，超過規定之最高時速逾100公里以上">4310212 - 行車速度，超過規定之最高時速逾100公里以上</option>
<option value="4340044 - 駕駛人行車速度超過規定之最高時速六十公里以上(處車主)">4340044 - 駕駛人行車速度超過規定之最高時速六十公里以上(處車主)</option>
</select>
      </div>
    </div>

<div class="form-group row">
<label class="col-sm-2 col-form-label">限速/測速</label>
<div class="col-sm-10">

        <div class="row" style="margin-top:0px;">
          <div class="col-xs" style="padding-right:0px;float:left; display:inline;">
         &nbsp; （限速&nbsp; <input class="form-control string required form-control" style="text-transform:uppercase;width:54px;display:initial;" required="required" aria-required="true" placeholder="" type="text" name="doc[car_max]" id="doc_first_car_number" value="<?php if( $car_max != "" ) echo $car_max;?>" required/> 公里 ,</div>
          <div class="col-xs" style="padding-left:0px;">實際測速 <input class="form-control string required form-control" style="text-transform:uppercase;width:54px;display:initial;" required="required" aria-required="true" placeholder="" type="text" name="doc[car_real]" id="doc_last_car_number" value="<?php if( $car_real != "" ) echo $car_real;?>" required /> 公里 )
          </div>
        </div>


</div>
</div>

    <div class="form-group row doc_attachments">
      <label class="col-sm-2 col-form-label">上傳檔案</label>
      <div class="col-sm-10">
        
          <div class="nested_doc_attachment_inputs custom-file" style="margin-left: 14px;">
  <div class="row" style="margin-bottom: 10px;">
    <div class="col-10">
      <input class="form-control-file file required form-control custom-file-input doc_attachment_input form-control-sm" autocomplete="off" accept="image/*, video/*" aria-required="true" type="file" name="doc[doc_attachments_attributes][0][file]" id="doc_doc_attachments_attributes_0_file" required />
      <label class="custom-file-label file-label-0" for="customFile" <?php if( $file_no == 1 ) echo 'style="border-color:red;"';?>>
      <?php if( $file_no == 1 ) { echo '<font color=red>請選上傳一張照片</font>'; } else { ?> 選擇檔案 <?php } ?>
      </label>
      <input class="doc_attachment_cache" data-file-size="0" type="hidden" name="doc[doc_attachments_attributes][0][file_cache]" id="doc_doc_attachments_attributes_0_file_cache" />
    </div>
    <div class="col-2 clear-file-btn">
      <a href="javascript:void(0)" onclick="clear_file_input(0)"><i class="fas fa-trash-alt"></i></a>
    </div>
  </div>
</div>


          <div class="nested_doc_attachment_inputs custom-file" style="margin-left: 14px;">
  <div class="row" style="margin-bottom: 10px;">
    <div class="col-10">
      <input class="form-control-file file required form-control custom-file-input doc_attachment_input form-control-sm" autocomplete="off" accept="image/*, video/*" required="required" aria-required="true" type="file" name="doc[doc_attachments_attributes][1][file]" id="doc_doc_attachments_attributes_1_file" />
      <label class="custom-file-label file-label-1" for="customFile">
        選擇檔案
      </label>
      <input class="doc_attachment_cache" data-file-size="0" type="hidden" name="doc[doc_attachments_attributes][1][file_cache]" id="doc_doc_attachments_attributes_1_file_cache" />
    </div>
    <div class="col-2 clear-file-btn">
      <a href="javascript:void(0)" onclick="clear_file_input(1)"><i class="fas fa-trash-alt"></i></a>
    </div>
  </div>
</div>


          <div class="nested_doc_attachment_inputs custom-file" style="margin-left: 14px;">
  <div class="row" style="margin-bottom: 10px;">
    <div class="col-10">
      <input class="form-control-file file required form-control custom-file-input doc_attachment_input form-control-sm" autocomplete="off" accept="image/*, video/*" required="required" aria-required="true" type="file" name="doc[doc_attachments_attributes][2][file]" id="doc_doc_attachments_attributes_2_file" />
      <label class="custom-file-label file-label-2" for="customFile">
        選擇檔案
      </label>
      <input class="doc_attachment_cache" data-file-size="0" type="hidden" name="doc[doc_attachments_attributes][2][file_cache]" id="doc_doc_attachments_attributes_2_file_cache" />
    </div>
    <div class="col-2 clear-file-btn">
      <a href="javascript:void(0)" onclick="clear_file_input(2)"><i class="fas fa-trash-alt"></i></a>
    </div>
  </div>
</div>


          <div class="nested_doc_attachment_inputs custom-file" style="margin-left: 14px;">
  <div class="row" style="margin-bottom: 10px;">
    <div class="col-10">
      <input class="form-control-file file required form-control custom-file-input doc_attachment_input form-control-sm" autocomplete="off" accept="image/*, video/*" required="required" aria-required="true" type="file" name="doc[doc_attachments_attributes][3][file]" id="doc_doc_attachments_attributes_3_file" />
      <label class="custom-file-label file-label-3" for="customFile">
        選擇檔案
      </label>
      <input class="doc_attachment_cache" data-file-size="0" type="hidden" name="doc[doc_attachments_attributes][3][file_cache]" id="doc_doc_attachments_attributes_3_file_cache" />
    </div>
    <div class="col-2 clear-file-btn">
      <a href="javascript:void(0)" onclick="clear_file_input(3)"><i class="fas fa-trash-alt"></i></a>
    </div>
  </div>
</div>


          <div class="nested_doc_attachment_inputs custom-file" style="margin-left: 14px;">
  <div class="row" style="margin-bottom: 10px;">
    <div class="col-10">
      <input class="form-control-file file required form-control custom-file-input doc_attachment_input form-control-sm" autocomplete="off" accept="image/*, video/*" required="required" aria-required="true" type="file" name="doc[doc_attachments_attributes][4][file]" id="doc_doc_attachments_attributes_4_file" />
      <label class="custom-file-label file-label-4" for="customFile">
        選擇檔案
      </label>
      <input class="doc_attachment_cache" data-file-size="0" type="hidden" name="doc[doc_attachments_attributes][4][file_cache]" id="doc_doc_attachments_attributes_4_file_cache" />
    </div>
    <div class="col-2 clear-file-btn">
      <a href="javascript:void(0)" onclick="clear_file_input(4)"><i class="fas fa-trash-alt"></i></a>
    </div>
  </div>
</div>

      </div>
    </div>
  </div>
</div>



              
            </div>
          </div>

        </div>
      </div>
    </div>


</div>


<div class="form-actions">
  <input type="submit" name="commit" value="案件傳送" class="btn btn btn-primary" data-disable-with="送出" />
  <a class="btn btn-secondary" href="cases2.php?DetectLocation=移動式測速&n">取消</a>
</div>

</form>

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
    <script src="/hsinchu/assets/admin-7e641842b7678866dba9f029b1984fc78978fbe3c300f84802bb98e82b1f6905.js"></script>
<script type="text/javascript">

    function clear_file_input(t){
      var f_input = $('input[name="doc[doc_attachments_attributes][' + t + '][file]"]')
      f_input.val('').change();
      $('input[name="doc[doc_attachments_attributes][' + t + '][file_cache]').val('').change();
      $('.file-label-' + t).text('選擇檔案');
    }
    $(function(){
      $('.is-valid').removeClass('is-valid')
      $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
      });


      $('.doc_attachment_input').removeAttr('required')
      var format = 'YYYY/MM/DD HH:mm';
      $('.datetimepicker').each(function(i, e){
        var picker = $(e);
        var date = moment(picker.val(), format).toDate();
        picker.datetimepicker({format: format, date: null});
        picker.datetimepicker('date', date);
      })

      var datas = {"新竹縣北區竹光路竹光國中（往北）":[["台68線(東區)","D0002"],["八德路","A0001"],["三民路","A0010"],["千甲路","A0011"],["大同路","A0012"],["大成街","A0013"],["大學路","A0014"],["公道五路一段","A0021"],["中央路","A0022"],["中正路","A0023"],["中南街","A0024"],["中華路一段","A0025"],["中華路二段","A0026"],["仁愛街","A0027"],["仁義街","A0028"],["介壽路","A0029"],["公園路","A0030"],["公道五路二段","A0031"],["公道五路三段","A0032"],["太原路","A0033"],["文化街","A0034"],["文昌街","A0035"],["水利路","A0037"],["水源街","A0038"],["世界街","A0039"],["世傑路","A0040"],["北大路","A0041"],["占梅路","A0042"],["四維路","A0043"],["平和街","A0044"],["民主路","A0045"],["民生路","A0046"],["民有一街","A0047"],["民有二街","A0048"],["民有街","A0049"],["民享一街","A0050"],["民享街","A0051"],["民治街","A0052"],["民族路","A0053"],["民權路","A0054"],["田美一街","A0055"],["田美二街","A0056"],["田美三街","A0057"],["立鵬路","A0058"],["仲生路","A0059"],["仰德路","A0060"],["光明新村","A0061"],["光復路一段","A0062"],["光復路二段","A0063"],["光復路二段清大西院","A0064"],["光復路二段清大東院","A0065"],["光華東街","A0066"],["光華南街","A0067"],["光華街","A0068"],["安和街","A0069"],["安康街","A0070"],["安富街","A0071"],["竹蓮街","A0077"],["自由路","A0078"],["西大路","A0079"],["西門街","A0080"],["志平路","A0081"],["育民街","A0082"],["府後街","A0083"],["忠孝路","A0084"],["明湖路","A0085"],["東大路一段","A0086"],["東大路二段","A0087"],["東山街","A0088"],["東光路","A0089"],["東明街","A0090"],["東門市場","A0091"],["東門街","A0092"],["東前街","A0093"],["東南街","A0094"],["東美路","A0095"],["東勝路","A0096"],["東進路","A0097"],["東勢街","A0098"],["東園街","A0099"],["林森路","A0100"],["武昌街","A0101"],["花園街","A0102"],["花園新城街","A0103"],["金山一街","A0104"],["金山二街","A0105"],["金山三街","A0106"],["金山五街","A0107"],["金山六街","A0108"],["金山七街","A0109"],["金山八街","A0110"],["金山九街","A0111"],["金山十街","A0112"],["金山十一街","A0113"],["金山十二街","A0114"],["金山十三街","A0115"],["金山十五街","A0116"],["金山十六街","A0117"],["金山十七街","A0118"],["金山十八街","A0119"],["金山十九街","A0120"],["金山二十街","A0121"],["金山二十一街","A0122"],["金山二十二街","A0123"],["金山二十三街","A0124"],["金山二十五街","A0125"],["金山二十六街","A0126"],["金山二十七街","A0127"],["金山二十八街","A0128"],["金山北一街","A0129"],["金山北二街","A0130"],["金山北三街","A0131"],["金山東一街","A0132"],["金山東二街","A0133"],["金山東三街","A0134"],["金山東街","A0135"],["金山街","A0136"],["金城一路","A0137"],["金城二路","A0138"],["長春街","A0139"],["信義街","A0140"],["南大路","A0141"],["南外街","A0142"],["南門街","A0143"],["南城街","A0144"],["建中一路","A0145"],["建中路","A0146"],["建功一路","A0147"],["建功二路","A0148"],["建功路","A0149"],["建美路","A0150"],["建華街","A0151"],["建新路","A0152"],["柏川一路","A0153"],["柏川二路","A0154"],["柏川三路","A0155"],["科學園路","A0166"],["風空街","A0167"],["食品路","A0168"],["原興路","A0169"],["埔頂一路","A0170"],["埔頂二路","A0171"],["埔頂三路","A0172"],["埔頂路","A0173"],["振興一街","A0176"],["振興路","A0177"],["柴橋路","A0178"],["草湖街","A0179"],["高峰路","A0180"],["高翠路","A0181"],["國華街","A0182"],["培英街","A0183"],["崇和路","A0184"],["勝利路","A0186"],["博愛街","A0187"],["復興路","A0188"],["惠民街","A0189"],["湖濱一路","A0190"],["湖濱二路","A0191"],["湖濱三路","A0192"],["園後街","A0193"],["慈祥路","A0197"],["慈雲路","A0198"],["慈濟路","A0199"],["愛民街","A0200"],["新光路","A0201"],["新香街","A0203"],["新莊街","A0204"],["新源街","A0205"],["溪州路","A0206"],["溪埔路","A0207"],["瑞麟路","A0208"],["經國路一段","A0209"],["裕民街","A0210"],["福德街","A0211"],["綠水路","A0212"],["德成街","A0213"],["德高街","A0214"],["學府路","A0215"],["澤藩路","A0216"],["興中街","A0219"],["興竹街","A0220"],["興達街","A0222"],["興學街","A0223"],["錦華街","A0224"],["龍山西路","A0225"],["龍山東一街","A0226"],["龍山東路","A0227"],["藝術路","A0228"],["關東路","A0229"],["關新一街","A0230"],["關新二街","A0231"],["關新北路","A0232"],["關新西街","A0233"],["關新東路","A0234"],["關新路","A0235"],["寶山路","A0236"],["鐵道路一段","A0237"],["鐵道路二段","A0238"],["體育街","A0239"],["赤土崎一街","A0240"],["赤土崎二街","A0242"],["金山南街","A0243"],["東新路","A0244"]],"新竹縣北區竹光路352號（往南）":[["台68線(北區)","D0001"],["大同路","B0001"],["中山路","B0002"],["中央路","B0003"],["中正路","B0004"],["中光路","B0005"],["中和路","B0006"],["中清一路","B0007"],["中清路","B0008"],["中清路一段","B0009"],["中華路二段","B0010"],["中華路三段","B0011"],["中福路","B0012"],["中興路","B0013"],["仁化街","B0014"],["仁和路","B0015"],["仁德街","B0016"],["公道五路四段","B0017"],["公道五路五段","B0018"],["天府路一段","B0019"],["天府路二段","B0020"],["少年街","B0021"],["文雅街","B0022"],["水田街","B0023"],["世界街","B0024"],["北大路","B0025"],["北門街","B0026"],["北新街","B0027"],["古賢","B0028"],["四維路","B0029"],["平和街","B0030"],["民富街","B0031"],["田美三街","B0032"],["石坊街","B0033"],["光華一街","B0034"],["光華二街","B0035"],["光華北街","B0036"],["光華東一街","B0037"],["光華東街","B0038"],["光華南街","B0039"],["光華街","B0040"],["吉羊路","B0041"],["成功路","B0042"],["成德一街","B0043"],["成德二街","B0044"],["成德路","B0045"],["江山街","B0046"],["竹文街","B0047"],["竹光路","B0048"],["西大路","B0049"],["西安街","B0050"],["西門街","B0051"],["西濱路一段","B0052"],["孝賢路","B0053"],["育英路","B0054"],["和平路","B0055"],["和福街","B0056"],["尚濱路","B0057"],["府後街","B0058"],["延平路一段","B0059"],["延平路三段","B0060"],["延濱路","B0061"],["忠信路","B0062"],["東大路二段","B0063"],["東大路三段","B0064"],["東大路四段","B0065"],["東門街","B0066"],["東濱街","B0067"],["林森路","B0068"],["武勇街","B0069"],["武陵西二路","B0070"],["武陵西三路","B0071"],["武陵西四路","B0072"],["武陵路","B0073"],["河北街","B0074"],["金竹路","B0075"],["金雅一街","B0076"],["金雅二街","B0077"],["金雅三街","B0078"],["金雅五街","B0079"],["金雅西街","B0080"],["金雅路","B0081"],["金農路","B0082"],["長安街","B0083"],["長和街","B0084"],["南大路","B0085"],["南勢二街","B0086"],["南勢六街","B0087"],["南勢八街","B0088"],["南勢十街","B0089"],["南勢十二街","B0090"],["南勢街","B0091"],["南寮街","B0092"],["城北街","B0093"],["建台街","B0094"],["建國街","B0095"],["建興街","B0096"],["英明街","B0097"],["凌雲街","B0098"],["海濱路","B0099"],["草湖街","B0100"],["國光街","B0101"],["國華街","B0102"],["崧嶺路","B0103"],["勝利路","B0104"],["富美路","B0105"],["港北一街","B0106"],["港北二街","B0107"],["港北三街","B0108"],["港北六街","B0109"],["集和街","B0110"],["集福街","B0111"],["集賢街","B0112"],["湳中街","B0113"],["湳雅街","B0114"],["愛文街","B0115"],["愛國路","B0116"],["新民街","B0117"],["新香街","B0118"],["新港三路","B0119"],["新港北路","B0120"],["新港南路","B0121"],["經國路一段","B0122"],["經國路二段","B0123"],["聖軍路","B0124"],["嘉濱路","B0125"],["境福街","B0126"],["榮濱南路","B0127"],["榮濱路","B0128"],["演藝路","B0129"],["廣州街","B0130"],["德成街","B0131"],["磐石路","B0132"],["衛民街","B0133"],["興南街","B0134"],["興濱路","B0135"],["聯興路","B0136"],["警光路","B0137"],["鐵道路二段","B0138"],["鐵道路三段","B0139"],["士林東路","B0140"],["士林西路","B0141"],["士林北路","B0142"],["士林一街","B0143"],["士林二街","B0144"],["新港四路","B0145"]],"新竹縣東區公道五路三段700號（往西）":[["大湖路","C0001"],["大庄路","C0002"],["中山路","C0003"],["中華路四段","C0004"],["中華路五段","C0005"],["中華路六段","C0006"],["五福路一段","C0007"],["五福路二段","C0008"],["元培街","C0009"],["內湖路","C0010"],["牛埔北路","C0011"],["牛埔東路","C0012"],["牛埔南路","C0013"],["牛埔路","C0014"],["古車路","C0015"],["玄奘路","C0016"],["竹香北路","C0017"],["竹香南路","C0018"],["至善街","C0019"],["西濱路二段","C0020"],["西濱路三段","C0021"],["西濱路四段","C0022"],["西濱路六段","C0023"],["吳厝街","C0024"],["育德街","C0025"],["那魯灣街","C0026"],["延平路二段","C0027"],["明德路","C0028"],["東香東街","C0029"],["東香南街","C0030"],["東香路一段","C0031"],["東香路二段","C0032"],["東華路","C0033"],["芝柏一街","C0034"],["芝柏二街","C0035"],["芝柏三街","C0036"],["芝柏五街","C0037"],["花園新城一街","C0038"],["花園新城二街","C0039"],["虎林街","C0040"],["長福街","C0041"],["長興街","C0042"],["南港街","C0043"],["南湖路","C0044"],["南隘路一段","C0045"],["南隘路二段","C0046"],["柯湳一街","C0047"],["柯湳二街","C0048"],["美山路","C0049"],["美之城","C0050"],["美之城一街","C0051"],["美森街","C0052"],["茄苳北街","C0053"],["茄苳東街","C0054"],["茄苳路","C0055"],["香北一路","C0056"],["香北路","C0057"],["香村路","C0058"],["香檳一街","C0059"],["香檳二街","C0060"],["香檳三街","C0061"],["香檳五街","C0062"],["香檳東街","C0063"],["香檳南街","C0064"],["埔前路","C0065"],["宮口街","C0066"],["柴橋路","C0067"],["浸水北街","C0068"],["浸水南街","C0069"],["浸水街","C0070"],["浸樹街","C0071"],["海山港十街","C0072"],["海山港路","C0073"],["海埔一街","C0074"],["海埔二街","C0075"],["海埔三街","C0076"],["海埔五街","C0077"],["海埔路","C0078"],["草漯街","C0079"],["國中街","C0080"],["崧嶺路","C0081"],["彩虹路","C0082"],["祥園街","C0083"],["莊敬街","C0084"],["頂美街","C0085"],["頂埔路","C0086"],["富群街","C0087"],["富禮街","C0088"],["景觀大道","C0089"],["港南一街","C0090"],["港南二街","C0091"],["港南三街","C0092"],["港南五街","C0093"],["華北路","C0094"],["華江街","C0095"],["閑谷一街","C0096"],["閑谷二街","C0097"],["閑谷街","C0098"],["新香街","C0099"],["瑞光街","C0100"],["經國路三段","C0101"],["遊樂街","C0102"],["福樹街","C0103"],["墩豐路","C0104"],["樹下街","C0105"],["麗山街","C0106"]],"新竹縣東區光復路二段90號（往西）":"","新竹縣東區光復路二段151號（往東）":"","新竹市東區經國路一段68巷（往南）":"","新竹市東區高峰路168號（往市區）":"","新竹市香山區牛埔東路359號(往北)":"","新竹市香山區竹香北路竹香吊橋（往西）":"","新竹市香山區竹香北路往東（延平路一段357巷）":"","新竹市香山區竹香南路往西（樹下街130巷口）":"","新竹市香山區竹香南路消防局香山分隊旁（往東）":"","新竹市香山區東香路二段59巷（往景觀大道）":"","新竹市香山區台1線79公里（往南）":"","新竹市香山區台1線88.2公里（往南）":"","新竹市香山區景觀大道4.7公里（往交流道）":"","新竹市香山區中華路六段97巷口 ( 往北 ) ":"","新竹市香山區中華路六段97巷口 ( 往南 ) ":""};
      var attrs = jQuery.parseJSON('{"id":null,"addr1":null,"addr2":null,"addr3":null,"addr4":null,"addr5":null,"area_code":null,"car_type_code":null,"first_car_number":null,"last_car_number":null,"sn":null,"area_district":null,"addr_detail":null,"violated_at":null,"state":"ready","finish_report_user_id":null,"review_report_user_id":null,"create_report_user_id":null,"reviewed_date":null,"expose_reason_code":null,"full_car_number":null,"finished_date":null,"comment":null,"created_at":null,"updated_at":null}')
      var select = $('select.district');
      select.change(function () {
        var streets = datas[$(this).val()];

	        $('select[name="doc[area_code]"]').empty();
	        // $('<option>' + '區' + '</option>').appendTo('select[name="doc[area_code]"]');
	         $('<option value>' + '請選擇' + '</option>').appendTo('select[name="doc[area_code]"]');
	                 $.each(streets, function(index,item) {
	                           $('<option value=' + item[1] + '>' + item[0] + '</option>').appendTo('select[name="doc[area_code]"]');
	                                   });
	                                         });
	//                                               // $('select.district option').remove();
	//                                                     // $('<option>' + '路名' + '</option>').appendTo('select[name="doc[area_code]"]');
	//                                                           // $('<option>' + '區' + '</option>').appendTo('select[name="doc[area_code]"]');
	                                                                 $.each(datas, function (key, value) {
	                                                                         $('<option value="' + key + '">' + key + '</option>').appendTo(select);
	                                                                               });
	                                                                                     if(!isEmpty(attrs['area_district'])){
	                                                                                             select.val(attrs['area_district']).change();
	                                                                                                   }
	

	                                                                                                         var pro = $('select.district').val();
	
	                                                                                                               var streets = datas[pro];
	
	                                                                                                                     // $('select[name="doc[area_code]"] option').remove();
	                                                                                                                           $.each(streets, function(index, item) {
	                                                                                                                                   $('<option value=' + item[1] + '>' + item[0] + '</option>').appendTo('select[name="doc[area_code]"]');
	                                                                                                                                         });
	
                                                                                                                                               if(!isEmpty(attrs['area_code'])){
	                                                                                                                                                       $('select[name="doc[area_code]"]').val(attrs['area_code']).change();
	                                                                                                                                                             }
	                                                                                                                                                                 })
	
	                                                                                                                                                                     function isEmpty(value) {
	                                                                                                                                                                           return typeof value == 'string' && !value.trim() || typeof value == 'undefined' || value === null;
	                                                                                                                                                                               }
	
	                                                                                                                                                                                 </script>
  </body>



</html>

