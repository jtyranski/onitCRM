<?php include "includes/header.php"; ?>
<?php  // this is just for fcs login ?>
<div align="center">
  <div class="whiteround" style="height:800px;">
  <div align="left">
<?php

$master_id = $_GET['master_id'];

$sql = "SELECT master_name from master_list where master_id='$master_id'";
$master_name = stripslashes(getsingleresult($sql));

?>
<script>

function ShowHide(id, x){
  if(x==1){
    document.getElementById("arrow_" + id).innerHTML = "<a href=\"javascript:ShowHide('" + id + "', '0')\" style=\"text-decoration:none;\">-</a>";
	document.getElementById("props_" + id).style.display = "";
  }
  else {
    document.getElementById("arrow_" + id).innerHTML = "<a href=\"javascript:ShowHide('" + id + "', '1')\" style=\"text-decoration:none;\">+</a>";
	document.getElementById("props_" + id).style.display = "none";
  }
}

function DelProp(id){
  document.location.href = "fcs_contractors_delproperty.php?master_id=<?=$master_id?>&property_id=" + id;
}

function DelCompany(id){
  document.location.href = "fcs_contractors_delcompany.php?master_id=<?=$master_id?>&prospect_id=" + id;
}

</script>
Properties for <?=$master_name?><br>
<div style="height:700px; overflow:auto;">
<?php
$sql = "SELECT prospect_id, company_name from prospects where master_id='$master_id' and display=1 order by company_name";
$result = executequery($sql);
$counter = 0;
while($record = go_fetch_array($result)){
  $counter++;
  ?>
  <div style="width:100%; padding:3px 3px 3px 3px;<?php if($counter % 2) echo "background-color:$ALT_ROW_COLOR;";?>">
    <span id="arrow_<?=$record['prospect_id']?>"><a href="javascript:ShowHide('<?=$record['prospect_id']?>', '1')" style="text-decoration:none;">+</a></span>
	<?=stripslashes($record['company_name'])?>
	<a href="javascript:DelCompany('<?=$record['prospect_id']?>')">delete</a>
	<br>
	<div id="props_<?=$record['prospect_id']?>" style="width:100%; display:none;">
	<table class="main" cellpadding="4">
	<?php
	$sql = "SELECT property_id, site_name from properties where prospect_id='" . $record['prospect_id'] . "' and display=1 
	and corporate=0 order by site_name";
	$prop_res = executequery($sql);
	while($prop = go_fetch_array($prop_res)){
	  ?>
	  <tr>
	  <td><?=stripslashes($prop['site_name'])?></td>
	  <td><a href="javascript:DelProp('<?=$prop['property_id']?>')">delete</a></td>
	  </tr>
	  <?php
	}
	?>
	</table>
	</div>
  </div>
  <?php
}
?>
</div>

</div>
</div>
</div>
<?php include "includes/footer.php"; ?>