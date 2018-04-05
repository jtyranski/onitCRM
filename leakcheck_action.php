<?php
include "includes/functions.php";

$property_id = go_escape_string($_POST['property_id']);
$sql = "SELECT prospect_id from properties where property_id='$property_id'";
$prospect_id = getsingleresult($sql);

include "includes/master_check.php";
$section_type = go_escape_string($_POST['section_type']);
$section_id = go_escape_string($_POST['section_id']);
$notes = go_escape_string($_POST['notes']);
$contact_id = go_escape_string($_POST['contact_id']);
$submit1 = $_POST['submit1'];
$demo = $_POST['demo'];
$demo_email_one = go_escape_string($_POST['demo_email_one']);
$extra_demo = $_POST['extra_demo'];
if($demo != 1) $demo = 0;
if($SESSION_VIEW_DEMO) $demo=1;

$silent_mode = go_escape_string($_POST['silent_mode']);
if($silent_mode != 1) $silent_mode = 0;

$code = uniqueTimeStamp();
$counter = 0;
while($counter < 4){
  $extra .= chr(rand(65, 90));
  $counter++;
}
$code .= $extra;

$demo_email = $demo_email_one;
if(is_array($extra_demo)){ 
  for($x=0;$x<sizeof($extra_demo);$x++){
    $demo_email .= "," . $extra_demo[$x];
  }
}

$am_contractor_id = $_POST['am_contractor_id'];
//$priority = $_POST['priority'];
$priority_id = $_POST['priority_id'];

$sql = "SELECT priority" . $priority_id . " from master_list where master_id='" . $SESSION_VIEW_MASTER_ID . "'";
$priority_name = stripslashes(getsingleresult($sql));


if(go_reg("\*", $am_contractor_id)){
  $internal = 1;
  $internal_id = go_reg_replace("\*", "", $am_contractor_id);
}
else {
  $internal_id=0;
}

