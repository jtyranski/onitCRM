<?php 
require_once "includes/functions.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" href="ro_favicon.ico" type="image/x-icon">
<title><?=$MAIN_CO_NAME?></title>
<link href="styles/css.css" type="text/css" rel="stylesheet">
<script src="motionpack.js"></script>
</head>


<?php
$TIMEZONE = $SESSION_TIMEZONE;
if($TIMEZONE=="") $TIMEZONE = 0;
$time = mktime(date("H") + $TIMEZONE, date("i"), date("s"), date("m")  , date("d"), date("Y"));
/*
if($SESSION_USE_GROUPS){
  echo "<!--";
  print_r($SESSION_GROUP_PROSPECT_ID);
  echo "-->";
}
*/
?>

<body class="main">
<div style="background-color:#FFFFFF; width:100%; position:relative; height:56px;" id="HEADER_DIV">
<div style="float:left; width:55px; margin-top:6px; margin-left:5px;">
<?php
$homepage_link = "homepage.php";
if($SESSION_MASTER_ID==1) $homepage_link = "homepage_total.php";
?>
<?php ImageLink($homepage_link, "home", 1, 0); ?>
</div>
<div style="float:left; margin-top:8px;">
<span style="font-size:16px;"><strong><?=$fullname_welcome?></strong></span>
[<a href="logout.php">logout</a>]
<br>
<?=date("l. F j, Y g:i A", $time)?>
</div>

<div style="float:right; margin-top:2px;">
<a href="contact.php">
<?php
if($MASTER_LOGO != ""){
	      $max_width = 400;
          $max_height = 50;
          list($width, $height) = getimagesize($UPLOAD . "master_logos/" . $MASTER_LOGO);
          $ratioh = $max_height/$height;
          $ratiow = $max_width/$width;
          $ratio = min($ratioh, $ratiow);
          if($width > $max_width || $height > $max_height){
            $width = intval($ratio*$width);
            $height = intval($ratio*$height); 
          }
		  echo "<img src='" . $UPLOAD . "master_logos/" . $MASTER_LOGO . "' width='$width' height='$height' border='0'>";
		  
}
else {
  echo $MASTER_NAME;
}
?>
</a>
</div>


<div style="float:right; padding-right:10px; margin-top:8px;">
<iframe frameborder="0" src="check_inspection_approval.php" width="50" height="40" style="border:none; overflow:hidden;"></iframe>
</div>

<div style="float:right; padding-right:10px; margin-top:8px;">
<iframe frameborder="0" src="check_dispatch_approval.php" width="50" height="40" style="border:none; overflow:hidden;"></iframe>
</div>

<div style="float:right; padding-right:10px; margin-top:8px;">
<iframe frameborder="0" src="check_new_bids.php" width="50" height="40" style="border:none; overflow:hidden;"></iframe>
</div>

<div style="float:right; padding-right:10px; margin-top:8px;">
<iframe frameborder="0" src="check_email_q.php" width="50" height="40" style="border:none; overflow:hidden;"></iframe>
</div>

</div>
<div style="clear:both;"></div>


<div style="height:800px; width:100%; background-color:#000000; position:relative;" class="gradient">
<div style="height:2px;">&nbsp;</div>
<?php //if($current_file_name=="index.php") include "includes/admin_message.php"; ?>
<?php include "includes/main_nav.php"; ?>
<div class="whiteround" id="maindebug" style="display:none;">
</div>
