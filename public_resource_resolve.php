<?php
include "includes/header_white.php";

$code = $_GET['code'];
$jwtest = $_GET['jwtest'];

if($jwtest != ""){
  $leak_id = $jwtest;
}
else {
$sql = "SELECT leak_id from am_leakcheck where code='$code'";
$leak_id = getsingleresult($sql);
if($leak_id==""){
  echo "There was an error accessing this Service Dispatch.  Please check the link in your email and try again.";
  exit;
}
}
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

$sql = "SELECT b.site_name,  
date_format(a.dispatch_date, \"%m/%d/%Y\") as datepretty,  
date_format(dispatch_date, \"%r\") as timepretty, a.property_id, a.section_type, 
b.city, b.state, b.address, b.zip, a.prospect_id, a.priority_id, 
a.priority, a.nte, a.nte_amount, a.labor_rate, a.open_resource, date_format(a.resource_due_date, \"%m/%d/%Y\") as resource_due_date_pretty, 
date_format(a.eta_date, \"%m/%d/%Y %h:%i %p\") as eta_pretty, a.eta_message, b.timezone, a.invoice_id, a.invoice_type, a.other_cost, a.upsell, 
a.po_number, a.notes, a.additional_notes, a.accept_resource, 
$imagequery 
from am_leakcheck a, properties b 
where a.property_id=b.property_id and 
a.leak_id=\"$leak_id\"";
$result = executequery($sql);


$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);
$property_id = stripslashes($record['property_id']);
$prospect_id = stripslashes($record['prospect_id']);
$invoice_id = stripslashes($record['invoice_id']);
$sf = $record['ximage'];
$invoice_type = stripslashes($record['invoice_type']);
$other_cost = stripslashes($record['other_cost']);
$upsell = stripslashes($record['upsell']);
$other_cost -= $upsell;

$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$address = stripslashes($record['address']);
$zip = stripslashes($record['zip']);

$nte = stripslashes($record['nte']);
$nte_amount = stripslashes($record['nte_amount']);
$labor_rate = stripslashes($record['labor_rate']);
$open_resource = stripslashes($record['open_resource']);
$resource_due_date_pretty = stripslashes($record['resource_due_date_pretty']);
$priority_id = stripslashes($record['priority_id']);
$eta_pretty = stripslashes($record['eta_pretty']);
$eta_message = stripslashes($record['eta_message']);

$datepretty = stripslashes($record['datepretty']);
$timepretty = stripslashes($record['timepretty']);
$timezone = stripslashes($record['timezone']);

$notes = stripslashes($record['notes']);
$po_number = stripslashes($record['po_number']);
$additional_notes = stripslashes($record['additional_notes']);
$accept_resource = stripslashes($record['accept_resource']);

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

$sql = "SELECT b.master_id from am_leakcheck a, prospects b where a.prospect_id=b.prospect_id and a.leak_id='$leak_id'";
  $master_id = getsingleresult($sql);

  
  $sql = "SELECT logo, master_name, emergency_time_frame, urgent_time_frame, scheduled_time_frame from master_list where master_id='" . $master_id . "'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $logo = stripslashes($record['logo']);
  $master_name = stripslashes($record['master_name']);
  $emergency_time_frame = stripslashes($record['emergency_time_frame']);
  $urgent_time_frame = stripslashes($record['urgent_time_frame']);
  $scheduled_time_frame = stripslashes($record['scheduled_time_frame']);

$sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
$company_name = stripslashes(getsingleresult($sql));

?>
<body style="margin:10px 10px 10px 10px;">
<script src="includes/calendar.js"></script>

<div class="main">
<?php if($logo != ""){ ?>
<img src="uploaded_files/master_logos/<?=$logo?>"><br>
<?php } ?>
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div style="color:red;"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<div style="width:100%; position:relative;">
<div style="float:left;">
<?php if($open_resource){ ?>
<form action="public_resource_resolve_action.php" method="post">
<?php } ?>
<input type="hidden" name="code" value="<?=$code?>">

Invoice #<?=$invoice_id?> <?=$datepretty?> <?=$timepretty?> <?=$tz_display?><br>
PO # <?=$po_number?><br>
<?=$company_name?><br>
<?=$site_name?><br>
<?=$address?><br>
<?=$city?>, <?=$state?> <?=$zip?>
<br><br>
<?php


if($priority_id != 4){
  if($invoice_type=="Billable - Contract"){
    echo "Contract Amount: $" . number_format($other_cost, 2) . "<br>";
  }
  else {
    if($nte==1) echo "Not to exceed: $" . number_format($nte_amount, 2) . "<br>";
    if($labor_rate != 0 && $labor_rate != "") echo "Labor Rate: $" . $labor_rate . " /hr<br>";
  }
}
if($priority_id==3) echo "Targeted Response: $emergency_time_frame<br><br>";
if($priority_id==2) echo "Targeted Response: $urgent_time_frame<br><br>";
if($priority_id==1) echo "Targeted Response: $scheduled_time_frame<br><br>";

if($resource_due_date_pretty != "00/00/0000") echo "Must complete by: $resource_due_date_pretty<br><br>";

$sql = "SELECT problem_name, problem_desc from am_leakcheck_problems where leak_id='$leak_id'";
$result = executequery($sql);
if(mysql_num_rows($result)){
  echo "Known problems:<br><br>";
  while($record = go_fetch_array($result)){
    echo "<strong>" . stripslashes($record['problem_name']) . "</strong> ";
	echo stripslashes(nl2br($record['problem_desc'])) . "<br><br>";
  }
}

if($priority_id==4){
  echo "We are requesting a confirmation that your company can provide a 'No Cost Proposal' on the above referenced property..<br><br>";
}
else {
  echo "We are requesting a confirmation that your company can provide service on the above reference property within the targeted response time.<br><br>";
}

?>
<?php if($notes != "") echo "Service Dispatch Notes:<br>" . nl2br($notes) . "<br><br>";?>
<?php if($additional_notes != "") echo "Additional Notes:<br>" . nl2br($additional_notes) . "<br><br>";?>
<?php
  $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and additional=2";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    ?>
	<img src="<?=$FCS_URL?>uploaded_files/leakcheck/<?=$record['photo']?>"><br>
	<?php
  }
  ?>
  <?php
  $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and additional=3";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    ?>
	<a href="<?=$FCS_URL?>uploaded_files/leakcheck/<?=$record['photo']?>"><img src="images/doc.png" border="0"><br>Important Document</a><br>
	<?php
  }
  ?>
<br><br>
<strong>YES! I can meet this Service Dispatch request.</strong><br>
<?php
$eta_date_pretty = date("m/d/Y");
  $hour = date("g");
  $ampm = date("A");
?>
ETA:
<?php if($open_resource){ ?>	
	<input size="10" type="text" name="eta_date_pretty" value="<?=$eta_date_pretty?>"> 
    <img src="images/calendar.gif" onClick="KW_doCalendar('eta_date_pretty',0)" align="absmiddle">
    <br>
    <select name="hour">
    <?php
    for($x=1;$x<=12;$x++){
      ?>
	  <option value="<?=$x?>"<?php if($x== $hour) echo " selected";?>><?=$x?></option>
	  <?php
    }
    ?>
    </select> : 
    <select name="minute">
    <option value="0">00</option>
    <option value="15"<?php if($minute==15) echo " selected";?>>15</option>
    <option value="30"<?php if($minute==30) echo " selected";?>>30</option>
    <option value="45"<?php if($minute==45) echo " selected";?>>45</option>
    </select>
    <select name="ampm">
    <option value="AM"<?php if($ampm=="AM") echo " selected";?>>AM</option>
    <option value="PM"<?php if($ampm=="PM") echo " selected";?>>PM</option>
    </select>
	<?=$tz_display?>
	<br><br>Notes<br>

	<textarea name="eta_message" rows="3" cols="50"></textarea>
<?php } else { ?>
<?=$eta_pretty?><br><br><?=nl2br($eta_message)?>
<?php } ?>

<?php if($open_resource){ ?>
<br><br>
<input type="submit" name="submit1" value="YES! I can meet this Service Dispatch request">
<br><br>
- OR -
<br><br>
<input type="submit" name="submit1" value="NO, I am unable to meet this Service Dispatch request">
<?php } ?>
</form>
</div>
<div style="float:right;">
<?php if($sf){ ?>
<img src="uploaded_files/properties/<?=$sf?>"><br><br>
<?php } ?>
</div>
</div>
<div style="clear:both;"></div>
<?php if($open_resource==0 && $accept_resource==1){?>
<a href="public_resource_resolve_pdf.php?code=<?=$code?>" target="_blank">Printable PDF Version</a>
<?php } ?>
</div>
</body>
