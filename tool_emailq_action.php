<?php
include "includes/functions.php";

$email_ids = $_POST['email_ids'];
$submit1 = $_POST['submit1'];
$sent = $_POST['sent'];

$master_id = $_POST['master_id'];
$to_filter = $_POST['to'];
$from = $_POST['from'];
$searchby = $_POST['searchby'];
$custom_startdate = $_POST['custom_startdate'];
$custom_enddate = $_POST['custom_enddate'];
$timerange = $_POST['timerange'];
$custom_starttime = $_POST['custom_starttime'];
$custom_endtime = $_POST['custom_endtime'];
$custom_starttime_ampm = $_POST['custom_starttime_ampm'];
$custom_endtime_ampm = $_POST['custom_endtime_ampm'];

$sql = "SELECT can_email_q from users where user_id='" . $SESSION_USER_ID . "'";
$CAN_EMAIL_Q = getsingleresult($sql);

include "mail_attachment.php";

if($submit1=="delete"){
  if(is_array($email_ids)){
    for($x=0;$x<sizeof($email_ids);$x++){
	  $id = $email_ids[$x];
	  $sql = "SELECT from_user_id from email_q where id='$id'";
	  $from_user_id = stripslashes(getsingleresult($sql));
	  if($CAN_EMAIL_Q==0){
	    if($from_user_id != $SESSION_USER_ID) continue;
	  }
	  $sql = "DELETE from email_q where id='$id'";
	  executeupdate($sql);
	}
  }
}

if($submit1=="send"){
  if(is_array($email_ids)){
    for($x=0;$x<sizeof($email_ids);$x++){
	  $id = $email_ids[$x];
	  $sql = "SELECT * from email_q where id='$id'";
	  $result = executequery($sql);
	  $record = go_fetch_array($result);
	  $to = stripslashes($record['to_field']);
	  $subject = stripslashes($record['subject']);
	  $message = stripslashes($record['message']);
	  $headers = stripslashes($record['headers']);
	  $attachment_url = stripslashes($record['attachment_url']);
	  $from_user_id = stripslashes($record['from_user_id']);
	  if($CAN_EMAIL_Q==0){
	    if($from_user_id != $SESSION_USER_ID) continue;
	  }
      $force_real_attach = stripslashes($record['force_real_attach']);
      if($force_real_attach){
        $attachment = stripslashes($record['attachment']);
    	$headers_array = explode("\n", $headers);
	    $headers = "";
	    for($x=0;$x<sizeof($headers_array);$x++){
	      if(go_reg("Content\-type", $headers_array[$x])) continue;
	      if($headers_array[$x]=="") continue;
	      $headers .= $headers_array[$x] . "\n";
	    }
	    $headers = go_reg_replace("\n$", "", $headers);
	    mail_attachment($to, $subject, $message, $headers, $attachment);
      } else { 
        $attachment_url = stripslashes($record['attachment_url']);
        if($attachment_url != "") $message .= "<br><br><a href='" . $attachment_url . "'>View Attachment</a>";
        @mail($to, $subject, $message, $headers);
      }
	  $sql = "UPDATE email_q set sent=1, ts_sent=now(), sent_user_id='" . $SESSION_USER_ID . "' where id='$id'";
	  executeupdate($sql);
	}
  }
}
	  
if($submit1=="threshhold"){
  if($CAN_EMAIL_Q){
    $email_threshhold = go_escape_string($_POST['email_threshhold']);
    $sql = "UPDATE global_variables set email_threshhold='$email_threshhold'";
    executeupdate($sql);
  }
}


meta_redirect("tool_emailq.php?sent=$sent&master_id=$master_id&to=$to_filter&from=$from&searchby=$searchby&timerange=$timerange&custom_startdate=$custom_startdate&custom_enddate=$custom_enddate&custom_starttime=$custom_starttime&custom_endtime=$custom_endtime&custom_starttime_ampm=$custom_starttime_ampm&custom_endtime_ampm=$custom_endtime_ampm");
?>