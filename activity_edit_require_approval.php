<?php
include "includes/functions.php";

$user_id = $_GET['user_id'];
$event = $_GET['event'];

if($event=="Inspection"){
  $sql = "SELECT require_pre_approval from users where user_id='$user_id'";
  $foo = getsingleresult($sql);
  if($foo==1){
    ?>
	document.getElementById('needs_approval').checked=true;
	<?php
  }
  else {
    ?>
	document.getElementById('needs_approval').checked=false;
	<?php
  }
}
?>