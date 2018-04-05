<?php
include "includes/header_white.php";

$sql = "SELECT count(*) from toolbox_items where tool_master_id=3 and master_id='" . $SESSION_MASTER_ID . "'";
$USING_SD = getsingleresult($sql);

$sql = "SELECT count(*) from toolbox_items where tool_master_id=4 and master_id='" . $SESSION_MASTER_ID . "'";
$USING_CAPS = getsingleresult($sql);

$sql = "SELECT multilogo from master_list where master_id='$master_id'";
$USING_ML = getsingleresult($sql);

$sql = "SELECT use_ops from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$USING_OPS = getsingleresult($sql);

$sql = "SELECT use_cron_sd_export from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$USING_CRON_SD_EXPORT = getsingleresult($sql);

$sql = "SELECT count(*) from toolbox_items where tool_master_id=19 and master_id='" . $SESSION_MASTER_ID . "'";
$USING_ACTIONPLAN = getsingleresult($sql);

$sql = "SELECT dispatch_from_email, address, city, state, zip, invoice_contact, invoice_contact_number, logo, fax, phone, logo_report, timezone, 
emergency_time_frame, urgent_time_frame, payment_terms, priority1, priority2, priority3, disable_caps, first_sd_alert, second_sd_alert, third_sd_alert, 
invoice_user, scheduled_time_frame, idle_time, logo2, logo_map, priority1_rate, priority2_rate, priority3_rate, custom_sd_field, license_number, map_service_xml, website, 
custom_sd_field2, xml_sd_export, productionmeeting_user, checks_payable_to, full_appointment, from_email, ar_account, sales_account, 
master_name, objective_met_label, cron_sd, cron_sd_filename, cron_sd_type, cron_sd_email, 
company_code, acct_rec_code, general_ledger_acct, sales_tax_acct
from master_list 
where master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$dispatch_from_email = stripslashes($record['dispatch_from_email']);
$address = stripslashes($record['address']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$zip = stripslashes($record['zip']);
$invoice_contact = stripslashes($record['invoice_contact']);
$invoice_contact_number = stripslashes($record['invoice_contact_number']);
$logo = stripslashes($record['logo']);
$logo2 = stripslashes($record['logo2']);
$fax = stripslashes($record['fax']);
$phone = stripslashes($record['phone']);
$logo_report = stripslashes($record['logo_report']);
$timezone = stripslashes($record['timezone']);
$emergency_time_frame = stripslashes($record['emergency_time_frame']);
$urgent_time_frame = stripslashes($record['urgent_time_frame']);
$payment_terms = stripslashes($record['payment_terms']);
$priority1 = stripslashes($record['priority1']);
$priority2 = stripslashes($record['priority2']);
$priority3 = stripslashes($record['priority3']);
$disable_caps = stripslashes($record['disable_caps']);
$first_sd_alert = stripslashes($record['first_sd_alert']);
$second_sd_alert = stripslashes($record['second_sd_alert']);
$third_sd_alert = stripslashes($record['third_sd_alert']);
$invoice_user = stripslashes($record['invoice_user']);
$scheduled_time_frame = stripslashes($record['scheduled_time_frame']);
$idle_time = stripslashes($record['idle_time']);
$logo_map = stripslashes($record['logo_map']);
$priority1_rate = stripslashes($record['priority1_rate']);
$priority2_rate = stripslashes($record['priority2_rate']);
$priority3_rate = stripslashes($record['priority3_rate']);
$custom_sd_field = stripslashes($record['custom_sd_field']);
$custom_sd_field2 = stripslashes($record['custom_sd_field2']);
$license_number = stripslashes($record['license_number']);
$map_service_xml = stripslashes($record['map_service_xml']);
$website = stripslashes($record['website']);
$xml_sd_export = stripslashes($record['xml_sd_export']);
$productionmeeting_user = stripslashes($record['productionmeeting_user']);
$checks_payable_to = stripslashes($record['checks_payable_to']);
$full_appointment = stripslashes($record['full_appointment']);
$from_email = stripslashes($record['from_email']);
$ar_account = stripslashes($record['ar_account']);
$sales_account = stripslashes($record['sales_account']);
$master_name = stripslashes($record['master_name']);
$objective_met_label = stripslashes($record['objective_met_label']);
$cron_sd = stripslashes($record['cron_sd']);
$cron_sd_filename = stripslashes($record['cron_sd_filename']);
$cron_sd_type = stripslashes($record['cron_sd_type']);
$cron_sd_email = stripslashes($record['cron_sd_email']);

$company_code = stripslashes($record['company_code']);
$acct_rec_code = stripslashes($record['acct_rec_code']);
$general_ledger_acct = stripslashes($record['general_ledger_acct']);
$sales_tax_acct = stripslashes($record['sales_tax_acct']);

switch($xml_sd_export){
    case "none":{ // standard
	  break;
	}
	case "ComputerEase":{
	  $cron_sd_type = "xml";
	  break;
	}
	case "ComputerEase2":{
	  $cron_sd_type = "xml";
	  break;
	}
	case "Excel 2":{
	  $cron_sd_type = "csv";
	  break;
	}
	case "Timberline":{
	  $cron_sd_type = "txt";
	  break;
	}
}
?>
<script type="text/javascript" src="includes/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>

<script>
function force_custom(x){
  if(x.value=="ComputerEase"){
    document.getElementById("custom_sd_field").value = "PO #";
	document.getElementById("custom_sd_field2").value = "Job #";
  }
  
  document.getElementById('ar_account_area').style.display="none";
  document.getElementById('sales_account_area').style.display="none";
  document.getElementById('timberline_area').style.display="none";
  
  switch(x.value){
    case "ComputerEase2":{
      document.getElementById('ar_account_area').style.display="";
	  document.getElementById('sales_account_area').style.display="";
	  break;
    }
	case "Timberline":{
	  document.getElementById('timberline_area').style.display="";
	  break;
	}
  }

}

function cron_sd_check(x){
  if(x.checked==true){
    document.getElementById('cron_sd_type_area').style.display="";
	document.getElementById('cron_sd_file_area').style.display="";
	document.getElementById('cron_sd_email_area').style.display="";
  }
  else {
    document.getElementById('cron_sd_type_area').style.display="none";
	document.getElementById('cron_sd_file_area').style.display="none";
	document.getElementById('cron_sd_email_area').style.display="none";
  }
}
</script>
	
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<form action="admin_info_action.php" method="post" enctype="multipart/form-data" name="main">
<div style="width:100%; position:relative;" class="main">
<div style="width:50%; float:left;">
<table class="main">
<tr>
<td>Logo</td>
<td><input type="file" name="logo"></td>
</tr>
<?php if($logo != ""){ ?>
<tr>
<td colspan="2">
<img src="uploaded_files/master_logos/<?=$logo?>">
</td>
</tr>
<?php } ?>
<?php if($USING_ML){ ?>
<tr>
<td colspan="2"><hr size="1"></td>
</tr>
<tr>
<td>Alternate Logo</td>
<td><input type="file" name="logo2"></td>
</tr>
<?php if($logo2 != ""){ ?>
<tr>
<td colspan="2">
<img src="uploaded_files/master_logos/<?=$logo2?>">
</td>
</tr>
<?php } ?>
<?php } ?>
<tr>
<td colspan="2"><hr size="1"></td>
</tr>
<tr>
<td align="right"><strong>Report Logo</strong></td>
<td><input type="file" name="logo_report"></td>
</tr>
<tr>
<td colspan="2">This logo appears on bottom left of first page of report</td>
</tr>
<?php if($logo_report != ""){ ?>
<tr>
<td colspan="2">
<img src="<?=$CORE_URL?>uploaded_files/master_logos/<?=$logo_report?>">
</td>
</tr>
<?php } ?>

<?php if($USING_SD){ ?>
<tr>
<td colspan="2"><hr size="1"></td>
</tr>
<tr>
<td align="right"><strong>Map Logo</strong></td>
<td><input type="file" name="logo_map"></td>
</tr>
<tr>
<td colspan="2">This logo appears as the icon on the Service map</td>
</tr>
<?php if($logo_map != ""){ ?>
<tr>
<td colspan="2">
<img src="<?=$CORE_URL?>/uploaded_files/master_logos/<?=$logo_map?>">
</td>
</tr>
<?php } ?>
<?php } ?>

<tr>
<td colspan="2"><hr size="1"></td>
</tr>

<tr>
<td>Company Name:</td>
<td><input type="text" name="master_name" value="<?=$master_name?>"></td>
</tr>

<tr>
<td>Address:</td>
<td><input type="text" name="address" value="<?=$address?>"></td>
</tr>

<tr>
<td>City:</td>
<td><input type="text" name="city" value="<?=$city?>"></td>
</tr>

<tr>
<td>State:</td>
<td>
<select name="state">
<?php
$sql = "SELECT state_name, state_code from states order by state_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['state_code']?>"<?php if($state==$record['state_code']) echo " selected";?>><?=stripslashes($record['state_name'])?></option>
  <?php
}
?>
</select>
</td>
</tr>

