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
?>

<strong><?=$MAIN_CO_NAME?> Action Plan</strong>
<br><br>

<form action="admin_actionplan_action.php" method="post">

<table class="main">
<tr>
<td>$</td>
<td><input type="text" name="ap_salesgoal" value="<?=$ap_salesgoal?>"></td>
<td>What is your sales goal for the current sales year?</td>
</tr>
<tr>
<td></td>
<td><input type="text" name="ap_increase" value="<?=$ap_increase?>">%</td>
<td>How much of an increase is that from last year?
</td>
</tr>
<tr>
<td></td>
<td><input type="text" name="ap_contract_percent" value="<?=$ap_contract_percent?>">%</td>
<td>What percentage of that goal will be contract work?
</td>
</tr>
<tr>
<td>$</td>
<td><input type="text" name="ap_contract_dollar" value="<?=$ap_contract_dollar?>"></td>
<td>What is the average dollar value of your contract work?
</td>
</tr>
<tr>
<td></td>
<td><input type="text" name="ap_contract_close" value="<?=$ap_contract_close?>">%</td>
<td>What is your close rate for contract work?
</td>
</tr>
<tr>
<td></td>
<td><input type="text" name="ap_contract_meetings" value="<?=$ap_contract_meetings?>"></td>
<td>How many meetings does it take to secure an average contract?
</td>
</tr>
<tr>
<td></td>
<td><input type="text" name="ap_contract_contacts" value="<?=$ap_contract_contacts?>"></td>
<td>How many contacts does it take to secure an average contract?
</td>
</tr>
<tr>
<td></td>
<td><input type="text" name="ap_service_percent" value="<?=$ap_service_percent?>">%</td>
<td>What percentage of that goal will be service work?
</td>
</tr>
<tr>
<td>$</td>
<td><input type="text" name="ap_service_dollar" value="<?=$ap_service_dollar?>"></td>
<td>What is the average dollar value of your service work?
</td>
</tr>
<tr>
<td></td>
<td><input type="text" name="ap_service_close" value="<?=$ap_service_close?>">%</td>
<td>What is your close rate for service work?
</td>
</tr>
<tr>
<td></td>
<td><input type="text" name="ap_tm" value="<?=$ap_tm?>">%</td>
<td>How much of that is T&M work?
</td>
</tr>
<tr>
<td></td>
<td><input type="text" name="ap_service_meetings" value="<?=$ap_service_meetings?>"></td>
<td>How many meetings does it take to secure an average service order? 
</td>
</tr>
<tr>
<td></td>
<td><input type="text" name="ap_service_contacts" value="<?=$ap_service_contacts?>"></td>
<td>How many contacts does it take to secure an average service order? 
</td>
</tr>
</table>
<input type="submit" name="submit1" value="Update">
</form>
