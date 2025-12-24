<?php include "function.php"; ?>
<?php
$i=0;
$resultn = $pdo->query("select * from `cases_img` where `cid`='".$_GET["cid"]."' and `flag`=0 order by ts asc limit 4");
while($rown = $resultn->fetch(PDO::FETCH_ASSOC)) {
	$i++;
}
if( $i != 4 ) {
	echo '<script>alert("單張相片必須有四張才可以合成! 大於四張或小於四張都無法進行合成! 請撥放影片擷取圖片!!");</script>';
	echo '<script>document.location.href="cases_edit.php?id='.$_GET["cid"].'";</script>';
	exit;
}
$result = $pdo->query("select * from `cases` where `id`='".$_GET["cid"]."'");
$row = $result->fetch(PDO::FETCH_ASSOC);
?>
<center><table><tr><td style="font-size:20px;">邦邦相片合成系統</td></tr></table>
<table border="1"><tr><td align="center" style="color:blue;font-size:20px">合成疊字定義</td></tr><tr><td>日期:<input type="text" name="jdate" value="<?=substr($row["created_at"],0,10);?>">  主機:<input type="text" name="jserver" value="<?=$row["cam_ip"];?>"> 限速:<input type="text" name="maxspeed" value="<?=$row["car_speed_max"];?>"> 車號:<input type="text" name="jnum" value="<?=$row["full_car_number"];?>"> 違規類型:<input type="text" name="jreason" value="<?=$row["cam_reason"];?>"></td></tr><tr><td>時間:<input type="text" name="jtime" value="<?=substr($row["created_at"],11,8);?>"> 案號:<input type="text" name="jsn" value="<?=$row["sn"];?>"> 地點:<input type="text" name="jplace" value="<?=$row["cam_place"];?>" size="88"></td></tr></table>

<table border="1"><tr>
<?php
$i=0;
$resultn = $pdo->query("select * from `cases_img` where `cid`='".$_GET["cid"]."' order by ts asc limit 4");
while($rown = $resultn->fetch(PDO::FETCH_ASSOC)) {
	$i++;
		if( $i==3 ) 
			echo '</tr><tr>';
echo '<td><img src="/CarSystem/'.$rown["img"].'" width="538" height="350"></td>';
}
?>
</tr></table>
<table><tr><td align="center" style="color:blue;font-size:20px;"><a id="4to1" href="#" style="text-decoration: none;color:red;background:yellow;">&nbsp; 確認進行合成 &nbsp;</a></td></tr></table>

<script src="jquery-3.4.1.min.js" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
  $('#4to1').click(function() {
    if (window.confirm('確認是否合成？合成後無法復原!')) {
		document.location.href="/hsinchu/php/4to1.php?id=<?=$_GET["cid"];?>";		
    } else {
	}
  });
});
</script>
