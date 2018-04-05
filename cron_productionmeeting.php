<?php
include "includes/functions.php";

$done = array();
$no_send = array();

//$sql = "SELECT act_id, property_id from activities where event='Production Meeting' and display=1 and complete=0";
$sql = "SELECT master_id, event_id, property_id, opm_id from supercali_events where category_id=42 and complete=0";
$res_main = executequery($sql);
while($act = go_fetch_array($res_main)){
  $master_id = $act['master_id'];
  $property_id = $act['property_id'];
  $event_id = $act['event_id'];
  $opm_id = $act['opm_id'];
  //echo $act_id . "<br>";
  /*
  if(in_array($property_id, $done)){
    continue;
  }
  else {
    $done[] = $property_id;
  }
  */
  
  $sql = "SELECT date_format(ip_email_start, \"%Y-%m-%d\") from opm where opm_id='$opm_id'";
  $ip_email_start = getsingleresult($sql);
  
  $sql = "SELECT site_name from properties where property_id='$property_id'";
  $site_name = stripslashes(getsingleresult($sql));
  //echo $site_name . "<br>";
  $sql = "SELECT date_format(date, \"%Y-%m-%d\") as datepretty from supercali_dates where 
  event_id='$event_id' order by date limit 1";
  // 32 and 33 are events we don't want to send this email for.
  $datepretty = getsingleresult($sql);
  //echo $datepretty . "<br>";
  if($datepretty > date("Y-m-d") && $ip_email_start <= date("Y-m-d")){
    /*
    if($send_productionmeeting==0){
      $no_send[] = $property_id;
	  continue;
    }
	*/
  /*
	$sql = "SELECT productionmeeting_email, productionmeeting_bid_id, user_id from activities where property_id='$property_id' and productionmeeting_email != '' order by act_id desc limit 1";
	$result = executequery($sql);
    $record = go_fetch_array($result);
	$productionmeeting_email = stripslashes($record['productionmeeting_email']);
	//$productionmeeting_bid_id = stripslashes($record['productionmeeting_bid_id']);
	//$user_id = stripslashes($record['user_id']);
	*/
	
	$productionmeeting_email = "";
	$sql = "SELECT email from contacts where property_id='$property_id' and email != ''";
	$result = executequery($sql); 
	while($record = go_fetch_array($result)){
	  // Check for unsubscribe here   *********************************************************************************
	  $sql = "SELECT count(*) from unsubscribe_productionmeeting where email=\"" . $record['email'] . "\" and opm_id='$opm_id'";
	  $test = getsingleresult($sql);
	  if($test==0){
	    $productionmeeting_email .= stripslashes($record['email']) . ",";
	  }
	}
	$productionmeeting_email = go_reg_replace("\,$", "", $productionmeeting_email);
	
	

    $sql = "SELECT date_format(date, \"%Y-%m-%d\") as startpretty from supercali_dates where 
    event_id='$event_id' order by date limit 1";
    $result = executequery($sql);
    $record = go_fetch_array($result);
	$startpretty = $record['startpretty'];
	//echo $startpretty . "<br>";
	$span = GetDays(date("Y-m-d"), $startpretty, "+1 day");
	$fullDays = sizeof($span);

    //$user_id=66; // just always make it Scott Young
	$sql = "SELECT productionmeeting_user, master_name, phone, logo from master_list where master_id='$master_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$productionmeeting_user = stripslashes($record['productionmeeting_user']);
	$master_name = stripslashes($record['master_name']);
	$master_phone = stripslashes($record['phone']);
	$master_logo = stripslashes($record['logo']);
	if($master_logo != "") $master_logo = "<img src=\"" . $CORE_URL . "uploaded_files/master_logos/" . $master_logo . "\">";
	
	
	
    $sql = "SELECT concat(firstname, ' ', lastname) as fullname, email, office, extension, title
	from users where user_id='" . $productionmeeting_user . "'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$mail_user_name = stripslashes($record['fullname']);
	$mail_user_email = stripslashes($record['email']);
	$mail_user_office = stripslashes($record['office']);
	//$mail_user_office = substr($mail_user_office,0,3)."-".substr($mail_user_office,3,3)."-".substr($mail_user_office,6,4);
	$mail_user_extension = stripslashes($record['extension']);
	$mail_user_title = stripslashes($record['title']);
	$mail_user_cellphone = stripslashes($record['cellphone']);
	//$mail_user_cellphone = substr($mail_user_cellphone,0,3)."-".substr($mail_user_cellphone,3,3)."-".substr($mail_user_cellphone,6,4);

	    $signature = "Respectfully,<br>";
	    $signature .= $mail_user_name . "<br>";
	    if($mail_user_title != "") $signature .= $mail_user_title . "<br>";
	    $signature .= "<a href='mailto:" . $mail_user_email . "'>" . $mail_user_email . "</a><br>";
	    $signature .= "Office: " . $mail_user_office;
	    if($mail_user_extension != "") $signature .= " Ext: " . $mail_user_extension;
	    $signature .= "<br>";
	    //if($mail_user_cellphone != "") $signature .= "Cell: " . $mail_user_cellphone . "<br>";
	$phone = $mail_user_office;
	if($mail_user_extension != "") $phone .= " x " . $mail_user_extension;
	
    if($productionmeeting_user==0 || $productionmeeting_user==""){
      $mail_user_name = $master_name;
	  $mail_user_email = "info@fcscontrol.com";
	  $signature = $master_name;
	  $phone = $master_phone;
    }
	if($phone=="") $phone = $master_phone;
    
	//$code = $property_id * 1789;
	$sql = "SELECT code from opm where opm_id='$opm_id'";
	$code = getsingleresult($sql);
	
    $sql = "SELECT body from templates where name='Production Meeting Update'";
    $body = stripslashes(getsingleresult($sql));

    $body = str_replace("[NAME]", $mail_user_name, $body);
    $body = str_replace("[PHONE]", $phone, $body);
    $body = str_replace("[CODE]", $code, $body);
    $body = str_replace("[NUMDAYS]", $fullDays, $body);
    $body = str_replace("[SIGNATURE]", $signature, $body);
	$body = str_replace("[CORE URL]", $CORE_URL, $body);
	$body = str_replace("[MASTER LOGO]", $master_logo, $body);
	
	$email_admin = "";
	$sql = "SELECT email from users where bcc_productionmeeting=1 and enabled=1 and master_id='$master_id'";
	$res_admin = executequery($sql);
	while($rec_admin = go_fetch_array($res_admin)){
	  $sql = "SELECT count(*) from unsubscribe_productionmeeting where email=\"" . $rec_admin['email'] . "\" and opm_id='$opm_id'";
	  $test = getsingleresult($sql);
	  if($test==0){
	    $email_admin .= stripslashes($rec_admin['email']) . ",";
	  }
	}
	$email_admin = go_reg_replace("\,$", "", $email_admin);

	
	//if($productionmeeting_email == "") $productionmeeting_email = $mail_user_email;
	
	$headers = "Content-type: text/html; charset=iso-8859-1\n";
    $headers .= "From: $mail_user_email\n";
    $headers .= "Return-Path: ". $mail_user_email ."\n";  // necessary for some emails such as aol
	//$headers .= "Bcc:jwert@encitegroup.com\n";
	$headers .= "Bcc:$email_admin,$productionmeeting_email\n";
	
	$subject = "Project Schedule Update - $site_name";
	
	//$productionmeeting_email = "jwert@encitegroup.com";
	

	
	//$body .= "<br><br>We apologize if you have received this twice today, but some emails displayed incorrect start dates.";
	//echo $email_admin . "<br>" . $email_list . "<br>";
	
	//echo "Email list for $act_id: $email_list <br>";
    email_q("", $subject, $body, $headers);
	//@mail("jwert@encitegroup.com", $subject, $body, $headers);
	
	$body = go_escape_string($body);
	$body .= "<br><br>Emailed to: $productionmeeting_email";
	
	
	$sql = "SELECT prospect_id from properties where property_id='$property_id'";
	$prospect_id = getsingleresult($sql);
	$sql = "INSERT into notes(user_id, property_id, prospect_id, date, event, note, note_large) values(
	'$productionmeeting_user', '$property_id', '$prospect_id', now(), 'Email', 'Production Schedule update email sent', \"$body\")";
	executeupdate($sql);
	
	
  }
}

/*
if(sizeof($no_send) > 0){
  for($x=0;$x<sizeof($no_send);$x++){
    $sql = "SELECT site_name from properties where property_id='" . $no_send[$x] . "'";
	$site_name = stripslashes(getsingleresult($sql));
	$no_list .= $site_name . "<br>";
  }
  $message = "The following sites have operations scheduled, but they have been selected to NOT send out countdown emails.  To re-activate, please click on 
  Production Meeting Emails in the Toolbox.<br><br>";
  $message .= $no_list;
  $headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: info@fcscontrol.com\n";
  $headers .= "Return-Path: info@fcscontrol.com\n";  // necessary for some emails such as aol
  @mail($mail_user_email, "Countdown Email Off List", $message, $headers);
}
*/

?>