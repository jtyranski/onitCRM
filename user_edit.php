<?php include "includes/header_white.php"; ?>
<script type="text/javascript" src="includes/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
<script src="includes/jquery-1.6.4.js"></script>
<script src="includes/jquery.maskedinput-1.3.js"></script>
<script src="includes/jquery.livequery.js"></script>
<script type="text/javascript"> 
$(".phone").livequery(function(){
    $(this).mask('(999) 999-9999');
});
</script>
<?php
$sql = "SELECT count(*) from toolbox_items where tool_master_id=2 and master_id='" . $SESSION_MASTER_ID . "'";
$USING_SD = getsingleresult($sql);

$user_id = $SESSION_USER_ID;
if($user_id != "new"){
  $sql = "SELECT master_id from users where user_id='$user_id'";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
  
  $sql = "SELECT *, date_format(password_change_date, \"%m/%d/%Y\") as pwdate from users where user_id='$user_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $firstname = stripslashes($record['firstname']);
  $lastname = stripslashes($record['lastname']);
  $email = stripslashes($record['email']);
  $office = stripslashes($record['office']);
  $extension = stripslashes($record['extension']);
  $title = stripslashes($record['title']);
  $cellphone = stripslashes($record['cellphone']);
  $cell_id = $record['cell_id'];
  $admin = $record['admin'];
  $report = $record['report'];
  $photo = $record['photo'];
  $pwdate = $record['pwdate'];
  $force_change = $record['force_change'];
  $enabled = $record['enabled'];
  $servicemen = $record['servicemen'];
  $resource = $record['resource'];
  
  
  $signature = stripslashes($record['signature']);
  $googlemap_zipcode = stripslashes($record['googlemap_zipcode']);
  
  $forcetime = stripslashes($record['forcetime']);
  $forcetime_start = stripslashes($record['forcetime_start']);
  $forcetime_end = stripslashes($record['forcetime_end']);
  
  $fcs_leakcheck = stripslashes($record['fcs_leakcheck']);
  
  $alert_inspection_approval = stripslashes($record['alert_inspection_approval']);
  $alert_inspection_approval_email = stripslashes($record['alert_inspection_approval_email']);
  $alert_inspection_approval_text = stripslashes($record['alert_inspection_approval_text']);
  
  $alert_dispatch_approval = stripslashes($record['alert_dispatch_approval']);
  $alert_dispatch_approval_email = stripslashes($record['alert_dispatch_approval_email']);
  $alert_dispatch_approval_text = stripslashes($record['alert_dispatch_approval_text']);
  
  $require_sd_approval = stripslashes($record['require_sd_approval']);
  $require_pre_approval = stripslashes($record['require_pre_approval']);
  $require_final_approval = stripslashes($record['require_final_approval']);
  
  $alert_inspection_scheduled_text = stripslashes($record['alert_inspection_scheduled_text']);
  $alert_inspection_scheduled_email = stripslashes($record['alert_inspection_scheduled_email']);
  $alert_contact_text = stripslashes($record['alert_contact_text']);
  $alert_contact_email = stripslashes($record['alert_contact_email']);
  $alert_meeting_text = stripslashes($record['alert_meeting_text']);
  $alert_meeting_email = stripslashes($record['alert_meeting_email']);
  
  $customer_portal_contact = stripslashes($record['customer_portal_contact']);
  
  $programming_schedule = stripslashes($record['programming_schedule']); // will be only for FCS
  $on_call_report = stripslashes($record['on_call_report']);
  $met1_goal = stripslashes($record['met1_goal']);
  $met2_goal = stripslashes($record['met2_goal']);
  $met3_goal = stripslashes($record['met3_goal']);
  $always_supercali = stripslashes($record['always_supercali']);
  
  $gets_sdemail = stripslashes($record['gets_sdemail']);
  $always_require_approval = stripslashes($record['always_require_approval']);
  
  $prospects_per_page = stripslashes($record['prospects_per_page']);
  $sd_results_per_page = stripslashes($record['sd_results_per_page']);
  
  $signature = stripslashes($record['signature']);
  $signature_block = stripslashes($record['signature_block']);
  
  
  
}

?>
<script>
function checkform(f){
  errmsg = "";
  //if(f.firstname.value==""){ errmsg += "Please enter first name. \n"; }
  //if(f.lastname.value==""){ errmsg += "Please enter last name. \n"; }
  if(f.email.value==""){ errmsg += "Please enter email. \n"; }
  <?php if($user_id=="new"){ ?>
  if(f.password.value==""){ errmsg += "Please enter a password. \n"; }
  <?php } ?>
  
  if(errmsg == ""){
    return(true);
  }
  else {
    alert(errmsg);
	return(false);
  }
}


function DelSig(){
  cf = confirm("Are you sure you want to delete this signature image?");
  if(cf){
    document.location.href="user_edit_delsig.php?user_id=<?=$user_id?>&type=user";
  }
}
</script>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<form action="user_edit_action.php"  method="post" onsubmit="return checkform(this)" enctype="multipart/form-data" name="form1">

