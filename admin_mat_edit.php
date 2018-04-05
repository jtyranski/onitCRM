<?php
include "includes/functions.php";

$id = $_GET['id'];
if($id != "new"){
  $sql = "SELECT master_id from material_list where id ='$id'";
  $master_id = getsingleresult($sql);
  if($master_id != $SESSION_MASTER_ID) exit;
  
  $sql = "SELECT * from material_list where id='$id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $material = stripslashes($record['material']);
  $unit = stripslashes($record['unit']);
  $cost = stripslashes($record['cost']);
  $category_ids = stripslashes($record['category_ids']);
  
  $material = go_reg_replace("\"", "&quot;", $material);
}
  
?>
<script>
function checkform(f){
  var errmsg = "";
  if(f.material.value==""){ errmsg += "Please enter the material name.\n";}
  if(f.xunit.value==""){ errmsg += "Please enter the unit.\n";}
  if(f.cost.value==""){ errmsg += "Please enter the cost per unit.\n";}
  
  if(errmsg==""){
    return true;
  }
  else {
    alert(errmsg);
	return false;
  }
}
</script>
  
<link href="styles/css_white.css" type="text/css" rel="stylesheet">

  
<div class="main">
<form action="admin_mat_edit_action.php" method="post" onsubmit="return checkform(this)">
<input type="hidden" name="id" value="<?=$id?>">
<table class="main">
<tr>
<td valign="top">Categories</td>
<td>
<input type="checkbox" name="category_ids[]" value="0"<?php if(go_reg(",0,", $category_ids)) echo " checked";?>>General<br>
<?php
$sql = "SELECT category_id, category_name from material_list_categories where master_id='" . $SESSION_MASTER_ID . "' order by category_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <input type="checkbox" name="category_ids[]" value="<?=$record['category_id']?>"<?php if(go_reg("," . $record['category_id'] . ",", $category_ids)) echo " checked";?>><?=stripslashes($record['category_name'])?><br>
  <?php
}
?>
</td>
</tr>
<tr>
<td>Material Name</td>
<td><input type="text" name="material" value="<?=$material?>"></td>
</tr>
<tr>
<td valign="top">Unit</td>
<td><input type="text" name="xunit" value="<?=$unit?>"></td>
</tr>
<tr>
<td valign="top">Cost per Unit</td>
<td>$<input type="text" name="cost" value="<?=$cost?>"></td>
</tr>
<tr>
<td colspan="2"><input type="submit" name="submit1" value="Update"></td>
</tr>
</table>
</form>
