<?php
include "includes/functions.php";
$leak_id = $_GET['leak_id'];
$field = $_GET['field'];
$action = $_GET['action'];

$mat_description = go_escape_string($_GET['mat_description']);
$mat_quantity = go_escape_string($_GET['mat_quantity']);
$mat_unit = go_escape_string($_GET['mat_unit']);
$mat_cost = go_escape_string($_GET['mat_cost']);

$other_description = go_escape_string($_GET['other_description']);
$other_quantity = go_escape_string($_GET['other_quantity']);
$other_unit = go_escape_string($_GET['other_unit']);
$other_cost = go_escape_string($_GET['other_cost']);

$mat_id = go_escape_string($_GET['mat_id']);
$other_id = go_escape_string($_GET['other_id']);

$checked = go_escape_string($_GET['checked']);

$desc_work_performed = go_escape_string($_GET['desc_work_performed']);
$desc_work_performed = go_reg_replace("NEWLINE", "\n", $desc_work_performed);
$desc_work_performed = go_reg_replace("AMPERSAND", "&", $desc_work_performed);
$desc_work_performed = go_reg_replace("POUNDSIGN", "#", $desc_work_performed);
$desc_work_performed = go_reg_replace("DBLQUOTE", "\"", $desc_work_performed);
  
$billto = go_escape_string($_GET['billto']);
$billto = go_reg_replace("NEWLINE", "\n", $billto);
$billto = go_reg_replace("AMPERSAND", "&", $billto);
$billto = go_reg_replace("POUNDSIGN", "#", $billto);
$billto = go_reg_replace("DBLQUOTE", "\"", $billto);

$desc_work_performed = go_escape_string($desc_work_performed);
$billto = go_escape_string($billto);

if($action=="form"){

  // always pass and update desc_work_performed and billto, because they were editing, not saving, then editing line items
  $sql = "UPDATE am_leakcheck set desc_work_performed=\"$desc_work_performed\", billto=\"$billto\" where leak_id='$leak_id'";
  executeupdate($sql);
  
  switch($field){
    case "invoice_sent_date":
    case "invoice_due_date":{
	  $sql = "SELECT date_format($field, \"%m/%d/%Y\") from am_leakcheck where leak_id='$leak_id'";
	  break;
	}
	default:{
      $sql = "SELECT $field from am_leakcheck where leak_id='$leak_id'";
	  break;
	}
  }
  $value = stripslashes(getsingleresult($sql));

  $html = "";
  switch($field){
    case "invoice_sent_date":
    case "invoice_due_date":{
	  $html .= "<input type='text' size='10' maxlength='255' name='newvalue' id='newvalue' value=\\\"" . $value . "\\\">";
	  $html .= "<img src='images/calendar.gif' onClick=\\\"KW_doCalendar('newvalue',0)\\\" align='absmiddle'>";
      $html .= "<input type='button' name='button1' value='Save' onclick=\\\"updateField('" . $field . "')\\\">";
	  break;
	}
	case "other_unit":{
	  $html .="<select name='newvalue' id='newvalue'>";
	  $html .="<option value='EA'";
	  if($value=="EA") $html .= " selected";
	  $html .=">EA</option>";
	  $html .="<option value='LF'";
	  if($value=="LF") $html .= " selected";
	  $html .=">LF</option>";
	  $html .="<option value='SF'";
	  if($value=="SF") $html .= " selected";
	  $html .=">SF</option>";
	  $html .="<option value='Hrs'";
	  if($value=="Hrs") $html .= " selected";
	  $html .=">Hrs</option>";
	  $sql = "SELECT unit from material_list where master_id='" . $SESSION_MASTER_ID . "'  and unit != 'SF' and unit !='LF' and unit != 'EA' group by unit order by unit";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	  
	    $html .= "<option value='" . stripslashes($record['unit']) . "'";
		if($value==stripslashes($record['unit'])) $html .= " selected";
		$html .= ">" . stripslashes($record['unit']) . "</option>";
	  }

	  $html .= "</select>";
	  $html .= "<input type='button' name='button1' value='Save' onclick=\\\"updateField('" . $field . "')\\\">";
	  break;
	}
	case "travel_label_desc":{
	  $html .="<select name='newvalue' id='newvalue'>";
	  $html .="<option value='Travel'";
	  if($value=="Travel") $html .= " selected";
	  $html .=">Travel</option>";
	  $html .="<option value='Trip'";
	  if($value=="Trip") $html .= " selected";
	  $html .=">Trip</option>";
	  $html .="<option value='None'";
	  if($value=="None") $html .= " selected";
	  $html .=">None</option>";
	  $html .= "</select>";
	  $html .= "<input type='button' name='button1' value='Save' onclick=\\\"updateField('" . $field . "')\\\">";
	  break;
	}
	default:{
      $html .= "<input type='text' size='20' maxlength='255' name='newvalue' id='newvalue' value=\\\"" . $value . "\\\">";
      $html .= "<input type='button' name='button1' value='Save' onclick=\\\"updateField('" . $field . "')\\\">";
	  break;
	}
  }
  
  ?>
  div = document.getElementById('<?=$field?>');
  div.innerHTML = "<?php echo $html; ?>";
  <?php
}

