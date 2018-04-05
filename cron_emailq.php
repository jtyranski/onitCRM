<?php
include "includes/functions.php";

$sql = "SELECT email_threshhold from global_variables";
$email_threshhold = getsingleresult($sql);

include "mail_attachment.php";

$sql = "SELECT * from email_q where sent=0 and num_recipients <= $email_threshhold";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $id = stripslashes($record['id']);
  $to = stripslashes($record['to_field']);
  $subject = stripslashes($record['subject']);
  $message = stripslashes($record['message']);
  $headers = stripslashes($record['headers']);
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
  $sql = "UPDATE email_q set sent=1, ts_sent=now() where id='$id'";
  executeupdate($sql);
}
?>