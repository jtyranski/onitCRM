<?php include "includes/header.php"; ?>
<?php include "includes/main_nav.php"; ?>
<br>
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname, title from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <a href="contact_details.php?user_id=<?=$record['user_id']?>" class="large"><?=stripslashes($record['fullname'])?></a>
  <?php if($record['title'] != "") echo "<br>"; ?>
  <?=stripslashes($record['title'])?>
  <br><br>
  <?php
}
?>
<?php include "includes/footer.php"; ?>