if($action=="update"){
  $newvalue = go_escape_string($_GET['newvalue']);
  $newvalue = go_reg_replace("AMPERSAND", "&", $newvalue);
  if($field=="invoice_due_date" || $field=="invoice_sent_date"){
    $date_parts = explode("/", $newvalue);
    $newvalue = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
  }
  $sql = "UPDATE am_leakcheck set $field=\"$newvalue\" where leak_id='$leak_id'";
  executeupdate($sql);
  
  if($field=="travel_time" && $newvalue==0){ // if manually setting to zero, force it to always stay zero, nullifying cron_leaktime
    $sql = "UPDATE am_leakcheck set force_zero_travel=1 where leak_id='$leak_id'";
	executeupdate($sql);
  }
  if($field=="labor_time" && $newvalue==0){ // if manually setting to zero, force it to always stay zero, nullifying cron_leaktime
    $sql = "UPDATE am_leakcheck set force_zero_labor=1 where leak_id='$leak_id'";
	executeupdate($sql);
  }
  
}

if($action=="formmat"){
  // always pass and update desc_work_performed and billto, because they were editing, not saving, then editing line items
  $sql = "UPDATE am_leakcheck set desc_work_performed=\"$desc_work_performed\", billto=\"$billto\" where leak_id='$leak_id'";
  executeupdate($sql);
  
  $sql = "SELECT $field from am_leakcheck_materials where leak_id='$leak_id' and id='$mat_id'";
  $value = stripslashes(getsingleresult($sql));

  $html = "";
  switch($field){
    case "units":{
	  $html .="<select name='newvalue' id='newvalue'>";
	  $html .="<option value='EA'";
	  if($value=="EA") $html .= " selected";
	  $html .=">EA</option>";
	  $html .="<option value='LF'";
	  if($value=="LF") $html .= " selected";
	  $html .=">LF</option>";
	  $html .="<option value='SF'";
	  if($value=="SF") $html .= " selected";
	  $html .=">SF</option>";
	  $sql = "SELECT unit from material_list where master_id='" . $SESSION_MASTER_ID . "'  and unit != 'SF' and unit !='LF' and unit != 'EA' group by unit order by unit";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	  
	    $html .= "<option value='" . stripslashes($record['unit']) . "'";
		if($value==stripslashes($record['unit'])) $html .= " selected";
		$html .= ">" . stripslashes($record['unit']) . "</option>";
	  }
	  $html .= "</select>";
	  $html .= "<input type='button' name='button1' value='Save' onclick=\\\"updateFieldMat('" . $field . "', '" . $mat_id . "')\\\">";
	  break;
	}
	  
	default:{
      $html .= "<input type='text' size='20' maxlength='255' name='newvalue' id='newvalue' value=\\\"" . $value . "\\\">";
      $html .= "<input type='button' name='button1' value='Save' onclick=\\\"updateFieldMat('" . $field . "', '" . $mat_id . "')\\\">";
	  break;
	}
  }
  ?>
  div = document.getElementById('mat_<?=$field?>_<?=$mat_id?>');
  div.innerHTML = "<?php echo $html; ?>";
  <?php
}

if($action=="updatemat"){
  $newvalue = go_escape_string($_GET['newvalue']);
  $newvalue = go_reg_replace("AMPERSAND", "&", $newvalue);
  $sql = "UPDATE am_leakcheck_materials set $field=\"$newvalue\" where leak_id='$leak_id' and id='$mat_id'";
  executeupdate($sql);
  $action="refresh";
}

if($action=="delmat"){
  $sql = "DELETE from am_leakcheck_materials where id='$mat_id' and leak_id='$leak_id'";
  executeupdate($sql);
  $action = "refresh";
}


if($action=="viewedit"){
  $payfcs_edit_sd_mode = $SESSION_EDIT_SD_MODE;
  
  if($payfcs_edit_sd_mode == "" || $payfcs_edit_sd_mode==0) {
    $_SESSION[$sess_header . '_edit_sd_mode'] = 1;
  }
  if($payfcs_edit_sd_mode==1) {
    $_SESSION[$sess_header . '_edit_sd_mode'] = 0;
	$sql = "UPDATE am_leakcheck set desc_work_performed=\"$desc_work_performed\", billto=\"$billto\" where leak_id='$leak_id'";
	executeupdate($sql);
  }
  $SESSION_EDIT_SD_MODE = $_SESSION[$sess_header . '_edit_sd_mode'];
  $action = "update";
}

if($action=="newmaterial"){
  $good_to_go = 1;
  if($mat_description=="") $good_to_go = 0;
  if($mat_unit=="") $good_to_go = 0;
  if($mat_quantity=="" || $mat_quantity==0) $good_to_go = 0;
  if($mat_cost=="" || $mat_cost==0) $good_to_go = 0;
  $mat_description = go_reg_replace("AMPERSAND", "&", $mat_description);
  if($good_to_go==1){
    $sql = "INSERT into am_leakcheck_materials(leak_id, description, quantity, units, cost) values('$leak_id', \"$mat_description\", 
	\"$mat_quantity\", \"$mat_unit\", \"$mat_cost\")";
	executeupdate($sql);
  }
  $action = "update";
  $clear_new_mat=1;
}


