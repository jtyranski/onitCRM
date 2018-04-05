<?php
include "includes/functions.php";

$sql = "SELECT section_id from sections_def group by section_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $section_id = $record['section_id'];
  
  $counter = 1;
  $sql = "SELECT * from sections_def where section_id='$section_id' order by def_id";
  $res2 = executequery($sql);
  while($rec2 = go_fetch_array($res2)){
    $def_id = $rec2['def_id'];
	$sql = "UPDATE sections_def set number='$counter' where def_id='$def_id'";
	executeupdate($sql);
	$counter++;
  }
}
?>