if($submit1=="Send Dispatch"){
  /*
  $sql = "SELECT am_contractor_id from sections where section_id='$section_id'";
  $am_contractor_id = getsingleresult($sql);
  */
  $sql = "SELECT priority" . $priority_id . "_rate from master_list where master_id='" . $SESSION_VIEW_MASTER_ID . "'";
  $labor_rate = getsingleresult($sql);
  
  $x_priority_id = $priority_id;
  if($x_priority_id==1 || $x_priority_id==4) $x_priority_id="";
  $sql = "SELECT nte_amount, labor_rate" . $x_priority_id . " as lr, company_name, address, city, state, zip, 
  hours_of_operation, invoice_requirements, checkin_procedure, checkout_procedure from prospects where prospect_id='" . $SESSION_VIEW_PROSPECT_ID . "'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $nte_amount = $record['nte_amount'];
  if($record['lr'] != 0) $labor_rate = $record['lr'];
  $nte=0;
  if($nte_amount != 0) $nte=1;
  $company['name'] = stripslashes($record['company_name']);
  $company['address'] = stripslashes($record['address']);
  $company['city'] = stripslashes($record['city']);
  $company['state'] = stripslashes($record['state']);
  $company['zip'] = stripslashes($record['zip']);
  $company['hours_of_operation'] = stripslashes($record['hours_of_operation']);
  $company['checkin_procedure'] = stripslashes($record['checkin_procedure']);
  $company['checkout_procedure'] = stripslashes($record['checkout_procedure']);
  $company['invoice_requirements'] = stripslashes($record['invoice_requirements']);
  $additional_notes = "";
  if($company['hours_of_operation'] != "") $additional_notes .= "Hours of Operation:\n" . $company['hours_of_operation'] . "\n\n";
  if($company['checkin_procedure'] != "") $additional_notes .= "Check In Procedure:\n" . $company['checkin_procedure'] . "\n\n";
  if($company['checkout_procedure'] != "") $additional_notes .= "Check Out Procedure:\n" . $company['checkout_procedure'] . "\n\n";
  if($company['invoice_requirements'] != "") $additional_notes .= "Invoice Requirements:\n" . $company['invoice_requirements'] . "\n\n";
  $additional_notes = go_escape_string($additional_notes);
  
  
  $sql = "SELECT concat(firstname, ' ', lastname) as fullname, phone from contacts where prospect_id='" . $SESSION_VIEW_PROSPECT_ID . "' 
  order by id limit 1";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $contact['fullname'] = stripslashes($record['fullname']);
  $contact['phone'] = stripslashes($record['phone']);
  
  $billto = "";
  //$billto = $contact['fullname'] . "\n";
  $billto .= $company['name'] . "\n";
  $billto .= $company['address'] . "\n";
  $billto .= $company['city'] . ", " . $company['state'] . " " . $company['zip'] . "\n";
  $billto .= $contact['phone'];
  $billto = go_escape_string($billto);
  
  $sql = "SELECT bill_to, bt_manufacturer, bt_installer, bt_term, bt_start, bt_contact, bt_phone
  from drawings where property_id='$property_id' order by drawing_id desc limit 1";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $bill_to = go_escape_string(stripslashes($record['bill_to']));
  $bt_manufacturer = go_escape_string(stripslashes($record['bt_manufacturer']));
  $bt_installer = go_escape_string(stripslashes($record['bt_installer']));
  $bt_term = go_escape_string(stripslashes($record['bt_term']));
  $bt_start = go_escape_string(stripslashes($record['bt_start']));
  $bt_contact = go_escape_string(stripslashes($record['bt_contact']));
  $bt_phone = go_escape_string(stripslashes($record['bt_phone']));
  
  
  
  $sql = "INSERT into am_leakcheck(property_id, section_id, user_id, notes, code, dispatch_date, prospect_id, 
  section_type, demo, demo_email, priority_id, internal_id, silent_mode, nte, nte_amount, labor_rate, 
  bill_to, bt_manufacturer, bt_installer, bt_term, bt_start, bt_contact, bt_phone, travel_rate, billto, resource_due_date, contact_id, additional_notes) values(
  '$property_id', '$section_id', '" . $SESSION_VIEW_USER_ID . "', \"$notes\", \"$code\", now(), 
  '" . $SESSION_VIEW_PROSPECT_ID . "', '$section_type', '$demo', \"$demo_email\", \"$priority_id\", '$internal_id', 
  '$silent_mode', '$nte', '$nte_amount', '$labor_rate', 
  \"$bill_to\", \"$bt_manufacturer\", \"$bt_installer\", \"$bt_term\", \"$bt_start\", \"$bt_contact\", \"$bt_phone\", \"$labor_rate\", \"$billto\", now(), \"$contact_id\", \"$additional_notes\")";
  executeupdate($sql);
  
  $leak_id = go_insert_id();
  $sql = "UPDATE am_leakcheck set invoice_id='$leak_id' where leak_id='$leak_id'";
  executeupdate($sql);
  
  $sql = "UPDATE users set show_new_dispatch=1 where master_id='" . $SESSION_VIEW_MASTER_ID . "'";
  executeupdate($sql);
  
  for($x=0;$x<sizeof($_FILES);$x++){
    if($_FILES['photo_' . $x]['type'] != ""){
      //echo $x . " - ";
      //echo $_FILES['photo_' . $x]['name'] . "<br>";
	  if(is_image_valid($_FILES['photo_' . $x]['name'])){
	    $sql = "INSERT into am_leakcheck_photos(leak_id, additional) values (
	    '$leak_id', '2')";
	    executeupdate($sql);
	    $sql = "SELECT photo_id from am_leakcheck_photos where leak_id='$leak_id' 
	    order by photo_id desc limit 1";
	    $photo_id=getsingleresult($sql);
		
		$ext = explode(".", $_FILES['photo_' . $x]['name']);
  	    $ext = array_pop($ext);
  	    $filename = uniqueTimeStamp() . "." . $ext;
	    move_uploaded_file($_FILES['photo_' . $x]['tmp_name'], "uploaded_files/temp/". $filename);
  	    resizeimage("uploaded_files/temp/$filename", "uploaded_files/leakcheck/", $REPORT_PHOTO_SIZE);
  	    @unlink("uploaded_files/temp/". $filename);
	
	
	    $sql = "UPDATE am_leakcheck_photos set photo='$filename'
		where photo_id='$photo_id' and leak_id='$leak_id'";
	    executeupdate($sql);
	  }
	}
  }
  
  if(is_uploaded_file($_FILES['extrafile']['tmp_name'])){
    $sql = "INSERT into am_leakcheck_photos(leak_id, additional) values (
	'$leak_id', '3')";
	executeupdate($sql);
	$sql = "SELECT photo_id from am_leakcheck_photos where leak_id='$leak_id' 
	order by photo_id desc limit 1";
	$photo_id=getsingleresult($sql);
	
	$ext = explode(".", $_FILES['extrafile']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['extrafile']['tmp_name'], "uploaded_files/leakcheck/". $filename);
	
	$sql = "UPDATE am_leakcheck_photos set photo='$filename'
	where photo_id='$photo_id' and leak_id='$leak_id'";
	executeupdate($sql);
  }
  
  
  $sql = "SELECT section_name from sections where section_id='$section_id'";
  $section_name = stripslashes(getsingleresult($sql));
  $building_section = $section_name;
  
 
  $sql = "SELECT company_name from prospects where prospect_id='" . $SESSION_VIEW_PROSPECT_ID . "'";
  $prospect_company_name = stripslashes(getsingleresult($sql));
  
  // send out Leak Check email
  $sql = "SELECT site_name, city, state, address, zip, region from properties where property_id='$property_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $site_name = stripslashes($record['site_name']);
  $city = stripslashes($record['city']);
  $state = stripslashes($record['state']);
  $address = stripslashes($record['address']);
  $zip = stripslashes($record['zip']);
  $location = $address . "<br>" . $city . ", " . $state . " " . $zip;
  $url = $SITE_URL . "leakcheck_contractor.php?" . $code;
  $region = $record['region'];
  /*
  $sql = "SELECT concat(firstname, ' ', lastname) as fullname, email from users where region='$region' limit 1";
  $res_region = getsingleresult($sql);
  $rec_region = go_fetch_array($res_region);
  $from_name = stripslashes($rec_region['fullname']);
  $from_email = stripslashes($rec_region['email']);
  if($from_email != ""){
    $mail_from_email = $from_email;
	$mail_from_name = $from_name;
  }
  else {
    $mail_from_email = "service@fcscontrol.com";
	$mail_from_name = "FCS";
  }
  */
  $sql = "SELECT c.master_name, c.dispatch_from_email from prospects b, master_list c 
  where b.master_id=c.master_id and b.prospect_id='" . $SESSION_VIEW_PROSPECT_ID . "'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $MASTER_NAME = stripslashes($record['master_name']);
  $DISPATCH_FROM_EMAIL = stripslashes($record['dispatch_from_email']);
  
  $mail_from_email = $DISPATCH_FROM_EMAIL;
  $mail_from_name = $MASTER_NAME;
  
  /*
  $sql = "SELECT sd_notifications from contractors where cont_id='$am_contractor_id'";
  $email = getsingleresult($sql);
  */
  
  if($internal){
    $sql = "SELECT email from am_users where user_id='$internal_id'";
    $result = executequery($sql);
    $record = go_fetch_array($result);
    $email = stripslashes($record['email']);
	
	$sql = "UPDATE am_leakcheck set contractor_email=\"$email\", internal='1' where leak_id='$leak_id'";
    executeupdate($sql);
  }
  else {
  /*  10/5/10  No emails to contractors.  All going through RO
    $sql = "SELECT email, cont_id from am_contractor_info where id='$am_contractor_id'";
    $result = executequery($sql);
    $record = go_fetch_array($result);
    $email = stripslashes($record['email']);
    $cont_id = $record['cont_id'];

    $sql = "UPDATE am_leakcheck set contractor_email=\"$email\", cont_id='$cont_id' where leak_id='$leak_id'";
    executeupdate($sql);
  */
  }
  
  //$fcs_option = go_escape_string($_POST['fcs_option']);
  $fcs_option = 0;
  $check_fcs_option_repair = go_escape_string($_POST['check_fcs_option_repair']);
  if($check_fcs_option_repair !=1) $check_fcs_option_repair = 0;
  $check_fcs_option_replace = go_escape_string($_POST['check_fcs_option_replace']);
  if($check_fcs_option_replace !=1) $check_fcs_option_replace = 0;
  if($check_fcs_option_repair==1) $fcs_option = "repair_getdone";
  if($check_fcs_option_replace==1) $fcs_option = "replace_getdone";
  
  $sddone = $_POST['sddone'];
  $defdone = $_POST['defdone'];
	
  if($fcs_option){
	$sql = "INSERT into am_leakcheck_extras(leak_id, type, checked) values('$leak_id', 1, '$fcs_option')";
	executeupdate($sql);
	
	$sql = "SELECT email from users where enabled=1 and master_id='" . $SESSION_VIEW_MASTER_ID . "'";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $to_fcs_option .= $record['email'] . ",";
	}
	//$to_fcs_option = "jwert@encitegroup.com";
	$message = $prospect_company_name . " has chosen ";
	if($fcs_option=="repair_getdone") $subject_extra = "Remedial Repairs and Monitoring";
	if($fcs_option=="replace_getdone") $subject_extra= "Complete Removal and Replacement";
	$message .= $subject_extra;
	$message .= " for the property:<br><br>$site_name.";
	$message .= "<br>$address<br>$city, $state $zip";
	$subject = $prospect_company_name . " - " . $subject_extra;
	$headers = "Content-type: text/html; charset=iso-8859-1\n";
    $headers .= "From: $mail_from_name <$mail_from_email>\n";
    $headers .= "Return-Path: $mail_from_email\n";  // necessary for some emails such as aol
	email_q($to_fcs_option, $subject, $message, $headers);
	
  }
	
  if(is_array($sddone)){
	for($x=0;$x<sizeof($sddone);$x++){
	  $sql = "INSERT into am_leakcheck_extras(leak_id, type, source_id) values('$leak_id', 2, '" . $sddone[$x] . "')";
	  executeupdate($sql);
	}
  }
	
  if(is_array($defdone)){
    $other_cost = 0;
	for($x=0;$x<sizeof($defdone);$x++){
	  //$sql = "INSERT into am_leakcheck_extras(leak_id, type, source_id) values('$leak_id', 3, '" . $defdone[$x] . "')";
	  $sql = "UPDATE sections_def set get_done_leak_id='$leak_id' where def_id='" . $defdone[$x] . "'";
	  executeupdate($sql);
	  $sql = "SELECT def, action, coordinates, cost from sections_def where def_id='" . $defdone[$x] . "'";
	  $result = executequery($sql);
	  $record = go_fetch_array($result);
	  $xdef = stripslashes($record['def']);
	  $xaction = stripslashes($record['action']);
	  $xcoordinates = stripslashes($record['coordinates']);
	  $xcost = stripslashes($record['cost']);
	  $other_cost += $cost;
	  $xdef = go_escape_string($xdef);
	  $xaction = go_escape_string($xaction);
	  $xcoordinates = go_escape_string($xcoordinates);
	  $sql = "INSERT into am_leakcheck_problems(leak_id, problem_desc, correction, def_id, from_app, coordinates) values('$leak_id', \"$xdef\", \"$xaction\", '" . $defdone[$x] . "', 0, \"$xcoordinates\")";
	  executeupdate($sql);
	}
	$sql = "UPDATE am_leakcheck set invoice_type='Billable - Contract', other_desc='Contract', other_cost='$other_cost' where leak_id='$leak_id'";
	executeupdate($sql);
  }
  
  $sendimmediately = 0;
  if($internal) $sendimmediately=1;
  if($priority_id==3) $sendimmediately=1;
  
  $sendimmediately = 1; // 2/22/10 Jim says to make all dispatches go without RO middle man
  
  /*
  $sql = "SELECT ro_email from am_contractor_info where cont_id='$am_contractor_id'";
  $ro_email = getsingleresult($sql);
  */
  // I want to switch all Leakcheck emails to use Leakcheck - General.  Make individual message in $message variable.
  
  if($sendimmediately){ // if emergency, run as normal.  If not, first send to RO admins
    /*
	if($internal){
	  $message = "You are being notified of a service dispatch in the following area:</strong>";
	  $message .= "<br><br>";
      $message .= "<a href=\"$url\">Click here to Acknowledge receipt of service dispatch and for more information.</a>";
	  
	}
	else {
      $message = "A Service Dispatch notification has been submitted for the following building:</strong><br>";
	  $message .= "Please view the FCS Dispatch Report for further information.";
	}
	*/
	$master_id = $SESSION_VIEW_MASTER_ID;
	include $UP_FCS . "sd_email.php";

	
	
	$message = "[SPECIAL]";
	$message .= "<br>Priority: $priority";
    $message .= "<br><br>";
    $message .= "Service Dispatch ID: #" . $leak_id . "<br>";
    $message .= "$prospect_company_name<br>";
    $message .= "$site_name<br>";
    $message .= "$location<br>";
    $message .= "$building_section";
    //$message .= "<br><br>";
    //$message .= "<a href=\"$url\">Click here to Acknowledge receipt of service dispatch and for more information.</a>";
  
    $message_raw = $message;
	$special = "You are being notified of a service dispatch in the following area:</strong>";
	$special .= "<br><br>";
    $special .= "<a href=\"$url\">Click here to Acknowledge receipt of service dispatch and for more information.</a>";
	$message = str_replace("[SPECIAL]", $special, $message_raw);
	
    $sql = "SELECT body from templates where name='Leakcheck - General'";
    $body_raw = stripslashes(getsingleresult($sql));
    $body = str_replace("[MESSAGE]", $message, $body_raw);
  
    if($internal){
	  $subject = "Service Dispatch - $priority_name - $site_name - $city, $state";
	}
	else {
	  $subject = "Service Dispatch - $priority_name - $prospect_company_name - $site_name - $city, $state";
	}
    $headers = "Content-type: text/html; charset=iso-8859-1\n";
    $headers .= "From: $mail_from_name <$mail_from_email>\n";
    $headers .= "Return-Path: $mail_from_email\n";  // necessary for some emails such as aol
	$headers .= "Bcc: $fcs_email, $ro_email\n";
  
  

	$body = str_replace("[MESSAGE]", $table, $body_raw);
    if($demo==0){
      email_q("", $subject, $body, $headers);
	  //email_q("jwert@encitegroup.com", $subject, "Sent to:<br>$ro_email", $headers);
	}

    if($demo_email) email_q($demo_email, $subject, $body, $headers);
	
	
  } // end if send immediately
  
  /*
  $sql = "SELECT warranty_manufacturer, warranty_term, warranty_start, warranty_installer, warranty_contact, warranty_phone, warranty_number 
  from info_operations where property_id='$property_id' and warranty_manufacturer != '' order by 
  bid_id desc limit 1";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $warranty_manufacturer = stripslashes($record['warranty_manufacturer']);
  $warranty_term = stripslashes($record['warranty_term']);
  $warranty_start = stripslashes($record['warranty_start']);
  $warranty_installer = stripslashes($record['warranty_installer']);
  $warranty_contact = stripslashes($record['warranty_contact']);
  $warranty_phone = stripslashes($record['warranty_phone']);
  $warranty_number = stripslashes($record['warranty_number']);
  if($warranty_manufacturer != ""){
    $sql = "UPDATE am_leakcheck set invoice_type='Billable - Warranty', bt_manufacturer=\"$warranty_manufacturer\", 
	bt_term=\"$warranty_term\", bt_start=\"$warranty_start\", bt_installer=\"$warranty_installer\", bt_contact=\"$warranty_contact\", 
	bt_phone=\"$warranty_phone\" where leak_id='$leak_id'";
	executeupdate($sql);
  }

  */
}

if($demo) meta_redirect("welcome.php?section_type=$section_type&view=leakcheck");

if($internal){
  $_SESSION['sess_msg'] = "A Service Dispatch has been sent to your Internal Resource.";
}
else {
  $_SESSION['sess_msg'] = "A Service Dispatch has been sent to your Contractor.";
}

$force_internal = $_POST['force_internal'];
if($force_internal==1){
  $_SESSION['sess_msg'] = "";
  meta_redirect($UP_FCS . "toolbox.php?go=fcs_sd_report_view.php*leak_id=$leak_id");
}

meta_redirect("view_property.php?section_type=$section_type&property_id=$property_id");
?>