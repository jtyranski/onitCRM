<?php
include "includes/functions.php";
//echo "<p>". $_POST['timezone1'] ."</p>";

$special_invoice = "";
$sql = "SELECT invoice_type from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$invoice_type = getsingleresult($sql);
if($invoice_type==2) $special_invoice = 2;
//$special_invoice = "";

$leak_id = go_escape_string($_POST['leak_id']);
$status = go_escape_string($_POST['status']);
$timezone1 = go_escape_string($_POST['timezone1']);
$priority_id = go_escape_string($_POST['priority_id']);
$old_status = go_escape_string($_POST['old_status']);
$send_email = go_escape_string($_POST['send_email']);
$servicemen_id = go_escape_string($_POST['servicemen_id']);
//$servicemen_id2 = go_escape_string($_POST['servicemen_id2']);
$rtm_customer = go_escape_string($_POST['rtm_customer']);
if($rtm_customer != 1) $rtm_customer = 0;
$rtm_customer_percent = go_escape_string($_POST['rtm_customer_percent']);

$section_id = go_escape_string($_POST['section_id']);
$invoice_type = go_escape_string($_POST['invoice_type']);
$nte_amount = go_escape_string($_POST['nte_amount']);
$contract_amount = go_escape_string($_POST['contract_amount']);
$bill_to = go_escape_string($_POST['bill_to']);
$bt_manufacturer = go_escape_string($_POST['bt_manufacturer']);
$bt_installer = go_escape_string($_POST['bt_installer']);
$bt_term = go_escape_string($_POST['bt_term']);
$bt_start = go_escape_string($_POST['bt_start']);
$bt_contact = go_escape_string($_POST['bt_contact']);
$bt_phone = go_escape_string($_POST['bt_phone']);
$promotional_type = go_escape_string($_POST['promotional_type']);
$pt_credit = go_escape_string($_POST['pt_credit']);
$pt_project_name = go_escape_string($_POST['pt_project_name']);
$bt_rate = go_escape_string($_POST['bt_rate']);
$bt_materials = go_escape_string($_POST['bt_materials']);
$bt_extra_cost = go_escape_string($_POST['bt_extra_cost']);
$contract_cost = go_escape_string($_POST['contract_cost']);
$contract_amount = go_escape_string($_POST['contract_amount']);

$po_number = go_escape_string($_POST['po_number']);


$nte = go_escape_string($_POST['nte']);
$labor_rate = go_escape_string($_POST['labor_rate']);
$gtotal_hours = go_escape_string($_POST['gtotal_hours']);
$materials = go_escape_string($_POST['materials']);
$extra_cost = go_escape_string($_POST['extra_cost']);
$sub_total = go_escape_string($_POST['sub_total']);
$discount_amount = go_escape_string($_POST['discount_amount']);
$promotional_amount = go_escape_string($_POST['promotional_amount']);
$tax_amount = go_escape_string($_POST['tax_amount']);
$invoice_total = go_escape_string($_POST['invoice_total']);
$credit = go_escape_string($_POST['credit']);
$withholding_amount = go_escape_string($_POST['withholding_amount']);
$rtm = go_escape_string($_POST['rtm']);
$rtm_billing = go_escape_string($_POST['rtm_billing']);
$rtm_amount = go_escape_string($_POST['rtm_amount']);
$rtm_discount = go_escape_string($_POST['rtm_discount']);
$rtm_materials = go_escape_string($_POST['rtm_materials']);
$eta_message = go_escape_string($_POST['eta_message']);
$nrp_eyes_only = go_escape_string($_POST['nrp_eyes_only']);

$payment = go_escape_string($_POST['payment']);

$use_subcontractor = go_escape_string($_POST['use_subcontractor']);
$subcontractor_amount = go_escape_string($_POST['subcontractor_amount']);

$silent_mode = go_escape_string($_POST['silent_mode']);
if($silent_mode != 1) $silent_mode = 0;

$check_inout = go_escape_string($_POST['check_inout']);
if($check_inout != 1) $check_inout = 0;
$check_ncp = go_escape_string($_POST['check_ncp']);
if($check_ncp != 1) $check_ncp = 0;
$check_assess = go_escape_string($_POST['check_assess']);
if($check_assess != 1) $check_assess = 0;
$check_gaf = go_escape_string($_POST['check_gaf']);
if($check_gaf != 1) $check_gaf = 0;
$check_other = go_escape_string($_POST['check_other']);
if($check_other != 1) $check_other = 0;
$check_recall = go_escape_string($_POST['check_recall']);
if($check_recall != 1) $check_recall = 0;
$check_approved_repair = go_escape_string($_POST['check_approved_repair']);
if($check_approved_repair != 1) $check_approved_repair = 0;


$review_def = go_escape_string($_POST['review_def']);
if($review_def != 1) $review_def = 0;
$app_data = go_escape_string($_POST['app_data']);
if($app_data != 1) $app_data = 0;
$manual_data = go_escape_string($_POST['manual_data']);
if($manual_data != 1) $manual_data = 0;

if(go_reg("\*", $servicemen_id)){
  $resource_id = go_reg_replace("\*", "", $servicemen_id);
  $servicemen_id = 0;
}
else {
  $resource_id = 0;
}

