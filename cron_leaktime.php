<?php
include "includes/functions.php";

$sql = "SELECT leak_id, travel_time, labor_time, force_zero_labor, force_zero_travel from am_leakcheck where (labor_time=0 or travel_time=0) and invoice_type='Billable - TM'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $leak_id = $record['leak_id'];
  $old_travel_time = $record['travel_time'];
  $old_labor_time = $record['labor_time'];
  $force_zero_labor = $record['force_zero_labor'];
  $force_zero_travel = $record['force_zero_travel'];
  
  $sql = "SELECT sum(total_hours) from am_leakcheck_time where leak_id='$leak_id' and travel=0";
  $labor_time = getsingleresult($sql);
  $sql = "SELECT sum(total_hours) from am_leakcheck_time where leak_id='$leak_id' and travel=1";
  $travel_time = getsingleresult($sql);
  
  if($travel_time > 0 && $travel_time < .5) $travel_time = .5;
  if($labor_time > 0 && $labor_time < .5) $labor_time = .5;
  $labor_time = round($labor_time, 2);
  $travel_time = round($travel_time, 2);
	  
  if($old_labor_time==0  && $force_zero_labor==0){
    $sql = "UPDATE am_leakcheck set labor_time='$labor_time' where leak_id='$leak_id'";
    executeupdate($sql);
  }
  if($old_travel_time==0 && $force_zero_travel==0){
    $sql = "UPDATE am_leakcheck set travel_time='$travel_time' where leak_id='$leak_id'";
    executeupdate($sql);
  }
}
?>  