<?php
include "includes/functions.php";

$unique_url = $_GET['unique_url'];

$sql = "SELECT master_id from master_list where unique_url = '$unique_url' and demo=1";
$master_id = getsingleresult($sql);

if($master_id==""){
  exit;
}

$sql = "SELECT demo_property_id from master_list where master_id='$master_id'";
$property_id = getsingleresult($sql);
if($property_id==0){
  $sql = "SELECT prospect_id from prospects where master_id='$master_id' and display=1 order by prospect_id limit 1";
  $prospect_id = getsingleresult($sql);

  $sql = "SELECT property_id from properties where prospect_id='$prospect_id' and display=1 and corporate=0 order by property_id limit 1";
  $property_id = getsingleresult($sql);
}
else {
  $sql = "SELECT prospect_id from properties where property_id='$property_id'";
  $prospect_id = getsingleresult($sql);
}

$sql = "SELECT section_id from sections where property_id='$property_id' and display=1 order by section_id limit 1";
$section_id = getsingleresult($sql);

$sql = "SELECT section_id from sections where property_id='$property_id' and display=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $multisection .= $record['section_id'] . ",";
}
$multisection = go_reg_replace("\,$", "", $multisection);

$sql = "SELECT user_id from users where master_id='$master_id' order by user_id limit 1";
$user_id = getsingleresult($sql);

$sql = "INSERT into document_proposal_fcs(section_id, user_id, intro_credit, property_id, multisection, proposal_date) values('$section_id', '$user_id', 0, '$property_id', '$multisection', now())";
executeupdate($sql);
$sql = "SELECT id from document_proposal_fcs where section_id='$section_id' order by id desc limit 1";
$doc_id = getsingleresult($sql);
meta_redirect("demo_proposal_display.php?doc_id=$doc_id");

?>