if($servicemen_id != 0){
  $sql = "SELECT resource_id from users where user_id='$servicemen_id'";
  $resource_id = getsingleresult($sql);
  if($resource_id != 0){
    $discount_id = $priority_id;
	if($discount_id==1) $discount_id = "";
	$sql = "SELECT labor_rate" . $discount_id . " as lr, discount from prospects_resources where prospect_id='$resource_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$lr = $record['lr'];
	$xd = $record['discount'];
	if($labor_rate=="" || $labor_rate==0) $labor_rate = $lr;
	/*
	if($discount_amount=="" || $discount_amount==0){
	  $discount_amount = $subtotal * ($xd / 100);
	}
	*/
  }
}

if($rtm != 1) $rtm = 0;
if($nte != 1) $nte = 0;
if($use_subcontractor != 1) $use_subcontractor = 0;

/*
if($invoice_type=="RTM" || $invoice_type=="RTM - Prevent") {
  $sub_total = $rtm_amount;
  $discount_amount = $rtm_discount;
  $materials = $rtm_materials;
  $invoice_total = $sub_total - $discount_amount;
  $extra_cost = $sub_total; // for use on individual serviceman report

}

if($invoice_type=="2 Year" || $invoice_type=="Warranty" || $invoice_type=="Billable - Warranty"){
  $materials = $bt_materials;
  $labor_rate = $bt_rate;
  $extra_cost = $bt_extra_cost;
  $sub_total = ($gtotal_hours * $labor_rate) + $extra_cost;
  $invoice_total = $sub_total;

}
*/

$serviceman2_labor_rate = go_escape_string($_POST['serviceman2_labor_rate']);
$serviceman_helper = go_escape_string($_POST['serviceman_helper']);
if($serviceman_helper != 1) $serviceman_helper=0;
if($serviceman_helper == 0) $serviceman2_labor_rate = 0;

$serviceman1_materials = go_escape_string($_POST['serviceman1_materials']);
$serviceman2_materials = go_escape_string($_POST['serviceman2_materials']);


$submit1 = $_POST['submit1'];
$submit2 = $_POST['submit2'];
if($submit2 != "") $submit1 = $submit2; // trying to force a submit on changing priority

$travel = $_POST['travel'];

$fix_contractor = go_escape_string($_POST['fix_contractor']);
$billto = go_escape_string($_POST['billto']);
$additional_notes = go_escape_string($_POST['additional_notes']);
$notes = go_escape_string($_POST['notes']);
$app_problem_desc = go_escape_string($_POST['app_problem_desc']);
$app_correction = go_escape_string($_POST['app_correction']);

  $eta_date_pretty = go_escape_string($_POST['eta_date_pretty']);
  $hour = go_escape_string($_POST['hour']);
  $minute = go_escape_string($_POST['minute']);
  $ampm = go_escape_string($_POST['ampm']);
  if($ampm == "PM" && $hour < 12) $hour += 12;
  if($ampm == "AM" && $hour == 12) $hour = 0;
  $hour = $hour - $timezone1;
  if($hour < 0) $hour += 24;
  $timepretty = $hour . ":" . $minute . ":00";
  $date_parts = explode("/", $eta_date_pretty);
  $eta_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
  $eta_date .= " " . $timepretty;

$resource_due_date = go_escape_string($_POST['resource_due_date']);
$date_parts = explode("/", $resource_due_date);
$resource_due_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];

if($status=="Closed Out" && $old_status=="Invoiced"){
  if($payment < $invoice_total){
    $status = "Invoiced";
	$_SESSION['sess_msg'] = "Status cannot be closed out unless PAYMENT equals Invoice Total.";
  }
}
if($submit1=="Process Invoice" || $submit1=="Send Client Invoice") $status = "Invoiced";

$updatets = go_escape_string($_POST['updatets']);
if($updatets != 1) $updatets = 0;

$status_array = array("Dispatched", "Acknowledge", "Arrival ETA", "In Progress", "Resolved", "Confirmed", "Invoiced", "Closed Out");
$ts_array = array("dispatch_date", "acknowledge_date", "eta_date", "inprogress_date", "fix_date", "confirm_date", "invoice_date", "closed_date");


$key = array_search($status, $status_array);


$x = $key + 1;

for($y=$x; $y<sizeof($status_array); $y++){
  $reset .= $ts_array[$y] . " = '', ";
}

if($status=="Arrival ETA"){
  $reset .= "eta_date = '$eta_date', mobile_display=1 ";
}
else {
  $reset .= $ts_array[$key] . " = now() ";
}

if($updatets == 0){
  $reset = " display=1 ";
}


// items from new invoice report
$withholding_percent = go_escape_string($_POST['withholding_percent']);
$tax_percent = go_escape_string($_POST['tax_percent']);
$desc_work_performed = go_escape_string($_POST['desc_work_performed']);
$include_docs = go_escape_string($_POST['include_docs']);

if($include_docs != 1) $include_docs=0;

$billto = go_reg_replace("ZZZZ", "\n", $billto);
$desc_work_performed = go_reg_replace("ZZZZ", "\n", $desc_work_performed);

