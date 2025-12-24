<?php
include "function.php";
$nav="";
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


<div class="card mb-3">
  <div class="card-header">
    <i class="far fa-info-square"></i>
    修改密碼
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-sm-8">
        <form class="simple_form edit_report_user" id="edit_report_user_69" action="update_password.php" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="_method" value="put" /><input type="hidden" name="authenticity_token" value="NB3MW2zBjI//P2G0L5oog1mD8X7SV31Ev+UN7iSwn0I05K2nOfDzpSf4r4o9sD6qVMY1Bw7x/NudV/wjsnrfqw==" />
          <div class="bgc-white p-20 bd">
            <div class="mT-30">
              <div class="form-group row">
                <label class="col-sm-2 col-form-label col-form-label-md">目前密碼</label>
                <div class="col-sm-5">
                  <input label="false" wrapper="false" class="form-control form-control-sm" type="password" name="report_user[current_password]" id="report_user_current_password" />
                </div>
              </div>


              <div class="form-group row">
                <label class="col-sm-2 col-form-label col-form-label-md">新密碼</label>
                <div class="col-sm-5">
                  <input autocomplete="off" label="false" wrapper="false" class="form-control form-control-sm" type="password" name="report_user[password]" id="report_user_password" />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 col-form-label col-form-label-md">密碼確認</label>
                <div class="col-sm-5">
                  <input autocomplete="off" label="false" wrapper="false" class="form-control form-control-sm" type="password" name="report_user[password_confirmation]" id="report_user_password_confirmation" />
                </div>
              </div>

              <div class="form-actions">
                <input type="submit" name="commit" value="送出" class="btn btn-primary" data-disable-with="送出" />
              </div>
            </div>
          </div>

</form>      </div>
    </div>

  </div>
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

    <script src="/hsinchu/assets/admin-7e641842b7678866dba9f029b1984fc78978fbe3c300f84802bb98e82b1f6905.js"></script>

  </body>



</html>

