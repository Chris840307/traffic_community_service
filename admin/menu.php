<nav class="navbar navbar-expand-md navbar-dark bg-dark">
<a class="navbar-brand" href="cases.php?state=nready&n">
<img src="/hsinchu/assets/logo-bd845e53b6ff49bb0d6cd7f768061e3efb817a06a4c46f626dbfcc2dc3589bcb.gif" width="40" height="40" />新竹市警察局交通事故案件便民服務網
  </a>
  <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
  </button>
  <div class="navbar-collapse collapse" id="navbarsExample04" style="margin-left: 30px;">
    <ul class="navbar-nav mr-auto" style="display: flex;flex-wrap: wrap;list-style-type:none;margin:5px;">
        <?php if( $row_user["flag5"] == "1" ) { ?>
        <li class="nav-item cases_approving <?php if( $_GET["state"] == "nready" ) echo "active";?>">
          <a class="nav-link" href="cases.php?state=nready&n">案件指派</a>
        </li>
        <?php } ?>
	<?php if( $row_user["flag1"] == "1" ) { ?>
        <li class="nav-item cases_approving <?php if( $_GET["state"] == "ready" ) echo "active";?>">
          <a class="nav-link" href="cases.php?state=ready&n">案件處理</a>
        </li>
	<?php } ?>
        <?php if( $row_user["flag2"] == "1" ) { ?>
        <li class="nav-item cases_reviewing <?php if( $_GET["state"] == "reviewing" && $nav != "綜合查詢" ) echo "active";?>">
          <a class="nav-link" href="cases.php?state=reviewing&n">案件審核</a>
        </li>
        <?php } ?>
<?php if( $row_user["id"] == "1" ) { ?>
        <!--<li class="nav-item cases_complete <?php if( $nav == "flag10" ) echo "active";?>">
          <a class="nav-link" href="cases.php?flag=14day&n">未結案件管考</a>
        </li>-->
<?php } ?>
        <?php if( $row_user["flag4"] == "1" ) { ?>
	<li class="nav-item cases_all <?php if( $nav == "綜合查詢" ) echo "active";?>">
          <a class="nav-link" href="casesall.php?n">綜合查詢</a>
        </li>
        <li class="nav-item cases_all <?php if( $nav == "日誌紀錄" ) echo "active";?>">
          <a class="nav-link" href="export_report2.php">日誌紀錄</a>
        </li>
	<?php } ?>
<?php if( $row_user["id"] == "1" || $row_user["flag5"] == "1" || $row_user["acc"] == "415654" ) { ?>
<li class="nav-item dropdown ">
          <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">資料維護</a>
          <div class="dropdown-menu" aria-labelledby="dropdown04">
	  <!--<a class="dropdown-item <?php if( $nav == "違規地點") { echo "active";}?>" href="areas.php">違規地點</a>
	    <a class="dropdown-item <?php if( $nav == "違規事實") { echo "active";}?>" href="illegalities.php">違規事實</a>
	    <a class="dropdown-item <?php if( $nav == "違規車種") { echo "active";}?>" href="car_types.php">違規車種</a>
	    <a class="dropdown-item <?php if( $nav == "舉發條款") { echo "active";}?>" href="expose_reasons.php">舉發條款</a>-->
	    <a class="dropdown-item <?php if( $nav == "退件原因") { echo "active";}?>" href="unexpose_reasons.php">退件原因</a>
	    <a class="dropdown-item <?php if( $nav == "單位") { echo "active";}?>" href="departments.php">單位</a>
	    <a class="dropdown-item <?php if( $nav == "admin") { echo "active";}?>" href="admin_users.php">管理員</a>
		<a class="dropdown-item <?php if( $nav == "查詢事由") { echo "active";}?>" href="search_reason.php">查詢事由</a>
	<?php if( $row_user["flag10"] == "1" && $row_user["id"] != "1" ) { ?>
	<a class="dropdown-item <?php if( $nav == "admin2") { echo "active";}?>" href="admin2_users.php">分局管理員</a>
	<?php } ?>
          </div>
        </li>
<?php } ?>
    </ul>
    <ul class="navbar-nav ml-auto ml-md-0">
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-user-circle fa-fw"></i>
<?=$row_user["name"];?> 
(<?=$row_user["department"].' '.$row_user["unit"];?>)
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="#">
          </a>
<!--<a class="dropdown-item" href="reason_list.php">法條管理</a>-->
<div class="dropdown-divider"></div>
          <a class="dropdown-item" href="edit_password.php">修改密碼</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="logout.php">登出</a>
        </div>
      </li>
    </ul>
  </div>
</nav>
