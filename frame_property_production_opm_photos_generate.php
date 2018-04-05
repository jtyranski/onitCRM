<?php
include "includes/functions.php";

$opm_entry_id = go_escape_string($_GET['opm_entry_id']);

ob_start();
?>
<table width="100%" cellpadding="3" cellspacing="0">
<?php
$sql = "SELECT photo_id, photo, description from opm_entry_photos where opm_entry_id='$opm_entry_id'";
$result = executequery($sql);
$counter = 0;
while($record = go_fetch_array($result)){
  if($counter==0) echo "<tr>\n";
  $description = stripslashes($record['description']);
  $title_desc = $description;
  $title_desc = go_reg_replace("\"", "&quot;", $title_desc);
  if($description=="") $description = "ENTER DESCRIPTION";
  ?>
  <td>
  <a href="uploaded_files/opm_photos/<?=$record['photo']?>" rel="lytebox[photos]" title="<?=$title_desc?>">
  <img src="uploaded_files/opm_photos/<?=$record['photo']?>" border="0" width="150">
  </a>
  <br>
  <span id="<?=$record['photo_id']?>"><a href="javascript:editField('<?=$record['photo_id']?>')" class="blankclick_red"><?=$description?></a></span><br>
  <br>
  <a href="javascript:DelPhoto('<?=$record['photo_id']?>')">Delete</a>
  </td>
  <?php
  $counter++;
  if($counter==6){
    echo "</tr>\n";
	$counter=0;
  }
}
?>
</table>
<?php
$html = ob_get_contents();
ob_end_clean();

$html = jsclean($html);
?>
div = document.getElementById('photo_area');
div.innerHTML = '<?php echo $html; ?>';