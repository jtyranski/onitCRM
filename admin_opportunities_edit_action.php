<?php
include "includes/functions.php";

$opp_product_id = go_escape_string($_POST['opp_product_id']);
$opp_name = go_escape_string($_POST['opp_name']);
$submit1 = go_escape_string($_POST['submit1']);

if($submit1=="Update"){
  if($opp_product_id != "new"){
    $sql = "SELECT master_id from opportunities_items where opp_product_id='$opp_product_id'";
	$master_id = getsingleresult($sql);
	if($master_id != $SESSION_MASTER_ID) exit;
  }
  
  if($opp_product_id == "new"){
    $sql = "INSERT into opportunities_items(master_id, opp_name) values('" . $SESSION_MASTER_ID . "', \"$opp_name\")";
	executeupdate($sql);
  }
  else{
    $sql = "UPDATE opportunities_items set opp_name=\"$opp_name\" where opp_product_id='$opp_product_id'";
	executeupdate($sql);
  }
}

meta_redirect("admin_opportunities.php");
?>
