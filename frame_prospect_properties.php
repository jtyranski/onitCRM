<?php include "includes/functions.php"; ?>
<?php $prospect_id = $_GET['prospect_id']; ?>
<?php
$searchby = $_GET['searchby'];
$search = $_GET['search'];
?>
<html>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<script>
function DelProperty(x){
  cf = confirm("Are you sure you want to delete this property?");
  if(cf){
    document.location.href="frame_prospect_delproperty.php?redirect=frame_prospect_properties.php&prospect_id=<?=$prospect_id?>&property_id=" + x;
  }
}

function goForm(action){
  if(action=="delete"){
    var count=0;
    for(x=0; x<document.propertyform.elements["property_ids[]"].length; x++){
        if(document.propertyform.elements["property_ids[]"][x].checked==true){
            count++;
        }
    }
	cf = confirm("Are you sure you want to delete these (" + count + ") properties?");
	if(cf){
	  document.propertyform.propertyaction.value=action;
      document.propertyform.submit();
	}
  }
  else {
    document.propertyform.propertyaction.value=action;
    document.propertyform.submit();
  }
}

var form='propertyform'; //Give the form name here

function SetChecked(x,chkName) {
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false) {
      dml.elements[i].checked=x.checked;
    }
  }
}
</script>
<body bgColor='#FFFFFF' leftMargin=0 topMargin=0>
<div class="main">
<?php

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

$groups_clause = " 1=1 ";
if($SESSION_USE_GROUPS==1){

  
  $groups_array = explode(",", $SESSION_GROUPS);
  $groups_search = "";
  for($x=0;$x<sizeof($groups_array);$x++){
    if($groups_array[$x]=="") continue;
	$groups_search .= $groups_array[$x] . ",";
  }
  $groups_search = go_reg_replace("\,$", "", $groups_search);
  if($groups_search != "") $groups_clause = " groups in ($groups_search) ";
  if($groups_clause == "") $groups_clause = " 1=1 ";
  
  if($SESSION_SUBGROUPS != ''){

    $subgroups_array = explode(",", $SESSION_SUBGROUPS);
    $subgroups_search = "";
    for($x=0;$x<sizeof($subgroups_array);$x++){
      if($subgroups_array[$x]=="") continue;
	  $subgroups_search .= $subgroups_array[$x] . ",";
    }
    $subgroups_search = go_reg_replace("\,$", "", $subgroups_search);
    if($subgroups_search != "") $groups_clause .= " and subgroups in ($subgroups_search)";
  }
  
}


$user_id = $SESSION_USER_ID;

if($SESSION_IREP==1) $irep_clause = " and irep='" . $SESSION_USER_ID . "' ";

$sql = "SELECT count(*) as count, sum(roof_size) as sqft from properties 
where prospect_id='$prospect_id' and corporate=0 and display=1 $irep_clause and $groups_clause";
$result = executequery($sql);
$record = go_fetch_array($result);
$count = $record['count'];
//$sqft = $record['sqft'];
//if($sqft == "") $sqft = 0;

$sql = "SELECT site_name, property_id, city, state, division_id, roof_size 
from properties where display=1 and corporate=0 and prospect_id='" . $prospect_id . "' $irep_clause and $groups_clause";
$result = executequery($sql);
while($sites = go_fetch_array($result)){
  $property_id = $sites['property_id'];
  $roof_size = stripslashes($sites['roof_size']);
  $sitesqft = 0;
	$sql = "SELECT sqft, grade, roof_type from sections where property_id='$property_id' and display=1";
	$res_sec = executequery($sql);
	while($sections = go_fetch_array($res_sec)){
	  $sitesqft += $sections['sqft'];
	}
  if($sitesqft == 0) $sitesqft = $roof_size * 100;;
  //echo "<!-- $sitesqft -->\n";
  $totalsqft += $sitesqft;
}

$sqft = $totalsqft / 100;



