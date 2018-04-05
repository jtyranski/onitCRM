<body style="margin:0px 0px 0px 0px;">
<meta http-equiv="refresh" content="45;url=check_messages.php">
<?php
include "includes/functions.php";
$sql = "SELECT count(*) from comments where to_user_id='" . $SESSION_USER_ID . "' and owner='" . $SESSION_USER_ID . "' 
	  and new=1";
$new = getsingleresult($sql);
if($new){
  ?>
  <a href="index.php" target="_parent"><img src="images/chat-icon.png" border="0"></a>
  <?php
}
?>