<?php include "includes/functions.php"; ?>
<?php $prospect_id = $_GET['prospect_id']; ?>
<?php
$sql = "SELECT nte_amount, labor_rate, labor_rate2, labor_rate3, hours_of_operation, payment_terms, invoice_requirements, checkin_procedure, checkout_procedure, contractdoc
from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$nte_amount = stripslashes($record['nte_amount']);
$labor_rate = stripslashes($record['labor_rate']);
$labor_rate2 = stripslashes($record['labor_rate2']);
$labor_rate3 = stripslashes($record['labor_rate3']);
$hours_of_operation = stripslashes($record['hours_of_operation']);
$payment_terms = stripslashes($record['payment_terms']);
$invoice_requirements = stripslashes($record['invoice_requirements']);
$checkin_procedure = stripslashes($record['checkin_procedure']);
$checkout_procedure = stripslashes($record['checkout_procedure']);
$contractdoc = stripslashes($record['contractdoc']);

$sql = "SELECT priority1, priority2, priority3 from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$priority1 = stripslashes($record['priority1']);
$priority2 = stripslashes($record['priority2']);
$priority3 = stripslashes($record['priority3']);
?>
<html>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">

<div class="main">

<form action="frame_prospect_contract_action.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
<input type="submit" name="submit1" value="Update">
<input type="hidden" name="old_nte_amount" value="<?=$nte_amount?>">
<input type="hidden" name="old_labor_rate" value="<?=number_format($labor_rate, 2)?>">
<input type="hidden" name="old_hours_of_operation" value="<?=$hours_of_operation?>">
<input type="hidden" name="old_payment_terms" value="<?=$payment_terms?>">
<input type="hidden" name="old_invoice_requirements" value="<?=$invoice_requirements?>">
<input type="hidden" name="old_checkin_procedure" value="<?=$checkin_procedure?>">
<input type="hidden" name="old_checkout_procedure" value="<?=$checkout_procedure?>">

<div style="width:100%; position:relative;">
<div style="width:50%; float:left;">
<table class="main">
<tr>
<td align="right">Not to Exceed:</td>
<td>$<input type="text" name="nte_amount" value="<?=$nte_amount?>" size="15"></td>
</tr>
<tr>
<td align="right"><?=$priority1?> Labor Rate:</td>
<td>$<input type="text" name="labor_rate" value="<?=number_format($labor_rate, 2)?>" size="15">/hr</td>
</tr>
<tr>
<td align="right"><?=$priority2?> Labor Rate:</td>
<td>$<input type="text" name="labor_rate2" value="<?=number_format($labor_rate2, 2)?>" size="15">/hr</td>
</tr>
<tr>
<td align="right"><?=$priority3?> Labor Rate:</td>
<td>$<input type="text" name="labor_rate3" value="<?=number_format($labor_rate3, 2)?>" size="15">/hr</td>
</tr>
</table>
Contract Doc: <input type="file" name="contractdoc"><br>
<?php if($contractdoc != ""){ ?>
<a href="uploaded_files/contracts/<?=$contractdoc?>" target="_blank">Contract Document</a>
<?php if($SESSION_ISADMIN || $SESSION_USER_LEVEL == "Manager"){ ?>
<a href="frame_prospect_contract_delete.php?prospect_id=<?=$prospect_id?>">[delete]</a>
<?php } ?>
<br>
<?php } ?>
Hours of Operation:<br>
<textarea name="hours_of_operation" rows="5" cols="70"><?=$hours_of_operation?></textarea>
<br><br>
Payment Terms:<br>
<textarea name="payment_terms" rows="5" cols="70"><?=$payment_terms?></textarea>
</div>
<div style="width:50%; float:left;">
Invoice Requirements:<br>
<textarea name="invoice_requirements" rows="5" cols="70"><?=$invoice_requirements?></textarea>
<br><br>
Check In Procedure:<br>
<textarea name="checkin_procedure" rows="5" cols="70"><?=$checkin_procedure?></textarea>
<br><br>
Check Out Procedure:<br>
<textarea name="checkout_procedure" rows="5" cols="70"><?=$checkout_procedure?></textarea>
</div>
</div>
<div style="clear:both;"></div>
</form>