<?php
include "includes/functions.php";
$category_id = $_GET['category_id'];
if($category_id != "new"){
  $sql = "SELECT master_id from material_list_categories where category_id ='$category_id'";
  $master_id = getsingleresult($sql);
  if($master_id != $SESSION_MASTER_ID) exit;
  
  $sql = "SELECT * from material_list_categories where category_id='$category_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $category_name = stripslashes($record['category_name']);
  
  $category_name = go_reg_replace("\"", "&quot;", $category_name);
}
?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<script>
function checkform(f){
  var errmsg = "";
  if(f.category_name.value==""){ errmsg += "Please enter the category name.\n";}
  
  if(errmsg==""){
    return true;
  }
  else {
    alert(errmsg);
	return false;
  }
}
</script>

<div class="main">
<form action="admin_mat_category_edit_action.php" method="post" onSubmit="return checkform(this)">
<input type="hidden" name="category_id" value="<?=$category_id?>">
Category Name:
<input type="text" name="category_name" value="<?=$category_name?>" maxlength="200" size="40">
<br><br>
<input type="submit" name="submit1" value="Update">
</form>
</div>