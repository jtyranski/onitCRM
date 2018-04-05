<?php
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
require_once("includes/functions.php");
$doc_id = $_GET['doc_id'];

$sql = "SELECT send_unsigned from document_proposal_fcs where id='$doc_id'";
$send_unsigned = getsingleresult($sql);

if($send_unsigned==0) include "public_proposal_fcs_pdf_signed.php";

$sql = "SELECT check1, check2, name, email, def_selected, section_id, subtotal, total, user_id, intro_credit, multisection, 
date_format(sign_date, \"%m/%d/%Y %r\") as sign_date_pretty, property_id, signature_image
from document_proposal_fcs where id='$doc_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$check1 = stripslashes($record['check1']);
$check2 = stripslashes($record['check2']);
$name = stripslashes($record['name']);
$email = stripslashes($record['email']);
$def_selected = stripslashes($record['def_selected']);
$sign_date_pretty = stripslashes($record['sign_date_pretty']);
$section_id = stripslashes($record['section_id']);
$subtotal = stripslashes($record['subtotal']);
$total = stripslashes($record['total']);
$ro_user_id = $record['user_id'];
$intro_credit = stripslashes($record['intro_credit']);
$multisection = stripslashes($record['multisection']);
$property_id = stripslashes($record['property_id']);
$signature_image = stripslashes($record['signature_image']);

$filename_signed = "PROPOSAL_SIGNED_" . $doc_id . ".pdf";
include 'mail_attachment.php';

$email_body = "Attached to this email you will find a copy of the repair proposal.";

$sql = "SELECT concat(firstname, ' ', lastname) as fullname, email, office, extension, title, cellphone, signature
	from users where user_id='" . $ro_user_id . "'";
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
	$mail_user_signature = $record['signature'];
	
	if($email_include_signature){
	  if($mail_user_signature != ""){
	    $signature = "<img src=\"" . $SITE_URL . "uploaded_files/headshots/" . $mail_user_signature . "\">";
	  }
	  else {
	    $signature = "Respectfully,<br>";
	    $signature .= $mail_user_name . "<br>";
	    if($mail_user_title != "") $signature .= $mail_user_title . "<br>";
	    $signature .= "<a href='mailto:" . $mail_user_email . "'>" . $mail_user_email . "</a><br>";
	    $signature .= "Office: " . $mail_user_office;
	    if($mail_user_extension != "") $signature .= " Ext: " . $mail_user_extension;
	    $signature .= "<br>";
	    if($mail_user_cellphone != "") $signature .= "Cell: " . $mail_user_cellphone . "<br>";

	  }
	}
	else {
	  $signature = "";
	}
$email_body .= "<br><br>" . $signature;

$subject = "$MASTER_NAME Proposal";
$headers = "From: $mail_user_name <$mail_user_email>\n";
$headers .= "Return-Path: ". $mail_user_email ."\n";  // necessary for some emails such as aol
$headers .= "Bcc: $email";
	  
//email_q($mail_user_email, $subject, $body, $headers);
if($send_unsigned==0){
  email_q($mail_user_email, $subject, $email_body, $headers, 'uploaded_files/proposals/' . $filename_signed);

  $sql = "INSERT into notes(user_id, property_id, prospect_id, date, event, regarding,  proposal) values('$ro_user_id', '$property_id', '$prospect_id', 
  now(), 'Note', 'Proposal', \"$filename_signed\")";
  executeupdate($sql);
  
  
  
  //****************************************************************************************************************
  //*  create a service dispatch ticket of New Project, with contract total of selected defs
  //****************************************************************************************************************
  
  $sql = "SELECT prospect_id from properties where property_id='$property_id'";
  $prospect_id = getsingleresult($sql);
  $sql = "SELECT master_id from prospects where prospect_id='$prospect_id'";
  $master_id = getsingleresult($sql);
  
  $sql = "SELECT groups, subgroups from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$property['groups'] = go_reg_replace(",", "", stripslashes($record['groups']));
$property['subgroups'] = go_reg_replace(",", "", stripslashes($record['subgroups']));
$USEGROUP = 0;
if($property['groups'] != "" && $property['groups'] !=0) $USEGROUP = $property['groups'];
if($property['subgroups'] != "" && $property['subgroups'] != 0) $USEGROUP = $property['subgroups'];

  $priority_id=1; // force the lowest priority for this group of get done items
  $sql = "SELECT priority" . $priority_id . "_rate from master_list where master_id='" . $master_id . "'";
  $labor_rate = getsingleresult($sql);
  if($USEGROUP !=0 && $USEGROUP != ""){
    $sql = "SELECT priority" . $priority_id . "_rate from groups where id='" . $USEGROUP . "'";
    $labor_rate = getsingleresult($sql);
  }
  
  $x_priority_id = $priority_id;
  if($x_priority_id==1 || $x_priority_id==4) $x_priority_id="";
  $sql = "SELECT nte_amount, labor_rate" . $x_priority_id . " as lr, company_name, address, city, state, zip, 
  hours_of_operation, invoice_requirements, checkin_procedure, checkout_procedure from prospects where prospect_id='" . $prospect_id . "'";
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
  
  
  $sql = "SELECT concat(firstname, ' ', lastname) as fullname, phone from contacts where prospect_id='" . $prospect_id . "' 
  order by id limit 1";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $contact['fullname'] = stripslashes($record['fullname']);
  $contact['phone'] = stripslashes($record['phone']);
  
  $billto = "";
  //$billto = $contact['fullname'] . "\n";
  $billto .= $company['name'] . "\n";
  $billto .= $company['address'] . "\n";
  $billto .= $company['city'] . ", " . $company['state'] . " " . $company['zip'];
  //$billto .= $contact['phone'];
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
  bill_to, bt_manufacturer, bt_installer, bt_term, bt_start, bt_contact, bt_phone, travel_rate, billto, resource_due_date, contact_id, additional_notes, status, lastupdate) values(
  '$property_id', '$section_id', '1', \"$notes\", \"$code\", now(), 
  '" . $prospect_id . "', '$section_type', '$demo', \"$demo_email\", \"$priority_id\", '$internal_id', 
  '$silent_mode', '$nte', '$nte_amount', '$labor_rate', 
  \"$bill_to\", \"$bt_manufacturer\", \"$bt_installer\", \"$bt_term\", \"$bt_start\", \"$bt_contact\", \"$bt_phone\", \"$labor_rate\", \"$billto\", now(), \"$contact_id\", \"$additional_notes\",
  'New Project', now())";
  executeupdate($sql);
  
  $new_leak_id = go_insert_id();
  $sql = "UPDATE am_leakcheck set invoice_id='$new_leak_id' where leak_id='$new_leak_id'";
  executeupdate($sql);
  $defdone = explode(",", $def_selected);
  
    $other_cost = 0;
	for($x=0;$x<sizeof($defdone);$x++){
	  if($defdone[$x]=="") continue;
	  //$sql = "INSERT into am_leakcheck_extras(leak_id, type, source_id) values('$leak_id', 3, '" . $defdone[$x] . "')";
	  $sql = "UPDATE sections_def set get_done_leak_id='$new_leak_id' where def_id='" . $defdone[$x] . "'";
	  executeupdate($sql);
	  $sql = "SELECT def, action, coordinates, cost from sections_def where def_id='" . $defdone[$x] . "'";
	  $result = executequery($sql);
	  $record = go_fetch_array($result);
	  $xdef = stripslashes($record['def']);
	  $xaction = stripslashes($record['action']);
	  $xcoordinates = stripslashes($record['coordinates']);
	  $xcost = stripslashes($record['cost']);
	  $other_cost += $xcost;
	  $xdef = go_escape_string($xdef);
	  $xaction = go_escape_string($xaction);
	  $xcoordinates = go_escape_string($xcoordinates);
	  $sql = "INSERT into am_leakcheck_problems(leak_id, problem_desc, correction, def_id, from_app, coordinates) values('$new_leak_id', \"$xdef\", \"$xaction\", '" . $defdone[$x] . "', 0, \"$xcoordinates\")";
	  executeupdate($sql);
	}
	$sql = "UPDATE am_leakcheck set invoice_type='Billable - Contract', other_desc='Contract', other_cost='$other_cost' where leak_id='$new_leak_id'";
	executeupdate($sql);
	$sql = "INSERT into am_leakcheck_othercost(leak_id, description, cost) values('$new_leak_id', 'Contract', '$other_cost')";
	executeupdate($sql);
	
	
	
	
	
}

if($send_unsigned==1){
  include "public_proposal_fcs_pdf_unsigned.php";
  $filename_unsigned = "PROPOSAL_UNSIGNED_" . $doc_id . ".pdf";
  $email_body = "Attached to this email you will find a copy of the repair proposal. Please print it out and sign it.";
  email_q($mail_user_email, $subject, $email_body, $headers, 'uploaded_files/proposals/' . $filename_unsigned);
  
  $sql = "INSERT into notes(user_id, property_id, prospect_id, date, event, regarding,  proposal) values('$ro_user_id', '$property_id', '$prospect_id', 
  now(), 'Note', 'Proposal', \"$filename_unsigned\")";
  executeupdate($sql);
}


  
$_SESSION['sess_msg'] = "Thank you for completing this form.";
meta_redirect($UP_FCSVIEW . "budget_matrix.php?property_id=$property_id");
  
?>
