<?php include "includes/header.php"; ?>
<?php
$prospect_id = $_GET['prospect_id'];
$property_id = $_GET['property_id'];
if($property_id == "") $property_id = 0;
$act_id = $_GET['act_id'];
if($act_id=="") $act_id="new";

$redirect = $_GET['redirect'];
?>
<div align="center">
  <div class="whiteround" style="height:600px;">
  <iframe frameborder="0" width="100%" height="600" style="border:none;" src="activity_edit_info.php?<?=$_SERVER['QUERY_STRING']?>"></iframe>
  </div>
</div>

<?php include "includes/footer.php"; ?>