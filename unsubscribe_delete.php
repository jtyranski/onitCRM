<?php include "includes/functions.php";

$id = $_GET['id'];

$sql = "DELETE from unsubscribe_admin where id='$id'";
executeupdate($sql);

meta_redirect("unsubscribe.php");
?>