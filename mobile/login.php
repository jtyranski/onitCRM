<?php include "includes/header.php"; ?>
<?php
if($_COOKIE['ro_rememberme'] != ""){
  $email = $_COOKIE['ro_rememberme'];
  $checked = " checked";
  $pass = $_COOKIE['ro_rememberme_pass'];
}
?>
<br>
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<form action="../login_action.php" method="post" name="login">
<input type="hidden" name="type" value="fcsmobi">
<img src="images/fcs_logo2.png" width="320px">
<table class="main">
<tr>
<td align="right">Username</td>
<td><input type="text" name="email" size="28" value="<?=$email?>" class="largerbox"></td>
</tr>
<tr>
<td align="right">Password</td>
<td><input type="password" name="password" size="28" value="<?=$pass?>" class="largerbox"></td>
</tr>
<tr>
<td colspan="2"><input type="checkbox" name="remember" value="1"<?=$checked?>>Remember Me</td>
</tr>
<?php /*
<tr>
<td colspan="2"><input type="checkbox" name="old_mobile" value="1">Use Old Mobile View</td>
</tr>
*/ ?>
<tr>
<td colspan="2"><input type="submit" name="submit1" value="Log In" style="width:290px; height:40px;"></td>
</tr>
</table>
</form>
<script>
document.login.email.focus();
<?php if($_COOKIE['ro_rememberme'] != ""){ ?>
document.login.password.focus();
<?php } ?>

<?php if($_SERVER['REMOTE_ADDR']=="98.206.53.234"){ ?>
//document.login.email.value="jwert@encitegroup.com";
//document.login.password.value="test55";
//document.login.submit();
<?php } ?>
</script>
<?php include "includes/footer.php"; ?>
