<?php
include "includes/functions.php";

$sql = "SELECT leak_id from am_leakcheck";
$r = executequery($sql);
while($rec = go_fetch_array($r)){
  $leak_id = $rec['leak_id'];
  $sql = "SELECT dispatch_date, eta_date, inprogress_date, fix_date, confirm_date, invoice_date, closed_date from am_leakcheck where leak_id='$leak_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $last_update = max($record);
  print_r($record);
  echo "<br>" . $last_update . "<br><br>";
}
?>