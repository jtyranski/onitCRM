<?php
include "includes/functions.php";

$company = go_escape_string($_GET['company']);


$sql = "SELECT prospect_id, company_name, city, state, zip, 
date_format(lastaction, \"%m/%d/%Y\") as lastaction_pretty, lastaction
from prospects 
where company_name like '%$company%' 
and master_id='" . $SESSION_MASTER_ID . "' and display=1 order by company_name";
$result = executequery($sql);

ob_start();

?>
<table class="main" width="99%" cellpadding="4" cellspacing="0">
<tr>
<td width="50%"><strong>Company</strong></td>
<td width="25%"><strong>City</strong></td>
<td width="10%"><strong>State</strong></td>
<td width="15%"><strong>Zip</strong></td>
</tr>
<?php
$counter = 0;
while($record = go_fetch_array($result)){
  $counter++;
  if($counter % 2){
    $class = "altrow";
  }
  else {
    $class = "mainrow";
  }
  ?>
  <tr class="<?=$class?>">
  <td><a href='view_company.php?prospect_id=<?=$record['prospect_id']?>' target="_top"><?=stripslashes($record['company_name'])?></a></td>
  <td><?=stripslashes($record['city'])?></td>
  <td><?=stripslashes($record['state'])?></td>
  <td align="left"><?=stripslashes($record['zip'])?></td>
  </tr>
  <?php
}
?>
</table>
<?php
$html = ob_get_contents();
ob_end_clean();
$html = jsclean($html);
?>
div = document.getElementById('search_results');
div.innerHTML = '<?php echo $html; ?>';
  