<?php include "includes/functions.php"; ?>
<?php
$property_id = $_GET['property_id'];
$dis_id=$_GET['dis_id'];
if($dis_id=="") $dis_id = 1;
?>
<html>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<body bgColor='#FFFFFF' leftMargin=0 topMargin=0>
<script>
function loadview(x){
  viewtype = x.value;
  document.location.href="<?=$_SERVER['SCRIPT_NAME']?>?view=" + viewtype;
}

function DelSection(x){
  cf = confirm("Are you sure you want to delete this section?");
  if(cf){
    document.location.href = "frame_property_sections_delete.php?property_id=<?=$property_id?>&section_id=" + x;
  }
}

</script>
<div class="main">
<?php
$view = $_GET['view'];

if($view=="") $view = "All";

$order_by = $_GET['order_by'];
$order_by2 = $_GET['order_by2'];
$letter = $_GET['letter'];
if($order_by == "") $order_by = "map_reference";
if($order_by2 == "") $order_by2 = "asc";
if($order_by2 == "asc"){
  $function = "compare";
}
else {
  $function = "rcompare";
}
$order_clause = " order by $order_by $order_by2";
if($order_by=="state") $order_clause .= ", city";

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


$sql = "SELECT count(*) as count, sum(sqft) as totalsqft from sections where property_id='$property_id' and display=1 
and dis_id='$dis_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$totalsections = $record['count'];
$totalsqft = $record['totalsqft'];


$counter = 0;
  
	
  $sql = "SELECT section_id, sqft, grade, roof_type, section_name, map_reference, include_in_report from sections where property_id='$property_id' 
  and display=1 and dis_id='$dis_id'";
  $res_sec = executequery($sql);
  while($sections = go_fetch_array($res_sec)){
	$sitesections++;
	$sqft = $sections['sqft'];
	$section_id = $sections['section_id'];
	$grade = $sections['grade'];
	$roof_type = stripslashes($sections['roof_type']);
	$section_name = stripslashes($sections['section_name']);
	$map_reference = stripslashes($sections['map_reference']);
	$include_in_report = stripslashes($sections['include_in_report']);
	if($grade=="0") $grade = "NA";
	$row[$counter]['section_id'] = $section_id;
    $row[$counter]['section_name'] = $section_name;
    $row[$counter]['sitesqft'] = $sqft;
    $row[$counter]['siteavg_grade'] = $grade;
    $row[$counter]['siteavg'] = $grade;
    $row[$counter]['roof_type'] = $roof_type;
	$row[$counter]['map_reference'] = $map_reference;
	$row[$counter]['include_in_report'] = $include_in_report;
    $counter++;
  }
  
usort($row, $function);
?>
<table class="main" width="100%">
<tr>
<td valign="top">
Total Sections: <?=$totalsections?><br>
Total Sq/Ft: <?=number_format($totalsqft, 0)?>
</td>
<td align="right" valign="top">

<a href="frame_property_section_add.php?property_id=<?=$property_id?>&section_id=new&dis_id=<?=$dis_id?>">Add section</a>
<br>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="form1">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<select name="dis_id" onChange="document.form1.submit()">
<?php
if(is_array($SESSION_DIS)){
  $sql = "SELECT dis_id, discipline from disciplines order by dis_id";
  $result = executequery($sql);
  $DIS_NAMES = array();
  while($record = go_fetch_array($result)){
    $xdis_id = $record['dis_id'];
	$DIS_NAMES[$xdis_id] = stripslashes($record['discipline']);
  }
  
  for($x=0;$x<sizeof($SESSION_DIS);$x++){ 
    $xdis_id = $SESSION_DIS[$x];?>
    <option value="<?=$xdis_id?>"<?php if($dis_id==$xdis_id) echo " selected";?>><?=$DIS_NAMES[$xdis_id]?></option>
	<?php
  }
}
?>
</select>
</form>
</td>
</tr>
</table>
<hr size="1" color="#9a000c">
<form action="frame_property_sections_action.php" method="post">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<table class="main" width="100%" cellpadding="2" cellspacing="0">
<tr>
<td></td>
<td><strong><?=sort_header('map_reference', "Map")?></strong></td>
<td><strong><?=sort_header('section_name', "Name")?></strong></td>
<td><strong><?=sort_header('sitesqft', "Sq/Ft")?></strong></td>
<?php /*<td><strong><?=sort_header('roof_type', "Type")?></strong></td>*/?>
<td><strong><?=sort_header('siteavg', "Grade")?></strong></td>
<td></td>
</tr>
<?php

for($x=0;$x<sizeof($row);$x++){
  ?>
  <input type="hidden" name="section_id[]" value="<?=$row[$x]['section_id']?>">
  <tr<?php if($x % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td><input type="checkbox" name="include_in_report[]" value="<?=$row[$x]['section_id']?>"<?php if($row[$x]['include_in_report']==1) echo " checked";?>></td>
  <td><input type="text" size="1" name="map_reference[]" value="<?=stripslashes($row[$x]['map_reference'])?>"></td>
  <td><?=$row[$x]['section_name']?></td>
  
  <td><?=number_format($row[$x]['sitesqft'], 0)?></td>
  <?php /*<td><?=stripslashes($row[$x]['roof_type'])?></td>*/?>
  <td><?=stripslashes($row[$x]['siteavg_grade'])?></td>
  <td>
  <?php if($SESSION_ISADMIN){ ?>
  <a href="javascript:DelSection('<?=$row[$x]['section_id']?>')">delete</a>
  <?php } ?>
  </td>
  </tr>
  <?php
}
?>
<tr>
<td colspan="5">
<input type="submit" name="submit1" value="Update Building Map References">
<input type="submit" name="submit1" value="Include Checked Sections in Report">
</td>
</tr>
</table>
</form>
</div>
</body>
</html>