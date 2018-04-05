<?php include "includes/header_white.php"; 

/*
$special_invoice = "";
$sql = "SELECT invoice_type from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$invoice_type = getsingleresult($sql);
if($invoice_type==2) $special_invoice = 2;
*/
//$special_invoice = "";
$special_invoice = 2;

$leak_id = $_GET['leak_id'];
if($leak_id=="") meta_redirect("welcome.php");

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
  $email_array = array_values($email_array);
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
<a href="fcs_sd_invoice_pdf<?=$special_invoice?>.php?leak_id=<?=$leak_id?>" target="_blank">Click here to view/print invoice</a><br><br>
<form action="fcs_sd_invoice_email.php" method="get">
<input type="hidden" name="leak_id" value="<?=$leak_id?>">
The following will be in the body of the email message:<br>
<textarea name="email_body" rows="7" cols="70">Attached you will find the invoice for your <?=$MAIN_CO_NAME?> Service Dispatch work.</textarea>
<br>
<input type="checkbox" name="email_include_signature" value="1" checked="checked">Include Signature

<br><br>
Send email to the following (one address per line):<br>
<textarea name="email_list" rows="7" cols="60"><?=$email_list?></textarea>
<br>
<input type="submit" name="submit1" value="Send Email">
</form>
<?php include "includes/footer.php"; ?>