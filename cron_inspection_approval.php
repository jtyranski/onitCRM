<?php
include "includes/functions.php";

$sql = "SELECT a.property_id, a.site_name, b.master_id from properties a, prospects b where 
a.prospect_id=b.prospect_id and a.text_alert_sent=0 and (a.ready_for_pre_approval=1 or a.ready_for_email=1)";
//echo $sql . "<br>";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $property_id = stripslashes($record['property_id']);
  $site_name = stripslashes($record['site_name']);
  $master_id = stripslashes($record['master_id']);
  
  $sql = "SELECT user_id from activities where property_id='$property_id' and event='Inspection' order by act_id desc limit 1";
  $user_id = getsingleresult($sql);
  if($user_id != "" && $user_id != 0){
    $sql = "SELECT email from users where user_id='$user_id'";
  }
  else {
    $sql = "SELECT dispatch_from_email from master_list where master_id='$master_id'";
  }
  $from_email = getsingleresult($sql);
  
  $sql = "SELECT use_groups from master_list where master_id='$master_id'";
  $use_groups = getsingleresult($sql);
  
  $groups_filter = " 1=1 ";
  if($use_groups){
    $sql = "SELECT groups, subgroups from properties where property_id='$property_id'";
	$result2 = executequery($sql);
	$record2 = go_fetch_array($result2);
	$test_groups = $record2['groups'];
	$test_subgroups = $record2['subgroups'];
	if(($test_groups != "" && $test_groups != 0) || ($test_subgroups != "" && $test_subgroups != 0)){
      $groups_filter = " ((";
	  if($test_groups != "" && $test_groups != 0) $groups_filter .= "groups like '%," . $test_groups . ",%' ";
	  if($test_subgroups != "" && $test_subgroups != 0 && $groups_filter != " ((") $groups_filter .= "and ";
	  if($test_subgroups != "" && $test_subgroups != 0) $groups_filter .= "subgroups like '%," . $test_subgroups . ",%' ";
	  $groups_filter .= ") or (groups = ''))";
    }
  }
  
  $text = "";
  $mail = "";
  
  $sql = "SELECT a.cellphone, b.cell_extension from users a, cell_providers b where a.cell_id=b.cell_id and a.enabled=1 
  and a.alert_inspection_approval_text=1 and a.cell_id != 0 and a.cellphone != '' and a.master_id='" . $master_id . "' and $groups_filter";
  //echo $sql . "<br>";
  $result_phone = executequery($sql);
  while($record_phone = go_fetch_array($result_phone)){
    $cellphone = remove_non_numeric(stripslashes($record_phone['cellphone']));
    $cell_extension = stripslashes($record_phone['cell_extension']);
  
    $text[] = $cellphone . "@" . $cell_extension;
  }


  $sql = "SELECT a.email from users a where a.enabled=1 
  and a.alert_inspection_approval_email=1 and a.master_id='$master_id' and $groups_filter";
  $result_email = executequery($sql);
  while($record_email = go_fetch_array($result_email)){
    $email = stripslashes($record_email['email']);
  
    $mail[] = $email;
  }

/*
  $message = $site_name . " needs inspection approval. " . $SITE_URL . "toolbox.php?go=report_inspectionapproval.php";
  if(is_array($text)){
    for($x=0;$x<sizeof($text);$x++){
	  email_q($text[$x], "", $message, "From:$from_email");
	  //echo $text[$x] . "<br>";
	}
  }
  
  if(is_array($mail)){
    for($x=0;$x<sizeof($mail);$x++){
	  email_q($mail[$x], "New inspection approval", $message, "From:$from_email");
	  //echo $text[$x] . "<br>";
	}
  }
  
  $sql = "UPDATE properties set text_alert_sent=1 where property_id='$property_id'";
  executeupdate($sql);
  */
}


?>