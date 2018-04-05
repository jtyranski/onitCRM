<?php


include "includes/functions.php";


$email = go_escape_string($_POST['email']);
$password = go_escape_string($_POST['password']);
$password = md5($password);
$type = go_escape_string($_POST['type']);

if($type=="") $type = "fcs";

if($type=="fcs" || $type=="fcsmobi"){
$sql = "SELECT count(*) from users where email=\"$email\" and password=\"$password\" and enabled=1";

$count = getsingleresult($sql);
switch($count){
  case 1:{
    $sql = "SELECT user_id, master_id, admin, resource, resource_id, user_level, irep, can_export, groups, subgroups
	from users where email='$email' and password='$password' and enabled=1";
	$result = executequery($sql);
	$record = go_fetch_array($result);
    $user_id = $record['user_id'];
	$master_id = $record['master_id'];
	$admin = $record['admin'];
	$resource = $record['resource'];
	$resource_id = $record['resource_id'];
	$user_level = $record['user_level'];
	$can_export = $record['can_export'];
	$irep = $record['irep'];
	$groups = $record['groups'];
	$subgroups = $record['subgroups'];
	
	
	if($resource==1){ // send them to the resource directory. Do not set payfcs session variables
	  $_SESSION[$sess_header . '_resource_master_id'] = $master_id;
	  $_SESSION[$sess_header . '_resource_user_id'] = $user_id;
	  $_SESSION[$sess_header . '_resource_prospect_id'] = $resource_id;
	  meta_redirect("../glorymaintenanceresource/index.php");
	}
	
	
	
	$sql = "SELECT active from master_list where master_id='$master_id'";
	$active = getsingleresult($sql);
	if($active==0){
	  $_SESSION['sess_msg'] = "The email/password combination entered is not in the system.";
      meta_redirect("login.php");
	}
    $_SESSION[$sess_header . '_user_id'] = $user_id;
	$_SESSION[$sess_header . '_master_id'] = $master_id;
	$_SESSION[$sess_header . '_isadmin'] = $admin;
	$_SESSION[$sess_header . '_user_level'] = $user_level;
	$_SESSION[$sess_header . '_irep'] = $irep;
	$_SESSION[$sess_header . '_can_export'] = $can_export;
	$_SESSION[$sess_header . '_supercali_user_id'] = "2";
    $sql = "UPDATE users set lastlogin=now() where user_id='$user_id'";
    executeupdate($sql);
	
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
	  //echo $sql;
	  //exit;
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

	
	if($master_id==1736){
	$sql = "INSERT into sessions_core(ip, user_id, master_id, admin, opportunities, multilogo, timezone, lastaction, use_resources, user_level) values(
	'" . $_SERVER['REMOTE_ADDR'] . "', '$user_id', '$master_id', '$admin', '$opportunities', '$multilogo', '$timezone', now(), '$use_resources', '$user_level')";
	executeupdate($sql);
	}

	if($type=="fcsmobi") meta_redirect("mobile/home.php");
    meta_redirect("index.php");
	break;
  }
  
  case 0:{
    $_SESSION['sess_msg'] = "The email/password combination entered is not in the system.";
    meta_redirect("login.php");
	break;
  }
  
  default:{
    /*
    $_SESSION['sess_login'] = $email;
	$_SESSION['sess_password'] = $_POST['password'];
	meta_redirect("login_multi.php");
	*/
	$_SESSION['sess_msg'] = "The email/password combination entered is not in the system.";
    meta_redirect("login.php");
	break;
  }
}
} // end if type=fcs

