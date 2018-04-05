<?php
exit;
include "includes/functions.php";

$sql = "SELECT section_id from sections_def where coordinates != '' group by section_id";
$res = executequery($sql);
while($rec = go_fetch_array($res)){
  $section_id = $rec['section_id'];
$sql = "SELECT * from sections where section_id='$section_id'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  //$section_id = $record['section_id'];
  $main_photo = $record['main_photo'];
  
  stretchimage("uploaded_files/sections/$main_photo", "uploaded_files/sections/", 600);
}
} 
  ?>