if($action=="formother"){
  // always pass and update desc_work_performed and billto, because they were editing, not saving, then editing line items
  $sql = "UPDATE am_leakcheck set desc_work_performed=\"$desc_work_performed\", billto=\"$billto\" where leak_id='$leak_id'";
  executeupdate($sql);
  
  $sql = "SELECT $field from am_leakcheck_othercost where leak_id='$leak_id' and other_id='$other_id'";
  $value = stripslashes(getsingleresult($sql));

  $html = "";
  switch($field){
    case "units":{
	  $html .="<select name='newvalue' id='newvalue'>";
	  $html .="<option value='EA'";
	  if($value=="EA") $html .= " selected";
	  $html .=">EA</option>";
	  $html .="<option value='LF'";
	  if($value=="LF") $html .= " selected";
	  $html .=">LF</option>";
	  $html .="<option value='SF'";
	  if($value=="SF") $html .= " selected";
	  $html .=">SF</option>";
	  $html .="<option value='Hrs'";
	  if($value=="Hrs") $html .= " selected";
	  $html .=">Hrs</option>";
	  $sql = "SELECT unit from material_list where master_id='" . $SESSION_MASTER_ID . "'  and unit != 'SF' and unit !='LF' and unit != 'EA' group by unit order by unit";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	  
	    $html .= "<option value='" . stripslashes($record['unit']) . "'";
		if($value==stripslashes($record['unit'])) $html .= " selected";
		$html .= ">" . stripslashes($record['unit']) . "</option>";
	  }
	  $html .= "</select>";
	  $html .= "<input type='button' name='button1' value='Save' onclick=\\\"updateFieldOther('" . $field . "', '" . $other_id . "')\\\">";
	  break;
	}
	  
	default:{
      $html .= "<input type='text' size='20' maxlength='255' name='newvalue' id='newvalue' value=\\\"" . $value . "\\\">";
      $html .= "<input type='button' name='button1' value='Save' onclick=\\\"updateFieldOther('" . $field . "', '" . $other_id . "')\\\">";
	  break;
	}
  }
  ?>
  div = document.getElementById('other_<?=$field?>_<?=$other_id?>');
  div.innerHTML = "<?php echo $html; ?>";
  <?php
}

if($action=="updateother"){
  $newvalue = go_escape_string($_GET['newvalue']);
  $newvalue = go_reg_replace("AMPERSAND", "&", $newvalue);
  $sql = "UPDATE am_leakcheck_othercost set $field=\"$newvalue\" where leak_id='$leak_id' and other_id='$other_id'";
  executeupdate($sql);
  $action="refresh";
}

if($action=="delother"){
  $sql = "DELETE from am_leakcheck_othercost where other_id='$other_id' and leak_id='$leak_id'";
  executeupdate($sql);
  $action = "refresh";
}

if($action=="newother"){
  $good_to_go = 1;
  if($other_description=="") $good_to_go = 0;
  if($other_unit=="") $good_to_go = 0;
  if($other_quantity=="" || $other_quantity==0) $good_to_go = 0;
  if($other_cost=="" || $other_cost==0) $good_to_go = 0;
  $other_description = go_reg_replace("AMPERSAND", "&", $other_description);
  if($good_to_go==1){
    $sql = "INSERT into am_leakcheck_othercost(leak_id, description, quantity, units, cost) values('$leak_id', \"$other_description\", 
	\"$other_quantity\", \"$other_unit\", \"$other_cost\")";
	executeupdate($sql);
  }
  $action = "update";
  $clear_new_mat=1;
}

if($action=="set_taxable"){
  switch(true){
    case go_reg("mat\_", $field):{
	  $field = go_reg_replace("mat\_", "", $field);
	  $sql = "UPDATE am_leakcheck_materials set taxable='$checked' where id='$field' and leak_id='$leak_id'";
	  executeupdate($sql);
	  break;
	}
	case go_reg("other\_", $field):{
	  $field = go_reg_replace("other\_", "", $field);
	  $sql = "UPDATE am_leakcheck_othercost set taxable='$checked' where other_id='$field' and leak_id='$leak_id'";
	  executeupdate($sql);
	  break;
	}
    default:{
      $sql = "UPDATE am_leakcheck set $field='$checked' where leak_id='$leak_id'";
	  executeupdate($sql);
	  break;
	}
  }

  $action="refresh";
}

if($action=="lock"){
  $sql = "SELECT resource_invoice_locked from am_leakcheck where leak_id='$leak_id'";
  $resource_invoice_locked = getsingleresult($sql);
  if($resource_invoice_locked==1){
    $resource_invoice_locked=0;
	$new_lock_label = "Lock Invoice";
	$showlock = "<font color='green'>Unlocked</font>";
  }
  else {
    $resource_invoice_locked=1;
	$new_lock_label = "Unlock Invoice";
	$showlock = "<font color='red'>Locked</font>";
  }
  $sql = "UPDATE am_leakcheck set resource_invoice_locked='$resource_invoice_locked' where leak_id='$leak_id'";
  executeupdate($sql);
  ?>
  document.getElementById('lockbutton').value="<?=$new_lock_label?>";
  document.getElementById('showlock').innerHTML="<?=$showlock?>";
  <?php
}

if($action=="refresh") $action = "update"; // I want it to run this stuff where it reshows the table, but not actually try to update any field