<div style="width:100%; position:relative;" class="main">
<div style="float:left; width:50%;">

<table class="main">
<tr id="firstname">
<td align="right">First Name</td>
<td><input type="text" name="firstname" value="<?=$firstname?>"></td>
</tr>
<tr id="lastname">
<td align="right">Last Name</td>
<td><input type="text" name="lastname" value="<?=$lastname?>"></td>
</tr>
<tr>
<td align="right">Email</td>
<td><input type="text" name="email" value="<?=$email?>"></td>
</tr>
<tr>
<td align="right">Password</td>
<td><input type="password" name="password"></td>
</tr>

<tr id="extension">
<td align="right">Extension</td>
<td><input type="text" name="office" size="11" value="<?=$office?>"> x <input type="text" name="extension" size="4" value="<?=$extension?>"></td>
</tr>
<tr id="title">
<td align="right">Title</td>
<td><input type="text" name="title" value="<?=$title?>"></td>
</tr>
<tr id="cellphone">
<td align="right">Cell Phone</td>
<td><input type="text" name="cellphone" value="<?=$cellphone?>" class="phone"></td>
</tr>
<tr id="cellprovider">
<td align="right">Cell Provider</td>
<td>
<select name="cell_id">
<option value="0">
<?php
$sql = "SELECT * from cell_providers order by cell_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['cell_id']?>"<?php if($cell_id == $record['cell_id']) echo " selected";?>><?=stripslashes($record['cell_name'])?></option>
  <?php
}
?>
</select>
</td>
</tr>
<tr>
<td align="right">Zip Code at<br>Current Location</td>
<td><input type="text" name="googlemap_zipcode" value="<?=$googlemap_zipcode?>"></td>
</tr>

<tr>
<td align="right">Headshot</td>
<td><input type="file" name="photo"></td>
</tr>
<?php if($photo != ""){ ?>
<tr>
<td colspan="2">
<img src="<?=$UPLOAD?>headshots/<?=$photo?>">
</td>
</tr>
<?php } ?>
<tr>
<td align="right">Signature</td>
<td><input type="file" name="signature"></td>
</tr>

<?php if($signature != ""){ ?>
<tr>
<td colspan="2">
<img src="<?=$UPLOAD?>headshots/<?=$signature?>">
<br>
<a href="javascript:DelSig()">DELETE SIGNATURE IMAGE</a>
</td>
</tr>
<?php } ?>
<tr>
<td align="right" valign="top">OR Use Signature</td>
<td><textarea name="signature_block" rows="3" cols="45"><?=$signature_block?></textarea></td>
</tr>
<tr>
<td colspan="2">Contact records to display per page: <input type="text" name="prospects_per_page" value="<?=$prospects_per_page?>" size="5"></td>
</tr>
<?php if($USING_SD){ ?>
<tr>
<td colspan="2">Service Dispatch results to display per page: <input type="text" name="sd_results_per_page" value="<?=$sd_results_per_page?>" size="5"></td>
</tr>
<?php } ?>
</table>
<?php
$help_inspection_system = "On a new inspection requiring approval, an icon will appear at the top of the Core screen.";
$help_inspection_email = "On a new inspection requiring approval, an email will be sent to your account.";
$help_inspection_text = "On a new inspection requiring approval, a text will be sent to your phone.";

$help_sd_system = "On a new service dispatch requiring approval, an icon will appear at the top of the Core screen.";
$help_sd_email = "On a new service dispatch requiring approval, an email will be sent to your account.";
$help_sd_text = "On a new service dispatch requiring approval, a text will be sent to your phone.";

$help_inspection_schedule_email = "When an Inspection activity is scheduled for you, you will receive an email.";
$help_inspection_schedule_text = "When an Inspection activity is scheduled for you, you will receive a text.";

$help_meeting_schedule_email = "When a Meeting activity is scheduled for you, you will receive an email.";
$help_meeting_schedule_text = "When a Meeting activity is scheduled for you, you will receive a text.";

$help_contact_schedule_email = "When a Contact activity is scheduled for you, you will receive an email.";
$help_contact_schedule_text = "When a Contact activity is scheduled for you, you will receive a text.";

$help_customer_portal = "On your customer\'s version of the site, they have the ability to compose a message to your company.<br>";
$help_customer_portal .= "Check this box if you would like your email address to be included in the list of recipients.";

$help_sd2_email = "Whenever the status of a Service Dispatch changes, an email is sent with updated information about the dispatch.<br>";
$help_sd2_email .= "Check this box if you would like your email address to be included in the list of recipients.";

$help_gets_sd_email = "When performing a Service Dispatch using the mobile app, a copy of the proposal is emailed to the client.<br>";
$help_gets_sd_email .= "Also, the app can send a copy of the Service Dispatch invoice.";
$help_gets_sd_email .= "Check this box if you would like your email address to be included in the list of recipients.";

$help_productionmeeting = "Once a project is scheduled, our system sends out an email each morning to the client letting them know<br>";
$help_productionmeeting .= "about the project, and the impending start date.<br>";
$help_productionmeeting .= "Check this box if you would like your email address to be included in the list of recipients.";

