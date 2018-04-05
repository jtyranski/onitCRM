<?php
include "../includes/functions.php";

$show_userid = $_GET['show_userid'];
$show_what = $_GET['show_what'];
$show_type = $_GET['show_type'];
$show_crew = $_GET['show_crew'];
$show_complete = $_GET['show_complete'];
$opp_id = $_GET['opp_id'];

$sql = "UPDATE opportunities set production_queue = 0 where opp_id='$opp_id'";
executeupdate($sql);

meta_redirect("index.php?show_userid=$show_userid&show_what=$show_what&show_type=$show_type&show_crew=$show_crew&show_complete=$show_complete");
?>