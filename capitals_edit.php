<?php
include "includes/functions.php";

$id = $_GET['id'];
if($id != "new"){
  $sql = "SELECT word from capitalize where id='$id' and master_id='" . $SESSION_MASTER_ID . "'";
  $word = getsingleresult($sql);
}

?>

<link href="styles/css_white.css" type="text/css" rel="stylesheet">

<form action="capitals_edit_action.php" method="post">
<input type="hidden" name="id" value="<?=$id?>">
<input type="text" name="word" value="<?=$word?>">
<br>
<input type="submit" name="submit1" value="Update">
</form>