$include_bid = go_escape_string($_POST['include_bid']);
if($include_bid != 1) $include_bid = 0;
$bid_value = go_escape_string($_POST['bid_value']);
$bid_value = go_reg_replace("\,", "", $bid_value);

$bid_scope = go_escape_string($_POST['bid_scope']);

$rr_upsell = go_escape_string($_POST['rr_upsell']);
$rr_upsell = go_reg_replace("\,", "", $rr_upsell);

if($submit1 != ""){
  $sql = "UPDATE am_leakcheck set `status`='$status', fix_contractor=\"$fix_contractor\", ";
  if($servicemen_id != 0) $sql .= "servicemen_id='$servicemen_id', ";
  $sql .= " 
  invoice_type=\"$invoice_type\", nte_amount=\"$nte_amount\", 
  contract_amount=\"$contract_amount\", bill_to=\"$bill_to\", bt_manufacturer=\"$bt_manufacturer\", 
  bt_installer=\"$bt_installer\", bt_term=\"$bt_term\", bt_start=\"$bt_start\", bt_contact=\"$bt_contact\", bt_phone=\"$bt_phone\", 
  promotional_type=\"$promotional_type\", 
  pt_credit=\"$pt_credit\", pt_project_name=\"$pt_project_name\", 
  nte=\"$nte\", materials=\"$materials\", extra_cost=\"$extra_cost\", labor_rate=\"$labor_rate\", 
  sub_total=\"$sub_total\", discount_amount=\"$discount_amount\", promotional_amount=\"$promotional_amount\", tax_amount=\"$tax_amount\", rtm=\"$rtm\", 
  invoice_total=\"$invoice_total\", gtotal_hours='$gtotal_hours', rtm_billing=\"$rtm_billing\", rtm_amount='$rtm_amount', 
  billto=\"$billto\", payment=\"$payment\", rtm_customer='$rtm_customer', rtm_customer_percent='$rtm_customer_percent', 
  eta_message=\"$eta_message\", use_subcontractor='$use_subcontractor', subcontractor_amount=\"$subcontractor_amount\", 
  silent_mode='$silent_mode', serviceman1_materials = '$serviceman1_materials', serviceman2_materials = '$serviceman2_materials',
  serviceman2_labor_rate = '$serviceman2_labor_rate', serviceman_helper='$serviceman_helper', priority_id='$priority_id', 
  section_id=\"$section_id\", review_def='$review_def', app_data='$app_data', manual_data='$manual_data', additional_notes=\"$additional_notes\", resource_id='$resource_id', 
  withholding_amount=\"$withholding_amount\", resource_due_date=\"$resource_due_date\", notes=\"$notes\", credit=\"$credit\", 
  check_inout='$check_inout', check_ncp='$check_ncp', check_assess='$check_assess', check_gaf='$check_gaf', check_other='$check_other', po_number=\"$po_number\", 
  check_recall='$check_recall', check_approved_repair='$check_approved_repair', nrp_eyes_only=\"$nrp_eyes_only\", contract_amount=\"$contract_amount\", 
  ";
  $sql .= "withholding_percent=\"$withholding_percent\", tax_percent=\"$tax_percent\", desc_work_performed=\"$desc_work_performed\", include_docs='$include_docs', ";
  
  $sql .= "$reset where leak_id='$leak_id'";
//echo $sql;
  //exit;
  executeupdate($sql);
  
  if($invoice_type=="Billable - Contract"){
    $sql = "UPDATE am_leakcheck set labor_time=0, travel_time=0, other_desc='Contract', other_cost='$contract_cost' where leak_id='$leak_id'";
	executeupdate($sql);
  }
  
  if($special_invoice==""){  
  $sql = "UPDATE am_leakcheck_time set travel=0 where leak_id='$leak_id' and from_app=0";
  executeupdate($sql);
  if(is_array($travel)){
    for($x=0;$x<sizeof($travel);$x++){
	  $sql = "UPDATE am_leakcheck_time set travel=1 where leak_id='$leak_id' and id='" . $travel[$x] . "'";
	  executeupdate($sql);
	}
  }
  }
  
  $sql = "SELECT property_id, section_id, notes from am_leakcheck where leak_id='$leak_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $property_id = $record['property_id'];
  $section_id = $record['section_id'];
  $notes = go_escape_string($record['notes']);
  
  $sql = "UPDATE properties set timezone=\"$timezone1\" where property_id='$property_id'";
	executeupdate($sql);
  
  $billto_drop = go_escape_string(ucfirst($_POST['billto_drop']));
  if($billto_drop != ""){
    $sql = "SELECT property_id from am_leakcheck where leak_id='$leak_id'";
	$property_id = getsingleresult($sql);
	$sql = "UPDATE properties set billto_default='$billto_drop' where property_id='$property_id'";
	executeupdate($sql);
  }
  
  if(is_uploaded_file($_FILES['extrafile']['tmp_name'])){
    $sql = "INSERT into am_leakcheck_photos(leak_id, additional) values (
	'$leak_id', '3')";
	executeupdate($sql);
	$photo_id=go_insert_id();
	
	$ext = explode(".", $_FILES['extrafile']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['extrafile']['tmp_name'], $UP_FCSVIEW . "uploaded_files/leakcheck/". $filename);
	
	$sql = "UPDATE am_leakcheck_photos set photo='$filename'
	where photo_id='$photo_id' and leak_id='$leak_id'";
	executeupdate($sql);
  }
  
  if (is_uploaded_file($_FILES['imagefront']['tmp_name'])){
	if(is_image_valid($_FILES['imagefront']['name']))
    {
	$sql = "SELECT property_id from am_leakcheck where leak_id='$leak_id'";
	$property_id = getsingleresult($sql);
	$ext = explode(".", $_FILES['imagefront']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['imagefront']['tmp_name'], $UPLOAD . "temp/". $filename);
  	resizeimage($UPLOAD . "temp/$filename", $UPLOAD . "properties/", $PROPERTY_SIZE);
  	@unlink($UPLOAD . "temp/". $filename);
	
	$sql = "UPDATE properties set image_front='$filename' where property_id='$property_id'";
	executeupdate($sql);
    }
  }
  
  
  
  if (is_uploaded_file($_FILES['proofdoc']['tmp_name'])){
	$ext = explode(".", $_FILES['proofdoc']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['proofdoc']['tmp_name'], $UPLOAD . "proofdoc/". $filename);
	
	$sql = "UPDATE am_leakcheck set proofdoc='$filename' where leak_id='$leak_id'";
	executeupdate($sql);
  }
  
   // update problem/correction info
  $problem_ids = $_POST['problem_ids'];
  $problem_descs = $_POST['problem_descs'];
  $corrections = $_POST['corrections'];
  $problem_names = $_POST['problem_names'];
  if(is_array($problem_ids)){
    for($x=0;$x<sizeof($problem_ids);$x++){
	  $problem_id = $problem_ids[$x];
	  $problem_desc = go_escape_string($problem_descs[$x]);
	  $problem_name = go_escape_string($problem_names[$x]);
	  $correction = go_escape_string($corrections[$x]);
	  $sql = "UPDATE am_leakcheck_problems set problem_desc=\"$problem_desc\", correction=\"$correction\", problem_name=\"$problem_name\" where problem_id='$problem_id' and leak_id='$leak_id'";
	  executeupdate($sql);
	}
  }
  
  $sql = "SELECT dispatch_date, eta_date, inprogress_date, fix_date, confirm_date, invoice_date, closed_date from am_leakcheck where leak_id='$leak_id'";
  $result = executequery($sql);
  $recordmax = go_fetch_array($result);
  $last_update = max($recordmax);
  $sql = "UPDATE am_leakcheck set lastupdate='$last_update' where leak_id='$leak_id'";
  executeupdate($sql);
  
}

