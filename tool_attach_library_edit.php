<?php
include "includes/header_white.php";

$attach_id = go_escape_string($_GET['attach_id']);

if($attach_id != "new"){
  $sql = "SELECT master_id from attachment_library where attach_id='$attach_id'";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
  
  $sql = "SELECT * from attachment_library where attach_id='$attach_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $description = stripslashes($record['description']);
  $description = go_reg_replace("\"", "&quot;", $description);
  $filename = stripslashes($record['filename']);
}
?>
<script>
function checkform(f){
  var errmsg = "";
  if(f.description.value==""){ errmsg += "Please enter a description for the file.\n";}
  
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
<form action="tool_attach_library_edit_action.php" method="post" enctype="multipart/form-data" onsubmit="return checkform(this)">
<input type="hidden" name="attach_id" value="<?=$attach_id?>">
File Description:<br>
<input type="text" name="description" size="50" maxlength="250" value="<?=$description?>">
<br><br>
File (PDF only):<br>
<input type="file" name="attach">
<?php if($filename != ""){?>
<br>
<a href="uploaded_files/attachment_library/<?=$record['filename']?>" target="_blank">View Existing File</a>
<?php } ?>
<br><br>
<input type="submit" name="submit1" value="Submit">
</form>
</div>