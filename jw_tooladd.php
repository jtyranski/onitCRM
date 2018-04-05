<?php
exit;
include "includes/functions.php";

$sql = "SELECT master_id from master_list";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $master_id = $record['master_id'];
  $sql = "INSERT into toolbox_items(cat_id, master_id, name, url) values('1', '$master_id', 'Dispatch Report', 'fcs_sd_report.php')";
  executeupdate($sql);
}
?>