if($action=="update"){
$sql = "SELECT *, date_format(invoice_due_date, \"%m/%d/%Y\") as invoice_due_date_pretty, date_format(invoice_sent_date, \"%m/%d/%Y\") as invoice_sent_date_pretty 
from am_leakcheck where leak_id='$leak_id'";
$result = executequery($sql);
$record = go_fetch_array($result);

$labor_rate = stripslashes($record['labor_rate']);
$materials_cost = stripslashes($record['materials_cost']);
$other_cost = stripslashes($record['other_cost']);
$travel_desc = stripslashes($record['travel_desc']);
$labor_desc = stripslashes($record['labor_desc']);
$other_desc = stripslashes($record['other_desc']);
$travel_time = stripslashes($record['travel_time']);
$labor_time = stripslashes($record['labor_time']);
$travel_rate = stripslashes($record['travel_rate']);
$other_quantity = stripslashes($record['other_quantity']);
$other_unit = stripslashes($record['other_unit']);
$labor_taxable = stripslashes($record['labor_taxable']);
$travel_taxable = stripslashes($record['travel_taxable']);
$other_taxable = stripslashes($record['other_taxable']);
$contract_taxable = stripslashes($record['contract_taxable']);
$tax_amount = stripslashes($record['tax_amount']);
$invoice_id = stripslashes($record['invoice_id']);
$custom_field = stripslashes($record['custom_field']);
$custom_field2 = stripslashes($record['custom_field2']);
$invoice_type = stripslashes($record['invoice_type']);
$simple_invoice = stripslashes($record['simple_invoice']);
$travel_label_desc = stripslashes($record['travel_label_desc']);
$contract_amount = stripslashes($record['contract_amount']);

$dotax=1;
if($tax_amount != 0) $dotax=0;
$dotax=1; // always calculate tax now!

$travel_time = number_format($travel_time, 2);
$labor_time = number_format($labor_time, 2);
$travel_cost = $travel_rate * $travel_time;
$labor_cost = $labor_rate * $labor_time;
$labor_cost_raw = $labor_cost;
$travel_cost_raw = $travel_cost;
$travel_cost = number_format($travel_cost, 2);
$labor_cost = number_format($labor_cost, 2);
$desc_work_performed = stripslashes($record['desc_work_performed']);
$invoice_due_date_pretty = stripslashes($record['invoice_due_date_pretty']);
if($invoice_due_date_pretty=="00/00/0000") $invoice_due_date_pretty = "[DATE]";
$invoice_sent_date_pretty = stripslashes($record['invoice_sent_date_pretty']);
if($invoice_sent_date_pretty=="00/00/0000") $invoice_sent_date_pretty = date("m/d/Y");

$billto = stripslashes($record['billto']);

$desc_work_performed = go_reg_replace("\n", "ZZZZ", $desc_work_performed);
$billto = go_reg_replace("\n", "ZZZZ", $billto);

$sql = "SELECT master_name, address, city, state, zip, phone, fax from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
$master = go_fetch_array($result);

$sql = "SELECT company_name, address, city, state, zip from prospects where prospect_id='" . $record['prospect_id'] . "'";
$result = executequery($sql);
$company = go_fetch_array($result);

$sql = "SELECT site_name, address, city, state, zip, image, image_front, property_id, groups, subgroups from properties where property_id='" . $record['property_id'] . "'";
$result = executequery($sql);
$property = go_fetch_array($result);

$sql = "SELECT custom_sd_field from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$custom_sd_field = getsingleresult($sql);

$sql = "SELECT custom_sd_field2 from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$custom_sd_field2 = getsingleresult($sql);

$property['groups'] = go_reg_replace(",", "", stripslashes($property['groups']));
$property['subgroups'] = go_reg_replace(",", "", stripslashes($property['subgroups']));
$USEGROUP = 0;
if($property['groups'] != "" && $property['groups'] !=0) $USEGROUP = $property['groups'];
if($property['subgroups'] != "" && $property['subgroups'] != 0) $USEGROUP = $property['subgroups'];
if($USEGROUP != 0 && $USEGROUP != ""){
  $sql = "SELECT master_name, address, city, state, zip, invoice_user, phone, website, custom_sd_field, custom_sd_field2, logo, master_name, fax, checks_payable_to from groups where id='$USEGROUP'";
  $result = executequery($sql);
  $groups = go_fetch_array($result);
//  $custom_sd_field = stripslashes($groups['custom_sd_field']);  // used if groups/subgroups need their own custom fields
//  $custom_sd_field2 = stripslashes($groups['custom_sd_field2']);  // used if groups/subgroups need their own custom fields
}
  

if($SESSION_EDIT_SD_MODE==1){ 
	  if($travel_desc=="") $travel_desc = "[DESCRIPTION]";
	  if($labor_desc=="") $labor_desc = "[DESCRIPTION]";
	  if($other_desc=="") $other_desc = "[DESCRIPTION]";
	  if($custom_field=="") $custom_field = "[]";
	  if($custom_field2=="") $custom_field2 = "[]";
	  if($labor_rate=="") $labor_rate = 0;
	  if($labor_time=="") $labor_time = 0;
	  if($travel_rate=="") $travel_rate = 0;
	  if($travel_time=="") $travel_time = 0;
	  if($other_unit=="") $other_unit = "[]";
	  
}




ob_start();
?>
<?php if($SESSION_EDIT_SD_MODE==1){?>
<div style="width:100%; position:relative;">
<div style="float:left;">
<input type="checkbox" name="simple_invoice" id="simple_invoice" value="1"<?php if($simple_invoice==1) echo " checked";?> onchange="updateField('simple_invoice')"> Use Simple Invoice
</div>
<div style="float:right;">
<strong>Bill To:</strong>
<select name="billto_select" onchange="billto_pop(this)">
<option value="">
<option value="company">Company</option>
<option value="property">Property</option>
<?php
$sql = "SELECT drawing_id, bill_to, bt_installer, bt_manufacturer from drawings where type='Warranty' and property_id='" . $property['property_id'] . "'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $drawing_id = $record['drawing_id'];
  $xb = $record['bill_to'];
  $bt_installer = stripslashes($record['bt_installer']);
  $bt_manufacturer = stripslashes($record['bt_manufacturer']);
  if($xb=="Installer") $xcompany = $bt_installer . " (Installer)";
  if($xb=="Manufacturer") $xcompany = $bt_manufacturer . " (Manufacturer)";
  ?>
  <option value="<?=$drawing_id?>"><?=$xcompany?></option>
  <?php
}
?>
</select>
</div>
</div>
<div style="clear:both;"></div>
<?php } ?>
<div style="width:100%; position:relative;">
<div style="float:left; width:50%;">
<?=stripslashes($master['master_name'])?><br>
<?=stripslashes($master['address'])?><br>
<?=stripslashes($master['city'])?>, <?=stripslashes($master['state'])?> <?=stripslashes($master['zip'])?><br>
<?php if($master['phone'] != ""){?>
P:<?=$master['phone']?><br>
<?php } ?>
<?php if($master['fax'] != ""){?>
F:<?=$master['fax']?><br>
<?php } ?>
<br>
Invoice To:
<div id="billto_area">
<?php if($SESSION_EDIT_SD_MODE==1){?>
<textarea name="billto" id="billto" rows="5" cols="30"><?=$billto?></textarea>
<?php }  else { ?>
<input type="hidden" name="billto" id="billto" value="<?=$billto?>">
<?php
$billto = go_reg_replace("ZZZZ", "\n", $billto);
?>
<?=nl2br($billto)?>
<?php } ?>
</div>
</div>

