<?php
include "includes/functions.php";

$sql = "SELECT leak_id, payment from am_leakcheck where payment != 0";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $leak_id = $record['leak_id'];
  $payment = $record['payment'];
  
  $sql = "INSERT into am_leakcheck_payments(leak_id, pay_date, type, amount) values('$leak_id', now(), 'Auto', '$payment')";
  executeupdate($sql);
}
?>