<tr>
<td>Zip:</td>
<td><input type="text" name="zip" value="<?=$zip?>"></td>
</tr>

<tr>
<td>Main Phone:</td>
<td><input type="text" name="phone" value="<?=$phone?>"></td>
</tr>
<tr>
<td>Main Fax:</td>
<td><input type="text" name="fax" value="<?=$fax?>"></td>
</tr>
<tr>
<td>Website:</td>
<td><input type="text" name="website" value="<?=$website?>" maxlength="250"></td>
</tr>


<tr>
<td>Timezone</td>
<td>
<select name="timezone">
<option value="1"<?php if($timezone==1) echo " selected";?>>Eastern</option>
<option value="0"<?php if($timezone==0) echo " selected";?>>Central</option>
<option value="-1"<?php if($timezone==-1) echo " selected";?>>Mountain</option>
<option value="-2"<?php if($timezone==-2) echo " selected";?>>Pacific</option>
</select>
</td>
</tr>

<tr>
<td valign="top">Payment Terms</td>
<td>
<textarea name="payment_terms" rows="4" cols="50"><?=$payment_terms?></textarea>
</td>
</tr>
<?php if($USING_SD){?>
<tr>
<td valign="top">Make Checks Payable To</td>
<td>
<textarea name="checks_payable_to" rows="4" cols="50"><?=$checks_payable_to?></textarea>
</td>
</tr>
<?php } ?>
<tr>
<td>License Number:</td>
<td><input type="text" name="license_number" value="<?=$license_number?>"></td>
</tr>
<tr>
<td>Idle Timeout</td>
<td><input type="text" name="idle_time" value="<?=$idle_time?>" size="3"> minutes</td>
</tr>
<tr>
<td valign="top">GPS Tracking URL</td>
<td>
<textarea name="map_service_xml" rows="2" cols="50"><?=$map_service_xml?></textarea>
</td>
</tr>
<?php if($USING_CAPS){ ?>
<tr>
<td>Disable Captialization Tool</td>
<td><input type="checkbox" name="disable_caps" value="1"<?php if($disable_caps) echo " checked";?>>Disable</td>
</tr>
<?php } ?>
</table>

