<?php
include "includes/functions.php";



$_SESSION[$sess_header . '_backup_login'] = $SESSION_USER_ID;
$_SESSION[$sess_header . '_backup_user_level'] = $SESSION_USER_LEVEL;
$_SESSION[$sess_header . '_backup_use_groups'] = $SESSION_USE_GROUPS;
$_SESSION[$sess_header . '_backup_groups'] = $SESSION_GROUPS;
$_SESSION[$sess_header . '_backup_subgroups'] = $SESSION_SUBGROUPS;
$_SESSION[$sess_header . '_backup_group_prospect_id'] = $SESSION_GROUP_PROSPECT_ID;
$_SESSION[$sess_header . '_backup_group_allowed_users'] = $SESSION_GROUP_ALLOWED_USERS;
$_SESSION[$sess_header . '_backup_disciplines'] = $SESSION_DIS;

$master_id = $_GET['master_id'];


  $sql = "SELECT user_id from users where master_id='$master_id' and admin=1 and enabled=1 order by user_id limit 1";
  $user_id = getsingleresult($sql);
  
if($user_id==""){
  echo "Error accessing selected user. You must have at least one user with admin access to log in this way.";
  exit;
}

$sql = "SELECT admin, user_level, groups, subgroups
	from users where user_id='$user_id' and master_id='$master_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$admin = $record['admin'];
	$user_level = $record['user_level'];
	$groups = $record['groups'];
	$subgroups = $record['subgroups'];
    $_SESSION[$sess_header . '_user_id'] = $user_id;
	$_SESSION[$sess_header . '_master_id'] = $master_id;
	$_SESSION[$sess_header . '_isadmin'] = $admin;
	$_SESSION[$sess_header . '_user_level'] = $user_level;
    $_SESSION[$sess_header . '_supercali_user_id'] = "2";

$sql = "SELECT opportunities, multilogo, timezone, use_resources, use_ops, residential, use_groups from master_list where master_id='$master_id'";
    $result = executequery($sql);
    $record = go_fetch_array($result);
    $opportunities = $record['opportunities'];
	$multilogo = $record['multilogo'];
	$timezone = $record['timezone'];
	$use_resources = $record['use_resources'];
	$use_ops = $record['use_ops'];
	$residential = $record['residential'];
	$use_groups = $record['use_groups'];

    $_SESSION[$sess_header . '_opportunities'] = $opportunities;
	$_SESSION[$sess_header . '_multilogo'] = $multilogo;
	$_SESSION[$sess_header . '_timezone'] = $timezone;
	$_SESSION[$sess_header . '_use_resources'] = $use_resources;
	$_SESSION[$sess_header . '_use_ops'] = $use_ops;
	$_SESSION[$sess_header . '_residential'] = $residential;
	$_SESSION[$sess_header . '_use_groups'] = $use_groups;
	$_SESSION[$sess_header . '_group_prospect_id'] = "";
	$_SESSION[$sess_header . '_groups'] = "";
	$_SESSION[$sess_header . '_subgroups'] = "";
	if($use_groups==1){
	  $_SESSION[$sess_header . '_groups'] = $groups;
	  $_SESSION[$sess_header . '_subgroups'] = $subgroups;
	  $allowed_users = "";
	  
	  $groups_array = explode(",", $groups);
      $groups_search = "";
      for($x=0;$x<sizeof($groups_array);$x++){
        if($groups_array[$x]=="") continue;
	    $groups_search .= " groups like '%," . $groups_array[$x] . ",%' or ";
		$sql = "SELECT user_id from users where enabled=1 and master_id='$master_id' and groups like '%," . $groups_array[$x] . ",%'";
		$result = executequery($sql);
		while($record = go_fetch_array($result)){
		  $allowed_users .= $record['user_id'] . ",";
		}
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
		  $sql = "SELECT user_id from users where enabled=1 and master_id='$master_id' and subgroups like '%," . $groups_array[$x] . ",%'";
		  $result = executequery($sql);
		  while($record = go_fetch_array($result)){
		    $allowed_users .= $record['user_id'] . ",";
		  }
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
	  $allowed_users = go_reg_replace("\,$", "", $allowed_users);
	  if($allowed_users=="") $allowed_users = $user_id;
	  $_SESSION[$sess_header . '_group_allowed_users'] = $allowed_users;
	}
	
	$disciplines = array(1);
	$sql = "SELECT dis_id from discipline_to_master where master_id='$master_id'";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $disciplines[] = $record['dis_id'];
	}
	$_SESSION[$sess_header . '_disciplines'] = $disciplines;

$_SESSION[$sess_header . '_contacts2_cand_type'] = 0;
meta_redirect("contacts.php");
?>