<?php include "includes/functions.php"; ?>
<?php
if($_SESSION['sess_login'] == "") meta_redirect("login.php");
?>
<br>
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<div align="center">
<form action="login_multi_action.php" method="post" name="login">

<table>
<tr>
<td align="right">Email</td>
<td><input type="text" name="email" value="<?=$_SESSION['sess_login']?>"></td>
</tr>
<tr>
<td align="right">Password</td>
<td><input type="password" name="password" value="<?=$_SESSION['sess_password']?>"></td>
</tr>
<tr>
<td align="right">Company</td>
<td>
<select name="master_id">
<?php
$sql = "SELECT master_id from users where email=\"" . $_SESSION['sess_login'] . "\" 
and password=\"" . md5($_SESSION['sess_password']) . "\" and enabled=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $master_id = $record['master_id'];
  $sql = "SELECT master_name from master_list where master_id='$master_id'";
  $master_name = stripslashes(getsingleresult($sql));
  ?>
  <option value="<?=$master_id?>"><?=$master_name?></option>
  <?php
}
?>
</select>
</td>
</tr>
<tr>
<td colspan="2"><input type="submit" name="submit1" value="Log In"></td>
</tr>
</table>
</form>
<a href="forgot_password.php">Forgot Password</a>
</div>
<script>
document.login.email.focus();

</script>
<?php
$_SESSION['sess_login'] = "";
$_SESSION['sess_password'] = "";
?>
<?php include "includes/footer.php"; ?>