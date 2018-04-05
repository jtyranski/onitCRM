<?php include "includes/functions.php"; ?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
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

$sql = "SELECT use_ops from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$USING_OPS = getsingleresult($sql);
  
$user_id = $_GET['user_id'];
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
  $signature_block = stripslashes($record['signature_block']);
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
  
  $on_sdemail_list = stripslashes($record['on_sdemail_list']);
  
  $customer_portal_contact = stripslashes($record['customer_portal_contact']);
  $user_level = stripslashes($record['user_level']);
  $irep = stripslashes($record['irep']);
  $gets_sdemail = stripslashes($record['gets_sdemail']);
  $always_require_approval = stripslashes($record['always_require_approval']);
  $can_export = stripslashes($record['can_export']);
  $sales_activity_report = stripslashes($record['sales_activity_report']);
  
  $programming_schedule = stripslashes($record['programming_schedule']); // will be only for FCS
  $programming_alert = stripslashes($record['programming_alert']); // will be only for FCS
  $on_call_report = stripslashes($record['on_call_report']);
  $met1_goal = stripslashes($record['met1_goal']);
  $met2_goal = stripslashes($record['met2_goal']);
  $met3_goal = stripslashes($record['met3_goal']);
  $always_supercali = stripslashes($record['always_supercali']);
  
  $language = stripslashes($record['language']);
  $vehicle_id = stripslashes($record['vehicle_id']);
  
  
  $bcc_productionmeeting = stripslashes($record['bcc_productionmeeting']);
  $bcc_opm = stripslashes($record['bcc_opm']);
  
  $can_email_q = stripslashes($record['can_email_q']);
  $can_edit_ops = stripslashes($record['can_edit_ops']);
  
  $groups = stripslashes($record['groups']);
  $subgroups = stripslashes($record['subgroups']);
  
  $prospects_per_page = stripslashes($record['prospects_per_page']);
  $sd_results_per_page = stripslashes($record['sd_results_per_page']);
  
}

?>
<script type="text/javascript" src="includes/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
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



function check_forcetime(x){
  if(x.checked==true){
    document.getElementById('forcetime_area').style.display="";
  }
  else{
    document.getElementById('forcetime_area').style.display="none";
  }
}


function check_resource(x){
  if(x.checked==true){
    document.getElementById('company').style.display="";
	document.getElementById('firstname').style.display="none";
	document.getElementById('lastname').style.display="none";
	document.getElementById('alerts').style.display="none";
	document.getElementById('right_side_boxes').style.display="none";
	document.getElementById('extension').style.display="none";
	document.getElementById('title').style.display="none";
	document.getElementById('cellphone').style.display="none";
	document.getElementById('cellprovider').style.display="none";
  }
  else {
    document.getElementById('company').style.display="none";
	document.getElementById('firstname').style.display="";
	document.getElementById('lastname').style.display="";
	document.getElementById('alerts').style.display="";
	document.getElementById('right_side_boxes').style.display="";
	document.getElementById('extension').style.display="";
	document.getElementById('title').style.display="";
	document.getElementById('cellphone').style.display="";
	document.getElementById('cellprovider').style.display="";
  }
}

function OpenClose(group, x){
  if(x==1){
    document.getElementById('group_' + group).style.display="";
	document.getElementById('group_' + group + '_arrow').innerHTML = "<a href=\"javascript:OpenClose('" + group + "', '0')\" style=\"text-decoration:none; font-weight:bold; font-size:14px;\">-</a>";
  }
  else {
    document.getElementById('group_' + group).style.display="none";
	document.getElementById('group_' + group + '_arrow').innerHTML = "<a href=\"javascript:OpenClose('" + group + "', '1')\" style=\"text-decoration:none; font-weight:bold; font-size:14px;\">+</a>";
  }
}

