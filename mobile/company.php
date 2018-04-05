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
    $whereclause = " And property_type like '%Beazer%' ";
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
  case "Contractor":{
    $whereclause = " And property_type like '%Contractor%' ";
	break;
  }
}
$sql = "SELECT count(*) from prospects where 1=1 $whereclause and display=1 and master_id='" . $SESSION_MASTER_ID . "'";
$prospect_count = getsingleresult($sql);
$total = $prospect_count;
?>
<table class="main" width="100%">
<tr>
<td><strong>Company</strong></td>
<td><strong>Location</strong></td>
</tr>
<?php
$sql = "SELECT prospect_id, company_name, city, state from prospects where 1=1 $whereclause and display=1 and master_id='" . $SESSION_MASTER_ID . "' order by company_name limit $start, $limit";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $company_name = stripslashes($record['company_name']);
  if(strlen($company_name) > 17) $company_name = substr($company_name, 0, 17) . "..";
  $city = stripslashes($record['city']);
  if(strlen($city) > 12) $city = substr($city, 0, 12) . "..";
  ?>
  <tr>
  <td><a href="company_details.php?prospect_id=<?=$record['prospect_id']?>"><?=$company_name?></a></td>
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