<div style="width:50%; float:left;">
<table class="main">
<tr>
<td>Invoice ID#</td>
<td>
    <?php if($SESSION_EDIT_SD_MODE==1){?>
	<?php /*<span id="invoice_id"><a href="javascript:editField('invoice_id')"><?=$invoice_id?></a></span> Invoice ID editable at top now 6/6/12 JW */?>
	<?=$invoice_id?>
	<?php } else { ?>
	<?=$invoice_id?>
	<?php } ?>
</td>
</tr>
<tr>
<td>Date</td>
<td>
    <?php if($SESSION_EDIT_SD_MODE==1){?>
	<span id="invoice_sent_date"><a href="javascript:editField('invoice_sent_date')"><?=$invoice_sent_date_pretty?></a></span>
	<?php } else { ?>
	<?=$invoice_sent_date_pretty?>
	<?php } ?>
</td>
</tr>
<tr>
<td>Due Date</td>
<td>
    <?php if($SESSION_EDIT_SD_MODE==1){?>
	<span id="invoice_due_date"><a href="javascript:editField('invoice_due_date')"><?=$invoice_due_date_pretty?></a></span>
	<?php } else { ?>
	<?=$invoice_due_date_pretty?>
	<?php } ?>
</td>
</tr>
<?php if($custom_sd_field != ""){ ?>
<tr>
<td><?=$custom_sd_field?></td>
<td>
    <?php if($SESSION_EDIT_SD_MODE==1){?>
	<span id="custom_field"><a href="javascript:editField('custom_field')"><?=$custom_field?></a></span>
	<?php } else { ?>
	<?=$custom_field?>
	<?php } ?>
</td>
</tr>
<?php } ?>
<?php if($custom_sd_field2 != ""){ ?>
<tr>
<td><?=$custom_sd_field2?></td>
<td>
    <?php if($SESSION_EDIT_SD_MODE==1){?>
	<span id="custom_field2"><a href="javascript:editField('custom_field2')"><?=$custom_field2?></a></span>
	<?php } else { ?>
	<?=$custom_field2?>
	<?php } ?>
</td>
</tr>
<?php } ?>
</table>
<br>
PROPERTY:<br>
<?=stripslashes($property['site_name'])?><br>
<?=stripslashes($property['address'])?><br>
<?=stripslashes($property['city'])?>, <?=stripslashes($property['state'])?> <?=stripslashes($property['zip'])?><br>
</div>
</div>
<div style="clear:both;">
<?php
if($SESSION_EDIT_SD_MODE==1){
?>
<table width="100%" class="main" cellpadding="2" cellspacing="0">
<tr bgcolor="#CFE7E5">
<td colspan="2"><strong>Description of work performed</strong></td>
</tr>
<tr>
<td><textarea name="desc_work_performed" rows="4" cols="90" id="desc_work_performed"><?=$desc_work_performed?></textarea></td>
<td valign="top"><input type="submit" name="submit1" value="Save"></td>
</tr>
</table>
<?php } else { ?>
<input type="hidden" name="desc_work_performed" id="desc_work_performed" value="<?=$desc_work_performed?>">
<?php $desc_work_performed = go_reg_replace("ZZZZ", "\n", $desc_work_performed); ?>
<?php if($desc_work_performed != ""){ ?>
<table width="100%" class="main" cellpadding="2" cellspacing="0">
<tr bgcolor="#CFE7E5">
<td><strong>Description of work performed</strong></td>
</tr>
<tr>
<td><?=nl2br($desc_work_performed)?></td>
</tr>
</table>
<?php } ?>
<?php } ?>
    <table width="100%" class="main" cellpadding="2" cellspacing="0">
	<tr bgcolor="#CFE7E5">
	<td><strong>Taxable</strong></td>
	<td><strong>Type</strong></td>
	<td><strong>Description</strong></td>
	<td align="right"><strong>Qty</strong></td>
	<td><strong>Units</strong></td>
	<td align="right"><strong>Unit Cost</strong></td>
	<td align="right"><strong>Total Cost</strong></td>
	<td></td>
	</tr>
	<?php if($invoice_type != "Billable - Contract"){ ?>
	<tr>
	<td valign="top">
	<?php if($SESSION_EDIT_SD_MODE==1){?>
	<input type="checkbox" name="travel_taxable" value="1"<?php if($travel_taxable) echo " checked";?> onchange="set_taxable(this, 'travel_taxable')">
	<?php } else { ?>
	<?php if($travel_taxable) echo "<img src='images/green_check.png'>"; ?>
	<?php } ?>
	</td>
	<td valign="top">
	<?php if($SESSION_EDIT_SD_MODE==1){?>
	<span id="travel_label_desc"><a href="javascript:editField('travel_label_desc')"><?=$travel_label_desc?></a></span>
	<?php } else { ?>
	Travel
	<?php } ?>
	</td>
	<td valign="top">
	<?php if($SESSION_EDIT_SD_MODE==1){?>
	<span id="travel_desc"><a href="javascript:editField('travel_desc')"><?=$travel_desc?></a></span>
	<?php } else { ?>
	<?=$travel_desc?>
	<?php } ?>
	</td>
	<td valign="top" align="right">
	<?php if($SESSION_EDIT_SD_MODE==1){?>
	<span id="travel_time"><a href="javascript:editField('travel_time')"><?=$travel_time?></a></span>
	<?php } else { ?>
	<?=number_format($travel_time, 2)?>
	<?php } ?>
	</td>
	<td valign="top">Hrs</td>
	<td valign="top" align="right">
	<?php if($SESSION_EDIT_SD_MODE==1){?>
	$<span id="travel_rate"><a href="javascript:editField('travel_rate')"><?=number_format($travel_rate, 2)?></a></span>
	<?php } else { ?>
	$<?=number_format($travel_rate, 2)?>
	<?php } ?>
	</td>
	<td valign="top" align="right">$<?=$travel_cost?></td>
	<td width="50">&nbsp;</td>
	</tr>
	<?php if($travel_taxable) $taxable_amount += $travel_cost_raw; ?>
	
	
	
	<tr>
	<td valign="top">
	<?php if($SESSION_EDIT_SD_MODE==1){?>
	<input type="checkbox" name="labor_taxable" value="1"<?php if($labor_taxable) echo " checked";?> onchange="set_taxable(this, 'labor_taxable')">
	<?php } else { ?>
	<?php if($labor_taxable) echo "<img src='images/green_check.png'>"; ?>
	<?php } ?>
	</td>
	<td valign="top">Labor</td>
	<td valign="top">
	<?php if($SESSION_EDIT_SD_MODE==1){?>
	<span id="labor_desc"><a href="javascript:editField('labor_desc')"><?=$labor_desc?></a></span>
	<?php } else { ?>
	<?=$labor_desc?>
	<?php } ?>
	</td>
	<td valign="top" align="right">
	<?php if($SESSION_EDIT_SD_MODE==1){?>
	<span id="labor_time"><a href="javascript:editField('labor_time')"><?=$labor_time?></a></span>
	<?php } else { ?>
	<?=number_format($labor_time, 2)?>
	<?php } ?>
	</td>
	<td valign="top">Hrs</td>
	<td valign="top" align="right">
	<?php if($SESSION_EDIT_SD_MODE==1){?>
	$<span id="labor_rate"><a href="javascript:editField('labor_rate')"><?=number_format($labor_rate, 2)?></a></span>
	<?php } else { ?>
	$<?=number_format($labor_rate, 2)?>
	<?php } ?>
	</td>
	<td valign="top" align="right">$<?=$labor_cost?></td>
	<td></td>
	</tr>
	<?php if($labor_taxable) $taxable_amount += $labor_cost_raw; ?>
	<?php
	$materials_cost = 0;
	$labor_plus_travel = $labor_cost + $travel_cost;
	$sql = "SELECT * from am_leakcheck_materials where leak_id='$leak_id'";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $subtotal = $record['quantity'] * $record['cost'];
	  $materials_cost += $subtotal;
	  ?>
	  <tr>
	  <td valign="top">
	  <?php if($SESSION_EDIT_SD_MODE==1){?>
	  <input type="checkbox" name="mat_taxable_<?=$record['id']?>" value="1"<?php if($record['taxable']) echo " checked";?> onchange="set_taxable(this, 'mat_<?=$record['id']?>')">
	  <?php } else { ?>
	  <?php if($record['taxable']) echo "<img src='images/green_check.png'>"; ?>
	  <?php } ?>
	  </td>
	  <td valign="top">Materials</td>
	  <td valign="top">
	  <?php if($SESSION_EDIT_SD_MODE==1){?>
	  <span id="mat_description_<?=$record['id']?>"><a href="javascript:editFieldMat('description', '<?=$record['id']?>')"><?=stripslashes($record['description'])?></a></span>
	  <?php } else { ?>
	  <?=stripslashes($record['description'])?>
	  <?php } ?>
	  </td>
	  <td valign="top" align="right">
	  <?php if($SESSION_EDIT_SD_MODE==1){?>
	  <span id="mat_quantity_<?=$record['id']?>"><a href="javascript:editFieldMat('quantity', '<?=$record['id']?>')"><?=stripslashes($record['quantity'])?></a></span>
	  <?php } else { ?>
	  <?=stripslashes(number_format($record['quantity']))?>
	  <?php } ?>
	  </td>
	  <td valign="top">
	  <?php if($SESSION_EDIT_SD_MODE==1){?>
	  <span id="mat_units_<?=$record['id']?>"><a href="javascript:editFieldMat('units', '<?=$record['id']?>')"><?=stripslashes($record['units'])?></a></span>
	  <?php } else { ?>
	  <?=stripslashes($record['units'])?>
	  <?php } ?>
	  </td>
	  <td valign="top" align="right">
	  <?php if($SESSION_EDIT_SD_MODE==1){?>
	  $<span id="mat_cost_<?=$record['id']?>"><a href="javascript:editFieldMat('cost', '<?=$record['id']?>')"><?=number_format($record['cost'], 2)?></a></span>
	  <?php } else { ?>
	  $<?=number_format($record['cost'], 2)?>
	  <?php } ?>
	  </td>
	  <td valign="top" align="right">$<?=number_format($subtotal, 2)?></td>
	  <td>
	  <?php if($SESSION_EDIT_SD_MODE==1){?>
	  <a href="javascript:invoicedelmat('<?=$record['id']?>')">delete</a>
	  <?php } ?>
	  </td>
	  </tr>
	  <?php if($record['taxable']) $taxable_amount += $subtotal; ?>
	  <?php
	}
	?>
	<?php } // end if invoice type is not contract 
	 else {
	   ?>
	   <tr>
	<td valign="top">
	<?php if($SESSION_EDIT_SD_MODE==1){?>
	  <input type="checkbox" name="contract_taxable" value="1"<?php if($contract_taxable) echo " checked";?> onchange="set_taxable(this, 'contract_taxable')">
	  <?php } else { ?>
	  <?php if($contract_taxable) echo "<img src='images/green_check.png'>"; ?>
	  <?php } ?>
	</td>
	<td valign="top">Contract</td>
	<td valign="top">

	</td>
	<td valign="top" align="right">

	</td>
	<td valign="top"></td>
	<td valign="top" align="right">

	</td>
	<td valign="top" align="right">$<?=number_format($contract_amount, 2)?></td>
	<td></td>
	</tr>
	<?php
	   $labor_cost = 0;
	   $travel_cost = 0;
	   $materials_cost = 0;
	   $labor_cost_raw = $contract_amount;
	   $travel_cost_raw = 0;
	   if($contract_taxable) $taxable_amount += $labor_cost_raw;
	 }?>
	 
	 
	 
	 
	 
	 <?php
	$other_cost = 0;
	$sql = "SELECT * from am_leakcheck_othercost where leak_id='$leak_id'";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $subtotal = $record['cost'] * $record['quantity'];
	  $other_cost += $subtotal;
	  ?>
	  <tr>
	  <td valign="top">
	  <?php if($SESSION_EDIT_SD_MODE==1){?>
	  <input type="checkbox" name="other_taxable_<?=$record['other_id']?>" value="1"<?php if($record['taxable']) echo " checked";?> onchange="set_taxable(this, 'other_<?=$record['other_id']?>')">
	  <?php } else { ?>
	  <?php if($record['taxable']) echo "<img src='images/green_check.png'>"; ?>
	  <?php } ?>
	  </td>
	  <td valign="top">Other</td>
	  <td valign="top">
	  <?php if($SESSION_EDIT_SD_MODE==1){?>
	  <span id="other_description_<?=$record['other_id']?>"><a href="javascript:editFieldOther('description', '<?=$record['other_id']?>')"><?=stripslashes($record['description'])?></a></span>
	  <?php } else { ?>
	  <?=stripslashes($record['description'])?>
	  <?php } ?>
	  </td>
	  <td valign="top" align="right">
	  <?php if($SESSION_EDIT_SD_MODE==1){?>
	  <span id="other_quantity_<?=$record['other_id']?>"><a href="javascript:editFieldOther('quantity', '<?=$record['other_id']?>')"><?=stripslashes($record['quantity'])?></a></span>
	  <?php } else { ?>
	  <?=stripslashes(number_format($record['quantity']))?>
	  <?php } ?>
	  </td>
	  <td valign="top">
	  <?php if($SESSION_EDIT_SD_MODE==1){?>
	  <span id="other_units_<?=$record['other_id']?>"><a href="javascript:editFieldOther('units', '<?=$record['other_id']?>')"><?=stripslashes($record['units'])?></a></span>
	  <?php } else { ?>
	  <?=stripslashes($record['units'])?>
	  <?php } ?>
	  </td>
	  <td valign="top" align="right">
      <?php if($SESSION_EDIT_SD_MODE==1){?>
	  $<span id="other_cost_<?=$record['other_id']?>"><a href="javascript:editFieldOther('cost', '<?=$record['other_id']?>')"><?=number_format($record['cost'], 2)?></a></span>
	  <?php } else { ?>
	  $<?=number_format($record['cost'], 2)?>
	  <?php } ?>
	  </td>
	  <td valign="top" align="right">
	  $<?=number_format($subtotal, 2)?>
	  
	  </td>
	  <td>
	  <?php if($SESSION_EDIT_SD_MODE==1){?>
	  <a href="javascript:invoicedelother('<?=$record['other_id']?>')">delete</a>
	  <?php } ?>
	  </td>
	  </tr>
	  <?php if($record['taxable']) $taxable_amount += $subtotal; ?>
	  <?php
	}
	?>
	 
	 
	 
	 
	 
	 <?php /*
	<?php if($other_cost != 0 || $SESSION_EDIT_SD_MODE==1){ ?>
	<tr>
	<td valign="top">
	<?php if($SESSION_EDIT_SD_MODE==1){?>
	<input type="checkbox" name="other_taxable" value="1"<?php if($other_taxable) echo " checked";?> onchange="set_taxable(this, 'other_taxable')">
	<?php } else { ?>
	<?php if($other_taxable) echo "<img src='images/green_check.png'>"; ?>
	<?php } ?>
	</td>
	<td valign="top">Other</td>
	<td valign="top">
	<?php if($SESSION_EDIT_SD_MODE==1){?>
	<span id="other_desc"><a href="javascript:editField('other_desc')"><?=$other_desc?></a></span>
	<?php } else { ?>
	<?=$other_desc?>
	<?php } ?>
	</td>
	<td valign="top" align="right">
	<?php if($SESSION_EDIT_SD_MODE==1){?>
	<span id="other_quantity"><a href="javascript:editField('other_quantity')"><?=number_format($other_quantity, 2)?></a></span>
	<?php } else { ?>
	<?=number_format($other_quantity, 2)?>
	<?php } ?>
	</td>
	<td valign="top">
	<?php if($SESSION_EDIT_SD_MODE==1){?>
	<span id="other_unit"><a href="javascript:editField('other_unit')"><?=$other_unit?></a></span>
	<?php } else { ?>
	<?=$other_unit?>
	<?php } ?>
	</td>
	<td valign="top"></td>
	<td valign="top" align="right">
	<?php if($SESSION_EDIT_SD_MODE==1){?>
	$<span id="other_cost"><a href="javascript:editField('other_cost')"><?=number_format($other_cost, 2)?></a></span>
	<?php } else { ?>
	$<?=number_format($other_cost, 2)?>
	<?php } ?>
	</td>
	<td></td>
	</tr>
	<?php if($other_taxable) $taxable_amount += $other_cost; ?>
	<?php } // end display of other line ?>
	*/?>
	
	
	</table>
