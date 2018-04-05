<?php include "includes/header.php"; ?>
<?php
$leak_id = $_GET['leak_id'];

$sql = "SELECT section_type from am_leakcheck where leak_id=\"$leak_id\"";
$section_type = getsingleresult($sql);

switch($section_type){
  case "paving":{
    $imagequery = " b.image_paving as ximage ";
	break;
  }
  case "mech":{
    $imagequery = " b.image_mech as ximage ";
	break;
  }
  case "ww":{
    $imagequery = " b.image_ww as ximage ";
	break;
  }
  default:{
    $imagequery = " b.image as ximage ";
	break;
  }
}

$sql = "SELECT b.site_name, b.am_user_id as site_user_id, a.admin_resolve, a.admin_invoice, a.section_id, 
b.am_user_id_corporate as corporate_user_id, a.alert, a.alert_desc, a.servicemen_id, a.servicemen_id2, 
concat(e.firstname, ' ', e.lastname) as dispatchedby, a.fix_contractor, 
a.notes, date_format(a.dispatch_date, \"%m/%d/%Y\") as datepretty, a.invoice, 
date_format(dispatch_date, \"%r\") as timepretty, 
date_format(eta_date, \"%m/%d/%Y %r\") as eta_pretty, 
a.property_id, a.problem_desc, a.correction, a.status, a.section_type, 
a.additional, a.add_desc, a.add_priority, a.add_cost, a.add_proposal, a.demo, b.city, b.state, a.code, b.address, b.zip, 
a.invoice_type, a.nte_amount, a.contract_amount, a.bill_to, a.bt_manufacturer, a.bt_installer, a.bt_term,
a.bt_start, a.bt_contact, a.bt_phone, a.promotional_type, a.pt_credit, a.pt_project_name, 
a.nte, a.labor_rate, a.materials, a.extra_cost, a.sub_total, a.discount_amount, a.promotional_amount, a.invoice_total, a.rtm, 
b.timezone, 
$imagequery 
from am_leakcheck a, properties b, am_users e 
where a.property_id=b.property_id and a.user_id = e.user_id and 
a.leak_id=\"$leak_id\"";
//echo "<!-- $sql -->\n";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);
$property_id = stripslashes($record['property_id']);
$section_id = stripslashes($record['section_id']);

$sf = $record['ximage'];

$site_user_id = stripslashes($record['site_user_id']);
$corporate_user_id = stripslashes($record['corporate_user_id']);
$section_name = stripslashes($record['section_name']);
$dispatchedby = stripslashes($record['dispatchedby']);
$notes = stripslashes(nl2br($record['notes']));
$problem_desc = stripslashes(nl2br($record['problem_desc']));
$correction = stripslashes(nl2br($record['correction']));
$datepretty = stripslashes($record['datepretty']);
$timepretty = stripslashes($record['timepretty']);
$status = stripslashes($record['status']);
$section_type = stripslashes($record['section_type']);
$invoice = stripslashes($record['invoice']);
$additional = stripslashes($record['additional']);
$add_desc = stripslashes($record['add_desc']);
$add_priority = stripslashes($record['add_priority']);
$add_cost = stripslashes($record['add_cost']);
$add_proposal = stripslashes($record['add_proposal']);
$admin_resolve = stripslashes($record['admin_resolve']);
$admin_invoice = stripslashes($record['admin_invoice']);
$alert = stripslashes($record['alert']);
$alert_desc = stripslashes($record['alert_desc']);
$demo = stripslashes($record['demo']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$address = stripslashes($record['address']);
$zip = stripslashes($record['zip']);
$code = stripslashes($record['code']);
$fix_contractor = stripslashes($record['fix_contractor']);
$servicemen_id = stripslashes($record['servicemen_id']);
$servicemen_id2 = stripslashes($record['servicemen_id2']);
$eta_pretty = stripslashes($record['eta_pretty']);

$invoice_type = stripslashes($record['invoice_type']);
$total_hours = stripslashes($record['total_hours']);
$nte_amount = stripslashes($record['nte_amount']);
$contract_amount = stripslashes($record['contract_amount']);
$bill_to = stripslashes($record['bill_to']);
$bt_manufacturer = stripslashes($record['bt_manufacturer']);
$bt_installer = stripslashes($record['bt_installer']);
$bt_term = stripslashes($record['bt_term']);
$bt_start = stripslashes($record['bt_start']);
$bt_contact = stripslashes($record['bt_contact']);
$bt_phone = stripslashes($record['bt_phone']);
$promotional_type = stripslashes($record['promotional_type']);
$pt_credit = stripslashes($record['pt_credit']);
$pt_project_name = stripslashes($record['pt_project_name']);

$nte = stripslashes($record['nte']);
$labor_rate = stripslashes($record['labor_rate']);
$materials = stripslashes($record['materials']);

$extra_cost = stripslashes($record['extra_cost']);
$sub_total = stripslashes($record['sub_total']);
$discount_amount = stripslashes($record['discount_amount']);
$promotional_amount = stripslashes($record['promotional_amount']);
$invoice_total = stripslashes($record['invoice_total']);
$rtm = stripslashes($record['rtm']);

$timezone = stripslashes($record['timezone']);
switch($timezone){
    case 1:{
	  $tz_display = "EST";
	  break;
	}
	case 0:{
	  $tz_display = "CST";
	  break;
	}
	case -1:{
	  $tz_display = "MST";
	  break;
	}
	case -2:{
	  $tz_display = "PST";
	  break;
	}
	default:{
	  $tz_display = "CST";
	  break;
	}
}

if($status == "Invoiced" && $admin_invoice == 0) $status = "Confirmed";

if($section_id != 0){
  $sql = "SELECT section_name from sections where section_id='$section_id'";
  $section_name = stripslashes(getsingleresult($sql));
}
else {
  $section_name = "Unknown";
}

$sql = "SELECT prospect_id from properties where property_id='$property_id'";
$prospect_id = getsingleresult($sql);

$sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
$prospect_company_name = stripslashes(getsingleresult($sql));

$sql = "SELECT concat(firstname, ' ', lastname) as fullname, phone from am_users where user_id='$site_user_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_user_name = stripslashes($record['fullname']);
$site_user_phone = stripslashes($record['phone']);
$sql = "SELECT concat(firstname, ' ', lastname) as fullname, phone from am_users where user_id='$corporate_user_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$corporate_user_name = stripslashes($record['fullname']);
$corporate_user_phone = stripslashes($record['phone']);

$color = "red";
if($status=="Arrival ETA") $color = "orange";
if($status=="In Progress") $color = "yellow";

if($status=="Dispatched"){
  $dispatched_div = "";
  $punch_div = " style='display:none;'";
}
else {
  $dispatched_div = " style='display:none;'";
  $punch_div = "";
}
?>
<?php include "includes/main_nav.php"; ?>
<br>
<?=$site_name?> - #<?=$leak_id?><br>
<font color="<?=$color?>">Service Dispatch - <?=$status?></font>
<br><br>
<?=$address?><br>
<?=$city?>, <?=$state?> <?=$zip?>
<br><br>
Section: <?=$section_name?>
<br>
Corporate Contact:<br>
<?=$corporate_user_name?>
<?php if($corporate_user_phone != ""){ ?>
<br><?=$corporate_user_phone?>
<?php } ?>
<br><br>
Site Contact:<br>
<?=$site_user_name?>
<?php if($site_user_phone != ""){ ?>
<br><?=$site_user_phone?>
<?php } ?>
<br><br>
Dispatched:<br>
<?=$datepretty?> - <?=$timepretty?>
<br><br>
<?php if($status=="Arrival ETA"){ ?>
Arrival ETA:<br>
<?=$eta_pretty?><br><br>
<?php } ?>
<?php
$hour_now = date("g", time() + (3600 * $timezone));
$ampm_now = date("A", time() + (3600 * $timezone));
//$hour_now = date("g");
//$ampm_now = date("A");

$serviceman = 1;
if($SESSION_USER_ID==$servicemen_id2) $serviceman = 2;
//echo $hour_now . " " . $ampm_now;
?>
<form action="service_dispatch_details_action.php" method="post">
<input type="hidden" name="leak_id" value="<?=$leak_id?>">
<input type="hidden" name="serviceman" value="<?=$serviceman?>">
<div<?=$dispatched_div?>>
ETA:
<select name="eta_month">
<?php for($x=1;$x<=12;$x++){ ?>
  <option value="<?=$x?>"<?php if($x==date("n")) echo " selected";?>><?=$monthname[$x]?></option>
<?php } ?>
</select>
/
<select name="eta_day">
<?php for($x=1;$x<=31;$x++){ ?>
  <option value="<?=$x?>"<?php if($x==date("j")) echo " selected";?>><?=$x?></option>
<?php } ?>
</select>
/
<select name="eta_year">
<?php for($x=date("Y");$x<=(date("Y") + 1);$x++){ ?>
  <option value="<?=$x?>"<?php if($x==date("Y")) echo " selected";?>><?=$x?></option>
<?php } ?>
</select>
<br>
<select name="eta_hour">
<?php
for($x=1;$x<=12;$x++){
  ?>
  <option value="<?=$x?>"<?php if($x== date("g")) echo " selected";?>><?=$x?></option>
  <?php
}
?>
</select> : 
<select name="eta_minute">
<option value="0">00</option>
<option value="15"<?php if(date("i") >=15 && date("i") <= 29) echo " selected";?>>15</option>
<option value="30"<?php if(date("i") >=30 && date("i") <= 44) echo " selected";?>>30</option>
<option value="45"<?php if(date("i") >=45 && date("i") <= 59) echo " selected";?>>45</option>
</select>
<select name="eta_ampm">
<option value="AM"<?php if(date("A")=="AM") echo " selected";?>>AM</option>
<option value="PM"<?php if(date("A")=="PM") echo " selected";?>>PM</option>
</select>
CST
<br>
<input type="submit" name="submit1" value="Enter ETA">
</div>

<div<?=$punch_div?>>
Invoice Type: 
<select name="invoice_type">
  <option value=""></option>
  <option value="Billable - TM"<?php if($invoice_type=="Billable - TM") echo " selected";?>>Billable - T&amp;M</option>
  <option value="Billable - Contract(minor)"<?php if($invoice_type=="Billable - Contract(minor)") echo " selected";?>>Billable - Contract(minor)</option>
  <option value="Billable - Contract(major)"<?php if($invoice_type=="Billable - Contract(major)") echo " selected";?>>Billable - Contract(major)</option>
  <option value="Billable - Warranty"<?php if($invoice_type=="Billable - Warranty") echo " selected";?>>Billable - Warranty</option>
  <option value="2 Year"<?php if($invoice_type=="2 Year") echo " selected";?>>2 Year</option>
  <option value="Warranty"<?php if($invoice_type=="Warranty") echo " selected";?>>Warranty</option>
  <option value="Promotional"<?php if($invoice_type=="Promotional") echo " selected";?>>Promotional</option>
  <option value="Project Mgmt"<?php if($invoice_type=="Project Mgmt") echo " selected";?>>Project Mgmt</option>
  <option value="RTM"<?php if($invoice_type=="RTM") echo " selected";?>>RTM</option>
  </select>
<br>
<?php
$sql = "SELECT id from am_leakcheck_time where leak_id='$leak_id' and complete=0 and servicemen_1_or_2='$serviceman' order by id desc limit 1";
$time_id = getsingleresult($sql);
if($time_id=="") $time_id=0;
if($time_id==0){
  $punch_label = "Punch In";
}
else {
  $punch_label = "Punch Out";
}
?>
<?=$punch_label?><br>
<select name="punch_month">
<?php for($x=1;$x<=12;$x++){ ?>
  <option value="<?=$x?>"<?php if($x==date("n")) echo " selected";?>><?=$monthname[$x]?></option>
<?php } ?>
</select>
/
<select name="punch_day">
<?php for($x=1;$x<=31;$x++){ ?>
  <option value="<?=$x?>"<?php if($x==date("j")) echo " selected";?>><?=$x?></option>
<?php } ?>
</select>
/
<select name="punch_year">
<?php for($x=date("Y");$x<=(date("Y") + 1);$x++){ ?>
  <option value="<?=$x?>"<?php if($x==date("Y")) echo " selected";?>><?=$x?></option>
<?php } ?>
</select>
<br>
<select name="punch_hour">
<?php
for($x=1;$x<=12;$x++){
  ?>
  <option value="<?=$x?>"<?php if($x== $hour_now) echo " selected";?>><?=$x?></option>
  <?php
}
?>
</select> : 
<select name="punch_minute">
<option value="0">00</option>
<option value="15"<?php if(date("i") >=15 && date("i") <= 29) echo " selected";?>>15</option>
<option value="30"<?php if(date("i") >=30 && date("i") <= 44) echo " selected";?>>30</option>
<option value="45"<?php if(date("i") >=45 && date("i") <= 59) echo " selected";?>>45</option>
</select>
<select name="punch_ampm">
<option value="AM"<?php if($ampm_now=="AM") echo " selected";?>>AM</option>
<option value="PM"<?php if($ampm_now=="PM") echo " selected";?>>PM</option>
</select>
<select name="timezone">
  <option value="1"<?php if($timezone==1) echo " selected";?>>EST</option>
  <option value="0"<?php if($timezone==0) echo " selected";?>>CST</option>
  <option value="-1"<?php if($timezone==-1) echo " selected";?>>MST</option>
  <option value="-2"<?php if($timezone==-2) echo " selected";?>>PST</option>
  </select>
<input type="hidden" name="time_id" value="<?=$time_id?>">
<br>
<input type="submit" name="submit1" value="<?=$punch_label?>">
<br>
<?php
  $html = "<table class='main' width='100%'>";
$sql = "SELECT date_format(time_in, \"%m/%d/%Y\") as datepretty, date_format(time_in, \"%l:%i %p\") as inpretty, id, timezone
from am_leakcheck_time where leak_id='$leak_id' and complete=0 and servicemen_1_or_2='$serviceman' order by time_in desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x_timezone = $record['timezone'];
  switch($x_timezone){
    case 1:{
	  $xtz_display = "EST";
	  break;
	}
	case 0:{
	  $xtz_display = "CST";
	  break;
	}
	case -1:{
	  $xtz_display = "MST";
	  break;
	}
	case -2:{
	  $xtz_display = "PST";
	  break;
	}
	default:{
	  $xtz_display = "CST";
	  break;
	}
  }
  $html .= "<tr>";
  $html .= "<td>" . $record['datepretty'] . " - </td>";
  $html .= "<td>In: " . $record['inpretty'] . " $xtz_display</td>";
  $html .= "<td>&nbsp;";
  $html .= "</td>";

  $html .= "</tr>";
}
$html .= "</table>";
echo $html;

$html = "<table class='main' width='100%'>";
$sql = "SELECT date_format(time_in, \"%m/%d/%Y\") as datepretty, date_format(time_in, \"%l:%i %p\") as inpretty, 
date_format(time_out, \"%l:%i %p\") as outpretty, total_hours, id, timezone
from am_leakcheck_time where leak_id='$leak_id' and complete=1 and servicemen_1_or_2='$serviceman' order by time_out desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x_timezone = $record['timezone'];
  switch($x_timezone){
    case 1:{
	  $xtz_display = "EST";
	  break;
	}
	case 0:{
	  $xtz_display = "CST";
	  break;
	}
	case -1:{
	  $xtz_display = "MST";
	  break;
	}
	case -2:{
	  $xtz_display = "PST";
	  break;
	}
	default:{
	  $xtz_display = "CST";
	  break;
	}
  }
  $html .= "<tr>";
  $html .= "<td>";
  $html .= $record['datepretty'] . " - </td>";
  $html .= "<td>In: " . $record['inpretty'] . " $xtz_display</td>";
  $html .= "<td>Out: " . $record['outpretty'] . " $xtz_display</td>";
  $html .= "</tr>";
}
$html .= "</table>";

echo $html;
?>
<?php
$sql = "SELECT count(*) from am_leakcheck_time where leak_id='$leak_id'";
$punches = getsingleresult($sql);
?>
<?php if($time_id==0 && $punches > 0){ ?>
<br>
<input type="submit" name="submit1" value="Mark Project Complete">
<?php } ?>
</div>
</form>
