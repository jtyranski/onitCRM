<?php
include "includes/functions.php";

$sql = "SELECT * from am_leakcheck";
$result_main = executequery($sql);
while($rec_main = go_fetch_array($result_main)){

$leak_id = $rec_main['leak_id'];
$status = $rec_main['status'];
$servicemen_id = $rec_main['servicemen_id'];
$invoice_type = $rec_main['invoice_type'];

if($status != "Dispatch" && $status != "Acknowledge" && $servicemen_id != 0  && $invoice_type != ''){
  $sql = "SELECT * from am_leakcheck where leak_id='$leak_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $servicemen_id = $record['servicemen_id'];
  $eta_date = $record['eta_date'];
  $invoice_type = $record['invoice_type'];
  $status = $record['status'];
  $prospect_id = $record['prospect_id'];
  $property_id = $record['property_id'];
  
  $sql = "SELECT master_id from prospects where prospect_id='$prospect_id'";
  $master_id = getsingleresult($sql);
  
  $complete=0;
  if($status=="Confirmed" || $status=="Invoiced" || $status=="Closed Out") $complete = 1;
  
  switch($invoice_type){
    case "Billable - TM":{
	  $category_id = 45;
	  break;
	}
	case "Billable - Contract":{
	  $category_id = 46;
	  break;
	}
	case "Warranty":{
	  $category_id = 47;
	  break;
	}
	case "2 Year":{
	  $category_id = 48;
	  break;
	}
	case "RTM":{
	  $category_id = 49;
	  break;
	}
	default:{
	  $category_id = 45;
	  break;
	}
  }
  
  $what = "SD";
  $description = "Service Dispatch ID " . $leak_id;
  $title = $description;
  
  $sql = "SELECT event_id from supercali_events where leak_id='$leak_id'";
  $event_id = getsingleresult($sql);
  if($event_id == ""){
    $sql = "INSERT into supercali_events(leak_id) values('$leak_id')";
	executeupdate($sql);
	$event_id = go_insert_id();
  }
  $sql = "UPDATE supercali_events set title=\"$title\", description=\"$description\", category_id='$category_id', user_id='2', 
  prospect_id='$prospect_id', property_id='$property_id', what='$what', ro_user_id='$servicemen_id', complete='$complete', master_id='$master_id'
  where event_id='$event_id'";
  executeupdate($sql);
  $sql = "DELETE from supercali_dates where event_id='$event_id'";
  executeupdate($sql);
  $sql = "INSERT into supercali_dates(event_id, date, end_date) values('$event_id', '$eta_date', '$eta_date')";
  executeupdate($sql);
}

}

?>