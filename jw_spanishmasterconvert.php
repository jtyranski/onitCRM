<?php
exit;
include "includes/functions.php";

$sql = "SELECT def_name, def_name_spanish from def_list where def_name_spanish != ''";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $def_name = stripslashes($record['def_name']);
  $def_name_spanish = stripslashes($record['def_name_spanish']);
  
  $sql = "UPDATE def_list_master set def_name_spanish=\"" . go_escape_string($def_name_spanish) . "\" where def_name=\"" . go_escape_string($def_name) . "\"";
  executeupdate($sql);
}
?>
  