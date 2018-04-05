<?php
include "includes/functions.php";

$user_id = $_POST['user_id'];
$scheduled_by = $SESSION_USER_ID;

$prospect_id = $_POST['prospect_id'];
$property_id = $_POST['property_id'];
$opp_id = $_POST['opp_id'];
$status = go_escape_string($_POST['status']);
$loi_flag = 0;

$product = go_escape_string($_POST['product']);
$opp_product_id = go_escape_string($_POST['opp_product_id']);
$amount = go_escape_string($_POST['amount']);
$description = go_escape_string($_POST['description']);
$probability = go_escape_string($_POST['probability']);
$projected_replacement = go_escape_string($_POST['projected_replacement']);
$srmanager = go_escape_string($_POST['srmanager']);
$srmanager = 0;

$submit1 = go_escape_string($_POST['submit1']);
$proposal = go_escape_string($_POST['proposal']);
if($proposal != 1) $proposal=0;
$lastaction_pretty = go_escape_string($_POST['lastaction_pretty']);
$date_parts = explode("/", $lastaction_pretty);
$lastaction = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];

$opp_stage_id = go_escape_string($_POST['opp_stage_id']);
$old_opp_stage_id = go_escape_string($_POST['old_opp_stage_id']);

$prodstat_id = go_escape_string($_POST['prodstat_id']);
$project_manager = go_escape_string($_POST['project_manager']);
$crew = go_escape_string($_POST['crew']);
$bid_id = go_escape_string($_POST['bid_id']);

$hourly_rate = go_escape_string($_POST['hourly_rate']);

$inspection_frequency = go_escape_string($_POST['inspection_frequency']);
$install_contractor = go_escape_string($_POST['install_contractor']);
$install_mfg = go_escape_string($_POST['install_mfg']);
$install_exp_pretty = go_escape_string($_POST['install_exp_pretty']);
$date_parts = explode("/", $install_exp_pretty);
$install_exp = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
if($inspection_frequency=="") $inspection_frequency = 0;


if($submit1 != ""){
  
  if($SESSION_MASTER_ID==1){ // only set has_opp to 1 if there's already an act with Objective Met.  Otherwise, has_opp of 1 without an act will not show on search results
    $sql = "SELECT count(*) from activities where prospect_id='$prospect_id' and act_result='Objective Met'";
	$test = getsingleresult($sql);
	if($test != 0){
      $sql = "UPDATE prospects set has_opp=1 where prospect_id='$prospect_id'";
      executeupdate($sql);
	}
  }
  
  if($opp_id=="new"){
    $code = uniqueTimeStamp();
    $sql = "INSERT into opportunities(prospect_id, property_id, user_id, product, amount, status, description, code, probability, 
	lastaction, projected_replacement, srmanager, loi_flag, scheduled_by, opp_stage_id, opp_stage_changedate, 
	prodstat_id, project_manager, crew, bid_id, hourly_rate, opp_product_id) values (
	'$prospect_id', '$property_id', '$user_id', \"$product\", '$amount', '$status', \"$description\", \"$code\", \"$probability\", 
	'$lastaction', '$projected_replacement', '$srmanager', '$loi_flag', '$scheduled_by', '$opp_stage_id', now(), 
	'$prodstat_id', '$project_manager', '$crew', '$bid_id', '$hourly_rate', \"$opp_product_id\")";
	executeupdate($sql);
	$opp_id = go_insert_id();
	

  }
  else {
    $sql = "UPDATE opportunities set property_id='$property_id', product=\"$product\", amount='$amount', description=\"$description\", 
	probability=\"$probability\", lastaction=\"$lastaction\", projected_replacement='$projected_replacement', 
	srmanager='$srmanager', status='$status', loi_flag='$loi_flag', scheduled_by='$scheduled_by', opp_stage_id='$opp_stage_id', 
	prodstat_id='$prodstat_id', project_manager='$project_manager', crew='$crew', bid_id='$bid_id', hourly_rate='$hourly_rate', 
	opp_product_id=\"$opp_product_id\"";
	if($opp_stage_id != $old_opp_stage_id) $sql .=", opp_stage_changedate=now()";
	if($user_id != "") $sql .= ", user_id=\"$user_id\"";
	
	$sql .= " where opp_id='$opp_id'";
	executeupdate($sql);

  }
  /*
    $sold_date_pretty = $_POST['sold_date_pretty'];
	$date_parts = explode("/", $sold_date_pretty);
    $sold_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
	$sql = "UPDATE opportunities set sold_date=\"$sold_date\" where opp_id='$opp_id'";
	executeupdate($sql);
   */
  
  if($status=="Sold"){
    $sql = "SELECT date_format(sold_date, \"%Y\") as sd from opportunities where opp_id='$opp_id'";
	$sd = getsingleresult($sql);
	if($sd=="0000"){
	  $sql = "UPDATE opportunities set sold_date=now() where opp_id='$opp_id'";
	  executeupdate($sql);
	}
  }
  
  if($status=="Sold" && $opp_product_id==-1 && $SESSION_USE_OPS==1){
    $project_id = go_escape_string($_POST['project_id']);
	if($project_id==""){
	  $_SESSION['sess_msg'] = "Please enter a value for your Project ID.";
	  meta_redirect("opportunity_edit.php?prospect_id=$prospect_id&property_id=$property_id&opp_id=$opp_id");
	}
	$sql = "SELECT count(*) from opm where master_id='" . $SESSION_MASTER_ID . "' and project_id=\"$project_id\" and opp_id != '$opp_id'";
	$test = getsingleresult($sql);
	if($test){
	  $_SESSION['sess_msg'] = "This Project ID is being used by another project in your system.";
	  meta_redirect("opportunity_edit.php?prospect_id=$prospect_id&property_id=$property_id&opp_id=$opp_id");
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
  }
  else { // if it's no longer sold, or no longer a roof replacement, kill the opm
    $sql = "DELETE from opm where opp_id='$opp_id' and opp_id != 0";
	executeupdate($sql);
  }
  
}

$redirect = $_POST['redirect'];
if($redirect != "") meta_redirect($redirect);
  
$sql = "SELECT corporate from properties where property_id='$property_id'";
$testcorp = getsingleresult($sql);
if($testcorp==1){
  meta_redirect("view_company.php?prospect_id=$prospect_id&view=opportunities");
}
else {
  meta_redirect("view_property.php?property_id=$property_id&view=opportunities");
}

?>
