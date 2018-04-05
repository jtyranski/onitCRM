<?php
include "includes/functions.php";

$doc_id = go_escape_string($_POST['doc_id']);
$submit1 = go_escape_string($_POST['submit1']);
$total = go_escape_string($_POST['total']);
$subtotal = go_escape_string($_POST['subtotal']);

$def_id = $_POST['def_id'];

$check1 = go_escape_string($_POST['check1']);
$check2 = go_escape_string($_POST['check2']);
$name = go_escape_string($_POST['name']);
$email = go_escape_string($_POST['email']);
$send_unsigned = go_escape_string($_POST['send_unsigned']);

if($check1 != 1) $check1 = 0;
if($check2 != 1) $check2 = 0;
if($send_unsigned != 1) $send_unsigned = 0;

if($submit1 != ""){
  if(is_array($def_id)){
    for($x=0;$x<sizeof($def_id);$x++){
	  $def_selected .= "," . $def_id[$x] . ",";
	}
  }
  
  $sql = "UPDATE document_proposal_fcs set total='$total', subtotal='$subtotal', check1='$check1', check2='$check2', 
  name=\"$name\", email=\"$email\", ip='" . $_SERVER['REMOTE_ADDR'] . "', def_selected=\"$def_selected\", sign_date=now(), 
  send_unsigned='$send_unsigned'
  where id='$doc_id'";
  executeupdate($sql);
  
  meta_redirect("public_proposal_fcs_pdf.php?doc_id=$doc_id");
  
}

?>