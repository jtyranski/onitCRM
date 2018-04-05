<?php
include "includes/header_white.php";

$bid_id = go_escape_string($_GET['bid_id']);

$sql = "SELECT b.site_name, c.company_name from bids a, properties b, prospects c where a.property_id=b.property_id and b.prospect_id=c.prospect_id and a.bid_id='$bid_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);
$company_name = stripslashes($record['company_name']);
?>
<div class="main">
<h2>
Bid #<?=$bid_id?> 
<?=$company_name?> - <?=$site_name?>
</h2>
<a href="tool_bids.php">return to list</a><br>

<form action="tool_bids_bidinfo_action.php" method="post">
<input type="hidden" name="bid_id" value="<?=$bid_id?>">
<div class="main">
<strong>Bid Systems</strong><br>
Enter in the different options you would like the contractors to bid on, i.e. Repairs Only, 60mil Fully Adhered TPO - 20yr, 80mil Fully Adhered PVC (KEE) - 25yr, etc.
<br><br>
<?php
$sql = "SELECT * from bids_to_roofsystem where bid_id=\"$bid_id\" order by rs_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <input type="hidden" name="rs_id[]" value="<?=$record['rs_id']?>">
  <input type="text" name="rs_name[]" value="<?=stripslashes($record['rs_name'])?>" size="40">
  <br>
  <?php
}
?>
<input type="text" name="new_rs_name" size="40">
<input type="submit" name="submit1" value="Add New System">


<br><br>
<input type="submit" name="submit1" value="Update Values">
</div>
</form>