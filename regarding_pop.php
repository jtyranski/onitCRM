<?php
include "includes/functions.php";

$act_id = $_GET['act_id'];

$sql = "SELECT regarding_large, attachment from activities where act_id='$act_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$regarding_large = stripslashes(nl2br($record['regarding_large']));
$attachment = $record['attachment'];

echo $regarding_large;
if($attachment != ""){
  echo "<br><br>";
  echo "<a href='" . $UPLOAD . "activities/" . $attachment . "' target='_blank'>Attachment</a>";
}
?>
<br><br>
<a href="javascript:window.close()">Close</a>