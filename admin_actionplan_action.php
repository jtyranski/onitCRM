<?php
include "includes/functions.php";

$ap_salesgoal = go_escape_string($_POST['ap_salesgoal']);
$ap_increase = go_escape_string($_POST['ap_increase']);
$ap_contract_percent = go_escape_string($_POST['ap_contract_percent']);
$ap_contract_dollar = go_escape_string($_POST['ap_contract_dollar']);
$ap_contract_close = go_escape_string($_POST['ap_contract_close']);
$ap_contract_meetings = go_escape_string($_POST['ap_contract_meetings']);
$ap_contract_contacts = go_escape_string($_POST['ap_contract_contacts']);
$ap_service_percent = go_escape_string($_POST['ap_service_percent']);
$ap_service_dollar = go_escape_string($_POST['ap_service_dollar']);
$ap_service_close = go_escape_string($_POST['ap_service_close']);
$ap_tm = go_escape_string($_POST['ap_tm']);
$ap_service_meetings = go_escape_string($_POST['ap_service_meetings']);
$ap_service_contacts = go_escape_string($_POST['ap_service_contacts']);

$submit1 = go_escape_string($_POST['submit1']);

$ap_salesgoal = go_reg_replace("\$", "", $ap_salesgoal);
$ap_increase = go_reg_replace("\$", "", $ap_increase);
$ap_contract_percent = go_reg_replace("\$", "", $ap_contract_percent);
$ap_contract_dollar = go_reg_replace("\$", "", $ap_contract_dollar);
$ap_contract_close = go_reg_replace("\$", "", $ap_contract_close);
$ap_contract_meetings = go_reg_replace("\$", "", $ap_contract_meetings);
$ap_contract_contacts = go_reg_replace("\$", "", $ap_contract_contacts);
$ap_service_percent = go_reg_replace("\$", "", $ap_service_percent);
$ap_service_dollar = go_reg_replace("\$", "", $ap_service_dollar);
$ap_service_close = go_reg_replace("\$", "", $ap_service_close);
$ap_tm = go_reg_replace("\$", "", $ap_tm);
$ap_service_meetings = go_reg_replace("\$", "", $ap_service_meetings);
$ap_service_contacts = go_reg_replace("\$", "", $ap_service_contacts);


$ap_salesgoal = go_reg_replace("\,", "", $ap_salesgoal);
$ap_increase = go_reg_replace("\,", "", $ap_increase);
$ap_contract_percent = go_reg_replace("\,", "", $ap_contract_percent);
$ap_contract_dollar = go_reg_replace("\,", "", $ap_contract_dollar);
$ap_contract_close = go_reg_replace("\,", "", $ap_contract_close);
$ap_contract_meetings = go_reg_replace("\,", "", $ap_contract_meetings);
$ap_contract_contacts = go_reg_replace("\,", "", $ap_contract_contacts);
$ap_service_percent = go_reg_replace("\,", "", $ap_service_percent);
$ap_service_dollar = go_reg_replace("\,", "", $ap_service_dollar);
$ap_service_close = go_reg_replace("\,", "", $ap_service_close);
$ap_tm = go_reg_replace("\,", "", $ap_tm);
$ap_service_meetings = go_reg_replace("\,", "", $ap_service_meetings);
$ap_service_contacts = go_reg_replace("\,", "", $ap_service_contacts);

if($submit1 != ""){
  $sql = "UPDATE master_list set ap_salesgoal=\"$ap_salesgoal\", 
  ap_increase=\"$ap_increase\", 
  ap_contract_percent=\"$ap_contract_percent\", 
  ap_contract_dollar=\"$ap_contract_dollar\", 
  ap_contract_close=\"$ap_contract_close\", 
  ap_contract_meetings=\"$ap_contract_meetings\", 
  ap_contract_contacts=\"$ap_contract_contacts\", 
  ap_service_percent=\"$ap_service_percent\", 
  ap_service_dollar=\"$ap_service_dollar\", 
  ap_service_close=\"$ap_service_close\", 
  ap_tm=\"$ap_tm\", 
  ap_service_meetings=\"$ap_service_meetings\", 
  ap_service_contacts=\"$ap_service_contacts\"
  where master_id='" . $SESSION_MASTER_ID . "'";
  executeupdate($sql);
}

meta_redirect("admin_actionplan.php");
?>

