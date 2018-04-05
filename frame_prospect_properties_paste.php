<?php
include "includes/functions.php";

$prospect_id = go_escape_string($_GET['prospect_id']);

$prop_list = "";

if(is_array($_SESSION[$sess_header . '_move_property'])){
  for($x=0;$x<sizeof($_SESSION[$sess_header . '_move_property']);$x++){
    $prop_id = go_escape_string($_SESSION[$sess_header . '_move_property'][$x]);
	
	$sql = "SELECT prospect_id from properties where property_id='$prop_id'";
	$old_prospect_id = getsingleresult($sql);
	
	$sql = "UPDATE properties set prospect_id=\"$prospect_id\" where property_id=\"$prop_id\"";
	executeupdate($sql);
	
	$sql = "UPDATE am_leakcheck set prospect_id=\"$prospect_id\" where property_id=\"$prop_id\"";
	executeupdate($sql);
	
	$sql = "UPDATE activities set prospect_id=\"$prospect_id\" where property_id=\"$prop_id\"";
	executeupdate($sql);
	
	$sql = "UPDATE opportunities set prospect_id=\"$prospect_id\" where property_id=\"$prop_id\"";
	executeupdate($sql);
	
	$sql = "UPDATE notes set prospect_id=\"$prospect_id\" where property_id=\"$prop_id\"";
	executeupdate($sql);
	
	$sql = "UPDATE supercali_events set prospect_id=\"$prospect_id\" where property_id=\"$prop_id\"";
	executeupdate($sql);
	
	$sql = "UPDATE drawings set prospect_id=\"$prospect_id\" where property_id=\"$prop_id\"";
	executeupdate($sql);
	
	$sql = "SELECT site_name from properties where property_id='$prop_id'";
	$site_name = stripslashes(getsingleresult($sql));
	
	$prop_list .= "<a href='view_property.php?property_id=$prop_id' target='_top'>$site_name</a>\n";
	
  }
  
  $sql = "SELECT company_name from prospects where prospect_id='$old_prospect_id'";
  $old_company_name = stripslashes(getsingleresult($sql));
  
  $sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
  $company_name = stripslashes(getsingleresult($sql));
  
  $note = "The following properties have been moved from <a href='view_company.php?prospect_id=$old_prospect_id' target='_top'>$old_company_name</a> to <a href='view_company.php?prospect_id=$prospect_id' target='_top'>$company_name</a>\n";
  $note .= $prop_list;
  $note = go_escape_string($note);
  
  $sql = "INSERT into notes(prospect_id, user_id, event, date, note) values('$prospect_id', '" . $SESSION_USER_ID . "', 'Note', now(), \"$note\")";
  executeupdate($sql);
  $sql = "INSERT into notes(prospect_id, user_id, event, date, note) values('$old_prospect_id', '" . $SESSION_USER_ID . "', 'Note', now(), \"$note\")";
  executeupdate($sql);
  
  
}

$_SESSION[$sess_header . '_move_property'] = "";

  $sql = "SELECT count(property_id) from properties where display=1 and corporate=0 and prospect_id='$old_prospect_id'";
  $properties = getsingleresult($sql);
  $sql = "UPDATE prospects set properties='$properties' where prospect_id='$old_prospect_id'";
  executeupdate($sql);
  
  $sql = "SELECT count(property_id) from properties where display=1 and corporate=0 and prospect_id='$prospect_id'";
  $properties = getsingleresult($sql);
  $sql = "UPDATE prospects set properties='$properties' where prospect_id='$prospect_id'";
  executeupdate($sql);

$sql = "UPDATE prospects set has_act=0, has_opp=0 where prospect_id='$old_prospect_id'";
executeupdate($sql);
  $sql = "SELECT count(act_id) from activities where prospect_id='$old_prospect_id' and display=1";
  $test = getsingleresult($sql);
  if($test){
    $sql = "UPDATE prospects set has_act=1 where prospect_id='$old_prospect_id'";
	executeupdate($sql);
  }
  $sql = "SELECT count(opp_id) from opportunities where prospect_id='$old_prospect_id' and display=1";
  $test = getsingleresult($sql);
  if($test){
    $sql = "UPDATE prospects set has_opp=1 where prospect_id='$old_prospect_id'";
	executeupdate($sql);
  }
  
$sql = "UPDATE prospects set has_act=0, has_opp=0 where prospect_id='$prospect_id'";
executeupdate($sql); 
  $sql = "SELECT count(act_id) from activities where prospect_id='$prospect_id' and display=1";
  $test = getsingleresult($sql);
  if($test){
    $sql = "UPDATE prospects set has_act=1 where prospect_id='$prospect_id'";
	executeupdate($sql);
  }
  $sql = "SELECT count(opp_id) from opportunities where prospect_id='$prospect_id' and display=1";
  $test = getsingleresult($sql);
  if($test){
    $sql = "UPDATE prospects set has_opp=1 where prospect_id='$prospect_id'";
	executeupdate($sql);
  }
meta_redirect("frame_prospect_properties.php?prospect_id=$prospect_id");
?>