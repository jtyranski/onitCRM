<?php include "includes/header_white.php"; ?>

<div class="main">

<a href="tool_discipline_edit.php?dis_id=new">Add new discipline</a><br>

<table class="main" cellpadding="3" cellspacing="0">
<?php
$sql = "SELECT dis_id, discipline from disciplines order by discipline";
$result = executequery($sql);
$counter=0;
while($record = go_fetch_array($result)){
  $counter++;
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td><?=stripslashes($record['discipline'])?></td>
  <td>
  <?php if($record['dis_id'] != 1){ ?>
  <a href="tool_discipline_edit.php?dis_id=<?=$record['dis_id']?>">edit</a>
  <?php } ?>
  </td>
  </tr>
  <?php
}
?>
</table>
</div>