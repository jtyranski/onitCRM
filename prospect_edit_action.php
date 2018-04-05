<?php
include "includes/functions.php";

$user_id = $SESSION_USER_ID;

$prospect_id = $_POST['prospect_id'];

$property_type_list = "Non-PFRI";

//$prospect_status = go_escape_string($_POST['prospect_status']);
$company_name = go_escape_string($_POST['company_name']);
$address = go_escape_string($_POST['address']);
$city = go_escape_string($_POST['city']);
$state = go_escape_string($_POST['state']);
$zip = go_escape_string($_POST['zip']);

$firstname = go_escape_string($_POST['firstname']);
$lastname = go_escape_string($_POST['lastname']);
$phone = go_escape_string($_POST['phone']);
$email = go_escape_string($_POST['email']);
$position = go_escape_string($_POST['position']);
$mobile = go_escape_string($_POST['mobile']);
$fax = go_escape_string($_POST['fax']);

$comres = go_escape_string($_POST['comres']);
$prospect_firstname = go_escape_string($_POST['prospect_firstname']);
$prospect_lastname = go_escape_string($_POST['prospect_lastname']);
if($comres=="") $comres = "C";

if($comres=="R") $company_name = $prospect_lastname . ", " . $prospect_firstname;

$manufacturer = go_escape_string($_POST['manufacturer']);
$installer = go_escape_string($_POST['installer']);
if($manufacturer != 1) $manufacturer = 0;
if($installer != 1) $installer = 0;



$prospect_type = go_escape_string($_POST['prospect_type']);
switch($prospect_type){
  case "Prospect":{
    $industry = 0;
	$resource = 0;
	$manufacturer = 0;
	$installer = 0;
    break;
  }
  case "Vendor":{
    $industry = 1;
	$resource = 0;
    break;
  }
  case "Resource":{
    $industry = 1;
	$resource = 1;
	$manufacturer = 0;
	$installer = 0;
    break;
  }
  default:{
    $industry = 0;
	$resource = 0;
	$manufacturer = 0;
	$installer = 0;
    break;
  }
}
  
$dis_id = $_POST['dis_id'];

$submit1 = go_escape_string($_POST['submit1']);

$_SESSION[$sess_header . '_prospect'] = $_POST;

if (is_uploaded_file($_FILES['logo']['tmp_name']))
{
  if(!(is_image_valid($_FILES['logo']['name']))){
	$_SESSION['sess_msg'] = "You can only upload jpg, gif, or png images";
	meta_redirect("prospect_edit.php?prospect_id=$prospect_id");
  }
}
  
