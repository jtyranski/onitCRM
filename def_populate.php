<?php
include "includes/functions.php";

$id = $_GET['id'];
$def_id = $_GET['def_id'];

$sql = "SELECT def, corrective_action, def_name from def_list_master where id='$id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$def = stripslashes($record['def']);
$corrective_action = stripslashes($record['corrective_action']);
$def_name = stripslashes($record['def_name']);

if($def_name=="Other") $def_name = "";

$def = nl2br($def);
$def = go_reg_replace("\<br \/\>", "ZZZZ", $def);
/*
$def = go_reg_replace("\"", "'", $def);
$def = go_reg_replace(chr(10), "", $def);
$def = go_reg_replace(chr(12), "", $def);
$def = go_reg_replace(chr(13), "", $def);
*/
$def = jsclean($def);

$corrective_action = nl2br($corrective_action);
$corrective_action = go_reg_replace("\<br \/\>", "ZZZZ", $corrective_action);
/*
$corrective_action = go_reg_replace("\"", "'", $corrective_action);
$corrective_action = go_reg_replace(chr(10), "", $corrective_action);
$corrective_action = go_reg_replace(chr(12), "", $corrective_action);
$corrective_action = go_reg_replace(chr(13), "", $corrective_action);
*/
$corrective_action = jsclean($corrective_action);

?>
document.getElementById('def_<?=$def_id?>').value = '<?php echo $def; ?>';

document.getElementById('action_<?=$def_id?>').value = '<?php echo $corrective_action; ?>';

document.getElementById('name_<?=$def_id?>').value = "<?php echo $def_name; ?>";

foo = document.getElementById('def_<?=$def_id?>').value;
bar = foo.replace(/ZZZZ/g, "\n");
document.getElementById('def_<?=$def_id?>').value = bar;

foo = document.getElementById('action_<?=$def_id?>').value;
bar = foo.replace(/ZZZZ/g, "\n");
document.getElementById('action_<?=$def_id?>').value = bar;