<?php
session_start();
include "function2.php";
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
<meta name="csrf-token" content="BrDsC0Qw5Ms4jcQPZOI4xE7tWa4mACO3P+lHoM58CElTBiIY1OxWbFaGuib0n/ZETjRupxXjUzvX4N43gBSUHQ==" />
  

  <link rel="stylesheet" media="all" href="assets/admin-92e995e469ea98c880e61710f498cb7c0dddcd185d591b92bc985fb93e14d29a.css" />

</head>

  <body class="bg-dark" style="background-color:#738AC8 !important">
    <div class="container">
      <div class="report_session card card-login mx-auto mt-5">
  <div class="card-header text-center" style="background-color:white!important;border-style: none;border-radius:150px 150px 150px 150px;">
    <h4 style="color:#738AC8 !important;font-weight: bold;">新竹市警察局<br>交通事故案件便民服務網</h4>
  </div>
  <div class="card-body">
    <form class="simple_form new_report_user" id="new_report_user" action="chk_login.php" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="authenticity_token" value="fOaOkWnGBKG6VzKZy/wnj8sj8hhFG8UoFViWZjua0KejBr8zXYc7UJsuxcsbeW4UE++syLM/VIy1TUMBuoS/OA==" />
      <div class="form-group">
        <div class="form-label-group">
          <input class="form-control string required form-control" autofocus="autofocus" required="required" aria-required="true" type="text" name="report_user[username]" id="report_user_username" <?php if( !empty($_COOKIE["login_acc"])) echo 'value="'.$_COOKIE["login_acc"].'"';?>/>
          <label>帳號名稱</label>
        </div>
      </div>
      <div class="form-group">
        <div class="form-label-group">
          <input class="form-control password required form-control" required="required" aria-required="true" type="password" name="report_user[password]" id="report_user_password" <?php if(!empty($_COOKIE["login_passwd"])) echo 'value="'.$_COOKIE["login_passwd"].'"';?>/><small class="form-text text-muted"><font color="red">密碼長度須8個字元以上、並含四種字元（英文大小寫、數字、特殊符號）中的3種</font></small>
          <label>密碼</label>
        </div>
      </div>
      <div class="form-group">
        <div class="checkbox">
          <label>
            <input value="1" name="admin_user[remember_me]" type="checkbox" data-parsley-multiple="checkbox-signup" class="peer" <?php if( !empty($_COOKIE["login_acc"])) echo 'checked';?>>
            記住我一週
          </label>
        </div>
      </div>
      <button class="btn btn-primary btn-block btn-login">登入</button>
</form>  </div>
</div>


<style type="text/css">
  span.hint{
    display: none;
  }
</style>



    </div>

    <script src="/hsinchu/assets/admin-7e641842b7678866dba9f029b1984fc78978fbe3c300f84802bb98e82b1f6905.js"></script>

  </body>



</html>
