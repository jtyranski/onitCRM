<?php include "includes/functions.php";

$id = $_POST['id'];
$email = go_escape_string($_POST['email']);

$submit1 = $_POST['submit1'];

if($submit1=="Update"){
  if($id=="new"){
    $sql = "INSERT into unsubscribe_admin(email) values(\"$email\")";
  }
  else {
    $sql = "UPDATE unsubscribe_admin set email=\"$email\" where id='$id'";
  }
  executeupdate($sql);
}

meta_redirect("unsubscribe.php");
?>