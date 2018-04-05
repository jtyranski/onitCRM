<?php
require_once "includes/functions.php";

$event_id = $_GET['event_id'];

if($event_id != "new"){
  $sql = "SELECT title, description, ro_user_id, attachment, date_format(complete_date, \"%m/%d/%Y\") as complete_pretty, publish from supercali_events where event_id='$event_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $title = stripslashes($record['title']);
  $description = stripslashes($record['description']);
  $ro_user_id = stripslashes($record['ro_user_id']);
  $attachment = stripslashes($record['attachment']);
  $complete_pretty = stripslashes($record['complete_pretty']);
  $publish = stripslashes($record['publish']);
  
  $description = go_reg_replace("\n", "ZZZZ", $description);
  

}
/*
$title = go_reg_replace("\"", "&quot;", $title);
$title = go_reg_replace("\'", "&#39;", $title);

$description = go_reg_replace("\"", "&quot;", $description);
$description = go_reg_replace("\'", "&#39;", $description);
*/




ob_start();

?>

Project Name: <?=$title?>
<br>
Description:<br>
<textarea name="description" rows="10" cols="60"><?=$description?></textarea>
<br>
<div style="position:relative;">
<div style="float:left;">
Complete Date: <?=$complete_pretty?>
</div>
<div style="float:left; padding-left:10px;">
<?php if($attachment != ""){ ?>
<a href="uploaded_files/programming/<?=$attachment?>" target="_blank">View Attachment</a>
<?php } ?>
</div>
</div>
<div style="clear:both;"></div>

<?php
$html = ob_get_contents();
  ob_end_clean();
if($publish==0){
  $html = "<textarea name='description' rows='10' cols='60'>Error Accessing Information</textarea>";
}
  $html = jsclean($html);
?>

div = document.getElementById('pform');
div.innerHTML = '<?php echo $html; ?>';
foo = document.programmingform.description.value;
bar = foo.replace(/ZZZZ/g, "\n");
document.programmingform.description.value = bar;