<?php
require_once "includes/functions.php";

$future = date("Y-m-d", strtotime('+100 days'));

$cycle["Daily"] = "+1 day";
$cycle["Weekly"] = "+1 week";
$cycle["Monthly"] = "+1 month";
$cycle["Yearly"] = "+1 year";

$sql = "SELECT date_format(start_date, \"%Y-%m-%d\") as datepretty, event_id, repeat_type from activities_repeat";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $datepretty = stripslashes($record['datepretty']);
  $x_event_id = stripslashes($record['event_id']);
  $x_repeat_type = stripslashes($record['repeat_type']);
  
  $span = GetDays($datepretty, $future, $cycle[$x_repeat_type]);
  for($x=0;$x<sizeof($span);$x++){
	  $sql = "SELECT event_id from supercali_dates where event_id = '$x_event_id' and date like '" . $span[$x] . "%'";
	  $test = getsingleresult($sql);
	  if($test==""){
	    $sql = "INSERT into supercali_dates(event_id, date, end_date) values('$x_event_id', '" . $span[$x] . "', '" . $span[$x] . "')";
		executeupdate($sql);
	  }
  }
}
?>
  
  
