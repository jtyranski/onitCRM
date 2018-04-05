<?php
exit;
include "includes/functions.php";

$leak_id=230;
$mat_sub = 0;
	  $materials = "";
      $sql = "SELECT * from am_leakcheck_materials where leak_id='$leak_id'";
      $result = executequery($sql);
      while($record = go_fetch_array($result)){
       $qty = $record['quantity'];
       $cost = $record['cost'];
       $line = $qty * $cost;
       $mat_sub += $line;
	   $materials .= stripslashes($record['description']) . " (" . $qty . ") @ $" . number_format($cost, 2) . " " . $record['units'] . " = " . number_format($line, 2) . "\n";
      }
	  $materials = go_escape_string($materials);
echo $materials;
$sql = "UPDATE am_leakcheck set extra_cost='$mat_sub', materials=\"$materials\", invoice_total=invoice_total + $mat_sub where leak_id='$leak_id'";
echo "<br>" . $sql;

?>