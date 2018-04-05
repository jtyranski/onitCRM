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

if($submit1 == "Publish"){
  if($event_id=="new"){
    $sql = "INSERT into supercali_events(category_id, ro_user_id, complete, user_id) values(37, 62, 1, 2)"; // just to give it a user, make new items one of Jim's
	executeupdate($sql);
	$event_id = go_insert_id();
	$sql = "INSERT into supercali_dates(event_id, date, end_date) values('$event_id', '$enddate', '$enddate')";
	executeupdate($sql);
  }
  
  $sql = "UPDATE supercali_events set title=\"$title\", description=\"$description\", complete_date='$enddate', publish=1, publish_type='$publish_type'
  where event_id='$event_id'";
  executeupdate($sql);
  

	
  if (is_uploaded_file($_FILES['attachment']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['attachment']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['attachment']['tmp_name'], "../uploaded_files/programming/". $filename);
	
	$sql = "UPDATE supercali_events set attachment='$filename' where event_id='$event_id'";
	executeupdate($sql);
  }
  
  $sql = "UPDATE users set show_new_support=1";
  executeupdate($sql);
 
}

meta_redirect("index.php");
?>