function group_core(x){
  var form='form1'; //Give the form name here
  if(x.checked==true){
    dml=document.forms[form];
    len = dml.elements.length;
    var i=0;
    for( i=0 ; i<len ; i++) {
      if (dml.elements[i].name=="group_list[]" && dml.elements[i].disabled==false) {
        dml.elements[i].checked=false;
      }
	  if (dml.elements[i].name=="subgroup_list[]" && dml.elements[i].disabled==false) {
        dml.elements[i].checked=false;
      }
    }
  }
  
}

function DelSig(){
  cf = confirm("Are you sure you want to delete this signature image?");
  if(cf){
    document.location.href="user_edit_delsig.php?user_id=<?=$user_id?>&type=admin";
  }
}
</script>

<body>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<a href="admin_users.php">Back to User List</a><br>
<form action="admin_users_edit_action.php"  method="post" onSubmit="return checkform(this)" enctype="multipart/form-data" name="form1">
<input type="hidden" name="user_id" value="<?=$user_id?>">

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
<td align="right">Vehicle ID</td>
<td><input type="text" name="vehicle_id" value="<?=$vehicle_id?>"></td>
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
<td colspan="2">When any service dispatch updates get emailed:</td>
</tr>
<tr>
<td align="right">Include Me <a alt="" onMouseOver="return overlib('<?=$help_sd2_email?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="on_sdemail_list" value="1"<?php if($on_sdemail_list==1) echo " checked";?>>Yes</td>
</tr>
<tr>
<td colspan="2">When emailing a service dispatch proposal from the app:</td>
</tr>
<tr>
<td align="right">Include Me <a alt="" onMouseOver="return overlib('<?=$help_gets_sd_email?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="gets_sdemail" value="1"<?php if($gets_sdemail==1) echo " checked";?>>Yes</td>
</tr>
<?php } ?>
<?php if($USING_OPS){ ?>
<tr>
<td colspan="2">When Project Start emails go out:</td>
</tr>
<tr>
<td align="right">Include Me <a alt="" onMouseOver="return overlib('<?=$help_productionmeeting?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="bcc_productionmeeting" value="1"<?php if($bcc_productionmeeting==1) echo " checked";?>>Yes</td>
</tr>
<tr>
<td colspan="2">When OPM emails go out:</td>
</tr>
<tr>
<td align="right">Include Me <a alt="" onMouseOver="return overlib('<?=$help_opm?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="checkbox" name="bcc_opm" value="1"<?php if($bcc_opm==1) echo " checked";?>>Yes</td>
</tr>
<?php } ?>


</table>
</div>

<input type="submit" name="submit1" value="Update User">
<br />
<br />
<br />
<br />
<br />
<br />
<br />

</div>


