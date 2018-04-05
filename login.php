<?php include "includes/functions.php"; ?>
<br>
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<form action="login_action.php" method="post" name="login">
<div align="center">
<img src="images/fcs_logo.jpg"><br>
<table>
<tr>
<td align="right">Email</td>
<td><input type="text" name="email"></td>
</tr>
<tr>
<td align="right">Password</td>
<td><input type="password" name="password"></td>
</tr>
<tr>
<td colspan="2">
<select name="type">
<option value="fcs">Core</option>
<option value="fcsview">Client View</option>
</select>
</td>
</tr>
<tr>
<td colspan="2"><input type="submit" name="submit1" value="Log In"></td>
</tr>
</table>
</div>
</form>
<?php /*
<a href="forgot_password.php">Forgot Password</a>
*/ ?>
<script>
document.login.email.focus();

</script>
<?php include "includes/footer.php"; ?>