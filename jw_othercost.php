<?php
exit;
include "includes/functions.php";

$sql = "SELECT leak_id, other_cost, other_desc, other_quantity, other_unit, other_taxable from am_leakcheck where other_cost != 0";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $leak_id = go_escape_string(stripslashes($record['leak_id']));
  $other_cost = go_escape_string(stripslashes($record['other_cost']));
  $other_desc = go_escape_string(stripslashes($record['other_desc']));
  $other_quantity = go_escape_string(stripslashes($record['other_quantity']));
  $other_unit = go_escape_string(stripslashes($record['other_unit']));
  $other_taxable = go_escape_string(stripslashes($record['other_taxable']));
  
  $sql = "INSERT into am_leakcheck_othercost(leak_id, description, quantity, cost, taxable, units) values(
  \"$leak_id\", \"$other_desc\", \"$other_quantity\", \"$other_cost\", \"$other_taxable\", \"$other_unit\")";
  executeupdate($sql);
}
  
  
?>