<?php
$html = ob_get_contents();
ob_end_clean();
$html = jsclean($html);
?>


div = document.getElementById('invoice_info_table');
div.innerHTML = '<?php echo $html; ?>';
document.getElementById('labor_cost').value="<?=$labor_cost_raw?>";
document.getElementById('travel_cost').value="<?=$travel_cost_raw?>";
document.getElementById('other_cost_field').value="<?=$other_cost?>";
document.getElementById('materials_cost').value="<?=$materials_cost?>";
document.getElementById('taxable_amount').value="<?=$taxable_amount?>";
newdesc = document.getElementById('desc_work_performed').value;
newdesc = newdesc.replace(/ZZZZ/g, "\n");
document.getElementById('desc_work_performed').value = newdesc;

newbillto = document.getElementById('billto').value;
newbillto = newbillto.replace(/ZZZZ/g, "\n");
document.getElementById('billto').value = newbillto;

CalcInvoice('<?=$dotax?>');
<?php } ?>

<?php if($SESSION_EDIT_SD_MODE==1){ ?>
document.getElementById('invoice_additem').style.display="";
document.getElementById('invoice_addother').style.display="";
document.getElementById('vieweditbutton').value="Switch to View Mode";
<?php } else { ?>
document.getElementById('invoice_additem').style.display="none";
document.getElementById('invoice_addother').style.display="none";
document.getElementById('vieweditbutton').value="Switch to Edit Mode";
<?php } ?>

<?php if($clear_new_mat==1){ ?>
document.getElementById('mat_dropdown').value=0;
document.getElementById('mat_description').value="";
document.getElementById('mat_cost').value="";
document.getElementById('mat_quantity').value="";
document.getElementById('invoice_additem_info').style.display="none";

document.getElementById('other_descriptionx').value="";
document.getElementById('other_costx').value="";
document.getElementById('other_quantityx').value="";
document.getElementById('invoice_addother_info').style.display="none";
<?php } ?>
