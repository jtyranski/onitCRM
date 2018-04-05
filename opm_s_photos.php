<?php include "opm_s_header.php";?>
<script type="text/javascript" language="javascript" src="lytebox/lytebox.js"></script>

<link rel="stylesheet" href="lytebox/lytebox.css" type="text/css" media="screen" />
<?php
/*
$sql = "SELECT b.site_name from opm a, properties b, sections c where 
a.section_id = c.section_id and c.property_id = b.property_id and a.opm_id='$opm_id'";
$site_name = stripslashes(getsingleresult($sql));
*/
$sql = "SELECT project_sqft, property_id from opm where opm_id='$opm_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$project_sqft = $record['project_sqft'];
$property_id = $record['property_id'];

$sql = "SELECT site_name from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);

$pic_counter = 0;
$sql = "SELECT opm_entry_id, date_format(opm_date, \"%m/%d/%Y\") as work_date_pretty from 
opm_entry where opm_id='$opm_id' order by opm_date";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $work_date_pretty = stripslashes($record['work_date_pretty']);
  ?>
  <table class="main">
  <tr>
  <td><strong><?=$site_name?></strong></td>
  </tr>
  </table>
  <table class="main">
  <?php
  $opm_entry_id = $record['opm_entry_id'];
  $counter = 0;
  $sql = "SELECT * from opm_entry_photos where opm_entry_id='$opm_entry_id' order by photo_id";
  $result2 = executequery($sql);
  while($record2 = go_fetch_array($result2)){
    if($counter == 0) echo "<tr>\n";
	$pic_counter++;
	$description=stripslashes($record2['description']);
	$description = go_reg_replace("\"", "&quot;", $description);
    ?>
    <td valign="top" align="center">
    <strong><?=stripslashes($record2['description'])?> <?=$work_date_pretty?></strong>
    <br>
    <a href="uploaded_files/opm_photos/<?=$record2['photo']?>" rel="lytebox[testcuts]" title="<?=$description?> <?=$work_date_pretty?>">
    <img src="uploaded_files/opm_photos/<?=$record2['photo']?>" width="350" border="0">
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
  <?php
}
?>
<?php include "opm_footer.php"; ?>