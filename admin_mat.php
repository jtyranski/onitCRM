<?php
include "includes/functions.php";

$cat_filter = $_GET['cat_filter'];
$cat_query = " 1=1 ";
if($cat_filter==""){
  if($_SESSION['ad_cat_filter'] != ""){
    $cat_filter = $_SESSION['ad_cat_filter'];
  }
  else {
    $cat_filter = -1;
  }
}
$_SESSION['ad_cat_filter'] = $cat_filter;

if($cat_filter != -1) $cat_query = " category_ids like '%," . $cat_filter . ",%' ";

?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<script>
function DelMat(x){
  cf = confirm("Are you sure you want to delete this material?");
  if(cf){
    document.location.href="admin_mat_delete.php?id=" + x;
  }
}
</script>
  

<div class="main">
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="form1">
Filter by category: 
<select name="cat_filter" onchange="document.form1.submit()">
<option value="-1"<?php if($cat_filter==-1) echo " selected";?>>All</option>
<option value="0"<?php if($cat_filter==0) echo " selected";?>>General</option>
<?php
$sql = "SELECT category_id, category_name from material_list_categories where master_id='" . $SESSION_MASTER_ID . "' order by category_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['category_id']?>"<?php if($cat_filter==$record['category_id']) echo " selected";?>><?=stripslashes($record['category_name'])?></option>
  <?php
}
?>
</select>
</form>
<a href="admin_mat_category.php">Edit categories</a><br><br>
<a href="admin_mat_edit.php?id=new">Add a new default material</a>
<table cellpadding="4" cellspacing="0">
<tr>
<td><strong>Material Name</strong></td>
<td><strong>Unit</strong></td>
<td><strong>Cost per Unit</strong></td>
<td><strong>Edit</strong></td>
<td><strong>Delete</strong></td>
</tr>
<?php
$counter=1;
$sql = "SELECT id, material, unit, cost from material_list where master_id='" . $SESSION_MASTER_ID . "' and $cat_query order by material";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $counter++;
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"#ececec\"";?>>
  <td><?=stripslashes($record['material'])?></td>
  <td valign="top"><?=stripslashes($record['unit'])?></td>
  <td valign="top">$<?=number_format($record['cost'], 2)?></td>

  <td><a href="admin_mat_edit.php?id=<?=$record['id']?>">Edit</a></td>
  <td><a href="javascript:DelMat('<?=$record['id']?>')">Delete</a></td>
  </tr>
  <?php
}
?>
</table>

</div>

