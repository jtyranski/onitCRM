<?php
include "includes/functions.php";

?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">

<script>
function delcat(x){
  cf = confirm("Are you sure you want to delete this category?");
  if(cf){
    document.location.href="admin_def_category_delete.php?category_id=" + x;
  }
}
</script>

<div class="main">
<a href="admin_def.php">Return to defiency list</a><br><br>

<a href="admin_def_category_edit.php?category_id=new">Add a new category</a>
<table cellpadding="4" cellspacing="0">
<tr>
<td><strong>Category Name</strong></td>
<td><strong>Edit</strong></td>
<td></td>
</tr>
<?php
$counter=1;
$sql = "SELECT category_id, category_name from def_list_categories where master_id='" . $SESSION_MASTER_ID . "' order by category_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $counter++;
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"#ececec\"";?>>
  <td><?=stripslashes($record['category_name'])?></td>

  <td><a href="admin_def_category_edit.php?category_id=<?=$record['category_id']?>">Edit</a></td>
  <td><a href="javascript:delcat('<?=$record['category_id']?>')">delete</a></td>
  </tr>
  <?php
}
?>
</table>

</div>

