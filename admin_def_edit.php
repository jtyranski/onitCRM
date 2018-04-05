<?php
include "includes/functions.php";

$id = $_GET['id'];
if($id != "new"){
  $sql = "SELECT master_id from def_list_master where id ='$id'";
  $master_id = getsingleresult($sql);
  if($master_id != $SESSION_MASTER_ID) exit;
  
  $sql = "SELECT * from def_list_master where id='$id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $def_name = stripslashes($record['def_name']);
  $def_name_spanish = stripslashes($record['def_name_spanish']);
  $def = stripslashes($record['def']);
  $corrective_action = stripslashes($record['corrective_action']);
  $category_id = stripslashes($record['category_id']);
  $category_ids = stripslashes($record['category_ids']);
  
  $def_name = go_reg_replace("\"", "&quot;", $def_name);
}
  
?>
<script>
function checkform(f){
  var errmsg = "";
  if(f.def_name.value==""){ errmsg += "Please enter the deficiency name.\n";}
  if(f.def.value==""){ errmsg += "Please enter the deficiency.\n";}
  if(f.corrective_action.value==""){ errmsg += "Please enter the corrective action.\n";}
  
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
<form action="admin_def_edit_action.php" method="post" onsubmit="return checkform(this)">
<input type="hidden" name="id" value="<?=$id?>">
<table class="main">
<tr>
<td valign="top">Categories</td>
<td>
<input type="checkbox" name="category_ids[]" value="0"<?php if(go_reg(",0,", $category_ids)) echo " checked";?>>General<br>
<?php
$sql = "SELECT category_id, category_name from def_list_categories where master_id='" . $SESSION_MASTER_ID . "' order by category_name";
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
<td>Deficiency Name</td>
<td><input type="text" name="def_name" value="<?=$def_name?>"></td>
</tr>
<tr>
<td>Deficiency Name (Spanish)(optional)</td>
<td><input type="text" name="def_name_spanish" value="<?=$def_name_spanish?>"></td>
</tr>
<tr>
<td valign="top">Deficiency</td>
<td><textarea name="def" rows="6" cols="70"><?=$def?></textarea></td>
</tr>
<tr>
<td valign="top">Corrective Action</td>
<td><textarea name="corrective_action" rows="6" cols="70"><?=$corrective_action?></textarea></td>
</tr>
<tr>
<td colspan="2"><input type="submit" name="submit1" value="Update"></td>
</tr>
</table>
</form>
