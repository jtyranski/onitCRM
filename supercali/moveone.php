<?php
//exit;
include "../includes/functions.php";

$show_userid = $_GET['show_userid'];
$show_what = $_GET['show_what'];
$show_type = $_GET['show_type'];
$show_crew = $_GET['show_crew'];
$show_complete = $_GET['show_complete'];
$event_id = $_GET['event_id'];
$month = $_GET['month'];
$year = $_GET['year'];
$x = $_GET['x'];
$x = go_reg_replace("px", "", $x);
$dw = 25;
//echo "Month: $month  Year: $year X: $x<br>";

function MonthDays($someMonth, $someYear)
    {
        return date("t", strtotime($someYear . "-" . $someMonth . "-01"));
    }  
  

if($event_id){

  if(date("m-Y") == $month . "-" . $year){
	$firstday = date("j");
  }
  else {
    $firstday = 1;
  }
  $firstday=1;

  $daysmonth1 = MonthDays($month, $year) - $firstday + 1;
  //echo $daysmonth1 . "<br>";
	
	$nextmonth = $month +1;
	$nextmonthyear = $year;
	if($nextmonth > 12){
	  $nextmonth -= 12;
	  $nextmonthyear += 1;
	}

	$daysmonth2 = MonthDays($nextmonth, $nextmonthyear);
	
	$nextmonth = $month +2;
	$nextmonthyear = $year;
	if($nextmonth > 12){
	  $nextmonth -= 12;
	  $nextmonthyear += 1;
	}

	$daysmonth3 = MonthDays($nextmonth, $nextmonthyear);
  
  $newmonth = $month;
  $newyear = $year;
  $em1 = ($dw + 2) * $daysmonth1;
  $em2 = ($dw + 2) * ($daysmonth1 + $daysmonth2);
  $em3 = ($dw + 2) * ($daysmonth1 + $daysmonth2 + $daysmonth3);
  //echo "X=$x EM3 = $em3 EM1=$em1 EM2=$em2<br>";
  
  switch($x){
    case $x > $em3:{
	  $x -= $em3;
	  $newmonth = $month + 3;
	  $newyear = $year;
	  if($newmonth > 12){
	    $newmonth -= 12;
	    $newyear += 1;
	  }
	  break;
	}
	case $x > $em2:{
	  $x -= $em2;
	  $newmonth = $month + 2;
	  $newyear = $year;
	  if($newmonth > 12){
	    $newmonth -= 12;
	    $newyear += 1;
	  }
	  break;
	}
	case $x > $em1:{
	  $x -= $em1;
	  $newmonth = $month + 1;
	  $newyear = $year;
	  if($newmonth > 12){
	    $newmonth -= 12;
	    $newyear += 1;
	  }
	  break;
	}
  }
	
	/*  
  if($x > $em3){
    $x -= $em3;
	$newmonth = $month + 3;
	$newyear = $year;
	if($newmonth > 12){
	  $newmonth -= 12;
	  $newyear += 1;
	}
  }
  
  if($x > $em2){
    $x -= $em2;
	$newmonth = $month + 2;
	$newyear = $year;
	if($newmonth > 12){
	  $newmonth -= 12;
	  $newyear += 1;
	}
  }
  
  if($x > $em1){
    $x -= $em1;
	$newmonth = $month + 1;
	$newyear = $year;
	if($newmonth > 12){
	  $newmonth -= 12;
	  $newyear += 1;
	}
  }
  */
  
  //echo "Ready to ceil(x / dw +2)<br>";
  //echo "X=$x<br>";
  $newday = ceil($x / ($dw + 2));
 // echo "New day: $newday<br>";
  if($newmonth==$month) {
    //echo "still in same month<br>";
	$newday += $firstday - 1;
  }
  
  $sql = "SELECT date_format(date, \"%Y-%m-%d\") as datepretty, 
	date_format(date, \"%m\") as month, date_format(date, \"%d\") as day, date_format(date, \"%Y\") as year 
	from supercali_dates where event_id='$event_id' order by date desc limit 1";
	$result = executequery($sql);
	$record = go_fetch_array($result);
    $enddate = $record['datepretty'];
	$endmonth = $record['month'];
	$endday = $record['day'];
	$endyear = $record['year'];
  
    $sql = "SELECT date_format(date, \"%Y-%m-%d\") as datepretty, 
	date_format(date, \"%m\") as month, date_format(date, \"%d\") as day, date_format(date, \"%Y\") as year 
	from supercali_dates where event_id='$event_id' order by date asc limit 1";
	$result = executequery($sql);
	$record = go_fetch_array($result);
    $startdate = $record['datepretty'];
	$startmonth = $record['month'];
	$startday = $record['day'];
	$startyear = $record['year'];
	
	$span = GetDays($startdate, $enddate, "+1 day");
	$number_bump = sizeof($span);
	$new_startdate = $newyear . "-" . $newmonth . "-" . $newday;
	$new_enddate = date("Y-m-d", mktime(0, 0, 0, $newmonth, $newday + ($number_bump - 1), $newyear));

    $span2 = GetDays($new_startdate, $new_enddate, "+1 day");
	$number_bump2 = sizeof($span2);
/*
  echo "$event_id<br>Old date span was $startdate to $enddate.  This is $number_bump days<br>";
  echo "Now starts $new_startdate and ends $new_enddate.  This is $number_bump2 days<br>";
  exit;
*/
  $sql = "DELETE from supercali_dates where event_id='$event_id'";
  executeupdate($sql);
  $span = GetDays($new_startdate, $new_enddate, "+1 day");
	for($y=0;$y<sizeof($span);$y++){
	  $sql = "SELECT event_id from supercali_dates where event_id = '$event_id' and date like '" . $span[$y] . "%'";
	  $test = getsingleresult($sql);
	  if($test==""){
	    $sql = "INSERT into supercali_dates(event_id, date, end_date) values('$event_id', '" . $span[$y] . "', '" . $span[$y] . "')";
		executeupdate($sql);
	  }
	}
	

  
 
  $x_event_id = $event_id;
  /*
  while($x_event_id){
    $x_event_id = BumpBack($x_event_id);
  }
  */
}

$sql = "SELECT ro_user_id from supercali_events where event_id='$event_id'";
$user_id = getsingleresult($sql);
supercaliOperations($user_id);

meta_redirect("index.php?show_userid=$show_userid&show_what=$show_what&show_type=$show_type&show_crew=$show_crew&show_complete=$show_complete");
?>