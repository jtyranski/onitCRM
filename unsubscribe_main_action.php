<?php
include "includes/functions.php";
if($_COOKIE['cookie_unsubscribe']==1){
  echo "You have recently added an email to the unsubscribe list.  Please wait a minute before adding more.";
  exit;
}

setcookie("cookie_unsubscribe", "1", time()+60);

$email = go_escape_string($_POST['email']);

$submit1 = $_POST['submit1'];

if($submit1 != ""){
  $sql = "SELECT count(*) from unsubscribe_admin where email=\"$email\"";
  $test = getsingleresult($sql);
  if($test==0){
    $sql = "INSERT into unsubscribe_admin(email) values(\"$email\")";
	executeupdate($sql);
  }
  
 
  $_SESSION['sess_msg'] = "$email has now been removed from this email list.";
}

meta_redirect("unsubscribe_main.php");
?>