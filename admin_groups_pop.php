<?php
include "includes/functions.php";

$action = go_escape_string($_GET['action']);
$group_id = go_escape_string($_GET['group_id']);
$subgroup_id = go_escape_string($_GET['subgroup_id']);

$groups_only_query = " 1=1 ";
if($SESSION_GROUPS != "" || $SESSION_SUBGROUPS != ""){
  $test = $SESSION_GROUPS . $SESSION_SUBGROUPS;
  $test_array = explode(",", $test);
  for($x=0;$x<sizeof($test_array);$x++){
    if($test_array[$x]=="") continue;
	$in_groups .= $test_array[$x] . ",";
  }
  $in_groups = go_reg_replace("\,$", "", $in_groups);
  if($in_groups=="") $in_groups = 0;
  $groups_only_query = " id in($in_groups) ";
}

if($action=="grouplist"){
  $sql = "SELECT id, group_name from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of=0 and $groups_only_query order by group_name";
  $result = executequery($sql);
  ob_start();
  $counter = 0;
  while($record = go_fetch_array($result)){
    $counter++;
	?>
	<div id="group_<?=$counter?>" onMouseOver="this.style.cursor='pointer'" onClick="go_pop('groupinfo', '<?=$record['id']?>', '0', '<?=$counter?>')" class="grouplist_class">
	<?=stripslashes($record['group_name'])?>
	</div>
	<?php
  }
  $html = ob_get_contents();
  ob_end_clean();
  
  $html = jsclean($html);
  ?>
  document.getElementById('grouplist').innerHTML = '<?=$html?>';
  <?php
}

if($action=="groupinfo"){
  $sql = "SELECT group_name from groups where id=\"$group_id\"";
  $name = stripslashes(getsingleresult($sql));
  $name = go_reg_replace("\"", "DOUBLEQUOTE", $name);
  ob_start();
  ?>
  <div style="width:100%; position:relative; height:25px;">
  <div style="float:left;">
  <strong>Group Name:</strong>
  <input type="text" name="edit_group_name" id="edit_group_name" value="<?=$name?>">
  </div>
  <div style="float:right;">
  <a href="javascript:go_form('edit_group')">submit</a>
  </div>
  </div>
  <div style="clear:both;"></div>
  <?php
  $html = ob_get_contents();
  ob_end_clean();
  $html = jsclean($html);
  ?>
  document.getElementById('group_id').value='<?=$group_id?>';
  document.getElementById('groupinfo').innerHTML = '<?=$html?>';
  
  newdesc = document.getElementById('edit_group_name').value;
  newdesc = newdesc.replace(/DOUBLEQUOTE/g, "\"");
  document.getElementById('edit_group_name').value = newdesc;
  
  <?php
  $warning = "";
  $sql = "SELECT count(*) from users where master_id='" . $SESSION_MASTER_ID . "' and groups like '%,$group_id,%'";
  $test = getsingleresult($sql);
  if($test) $warning .= "users, ";
  
  $sql = "SELECT count(*) from prospects where master_id='" . $SESSION_MASTER_ID . "' and groups like '%,$group_id,%'";
  $test = getsingleresult($sql);
  if($test) $warning .= "companies, ";
  
  $sql = "SELECT count(*) from properties where groups = '$group_id'";
  $test = getsingleresult($sql);
  if($test) $warning .= "properties, ";
  
  $sql = "SELECT count(*) from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of = '$group_id'";
  $test = getsingleresult($sql);
  if($test) $warning .= "sub groups";
  
  $warning = go_reg_replace(", $", "", $warning);
  
  ob_start();
  ?>
  <div style="width:100%; height:200px; overflow:auto; border:2px solid black;">
  <input type='checkbox' onchange="SetChecked(this, 'group_members[]')">All<br>
  <?php
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname, groups from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER order by lastname";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    ?>
	<input type="checkbox" name="group_members[]" value="<?=$record['user_id']?>"<?php if(go_reg("," . $group_id . ",", $record['groups'])) echo " checked";?>><?=stripslashes($record['fullname'])?><br>
	<?php
  }
  ?>
  </div>
  <div style="width:100%; position:relative;">
  <div style="float:left;"><a href="javascript:delgroup('<?=$warning?>', 'delgroup')">remove group</a></div>
  <div style="float:right;">
  <a href="javascript:go_form('edit_group')">submit</a>
  </div>
  </div>
  <div style="clear:both;"></div>
  <?php
  $html = ob_get_contents();
  ob_end_clean();
  $html = jsclean($html);
  ?>
  document.getElementById('group_memberlist').innerHTML = '<?=$html?>';
  <?php
  
  $sql = "SELECT id, group_name from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of='$group_id' and $groups_only_query order by group_name";
  $result = executequery($sql);
  ob_start();
  $counter = 0;
  while($record = go_fetch_array($result)){
    $counter++;
	?>
	<div id="subgroup_<?=$counter?>" onMouseOver="this.style.cursor='pointer'" onClick="go_pop('subgroupinfo', '<?=$record['id']?>', '<?=$group_id?>', '<?=$counter?>')" class="subgrouplist_class">
	<?=stripslashes($record['group_name'])?>
	</div>
	<?php
  }
  $html = ob_get_contents();
  ob_end_clean();
  
  $html = jsclean($html);
  ?>
  document.getElementById('subgroup_main').style.display="";
  document.getElementById('subgrouplist').innerHTML = '<?=$html?>';
  <?php
  $action = "groupcompany";
}


