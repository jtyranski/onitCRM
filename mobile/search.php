<?php include "includes/header.php"; ?>
<?php include "includes/main_nav.php"; ?>
<?php
$user_id = $SESSION_USER_ID;
$searchby = $_GET['searchby'];
$searchfor = $_GET['searchfor'];
$prospect_status_searchby = $_GET['prospect_status_searchby'];
$sales_stage_searchby = $_GET['sales_stage_searchby'];
$prospecting_searchby = $_GET['prospecting_searchby'];
$prospecting_beazer_searchby = $_GET['prospecting_beazer_searchby'];
$prospect_result_searchby = $_GET['prospect_result_searchby'];

$zip = $_GET['zip'];
$distance = $_GET['distance'];


$searchby2 = $_GET['searchby2'];
$searchfor2 = $_GET['searchfor2'];
$real_searchby2 = $searchby2;
if($real_searchby2=="beazer_file" || $real_searchby2=="key_number") $real_searchby2 == "number";
if($real_searchby2=="") $searchfor2="";

$order_by = $_GET['order_by'];
$order_by2 = $_GET['order_by2'];
if($order_by2 == "") $order_by2 = "asc";
if($order_by2 == "asc"){
  $function = "compare";
}
else {
  $function = "rcompare";
}

$view_filter = $_GET['view_filter'];
$view_filter_property_type = $view_filter;
if($view_filter == "All") $view_filter_property_type = "";

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

?>
<script>


function loadview(x){
  y = x.value;
  document.location.href = "<?=$_SERVER['SCRIPT_NAME']?>?searchby=<?=$searchby?>&searchfor=<?=$searchfor?>&prospect_status_searchby=<?=$prospect_status_searchby?>&submit1=Search&searchclick=Search&searchby2=<?=$searchby2?>&searchfor2=<?=$searchfor2?>&zip=<?=$zip?>&distance=<?=$distance?>&view_filter=" + y;
}

</script>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="searchform">
<table class="main">
<tr>
<td nowrap="nowrap">
Search by 
<select name="searchby">
<option value="company_name"<?php if($searchby=="company_name") echo " selected"; ?>>Company</option>
<option value="site_name"<?php if($searchby=="site_name") echo " selected"; ?>>Property</option>
<option value="address"<?php if($searchby=="address") echo " selected"; ?>>Property Address</option>
<option value="city"<?php if($searchby=="city") echo " selected"; ?>>Property City</option>
<option value="state"<?php if($searchby=="state") echo " selected"; ?>>Property State</option>
</select>
</td>
</tr>
<tr>
<td id="searchby_id" width="240">
<input type="text" class="largerbox" name="searchfor" value="<?=$searchfor?>" size="45">
</td>
</tr>
</table>


Zip Code: <input type="text" name="zip" value="<?=$zip?>" size="5">
Radius: <input type="text" name="distance" value="<?=$distance?>" size="3">miles
<input type="submit" name="submit1" value="Search" style="width:310px; height:50px;">
<input type="hidden" name="searchclick" value="Search">

