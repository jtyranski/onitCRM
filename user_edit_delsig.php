<?php 
include "includes/functions.php";

$user_id = go_escape_string($_GET['user_id']);
$type = $_GET['type'];

$redirect = "user_edit.php";
if($type=="admin"){
  if($SESSION_ISADMIN==0) exit;
  $sql = "SELECT master_id from users where user_id=\"$user_id\"";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
  $redirect = "admin_users_edit.php?user_id=$user_id";
}
else {
  if($user_id != $SESSION_USER_ID) exit;
}

$sql = "SELECT signature from users where user_id=\"$user_id\"";
$signature = getsingleresult($sql);
if($signature != "") @unlink("uploaded_files/headshots/$signature");
$sql = "UPDATE users set signature='' where user_id=\"$user_id\"";
executeupdate($sql);

meta_redirect($redirect);
?>