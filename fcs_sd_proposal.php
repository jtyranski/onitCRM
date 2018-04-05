<?php include "includes/header_white.php"; 


$leak_id = $_GET['leak_id'];
if($leak_id=="") meta_redirect("welcome.php");

$sql = "SELECT status, date_format(confirm_date, \"%Y\") as cd from am_leakcheck where leak_id='$leak_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
if($record['status'] != "Confirmed" && $record['status'] != "Invoiced" && $record['status'] != "Closed Out"){ // if not yet confirmed, but not if passed it
  $sql = "UPDATE am_leakcheck set status='Confirmed'";
  if($record['cd']=="0000") $sql .= ", confirm_date=now()";
  $sql .= " where leak_id='$leak_id'";
  executeupdate($sql);
  
  $subject = "Service Dispatch Confirm";
  $sql = "SELECT b.master_name, b.dispatch_from_email, b.master_id from master_list b where b.master_id='" . $SESSION_MASTER_ID . "'";
	  $result = executequery($sql);
      $record = go_fetch_array($result);
      $master_name = stripslashes($record['master_name']);
	  $dispatch_email_from = stripslashes($record['dispatch_from_email']);
      $mail_from_email = $dispatch_email_from;
      $mail_from_name = $master_name;
  
  
      include "sd_email.php";
      $sql = "SELECT body from templates where name='Leakcheck - General'";
      $body = stripslashes(getsingleresult($sql));
      $body = str_replace("[MESSAGE]", $table, $body);
  

      $headers = "Content-type: text/html; charset=iso-8859-1\n";
      $headers .= "From: $mail_from_name <$mail_from_email>\n";
      $headers .= "Return-Path: $mail_from_email\n";  // necessary for some emails such as aol
	  $headers_fcs = $headers . "Bcc: $fcs_email\n";
      $headers_ro = $headers . "Bcc: $ro_email\n";
  
      if($fcs_email != "") email_q("", $subject, $body, $headers_fcs);
  
      $ro_body = $body;
      if($ro_email != "") email_q("", $subject, $ro_body, $headers_ro);
}

$sql = "SELECT property_id from am_leakcheck where leak_id='$leak_id'";
$property_id = getsingleresult($sql);

$sql = "SELECT prospect_id from properties where property_id='$property_id'";
$prospect_id = getsingleresult($sql);

$sql = "SELECT email from contacts where email != '' and property_id='$property_id'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $email_array[] = stripslashes($record['email']);
}
$sql = "SELECT email from contacts where email != '' and prospect_id='$prospect_id'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $email_array[] = stripslashes($record['email']);
}



if(is_array($email_array)){
  $email_array = array_unique($email_array);
  
  for($x=0;$x<sizeof($email_array);$x++){
    $email_list .= $email_array[$x] . "\n";
  }
}
?>

<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<a href="<?=$FCS_URL?>report_print_presentation_pdf_property.php?property_id=<?=$property_id?>&ipod=1" target="_blank">Preview Inspection Report Section of Proposal</a><br>
<a href="<?=$CORE_URL?>public_nocostproposal_fcs_pdf_unsigned.php?pdf_output=I&leak_id=<?=$leak_id?>" target="_blank">Preview Signature Section of Propsoal</a><br>
<form action="fcs_sd_proposal_email.php" method="get">
<input type="hidden" name="leak_id" value="<?=$leak_id?>">
The following will be in the body of the email message:<br>
<textarea name="email_body" rows="7" cols="70">Attached you will find the inspection report for your roof, and a copy of proposal for you to sign.</textarea>
<br>
<input type="checkbox" name="email_include_signature" value="1" checked="checked">Include Signature

<br><br>
Send email to the following (one address per line):<br>
<textarea name="email_list" rows="7" cols="60"><?=$email_list?></textarea>
<br>
<input type="submit" name="submit1" value="Send Email">
<br>
(You may see a bunch of random characters briefly show up on your screen after clicking Send Email. This is normal, as it is generating the pdf file to attach.)
</form>
<?php include "includes/footer.php"; ?>