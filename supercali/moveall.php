<?php
//exit;
include "../includes/functions.php";

$show_userid = $_GET['show_userid'];
$show_what = $_GET['show_what'];
$show_type = $_GET['show_type'];
$show_crew = $_GET['show_crew'];
$show_complete = $_GET['show_complete'];
$user_id = $_GET['user_id'];
$move = $_GET['move'];
$cat = $_GET['cat'];
if($cat=="") $cat = "Operations";

$cat_clause = " 1=1 ";
if($cat=="Operations"){
  $cat_clause = " a.category_id in(41, 42, 43) ";
}
if($cat=="Programming"){
  $cat_clause = " a.category_id = 37 ";
}

if($move==0) $move = -1;

$sql = "SELECT a.event_id from supercali_events a, supercali_dates b where a.event_id=b.event_id and 
a.ro_user_id='$user_id' and a.complete=0 and $cat_clause group by a.event_id";
$result_main = executequery($sql);
while($record_main = go_fetch_array($result_main)){
    $event_id = $record_main['event_id'];
	
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
	
	$new_enddate = date("Y-m-d", mktime(0, 0, 0, $endmonth, $endday + $move, $endyear));
	$new_startdate = date("Y-m-d", mktime(0, 0, 0, $startmonth, $startday + $move, $startyear));
	
	$sql = "DELETE from supercali_dates where event_id='$event_id'";
	executeupdate($sql);
	$span = GetDays($new_startdate, $new_enddate, "+1 day");
	for($x=0;$x<sizeof($span);$x++){
	  $sql = "SELECT event_id from supercali_dates where event_id = '$event_id' and date like '" . $span[$x] . "%'";
	  $test = getsingleresult($sql);
	  if($test==""){
	    $sql = "INSERT into supercali_dates(event_id, date, end_date) values('$event_id', '" . $span[$x] . "', '" . $span[$x] . "')";
		executeupdate($sql);
	  }
	}
}
 
supercaliOperations($user_id); 

meta_redirect("index.php?show_userid=$show_userid&show_what=$show_what&show_type=$show_type&show_crew=$show_crew&show_complete=$show_complete");
?>