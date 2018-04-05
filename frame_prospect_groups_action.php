<?php
include "includes/functions.php";

$property_id = go_escape_string($_POST['property_id']);
$prospect_id = go_escape_string($_POST['prospect_id']);
$group_list = $_POST['group_list'];
$subgroup_list = $_POST['subgroup_list'];
$submit1 = $_POST['submit1'];

if($submit1 != ""){
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
}

meta_redirect("frame_prospect_groups.php?prospect_id=$prospect_id");
?>
  