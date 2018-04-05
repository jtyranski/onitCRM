<?php include "includes/header.php"; ?>
<?php  // this is just for fcs login ?>
<div align="center">
  <div class="whiteround" style="height:800px;">
  <div align="left">
<?php



$master_id = $_GET['master_id'];

$sql = "SELECT master_name from master_list where master_id='$master_id'";
$master_name = stripslashes(getsingleresult($sql));

$tools = array();
$sql = "SELECT tool_master_id from toolbox_items where master_id='$master_id'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $tools[] = $record['tool_master_id'];
}

$activities = array();
$sql = "SELECT activity_master_id from activities_items where master_id='$master_id'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $activities[] = $record['activity_master_id'];
}

$disciplines = array();
$sql = "SELECT dis_id from discipline_to_master where master_id='$master_id'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $disciplines[] = $record['dis_id'];
}

$sql = "SELECT *, opportunities, multilogo, quickbid, demo, use_resources, invoice_type, use_ops, residential, use_groups, use_cron_sd_export from master_list where master_id='$master_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$opportunities = $record['opportunities'];
$multilogo = $record['multilogo'];
$quickbid = $record['quickbid'];
$demo = $record['demo'];
$use_resources = $record['use_resources'];
$invoice_type = $record['invoice_type'];
$use_ops = $record['use_ops'];
$residential = $record['residential'];
$use_groups = $record['use_groups'];
$use_cron_sd_export = $record['use_cron_sd_export'];

$goal_fcs_users = $record['goal_fcs_users'];
$goal_companies = $record['goal_companies'];
$goal_properties = $record['goal_properties'];
$goal_contacts = $record['goal_contacts'];
$goal_meetings = $record['goal_meetings'];
$goal_inspections = $record['goal_inspections'];
$goal_dispatches = $record['goal_dispatches'];
$goal_quoted = $record['goal_quoted'];
$goal_sold = $record['goal_sold'];
$goal_quickbid = $record['goal_quickbid'];

?>

<form action="fcs_contractors_tools_action.php" method="post">
<input type="hidden" name="master_id" value="<?=$master_id?>">
<div style="width:100%; position:relative;">
<div style="float:left; width:50%;">
Tools for <?=$master_name?><br>
<?php
$sql = "SELECT * from toolbox_master";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <input type="checkbox" name="tool_id[]" value="<?=$record['tool_master_id']?>"<?php if(in_array($record['tool_master_id'], $tools)) echo " checked";?>>
  <?=stripslashes($record['name'])?>
  <?php if($record['fcs_only']==1) echo " (should be for $MAIN_CO_NAME Only)";?>
  <br>
  <?php
}
?>
<br><br>
<a href="master_import.php?master_id=<?=$master_id?>">Import company/property info for this core company</a><br>
<a href="master_import_error.php?master_id=<?=$master_id?>">View Property Import Errors for this core company</a>
<br>
<a href="master_import_error_contact.php?master_id=<?=$master_id?>">View Contact Import Errors for this core company</a>
</div>

<div style="float:left; width:50%;">
<input type="checkbox" name="demo" value="1"<?php if($demo) echo " checked";?>>Demo<br><br><br>
Invoice Type:
<select name="invoice_type">
<option value="1"<?php if($invoice_type==1) echo " selected";?>>Classic</option>
<option value="2"<?php if($invoice_type==2) echo " selected";?>>New</option>
</select><br><br><br>
<input type="checkbox" name="opportunities" value="1"<?php if($opportunities) echo " checked";?>>Opportunities<br>
<input type="checkbox" name="multilogo" value="1"<?php if($multilogo) echo " checked";?>>Can Use Alternate Logo<br>
<input type="checkbox" name="quickbid" value="1"<?php if($quickbid) echo " checked";?>>Can Use Quick Bid<br>
<input type="checkbox" name="use_resources" value="1"<?php if($use_resources) echo " checked";?>>Can Use Resources<br>
<input type="checkbox" name="use_ops" value="1"<?php if($use_ops) echo " checked";?>>Can Use Ops<br>
<input type="checkbox" name="residential" value="1"<?php if($residential) echo " checked";?>>Can Use Residential<br>
<input type="checkbox" name="use_groups" value="1"<?php if($use_groups) echo " checked";?>>Enterprise Core (groups and subgroups)<br>
<input type="checkbox" name="use_cron_sd_export" value="1"<?php if($use_cron_sd_export) echo " checked";?>>Nightly Service Dispatch Export<br>

