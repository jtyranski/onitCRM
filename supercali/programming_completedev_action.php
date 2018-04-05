<?php
include "../includes/functions.php";

$event_id = go_escape_string($_POST['event_id']);
$title = go_escape_string($_POST['title']);
$description = go_escape_string($_POST['description']);
$publish_type = go_escape_string($_POST['publish_type']);
$enddate = go_escape_string($_POST['enddate']);

$submit1 = go_escape_string($_POST['submit1']);


$date_parts = explode("/", $enddate);
$enddate = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];

if($submit1 == "Update Description"){
  $sql = "UPDATE supercali_events set title=\"$title\", description=\"$description\"
  where event_id='$event_id'";
  executeupdate($sql);
}

if($submit1=="NOT COMPLETE"){
  $sql = "UPDATE supercali_events set complete=0, complete_dev=0 where event_id='$event_id'";
  executeupdate($sql);
}

if($submit1=="Complete Live"){
    $sql = "UPDATE supercali_events set complete=1, complete_date=now(), complete_dev=0 where event_id='$event_id'";
	executeupdate($sql);
}

meta_redirect("index.php");
?>
