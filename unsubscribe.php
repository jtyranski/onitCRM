<?php include "includes/functions.php"; ?>

<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<script>
function DelItem(x){
  cf = confirm("Are you sure you want to delete this entry?");
  if(cf){
    document.location.href="unsubscribe_delete.php?id=" + x;
  }
}
</script>
<a href="fcs_contractors.php">Return to Main</a><br>
<div class="main">
This list will not receive emails from the Contractor report.
</div>

<a href="unsubscribe_edit.php?id=new">Add a new email</a><br>
<table class="main" cellpadding="3">
<?php
$sql = "SELECT id, email from unsubscribe_admin";
$result = executequery($sql);
$counter = 0;
while($record = go_fetch_array($result)){
  $counter++;
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td><?=stripslashes($record['email'])?></td>
  <td><a href="unsubscribe_edit.php?id=<?=$record['id']?>">edit</a></td>
  <td><a href="javascript:DelItem('<?=$record['id']?>')">delete</a></td>
  </tr>
  <?php
}
?>
</table>