<br>
Activities for <?=$master_name?><br>
<?php
$sql = "SELECT * from activities_master";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <input type="checkbox" name="activity_id[]" value="<?=$record['activity_master_id']?>"<?php if(in_array($record['activity_master_id'], $activities)) echo " checked";?>>
  <?=stripslashes($record['activity_name'])?><br>
  <?php
}
?>

<br><br>
Disciplines<br>
<input type="checkbox" disabled="disabled" checked="checked">Roofing (everyone)<br>
<?php
$sql = "SELECT dis_id, discipline from disciplines where dis_id != 1 order by dis_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <input type="checkbox" name="discipline[]" value="<?=$record['dis_id']?>"<?php if(in_array($record['dis_id'], $disciplines)) echo " checked";?>><?=stripslashes($record['discipline'])?><br>
  <?php
}
?>
</div>

</div>
<div style="clear:both;"></div>
<br><strong>Goals</strong><br>
<div style="position:relative;">
<div style="float:left; padding-right:10px;">
<table class="main">
<tr>
<td colspan="2">General</td>
</tr>
<tr>
<td align="right"><?=$MAIN_CO_NAME?> Users</td>
<td><input type="text" name="goal_fcs_users" value="<?=$goal_fcs_users?>" size="10"></td>
</tr>
<tr>
<td align="right">Companies</td>
<td><input type="text" name="goal_companies" value="<?=$goal_companies?>" size="10"></td>
</tr>
<tr>
<td align="right">Properties</td>
<td><input type="text" name="goal_properties" value="<?=$goal_properties?>" size="10"></td>
</tr>
</table>
</div>

<div style="float:left; padding-right:10px;">
<table class="main">
<tr>
<td colspan="2">Activities</td>
</tr>
<tr>
<td align="right">Contacts</td>
<td><input type="text" name="goal_contacts" value="<?=$goal_contacts?>" size="10"></td>
</tr>
<tr>
<td align="right">Meetings</td>
<td><input type="text" name="goal_meetings" value="<?=$goal_meetings?>" size="10"></td>
</tr>
</table>
</div>

<div style="float:left; padding-right:10px;">
<table class="main">
<tr>
<td colspan="2">Service</td>
</tr>
<tr>
<td align="right">Inspections</td>
<td><input type="text" name="goal_inspections" value="<?=$goal_inspections?>" size="10"></td>
</tr>
<tr>
<td align="right">Dispatches</td>
<td><input type="text" name="goal_dispatches" value="<?=$goal_dispatches?>" size="10"></td>
</tr>
<tr>
<td align="right">Qwikbid</td>
<td><input type="text" name="goal_quickbid" value="<?=$goal_quickbid?>" size="10"></td>
</tr>
</table>
</div>

<div style="float:left; padding-right:10px;">
<table class="main">
<tr>
<td colspan="2">Sales</td>
</tr>
<tr>
<td align="right"># Quoted</td>
<td><input type="text" name="goal_quoted" value="<?=$goal_quoted?>" size="10"></td>
</tr>
<tr>
<td align="right"># Sold</td>
<td><input type="text" name="goal_sold" value="<?=$goal_sold?>" size="10"></td>
</tr>
</table>
</div>
</div>
<div style="clear:both;"></div>

<br>
<input type="submit" name="submit1" value="Update">
</form>
</div>
</div>
</div>
<?php include "includes/footer.php"; ?>