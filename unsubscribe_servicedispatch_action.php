<?php
include "includes/functions.php";
if($_COOKIE['cookie_unsubscribe']==1){
  echo "You have recently added an email to the unsubscribe list.  Please wait a minute before adding more.";
  exit;
}

setcookie("cookie_unsubscribe", "1", time()+60);

$email = go_escape_string($_POST['email']);
$leak_id = go_escape_string($_POST['leak_id']);

$submit1 = $_POST['submit1'];

if($submit1 != ""){
  $sql = "SELECT count(*) from unsubscribe_servicedispatch where email=\"$email\" and leak_id='$leak_id'";
  $test = getsingleresult($sql);
  if($test==0){
    $sql = "INSERT into unsubscribe_servicedispatch(email, leak_id) values(\"$email\", '$leak_id')";
	executeupdate($sql);
  }
  
 
  $_SESSION['sess_msg'] = "$email has now been removed from this Service Dispatch email list.";
}

meta_redirect("unsubscribe_servicedispatch.php?$leak_id");
?>