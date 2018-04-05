<?php
include "includes/functions.php";
if($_COOKIE['cookie_unsubscribe']==1){
  echo "You have recently added an email to the unsubscribe list.  Please wait a minute before adding more.";
  exit;
}

setcookie("cookie_unsubscribe", "1", time()+60);

$email = go_escape_string($_POST['email']);
$code = go_escape_string($_POST['code']);

$submit1 = $_POST['submit1'];

if($submit1 != ""){
  $sql = "SELECT opm_id from opm where code=\"$code\"";
  $opm_id = getsingleresult($sql);
  $sql = "SELECT count(*) from unsubscribe_productionmeeting where email=\"$email\" and opm_id='$opm_id'";
  $test = getsingleresult($sql);
  if($test==0){
    $sql = "INSERT into unsubscribe_productionmeeting(email, opm_id) values(\"$email\", '$opm_id')";
	executeupdate($sql);
  }
  
 
  $_SESSION['sess_msg'] = "$email has now been removed from this Production Meeting email list.";
}

meta_redirect("unsubscribe_productionmeeting.php?$code");
?>