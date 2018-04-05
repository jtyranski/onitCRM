<?php
include "includes/header_white.php";
$bid_id = go_escape_string($_GET['bid_id']);
$prospect_id = go_escape_string($_GET['prospect_id']);

$sql = "SELECT bid, notes from bids_to_invites where bid_id=\"$bid_id\" and prospect_id=\"$prospect_id\"";
$result = executequery($sql);
$record = go_fetch_array($result);
$bid = stripslashes($record['bid']);
$notes = stripslashes($record['notes']);

$sql = "SELECT company_name from prospects where prospect_id=\"$prospect_id\"";
$company_name = stripslashes(getsingleresult($sql));

$sql = "SELECT winning_prospect_id from bids where bid_id=\"$bid_id\"";
$winning_prospect_id = getsingleresult($sql);

?>
<div class="main">
<a href="tool_bids_contractors.php?bid_id=<?=$bid_id?>">Return to Contractor List for this Bid</a><br><br>
<h2>Bid Information for <?=$company_name?></h2>
<br>

Bid: $<?=number_format($bid, 2)?>
<br><br>
Notes:
<?=nl2br($notes)?>
<br><br>
<?php if($winning_prospect_id ==0){ ?>
  <form action="tool_bids_viewcontractorbidinfo_action.php" method="post">
  <input type="hidden" name="bid_id" value="<?=$bid_id?>">
  <input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
  <input type="submit" name="submit1" value="Award Bid to this Contractor">
  </form>
<?php } else { 
  if($prospect_id==$winning_prospect_id){
    echo "<strong>This bid has been awarded to this contractor</strong>";
  }
  else {
    echo "<strong>This bid has been awarded to a different contractor</strong>";
  }
}
?>
</div>
  