if($submit1 != ""){
  if($prospect_id=="new"){
  // check for existing company name.
  /*
    $sql = "SELECT prospect_id from prospects where company_name='$company_name'";
	$test = getsingleresult($sql);
	if($test != ""){
	  $_SESSION['sess_msg'] = "ERROR: There is already a prospect named $company_name in the system.";
	  meta_redirect("prospect_edit.php?prospect_id=$prospect_id");
	}
	*/

    $sql = "INSERT into prospects(company_name, address, city, state, zip, user_id, property_type, 
	master_id, industry, resource, manufacturer, installer, comres, firstname, lastname) values (
	\"$company_name\", \"$address\", \"$city\", \"$state\", \"$zip\", '$user_id', \"$property_type_list\",
	'" . $SESSION_MASTER_ID . "', '$industry', '$resource', '$manufacturer', '$installer', '$comres', \"$prospect_firstname\", \"$prospect_lastname\")"; 

	executeupdate($sql);
	$prospect_id = go_insert_id();
	// add corporate property
	
	if($SESSION_IREP==1){
      $sql = "UPDATE prospects set irep='," . $SESSION_USER_ID . ",' where prospect_id='$prospect_id'";
	  executeupdate($sql);
    }
  
	$sql = "INSERT into properties (prospect_id, site_name, corporate) values(
	'$prospect_id', \"$company_name Corporate\", 1)";
	executeupdate($sql);
	$property_id = go_insert_id();
	
	if(is_array($dis_id)){
	  $sql = "DELETE from vendor_to_dis where prospect_id='$prospect_id'";
	  executeupdate($sql);
	  
	  for($x=0;$x<sizeof($dis_id);$x++){
	    $sql = "INSERT into vendor_to_dis(prospect_id, dis_id) values('$prospect_id', '" . $dis_id[$x] . "')";
		executeupdate($sql);
	  }
	}
	
	$group_list = $_POST['group_list'];
    $subgroup_list = $_POST['subgroup_list'];
	
	$groups = "";
  for($x=0;$x<sizeof($group_list);$x++){
    $groups .= $group_list[$x];
  }
  
  $subgroups = "";
  for($x=0;$x<sizeof($subgroup_list);$x++){
    $subgroups .= $subgroup_list[$x];
  }
  
  $USEGROUP = 0;
  if($groups != "") $USEGROUP = $groups;
  if($subgroups != "") $USEGROUP = $subgroups;
  
  if($USEGROUP != 0){
    $sql = "SELECT timezone from groups where id='$USEGROUP'";
	$timezone = getsingleresult($sql);
	$sql = "UPDATE properties set timezone='$timezone' where property_id='$property_id'";
	executeupdate($sql);
  }
  
  $sql = "UPDATE properties set groups='$groups', subgroups='$subgroups' where property_id='$property_id'";
  executeupdate($sql);
  
  $sql = "SELECT prospect_id from properties where property_id='$property_id'";
  $prospect_id = getsingleresult($sql);
  
  $groups = "";
  $subgroups = "";
  $sql = "SELECT groups, subgroups from properties where prospect_id='$prospect_id' and display=1";
  
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $groups .= $record['groups'] . ",";
	$subgroups .= $record['subgroups'] . ",";
  }

  
  $group_array = explode(",", $groups);
  $subgroup_array = explode(",", $subgroups);
  
  $group_array = array_unique($group_array);
  $subgroup_array = array_unique($subgroup_array);
  
  $group_array = array_values($group_array);
  $subgroup_array = array_values($subgroup_array);
  
  $groups = "";
  for($x=0;$x<sizeof($group_array);$x++){
    if($group_array[$x]=="") continue;
	if($group_array[$x]==0) continue;
	$groups .= "," . $group_array[$x] . ",";
  }
  
  $subgroups = "";
  for($x=0;$x<sizeof($subgroup_array);$x++){
    if($subgroup_array[$x]=="") continue;
	if($subgroup_array[$x]==0) continue;
	$subgroups .= "," . $subgroup_array[$x] . ",";
  }
  
  $sql = "UPDATE prospects set groups='$groups', subgroups='$subgroups' where prospect_id='$prospect_id'";
  executeupdate($sql);
  
  if($SESSION_USE_GROUPS && ($SESSION_GROUPS != "" || $SESSION_SUBGROUPS != "")){
    $_SESSION[$sess_header . '_group_prospect_id'] = array();
    $groups_array = explode(",", $groups);
      $groups_search = "";
      for($x=0;$x<sizeof($groups_array);$x++){
        if($groups_array[$x]=="") continue;
	    $groups_search .= " groups like '%," . $groups_array[$x] . ",%' or ";

      }
      $groups_search = go_reg_replace("or $", "", $groups_search);
      $groups_clause = $groups_search;
      if($groups_clause == "") $groups_clause = " 1=1 ";
  
      if($subgroups != ''){
        if($groups_clause == " 1=1 "){
	      $groups_clause = "(";
	    }
	    else {
          $groups_clause = "(" . $groups_clause;
	    }
        $subgroups_array = explode(",", $subgroups);
        $subgroups_search = "";
        for($x=0;$x<sizeof($subgroups_array);$x++){
          if($subgroups_array[$x]=="") continue;
	      $subgroups_search .= " subgroups like '%," . $subgroups_array[$x] . ",%' or ";

        }
        $subgroups_search = go_reg_replace("or $", "", $subgroups_search);
        if($groups_clause != "(") $groups_clause .= " and ";
        $groups_clause = $groups_clause .= " $subgroups_search) ";
      }
	  $sql = "SELECT prospect_id from prospects where master_id='$master_id' and display=1 and $groups_clause";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $_SESSION[$sess_header . '_group_prospect_id'][] = $record['prospect_id'];
	  }
  }
  
  
	
	$sql = "INSERT into contacts(prospect_id, firstname, lastname, phone, email, position, mobile, fax, master_id) values('$prospect_id', \"$firstname\", 
	\"$lastname\", \"$phone\", \"$email\", \"$position\", \"$mobile\", \"$fax\", '$SESSION_MASTER_ID')";
	executeupdate($sql);
	
  }
  else {
    $sql = "UPDATE prospects set company_name=\"$company_name\", address=\"$address\", city=\"$city\", state='$state', 
	zip='$zip', 
	property_type=\"$property_type_list\", 
	prospect_status=\"$prospect_status\", 
	where prospect_id='$prospect_id'";
	
	executeupdate($sql);
  }
  
  
  $sql = "INSERT into check_latlng(prospect_id) values('$prospect_id')";
  executeupdate($sql);
  
  if (is_uploaded_file($_FILES['logo']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['logo']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['logo']['tmp_name'], $UPLOAD . "temp/". $filename);
  	resizeimage($UPLOAD . "temp/$filename", $UPLOAD . "logos/", $LOGO_SIZE);
  	@unlink($UPLOAD . "temp/". $filename);
	
	$sql = "UPDATE prospects set logo='$filename' where prospect_id='$prospect_id'";
	executeupdate($sql);
  }
}

$_SESSION[$sess_header . '_prospect'] = "";
meta_redirect("view_company.php?prospect_id=$prospect_id");
?>
