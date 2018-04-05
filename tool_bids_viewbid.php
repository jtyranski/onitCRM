<?php
include "includes/header_white.php";

$bid_id = go_escape_string($_GET['bid_id']);

?>

<div class="main">

<h2>The following deficiencies have been selected to be repaired in this bid</h2>
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
  <table class="main" width="100%">
  <tr>
  <td>Estimated Repair Cost:</td>
  <td align="right">$<?=number_format($record['cost'], 2)?></td>
  </tr>
  </table>
  <?php if($record['complete']==1){ ?>
  <font color="red">This deficiency has been fixed</font>
  <?php } ?>
  </td>
  </tr>
  </table>
  </td>
  </tr>
  </table>
  <hr size="1">
  <?php
}
?>
</div>