$help_opm = "Each day, our system sends out a daily progress report of the work performed on your project, with photos, descriptions,<br>";
$help_opm .= "and indication of percentage complete.<br>";
$help_opm .= "Check this box if you would like your email address to be included in the list of recipients.";

?>
<div id="alerts">
<strong>Alerts</strong><br>
<table class="main">
<tr>
<td colspan="2">Inspection Approval</td>
</tr>
<tr>
<td align="right">System Alert <a alt="" onMouseOver="return overlib('<?=$help_inspection_system?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="alert_inspection_approval" value="1"<?php if($alert_inspection_approval==1) echo " checked";?>>Yes</td>
</tr>
<tr>
<td align="right">Email Alert <a alt="" onMouseOver="return overlib('<?=$help_inspection_email?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="alert_inspection_approval_email" value="1"<?php if($alert_inspection_approval_email==1) echo " checked";?>>Yes</td>
</tr>
<tr>
<td align="right">Text Alert <a alt="" onMouseOver="return overlib('<?=$help_inspection_text?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="alert_inspection_approval_text" value="1"<?php if($alert_inspection_approval_text==1) echo " checked";?>>Yes</td>
</tr>
<?php if($USING_SD){ ?>
<tr>
<td colspan="2">Service Dispatch Approval</td>
</tr>
<tr>
<td align="right">System Alert <a alt="" onMouseOver="return overlib('<?=$help_sd_system?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="alert_dispatch_approval" value="1"<?php if($alert_dispatch_approval==1) echo " checked";?>>Yes</td>
</tr>
<tr>
<td align="right">Email Alert <a alt="" onMouseOver="return overlib('<?=$help_sd_email?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="alert_dispatch_approval_email" value="1"<?php if($alert_dispatch_approval_email==1) echo " checked";?>>Yes</td>
</tr>
<tr>
<td align="right">Text Alert <a alt="" onMouseOver="return overlib('<?=$help_sd_text?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="alert_dispatch_approval_text" value="1"<?php if($alert_dispatch_approval_text==1) echo " checked";?>>Yes</td>
</tr>

<?php } ?>
<tr>
<td colspan="2">&nbsp;</td>
</tr>
<tr>
<td colspan="2">Inspection Scheduled</td>
</tr>
<tr>
<td align="right">Email Alert <a alt="" onMouseOver="return overlib('<?=$help_inspection_schedule_email?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="alert_inspection_scheduled_email" value="1"<?php if($alert_inspection_scheduled_email==1) echo " checked";?>>Yes</td>
</tr>
<tr>
<td align="right">Text Alert <a alt="" onMouseOver="return overlib('<?=$help_inspection_schedule_text?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="alert_inspection_scheduled_text" value="1"<?php if($alert_inspection_scheduled_text==1) echo " checked";?>>Yes</td>
</tr>
<tr>
<td colspan="2">Meeting Scheduled</td>
</tr>
<tr>
<td align="right">Email Alert <a alt="" onMouseOver="return overlib('<?=$help_meeting_schedule_email?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="alert_meeting_email" value="1"<?php if($alert_meeting_email==1) echo " checked";?>>Yes</td>
</tr>
<tr>
<td align="right">Text Alert <a alt="" onMouseOver="return overlib('<?=$help_meeting_schedule_text?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="alert_meeting_text" value="1"<?php if($alert_meeting_text==1) echo " checked";?>>Yes</td>
</tr>
<tr>
<td colspan="2">Contact Scheduled</td>
</tr>
<tr>
<td align="right">Email Alert <a alt="" onMouseOver="return overlib('<?=$help_contact_schedule_email?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="alert_contact_email" value="1"<?php if($alert_contact_email==1) echo " checked";?>>Yes</td>
</tr>
<tr>
<td align="right">Text Alert <a alt="" onMouseOver="return overlib('<?=$help_contact_schedule_text?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="alert_contact_text" value="1"<?php if($alert_contact_text==1) echo " checked";?>>Yes</td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
</tr>
<tr>
<td colspan="2">When customer sends email from the customer portal:</td>
</tr>
<tr>
<td align="right">Include Me <a alt="" onMouseOver="return overlib('<?=$help_customer_portal?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="customer_portal_contact" value="1"<?php if($customer_portal_contact==1) echo " checked";?>>Yes</td>
</tr>
<?php if($USING_SD){ ?>
<tr>
<td colspan="2">When emailing a service dispatch proposal from the app:</td>
</tr>
<tr>
<td align="right">Include Me <a alt="" onMouseOver="return overlib('<?=$help_gets_sd_email?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="gets_sdemail" value="1"<?php if($gets_sdemail==1) echo " checked";?>>Yes</td>
</tr>
<?php } ?>

</table>
</div>
<br><br>
<input type="submit" name="submit1" value="Update User">
<br><br><br><br><br>
</div>

</div>
</div>
</div>


</div>
</div>


</form>


</div>




