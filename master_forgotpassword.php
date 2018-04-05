<?php include "includes/functions.php"; ?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div style="color:red;"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<table align="center" class="main">
<tr>
<td>
<form action="master_forgotpassword_action.php" method="post">
Please enter your registered email address in the box below, and your password will be emailed to you.
<br>
Log In Option
<br>
<select name="logchoice">
<option value="fcs">FCS Admin</option>
<option value="fcsview">FCS View</option>
</select>
<br>
Email:<br>
<input type="text" name="email" size="40">

<br>
<input type="submit" value="Retrieve Password">
</form>
</td>
</tr>
</table>