$sql = "SELECT count(a.leak_id) from am_leakcheck a, prospects b where a.prospect_id=b.prospect_id and b.master_id='" . $SESSION_MASTER_ID . "' and a.status='Dispatched'";
$test = getsingleresult($sql);
if($test==0){
  $sql = "UPDATE users set show_new_dispatch=0 where master_id='" . $SESSION_MASTER_ID . "'";
  executeupdate($sql);
}

if($submit1=="Rename Document"){
  $rename_file_photo_id = go_escape_string($_POST['rename_file_photo_id']);
  $rename_file_photo_name = go_escape_string($_POST['rename_file_photo_name']);
  $sql = "UPDATE am_leakcheck_photos set docname=\"$rename_file_photo_name\" where photo_id=\"$rename_file_photo_id\" and leak_id=\"$leak_id\"";
  executeupdate($sql);
}


// if invoice, add as opportunity
if($status=="Invoiced" || $status=="Closed Out"){
  $sql = "SELECT prospect_id, property_id, servicemen_id, resource_id from am_leakcheck where leak_id='$leak_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $prospect_id = $record['prospect_id'];
  $property_id = $record['property_id'];
  $opp_user_id = $record['servicemen_id'];
  $opp_resource_id = $record['resource_id'];
  
  if($opp_user_id==0){
    if($opp_resource_id != 0){
	  $sql = "SELECT user_id from users where enabled=1 and resource_id='$opp_resource_id' order by user_id limit 1";
	  $opp_user_id = getsingleresult($sql);
	}
	else{
	  $sql = "SELECT user_id from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and servicemen=1 and resource=0 order by user_id limit 1";
	  $opp_user_id = getsingleresult($sql);
	}
  }
  
  
  $sql = "SELECT opp_id from opportunities where prospect_id='$prospect_id' and property_id='$property_id' and user_id='$opp_user_id' and opp_product_id=-3 and product='Leak $leak_id'";
  $test = getsingleresult($sql);
  if($test==""){
    $sql = "INSERT into opportunities(prospect_id, property_id, user_id, opp_product_id, product, description, amount, status, lastaction, sold_date) values(
	'$prospect_id', '$property_id', '$opp_user_id', -3, 'Leak $leak_id', 'Service Dispatch $leak_id', '$invoice_total', 'Sold', now(), now())";
	executeupdate($sql);
  }
}


// Need to send out notifcations for ETA or In Progress

$sendmessage = 0;
if($status != $old_status){
  $sendmessage = 1;
  $subject = "Service Dispatch Update";
}

