<?php include "includes/header_white.php"; ?>
<?php
$sql = "SELECT ap_salesgoal, ap_increase, ap_contract_percent, ap_contract_dollar, ap_contract_close, ap_contract_meetings, 
ap_contract_contacts, ap_service_percent, ap_service_dollar, ap_service_close, ap_tm, ap_service_meetings, 
ap_service_contacts from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$ap_salesgoal = stripslashes($record['ap_salesgoal']);
$ap_increase = stripslashes($record['ap_increase']);
$ap_contract_percent = stripslashes($record['ap_contract_percent']);
$ap_contract_dollar = stripslashes($record['ap_contract_dollar']);
$ap_contract_close = stripslashes($record['ap_contract_close']);
$ap_contract_meetings = stripslashes($record['ap_contract_meetings']);
$ap_contract_contacts = stripslashes($record['ap_contract_contacts']);
$ap_service_percent = stripslashes($record['ap_service_percent']);
$ap_service_dollar = stripslashes($record['ap_service_dollar']);
$ap_service_close = stripslashes($record['ap_service_close']);
$ap_tm = stripslashes($record['ap_tm']);
$ap_service_meetings = stripslashes($record['ap_service_meetings']);
$ap_service_contacts = stripslashes($record['ap_service_contacts']);

if($ap_salesgoal==0){
  echo "You must first enter your ActionPlan Sales Goal in the admin area before you can view this report";
  exit;
}

$dv = $_GET['dv'];
if($dv=="") $dv = 52;
?>
<a href="tool_actionplan_report.php">View Sales Report</a><br>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="form1">
<div class="main" style="font-size:36px; font-weight:bold;">
<select name="dv" onchange="document.form1.submit()" style="font-size:36px; font-weight:bold;">
<option value="52"<?php if($dv==52) echo " selected";?>>Weekly</option>
<option value="12"<?php if($dv==12) echo " selected";?>>Monthly</option>
<option value="1"<?php if($dv==1) echo " selected";?>>Yearly</option>
</select>
Sales Performance Goals
</div>
</form>
<?php

$yearly_contract_dollar = round($ap_salesgoal * ($ap_contract_percent / 100));
$yearly_contract_number = round($yearly_contract_dollar / $ap_contract_dollar);

$yearly_contract_sold_dollar = round($yearly_contract_dollar / ($ap_contract_close / 100));
$yearly_contract_sold_number = round($yearly_contract_number / ($ap_contract_close / 100));

$yearly_contract_quoted_dollar = round($ap_salesgoal * ($ap_service_percent / 100));
$yearly_contract_quoted_number = round($yearly_contract_quoted_dollar / $ap_service_dollar);

$yearly_service_quoted_dollar = round($yearly_contract_quoted_dollar / ($ap_service_close / 100));
$yearly_service_quoted_number = round($yearly_contract_quoted_number / ($ap_service_close / 100));

$yearly_contacts = round(($yearly_service_quoted_number * $ap_service_contacts) + ($yearly_contract_sold_number * $ap_contract_contacts));
$yearly_meetings = round(($yearly_service_quoted_number * $ap_service_meetings) + ($yearly_contract_sold_number * $ap_contract_meetings));
$yearly_ops = round(($yearly_service_quoted_number + $yearly_contract_sold_number) * ($ap_increase / 100));
$yearly_calls = round($yearly_ops * 10);

$display_contract_sold_number = ceil($yearly_contract_number / $dv);
$display_contract_sold_dollar = ceil($yearly_contract_dollar / $dv);

$display_service_sold_number = ceil($yearly_contract_quoted_number / $dv);
$display_service_sold_dollar = ceil($yearly_contract_quoted_dollar / $dv);

$display_contract_quoted_number = ceil($yearly_contract_sold_number / $dv);
$display_contract_quoted_dollar = ceil($yearly_contract_sold_dollar / $dv);

$display_service_quoted_number = ceil($yearly_service_quoted_number / $dv);
$display_service_quoted_dollar = ceil($yearly_service_quoted_dollar / $dv);

$display_inspections = ceil(((100 - $ap_tm) / 100) * $display_service_quoted_number);

$display_qwikbids = ceil(($ap_tm / 100) * $display_service_sold_number);

$display_contacts = ceil($yearly_contacts / $dv);
$display_meetings = ceil($yearly_meetings / $dv);
$display_ops = ceil($yearly_ops / $dv);
$display_calls = ceil($yearly_calls / $dv);

?>

<table class="main" style="font-size:18px; font-weight:bold;" cellpadding="4">
<tr style="color:#FF0000;">
<td align="right">Prospecting Calls</td>
<td><?=$display_calls?></td>
</tr>
<tr style="color:#FF0000;">
<td align="right">New Opportunities</td>
<td><?=$display_ops?></td>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr style="color:#FF0000;">
<td align="right">Meetings</td>
<td><?=$display_meetings?></td>
</tr>
<tr style="color:#FF0000;">
<td align="right">Contacts</td>
<td><?=$display_contacts?></td>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr style="color:#FF0000;">
<td align="right">Qwikbids</td>
<td><?=$display_qwikbids?></td>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr style="color:#FF0000;">
<td align="right">Inspections</td>
<td><?=$display_inspections?></td>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr style="color:#FF6600;">
<td align="right">Service Quoted</td>
<td>$<?=number_format($display_service_quoted_dollar, 0)?></td>
<td><?=$display_service_quoted_number?></td>
</tr>
<tr style="color:#FF6600;">
<td align="right">Contract Quoted</td>
<td>$<?=number_format($display_contract_quoted_dollar, 0)?></td>
<td><?=$display_contract_quoted_number?></td>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr style="color:#009900;">
<td align="right">Service Sold</td>
<td>$<?=number_format($display_service_sold_dollar, 0)?></td>
<td><?=$display_service_sold_number?></td>
</tr>
<tr style="color:#009900;">
<td align="right">Contract Sold</td>
<td>$<?=number_format($display_contract_sold_dollar, 0)?></td>
<td><?=$display_contract_sold_number?></td>
</tr>

</table>
