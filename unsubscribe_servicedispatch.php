<?php include "includes/header_white.php"; ?>
<?php
$leak_id = go_escape_string($_SERVER['QUERY_STRING']);

?>

<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<div align="left">
<form action="unsubscribe_servicedispatch_action.php" method="post" name="subscribe">
<input type="hidden" name="leak_id" value="<?=$leak_id?>">
<table class="main">
<tr>
<td colspan="2">Please enter the email address you would like removed from this Service Dispatch emailer.</td>
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
<?php include "includes/footer.php"; ?>