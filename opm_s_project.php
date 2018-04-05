<?php include "opm_s_header.php"; ?>
<?php

$sql = "SELECT project_sqft, property_id, produced_sqft from opm where opm_id='$opm_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$project_sqft = $record['project_sqft'];
$property_id = $record['property_id'];
$produced_sqft = $record['produced_sqft'];

$sql = "SELECT site_name from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);


?>
<div class="main_large">Project Review for <?=$site_name?></div>
<table class="main" cellpadding="8" cellspacing="0">
<tr>
<td><strong>Date of Work</strong></td>
<td><strong>Removed</strong></td>
<td><strong>Replaced</strong></td>
<td><strong>Number of Photos</strong></td>
</tr>
<?php
$total_removed = $total_replaced = $total_photos = 0;
$sql = "SELECT opm_entry_id, removed, replaced, date_format(opm_date, \"%m/%d/%Y\") as opm_date from opm_entry where opm_id='$opm_id' order by opm_date";
$result = executequery($sql);
$counter = 0;
while($record = go_fetch_array($result)){
  $sql = "SELECT count(*) from opm_entry_photos where opm_entry_id = '" . $record['opm_entry_id'] . "'";
  $photos = getsingleresult($sql);
  $counter++;
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"#ececec\"";?>>
  <td>
  <input type="button" name="button<?=$x?>" value="<?=$record['opm_date']?>" onclick="document.location.href='opm_s_entryview.php?xid=<?=$xid?>&opm_entry_id=<?=$record['opm_entry_id']?>'">
  </td>
  <td><?=number_format($record['removed'], 1)?></td>
  <td><?=number_format($record['replaced'], 1)?></td>
  <td><?=$photos?></td>
  </tr>
  <?php
  $total_removed += $record['removed'];
  $total_replaced += $record['replaced'];
  $total_photos += $photos;
}
if($produced_sqft != 0) $total_replaced = $produced_sqft;

$removed_percent = round(($total_removed / $project_sqft) * 100, 1);
$replaced_percent = round(($total_replaced / $project_sqft) * 100, 1);
?>
<tr>
<td><strong>Totals</strong></td>
<td><?=number_format($total_removed, 1)?></td>
<td><?=number_format($total_replaced, 1)?></td>
<td><?=$total_photos?></td>
</tr>
<tr>
<td><strong>% Complete</strong></td>
<td><?=$removed_percent?>%</td>
<td><?=$replaced_percent?>%</td>
<td></td>
</tr>

</table>
  
<?php include "opm_s_footer.php"; ?>