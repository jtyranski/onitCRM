<?php
include "includes/functions.php";

$mat_id = $_GET['mat_id'];

$sql = "SELECT * from material_list where id='$mat_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$material = stripslashes(jsclean($record['material']));
$unit = stripslashes(jsclean($record['unit']));
$cost = stripslashes(jsclean($record['cost']));

if(strtolower($unit)=="sqft" || strtolower($unit)=="sq/ft") $unit = "SF";
?>
document.getElementById('mat_description').value='<?=$material?>';
document.getElementById('mat_unit').value='<?=$unit?>';
document.getElementById('mat_cost').value='<?=$cost?>';
