<?php
include "includes/functions.php";

$id = go_escape_string($_GET['id']);

$sql = "SELECT template from document_template where id=\"$id\" and master_id='" . $SESSION_MASTER_ID . "'";
$template = stripslashes(getsingleresult($sql));

$template = specialChar($template);

$template = jsclean($template);
?>
document.form1.content.value = "<?=$template?>";
newdesc = document.getElementById('content').value;
newdesc = newdesc.replace(/NEWLINE/g, "\n");
<?php
$sql = "SELECT * from special_chars";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $actual_char = $record['search_for'];
  $code = stripslashes($record['code']);
  ?>
  newdesc = newdesc.replace(/<?=$code?>/g, "<?=$actual_char?>");
  <?php
}
?>
  document.form1.content.value = newdesc;