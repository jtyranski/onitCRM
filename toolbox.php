<?php include "includes/header.php"; ?>
<script>
function go_toolbox(url){
  document.getElementById('toolbox_goback').style.display="";
  document.getElementById('toolbox_frame').src=url;
}
</script>
<div align="center">
  <div class="whiteround" style="height:800px;">
  <div align="right" id="toolbox_goback" style="display:none;">
  <?php
  ImageLink("self.frames['toolbox_frame'].history.back()", "back-icon", 1, 1);
  ?>
  </div>
  <iframe frameborder="0" width="100%" height="760" style="border:none;" src="ipad_toolbox.php" id="toolbox_frame" name="toolbox_frame"></iframe>
  </div>
</div>
<?php
$go = $_GET['go'];
//$go = go_reg_replace("\*", "?", $go);
if($go != ""){
  $go_parts = explode("*", $go);
  $go_link = chop($go_parts[0]);
  if($go_parts[1] != "") $go_link .= "?" . chop($go_parts[1]);
  for($x=2;$x<sizeof($go_parts);$x++){
    $go_link .= "&" . chop($go_parts[$x]);
  }
  ?>
  <script>
  go_toolbox('<?=$go_link?>');
  </script>
  <?php
}
?>
<?php include "includes/footer.php"; ?>