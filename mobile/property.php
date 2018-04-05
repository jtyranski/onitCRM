<?php include "includes/header.php"; ?>
<?php
$limit = 150;
$start = $_GET['start'];
if($start=="")$start = 0;

$view = $_GET['view'];
if($view=="") $view = "All";
?>
<script>
function loadview(x){
  y = x.value;
  document.location.href="<?=$_SERVER['SCRIPT_NAME']?>?view=" + y;
}
</script>
<table class="main" width="100%">
<tr>
<td valign="top">
<?php include "includes/main_nav.php"; ?>
</td>
<td align="right" valign="top">

</td>
</tr>
</table>
<?php


switch($view){
  case "All":{
    $whereclause = " And 1=1 ";
	break;
  }
  case "Beazer":{
    $whereclause = " And property_type like '%Beazer' ";
	break;
  }
  case "Manville":{
    $whereclause = " And (property_type like '%Manville%' or property_type like '%Beazer B%') ";
	break;
  }
  case "Non-PFRI":{
    $whereclause = " And property_type like '%Non-PFRI%' ";
	break;
  }
}
$sql = "SELECT count(a.property_id) from properties a, prospects b where 1=1 and a.display=1 and a.corporate=0 and b.display=1 and a.prospect_id=b.prospect_id 
and b.master_id='" . $SESSION_MASTER_ID . "' $whereclause";
$prospect_count = getsingleresult($sql);
$total = $prospect_count;
?>
<table class="main" width="100%">
<tr>
<td><strong>Property</strong></td>
<td><strong>Location</strong></td>
</tr>
<?php
$sql = "SELECT a.property_id, a.site_name, a.city, a.state from properties a, prospects b where a.corporate=0 $whereclause and a.display=1 and b.display=1 and a.prospect_id=b.prospect_id 
and b.master_id='" . $SESSION_MASTER_ID . "' order by site_name limit $start, $limit";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $site_name = stripslashes($record['site_name']);
  if($site_name=="") $site_name = "(None)";
  if(strlen($site_name) > 17) $site_name = substr($site_name, 0, 17) . "..";
  $city = stripslashes($record['city']);
  if(strlen($city) > 12) $city = substr($city, 0, 12) . "..";
  ?>
  <tr>
  <td><a href="property_details.php?property_id=<?=$record['property_id']?>"><?=$site_name?></a></td>
  <td><?=$city?>, <?=$record['state']?></td>
  </tr>
  <?php
}
?>
</table>
<table class="main" width="100%">
<tr>
<td valign="top">
<?php if($start > 0) { 
  $newstart = $start - $limit;?>
<a href="<?=$_SERVER['SCRIPT_NAME']?>?view=<?=$view?>&start=<?=$newstart?>">Prev</a>
<?php } ?>
</td>
<td>
<?php
$q = ceil($total / $limit);
//echo $total . "&nbsp;";
for($foo=1;$foo<=$q;$foo++){
  $bold=0;
  $n_start = ($foo - 1) * $limit;
  if($start == $n_start) $bold = 1;
  $n_next = ($limit * ($foo - 1));
  ?>
  &nbsp;<a href="<?=$_SERVER['SCRIPT_NAME']?>?view=<?=$view?>&start=<?=$n_next?>"><?php if($bold) echo "<strong>"; ?><?=$foo?><?php if($bold) echo "</strong>"; ?></a>
  <?php
}
?>
</td>

<td valign="top" align="right">
<?php if(($start + $limit) < $total) { 
  $newstart = $start + $limit;?>
<a href="<?=$_SERVER['SCRIPT_NAME']?>?view=<?=$view?>&start=<?=$newstart?>">Next</a>
<?php } ?>
</td>
</tr>
</table>
<?php include "includes/footer.php"; ?>