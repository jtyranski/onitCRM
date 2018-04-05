<?php include "includes/functions.php"; ?>
<?php $note_id = $_GET['note_id']; ?>
<?php $property_id = $_GET['property_id']; ?>
<?php $prospect_id = $_GET['prospect_id']; ?>
<?php if($property_id=="") $property_id = 0;?>

<?php if($note_id != "new"){
  $sql = "SELECT * from notes where note_id='$note_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $regarding = stripslashes($record['regarding']);
  $attachment = stripslashes($record['attachment']);
  $note = stripslashes($record['note']);
  $regarding = go_reg_replace("\"", "", $regarding);
}
?>
<html>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<div class="main">
<form action="frame_addnote_action.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
<input type="hidden" name="note_id" value="<?=$note_id?>">
Regarding:<br>
<input type="text" name="regarding" size="50" maxlength="250" value="<?=$regarding?>">
<br><br>
Attachment (20 MB Max): <input type="file" name="attachment">
<?php if($attachment != ""){ ?>
<br><a href="<?=$UPLOAD?>attachments/<?=$attachment?>" target="_blank">Attachment</a>
<?php } ?>
<br><br>
Note:<br>
<textarea name="note" cols="90" rows="7"><?=$note?></textarea>
<br>
<input type="submit" name="submit1" value="Add Note">
</form>
</div>