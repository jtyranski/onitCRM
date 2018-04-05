<?php
include "includes/functions.php";

$id = $_GET['id'];

$sql = "SELECT firstname, lastname, email from contacts where id='$id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$firstname = stripslashes($record['firstname']);
$lastname = stripslashes($record['lastname']);
$email = stripslashes($record['email']);

$firstname = jsclean($firstname);
$lastname = jsclean($lastname);
$email = jsclean($email);
?>
document.getElementById('firstname').value="<?=$firstname?>";
document.getElementById('lastname').value="<?=$lastname?>";
document.getElementById('email').value="<?=$email?>";