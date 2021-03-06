<?php
include "includes/functions.php";

$opm_entry_id = go_escape_string($_POST['opm_entry_id']);

$submit1 = $_POST['submit1'];

if($submit1 != ""){
$sql = "SELECT opm_id, opm_entry_id, removed, replaced, comments, date_format(opm_date, \"%m/%d/%Y\") as opm_date, sent, date_format(last_sent_date, \"%m/%d/%Y %r\") as last_sent_date, 
date_format(opm_date, \"%Y-%m-%d\") as opm_date_raw from opm_entry where 
opm_entry_id='$opm_entry_id'";
$result_main = executequery($sql);
while($record_main = go_fetch_array($result_main)){
  $sent = $record_main['sent'];
  $last_sent_date = $record_main['last_sent_date'];
  include "opm_email_guts.php";
  
  $email_core = "";
  $email_client = "";
  
  $sql = "SELECT email from users where enabled=1 and bcc_opm=1 and master_id='$master_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $email = $record['email'];
	$sql = "SELECT count(*) from unsubscribe_opm where email=\"" . $email . "\" and opm_id='$opm_id'";
	$test = getsingleresult($sql);
	if($test==0){
	  $email_core .= $email . ",";
	}
  }
  $email_core = go_reg_replace(",$", "", $email_core);
  
  $sql = "SELECT email from contacts where email != '' and property_id='$property_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $email = $record['email'];
	if(!(go_reg($email, $email_client))){
	  $sql = "SELECT count(*) from unsubscribe_opm where email=\"" . $email . "\" and opm_id='$opm_id'";
	  $test = getsingleresult($sql);
	  if($test==0){
	    $email_client .= $email . ",";
	  }
	}
  }
  $sql = "SELECT email from contacts where email != '' and prospect_id='$prospect_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $email = $record['email'];
	if(!(go_reg($email, $email_client))){
	  $sql = "SELECT count(*) from unsubscribe_opm where email=\"" . $email . "\" and opm_id='$opm_id'";
	  $test = getsingleresult($sql);
	  if($test==0){
	    $email_client .= $email . ",";
	  }
	}
  }
  $email_client = go_reg_replace(",$", "", $email_client);
  
  $subject = "OPM Report - $site_name - $opm_date";
  $headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $fullname <$email_from>\n";
  $headers .= "Return-Path: $email_from\n";  // necessary for some emails such as aol
  $headers .= "Bcc: $email_core,$email_client\n";
  
	  
  email_q("", $subject, $body, $headers);
  
  $sql = "UPDATE opm_entry set sent=sent+1, last_sent_date=now() where opm_entry_id='$opm_entry_id'";
  executeupdate($sql);
}

}

$property_id = $_POST['property_id'];
$opm_id = $_POST['opm_id'];
$opm_entry_id = $_POST['opm_entry_id'];
meta_redirect("frame_property_production_opm_view.php?property_id=$property_id&opm_id=$opm_id&opm_entry_id=$opm_entry_id");
?>