if($action=="subgroupinfo"){
  $sql = "SELECT group_name from groups where id=\"$group_id\"";
  $name = stripslashes(getsingleresult($sql));
  $name = go_reg_replace("\"", "DOUBLEQUOTE", $name);
  ob_start();
  ?>
  <div style="width:100%; position:relative; height:25px;">
  <div style="float:left;">
  <strong>Sub Group Name:</strong>
  <input type="text" name="edit_subgroup_name" id="edit_subgroup_name" value="<?=$name?>">
  </div>
  <div style="float:right;">
  <a href="javascript:go_form('edit_group')">submit</a>
  </div>
  </div>
  <div style="clear:both;"></div>
  <?php
  $html = ob_get_contents();
  ob_end_clean();
  $html = jsclean($html);
  ?>
  document.getElementById('subgroup_id').value='<?=$group_id?>';
  document.getElementById('subgroupinfo').innerHTML = '<?=$html?>';
  
  newdesc = document.getElementById('edit_subgroup_name').value;
  newdesc = newdesc.replace(/DOUBLEQUOTE/g, "\"");
  document.getElementById('edit_subgroup_name').value = newdesc;
  
  <?php
  $warning = "";
  $sql = "SELECT count(*) from users where master_id='" . $SESSION_MASTER_ID . "' and subgroups like '%,$group_id,%'";
  $test = getsingleresult($sql);
  if($test) $warning .= "users, ";
  
  $sql = "SELECT count(*) from prospects where master_id='" . $SESSION_MASTER_ID . "' and subgroups like '%,$group_id,%'";
  $test = getsingleresult($sql);
  if($test) $warning .= "companies, ";
  
  $sql = "SELECT count(*) from properties where subgroups = '$group_id'";
  $test = getsingleresult($sql);
  if($test) $warning .= "properties, ";
  
  $sql = "SELECT count(*) from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of = '$group_id'";
  $test = getsingleresult($sql);
  if($test) $warning .= "sub groups";
  
  $warning = go_reg_replace(", $", "", $warning);
  
  ob_start();
  ?>
  <div style="width:100%; height:200px; overflow:auto; border:2px solid black;">
  <input type='checkbox' onchange="SetChecked(this, 'subgroup_members[]')">All<br>
  <?php
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname, subgroups from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER order by lastname";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    ?>
	<input type="checkbox" name="subgroup_members[]" value="<?=$record['user_id']?>"<?php if(go_reg("," . $group_id . ",", $record['subgroups'])) echo " checked";?>><?=stripslashes($record['fullname'])?><br>
	<?php
  }
  ?>
  </div>
  <div style="width:100%; position:relative;">
  <div style="float:left;"><a href="javascript:delgroup('<?=$warning?>', 'delsubgroup')">remove group</a></div>
  <div style="float:right;">
  <a href="javascript:go_form('edit_group')">submit</a>
  </div>
  </div>
  <div style="clear:both;"></div>
  <?php
  $html = ob_get_contents();
  ob_end_clean();
  $html = jsclean($html);
  ?>
  document.getElementById('subgroup_memberlist').innerHTML = '<?=$html?>';
  <?php
  $action = "subgroupcompany";
}

