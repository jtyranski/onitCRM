<?php
include "includes/functions.php";

$leak_id = go_escape_string($_GET['leak_id']);
$pay_id = go_escape_string($_GET['pay_id']);
$action = go_escape_string($_GET['action']);
$datepretty = go_escape_string($_GET['datepretty']);
$amount = go_escape_string($_GET['amount']);
$type = go_escape_string($_GET['type']);
$details = go_escape_string($_GET['details']);

if($action=="add"){
  $date_parts = explode("/", $datepretty);
  $pay_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
  
  $amount = go_reg_replace("\$", "", $amount);
  $amount = go_reg_replace("\,", "", $amount);
  
  $sql = "INSERT into am_leakcheck_payments(leak_id, pay_date, amount, type, details) values(
  \"$leak_id\", \"$pay_date\", \"$amount\", \"$type\", \"$details\")";
  executeupdate($sql);
  
  $sql = "SELECT sum(amount) from am_leakcheck_payments where leak_id='$leak_id'";
  $payment = getsingleresult($sql);
  $sql = "UPDATE am_leakcheck set payment=\"$payment\" where leak_id='$leak_id'";
  executeupdate($sql);
  
  $action = "view";
}

if($action == "delete"){
  $sql = "DELETE from am_leakcheck_payments where leak_id='$leak_id' and pay_id=\"$pay_id\"";
  executeupdate($sql);
  $sql = "SELECT sum(amount) from am_leakcheck_payments where leak_id='$leak_id'";
  $payment = getsingleresult($sql);
  $sql = "UPDATE am_leakcheck set payment=\"$payment\" where leak_id='$leak_id'";
  executeupdate($sql);
  
  $action = "view";
}

if($action=="edit"){
  $sql = "SELECT pay_id, date_format(pay_date, \"%m/%d/%Y\") as datepretty, amount, type, details from am_leakcheck_payments where leak_id='$leak_id' and pay_id=\"$pay_id\"";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  ob_start();
  switch($record['type']){
    case "Cash":{
	  $filler = "Details";
	  break;
	}
	case "CC":{
	  $filler = "Last 4";
	  break;
	}
	case "Check":{
	  $filler = "Check #";
	  break;
	}
  }
	
  ?>
  <table class="main">
	<tr>
	<td width="200">
	Date
	<input type="text" name="edit_payment_datepretty" id="edit_payment_datepretty" size="12" value="<?=$record['datepretty']?>">
	<img src="images/calendar.gif" onClick="KW_doCalendar('edit_payment_datepretty',0)" align="absmiddle">
	</td>
	<td width="200">
	Amount: $<input type="text" name="edit_payment_amount" id="edit_payment_amount" size="10" value="<?=stripslashes($record['amount'])?>">
	</td>
	<td width="200">
	Type:
	<select name="edit_payment_type" id="edit_payment_type" onchange="add_payment_type_change(this, 'edit')">
	<option value="Cash"<?php if($record['type']=="Cash") echo " selected";?>>Cash</option>
	<option value="CC"<?php if($record['type']=="CC") echo " selected";?>>CC</option>
	<option value="Check"<?php if($record['type']=="Check") echo " selected";?>>Check</option>
	</select>
	<td>
	<td width="200">
	<span id="edit_payment_filler"><?=$filler?></span>
	<input type="text" name="edit_payment_details" id="edit_payment_details" size="10" maxlength="20" value="<?=stripslashes($record['details'])?>">
	</td>
	<td width="200">
	<a href="javascript:edit_payment('<?=$record['pay_id']?>')">Edit</a>
	</td>
    </tr>
	</table>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	$html = jsclean($html);
  ?>
  document.getElementById('payment_history').innerHTML = '<?=$html?>';
  <?php
}

if($action=="editupdate"){
  $date_parts = explode("/", $datepretty);
  $pay_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
  
  $amount = go_reg_replace("\$", "", $amount);
  $amount = go_reg_replace("\,", "", $amount);
  
  $sql = "UPDATE am_leakcheck_payments set pay_date=\"$pay_date\", amount=\"$amount\", type=\"$type\", details=\"$details\" where leak_id=\"$leak_id\" and pay_id=\"$pay_id\"";
  executeupdate($sql);
  
  $sql = "SELECT sum(amount) from am_leakcheck_payments where leak_id='$leak_id'";
  $payment = getsingleresult($sql);
  $sql = "UPDATE am_leakcheck set payment=\"$payment\" where leak_id='$leak_id'";
  executeupdate($sql);
  
  $action = "view";
}
	
if($action=="view"){
  $sql = "SELECT pay_id, date_format(pay_date, \"%m/%d/%Y\") as datepretty, amount, type, details from am_leakcheck_payments where leak_id='$leak_id' order by pay_date";
  $result = executequery($sql);
  ob_start();
  ?>
  <table class="main">
  <?php
  while($record = go_fetch_array($result)){
    ?>
	<tr>
	<td width="200"><?=stripslashes($record['datepretty'])?></td>
	<td width="200">$<?=number_format($record['amount'], 2)?></td>
	<td width="200"><?=$record['type']?></td>
	<td width="200"><?=stripslashes($record['details'])?></td>
	<td width="200">
	<?php if($record['type'] != "Auto"){ ?>
	<a href="javascript:add_payment_delete_edit('<?=$record['pay_id']?>', 'edit')">edit</a> &nbsp;
	<a href="javascript:add_payment_delete_edit('<?=$record['pay_id']?>', 'delete')">delete</a>
	<?php } ?>
	</tr>
	<?php
  }
  ?>
  </table>
  <?php
  $html = ob_get_contents();
  ob_end_clean();
  
  $html = jsclean($html);
  
  $sql = "SELECT payment from am_leakcheck where leak_id='$leak_id'";
  $payment = getsingleresult($sql);
  ?>
  document.getElementById('payment_history').innerHTML = '<?=$html?>';
  document.getElementById('payment_box').value = '<?=$payment?>';
  <?php
}
?>
