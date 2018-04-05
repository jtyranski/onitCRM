<?php include "includes/header.php"; ?>
<?php
if($_COOKIE['ro_rememberme'] != ""){
  $email = $_COOKIE['ro_rememberme'];
  $checked = " checked";
}
?>
<br>
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<script>
function checkform(f){
  errmsg = "";
  if(f.email.value == "") { errmsg += "Please enter your email address.\n";}
  if(f.oldpassword.value == "") { errmsg += "Please enter your current password.\n";}
  if(f.newpassword1.value == "") { errmsg += "Please enter your new password.\n";}
  if(f.newpassword1.value != f.newpassword2.value) { errmsg += "Passwords do not match.\n";}
  
  if(errmsg == ""){
    return true;
  }
  else {
    alert(errmsg);
	return false;
  }
}
</script>
<form action="password_change_action.php" method="post" name="login" onsubmit="return checkform(this)">
<div align="center">
<table>
<tr>
<td align="right">Email</td>
<td><input type="text" class="largerbox" name="email" value="<?=$email?>"></td>
</tr>
<tr>
<td align="right">Current Password</td>
<td><input type="password" name="oldpassword"></td>
</tr>
<tr>
<td align="right">New Password</td>
<td><input type="password" name="newpassword1"></td>
</tr>
<tr>
<td align="right">Confirm New Password</td>
<td><input type="password" name="newpassword2"></td>
</tr>
<tr>
<td colspan="2"><input type="submit" name="submit1" value="Submit"></td>
</tr>
</table>
</div>
</form>
<script>
document.login.email.focus();

</script>
<?php include "includes/footer.php"; ?>