if($status=="Arrival ETA"){
  $sql = "SELECT date_format(eta_date, \"%m/%d/%Y %r\") as datepretty from am_leakcheck where leak_id='$leak_id'";
  $eta_date = getsingleresult($sql);
  $sendmessage = 1;
  $message = "An arrival ETA has been established for the Service Dispatch at your location.<br><br>ETA: $eta_date<br><br>";
  $subject = "Service Dispatch Arrival ETA";
}
if($status=="In Progress"){
  $sql = "SELECT date_format(inprogress_date, \"%m/%d/%Y %r\") as datepretty from am_leakcheck where leak_id='$leak_id'";
  $inprogress_date = getsingleresult($sql);
  $sendmessage = 1;
  $message = "As of $inprogress_date, NRP has identified that work has begun on the Service Dispatch at your location.<br><br>";
  $subject = "Service Dispatch In Progress";
}

if($status=="Invoiced"){
  $sql = "SELECT asset_id from asset_management where leak_id='$leak_id'";
  $test = getsingleresult($sql);
  if($test==""){
    $sql = "INSERT into asset_management(property_id, section_id, date, item, description, expense_type, amount, leak_id) values(
	'$property_id', '$section_id', now(), 'Work Order', \"$notes\", 'Expense', '$invoice_total', '$leak_id')";
	executeupdate($sql);
  }
}

//if($leak_id==1660) $sendmessage = 1; // test

if($sendmessage==1 && $send_email==1){
  $sql = "SELECT b.site_name, b.city, b.state, a.priority, a.eta_message from am_leakcheck a, properties b where a.property_id=b.property_id 
  and a.leak_id='$leak_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $site_name = stripslashes($record['site_name']);
  $city = stripslashes($record['city']);
  $state = stripslashes($record['state']);
  $priority = stripslashes($record['priority']);
  $eta_message = stripslashes($record['eta_message']);
  
  $message .= "$site_name<br>$city, $state<br>Dispatch ID: $leak_id<br>Priority: $priority";
  
  $sql = "SELECT dispatch_from_email from master_list where master_id='" . $SESSION_MASTER_ID . "'";
  $mail_from_email = stripslashes(getsingleresult($sql));
  
  //$mail_from_email = "service@fcscontrol.com";
  $mail_from_name = $MASTER_NAME;
  
  $master_id = $SESSION_MASTER_ID;
  include "sd_email.php";
  $sql = "SELECT body from templates where name='Leakcheck - General'";
  $body = stripslashes(getsingleresult($sql));
  $body = str_replace("[MESSAGE]", $table, $body);
  

  $headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $mail_from_name <$mail_from_email>\n";
  $headers .= "Return-Path: $mail_from_email\n";  // necessary for some emails such as aol
  $headers_fcs = $headers . "Bcc: $fcs_email\n";
  $headers_ro = $headers . "Bcc: $ro_email\n";
  
  if($fcs_email != "") email_q("", $subject, $body, $headers_fcs);
  
  $ro_body = $body;
  $ro_body .= "<br><br>Internal ETA Notes:<br>" . nl2br($eta_message);
  $ro_body .= "<br>This message sent to following NRP view users: $fcs_email";
  if($ro_email != "") email_q("", $subject, $ro_body, $headers_ro);
}  

// update stage for opportunity
if($status=="Arrival ETA"  && ($invoice_type=="RTM" || $invoice_type=="RTM - Prevent")){
  $sql = "UPDATE opportunities set opp_stage_id=2 where property_id='$property_id' and product='Roof Management - Proactive' and display=1";
  executeupdate($sql);
}

// if confirmed or higher, mark any def's associated with this dispatch as complete
if($status=="Resolved" || $status=="Confirmed" || $status=="Invoiced" || $status=="Closed Out"){
  $sql = "UPDATE sections_def set complete=1 where leak_id='$leak_id'";
  executeupdate($sql);
  
  $sql = "UPDATE sections_def set complete=1 where get_done_leak_id='$leak_id'";
  executeupdate($sql);
  
  $sql = "SELECT date_format(invoice_due_date, \"%Y\") from am_leakcheck where leak_id='$leak_id'";
  $test = getsingleresult($sql);
  if($test=="0000"){
    $sql = "SELECT prospect_id from am_leakcheck where leak_id='$leak_id'";
    $prospect_id = getsingleresult($sql);
    $sql = "SELECT payment_terms from prospects where prospect_id='$prospect_id'";
	$payment_terms = remove_non_numeric(stripslashes(getsingleresult($sql)));
	if($payment_terms==""){
      $sql = "SELECT payment_terms from master_list where master_id='" . $SESSION_MASTER_ID . "'";
      $payment_terms = remove_non_numeric(stripslashes(getsingleresult($sql)));
	}
	if($payment_terms != ""){
	  $sql = "UPDATE am_leakcheck set invoice_due_date=date_add(confirm_date, interval $payment_terms day) where leak_id='$leak_id'";
	  executeupdate($sql);
	}
  }
}