</form>
<?php
$submit1 = $_GET['searchclick'];
if($submit1=="Search"){
$irep_prospect = " 1=1 ";
$irep_property = " 1=1 ";
if($SESSION_IREP==1){
  $irep_prospect = " irep like '%," . $SESSION_USER_ID . ",%' ";
  $irep_property = " a.irep = '" . $SESSION_USER_ID . "' ";
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
  $property_groups_clause = go_reg_replace("XXX", "a", $groups_clause);
  
  for($x=0;$x<sizeof($SESSION_GROUP_PROSPECT_ID);$x++){
    $pg .= $SESSION_GROUP_PROSPECT_ID[$x] . ",";
  }
  $pg = go_reg_replace("\,$", "", $pg);
  if($pg=="") $pg="0";
  $prospect_groups_clause = " prospects.prospect_id in ($pg) ";
}
else {
  $property_groups_clause = " 1=1 ";
  $prospect_groups_clause = " 1=1 ";
}



  $searchfor = go_escape_string($searchfor);
  switch($searchby){
  
  case "company_name":
  case "importidentifier":
  case "prospect_status":{
    if($order_by == "") $order_by = "company_name";
    if($searchby=="company_name") $special_search = " company_name like \"%$searchfor%\" ";
	if($searchby=="importidentifier") $special_search = " identifier like \"%$searchfor%\" ";
	if($searchby=="prospect_status") $special_search = " prospect_status =\"$prospect_status_searchby\" ";
    $searchlable = "Company Status" . sort_arrows('searchfield');
	$search_display = "company";
    $counter=0;
    $sql = "SELECT prospect_id, company_name, city, state, zip, 
	properties, property_type, 
	date_format(lastaction, \"%m/%d/%Y\") as lastaction_pretty
	from prospects 
	where $special_search and $irep_prospect and $prospect_groups_clause and display=1 and master_id='" . $SESSION_MASTER_ID . "' order by company_name";
	$result = executequery($sql);
	while($company = go_fetch_array($result)){
	  $prospect_id = $company['prospect_id'];
	  $company_name = stripslashes($company['company_name']);

	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = "";
	  $row[$counter]['property_id'] = "";
	  $row[$counter]['number'] = "";
	  $row[$counter]['searchfield'] = "";
	  $row[$counter]['prospect_status'] = stripslashes($company['prospect_status']);
	  $row[$counter]['city'] = stripslashes($company['city']);
	  $row[$counter]['state'] = stripslashes($company['state']);
	  $row[$counter]['properties'] = stripslashes($company['properties']);
	  $row[$counter]['property_type'] = stripslashes($company['property_type']);
	  $row[$counter]['lastaction'] = stripslashes($company['lastaction_pretty']);
	  $row[$counter]['zip'] = $company['zip'];
	  $row[$counter]['territory'] = "";
		
	  $counter++;
	}
	break;
  }
  
  default:{
    if($order_by == "") $order_by = "site_name";
    $special_search = " a.$searchby like \"%$searchfor%\" ";
	if($searchby=="prospecting_type") $special_search = " a.prospecting_type ='$prospecting_searchby' ";
	if($searchby=="beazer_claim_id") $special_search = " a.beazer_claim_id ='$prospecting_beazer_searchby' ";
	
    $counter=0;
	$search_display = "property";
	$searchlable = ucwords($searchby) . sort_arrows('searchfield');
	$sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, a.$searchby as searchfield, 
	a.address, a.city, a.state,
	a.zip, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty
	from 
	properties a, prospects b 
	where a.prospect_id = b.prospect_id and $special_search and $irep_property and $property_groups_clause and a.corporate=0 and a.display=1 and b.master_id='" . $SESSION_MASTER_ID . "' order by a.site_name";
	$result = executequery($sql);
	while($property = go_fetch_array($result)){
	  $site_name = stripslashes($property['site_name']);
	  $property_id = stripslashes($property['property_id']);
	  $property_type = stripslashes($property['property_type']);
	  $company_name = stripslashes($property['company_name']);
	  $prospect_id = stripslashes($property['prospect_id']);
	  $searchfield = stripslashes($property['searchfield']);

	  
	  $address = stripslashes($property['address']);
	  $city = stripslashes($property['city']);
	  $state = stripslashes($property['state']);
	  $row[$counter]['address'] = $address;
	  $row[$counter]['city'] = $city;
	  $row[$counter]['state'] = $state;
	  $row[$counter]['identifier'] = stripslashes($property['identifier']);
	  $row[$counter]['prospect_status'] = stripslashes($property['propsect_status']);
	  
	  $sales_stage = $property['sales_stage'];
	  $row[$counter]['sales_stage'] = $sales_stage_array[$sales_stage];
	  
	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = $site_name;
	  $row[$counter]['property_id'] = $property_id;
	  $row[$counter]['number'] = $number;
	  $row[$counter]['searchfield'] = $searchfield;
	  $row[$counter]['money'] = $money;
	  $row[$counter]['roof_size'] = stripslashes($property['roof_size']);
	  $row[$counter]['property_type'] = $property_type;
	  $row[$counter]['ssdate'] = $property['ssdate'];
	  $row[$counter]['lastaction'] = stripslashes($property['lastaction_pretty']);
	  $row[$counter]['region'] = $property['region'];
	  $row[$counter]['ro_status'] = stripslashes($property['ro_status']);
	  $row[$counter]['zip'] = $property['zip'];
	  $row[$counter]['territory'] = $property['territory'];
	  
	  $counter++;
	}
	break;
  }
  

  
  
	  
  
  } // end switch	
  

if($zip){
  if($distance){
        $srch_serv_prov = array();
		
		$latitude = array();
		$longitute = array();
		$sqlzips = "SELECT * from zipcodes";
		$resultzips = executequery($sqlzips);
		while($record = go_fetch_array($resultzips)){
		  $x = $record['zipcode'];
		  $latitude[$x] = $record['latitude'];
		  $longitude[$x] = $record['longitude'];
		}
		$sql_new="select * from	zipcodes where zipcode='$zip'";
		$result2=executeQuery($sql_new);
		if($line2=go_fetch_array($result2))
		{
			$search_longitude=$line2["longitude"];
			$search_latitude=$line2["latitude"];
		}
		$counter = 0;
	    for($y=0;$y<sizeof($row);$y++){
		  $x = $row[$y]['zip'];
			$x = go_reg_replace("\-.*", "", $x);
			//$distance2=great_circle_distance($search_latitude,$line['latitude'],$search_longitude,$line['longitude']);
			$distance2=great_circle_distance($search_latitude,$latitude[$x],$search_longitude,$longitude[$x]);
			//echo "<br>distance2:$distance2<br>";
			if($distance2 <= $distance)
			{
				$row2[$counter] = $row[$y];
			    $counter++;
			}
		}
		$row = $row2;
  }
}
 



 

  usort($row, $function);
  ?>
  <table class="main" width="100%" cellpadding="2" cellspacing="0">


  <?php if($search_display == "property"){ ?>
  <tr>
  <td nowrap="nowrap"><strong>Property</strong></td>
  <td nowrap="nowrap"><strong>Location</strong></td>
  </tr>
  <?php
  $counter=0;
  for($x=0;$x<sizeof($row);$x++){

	$counter++;
	
    $site_name = stripslashes($row[$x]['site_name']);
    if($site_name=="") $site_name = "(None)";
    if(strlen($site_name) > 17) $site_name = substr($site_name, 0, 17) . "..";
    $city = stripslashes($row[$x]['city']);
    if(strlen($city) > 12) $city = substr($city, 0, 12) . "..";
    ?>
    <tr>
    <td><a href="property_details.php?property_id=<?=$row[$x]['property_id']?>"><?=$site_name?></a></td>
    <td><?=$city?>, <?=$row[$x]['state']?></td>
    </tr>
  <?php
  }
  ?>
  <?php } else {  // company display?>
  <tr>
  <td><strong>Company</strong></td>
  <td><strong>Location</strong></td>
  </tr>
  <?php
  $counter = 0;
  for($x=0;$x<sizeof($row);$x++){

	$counter++;
	
    $company_name = stripslashes($row[$x]['company_name']);
    if($company_name=="") $company_name = "(None)";
    if(strlen($company_name) > 17) $company_name = substr($company_name, 0, 17) . "..";
    $city = stripslashes($row[$x]['city']);
    if(strlen($city) > 12) $city = substr($city, 0, 12) . "..";
    ?>
    <tr>
    <td><a href="company_details.php?prospect_id=<?=$row[$x]['prospect_id']?>"><?=$company_name?></a></td>
    <td><?=$city?>, <?=$row[$x]['state']?></td>
    </tr>
	<?php
  }
  } // end if display of other than city
  if($x==0){
    ?>
	<tr>
	<td colspan="2">No records found to match your search criteria</td>
	</tr>
	<?php
  }
  ?>
  </table>
  <?php
	
}// end if submit
?>
</div>


<?php include "includes/footer.php"; ?>