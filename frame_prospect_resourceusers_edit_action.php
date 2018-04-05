<?php
include "includes/functions.php";

$user_id = $_POST['user_id'];
$prospect_id = $_POST['prospect_id'];
if($user_id != "new"){
  $sql = "SELECT master_id from users where user_id='$user_id'";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
}


$firstname = go_escape_string($_POST['firstname']);
$lastname = go_escape_string($_POST['lastname']);
$email = go_escape_string($_POST['email']);
$password = go_escape_string($_POST['password']);
$submit1 = go_escape_string($_POST['submit1']);
$googlemap_zipcode = go_escape_string($_POST['googlemap_zipcode']);

$admin = 0;
$enabled=1;
$resource = 1;


if($submit1 != ""){
  if($user_id=="new"){
    for($x=0;$x<=9;$x++){
      $chars[] = $x;
    }

    for($x=65;$x<=90;$x++){
      $chars[] = chr($x);
    }

    for($x=97;$x<=122;$x++){
      $chars[] = chr($x);
    }
	for($x=0;$x<25;$x++){
      $y = rand(0, sizeof($chars));
	  $token .= $chars[$y];
    }
	$sql = "SELECT user_id from users where email=\"$email\"";
	$test = getsingleresult($sql);
	if($test){
	  $_SESSION['sess_msg'] = "A user with the email of $email already exists in the system.";
	  meta_redirect("admin_users_edit.php?user_id=new");
	}
	$sql = "INSERT into users(master_id, email) values('" . $SESSION_MASTER_ID . "', \"$email\")";
	executeupdate($sql);
	$user_id = go_insert_id();

  }
  
    $sql = "UPDATE users set firstname=\"$firstname\", lastname=\"$lastname\", email=\"$email\", ";
	if($password != "") $sql .= " password = \"" . md5($password) . "\", ";
	$sql .= " admin='$admin', 
	enabled=\"$enabled\", googlemap_zipcode=\"$googlemap_zipcode\", resource=\"$resource\", 
	resource_id='$prospect_id'
	where user_id='$user_id' and master_id='" . $SESSION_MASTER_ID . "'";
	executeupdate($sql);
  
  

}

if($_SESSION['temp_redirect'] != ""){
  $rd = $_SESSION['temp_redirect'];
  $_SESSION['temp_redirect'] = "";
  meta_redirect($rd, 0, 1);
}

meta_redirect("frame_prospect_resourceusers.php?prospect_id=$prospect_id");
?>
