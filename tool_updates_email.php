<?php include "includes/functions.php"; 


$submit1 = $_POST['submit1'];
if($submit1 != "Send Email") exit;

ob_start();
?>
<body style="margin:10px 10px 10px 10px;">
<a href="http://www.fcscontrol.com"><img src="<?=$SITE_URL?>images/fcs_logo.jpg" border="0"></a><br>
<div class="main">

  
  <div>
  <span style="font-size:18px; font-weight:bold;">Support</span>
  <br><br>

  
  <span style="font-size:16px; font-weight:bold;">New Features Released This Week</span> (click on item for more detailed description)<br>
    <div style="width:90%; position:relative;">
	<div style="width:50%; float:left;">
	<u>Core Changes</u>
	<br>
	<?php
	$shown = array();
	$sql = "SELECT a.event_id, a.title, date_format(a.complete_date, \"%m/%d/%Y\") as complete_pretty 
	from supercali_events a, supercali_categories b
	where a.category_id = b.category_id
	and b.calendar_type='Programming'
	and a.publish=1
	and a.publish_type='core'
	and a.complete_date >= date_sub(now(), interval 7 day)";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $shown[] = $record['event_id'];
	  $title = stripslashes($record['title']);
	  if(strlen($title) > 35) $title = substr($title, 0, 35) . "...";
	  ?>
	  <?=$record['complete_pretty']?> <a href="<?=$SITE_URL?>public_updates.php" style="text-decoration:none;"><?=$title?></a><br>
	  <?php
	}
	?>
	</div>
	<div style="width:50%; float:left;">
	<u><?=$MAIN_CO_NAME?> Connect Changes</u>
	<br>
	<?php
	$sql = "SELECT a.event_id, a.title, date_format(a.complete_date, \"%m/%d/%Y\") as complete_pretty 
	from supercali_events a, supercali_categories b
	where a.category_id = b.category_id
	and b.calendar_type='Programming'
	and a.publish=1
	and a.publish_type='fcs'
	and a.complete_date >= date_sub(now(), interval 7 day)";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $shown[] = $record['event_id'];
	  $title = stripslashes($record['title']);
	  if(strlen($title) > 35) $title = substr($title, 0, 35) . "...";
	  ?>
	  <?=$record['complete_pretty']?> <a href="<?=$SITE_URL?>public_updates.php" style="text-decoration:none;"><?=$title?></a><br>
	  <?php
	}
	?>
	</div>
	</div>
	<div style="clear:both;"></div>
  <br><br>
  
  

  </div>


You are receiving this email because you are an <?=$MAIN_CO_NAME?> Subscriber. If you do not wish to receive updates of newly released features, please click to 
<a href="<?=$CORE_URL?>unsubscribe_main.php">unsubscribe</a>.
<br><br>
Facility Control Systems<br>
129 E Calhoun St<br>
Woodstock, IL 60098<br>
phone: 855-NEED-FCS
</div>

</body>
<?php
$message = ob_get_contents();
ob_end_clean();

$lastweek  = mktime(0, 0, 0, date("m")  , date("d")-7, date("Y"));
$lastweek_pretty = date("m/d/Y", $lastweek);
$subject = "$MAIN_CO_NAME New Features and Updates for $lastweek_pretty through " . date("m/d/Y");
$headers = "Content-type: text/html; charset=iso-8859-1\n";
$headers .= "From: $MAIN_CO_NAME Support <support@fcscontrol.com>\n";
$headers .= "Return-Path: support@fcscontrol.com\n";  // necessary for some emails such as aol

$include_fcs = $_POST['include_fcs'];
$fcs_filter = " a.master_id != 1 ";
if($include_fcs ==1) $fcs_filter = " 1=1 ";

$sql = "SELECT a.email from users a, master_list b where a.master_id=b.master_id and b.demo=0 and b.active=1 and a.enabled=1 
and $fcs_filter group by a.email";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $email = stripslashes($record['email']);
  $sql = "SELECT id from unsubscribe_admin where email=\"$email\"";
  $test = getsingleresult($sql);
  if($test != "") continue;
  $email_list .= $email . ",";
}

//$message .= "<br>" . $email_list;
$headers .= "Bcc: $email_list";

email_q("support@fcscontrol.com", $subject, $message, $headers);

$_SESSION['sess_msg'] = "The email has been sent";
meta_redirect("tool_updates.php");
?>