if($type=="fcsview" || $type=="fcsviewmobi"){
$sql = "SELECT count(*) from am_users where email=\"$email\" and password=\"$password\" and enabled=1";
$count = getsingleresult($sql);
switch($count){
  case 1:{
    $sql = "SELECT user_id, prospect_id, admin, divisions, sites, edit_mode, reports 
	from am_users where email='$email' and password='$password' and enabled=1";
	$result = executequery($sql);
	$record = go_fetch_array($result);
    $user_id = $record['user_id'];
	$prospect_id = $record['prospect_id'];
	$admin = $record['admin'];
	$divisions = $record['divisions'];
	$sites = $record['sites'];
	$edit_mode = $record['edit_mode'];
	$reports = $record['reports'];
    $_SESSION[$sess_view_header . '_user_id'] = $user_id;
	$_SESSION[$sess_view_header . '_prospect_id'] = $prospect_id;
	$_SESSION[$sess_view_header . '_isadmin'] = $admin;
	$_SESSION[$sess_view_header . '_divisions'] = $divisions;
	$_SESSION[$sess_view_header . '_sites'] = $sites;
	$_SESSION[$sess_view_header . '_edit_mode'] = $edit_mode;
	$_SESSION[$sess_view_header . '_reports'] = $reports;
	
	/*
	if($_SERVER['REMOTE_ADDR']=="71.201.115.237"){
	  echo $user_id;
	  //exit;
	}
	*/
	
	$sql = "SELECT master_id from prospects where prospect_id='$prospect_id'";
	$master_id = getsingleresult($sql);
	$_SESSION[$sess_view_header . '_master_id'] = $master_id;
	
	$sql = "SELECT logo, master_name from master_list where master_id='$master_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$master_logo = $record['logo'];
	$master_name = stripslashes($record['master_name']);
	$_SESSION[$sess_view_header . '_master_logo'] = $record['logo'];
	$_SESSION[$sess_view_header . '_master_name'] = stripslashes($record['master_name']);
	
	$disciplines = array(1);
	$sql = "SELECT dis_id from discipline_to_master where master_id='$master_id'";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $disciplines[] = $record['dis_id'];
	}
	$_SESSION[$sess_view_header . '_disciplines'] = $disciplines;
	
    $sql = "UPDATE am_users set lastlogin=now() where user_id='$user_id'";
    executeupdate($sql);
	
	$sql = "INSERT into sessions(ip, user_id, prospect_id, master_id, admin, master_logo, master_name, lastaction, sites) values(
	'" . $_SERVER['REMOTE_ADDR'] . "', '$user_id', '$prospect_id', '$master_id', '$admin', \"$master_logo\", \"" . go_escape_string($master_name) . "\", now(), \"$sites\")";
	executeupdate($sql);
	
	/*
	$sql = "SELECT force_change from am_users where user_id='$user_id'";
    $force_change = getsingleresult($sql);
    if($force_change){
      $_SESSION['sess_msg'] = "Your Asset Manager password has expired.  Please set a new one.";
      $_SESSION['am_force_change'] = 1;
    }
	*/
	
	/*
	$sql = "SELECT company_name from prospects where prospect_id='" . $prospect_id . "'";
    $company_name = stripslashes(getsingleresult($sql));
    $sql = "SELECT user_id from users where lastlogin like '" . date("Y-m-d") . "%' and fcslogins=1";
    $result = executequery($sql);
	$announce_ids = "";
    while($record = go_fetch_array($result)){
      $announce_ids .= "," . $record['user_id'] . ",";
    }
    //$announce_ids = ",1,";
    $announcement = $company_name . " has just logged into FCS.";
    $announcement = go_escape_string($announcement);

    $sql = "INSERT into announcements(date_time, users, announcement, user_id) 
    values(now(), \"$announce_ids\", \"$announcement\", '0')";
    executeupdate($sql);
	*/
	if($type=="fcsviewmobi") meta_redirect($FCS_URL . "mobile/index.php");
    meta_redirect($FCS_URL . "welcome.php");
	break;
  }
  
  case 0:{
    $_SESSION['sess_msg'] = "The email/password combination entered is not in the system.";
    meta_redirect($FCS_URL . "login.php");
	break;
  }
  
  default:{
    
    $_SESSION['sess_login'] = $email;
	$_SESSION['sess_password'] = $_POST['password'];
	meta_redirect($FCS_URL . "login_multi.php");
	
	/*
	$_SESSION['sess_msg'] = "The email/password combination entered is not in the system.";
    meta_redirect($FCS_URL . "login.php");
	*/
	break;
  }
  
}
} // end if fcsview
?>