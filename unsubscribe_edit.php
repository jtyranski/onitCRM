<?php include "includes/functions.php"; ?>
<?php
$id = $_GET['id'];
if($id != "new"){
  $sql = "SELECT email from unsubscribe_admin where id='$id'";
  $email = stripslashes(getsingleresult($sql));
}
?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">

<form action="unsubscribe_edit_action.php" method="post">
<input type="hidden" name="id" value="<?=$id?>">
<div class="main">
Email Address:<br>
<input type="text" name="email" value="<?=$email?>" size="40">
</div>
<br>
<input type="submit" name="submit1" value="Update">
</form>