</div>
<div style="width:50%; float:left;">
<table class="main">
<?php if($USING_SD){ ?>
<tr>
<td>Service Dispatch and Proposals From:</td>
<td><input type="text" name="dispatch_from_email" value="<?=$dispatch_from_email?>"></td>
</tr>

<tr>
<td>Priority 1 Name:</td>
<td><input type="text" name="priority1" value="<?=$priority1?>"> $<input type="text" name="priority1_rate" value="<?=$priority1_rate?>" size="4">/hr</td>
</tr>
<tr>
<td>Priority 2 Name:</td>
<td><input type="text" name="priority2" value="<?=$priority2?>"> $<input type="text" name="priority2_rate" value="<?=$priority2_rate?>" size="4">/hr</td>
</tr>
<tr>
<td>Priority 3 Name:</td>
<td><input type="text" name="priority3" value="<?=$priority3?>"> $<input type="text" name="priority3_rate" value="<?=$priority3_rate?>" size="4">/hr</td>
</tr>

<tr>
<td>Priority 3 Time Frame:</td>
<td><input type="text" name="emergency_time_frame" value="<?=$emergency_time_frame?>"></td>
</tr>

<tr>
<td>Priority 2 Time Frame:</td>
<td><input type="text" name="urgent_time_frame" value="<?=$urgent_time_frame?>"></td>
</tr>

<tr>
<td>Priority 1 Time Frame:</td>
<td><input type="text" name="scheduled_time_frame" value="<?=$scheduled_time_frame?>"></td>
</tr>
<?php
// only want this to show if they have 'use resources' selected (which all should be disabled in this database)
$sql = "SELECT use_resources FROM master_list WHERE master_id='" . $SESSION_MASTER_ID . "'";
$count = getsingleresult($sql);
if($count > 0){?>

<tr>
<td colspan="2">When sending a request for a resource to work on a service dispatch, and the resource doesn't reply:</td>
</tr>
<tr>
<td>Resend After</td>
<td><input type="text" name="first_sd_alert" value="<?=$first_sd_alert?>" size="3"> minutes</td>
</tr>
<tr>
<td>Send Final Notice</td>
<td><input type="text" name="second_sd_alert" value="<?=$second_sd_alert?>" size="3"> minutes after previous notice</td>
</tr>
<tr>
<td>Send Cancellation Notice</td>
<td><input type="text" name="third_sd_alert" value="<?=$third_sd_alert?>" size="3"> minutes after previous notice</td>
</tr>
<?php } else { ?>
<input type="hidden" name="first_sd_alert" value="<?=$first_sd_alert?>">
<input type="hidden" name="second_sd_alert" value="<?=$second_sd_alert?>">
<input type="hidden" name="third_sd_alert" value="<?=$third_sd_alert?>">
<?php } ?>


<tr>
<td>Invoice Contact:</td>
<td>
<select name="invoice_user">
<option value="0"></option>
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where master_id='" . $SESSION_MASTER_ID . "' and enabled=1 and $RESOURCE_FILTER order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($invoice_user==$record['user_id']) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
</td>
</tr>
<?php /*
<tr>
<td>Invoice Contact Name:</td>
<td><input type="text" name="invoice_contact" value="<?=$invoice_contact?>"></td>
</tr>

<tr>
<td>Invoice Contact Phone:</td>
<td><input type="text" name="invoice_contact_number" value="<?=$invoice_contact_number?>"></td>
</tr>
*/?>
<tr>
<td>Custom Invoice Field</td>
<td><input type="text" name="custom_sd_field" value="<?=$custom_sd_field?>" id="custom_sd_field" maxlength="25" size="20"></td>
</tr>
<tr>
<td>Custom Invoice Field 2</td>
<td><input type="text" name="custom_sd_field2" value="<?=$custom_sd_field2?>" id="custom_sd_field2" maxlength="25" size="20"></td>
</tr>
<tr>
<td>Dispatch Report Export 
<a alt="" onMouseOver="return overlib('For manually exporting service dispatches')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a>
</td>
<td>
<select name="xml_sd_export" onchange="force_custom(this)">
<option value="none"<?php if($xml_sd_export=="none") echo " selected";?>>Standard</option>
<option value="ComputerEase"<?php if($xml_sd_export=="ComputerEase") echo " selected";?>>ComputerEase</option>
<option value="ComputerEase2"<?php if($xml_sd_export=="ComputerEase2") echo " selected";?>>ComputerEase 2</option>
<option value="Excel 2"<?php if($xml_sd_export=="Excel 2") echo " selected";?>>Excel 2</option>
<option value="Timberline"<?php if($xml_sd_export=="Timberline") echo " selected";?>>Timberline</option>
</select>
</td>
</tr>
<tr id="ar_account_area" style="display:none;">
<td>AR Account</td>
<td><input type="text" name="ar_account" value="<?=$ar_account?>"></td>
</tr>
<tr id="sales_account_area" style="display:none;">
<td>Sales Account</td>
<td><input type="text" name="sales_account" value="<?=$sales_account?>"></td>
</tr>
<tr id="timberline_area" style="display:none;">
<td colspan="2">
  <table class="main">
  <tr>
  <td>Company Code</td>
  <td><input type="text" name="company_code" value="<?=$company_code?>"></td>
  </tr>
  <tr>
  <td>Account Receivable Code</td>
  <td><input type="text" name="acct_rec_code" value="<?=$acct_rec_code?>"></td>
  </tr>
  <tr>
  <td colspan="2">Invoice Types</td>
  </tr>
  <tr>
  <td colspan="2"><div>Custom Invoice Types</div>
  <div>
    <p style="padding-left:10px;"><a href="invoice_type.php" />Edit Invoice Types</a></p>
    <?php  // show custom invoice_types (based on T&M type)
    $sql = "SELECT * FROM invoice_types WHERE master_id='" . $SESSION_MASTER_ID . "' AND display=1";
    $result = mysql_query($sql);
    if(!$result) { echo "<p style='color:red;padding-left:10px;'>Could not successfully run query ($sql)" . mysql_error() ."</p>"; }
    while($row = mysql_fetch_assoc($result)) {
      echo "<p style='margins:0px 0px;padding:0 0 0 30px;'>". $row['invoice_type'] ." [". $row['general_ledger'] ."]</p>";
    }
    ?>
    </div>
  </div>
  </td>
  </tr>
  <tr>
  <td>Sales Tax Payable Account</td>
  <td><input type="text" name="sales_tax_acct" value="<?=$sales_tax_acct?>"></td>
  </tr>
  </table>
</td>
</tr>
  

<?php if($USING_CRON_SD_EXPORT){ ?>
<tr>
<td>Auto Generate Report
<a alt="" onMouseOver="return overlib('Every night at midnight, an export of your Service Dispatches can be generated in the above selected format.')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>

</td>
<td><input type="checkbox" name="cron_sd" id="cron_sd" value="1" onchange="cron_sd_check(this)"<?php if($cron_sd) echo " checked";?>>Yes</td>
</tr>
<tr id="cron_sd_type_area">
<td style="padding-left:10px;">Auto Generate Type
<a alt="" onMouseOver="return overlib('Only affects Standard Format. ComputerEase and ComputerEase2 are always XML, Excel 2 is always CSV, and Timberline is always TXT')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a>
</td>
<td>
<select name="cron_sd_type">
<option value="csv"<?php if($cron_sd_type=="csv") echo " selected";?>>CSV</option>
<option value="xml"<?php if($cron_sd_type=="xml") echo " selected";?>>XML</option>
</select>
</td>
</tr>
<tr id="cron_sd_file_area">
<td style="padding-left:10px;">Download Auto File:</td>
<td>
<?php if($cron_sd_filename != ""){ ?>
<a href="uploaded_files/download/<?=$cron_sd_filename?>.<?=$cron_sd_type?>" target="_blank"><?=$CORE_URL?>uploaded_files/download/<?=$cron_sd_filename?>.<?=$cron_sd_type?></a>
<?php } else { ?>
File will first be generated at midnight Central time.
<?php } ?>
</td>
</tr>
<tr id="cron_sd_email_area">
<td style="padding-left:10px;">Email File To:</td>
<td><input type="text" name="cron_sd_email" value="<?=$cron_sd_email?>">
</td>
</tr>
<?php } else { // if not using cron sd export?>
<input type="hidden" name="cron_sd_type" value="<?=$cron_sd_type?>">
<?php } // end if block of cron sd export ?>



<?php } else {  // if not using SD ?>
<input type="hidden" name="dispatch_from_email" value="<?=$dispatch_from_email?>">
<input type="hidden" name="emergency_time_frame" value="<?=$emergency_time_frame?>">
<input type="hidden" name="urgent_time_frame" value="<?=$urgent_time_frame?>">


<?php } ?>

