<?php

include "includes/functions.php";

  
  ?>
  <link href="styles/css_white.css" type="text/css" rel="stylesheet">
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
<?php if($_SESSION['sess_msg']){ ?>
<font color="red"><?=$_SESSION['sess_msg']?></font>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<form action="master_password_reset_forgot_action.php" method="post" name="login" onsubmit="return checkform(this)">
<div align="center">
Your new password must be at least 6 characters long, with at least 2 numbers.<br>
<table>
<tr>
<td align="right">Log In Option</td>
<td>
<select name="logchoice">
<option value="fcs">FCS Admin</option>
<option value="fcsview">FCS View</option>
</select>
</td>
</tr>

<tr>
<td align="right">Email</td>
<td><input type="text" name="email"></td>
</tr>
<tr>
<td align="right">Temporary Password (from email)</td>
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
