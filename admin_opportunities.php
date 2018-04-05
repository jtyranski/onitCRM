<?php
include "includes/functions.php";
?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<script>
function DelOpp(x){
  cf = confirm("Are you sure you want to delete this product?");
  if(cf){
    document.location.href="admin_opportunities_delete.php?opp_product_id=" + x;
  }
}
</script>
<div class="main">
<strong>Products for your Opportunities</strong>
<br>
<a href="admin_opportunities_edit.php?opp_product_id=new">Add a new product</a><br>
<table class="main" cellpadding="4">
<tr>
<td><strong>Product</strong></td>
<td></td>
<td></td>
</tr>
<?php
$sql = "SELECT * from opportunities_master";
$result = executequery($sql);
$counter=0;
while($record = go_fetch_array($result)){
  $counter++;
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"#ececec\"";?>>
  <td><?=stripslashes($record['opp_name'])?></td>
  <td></td>
  <td></td>
  </tr>
  <?php
}
?>
<?php
$sql = "SELECT * from opportunities_items where master_id='" . $SESSION_MASTER_ID . "' order by opp_name";
$result = executequery($sql);
$counter=0;
while($record = go_fetch_array($result)){
  $counter++;
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"#ececec\"";?>>
  <td><?=stripslashes($record['opp_name'])?></td>
  <td><a href="admin_opportunities_edit.php?opp_product_id=<?=$record['opp_product_id']?>">edit</a></td>
  <td><a href="javascript:DelOpp('<?=$record['opp_product_id']?>')">delete</a></td>
  </tr>
  <?php
}
?>
</table>
</div>