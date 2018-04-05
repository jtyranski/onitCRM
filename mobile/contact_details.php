<?php include "includes/header.php"; ?>
<?php include "includes/main_nav.php"; ?>
<br>
<?php
$user_id = $_GET['user_id'];
$sql = "SELECT concat(firstname, ' ', lastname) as fullname, photo, title, office, extension, cellphone, office_address, email 
from users where user_id='$user_id' and enabled=1 and master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$fullname = stripslashes($record['fullname']);
$photo = stripslashes($record['photo']);
$title = stripslashes($record['title']);
$office = stripslashes($record['office']);
$extension = stripslashes($record['extension']);
$cellphone = stripslashes($record['cellphone']);
$office_address = stripslashes($record['office_address']);
$email = stripslashes($record['email']);
?>
<table class="main" width="100%">
<tr>
<td valign="top">
<?php if($photo != ""){ ?>
<img src="../uploaded_files/headshots/<?=$photo?>">
<?php } ?>
</td>
<td valign="top">
<div class="main_large"><?=$fullname?></div>
<?=$title?>
<br><br>
<?=nl2br($office_address)?>
</td>
</tr>
</table>
<br>
<?php if($office != ""){ ?>
O: <?=$office?>
  <?php if($extension != "") echo " x $extension";?>
<br>
<?php } ?>
<?php if($cellphone != ""){ ?>
C: <?=$cellphone?>
<br>
<?php } ?>
<br>
<?php if($email != ""){ ?>
Email: <a href="mailto:<?=$email?>"><?=$email?></a>
<br>
<?php } ?>


<?php include "includes/footer.php"; ?>