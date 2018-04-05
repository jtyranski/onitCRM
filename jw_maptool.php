<?php
exit;
include "includes/functions.php";

$already_has = 3;
$so_give_them = 8;

$sql = "SELECT url, name, on_icon, off_icon from toolbox_master where tool_master_id='$so_give_them'";
$result = executequery($sql);
$record = go_fetch_array($result);
$url = $record['url'];
$name = $record['name'];
$on_icon = $record['on_icon'];
$off_icon = $record['off_icon'];



$sql = "SELECT master_id from toolbox_items where tool_master_id='$already_has'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $master_id = $record['master_id'];
  
  $sql = "SELECT count(*) from toolbox_items where master_id='$master_id' and tool_master_id='$so_give_them'";
  $test = getsingleresult($sql);
  if($test==0){
    $sql = "INSERT into toolbox_items(master_id, tool_master_id, url, name, on_icon, off_icon, cat_id) values(
	'$master_id', '$so_give_them', \"$url\", \"$name\", \"$on_icon\", \"$off_icon\", 1)";
	executeupdate($sql);
  }
}
?>
