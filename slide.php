<?php include "../includes/functions.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Scroll</title>
<script language="JavaScript" src="motionpack.js"></script>
</head>

<body>



<div style="position:relative;">

<?php /*
<div id="mydiv1" style="display:block; overflow:hidden; height:95px; border:1px solid black; width:308px; float:left;">
<img src="../images/100_dollar_bill.jpg">
</div>
<div id="mydiv2" style="display:none; overflow:hidden; height:95px; border:1px solid black; width:308px; float:left;">
<h3>This is a test2!<br>Can you see me2?</h3>
</div>
<div id="mydiv3" style="display:none; overflow:hidden; height:95px; border:1px solid black; width:308px; float:left;">
<h3>This is a test3!<br>Can you see me3?</h3>
</div>
*/
?>

<?php
$sql = "SELECT photo from users where enabled=1 and photo != ''";
$result = executequery($sql);
$counter = 0;
$num = 0;
$first=1;
while($record = go_fetch_array($result)){
  
  if($first){
    $display = "block";
	$first=0;
  }
  else {
    $display = "none";
  }
  
  if($num==0){
    $counter++;
    ?>
    <div id="mydiv<?=$counter?>" style="display:<?=$display?>; overflow:hidden; height:95px; border:1px solid black; width:308px; float:left;">
	<?php
	
  }
  $num++;
  ?>
  <img src="../uploaded_files/headshots/<?=$record['photo']?>">
  <?php
  if($num==2) {
    ?>
	</div>
	<?php
	$num=0;
  }
}
if($num != 0) echo "</div>";
?>

<div style="float:left; vertical-align:middle; height:95px;">
<a href="javascript:;" onmousedown="slideThrough('mydiv', '<?=$counter?>');">-></a>
</div>
</div>
<div style="clear:both;"></div>


<div id="debug"></div>
</body>
</html>
