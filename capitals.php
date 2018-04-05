<?php
include "includes/functions.php";
?>
<script>
function delWord(x){
  cf = confirm("Are you sure you want to delete this word?");
  if(cf){
    document.location.href="capitals_delete.php?id=" + x;
  }
}
</script>

<link href="styles/css_white.css" type="text/css" rel="stylesheet">

<a href="capitals_edit.php?id=new">Add New Word</a><br>
<table class="main" cellpadding="4">
<?php
$counter = 0;
$sql = "SELECT word, id from capitalize where master_id='" . $SESSION_MASTER_ID . "' order by word";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $counter++;
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td><?=$record['word']?></td>
  <td><a href="capitals_edit.php?id=<?=$record['id']?>">edit</a></td>
  <td><a href="javascript:delWord('<?=$record['id']?>')">delete</a></td>
  </tr>
  <?php
}
?>
</table>