$sql = "SELECT * from properties 
where prospect_id='$prospect_id' and prospect_id != 0 and corporate=0 and display=1 and $groups_clause";
if($search != "") $sql .= " and $searchby like '%$search%'";
$sql .= " $irep_clause";
$result = executequery($sql);
$counter=0;
while($record = go_fetch_array($result)){
  $sitesections = 0;
  $sitesqft = 0;
  $siteavg = 0;
  $sitepoints = 0;
  $sitesqs = 0;
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
?>
<script>
function go_search(x){
  searchfor = x.value;
  sb = document.searchform.searchby.value;
  url="frame_prospect_properties_filter.php?prospect_id=<?=$prospect_id?>&order_by=<?=$order_by?>&order_by2=<?=$order_by2?>&searchfor=" + searchfor + "&searchby=" + sb;
    url=url+"&sid="+Math.random();
	//document.getElementById('maindebug').style.display="";
	//document.getElementById('maindebug').innerHTML = url;
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}
</script>
<table class="main" width="100%">
<tr>
<td valign="top">
  <table class="main">
  <tr>
  <td><strong>Total Properties</strong></td>
  <td><?=$count?></td>
  </tr>
  <tr>
  <td><strong>Total SQS</strong></td>
  <td><?=number_format($sqft, 2)?></td>
  </tr>
  </table>
</td>
<td align="right" valign="top">
<a href="frame_prospect_addproperty.php?property_id=new&prospect_id=<?=$prospect_id?>">Add a new property</a>
</td>
</tr>
</table>
<?php
if($_SESSION[$sess_header . '_move_property'] != ""){
?>
<a href="frame_prospect_properties_paste.php?prospect_id=<?=$prospect_id?>">Paste properties to this company</a><br>
<?php } ?>

<hr size="1" color="#9a000c">
<form name="searchform" action="frame_prospect_properties.php" method="get">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
<select name="searchby">
<option value="site_name"<?php if($searchby=="site_name") echo " selected";?>>Property</option>
<option value="address"<?php if($searchby=="address") echo " selected";?>>Address</option>
<option value="city"<?php if($searchby=="city") echo " selected";?>>City</option>
</select>
 <input type="text" name="search" onKeyUp="go_search(this)" value="<?=$search?>">
</form>
<form action="frame_prospect_properties_action.php" method="post" name="propertyform">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
<input type="hidden" name="propertyaction" value="">
<div id="table_div">
<table class="main" width="100%" cellpadding="2" cellspacing="0">
<tr>
<!--<td><strong>Status</strong></td>-->
<td><input type='checkbox' onChange="SetChecked(this, 'property_ids[]')"></td>
<td><strong><?=sort_header('site_name', 'Property')?></strong></td>
<td><strong><?=sort_header('address', 'Address')?></strong></td>
<td><strong><?=sort_header('state', 'Location')?></strong></td>
<td><strong><?=sort_header('sitesqs', 'SQS')?></strong></td>
<?php /*
<td><strong><?=sort_header('estimated_install', 'Estimated Install')?></strong></td>
*/?>
<td><strong><?=sort_header('grade', 'Grade')?></strong></td>
<td></td>
</tr>
<?php
  
for($x=0;$x<sizeof($row);$x++){
  $site_name = stripslashes($row[$x]['site_name']);
  if($site_name=="" || $site_name == " ") $site_name="[PROPERTY]";
  ?>
  <tr<?php if($x % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td><input type="checkbox" name="property_ids[]" value="<?=$row[$x]['property_id']?>"></td>
  <td>
  <a href="view_property.php?property_id=<?=$row[$x]['property_id']?>" target="_parent">
  <?=$site_name?>
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
</div>
</form>
<?php if($SESSION_ISADMIN){ ?>
<a href="javascript:goForm('move')">Copy checked properties and move to...</a><br>
<br>
<a href="javascript:goForm('delete')">Delete checked properties</a>
<?php } ?>
</div>
</body>
</html>
