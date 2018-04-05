<?php include "includes/functions.php"; ?>
<?php $property_id = $_GET['property_id']; ?>
<?php
$sql = "SELECT prospect_id from properties where property_id='$property_id'";
$prospect_id = getsingleresult($sql);
?>
<html>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<script>
function loadview(x){
  viewtype = x.value;
  document.location.href="<?=$_SERVER['SCRIPT_NAME']?>?property_id=<?=$property_id?>&view=" + viewtype;
}
</script>

<body bgColor='#FFFFFF' leftMargin=0 topMargin=0>
<div class="main">
<?php
$sql = "SELECT * from opportunities_master";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $opp_product_id = $record['opp_product_id'];
  $opp_name = stripslashes($record['opp_name']);
  $OPP_PRODUCT[$opp_product_id] = $opp_name;
}

$sql = "SELECT * from opportunities_items where master_id='" . $SESSION_MASTER_ID . "' order by opp_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $opp_product_id = $record['opp_product_id'];
  $opp_name = stripslashes($record['opp_name']);
  $OPP_PRODUCT[$opp_product_id] = $opp_name;
}

$user_id = $SESSION_USER_ID;
$view = $_GET['view'];
if($view=="") $view = "All";

$sql = "SELECT count(*) as count, sum(amount) as total from opportunities 
where 1=1 and status='Quoted' and property_id='$property_id' and display=1";
$result = executequery($sql);
$record = go_fetch_array($result);
$count_quoted = $record['count'];
$total_quoted = $record['total'];

$sql = "SELECT count(*) as count, sum(amount) as total from opportunities 
where 1=1 and status='Sold' and property_id='$property_id' and display=1";
$result = executequery($sql);
$record = go_fetch_array($result);
$count_sold = $record['count'];
$total_sold = $record['total'];

$sql = "SELECT count(*) as count, sum(amount) as total from opportunities 
where 1=1 and status='Dead' and property_id='$property_id' and display=1";
$result = executequery($sql);
$record = go_fetch_array($result);
$count_dead = $record['count'];
$total_dead = $record['total'];
?>
<table class="main" width="100%">
<tr>
<td valign="top">
  <table class="main">
  <tr>
  <td><strong>Quoted</strong></td>
  <td>$<?=number_format($total_quoted, 0)?> (<?=$count_quoted?>)</td>
  </tr>
  <tr>
  <td><strong>Sold</strong></td>
  <td>$<?=number_format($total_sold, 0)?> (<?=$count_sold?>)</td>
  </tr>
  <tr>
  <td><strong>Dead</strong></td>
  <td>$<?=number_format($total_dead, 0)?> (<?=$count_dead?>)</td>
  </tr>
  </table>
</td>
<td align="right" valign="top">
<a href="opportunity_edit.php?prospect_id=<?=$prospect_id?>&property_id=<?=$property_id?>&opp_id=new" target="_parent">Add Opportunity</a>
<br>
View: 
<select name="view" onChange="loadview(this)">
<option value="All"<?php if($view=="All") echo " selected";?>>All</option>
<option value="Quoted"<?php if($view=="Quoted") echo " selected";?>>Quoted</option>
<option value="Sold"<?php if($view=="Sold") echo " selected";?>>Sold</option>
<option value="Dead"<?php if($view=="Dead") echo " selected";?>>Dead</option>
</select>
</td>
</tr>
</table>

<table class="main" width="100%" cellpadding="2" cellspacing="0">
<tr>
<td><strong>Date</strong></td>
<td><strong>Status</strong></td>
<td><strong>Property</strong></td>
<td><strong>Manager</strong></td>
<td><strong>Product</strong></td>
<td><strong>Description</strong></td>
<td align="right"><strong>Amount</strong></td>
<td></td>
</tr>
<?php
switch($view){
  case "All":{
    $whereclause = " And 1=1 ";
	break;
  }
  case "Quoted":{
    $whereclause = " And status='Quoted' ";
	break;
  }
  case "Sold":{
    $whereclause = " And status='Sold' ";
	break;
  }
  case "Dead":{
    $whereclause = " And status='Dead' ";
	break;
  }
}
  
$sql = "SELECT *, date_format(lastaction, \"%m/%d/%y\") as datepretty from opportunities 
where 1=1 $whereclause and property_id='$property_id' and display=1 order by lastaction desc";
$result = executequery($sql);
$counter=0;
while($record = go_fetch_array($result)){
  $counter++;
  $sql = "SELECT site_name, corporate from properties where property_id='" . $record['property_id'] . "'";
  $result2 = executequery($sql);
  $record2 = go_fetch_array($result2);
  $site_name = stripslashes($record2['site_name']);
  $corporate = $record2['corporate'];
  
  $sql = "SELECT firstname from users where user_id='" . $record['user_id'] . "'";
  $firstname = stripslashes(getsingleresult($sql));
  $loi_flag = $record['loi_flag'];
  $status = stripslashes($record['status']);
  if($status=="Sold" && $loi_flag) $status = "Sold-LOI";
  $opp_product_id = $record['opp_product_id'];
  
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td valign="top"><?=$record['datepretty']?></td>
  <td valign="top"><?=$status?></td>
  <td valign="top">
  <?=$site_name?>
  </td>
  <td valign="top"><?=$firstname?></td>
  <td valign="top"><?=$OPP_PRODUCT[$opp_product_id]?></td>
  <td valign="top"><?=nl2br(stripslashes($record['description']))?></td>
  <td align="right" valign="top">$<?=number_format($record['amount'], 0)?></td>
  <td align="right" valign="top">
  <?php if($record['user_id']==$SESSION_USER_ID || $SESSION_USER_LEVEL=="Manager"){?>
  <a href="opportunity_edit.php?prospect_id=<?=$prospect_id?>&opp_id=<?=$record['opp_id']?>&property_id=<?=$property_id?>" target="_parent">edit</a> - 
  <a href="opportunity_delete.php?opp_id=<?=$record['opp_id']?>&property_id=<?=$property_id?>" target="_parent">delete</a>
  <?php } ?>
  </td>
  </tr>
  <?php
}
?>
</table>
</div>
</body>
</html>