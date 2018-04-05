<script language="JavaScript">
<!--
function refreshParent() {
  window.opener.location.href = window.opener.location.href;

  if (window.opener.progressWindow)
		
 {
    window.opener.progressWindow.close()
  }
  window.close();
}
//-->
</script>
<?php
include "../includes/functions.php";

$id = go_escape_string($_POST['id']);
$event_id = $id;
$act_id = go_escape_string($_POST['act_id']);
$regarding = go_escape_string($_POST['regarding']);
$datepretty = go_escape_string($_POST['datepretty']);
$hourpretty = go_escape_string($_POST['hourpretty']);
$minutepretty = go_escape_string($_POST['minutepretty']);
$ampm = go_escape_string($_POST['ampm']);
$user_id = go_escape_string($_POST['user_id']);

$old_repeat_type = go_escape_string($_POST['old_repeat_type']);
$repeat_type = go_escape_string($_POST['repeat_type']);
if($repeat_type=="") $repeat_type = "Never";
$spanpretty = go_escape_string($_POST['spanpretty']);
if($spanpretty != ""){
  $date_parts = explode("/", $spanpretty);
  $span_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
}
else {
  $span_date = "";
}

$submit1 = $_POST['submit1'];

if($submit1 != ""){
  if($ampm == "PM") $hourpretty += 12;
  $timepretty = $hourpretty . ":" . $minutepretty . ":00";
  $date_parts = explode("/", $datepretty);
  $fixed_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
  if($fixed_date < date("Y-m-d")){
    echo "Error: You can't add activities with dates before " . date("m/d/Y") . "<br>";
	echo "<a href='javascript:history.go(-1)'>Back</a>";
	exit;
  }
  $raw_fixed_date = $fixed_date;
  $fixed_date .= " " . $timepretty;
  
  if($id == "new"){
    $sql = "INSERT into activities(user_id, event) values('$user_id', 'Calendar - Other')";
	executeupdate($sql);
	$sql = "SELECT act_id from activities where user_id='$user_id' order by act_id desc limit 1";
	$act_id = getsingleresult($sql);
	$sql = "INSERT into supercali_events(title, description, category_id, user_id, what, ro_user_id, act_id) values(
	'Calendar - Other - $act_id', \"$regarding\", 26, 2, 'O', '$user_id', '$act_id')";
	executeupdate($sql);
	$sql = "SELECT event_id from supercali_events where ro_user_id='$user_id' and act_id='$act_id' order by event_id desc limit 1";
	$id = getsingleresult($sql);
	$sql = "INSERT into supercali_dates (event_id) values('$id')";
	executeupdate($sql);
	$event_id = $id;
  }
  
  $sql = "UPDATE supercali_dates set date='$fixed_date', end_date='$fixed_date' where event_id='$id'";
  executeupdate($sql);
  
  $sql = "UPDATE supercali_events set description=\"$regarding\" where event_id='$event_id'";
  executeupdate($sql);
  
  $sql = "UPDATE activities set date='$fixed_date', regarding=\"$regarding\", user_id='$user_id', 
  repeat_type=\"$repeat_type\", span_date='$span_date' where act_id='$act_id'";
  executeupdate($sql);
  
  
  $sql = "DELETE from activities_repeat where act_id='$act_id'";
  executeupdate($sql);
  
  if(($repeat_type == "Never" || $repeat_type=="Span") && $old_repeat_type != "Never"){
    $sql = "DELETE from supercali_dates where event_id='$event_id'";
	executeupdate($sql);
  }
  
	

  
  if($repeat_type != "Never" && $repeat_type != "Span"){
	$sql = "DELETE from supercali_dates where event_id='$event_id'";
	executeupdate($sql);
	
    $sql = "INSERT into activities_repeat(act_id, event_id, start_date, repeat_type) values ('$act_id', '$event_id', '$raw_fixed_date', 
	'$repeat_type')";
	executeupdate($sql);
	
	
  }
  
  if($repeat_type == "Span"){
    $span = GetDays($raw_fixed_date, $span_date, "+1 day");
	for($x=0;$x<sizeof($span);$x++){
	  $sql = "SELECT event_id from supercali_dates where event_id = '$event_id' and date like '" . $span[$x] . "%'";
	  $test = getsingleresult($sql);
	  if($test==""){
	    $sql = "INSERT into supercali_dates(event_id, date, end_date) values('$event_id', '" . $span[$x] . "', '" . $span[$x] . "')";
		executeupdate($sql);
	  }
	}
  }
  
  if($repeat_type=="Daily" || $repeat_type=="Weekly" || $repeat_type=="Monthly" || $repeat_type=="Yearly") include "../cron_calendar_repeat.php";
  
}
?>

<script>
refreshParent();
</script>