if($action=="groupcompany" || $action == "subgroupcompany"){
  $sql = "SELECT * from groups where id='$group_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $group_name = stripslashes($record['group_name']);
  $master_name = stripslashes($record['master_name']);
  $logo = stripslashes($record['logo']);
  $logo2 = stripslashes($record['logo2']);
  $logo_report = stripslashes($record['logo_report']);
  $dispatch_from_email = stripslashes($record['dispatch_from_email']);
  $address = stripslashes($record['address']);
  $city = stripslashes($record['city']);
  $state = stripslashes($record['state']);
  $zip = stripslashes($record['zip']);
  $invoice_user = stripslashes($record['invoice_user']);
  $phone = stripslashes($record['phone']);
  $fax = stripslashes($record['fax']);
  $website = stripslashes($record['website']);
  $emergency_time_frame = stripslashes($record['emergency_time_frame']);
  $urgent_time_frame = stripslashes($record['urgent_time_frame']);
  $scheduled_time_frame = stripslashes($record['scheduled_time_frame']);
  $productionmeeting_user = stripslashes($record['productionmeeting_user']);
  $priority1 = stripslashes($record['priority1']);
  $priority2 = stripslashes($record['priority2']);
  $priority3 = stripslashes($record['priority3']);
  $priority1_rate = stripslashes($record['priority1_rate']);
  $priority2_rate = stripslashes($record['priority2_rate']);
  $priority3_rate = stripslashes($record['priority3_rate']);
  $custom_sd_field = stripslashes($record['custom_sd_field']);
  $custom_sd_field2 = stripslashes($record['custom_sd_field2']);
  $xml_sd_export = stripslashes($record['xml_sd_export']);
  $from_email = stripslashes($record['from_email']);
  $ar_account = stripslashes($record['ar_account']);
  $sales_account = stripslashes($record['sales_account']);
  $logo_map = stripslashes($record['logo_map']);
  $checks_payable_to = stripslashes($record['checks_payable_to']);
  $company_code = stripslashes($record['company_code']);
  $acct_rec_code = stripslashes($record['acct_rec_code']);
  $general_ledger_acct = stripslashes($record['general_ledger_acct']);
  $sales_tax_acct = stripslashes($record['sales_tax_acct']);
  $timezone = stripslashes($record['timezone']);
  
  
  $checks_payable_to = go_reg_replace("\n", "NEWLINE", $checks_payable_to);
  
  
  $filler = "Edit company information for ";
  if($action=="groupcompany") $filler .= "group: ";
  if($action=="subgroupcompany") $filler .= "subgroup: ";
  $filler .= $group_name;
  ob_start();
  
  ?>
  <?=$filler?> <input type="button" name="buttoncompanyedit" value="Update Info" onclick="go_form('companyinfo')"><br>
  <input type="hidden" name="company_info_group_id" value="<?=$group_id?>">
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


</table>

</div>
<div style="width:50%; float:left;">
Make checks payable to:<br>
<textarea name="checks_payable_to" rows="3" cols="50" id="checks_payable_to"><?=$checks_payable_to?></textarea>
<br>
<table class="main">
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

<tr>
<td>Custom Invoice Field</td>
<td><input type="text" name="custom_sd_field" value="<?=$custom_sd_field?>" id="custom_sd_field" maxlength="25" size="20"></td>
</tr>
<tr>
<td>Custom Invoice Field 2</td>
<td><input type="text" name="custom_sd_field2" value="<?=$custom_sd_field2?>" id="custom_sd_field2" maxlength="25" size="20"></td>
</tr>
<tr>
<td>Dispatch Report Export </td>
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
  <td>General Ledger Account</td>
  <td><input type="text" name="general_ledger_acct" value="<?=$general_ledger_acct?>"></td>
  </tr>
  <tr>
  <td>Sales Tax Payable Account</td>
  <td><input type="text" name="sales_tax_acct" value="<?=$sales_tax_acct?>"></td>
  </tr>
  </table>
</td>
</tr>

<tr>
<td>Send All Core Emails From:
<a alt="" onMouseOver="return overlib('If this is filled out, all emails sent through the system will appear to be sent from this address.  Leave this blank if you want emails to be sent from individual logged in users.')"; onMouseOut="nd();"><img src="images/question.png" border="0"></a></td>
<td><input type="text" name="from_email" value="<?=$from_email?>"></td>
</tr>
</table>
</div>
</div>
<div style="clear:both;"></div>
<?php
  $html = ob_get_contents();
  ob_end_clean();
  
  $html = jsclean($html);
  ?>
  document.getElementById('group_company_info').innerHTML = '<?=$html?>';
  newdesc = document.getElementById('checks_payable_to').value;
  newdesc = newdesc.replace(/NEWLINE/g, "\n");
  document.getElementById('checks_payable_to').value = newdesc;
  
  force_custom(document.form1.xml_sd_export);
  <?php
}
?>
