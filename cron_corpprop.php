<?php
include "includes/functions.php";

$sql = "SELECT prospect_id, company_name from prospects";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  $company_name = stripslashes($record['company_name']);
  
  $sql = "SELECT property_id from properties where prospect_id='$prospect_id' and corporate=1";
  $test = getsingleresult($sql);
  if($test==""){
    $sql = "INSERT into properties(prospect_id, site_name, corporate) values('$prospect_id', \"" . go_escape_string($company_name) . " Corporate\", 1)";
	executeupdate($sql);
  }
}

$sql = "SELECT property_id, prospect_id from properties where corporate=1 and address=''";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  $property_id = $record['property_id'];
  
  $sql = "SELECT address from prospects where prospect_id='$prospect_id'";
  $value = stripslashes(getsingleresult($sql));
  
  $sql = "UPDATE properties set address=\"" . go_escape_string($value) . "\" where property_id='$property_id'";
  executeupdate($sql);
}

$sql = "SELECT property_id, prospect_id from properties where corporate=1 and city=''";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  $property_id = $record['property_id'];
  
  $sql = "SELECT city from prospects where prospect_id='$prospect_id'";
  $value = stripslashes(getsingleresult($sql));
  
  $sql = "UPDATE properties set city=\"" . go_escape_string($value) . "\" where property_id='$property_id'";
  executeupdate($sql);
}

$sql = "SELECT property_id, prospect_id from properties where corporate=1 and state=''";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  $property_id = $record['property_id'];
  
  $sql = "SELECT state from prospects where prospect_id='$prospect_id'";
  $value = stripslashes(getsingleresult($sql));
  
  $sql = "UPDATE properties set state=\"" . go_escape_string($value) . "\" where property_id='$property_id'";
  executeupdate($sql);
}

$sql = "SELECT property_id, prospect_id from properties where corporate=1 and zip=''";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  $property_id = $record['property_id'];
  
  $sql = "SELECT zip from prospects where prospect_id='$prospect_id'";
  $value = stripslashes(getsingleresult($sql));
  
  $sql = "UPDATE properties set zip=\"" . go_escape_string($value) . "\" where property_id='$property_id'";
  executeupdate($sql);
}




?>