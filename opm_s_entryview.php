<?php include "opm_s_header.php"; ?>
<?php
$opm_entry_id = go_escape_string($_GET['opm_entry_id']);
$sql = "SELECT opm_id from opm_entry where opm_entry_id=\"$opm_entry_id\"";
$test = getsingleresult($sql);
if($test != $opm_id) exit;

$sql = "SELECT project_sqft, property_id from opm where opm_id='$opm_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$project_sqft = $record['project_sqft'];
$property_id = $record['property_id'];

$sql = "SELECT site_name from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);

$sql = "SELECT *, date_format(opm_date, \"%m/%d/%Y\") as work_date_pretty from opm_entry where opm_entry_id='$opm_entry_id'";
$result = executequery($sql);
$record = go_fetch_array($result);

$work_date_pretty = stripslashes($record['work_date_pretty']);
$comments = stripslashes(nl2br($record['comments']));
$removed = $record['removed'];
$replaced = $record['replaced'];

?>
<script type="text/javascript" language="javascript" src="lytebox/lytebox.js"></script>

<link rel="stylesheet" href="lytebox/lytebox.css" type="text/css" media="screen" />
<strong>Work for <?=$site_name?> on <?=$work_date_pretty?></strong>
<br>

<table class="main">
<tr>
<td>Removed</td>
<td><?=$removed?></td>
<td>sqft</td>
</tr>
<tr>
<td>Replaced</td>
<td><?=$replaced?></td>
<td>sqft</td>
</tr>
</table>

<table class="main">
<tr>
<td>
<?=$comments?>
</td>
</tr>
</table>

<p style='text-align:center;'>(Click on any photo for a larger view)</p>
<table class="main">
<?php
$counter = 0;
$pic_counter = 0;
$sql = "SELECT * from opm_entry_photos where opm_entry_id='$opm_entry_id' and photo != '' order by photo_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  if($counter == 0) echo "<tr>\n";
  $pic_counter++;
  $description=stripslashes($record['description']);
	$description = go_reg_replace("\"", "&quot;", $description);
  ?>
  <td valign="top" align="center">
  <strong><?=stripslashes($record['description'])?></strong>
  <br>
  <a href="uploaded_files/opm_photos/<?=$record['photo']?>" rel="lytebox[testcuts]" title="<?=$description?>">
  <img src="uploaded_files/opm_photos/<?=$record['photo']?>" width="350" border="0">
  </a>
  </td>
  <?php
  $counter++;
  if($counter==2){
    echo "</tr>\n";
	$counter = 0;
  }
}
?>
</table>

<?php include "opm_footer.php"; ?>
