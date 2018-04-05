<?php
include "includes/functions.php";

$category_id = go_escape_string($_GET['category_id']);

if($category_id==-1){
  $cat_search = " 1=1 ";
}
else {
  $cat_search = " category_ids like '%," . $category_id . ",%' ";
}

ob_start();
?>
<select name="mat_dropdown" id="mat_dropdown" onChange="mat_dropdown_go(this)">
	<option value="0"></option>
	<?php
	$sql = "SELECT id, material from material_list where master_id='" . $SESSION_MASTER_ID . "' and $cat_search order by material";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  ?>
	  <option value="<?=$record['id']?>"><?=stripslashes($record['material'])?></option>
	  <?php
	}
	?>
</select>
<?php
$html = ob_get_contents();
ob_end_clean();
$html = jsclean($html);
?>
document.getElementById('mat_dropdown_area').innerHTML = '<?=$html?>';