$sql = "SELECT servicemen_id, text_serviceman from am_leakcheck where leak_id='$leak_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$sid = $record['servicemen_id'];
$text_serviceman = $record['text_serviceman'];
if($text_serviceman==0 && $sid != 0){
  $sql = "SELECT dispatch_from_email from master_list where master_id='" . $SESSION_MASTER_ID . "'";
  $from_email = getsingleresult($sql);
  
  $sql = "SELECT a.priority_id, b.site_name, b.address, b.city, b.state from am_leakcheck a, properties b where a.property_id=b.property_id and a.leak_id='$leak_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $priority_id = $record['priority_id'];
  $site_name = stripslashes($record['site_name']);
  $address = stripslashes($record['address']);
  $city = stripslashes($record['city']);
  $state = stripslashes($record['state']);
  $sql = "SELECT priority" . $priority_id . " from master_list where master_id='" . $SESSION_MASTER_ID . "'";
  $p = stripslashes(getsingleresult($sql));
  $message = "New Dispatch #" . $leak_id . ": $p, $site_name, $address, $city, $state";
  
  
  $sql = "SELECT a.cellphone, b.cell_extension from users a, cell_providers b where a.cell_id=b.cell_id and a.enabled=1 
  and a.cell_id != 0 and a.cellphone != '' and a.user_id='$sid'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $cellphone = remove_non_numeric(stripslashes($record['cellphone']));
    $cell_extension = stripslashes($record['cell_extension']);
  
    $text[] = $cellphone . "@" . $cell_extension;
  }
  if(is_array($text)){
    for($x=0;$x<sizeof($text);$x++){
	  email_q($text[$x], "", $message, "From:$from_email");
	  //echo $text[$x] . "<br>";
	}
  }
  $sql = "UPDATE am_leakcheck set text_serviceman=1 where leak_id='$leak_id'";
  executeupdate($sql);
  
 
}

/* I think this may have caused an unwanted resend, with resource_id of zero. I'm pulling it out. 12/9/2011 jw
$old_additional_notes = go_escape_string($_POST['old_additional_notes']);
if($additional_notes != $old_additional_notes){
  $sql = "SELECT open_resource from am_leakcheck where leak_id='$leak_id'";
  $open_resource = getsingleresult($sql);
  if($open_resource){
    meta_redirect("fcs_sd_report_resourceconfirm.php?leak_id=$leak_id&resource_id=$resource_id");
  }
}
*/

if($submit1=="Update Proposal / View Mode"){
  $def_id = $_POST['def_id'];
  $def = $_POST['def'];
  $action = $_POST['action'];
  $cost = $_POST['cost'];
  $def_name = $_POST['def_name'];
  $quantity = $_POST['quantity'];
  $upsell = $_POST['upsell'];
  
  if(is_array($def_id)){
    for($x=0;$x<sizeof($def_id);$x++){
	  $sql = "UPDATE sections_def set def=\"" . go_escape_string($def[$x]) . "\", action=\"" . go_escape_string($action[$x]) . "\", cost=\"" . go_escape_string($cost[$x]) . "\", 
	  name=\"" . go_escape_string($def_name[$x]) . "\", quantity=\"" . go_escape_string($quantity[$x]) . "\", upsell=\"" . go_escape_string($upsell[$x]) . "\"
	  where def_id='" . $def_id[$x] . "'";
	  executeupdate($sql);
	}
  }
  $_SESSION['edit_proposal'] = 0;
  meta_redirect("fcs_sd_report_view.php?leak_id=$leak_id#proposal");
}

if($submit1=="Send Confirmation" || $submit1=="Resend Confirmation"){
  meta_redirect("fcs_sd_report_resourceconfirm.php?leak_id=$leak_id&resource_id=$resource_id");
}
	
if($submit1=="Process Invoice" || $submit1=="Send Client Invoice") {
  $sql = "SELECT date_format(invoice_sent_date, \"%Y\") from am_leakcheck where leak_id='$leak_id'";
  $test = getsingleresult($sql);
  if($test=="0000"){
    $sql = "UPDATE am_leakcheck set invoice_sent_date=now() where leak_id='$leak_id'";
	executeupdate($sql);
  }
  
  meta_redirect("fcs_sd_invoice.php?leak_id=$leak_id");
}

