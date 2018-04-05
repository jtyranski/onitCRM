<?php 
include "includes/functions.php";
$prospect_id = $_GET['prospect_id'];
$searchfor = $_GET['searchfor'];
$searchby = $_GET['searchby'];

$order_by = $_GET['order_by'];
$order_by2 = $_GET['order_by2'];
$letter = $_GET['letter'];
if($order_by == "") $order_by = "site_name";
if($order_by2 == "") $order_by2 = "asc";
if($order_by2 == "asc"){
  $function = "compare";
}
else {
  $function = "rcompare";
}

function compare($x, $y)
{
global $order_by;
if ( strtoupper($x[$order_by]) == strtoupper($y[$order_by]) )
 return 0;
else if ( strtoupper($x[$order_by]) < strtoupper($y[$order_by]) )
 return -1;
else
 return 1;
}

function rcompare($x, $y)
{
global $order_by;
if ( strtoupper($x[$order_by]) == strtoupper($y[$order_by]) )
 return 0;
else if ( strtoupper($x[$order_by]) < strtoupper($y[$order_by]) )
 return 1;
else
 return -1;
}

$sql = "SELECT * from properties 
where prospect_id='$prospect_id' and corporate=0 and display=1 and prospect_id != 0 and $searchby like '%$searchfor%'";
$result = executequery($sql);
$counter=0;
while($record = go_fetch_array($result)){
  $property_id = $record['property_id'];
  $sitesqs = 0;
  $sql = "SELECT sqft, grade, roof_type from sections where property_id='$property_id' and display=1";
	$res_sec = executequery($sql);
	while($sections = go_fetch_array($res_sec)){
	  $sitesqs += $sections['sqft'];
	  if($sections['grade'] != "0") $sitesections++;
	  $grade = $sections['grade'];
	  $this_points = $gradevalue[$grade];
	  $sitepoints += $this_points;
	}
  $siteavg = round($sitepoints / $sitesections);
  $siteavg_grade = $gradevalue_reverse[$siteavg];
  
  if($sitesqs == 0){
    $sitesqs = $record['roof_size'];
  }
  else {
    $sitesqs = $sitesqs / 100;
  }
  
  $row[$counter]['property_id'] = $property_id;
  $row[$counter]['site_name'] = stripslashes($record['site_name']);
  $row[$counter]['address'] = stripslashes($record['address']);
  $row[$counter]['city'] = stripslashes($record['city']);
  $row[$counter]['state'] = stripslashes($record['state']);
  $row[$counter]['sitesqs'] = $sitesqs;
  $row[$counter]['roof_type'] = stripslashes($record['roof_type']);
  $row[$counter]['estimated_install'] = stripslashes($record['estimated_install']);
  $row[$counter]['grade'] = $siteavg_grade;
  
  $counter++;
}

usort($row, $function); 

ob_start();
?>
<table class="main" width="100%" cellpadding="2" cellspacing="0">
<tr>
<!--<td><strong>Status</strong></td>-->
<td><input type='checkbox' onchange="SetChecked(this, 'property_ids[]')"></td>
<td><strong>Property</strong></td>
<td><strong>Address</strong></td>
<td><strong>Location</strong></td>
<td><strong>SQS</strong></td>
<?php /*
<td><strong><?=sort_header('estimated_install', 'Estimated Install')?></strong></td>
*/?>
<td><strong>Grade</strong></td>
<td></td>
</tr>
<?php
  
for($x=0;$x<sizeof($row);$x++){
  ?>
  <tr<?php if($x % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td><input type="checkbox" name="property_ids[]" value="<?=$row[$x]['property_id']?>"></td>
  <td>
  <a href="view_property.php?property_id=<?=$row[$x]['property_id']?>" target="_parent">
  <?=stripslashes($row[$x]['site_name'])?>
  </a>
  </td>
  <td>
  <?=$row[$x]['address']?>
  </td>
  <td>
  <?=$row[$x]['city']?>, <?=$row[$x]['state']?>
  </td>
  <td><?=number_format($row[$x]['sitesqs'], 0)?></td>
  <?php /*
  <td><?=$row[$x]['estimated_install']?></td>
  */?>
  <td><?=$row[$x]['grade']?></td>
  <td>
  <?php /*
  <?php if($SESSION_ISADMIN){ ?>
  <a href="javascript:DelProperty('<?=$row[$x]['property_id']?>')">Delete</a>
  <?php } ?>
  */ ?>
  </td>
  </tr>
  <?php
}
?>
</table>
<?php
$html = ob_get_contents();
ob_end_clean();
$html = jsclean($html);
?>

div = document.getElementById('table_div');
div.innerHTML = '<?php echo $html; ?>';