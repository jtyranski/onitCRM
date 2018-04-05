<?php include "includes/header_white.php"; ?>
<?php
$STAGES[1] = "New";
$STAGES[2] = "Bids Out";
$STAGES[3] = "Bid Accepted";
?>
<div class="main">

<table class="main" cellpadding="3" cellspacing="0" width="100%">
<tr>
<td><strong>Bid ID</strong></td>
<td><strong>Company</strong></td>
<td><strong>Property</strong></td>
<td><strong>Date Created</strong></td>
<td><strong>Stage</strong></td>
<td><strong>Options</strong></td>
<td><strong>View</strong></td>
<td><strong>Contractors</strong></td>
</tr>
<?php
$counter = 0;
$sql = "SELECT a.bid_id, date_format(a.create_date, \"%m/%d/%Y\") as create_date, a.property_id, b.site_name, b.prospect_id, c.company_name, a.stage from
bids a, properties b, prospects c where
a.property_id=b.property_id and
b.prospect_id = c.prospect_id and
c.master_id='" . $SESSION_MASTER_ID . "' and 
b.display=1 and c.display=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $counter++;
  $stage = $record['stage'];
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td><?=$record['bid_id']?></td>
  <td><a href="view_company.php?prospect_id=<?=$record['prospect_id']?>" target="_top"><?=stripslashes($record['company_name'])?></a></td>
  
  <td><a href="view_property.php?property_id=<?=$record['property_id']?>" target="_top"><?=stripslashes($record['site_name'])?></a></td>
  
  <td><?=$record['create_date']?></td>
  
  <td><?=$STAGES[$stage]?></td>
  
  <td><a href="tool_bids_bidinfo.php?bid_id=<?=$record['bid_id']?>">Options</a></td>
  
  <td><a href="tool_bids_viewbid.php?bid_id=<?=$record['bid_id']?>">View</a></td>
  
  <td><a href="tool_bids_contractors.php?bid_id=<?=$record['bid_id']?>">Contractors</a></td>
  
  </tr>
  <?php
}
?>
</table>

</div>