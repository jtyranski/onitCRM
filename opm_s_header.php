<?php
ob_start("ob_gzhandler");
session_start();
include "includes/functions.php";
$xid = go_escape_string($_GET['xid']);
$sql = "SELECT opm_id from opm where code=\"$xid\"";
$opm_id = getsingleresult($sql);
if($opm_id == ""){
  echo "There was an error retrieving the OPM report.  Please make sure you have the correct link.";
  exit;
}
$sql = "SELECT master_id from opm where opm_id='$opm_id'";
$master_id = getsingleresult($sql);

$sql = "SELECT master_name, logo from master_list where master_id='$master_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$master_name = stripslashes($record['master_name']);
$master_logo = stripslashes($record['logo']);
?>
<html>
<head>
<title>Online Project Monitoring</title>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
</head>
<script>
function hilite(x){
  alert("test");
  document.getElementById('menu_' + x).style.background-color="#ececec";
}
function hiliteoff(x){
  document.getElementById('menu_' + x).style.background-color="#ffffff";
}
</script>
<body bgColor='#FFFFFF'>

<div class="main">

<div>
<?php
if($master_logo != ""){ ?>
  <img src="uploaded_files/master_logos/<?=$master_logo?>">
<?php } else { ?>
  <font size="+3"><?=$master_name?></font>
<?php } ?>
</div>
<br>
<?php
$menu = array("Progress Report", "View All Photos");
$urls = array("opm_s_project.php", "opm_s_photos.php");
?>
<div style="position:relative; width:100%;">
<div style="float:left; width: 250px; background-color:#ececec; min-height:800px;">
<?php for($x=0;$x<sizeof($menu);$x++){ ?>
  <div style="width:100%; height:60px; padding-top:40px; text-align:center; background-color:#ececec" id="menu_<?=$x?>" onMouseOver="this.style.backgroundColor='#999999';this.style.cursor='pointer'" onMouseOut="this.style.backgroundColor='#ececec'" onClick="document.location.href='<?=$urls[$x]?>?xid=<?=$xid?>'">
  <span style="color:black; font-size:18px;<?php if($current_file_name==$urls[$x]) echo " font-weight:bold;";?>">
  <?=$menu[$x]?>
  </span>
  </div>
  <?php
}
?>
</div>

<div style="float:left; min-width:800px; max-width:1000px; padding-left:10px;" class="main">