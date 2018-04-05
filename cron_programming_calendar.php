<?php
include "includes/functions.php";

$sql = "SELECT event_id from supercali_events where category_id=37 and complete=0 and ro_user_id != 0";
$result_main = executequery($sql);
while($record_main = go_fetch_array($result_main)){
  $event_id = $record_main['event_id'];
  
  $sql = "SELECT date_format(date, \"%Y-%m-%d\") as date
	from supercali_dates where event_id='$event_id' order by date desc limit 1";
	$result = executequery($sql);
	$record = go_fetch_array($result);
    $enddate = $record['date'];
	$endmonth = $record['month'];
	$endday = $record['day'];
	$endyear = $record['year'];
	
	$sql = "SELECT date_format(date, \"%Y-%m-%d\") as date 
	from supercali_dates where event_id='$event_id' order by date asc limit 1";
	$result = executequery($sql);
	$record = go_fetch_array($result);
    $startdate = $record['date'];
	$startmonth = $record['month'];
	$startday = $record['day'];
	$startyear = $record['year'];
	
	if($enddate < date("Y-m-d")){
	  $newend = date("Y-m-d");
	  $sql = "DELETE from supercali_dates where event_id='$event_id'";
      executeupdate($sql);
  
      $span = GetDays($startdate, $newend, "+1 day");
	  for($x=0;$x<sizeof($span);$x++){
	    $sql = "SELECT event_id from supercali_dates where event_id = '$event_id' and date like '" . $span[$x] . "%'";
	    $test = getsingleresult($sql);
	    if($test==""){
	      $sql = "INSERT into supercali_dates(event_id, date, end_date) values('$event_id', '" . $span[$x] . "', '" . $span[$x] . "')";
		  executeupdate($sql);
	    }
	  }
	}
}
	
	?>