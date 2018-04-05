<?php
include "includes/functions.php";

$searchfor = go_escape_string($_GET['searchfor']);
$searchby = go_escape_string($_GET['searchby']);

/*
select a.firstname, a.lastname, b.site_name, c.company_name 
from contacts a
left join properties b on a.property_id=b.property_id
left join prospects c on a.prospect_id=c.prospect_id
where concat(a.firstname, ' ', a.lastname) like '%Jim%'
*/

$irep_prospect = " 1=1 ";
$irep_property = " 1=1 ";
if($SESSION_IREP==1){
  $irep_prospect = " b.irep like '%," . $SESSION_USER_ID . ",%' ";
  $irep_property = " c.irep = '" . $SESSION_USER_ID . "' ";
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
  if($groups_search != "") $groups_clause = " XXX.groups in ($groups_search) ";
  if($groups_clause == "") $groups_clause = " 1=1 ";
  
  if($SESSION_SUBGROUPS != ''){

    $subgroups_array = explode(",", $SESSION_SUBGROUPS);
    $subgroups_search = "";
    for($x=0;$x<sizeof($subgroups_array);$x++){
      if($subgroups_array[$x]=="") continue;
	  $subgroups_search .= $subgroups_array[$x] . ",";
    }
    $subgroups_search = go_reg_replace("\,$", "", $subgroups_search);
    if($subgroups_search != "") $groups_clause .= " and XXX.subgroups in ($subgroups_search)";
  }
  $property_groups_clause = go_reg_replace("XXX", "c", $groups_clause);
  
  for($x=0;$x<sizeof($SESSION_GROUP_PROSPECT_ID);$x++){
    $pg .= $SESSION_GROUP_PROSPECT_ID[$x] . ",";
  }
  $pg = go_reg_replace("\,$", "", $pg);
  if($pg=="") $pg="0";
  $prospect_groups_clause = " b.prospect_id in ($pg) ";
}
else {
  $property_groups_clause = " 1=1 ";
  $prospect_groups_clause = " 1=1 ";
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

if($searchfor != ""){
switch($searchby){
  case "Company":
  case "Company Address":
  case "Identifier":{
    if($searchby=="Company") $field = "company_name";
	if($searchby=="Company Address") $field = "address";
	if($searchby=="Identifier") $field = "identifier";

$sql = "SELECT prospect_id, company_name, city, state, zip, address
from prospects b
where $field like '%$searchfor%' 
and $irep_prospect
and $prospect_groups_clause
and master_id='" . $SESSION_MASTER_ID . "' and display=1 order by company_name";
$result = executequery($sql);
//echo $sql;
//exit;

ob_start();

?>
<table class="main" width="99%" cellpadding="4" cellspacing="0">
<tr>
<td width="30%"><strong>Company</strong></td>
<td width="25%"><strong>Address</strong></td>
<td width="20%"><strong>City</strong></td>
<td width="10%"><strong>State</strong></td>
<td width="15%"><strong>Zip</strong></td>
</tr>
<?php
$counter = 0;
while($record = go_fetch_array($result)){
  $counter++;
  if($counter % 2){
    $class = "altrow";
  }
  else {
    $class = "mainrow";
  }
  ?>
  <tr class="<?=$class?>">
  <td><a href='view_company.php?prospect_id=<?=$record['prospect_id']?>' target="_top"><?=stripslashes($record['company_name'])?></a></td>
  <td><?=stripslashes($record['address'])?></td>
  <td><?=stripslashes($record['city'])?></td>
  <td><?=stripslashes($record['state'])?></td>
  <td align="left"><?=stripslashes($record['zip'])?></td>
  </tr>
  <?php
}
?>
</table>
<?php
break;
}

  case "Property":
  case "Property Address":{
    if($searchby=="Property") $field = "c.site_name";
	if($searchby=="Property Address") $field = "c.address";

$sql = "SELECT c.prospect_id, c.site_name, c.property_id, c.city, c.state, c.zip, c.address, b.company_name
from properties c, prospects b 
where c.prospect_id = b.prospect_id and $field like '%$searchfor%' 
and $irep_property
and $property_groups_clause
and b.master_id='" . $SESSION_MASTER_ID . "' and c.display=1 and b.display=1 and c.corporate=0 order by c.site_name";
$result = executequery($sql);

ob_start();

?>
<table class="main" width="99%" cellpadding="4" cellspacing="0">
<tr>
<td width="30%"><strong>Property</strong></td>
<td width="25%"><strong>Address</strong></td>
<td width="20%"><strong>City</strong></td>
<td width="10%"><strong>State</strong></td>
<td width="15%"><strong>Zip</strong></td>
</tr>
<?php
$counter = 0;
while($record = go_fetch_array($result)){
  $counter++;
  if($counter % 2){
    $class = "altrow";
  }
  else {
    $class = "mainrow";
  }
  ?>
  <tr class="<?=$class?>">
  <td><a href='view_property.php?property_id=<?=$record['property_id']?>' target="_top"><?=stripslashes($record['site_name'])?></a></td>
  <td><?=stripslashes($record['address'])?></td>
  <td><?=stripslashes($record['city'])?></td>
  <td><?=stripslashes($record['state'])?></td>
  <td align="left"><?=stripslashes($record['zip'])?></td>
  </tr>
  <?php
}
?>
</table>
<?php
break;
}


  
  case "Contact":
  case "Contact Phone":
  case "Contact Email":{
    if($searchby=="Contact") $searchclause = " concat(a.firstname, ' ', a.lastname) like '%$searchfor%' ";
	if($searchby=="Contact Email") $searchclause = " a.email like '%$searchfor%' ";
	if($searchby=="Contact Phone") $searchclause = " (a.phone like '%$searchfor%' or a.mobile like '%$searchfor%') ";
	
	$counter = 0;
	$sql = "SELECT concat(a.firstname, ' ', a.lastname) as fullname, a.email, a.phone, a.mobile, a.property_id, c.site_name, c.address, c.city, c.state
	from contacts a, properties c, prospects b
	where a.property_id=c.property_id
	and c.prospect_id = b.prospect_id
    and $searchclause
	and $irep_property
	and $property_groups_clause
    and a.master_id='" . $SESSION_MASTER_ID . "' and c.display=1 order by a.lastname";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $fullname = stripslashes($record['fullname']);
	  $email = stripslashes($record['email']);
	  $phone = stripslashes($record['phone']);
	  $mobile = stripslashes($record['mobile']);
	  $site_name = stripslashes($record['site_name']);
	  $address = stripslashes($record['address']);
	  $city = stripslashes($record['city']);
	  $state = stripslashes($record['state']);
	  $property_id = $record['property_id'];
	  
	  $row[$counter]['fullname'] = $fullname;
	  $row[$counter]['email'] = $email;
	  $row[$counter]['phone'] = $phone;
	  $row[$counter]['mobile'] = $mobile;
	  $row[$counter]['address'] = $address;
	  $row[$counter]['city'] = $city;
	  $row[$counter]['state'] = $state;
	  $row[$counter]['pid'] = $property_id;
	  $row[$counter]['name'] = $site_name;
	  $row[$counter]['type'] = "property";
	  $counter++;
	}
	
	$sql = "SELECT concat(a.firstname, ' ', a.lastname) as fullname, a.email, a.phone, a.mobile, a.prospect_id, b.company_name, b.address, b.city, b.state
	from contacts a, prospects b
	where a.prospect_id=b.prospect_id
    and $searchclause
	and $irep_prospect
	and $prospect_groups_clause
    and a.master_id='" . $SESSION_MASTER_ID . "' and b.display=1 order by a.lastname";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $fullname = stripslashes($record['fullname']);
	  $email = stripslashes($record['email']);
	  $phone = stripslashes($record['phone']);
	  $mobile = stripslashes($record['mobile']);
	  $company_name = stripslashes($record['company_name']);
	  $address = stripslashes($record['address']);
	  $city = stripslashes($record['city']);
	  $state = stripslashes($record['state']);
	  $prospect_id = $record['prospect_id'];
	  
	  $row[$counter]['fullname'] = $fullname;
	  $row[$counter]['email'] = $email;
	  $row[$counter]['phone'] = $phone;
	  $row[$counter]['mobile'] = $mobile;
	  $row[$counter]['address'] = $address;
	  $row[$counter]['city'] = $city;
	  $row[$counter]['state'] = $state;
	  $row[$counter]['pid'] = $prospect_id;
	  $row[$counter]['name'] = $company_name;
	  $row[$counter]['type'] = "prospect";
	  $counter++;
	}
	
	usort($row, "compare");
	  
	


ob_start();

?>
<table class="main" width="99%" cellpadding="4" cellspacing="0">
<tr>
<td width="20%"><strong>Company/Property</strong></td>
<td width="15%"><strong>Address</strong></td>
<td width="15%"><strong>City</strong></td>
<td width="5%"><strong>State</strong></td>
<td width="15%"><strong>Name</strong></td>
<td width="8%"><strong>Phone</strong></td>
<td width="8%"><strong>Mobile</strong></td>
<td width="14%"><strong>Email</strong></td>
</tr>
<?php
$counter = 0;
for($x=0;$x<sizeof($row);$x++){
  $counter++;
  if($counter % 2){
    $class = "altrow";
  }
  else {
    $class = "mainrow";
  }
  $link = "";
  if($row[$x]['type']=="prospect") {
    $link = "<a href='view_prospect.php?prospect_id=" . $row[$x]['pid'] . "' target='_top'>" . stripslashes($row[$x]['name']) . " (Company)</a>";
  }
  else {
    $link = "<a href='view_prospect.php?property_id=" . $row[$x]['pid'] . "' target='_top'>" . stripslashes($row[$x]['name']) . " (Property)</a>";
  }
  ?>
  <tr class="<?=$class?>">
  <td><?=$link?></td>
  <td><?=stripslashes($row[$x]['address'])?></td>
  <td><?=stripslashes($row[$x]['city'])?></td>
  <td><?=stripslashes($row[$x]['state'])?></td>
  <td><?=stripslashes($row[$x]['fullname'])?></td>
  <td><?=stripslashes($row[$x]['phone'])?></td>
  <td><?=stripslashes($row[$x]['mobile'])?></td>
  <td><?=stripslashes($row[$x]['email'])?></td>
  </tr>
  <?php
}
?>
</table>
<?php
break;
}


  

} // end switch
} // end if searchfor is blank

$html = ob_get_contents();
ob_end_clean();
$html = jsclean($html);
?>
div = document.getElementById('search_results');
div.innerHTML = '<?php echo $html; ?>';
  