<?php include "includes/header_white.php"; ?>

<script>
function DelItem(attach_id){
  cf = confirm("Are you sure you want to delete this file?");
  if(cf){
    document.location.href="admin_library_pdf_delete.php?attach_id=" + attach_id;
  }
}
</script>
<div class="main">

<a href="admin_library_pdf_edit.php?attach_id=new">Add new file</a>
<br>
<table class="main" cellpadding="5" cellspacing="0">
<tr>
<td>Description</td>
<td>View</td>
<td>Edit</td>
<td>Delete</td>
</tr>
<?php
$sql = "SELECT * from document_library_pdf where master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
$counter = 0;
while($record = go_fetch_array($result)){
  $counter++;
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td><?=stripslashes($record['doc_name'])?></td>
  <td><a href="uploaded_files/attachment_library/<?=$record['filename']?>" target="_blank">View</a></td>
  <td><a href="admin_library_pdf_edit.php?attach_id=<?=$record['id']?>">Edit</a></td>
  <td><a href="javascript:DelItem('<?=$record['id']?>')">Delete</a></td>
  </tr>
  <?php
}
?>
</table>
</div>