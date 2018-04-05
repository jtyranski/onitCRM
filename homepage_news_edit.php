<?php
include "includes/header_white.php";

$message_id = go_escape_string($_GET['message_id']);

if($message_id != "new"){
  $sql = "SELECT message, display from homepage_messages where message_id='$message_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $message = stripslashes($record['message']);
  $display = $record['display'];
}
else {
  $display=1;
}

?>
<div class="main">

<form action="homepage_news_edit_action.php" method="post">
<input type="hidden" name="message_id" value="<?=$message_id?>">
<strong>News Item</strong>
<br>
<textarea name="message" rows="10" cols="80"><?=$message?></textarea>
<br><br>
<strong>Display</strong><br>
<select name="display">
<option value="1"<?php if($display==1) echo " selected";?>>On</option>
<option value="0"<?php if($display==0) echo " selected";?>>Off</option>
</select>
<br><br>
<input type="submit" name="submit1" value="Submit">
</form>

</div>