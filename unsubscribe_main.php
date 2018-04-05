<?php include "includes/header_white.php"; ?>
<?php

?>

<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<div align="left">
<form action="unsubscribe_main_action.php" method="post" name="subscribe">
<table class="main">
<tr>
<td colspan="2">Please enter the email address you would like removed from this emailer.</td>
</tr>
<tr>
<td colspan="2">
<input type="text" name="email" value="<?=$email?>" size="60">
</td>
</tr>
<tr>
<td colspan="2">
<input type="submit" name="submit1" value="Submit">
</td>
</tr>
</table>
</form>
</div>