<?php 

if($USING_OPS){ ?>
<tr>
<td>Project Start Emails From:</td>
<td>
<select name="productionmeeting_user">
<option value="0"></option>
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where master_id='" . $SESSION_MASTER_ID . "' and enabled=1 and $RESOURCE_FILTER order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$productionmeeting_user) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
</td>
</tr>
<?php } 

?>
<?php if($SESSION_RESIDENTIAL){ ?>
<tr>
<td valign="top"># Residential Appointments<br>for a full day</td>
<td valign="top"><input type="text" name="full_appointment" value="<?=$full_appointment?>" size="3"></td>
</tr>
<?php } ?>
<?php if($USING_ACTIONPLAN){ ?>
<tr>
<td valign="top">Activity "Objective Met"<br>Report Label</td>
<td valign="top"><input type="text" name="objective_met_label" value="<?=$objective_met_label?>"></td>
</tr>
<?php } else { ?>
<input type="hidden" name="objective_met_label" value="<?=$objective_met_label?>">
<?php } ?>

<tr>
<td>Send All Core Emails From:
<a alt="" onMouseOver="return overlib('If this is filled out, all emails sent through the system will appear to be sent from this address.  Leave this blank if you want emails to be sent from individual logged in users.')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="text" name="from_email" value="<?=$from_email?>"></td>
</tr>
</table>
</div>
</div>
<div style="clear:both;"></div>

<input type="submit" name="submit1" value="Update">
</form>
<script>
<?php if($USING_SD){?>
force_custom(document.main.xml_sd_export);
<?php } ?>
<?php if($USING_CRON_SD_EXPORT){ ?>
cron_sd_check(document.main.cron_sd);
<?php } ?>
</script>

