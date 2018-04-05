<?php
include "includes/functions.php";

?>
<form action="frame_user_email.php" method="post" name="form1">
<?php for($x=0;$x<sizeof($_SESSION['list_just_id']);$x++){ ?>
<input type="hidden" name="ids[]" value="<?=$_SESSION['list_just_id'][$x]?>">
<?php } ?>
<input type="hidden" name="list_type" value="<?=$_SESSION['list_boxtype']?>">
</form>
<div style="display:none;">
<input type="submit" name="submit1" value="Submit">
</div>
<script>
document.forms["form1"].submit();
</script>