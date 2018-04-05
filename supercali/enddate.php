<?php
//exit;
include "../includes/functions.php";

$show_userid = $_GET['show_userid'];
$show_what = $_GET['show_what'];
$show_type = $_GET['show_type'];
$show_crew = $_GET['show_crew'];
$show_complete = $_GET['show_complete'];
$event_id = $_GET['event_id'];
$move = $_GET['move'];


	
    
  

if($move==1){
  $sql = "SELECT date_format(date, \"%m\") as month, date_format(date, \"%d\") as day, date_format(date, \"%Y\") as year 
  from supercali_dates where event_id='$event_id' order by date desc limit 1";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $day = $record['day'];
  $month = $record['month'];
  $year = $record['year'];
  $nextday  = date("Y-m-d", mktime(0, 0, 0, $month, $day +1, $year));
  $sql = "INSERT into supercali_dates(event_id, date) values('$event_id', '$nextday')";
  executeupdate($sql);
  
 
  $x_event_id = $event_id;
  while($x_event_id){
    $x_event_id = BumpBack($x_event_id);
  }
}

if($move==0){
    $sql = "SELECT date_format(date, \"%y-%m-%d\") as datepretty, 
	date_format(date, \"%m\") as month, date_format(date, \"%d\") as day, date_format(date, \"%Y\") as year 
	from supercali_dates where event_id='$event_id' order by date desc limit 1";
	$result = executequery($sql);
	$record = go_fetch_array($result);
    $enddate = $record['datepretty'];
	$endmonth = $record['month'];
	$endday = $record['day'];
	$endyear = $record['year'];
	
	$sql = "SELECT date_format(date, \"%y-%m-%d\") as datepretty, 
	date_format(date, \"%m\") as month, date_format(date, \"%d\") as day, date_format(date, \"%Y\") as year 
	from supercali_dates where event_id='$event_id' order by date asc limit 1";
	$result = executequery($sql);
	$record = go_fetch_array($result);
    $startdate = $record['datepretty'];
	$startmonth = $record['month'];
	$startday = $record['day'];
	$startyear = $record['year'];
	
	$new_enddate = date("Y-m-d", mktime(0, 0, 0, $endmonth, $endday - 1, $endyear));
	
	$sql = "DELETE from supercali_dates where event_id='$event_id'";
	executeupdate($sql);
	$span = GetDays($startdate, $new_enddate, "+1 day");
	for($x=0;$x<sizeof($span);$x++){
	  $sql = "SELECT event_id from supercali_dates where event_id = '$event_id' and date like '" . $span[$x] . "%'";
	  $test = getsingleresult($sql);
	  if($test==""){
	    $sql = "INSERT into supercali_dates(event_id, date, end_date) values('$event_id', '" . $span[$x] . "', '" . $span[$x] . "')";
		executeupdate($sql);
	  }
	}
}
 
$sql = "SELECT ro_user_id from supercali_events where event_id='$event_id'";
$user_id = getsingleresult($sql);
supercaliOperations($user_id);

meta_redirect("index.php?show_userid=$show_userid&show_what=$show_what&show_type=$show_type&show_crew=$show_crew&show_complete=$show_complete");
?>