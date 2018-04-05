<?php
include "includes/functions.php";
$to_user_id = $_POST['to_user_id'];
$message = go_escape_string($_POST['message']);
$submit1 = $_POST['submit1'];
$multi_ids = $_POST['multi_ids'];

if($submit1 =="Send"){
  $sql = "INSERT into comments(to_user_id, from_user_id, comment_time, comment, owner) values (
  '$to_user_id', '" . $SESSION_USER_ID . "', now(), \"$message\", '$to_user_id')";
  executeupdate($sql);
  $sql = "INSERT into comments(to_user_id, from_user_id, comment_time, comment, owner, new) values (
  '$to_user_id', '" . $SESSION_USER_ID . "', now(), \"$message\", '" . $SESSION_USER_ID . "', 0)";
  executeupdate($sql);
}

if($submit1== "Send Multiple"){
  for($x=0;$x<sizeof($multi_ids);$x++){
    $x_to_user_id = $multi_ids[$x];
	$sql = "INSERT into comments(to_user_id, from_user_id, comment_time, comment, owner) values (
    '$x_to_user_id', '" . $SESSION_USER_ID . "', now(), \"$message\", '$x_to_user_id')";
    executeupdate($sql);
    $sql = "INSERT into comments(to_user_id, from_user_id, comment_time, comment, owner, new) values (
    '$x_to_user_id', '" . $SESSION_USER_ID . "', now(), \"$message\", '" . $SESSION_USER_ID . "', 0)";
    executeupdate($sql);
  }
}
	

meta_redirect("index.php");
?>