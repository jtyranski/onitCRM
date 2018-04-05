<?php
include "includes/functions.php";

$act_id = $_GET['act_id'];
$personal = $_GET['personal'];
$sql = "UPDATE activities set display=0 where act_id='$act_id'";
executeupdate($sql);

$sql = "DELETE from supercali_events where act_id='$act_id' and act_id != 0";
executeupdate($sql);

meta_redirect("calendar.php?personal=$personal");
?>