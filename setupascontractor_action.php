<?php
include "includes/functions.php";

 // this is just for fcs login

$prospect_id = go_escape_string($_POST['prospect_id']);
$x = go_escape_string($_POST['x']);
$master_name = go_escape_string($_POST['master_name']);
$address = go_escape_string($_POST['address']);
$city = go_escape_string($_POST['city']);
$state = go_escape_string($_POST['state']);
$zip = go_escape_string($_POST['zip']);

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$admin = $_POST['admin'];
$password = $_POST['password'];

$submit1 = $_POST['submit1'];

if($submit1 != ""){
  $sql = "INSERT into master_list(master_name, address, city, state, zip, date_created, active, payment_terms) values(\"$master_name\", \"$address\", \"$city\", \"$state\", \"$zip\", now(), 1, 
  'I hereby authorize the work indicated above\nPayment terms: 30 days from completion of work')";
  executeupdate($sql);
  $master_id = go_insert_id();
  
    $sql = "SELECT * from def_list where visible=1";
    $res2 = executequery($sql);
    while($rec2 = go_fetch_array($res2)){
      $def_name = go_escape_string($rec2['def_name']);
	  $def_name_spanish = go_escape_string($rec2['def_name_spanish']);
	  $def = go_escape_string($rec2['def']);
	  $corrective_action = go_escape_string($rec2['corrective_action']);
	
	  $sql = "INSERT into def_list_master(master_id, def_name, def, corrective_action, def_name_spanish) values('$master_id', 
	  \"$def_name\", \"$def\", \"$corrective_action\", \"$def_name_spanish\")";
	  executeupdate($sql);
    }
  
  $sql = "UPDATE prospects set created_master_id = '$master_id' where prospect_id='$prospect_id'";
  executeupdate($sql);
  
  for($y = 0;$y<sizeof($email);$y++){
    if($email[$y] != ""){
      $sql = "INSERT into users(master_id, firstname, lastname, email, password, admin) values('$master_id', \"" . $firstname[$y] . "\", 
	  \"" . $lastname[$y] . "\", \"" . $email[$y] . "\", \"" . md5($password[$y]) . "\", '" . $admin[$y] . "')";
	  executeupdate($sql);
	}
  }
  
  $x++;
  if($x >= sizeof($_SESSION['list_prospect_id'])){
    meta_redirect("contacts.php");
  }
  else{
    meta_redirect("setupascontractor.php?x=$x");
  }
}




?>