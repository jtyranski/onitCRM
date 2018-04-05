<?php
include "includes/functions.php";


$master_id = $_GET['master_id'];
$active = $_GET['active'];

$sql = "UPDATE master_list set active='$active' where master_id='$master_id'";
executeupdate($sql);

meta_redirect("contacts.php");
?>