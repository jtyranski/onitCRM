<?php
include "includes/functions.php";

$leak_id = $_GET['leak_id'];
$id = $_GET['id'];
$rate = $_GET['rate'];


$sql = "SELECT date_format(time_in, \"%k\") as in_hour, date_format(time_in, \"%i\") as in_minute, 
  date_format(time_out, \"%k\") as out_hour, date_format(time_out, \"%i\") as out_minute
  from am_leakcheck_time where id='$id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $in_hour = $record['in_hour'];
  $in_minute = $record['in_minute'];
  $out_hour = $record['out_hour'];
  $out_minute = $record['out_minute'];
  
  if($in_minute == 15) $in_minute = .25;
  if($in_minute == 30) $in_minute = .5;
  if($in_minute == 45) $in_minute = .75;
  
  if($out_minute == 15) $out_minute = .25;
  if($out_minute == 30) $out_minute = .5;
  if($out_minute == 45) $out_minute = .75;
  
  $in = $in_hour + $in_minute;
  $out = $out_hour + $out_minute;
  
  $total_hours = $out - $in;
  if($total_hours < 0) $total_hours += 24;
  
  $total_hours = $total_hours * $rate;
  
  $sql = "UPDATE am_leakcheck_time set total_hours='$total_hours', rate='$rate' where id='$id'";
  executeupdate($sql);
  
$sql = "SELECT date_format(time_in, \"%m/%d/%Y\") as datepretty, date_format(time_in, \"%h:%i %p\") as inpretty, 
date_format(time_out, \"%h:%i %p\") as outpretty, total_hours, id 
from am_leakcheck_time where leak_id='$leak_id' and complete=1 order by time_out desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){

  $gtotal_hours += $record['total_hours'];
}


$sql = "UPDATE am_leakcheck set gtotal_hours = '$gtotal_hours' where leak_id='$leak_id'";
executeupdate($sql);

$sql = "SELECT sum(total_hours) from am_leakcheck_time where leak_id='$leak_id' and travel=0";
      $labor_time = getsingleresult($sql);
      $sql = "SELECT sum(total_hours) from am_leakcheck_time where leak_id='$leak_id' and travel=1";
      $travel_time = getsingleresult($sql);
	  
	  if($travel_time > 0 && $travel_time < .5) $travel_time = .5;
	  if($labor_time > 0 && $labor_time < .5) $labor_time = .5;
	  $labor_time = round($labor_time, 2);
	  $travel_time = round($travel_time, 2);
	  
	  $sql = "UPDATE am_leakcheck set labor_time='$labor_time', travel_time='$travel_time' where leak_id='$leak_id'";
	  executeupdate($sql);
	  
meta_redirect("fcs_sd_report_view.php?leak_id=$leak_id");
?>