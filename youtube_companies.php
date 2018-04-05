<?php
include "includes/functions.php";

$master_id = $_GET['master_id'];
$video_id = $_GET['video_id'];
if($video_id != "new"){
  $sql = "SELECT prospect_id from videos_embed where video_id='$video_id'";
  $prospect_id = getsingleresult($sql);
}

ob_start();
?>
<select name="prospect_id">
<?php
$sql = "SELECT prospect_id, company_name from prospects where display=1 and master_id='$master_id' order by company_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['prospect_id']?>"<?php if($prospect_id==$record['prospect_id']) echo " selected";?>><?=stripslashes($record['company_name'])?></option>
  <?php
}
?>
</select>
<?php

$html = ob_get_contents();
ob_end_clean();
$html = jsclean($html);

?>

div = document.getElementById('prospectarea');
div.innerHTML = '<?php echo $html; ?>';