<div style="float:left; width:50%;">
<div id="right_side_boxes">
<input type="submit" name="submit1" value="Update User">
<table class="main">
<?php if($SESSION_MASTER_ID==1){ ?>
<tr>
<td align="right">On Programming Schedule?</td>
<td><input type="checkbox" name="programming_schedule" value="1"<?php if($programming_schedule) echo " checked";?>> Yes</td>
</tr>
<tr>
<td align="right">Receive stage notifications?</td>
<td><input type="checkbox" name="programming_alert" value="1"<?php if($programming_alert) echo " checked";?>> Yes</td>
</tr>
<?php } ?>
<tr>
<td align="right">Account Enabled?</td>
<td><input type="checkbox" name="enabled" value="1"<?php if($enabled) echo " checked";?>> Yes</td>
</tr>
<tr>
<td align="right">Login to Admin?</td>
<td><input type="checkbox" name="admin" value="1"<?php if($admin) echo " checked";?>> Yes</td>
</tr>
<tr>
<td align="right">Independent Rep?</td>
<td><input type="checkbox" name="irep" value="1"<?php if($irep) echo " checked";?>> Yes</td>
</tr>
<tr>
<td align="right">Can Export to Excel?</td>
<td><input type="checkbox" name="can_export" value="1"<?php if($can_export) echo " checked";?>> Yes</td>
</tr>
<tr>
<td align="right">On Sales Activity Report?</td>
<td><input type="checkbox" name="sales_activity_report" value="1"<?php if($sales_activity_report) echo " checked";?>> Yes</td>
</tr>
<?php if($USING_OPS){ ?>
<tr>
<td align="right">Can Edit Ops?</td>
<td><input type="checkbox" name="can_edit_ops" value="1"<?php if($can_edit_ops) echo " checked";?>> Yes</td>
</tr>
<?php } ?>
<?php if($SESSION_MASTER_ID==1){?>
<tr>
<td align="right">Can Send All in Email Queue?</td>
<td><input type="checkbox" name="can_email_q" value="1"<?php if($can_email_q) echo " checked";?>> Yes</td>
</tr>
<?php } ?>
<tr>
<td align="right">User Level</td>
<td>
<select name="user_level">
<option value="User"<?php if($user_level=="User") echo " selected";?>>User</option>
<option value="Manager"<?php if($user_level=="Manager") echo " selected";?>>Manager</option>
</select>
</td>
</tr>
<tr>
<td align="right">Default Language (app)</td>
<td>
<select name="language">
<option value="English"<?php if($language=="English") echo " selected";?>>English</option>
<option value="Spanish"<?php if($language=="Spanish") echo " selected";?>>Spanish</option>
</select>
</td>
</tr>

</table>



<?php if($SESSION_MASTER_ID==1){ 
$sql = "SELECT * from activities_met_options order by met_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $met_id = $record['met_id'];
  $met_name = stripslashes($record['met_name']);
  $MET[$met_id] = $met_name;
}

?>
<br>
<table class="main">
<tr>
<td align="right">Always add activities<br>to the calendar?</td>
<td><input type="checkbox" name="always_supercali" value="1"<?php if($always_supercali) echo " checked";?>> Yes</td>
</tr>
<tr>
<td align="right">On Prospecting Report?</td>
<td><input type="checkbox" name="on_call_report" value="1"<?php if($on_call_report) echo " checked";?>> Yes</td>
</tr>
<tr>
<td align="right">Weekly <?=$MET[1]?> Goal</td>
<td><input type="text" name="met1_goal" value="<?=$met1_goal?>" size="8"></td>
</tr>
<tr>
<td align="right">Weekly <?=$MET[2]?> Goal</td>
<td><input type="text" name="met2_goal" value="<?=$met2_goal?>" size="8"></td>
</tr>
<tr>
<td align="right">Weekly <?=$MET[3]?> Goal</td>
<td><input type="text" name="met3_goal" value="<?=$met3_goal?>" size="8"></td>
</tr>
</table>
<?php } ?>



<br>
<strong>Other Options</strong>
<br>
<table class="main">

<tr>
<td align="right">Can Only Log In At Certain Times?</td>
<td><input type="checkbox" name="forcetime" value="1"<?php if($forcetime) echo " checked";?> onChange="check_forcetime(this)"> Yes</td>
</tr>
<tr id="forcetime_area"<?php if($forcetime==0) echo " style=\"display:none;\"";?>>
<td colspan="2">
Between 
<select name="forcetime_start">
<?php for($x=0;$x<=23;$x++){ 
  $x_display = $x;
  if($x==0) $x_display = 12;
  $ampm = "AM";
  if($x >= 12) $ampm = "PM";
  if($x > 12) $x_display = $x - 12;
  ?>
  <option value="<?=$x?>"<?php if($forcetime_start==$x) echo " selected";?>><?=$x_display?> <?=$ampm?></option>
  <?php
}
?>
</select>
 And 
<select name="forcetime_end">
<?php for($x=0;$x<=23;$x++){ 
  $x_display = $x;
  if($x==0) $x_display = 12;
  $ampm = "AM";
  if($x >= 12) $ampm = "PM";
  if($x > 12) $x_display = $x - 12;
  ?>
  <option value="<?=$x?>"<?php if($forcetime_end==$x) echo " selected";?>><?=$x_display?> <?=$ampm?></option>
  <?php
}
?>
</select>
</td>
</tr>




