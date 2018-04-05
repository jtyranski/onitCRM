<?php include "includes/header_white.php"; ?>
<?php
$sql = "SELECT * from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);


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

<div class="main">
<form action="tool_goals_action.php" method="post">

<br><strong>Goals</strong><br>
<div style="position:relative;">
<div style="float:left; padding-right:10px;">
<table class="main">
<tr>
<td colspan="2">General</td>
</tr>
<tr>
<td align="right">FCS Users</td>
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
<td align="right">Qwikbids</td>
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
