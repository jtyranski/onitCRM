<?php include "includes/header.php"; ?>
<br>
<div align="center">
  <div class="whiteround" align="left">
  <div>
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname, photo, title from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
$photo = stripslashes($record['photo']);
?>
<div style="position:relative;">
<div style="float:left; width:95px;">
<?php
if($photo != ""){ 
  echo "<img src='" . $UPLOAD . "headshots/". $photo ."'>";
}
else {
  echo "<img src='images/spacer.gif'>";
}
  ?>
</div>
<div style="float:left;">
  <a href="contact_details.php?user_id=<?=$record['user_id']?>" class="large"><?=stripslashes($record['fullname'])?></a>
  <?php if($record['title'] != "") echo "<br>"; ?>
  <?=stripslashes($record['title'])?>
</div>
</div>
<div style="clear:both; height:10px;"><img src="images/spacer.gif"></div>

  <?php
}
?>

</div>
</div>
</div>
<?php include "includes/footer.php"; ?>
