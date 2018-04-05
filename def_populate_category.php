<?php
include "includes/functions.php";

$category_id = $_GET['category_id'];
$def_id = $_GET['def_id'];
ob_start();
?>
<select name="def_list" onChange="ajax_def(this, '<?=$def_id?>')">
<option value=""></option>
<?php
$counter=0;
$sql = "SELECT id, def_name from def_list_master where visible=1 and master_id='" . $SESSION_MASTER_ID . "' and category_ids like '%,$category_id,%'";
$result_list = executequery($sql);
while($record_list = go_fetch_array($result_list)){
  $counter++;
  ?>
  <option value="<?=$record_list['id']?>"<?php if($record_list['def_name']==$record['name']) echo " selected";?>><?=$counter?> <?=stripslashes($record_list['def_name'])?></option>
  <?php
}
?>
</select>
<?php
$html = ob_get_contents();
ob_end_clean();


$html = jsclean($html);


?>
document.getElementById('def_list_select_<?=$def_id?>').innerHTML = '<?php echo $html; ?>';