if($submit1=="Get Proposal Done"){

  $sql = "SELECT prospect_id, property_id, servicemen_id, resource_id, ncp_spawned_from from am_leakcheck where leak_id='$leak_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $prospect_id = $record['prospect_id'];
  $property_id = $record['property_id'];
  $opp_user_id = $record['servicemen_id'];
  $opp_resource_id = $record['resource_id'];
  $ncp_spawned_from = $record['ncp_spawned_from'];
  
  if($opp_user_id==0){
    if($opp_resource_id != 0){
	  $sql = "SELECT user_id from users where enabled=1 and resource_id='$opp_resource_id' order by user_id limit 1";
	  $opp_user_id = getsingleresult($sql);
	}
	else{
	  $sql = "SELECT user_id from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and servicemen=1 and resource=0 order by user_id limit 1";
	  $opp_user_id = getsingleresult($sql);
	}
  }
  
  if($ncp_spawned_from != 0 && $ncp_spawned_from != ""){
    $sql = "SELECT opp_id from opportunities where prospect_id='$prospect_id' and property_id='$property_id' and user_id='$opp_user_id' and opp_product_id=-3 and product='Leak $ncp_spawned_from'";
    $test = getsingleresult($sql);
    if($test==""){
      $sql = "INSERT into opportunities(prospect_id, property_id, user_id, opp_product_id, product, description, amount, status, lastaction, sold_date) values(
	  '$prospect_id', '$property_id', '$opp_user_id', -3, 'Leak $ncp_spawned_from', 'Service Dispatch $ncp_spawned_from', '$invoice_total', 'Dead', now(), now())";
	  executeupdate($sql);
    }
	$new_invoice_id = "R-" . $ncp_spawned_from;
  }
  else {
    $new_invoice_id = "R-" . $leak_id;
  }
  
  $defdone = $_POST['defdone'];
  if(!(is_array($defdone))){
    $_SESSION['sess_msg'] = "Please select at least one deficiency from the proposal to Get Done.";
	meta_redirect("fcs_sd_report_view2.php?leak_id=$leak_id");
  }
  
  $sql = "SELECT prospect_id, property_id, user_id from am_leakcheck where leak_id='$leak_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $prospect_id = $record['prospect_id'];
  $property_id = $record['property_id'];
  $user_id = $record['user_id'];
  
  $code = uniqueTimeStamp();
      $counter = 0;
      while($counter < 4){
        $extra .= chr(rand(65, 90));
        $counter++;
      }
      $code .= $extra;
	  $priority_id=3;
	  $sql = "SELECT priority" . $priority_id . "_rate from master_list where master_id='" . $SESSION_MASTER_ID . "'";
  $labor_rate = getsingleresult($sql);
  
  $x_priority_id = $priority_id;
  if($x_priority_id==1 || $x_priority_id==4) $x_priority_id="";
  $sql = "SELECT nte_amount, labor_rate" . $x_priority_id . " as lr, company_name, address, city, state, zip from prospects where prospect_id='" . $prospect_id . "'";
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
  
  $sql = "SELECT concat(firstname, ' ', lastname) as fullname, phone from contacts where prospect_id='" . $prospect_id . "' 
  order by id limit 1";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $contact['fullname'] = stripslashes($record['fullname']);
  $contact['phone'] = stripslashes($record['phone']);

  $billto = $contact['fullname'] . "\n";
  $billto .= $company['name'] . "\n";
  $billto .= $company['address'] . "\n";
  $billto .= $company['city'] . ", " . $company['state'] . " " . $company['zip'] . "\n";
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
  bill_to, bt_manufacturer, bt_installer, bt_term, bt_start, bt_contact, bt_phone, travel_rate, billto, resource_due_date, spawned_from_leak_id, invoice_id, ncp_clickedonce) values(
  '$property_id', '$section_id', '" . $user_id . "', \"$notes\", \"$code\", now(), 
  '" . $prospect_id . "', 'roof', '0', \"$demo_email\", \"$priority_id\", '$internal_id', 
  '0', '$nte', '$nte_amount', '$labor_rate', 
  \"$bill_to\", \"$bt_manufacturer\", \"$bt_installer\", \"$bt_term\", \"$bt_start\", \"$bt_contact\", \"$bt_phone\", \"$labor_rate\", \"$billto\", now(), '$leak_id', '$new_invoice_id', 1)";
  executeupdate($sql);
  // set ncp_clickedonce to 1 because we don't want them generating another NCP from this proposal
  $new_leak_id = go_insert_id();
  
  $other_cost = 0;
  $total_upsell = 0;
  if(is_array($defdone)){
	for($x=0;$x<sizeof($defdone);$x++){
	  //$sql = "INSERT into am_leakcheck_extras(leak_id, type, source_id) values('$leak_id', 3, '" . $defdone[$x] . "')";
	  $sql = "UPDATE sections_def set get_done_leak_id='$new_leak_id' where def_id='" . $defdone[$x] . "'";
	  executeupdate($sql);
	  $sql = "SELECT def, action, coordinates, cost, upsell from sections_def where def_id='" . $defdone[$x] . "'";
	  $result = executequery($sql);
	  $record = go_fetch_array($result);
	  $xdef = stripslashes($record['def']);
	  $xaction = stripslashes($record['action']);
	  $xcoordinates = stripslashes($record['coordinates']);
	  $xdef = go_escape_string($xdef);
	  $xaction = go_escape_string($xaction);
	  $xcoordinates = go_escape_string($xcoordinates);
	  $cost = $record['cost'];
	  $upsell = $record['upsell'];
	  $other_cost += $cost;
	  $other_cost += $upsell;
	  $total_upsell += $upsell;
	  $sql = "INSERT into am_leakcheck_problems(leak_id, problem_desc, correction, def_id, from_app, coordinates) values('$new_leak_id', \"$xdef\", \"$xaction\", '" . $defdone[$x] . "', 0, \"$xcoordinates\")";
	  executeupdate($sql);
	}
	$sql = "UPDATE am_leakcheck set invoice_type='Billable - Contract', other_desc='Contract', contract_amount='$other_cost', upsell='$total_upsell' where leak_id='$new_leak_id'";
	executeupdate($sql);
  }
  // add an "other" item noting this is from a previous leak_id
  if($ncp_spawned_from != 0 && $ncp_spawned_from != ""){
    $sql = "SELECT invoice_id from am_leakcheck where leak_id='$ncp_spawned_from'";
	$invoice_id_spawned_from = stripslashes(getsingleresult($sql));
	$sql = "INSERT into am_leakcheck_othercost(leak_id, description, quantity, units, cost) values('$new_leak_id', 'Invoice $invoice_id_spawned_from', 1, 'EA', 0)";
	executeupdate($sql);
	$sql = "UPDATE am_leakcheck set ncp_showconnectlink=0 where leak_id='$ncp_spawned_from'";
	executeupdate($sql);
	
	$sql = "UPDATE am_leakcheck set archive=1 where leak_id='$ncp_spawned_from'";
	executeupdate($sql);
  }
  
  $sql = "UPDATE am_leakcheck set archive=1 where leak_id='$leak_id'";
  executeupdate($sql);
  $sql = "UPDATE am_leakcheck set allow_proposal_done = 0 where leak_id='$leak_id'";
  executeupdate($sql);
  
  $leak_id = $new_leak_id;
}