<?php if($USING_SD){ ?>
<tr>
<td align="right">On Serviceman List?</td>
<td><input type="checkbox" name="servicemen" value="1"<?php if($servicemen) echo " checked";?>> Yes</td>
</tr>
<?php } ?>
<tr>
<td align="right">Requires Inspection Pre-Approval?</td>
<td><input type="checkbox" name="require_pre_approval" value="1"<?php if($require_pre_approval) echo " checked";?>> Yes</td>
</tr>

<tr>
<td align="right">Requires Inspection Final Approval?</td>
<td><input type="checkbox" name="require_final_approval" value="1"<?php if($require_final_approval) echo " checked";?>> Yes</td>
</tr>

<?php if($USING_SD){ ?>
<tr>
<td align="right">Requires Service Dispatch Approval?</td>
<td><input type="checkbox" name="require_sd_approval" value="1"<?php if($require_sd_approval) echo " checked";?>> Yes</td>
</tr>
<?php } ?>
</table>

<?php if($SESSION_USE_GROUPS){?>
<?php
if($SESSION_GROUPS=="" && $SESSION_SUBGROUPS==""){ // only core users can do group assignments

$help_groups = "If this user has a group or subgroup checked, this user will only be able to access properties with that group or subgroup.<br>";
$help_groups .= "If no groups are checked, this user is a Core user with access to all properties, regardless of group or subgroup.";
?>
<br>
<strong>Groups</strong><a alt="" onMouseOver="return overlib('<?=$help_groups?>')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a><br>
<input type="checkbox" name="group_core_box" onChange="group_core(this)"><em><strong>Core User (unchecks all groups/subgroups)</strong></em><br>
<?php
$sql = "SELECT id, group_name from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of=0 order by group_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $group_id = $record['id'];
  ?>
  <input type="checkbox" name="group_list[]" value="<?=$group_id?>"<?php if(go_reg("," . $group_id . ",", $groups)) echo " checked";?>> 
  <span id="group_<?=$group_id?>_arrow" style="display:none;"><a href="javascript:OpenClose('<?=$group_id?>', '1')" style="text-decoration:none; font-weight:bold; font-size:14px;">+</a></span>
  <?=stripslashes($record['group_name'])?>
  <br>
  <div id="group_<?=$group_id?>" style="padding-left:20px; display:none;">
  <?php
  $sql = "SELECT id, group_name from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of='$group_id' order by group_name";
  $result_sub = executequery($sql);
  if(mysql_num_rows($result_sub)) $SHOWARROW[] = $group_id;
  while($record_sub = go_fetch_array($result_sub)){
    $subgroup_id = $record_sub['id'];
	?>
	<input type="checkbox" name="subgroup_list[]" value="<?=$subgroup_id?>"<?php if(go_reg("," . $subgroup_id . ",", $subgroups)) echo " checked";?>> <?=stripslashes($record_sub['group_name'])?><br>
	<?php
	if(go_reg("," . $subgroup_id . ",", $subgroups)) $OPEN[] = $group_id;
  }
  ?>
  </div>
  <?php
}
}
?>
<script>
<?php
$OPEN2 = array_unique($OPEN);
$OPEN2 = array_values($OPEN2);
for($x=0;$x<sizeof($SHOWARROW);$x++){
  if($SHOWARROW[$x]=="") continue;
  ?>
  document.getElementById('group_<?=$SHOWARROW[$x]?>_arrow').style.display="";
  <?php
}

for($x=0;$x<sizeof($OPEN2);$x++){
  if($OPEN2[$x]=="") continue;
  ?>
  OpenClose('<?=$OPEN2[$x]?>', '1');
  <?php
}
?>
</script>
<?php } // end if using groups ?>
</div>

</div>
</div>


</form>


</div>
</body>



