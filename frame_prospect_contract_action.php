<?php 
include "includes/functions.php";
$prospect_id = go_escape_string($_POST['prospect_id']);

$nte_amount = go_escape_string($_POST['nte_amount']);
$labor_rate = go_escape_string($_POST['labor_rate']);
$labor_rate2 = go_escape_string($_POST['labor_rate2']);
$labor_rate3 = go_escape_string($_POST['labor_rate3']);
$hours_of_operation = go_escape_string($_POST['hours_of_operation']);
$payment_terms = go_escape_string($_POST['payment_terms']);
$invoice_requirements = go_escape_string($_POST['invoice_requirements']);
$checkin_procedure = go_escape_string($_POST['checkin_procedure']);
$checkout_procedure = go_escape_string($_POST['checkout_procedure']);

$old_nte_amount = go_escape_string($_POST['old_nte_amount']);
$old_labor_rate = go_escape_string($_POST['old_labor_rate']);
$old_hours_of_operation = go_escape_string($_POST['old_hours_of_operation']);
$old_payment_terms = go_escape_string($_POST['old_payment_terms']);
$old_invoice_requirements = go_escape_string($_POST['old_invoice_requirements']);
$old_checkin_procedure = go_escape_string($_POST['old_checkin_procedure']);
$old_checkout_procedure = go_escape_string($_POST['old_checkout_procedure']);

$submit1 = go_escape_string($_POST['submit1']);

if($submit1=="Update"){
  $sql = "UPDATE prospects set nte_amount=\"$nte_amount\", labor_rate=\"$labor_rate\", hours_of_operation=\"$hours_of_operation\", payment_terms=\"$payment_terms\", 
  invoice_requirements=\"$invoice_requirements\", checkin_procedure=\"$checkin_procedure\", checkout_procedure=\"$checkout_procedure\", 
  labor_rate2=\"$labor_rate2\", labor_rate3=\"$labor_rate3\" where prospect_id='$prospect_id'";
  executeupdate($sql);
  
  $changes = "";
  
  if (is_uploaded_file($_FILES['contractdoc']['tmp_name']))
    {
	
	$ext = explode(".", $_FILES['contractdoc']['name']);
  	$ext = strtolower(array_pop($ext));
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['contractdoc']['tmp_name'], "uploaded_files/contracts/". $filename);
	
	$sql = "UPDATE prospects set contractdoc='$filename' where prospect_id='$prospect_id'";
	executeupdate($sql);
	$changes .= "New Contract Document\n";
  }
  
  
  if($nte_amount != $old_nte_amount) $changes .= "Not to Exceed Amount\n";
  if($labor_rate != $old_labor_rate) $changes .= "Labor Rate\n";
  if($hours_of_operation != $old_hours_of_operation) $changes .= "Hours of Operation\n";
  if($payment_terms != $old_payment_terms) $changes .= "Payment Terms\n";
  if($invoice_requirements != $old_invoice_requirements) $changes .= "Invoice Requirements\n";
  if($checkin_procedure != $old_checkin_procedure) $changes .= "Check In Procedure\n";
  if($checkout_procedure != $old_checkout_procedure) $changes .= "Check Out Procedure\n";
  
  if($changes != ""){
    $sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $SESSION_USER_ID . "'";
	$fullname = go_escape_string(stripslashes(getsingleresult($sql)));
	$note = $fullname . " made the following contract changes:\n" . $changes;
	$sql = "INSERT into notes(prospect_id, user_id, date, event, regarding, note) values(
	'$prospect_id', '" . $SESSION_USER_ID . "', now(), 'Note', 'Contract Changes', \"$note\")";
	executeupdate($sql);
  }
  
  
}

meta_redirect("frame_prospect_contract.php?prospect_id=$prospect_id");
?>