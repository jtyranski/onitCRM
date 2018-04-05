<?php
include "includes/header_white.php";

$code = $_SERVER['QUERY_STRING'];

$sql = "SELECT bid_id, prospect_id, bid from bids_to_invites where code=\"$code\"";
$result = executequery($sql);
$record = go_fetch_array($result);
$bid_id = $record['bid_id'];
$prospect_id = $record['prospect_id'];
$bid = $record['bid'];

if($bid_id == ""){
  echo "There was an error accessing the bid information.  Please check to make sure the link is correct.";
  exit;
}

$sql = "SELECT d.logo, d.master_name, b.city, b.state, b.zip from 
bids a, properties b, prospects c, master_list d where
a.bid_id='$bid_id' and 
a.property_id=b.property_id and 
b.prospect_id = c.prospect_id and 
c.master_id = d.master_id";
$result = executequery($sql);
$record = go_fetch_array($result);
$logo = stripslashes($record['logo']);
$master_name = stripslashes($record['master_name']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$zip = stripslashes($record['zip']);

$sql = "SELECT company_name, logo from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company_logo = stripslashes($record['logo']);
$company_name = stripslashes($record['company_name']);
?>

<div class="main">

<div style="width:100%; position:relative;">
<div style="float:left;">
<?php
if($logo != ""){ ?>
<img src="uploaded_files/master_logos/<?=$logo?>">
<?php } else { ?>
<h2><?=$master_name?></h2>
<?php } ?>
</div>
<div style="float:right;">
<?php
if($company_logo != ""){ ?>
<img src="uploaded_files/logos/<?=$company_logo?>">
<?php } else { ?>
<h2><?=$company_name?></h2>
<?php } ?>
</div>
</div>
<div style="clear:both;"></div>

<h3>The following deficiencies have been selected to be repaired in this bid</h3>
<br>

<?php
$sql = "SELECT def_id from bids_to_def where bid_id='$bid_id' order by def_id";
$result_main = executequery($sql);
while($record_main = go_fetch_array($result_main)){
  $def_id = $record_main['def_id'];
  $sql = "SELECT * from sections_def where def_id='$def_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  
  ?>
  <table class="main">
  <tr>
  <td valign="top">
  <?php if($record['photo'] != ""){ ?>
  <?php
  $size = getimagesize($CORE_URL . "uploaded_files/def/" . $record['photo']);
  $width = $size[0];
  if($width > 580) $width = 580;
  ?>
  <img src="<?=$CORE_URL?>uploaded_files/def/<?=$record['photo']?>" width="<?=$width?>">
  <?php } ?>
  </td>
  <td valign="top">
  <strong><?=stripslashes($record['name'])?></strong>
  <br><br>
  <?php if($record['def_type']=="R") {
    echo "<strong>Remedial</strong>";
  }
  else{
    echo "<strong>Emergency</strong>";
  }
  ?>
  <br><br>
  <?php if($record['quantity'] != ""){ ?>
  Quantity: <?=$record['quantity']?> <?=$record['quantity_unit']?><br><br>
  <?php } ?>

  <strong>Deficiency:</strong><br>
  <?=stripslashes(nl2br($record['def']))?><br><br>
  <strong>Corrective Action:</strong><br>
  <?=stripslashes(nl2br($record['action']))?><br><br>

  </td>
  </tr>
  </table>
  <hr size="1">
  <?php
}
?>
<script>
function checkform(f){
  var errmsg = "";
  
  if(f.bid.value=="" || f.bid.value==0){ errmsg = "Please place a value for your bid.\n";}
  
  if(errmsg==""){
    return true;
  }
  else {
    alert(errmsg);
	return false;
  }
}
</script>
<?php if($bid != 0){ ?>
Thank you for placing your bid on this project!
<?php } else { ?>
<form action="public_view_bid_action.php" method="post" onsubmit="return checkform(this)">
<input type="hidden" name="code" value="<?=$code?>">
Please enter bid amount: $
<input type="text" name="bid">
<br><br>
Any additional notes or comments regarding your bid:<br>
<textarea name="notes" rows="4" cols="60"></textarea><br><br>
<input type="submit" name="submit1" value="Submit Bid">
</form>
<?php } ?>
</div>