if($submit1=="Roof Replacement Edit Mode"){
  $_SESSION['rr_edit_mode'] = 1;
}

if($submit1=="Roof Replacement View Mode"){
  $sql = "UPDATE am_leakcheck set include_bid=\"$include_bid\", bid_value=\"$bid_value\", bid_scope=\"$bid_scope\", rr_upsell=\"$rr_upsell\" where leak_id='$leak_id'";
  executeupdate($sql);
  
  if (is_uploaded_file($_FILES['literature']['tmp_name'])){
	$ext = explode(".", $_FILES['literature']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['literature']['tmp_name'], $UPLOAD . "proposals/". $filename);
	
	$sql = "UPDATE am_leakcheck set literature='$filename' where leak_id='$leak_id'";
	executeupdate($sql);
  }
  
  $_SESSION['rr_edit_mode'] = 0;
}


if($submit1== "Get Replacement Done"){
  $sql = "SELECT prospect_id, property_id, (bid_value + rr_upsell) as amount, servicemen_id, resource_id from am_leakcheck where leak_id='$leak_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $prospect_id = stripslashes($record['prospect_id']);
  $property_id = stripslashes($record['property_id']);
  $amount = stripslashes($record['amount']);
  $servicemen_id = stripslashes($record['servicemen_id']);
  $resource_id = stripslashes($record['resource_id']);
  if($servicemen_id==0){
    $sql = "SELECT user_id from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and resource_id=\"$resource_id\" order by user_id limit 1";
	$servicemen_id = getsingleresult($sql);
  }
  
  $project_id = "RR-" . $leak_id;
  $sql = "INSERT into opportunities(prospect_id, property_id, amount, user_id, opp_product_id, lastaction, sold_date, status, leak_id) values(
  '$prospect_id', '$property_id', '$amount', '$servicemen_id', -1, now(), now(), 'Sold', '$leak_id')";
  executeupdate($sql);
  $opp_id = go_insert_id();
  
  $sql = "SELECT count(*) from opportunities where project_id=\"$project_id\"";
  $test = getsingleresult($sql);
  while($test){
    $project_id .= "a";
	$sql = "SELECT count(*) from opportunities where project_id=\"$project_id\"";
    $test = getsingleresult($sql);
  }
  
  $sql = "SELECT opm_id from opm where master_id='" . $SESSION_MASTER_ID . "' and project_id=\"$project_id\" and opp_id = '$opp_id'";
	$opm_id = getsingleresult($sql);
	if($opm_id==""){
	  $secretcode = secretCode();
	  $project_sqft = 0;
	  $sql = "SELECT sum(sqft) from sections where property_id='$property_id'";
	  $project_sqft = stripslashes(getsingleresult($sql));
	  
	  $sql = "INSERT into opm(master_id, prospect_id, property_id, project_id, code, opp_id, project_sqft) values(
	  '" . $SESSION_MASTER_ID . "', '$prospect_id', '$property_id', \"$project_id\", \"$secretcode\", '$opp_id', \"$project_sqft\")";
	  executeupdate($sql);
	  $opm_id = go_insert_id();
	}
	$sql = "UPDATE opportunities set opm_id='$opm_id', project_id=\"$project_id\" where opp_id='$opp_id'";
	executeupdate($sql);
  
  $sql = "UPDATE am_leakcheck set archive=1 where leak_id='$leak_id'";
  executeupdate($sql);
  $sql = "UPDATE am_leakcheck set allow_proposal_done = 0 where leak_id='$leak_id'";
  executeupdate($sql);
}

if($submit1=="Proposal Declined"){
  $sql = "UPDATE am_leakcheck set archive=1 where leak_id='$leak_id'";
  executeupdate($sql);
  $sql = "SELECT ncp_spawned_from from am_leakcheck where leak_id='$leak_id'";
  $ncp_spawned_from = getsingleresult($sql);
  if($ncp_spawned_from != "" && $ncp_spawned_from != 0){
    $sql = "UPDATE am_leakcheck set ncp_lock=0, ncp_rejected=1 where leak_id='$ncp_spawned_from'";
	executeupdate($sql);
	$_SESSION['sess_msg'] = "No Cost Proposal was declined.  This is the original dispatch.";
	meta_redirect("fcs_sd_report_view.php?leak_id=$ncp_spawned_from");
  }
  
}
  
meta_redirect("fcs_sd_report_view" . $special_invoice . ".php?leak_id=$leak_id");
?>
