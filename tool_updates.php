<?php include "includes/header_white.php"; ?>
<div class="main">
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div style="color:red;"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
This program will send an email to our users with the most recent updates<br>
<form action="tool_updates_email.php" method="post">
<input type="checkbox" name="include_fcs" value="1">Include <?=$MAIN_CO_NAME?> users in email
<br><br>
<input type="submit" name="submit1" value="Send Email">
</form>
</div>