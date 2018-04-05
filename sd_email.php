<?php
if($master_id=="") $master_id = $SESSION_MASTER_ID;
if($master_id=="") $master_id = $_SESSION[$sess_view_header . '_master_id'];

$sql = "SELECT priority1, priority2, priority3 from master_list where master_id='" . $master_id . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$xpriority[1] = stripslashes($record['priority1']);
$xpriority[2] = stripslashes($record['priority2']);
$xpriority[3] = stripslashes($record['priority3']);

if($xpriority[1]=="") $xpriority[1]="Scheduled";
if($xpriority[2]=="") $xpriority[2]="Urgent";
if($xpriority[3]=="") $xpriority[3]="Emergency";

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
	$sql = "SELECT b.site_name, b.am_user_id as site_user_id, a.section_id, 
    b.am_user_id_corporate as corporate_user_id, a.alert, a.alert_desc, 
    concat(e.firstname, ' ', e.lastname) as dispatchedby, 
    a.notes, date_format(a.dispatch_date, \"%m/%d/%Y\") as datepretty, 
    date_format(dispatch_date, \"%h:%i %p\") as timepretty, a.property_id, a.status, a.priority_id,
    b.city, b.state, b.address, b.zip, f.company_name, b.timezone, a.contact_id as sd_contact, a.invoice_id, 
	b.groups, b.subgroups,
    $imagequery 
    from am_leakcheck a, properties b, am_users e, prospects f
    where a.property_id=b.property_id and a.user_id = e.user_id and 
	a.prospect_id=f.prospect_id and
    a.leak_id=\"$leak_id\"";
	
$result = executequery($sql);
$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);
$company_name = stripslashes($record['company_name']);
$property_id = stripslashes($record['property_id']);
$invoice_id = stripslashes($record['invoice_id']);

$sf = $record['ximage'];

$site_user_id = stripslashes($record['site_user_id']);
$corporate_user_id = stripslashes($record['corporate_user_id']);
$section_id = stripslashes($record['section_id']);
$dispatchedby = stripslashes($record['dispatchedby']);
$notes = stripslashes(nl2br($record['notes']));
$datepretty = stripslashes($record['datepretty']);
$timepretty = stripslashes($record['timepretty']);
$status = stripslashes($record['status']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$address = stripslashes($record['address']);
$zip = stripslashes($record['zip']);
$priority_id = stripslashes($record['priority_id']);
$timezone = stripslashes($record['timezone']);
$sd_contact = stripslashes($record['sd_contact']);
  //$timezone = 0; // timezone is always cst now

$property['groups'] = go_reg_replace(",", "", stripslashes($record['groups']));
$property['subgroups'] = go_reg_replace(",", "", stripslashes($record['subgroups']));
$USEGROUP = 0;
if($property['groups'] != "" && $property['groups'] !=0) $USEGROUP = $property['groups'];
if($property['subgroups'] != "" && $property['subgroups'] != 0) $USEGROUP = $property['subgroups'];

if($USEGROUP !=0 && $USEGROUP != ""){
  $sql = "SELECT priority1, priority2, priority3 from groups where id='" . $USEGROUP . "'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $xpriority[1] = stripslashes($record['priority1']);
  $xpriority[2] = stripslashes($record['priority2']);
  $xpriority[3] = stripslashes($record['priority3']);
}
if($xpriority[1]=="") $xpriority[1]="Scheduled";
if($xpriority[2]=="") $xpriority[2]="Urgent";
if($xpriority[3]=="") $xpriority[3]="Emergency";

if($section_id != 0){
  $sql = "SELECT section_name from sections where section_id='$section_id'";
  $section_name = stripslashes(getsingleresult($sql));
}
else {
  $section_name = "Unknown";
}
 if($timezone=="") $timezone = 0; 
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
  
  //$tz_display = "";
  
  $sql = "SELECT 
  date_format(date_add(a.dispatch_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as dispatch, 
  date_format(date_add(a.fix_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as resolved, 
  date_format(date_add(a.acknowledge_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as acknowledge, 
  date_format(date_add(a.eta_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as eta, 
  date_format(date_add(a.confirm_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as confirm, 
  date_format(date_add(a.invoice_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as invoice, 
  date_format(date_add(a.inprogress_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as inprogress, 
  date_format(date_add(a.closed_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as closed
  from am_leakcheck a where leak_id='$leak_id'";
  $result_times = executequery($sql);
  $record_times = go_fetch_array($result_times);
  $dispatch = stripslashes($record_times['dispatch']) . " " . $tz_display;
  $acknowledge = stripslashes($record_times['acknowledge']) . " " . $tz_display;
  $eta = stripslashes($record_times['eta']) . " " . $tz_display;
  $resolved = stripslashes($record_times['resolved']) . " " . $tz_display;
  $confirm = stripslashes($record_times['confirm']) . " " . $tz_display;
  $invoice = stripslashes($record_times['invoice']) . " " . $tz_display;
  $inprogress = stripslashes($record_times['inprogress']) . " " . $tz_display;
  $closed = stripslashes($record_times['closed']) . " " . $tz_display;
  
  $show_resolution = 0;
  
  switch($status){
    case "Dispatched":
	case "Acknowledged":{
	  $dis_bg = "red";
	  $dis_name = "<strong>Dispatched</strong>";
	  $dis_time = $dispatch;
	  
	  $eta_bg = "white";
	  $eta_name = "Arrival ETA";
	  $eta_time = "";
	  
	  $ip_bg = "white";
	  $ip_name = "In Progress";
	  $ip_time = "";
	  
	  $res_bg = "white";
	  $res_name = "Resolved";
	  $res_time = "";
	  
	  $con_bg = "white";
	  $con_name = "Confirmed";
	  $con_time = "";
	  
	  break;
	}
	
	case "Arrival ETA":{
	  $dis_bg = "red";
	  $dis_name = "Dispatched";
	  $dis_time = $dispatch;
	  
	  $eta_bg = "#FF9146";
	  $eta_name = "<strong>Arrival ETA</strong>";
	  $eta_time = $eta;
	  
	  $ip_bg = "white";
	  $ip_name = "In Progress";
	  $ip_time = "";
	  
	  $res_bg = "white";
	  $res_name = "Resolved";
	  $res_time = "";
	  
	  $con_bg = "white";
	  $con_name = "Confirmed";
	  $con_time = "";
	  
	  break;
	}
	
	case "In Progress":{
	  $dis_bg = "red";
	  $dis_name = "Dispatched";
	  $dis_time = $dispatch;
	  
	  $eta_bg = "#FF9146";
	  $eta_name = "Arrival ETA";
	  $eta_time = $eta;
	  
	  $ip_bg = "yellow";
	  $ip_name = "<strong>In Progress</strong>";
	  $ip_time = $inprogress;
	  
	  $res_bg = "white";
	  $res_name = "Resolved";
	  $res_time = "";
	  
	  $con_bg = "white";
	  $con_name = "Confirmed";
	  $con_time = "";
	  
	  break;
	}
	
	case "Resolved":{
	  $dis_bg = "red";
	  $dis_name = "Dispatched";
	  $dis_time = $dispatch;
	  
	  $eta_bg = "#FF9146";
	  $eta_name = "Arrival ETA";
	  $eta_time = $eta;
	  
	  $ip_bg = "yellow";
	  $ip_name = "In Progress";
	  $ip_time = $inprogress;
	  
	  $res_bg = "#00FF00";
	  $res_name = "<strong>Resolved</strong>";
	  $res_time = $resolved;
	  
	  $con_bg = "white";
	  $con_name = "Confirmed";
	  $con_time = "";
	  
	  break;
	}
	
	case "Invoiced":
	case "Closed Out":
	case "Confirmed":{
	  $dis_bg = "red";
	  $dis_name = "Dispatched";
	  $dis_time = $dispatch;
	  
	  $eta_bg = "#FF9146";
	  $eta_name = "Arrival ETA";
	  $eta_time = $eta;
	  
	  $ip_bg = "yellow";
	  $ip_name = "In Progress";
	  $ip_time = $inprogress;
	  
	  $res_bg = "#00FF00";
	  $res_name = "Resolved";
	  $res_time = $resolved;
	  
	  $con_bg = "blue";
	  $con_name = "<strong>Confirmed</strong>";
	  $con_time = $confirm;
	  
	  $show_resolution = 1;
	  
	  break;
	}
  }

/*
$sql = "SELECT *, concat(firstname, ' ', lastname) as fullname from am_users where user_id='$site_user_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_manager_name = stripslashes($record['fullname']);
$site_manager_photo = $record['photo'];
$site_manager_title = stripslashes($record['title']);
$site_manager_phone = stripslashes($record['phone']);
$site_manager_cell = stripslashes($record['cell']);
$site_manager_email = stripslashes($record['email']);
*/

$sql = "SELECT *, concat(firstname, ' ', lastname) as fullname from am_users where user_id='$corporate_user_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$corporate_manager_name = stripslashes($record['fullname']);
$corporate_manager_photo = $record['photo'];
$corporate_manager_title = stripslashes($record['title']);
$corporate_manager_phone = stripslashes($record['phone']);
$corporate_manager_cell = stripslashes($record['cell']);
$corporate_manager_email = stripslashes($record['email']);

$sql = "SELECT master_name, logo from master_list where master_id='$master_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$master_name = stripslashes($record['master_name']);
$master_logo = stripslashes($record['logo']);
if($USEGROUP !=0 && $USEGROUP != ""){
  $sql = "SELECT master_name, logo from groups where id='" . $USEGROUP . "'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $master_name = stripslashes($record['master_name']);
  $master_logo = stripslashes($record['logo']);
}
  
$sql = "SELECT concat(firstname, ' ', lastname) as sd_contact_name, email, phone, mobile from contacts where id='$sd_contact'";
$result = executequery($sql);
$record = go_fetch_array($result);
$sd_contact_name = stripslashes($record['sd_contact_name']);
$sd_contact_email = stripslashes($record['email']);
$sd_contact_phone = stripslashes($record['phone']);
$sd_contact_mobile = stripslashes($record['mobile']);


$table = "";
if($master_logo != "") $table .= "<img src='" . $CORE_URL . "uploaded_files/master_logos/$master_logo'><br>";

  $table .= "
  <table class='main' width='100%'>
  <tr>
  <td valign='top'>
  A Service Dispatch notification has been submitted for the following building:
  <br><br>
  Service Dispatch ID #$leak_id<br>";
  if($invoice_id != $leak_id) $table .= "Invoice ID: $invoice_id<br>";
  $table .= $master_name . "<br>" . $company_name . "<br>" . $site_name . "<br>" . $address . "<br>" . $city . ", " . $state . " " . $zip;
  $table .= "
  <br><br>Section: " . $section_name . "<br>";
  /*
  $table .= "Corporate Contact:<br>" . $corporate_manager_name;
  if($corporate_manager_phone != "") $table .= "<br>" . $corporate_manager_phone;
  if($corporate_manager_email != "") $table .= "<br><a mailto:'$corporate_manager_email'>" . $corporate_manager_email . "</a>";
  */
  if($sd_contact) {
    $table .= "<br>Service Dispatch Contact:<br>" . $sd_contact_name;
	if($sd_contact_email != "") $table .= "<br>Email: $sd_contact_email";
	if($sd_contact_phone != "") $table .= "<br>Phone: $sd_contact_phone";
	if($sd_contact_mobile != "") $table .= "<br>Mobile: $sd_contact_mobile";
  }
	
  /*
  $table .= "
  <br>Site Contact:<br>" . $site_manager_name;
  if($site_manager_phone != "") $table .= "<br>" . $site_manager_phone;
  if($site_manager_email != "") $table .= "<br><a mailto:'$site_manager_email'>" . $site_manager_email . "</a>";
  */
  $table .="
  <br><br>Distributed By: " . $dispatchedby . "<br>Dispatched: " . $dispatch;
  $table .= "<br><br>Priority: " . $xpriority[$priority_id];
  $table .= "</td><td valign='top'>";
  if($sf != "") $table .= "<img src='" . $CORE_URL . "uploaded_files/properties/$sf' width='400'>";
  $table .= "</td></tr></table>";
  $table .= "<div class='main'>";
  if($notes != "") $table .= "Notes: " . nl2br($notes);
  $table .= "<br><br>";
  $table .= "
<div align='center'>
  <div style='width:710px; position:relative; height:32px;'>
    <div style='width:140px; border:1px solid black; background-color:" . $dis_bg . "; height:30px; float:left;'></div>
	<div style='width:140px; border:1px solid black; background-color:" . $eta_bg . "; height:30px; float:left;'></div>
	<div style='width:140px; border:1px solid black; background-color:" . $ip_bg . "; height:30px; float:left;'></div>
	<div style='width:140px; border:1px solid black; background-color:" . $res_bg . "; height:30px; float:left;'></div>
	<div style='width:140px; border:1px solid black; background-color:" . $con_bg . "; height:30px; float:left;'></div>
  </div>
  <div style='clear:both;'></div>
  <div style='width:710px; position:relative;'>
    <div style='width:142px; float:left;' align='center'>" . $dis_name . "</div>
	<div style='width:142px; float:left;' align='center'>" . $eta_name . "</div>
	<div style='width:142px; float:left;' align='center'>" . $ip_name . "</div>
	<div style='width:142px; float:left;' align='center'>" . $res_name . "</div>
	<div style='width:142px; float:left;' align='center'>" . $con_name . "</div>
  </div>
  <div style='clear:both;'></div>
  <div style='width:710px; position:relative;'>
    <div style='width:142px; float:left;' align='center'>" . $dis_time . "</div>
	<div style='width:142px; float:left;' align='center'>" . $eta_time . "</div>
	<div style='width:142px; float:left;' align='center'>" . $ip_time . "</div>
	<div style='width:142px; float:left;' align='center'>" . $res_time . "</div>
	<div style='width:142px; float:left;' align='center'>" . $con_time . "</div>
  </div>
  <div style='clear:both;'></div>
	
</div>";

if($show_resolution){
  $sql = "SELECT problem_desc, correction from am_leakcheck where leak_id='$leak_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $email_problem_desc = nl2br(stripslashes($record['problem_desc']));
  $email_correction = nl2br(stripslashes($record['correction']));
  
  $table .= "<br>";
  $table .= "General Problem Description: $email_problem_desc<br><br>";
  $table .= "General Correction: $email_correction<br><br>";
  $table .= "General Problem Photos";
  $table .= "<table>";
  $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and additional=0 and problem_id=0 and type=1";
  $result = executequery($sql);
  $counter = 0;
  while($record = go_fetch_array($result)){
    if($counter == 0) $table .= "<tr>\n";
	
	$table .= "<td valign=\"top\"><img src=\"" . $FCS_URL . "uploaded_files/leakcheck/" . $record['photo'] . "\"></td>";
	$counter++;
	if($counter==3){
	  $table .= "</tr>\n";
	  $counter = 0;
	}
  }
  $table .= "</table>";
  
  $table .= "General Correction Photos";
  $table .= "<table>";
  $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and additional=0 and problem_id=0 and type=2";
  $result = executequery($sql);
  $counter = 0;
  while($record = go_fetch_array($result)){
    if($counter == 0) $table .= "<tr>\n";
	
	$table .= "<td valign=\"top\"><img src=\"" . $FCS_URL . "uploaded_files/leakcheck/" . $record['photo'] . "\"></td>";
	$counter++;
	if($counter==3){
	  $table .= "</tr>\n";
	  $counter = 0;
	}
  }
  $table .= "</table>";
  
  $sql = "SELECT * from am_leakcheck_problems where leak_id='$leak_id'";
  $result = executequery($sql);
  $test = mysql_num_rows($result);
  if($test != 0){
    $table .=  "<strong>Breakdown of problems:</strong><br>";
  }
  while($record = go_fetch_array($result)){
    $problem_id = $record['problem_id'];
	$x_problem_desc = stripslashes(nl2br($record['problem_desc']));
	$x_correction = stripslashes(nl2br($record['correction']));
	$table .= "Problem Description:<br>";
	$table .= $x_problem_desc;
    $table .= "<br><br>
    Correction:<br>";
    $table .= $x_correction;
    $table .= "<br>
	Problem Photos:
	<table>";
    $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and problem_id='$problem_id' and type=1";
    $result_photo = executequery($sql);
    $counter = 0;
    while($record_photo = go_fetch_array($result_photo)){
      if($counter == 0) $table .=  "<tr>\n";
	  $table .= "
	  <td valign='top' align='center'>
	  <img src='" . $FCS_URL . "uploaded_files/leakcheck/" . $record_photo['photo'] . "'>
	  </td>";
	  $counter++;
	  if($counter==3){
	    $table .=  "</tr>\n";
	    $counter = 0;
	  }
    }
	$table .= "
    </table>
	<br>
	
	Correction Photos:
	<table>";
    $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and problem_id='$problem_id' and type=2";
    $result_photo = executequery($sql);
    $counter = 0;
    while($record_photo = go_fetch_array($result_photo)){
      if($counter == 0) $table .=  "<tr>\n";
	  $table .= "
	  <td valign='top' align='center'>
	  <img src='" . $FCS_URL . "uploaded_files/leakcheck/" . $record_photo['photo'] . "'>
	  </td>";
	  $counter++;
	  if($counter==3){
	    $table .=  "</tr>\n";
	    $counter = 0;
	  }
    }
	$table .= "
    </table>
	<br>
	
	<hr size='1'>";
  }
  
}
/*
$sql = "SELECT warranty_manufacturer, warranty_term, warranty_start, warranty_installer, warranty_contact, warranty_phone, warranty_number 
from info_operations where property_id='$property_id' and warranty_manufacturer != '' order by 
bid_id desc limit 1";
$result = executequery($sql);
$record = go_fetch_array($result);
$warranty_manufacturer = stripslashes($record['warranty_manufacturer']);
$warranty_term = stripslashes($record['warranty_term']);
$warranty_start = stripslashes($record['warranty_start']);
$warranty_installer = stripslashes($record['warranty_installer']);
$warranty_contact = stripslashes($record['warranty_contact']);
$warranty_phone = stripslashes($record['warranty_phone']);
$warranty_number = stripslashes($record['warranty_number']);

if($warranty_manufacturer != ""){
  $table .= "<br><br>Warranty Information";
  $table .= "<table class='main' cellpadding='3'>
  <tr>
  <td align='right'>Manufacturer:</td>
  <td>" . $warranty_manufacturer . "</td>
  <td align='right'>Term:</td>
  <td>" . $warranty_term . "</td>
  </tr>
  <tr>
  <td align='right'>Start:</td>
  <td>" . $warranty_start . "</td>
  <td align='right'>Installer:</td>
  <td>" . $warranty_installer . "</td>
  </tr>
  <tr>
  <td align='right'>Contact:</td>
  <td>" . $warranty_contact . "</td>
  <td align='right'>Phone:</td>
  <td>" . $warranty_phone . "</td>
  </tr>
  <tr>
  <td align='right'>Warranty #:</td>
  <td>" . $warranty_number . "</td>
  <td></td>
  <td></td>
  </tr>
  </table>";
}
*/
$table .= "<br><br>";
$table .= "<a href=\"" . $CORE_URL . "unsubscribe_servicedispatch.php?$leak_id\">Click here to unsubscribe to this series of Service Dispatch notices.</a>";


$sql = "SELECT prospect_id from am_leakcheck where leak_id='$leak_id'";
$prospect_id = getsingleresult($sql);

/*
$sql = "SELECT email from am_users where prospect_id='" . $prospect_id . "' and level_access='Master Admin' and sd_notify=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $sql = "SELECT id from unsubscribe_servicedispatch where email=\"" . $record['email'] . "\" and leak_id='$leak_id'";
  $test = getsingleresult($sql);
  if($test=="") $fcs_email .= $record['email'] . ",";
}
$sql = "SELECT email from am_users where prospect_id='" . $prospect_id . "' and level_access !='Master Admin' and sites like '%," . $property_id . ",%' and sd_notify=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $sql = "SELECT id from unsubscribe_servicedispatch where email=\"" . $record['email'] . "\" and leak_id='$leak_id'";
  $test = getsingleresult($sql);
  if($test=="") $fcs_email .= $record['email'] . ",";
}
*/
// Jim says any fcsview user with sd_notify.  I'll ignore being an admin, or whether or not that property is selected

$sql = "SELECT email from am_users where prospect_id='" . $prospect_id . "' and enabled=1 and sd_notify=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $sql = "SELECT id from unsubscribe_servicedispatch where email=\"" . $record['email'] . "\" and leak_id='$leak_id'";
  $test = getsingleresult($sql);
  if($test=="") $fcs_email .= $record['email'] . ",";
}


$sql = "SELECT email, groups, subgroups from users where enabled=1 and master_id='$master_id' and on_sdemail_list=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  if($property['groups'] != "" && $record['groups'] != ""){
    if(!(go_reg("," . $property['groups'] . ",", $record['groups']))) continue;
  }
  if($property['subgroups'] != "" && $record['subgroups'] != ""){
    if(!(go_reg("," . $property['subgroups'] . ",", $record['subgroups']))) continue;
  }
  $ro_email .= $record['email'] . ",";
}

/* not using resources, only nrp is
$sql = "SELECT resource_id, accept_resource from am_leakcheck where leak_id='$leak_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$resource_id = $record['resource_id'];
$accept_resource = $record['accept_resource'];
if($accept_resource==1 && $resource_id != 0){
  $sql = "SELECT code from am_leakcheck where leak_id='$leak_id'";
  $code = getsingleresult($sql);
  $table .= "<br><br>";
  $table .= "<a href='" . $CORE_URL . "public_resource_resolve_pdf.php?code=" . $code . "' target='_blank'>Proof Doc</a>";
  $sql = "SELECT email from contacts where email != '' and prospect_id='$resource_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $ro_email .= stripslashes($record['email']) . ",";
  }
}
*/
$sql = "SELECT b.email from am_leakcheck a, users b where a.servicemen_id = b.user_id and b.enabled=1 and a.leak_id='$leak_id'";
$service_email = getsingleresult($sql);
if($service_email) $ro_email .= $service_email;

//$ro_email .= "dispatch@roofoptions.com";

$sql = "SELECT silent_mode from am_leakcheck where leak_id='$leak_id'";
$silent_mode = getsingleresult($sql);
if($silent_mode){
  $fcs_email = "";
  $ro_email = "";
}
 
?>