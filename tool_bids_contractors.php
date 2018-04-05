<?php
include "includes/header_white.php";

$bid_id = go_escape_string($_GET['bid_id']);

$sql = "SELECT winning_prospect_id from bids where bid_id='$bid_id'";
$winning_prospect_id = getsingleresult($sql);


$invites = array();
$sql = "SELECT prospect_id from bids_to_invites where bid_id='$bid_id'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $invites[] = $record['prospect_id'];
}

?>
<div class="main">
<div style="width:100%; position:relative;">
<div style="float:left; width:20%;">
<form action="tool_bids_contractors_action.php" method="post">
<input type="hidden" name="bid_id" value="<?=$bid_id?>">
<h2>Invited Contractors for this Bid</h2>
<br>
<input type="submit" name="submit1" value="Invite Selected Contractors">
<br><br>
<div class="main" style="height:500px; overflow:auto; width:100%; border:1px solid black;">
<?php 
$sql = "SELECT prospect_id, company_name from prospects where industry=1 and master_id='" . $SESSION_MASTER_ID . "' order by company_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  if(in_array($record['prospect_id'], $invites)){
    ?>
	<input type="checkbox" name="prospect_ids[]" value="<?=$record['prospect_id']?>" checked="checked" disabled="disabled"><font color="#006633"><strong><?=stripslashes($record['company_name'])?></strong></font><br>
	<?php
  }
  else {
    ?>
	<input type="checkbox" name="prospect_ids[]" value="<?=$record['prospect_id']?>"><?=stripslashes($record['company_name'])?><br>
	<?php
  }
}
?>
</div>

</form>
</div>

<div style="float:left; width:2%;">&nbsp;</div>

<div style="float:left; width:78%; height:500px;">

<div style="overflow:auto; height:100%;">
<?php
$bulk_ind = "ind";

$firstwidth = 300;
$eachwidth = 150;

$sql = "SELECT a.prospect_id, a.bidlock, a.code, b.company_name from bids_to_invites a, prospects b where 
a.prospect_id = b.prospect_id and a.bid_id=\"$bid_id\" order by b.company_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $CONT_ID[] = $record['prospect_id'];
  $CONT_NAME[] = stripslashes($record['company_name']);
  $CONT_CODE[] = stripslashes($record['code']);
  $CONT_LOCK[] = stripslashes($record['bidlock']);
}

$sql = "SELECT rs_id, rs_name from bids_to_roofsystem where bid_id='" . $bid_id . "'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $RS_ID[] = $record['rs_id'];
  $RS_NAME[] = stripslashes($record['rs_name']);
}
?>
<div style="position:relative;">
<div style="float:left; width:<?=$firstwidth?>px;">&nbsp;</div>
<?php for($y=0;$y<sizeof($CONT_ID);$y++){?>
<div style="float:left; width:<?=$eachwidth?>px;">
<a href="view_company.php?prospect_id=<?=$CONT_ID[$y]?>" target="_top"><?=$CONT_NAME[$y]?></a>
</div>
<?php } ?>
</div>
<div style="clear:both;"></div>

<div style="position:relative;">
<div style="float:left; width:<?=$firstwidth?>px;">&nbsp;</div>
<?php for($y=0;$y<sizeof($CONT_ID);$y++){?>
<div style="float:left; width:<?=$eachwidth?>px;">
<a href="<?=$FCS_URL?>bids/bid.php?code=<?=$CONT_CODE[$y]?>" target="_blank">Place bid</a>
</div>
<?php } ?>
</div>
<div style="clear:both;"></div>

<div style="position:relative;">
<div style="float:left; width:<?=$firstwidth?>px;">&nbsp;</div>
<?php for($y=0;$y<sizeof($CONT_ID);$y++){?>
<div style="float:left; width:<?=$eachwidth?>px;">
<?php
if($CONT_LOCK[$y]==1){
?>
<a href="tool_bids_contractors_lock.php?bid_id=<?=$bid_id?>&cont_id=<?=$CONT_ID[$y]?>" style="color:red;">LOCKED</a>
<?php } else { ?>
<a href="tool_bids_contractors_lock.php?bid_id=<?=$bid_id?>&cont_id=<?=$CONT_ID[$y]?>" style="color:#009933;">UNLOCKED</a>
<?php } ?>
</div>
<?php } ?>
</div>
<div style="clear:both;"></div>

<?php
if($bulk_ind=="ind"){
  $sql = "SELECT a.section_id, b.section_name from bids_to_sections a, sections b where a.section_id=b.section_id and a.bid_id='$bid_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    ?>
	<div><strong><?=stripslashes($record['section_name'])?></strong></div>
	<?php for($x=0;$x<sizeof($RS_ID);$x++){?>
	<div style="position:relative;">
	<div style="float:left; width:<?=$firstwidth?>px;"><?=$RS_NAME[$x]?></div>
	  <?php for($y=0;$y<sizeof($CONT_ID);$y++){?>
	  <?php
	  $sql = "SELECT bid_amount from bids_to_roofsystem_bid_ind where bid_id='$bid_id' and rs_id='" . $RS_ID[$x] . "' and prospect_id='" . $CONT_ID[$y] . "' and section_id='" . $record['section_id'] . "'";
	  $bid_amount = getsingleresult($sql);
	  $bid_amount = number_format($bid_amount, 2);
	  ?>
	  <div style="float:left; width:<?=$eachwidth?>px;">$<?=$bid_amount?></div>
	  <?php
	  }
	  ?>
	</div>
	<div style="clear:both;"></div>
	<?php
	}
	?>
	<div style="height:10px;">&nbsp;</div>
	<?php
  }
} // end if ind
else { // bulk
    ?>
    <div><strong>All Sections</strong></div>
	<?php for($x=0;$x<sizeof($RS_ID);$x++){?>
	<div style="position:relative;">
	<div style="float:left; width:150px;"><?=$RS_NAME[$x]?></div>
	  <?php for($y=0;$y<sizeof($CONT_ID);$y++){?>
	  <?php
	  $sql = "SELECT bid_amount from bids_to_roofsystem_bid where bid_id='$bid_id' and rs_id='" . $RS_ID[$x] . "' and prospect_id='" . $CONT_ID[$y] . "'";
	  $bid_amount = getsingleresult($sql);
	  $bid_amount = number_format($bid_amount, 2);
	  ?>
	  <div style="float:left; width:100px;">$<?=$bid_amount?></div>
	  <?php
	  }
	  ?>
	</div>
	<div style="clear:both;"></div>
	<?php
	}
	?>
	<div style="height:10px;">&nbsp;</div>
	<?php
} // end bulk
?>




</div>
</div>
</div>
<div style="clear:both;"></div>


</div>