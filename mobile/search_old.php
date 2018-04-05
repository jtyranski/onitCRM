<?php include "includes/header.php"; ?>
<?php include "includes/main_nav.php"; ?>
<?php
$searchby = $_GET['searchby'];
$searchfor = $_GET['searchfor'];
$prospect_status_searchby = $_GET['prospect_status_searchby'];
$sales_stage_searchby = $_GET['sales_stage_searchby'];

$searchby2 = $_GET['searchby2'];
$searchfor2 = $_GET['searchfor2'];
$real_searchby2 = $searchby2;
if($real_searchby2=="beazer_file" || $real_searchby2=="key_number") $real_searchby2 == "number";
if($real_searchby2=="") $searchfor2="";

$order_by = $_GET['order_by'];
$order_by2 = $_GET['order_by2'];
if($order_by == "") $order_by = "company_name";
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
function searchby_select(x){
  y = x.value;
  switch(y){
    case "prospect_status":{
      document.getElementById('prospect_status_dropdown').style.display="block";
	  document.getElementById('sales_stage_dropdown').style.display="none";
	  document.getElementById('searchby_id').style.display="none";
	  break;
    }
	case "sales_stage":{
      document.getElementById('prospect_status_dropdown').style.display="none";
	  document.getElementById('sales_stage_dropdown').style.display="none";
	  document.getElementById('searchby_id').style.display="block";
	  break;
    }
    default: {
      document.getElementById('prospect_status_dropdown').style.display="none";
	  document.getElementById('sales_stage_dropdown').style.display="none";
	  document.getElementById('searchby_id').style.display="block";
	  break;
    }
  }
}

function loadview(x){
  y = x.value;
  document.location.href = "<?=$_SERVER['SCRIPT_NAME']?>?searchby=<?=$searchby?>&searchfor=<?=$searchfor?>&prospect_status_searchby=<?=$prospect_status_searchby?>&submit1=Search&searchclick=Search&searchby2=<?=$searchby2?>&searchfor2=<?=$searchfor2?>&view_filter=" + y;
}

</script>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="searchform">
<table class="main">
<tr>
<td nowrap="nowrap">
Search by 
<select name="searchby" onChange="searchby_select(this)">
<option value="company_name"<?php if($searchby=="company_name") echo " selected"; ?>>Company</option>
<option value="site_name"<?php if($searchby=="site_name") echo " selected"; ?>>Property</option>
<option value="contact"<?php if($searchby=="contact") echo " selected"; ?>>Contact</option>
<option value="beazer_file"<?php if($searchby=="beazer_file") echo " selected"; ?>>Beazer Number</option>
<option value="key_number"<?php if($searchby=="key_number") echo " selected"; ?>>Key Number</option>
<option value="phone"<?php if($searchby=="phone") echo " selected"; ?>>Phone</option>
<option value="address"<?php if($searchby=="address") echo " selected"; ?>>Property Address</option>
<option value="city"<?php if($searchby=="city") echo " selected"; ?>>Property City</option>
<option value="state"<?php if($searchby=="state") echo " selected"; ?>>Property State</option>
<option value="identifier"<?php if($searchby=="identifier") echo " selected"; ?>>Identifier</option>
<option value="id_status"<?php if($searchby=="id_status") echo " selected"; ?>>Beazer - ID Status</option>
<option value="beazer_status"<?php if($searchby=="beazer_status") echo " selected"; ?>>Beazer Status</option>
<option value="prospect_status"<?php if($searchby=="prospect_status") echo " selected"; ?>>Company Status</option>
<option value="sales_stage"<?php if($searchby=="sales_stage") echo " selected"; ?>>Sales Stage</option>
<option value="region"<?php if($searchby=="region") echo " selected"; ?>>Region</option>
</select>
</td>
</tr>
<tr>
<td id="searchby_id" width="240">
<input type="text" class="largerbox" name="searchfor" value="<?=$searchfor?>" size="45">
</td>
</tr>
</table>


<table class="main">
<tr>
<td nowrap="nowrap">
Then By 
<select name="searchby2">
<option value=""></option>
<option value="company_name"<?php if($searchby2=="company_name") echo " selected"; ?>>Company</option>
<option value="site_name"<?php if($searchby2=="site_name") echo " selected"; ?>>Property</option>
<option value="beazer_file"<?php if($searchby2=="beazer_file") echo " selected"; ?>>Beazer Number</option>
<option value="key_number"<?php if($searchby2=="key_number") echo " selected"; ?>>Key Number</option>
<option value="phone"<?php if($searchby2=="phone") echo " selected"; ?>>Phone</option>
<option value="address"<?php if($searchby2=="address") echo " selected"; ?>>Property Address</option>
<option value="city"<?php if($searchby2=="city") echo " selected"; ?>>Property City</option>
<option value="state"<?php if($searchby2=="state") echo " selected"; ?>>Property State</option>
<option value="identifier"<?php if($searchby2=="identifier") echo " selected"; ?>>Identifier</option>
<option value="id_status"<?php if($searchby2=="id_status") echo " selected"; ?>>Beazer - ID Status</option>
<option value="beazer_status"<?php if($searchby2=="beazer_status") echo " selected"; ?>>Beazer Status</option>
<option value="prospect_status"<?php if($searchby2=="prospect_status") echo " selected"; ?>>Company Status</option>
<option value="sales_stage"<?php if($searchby2=="sales_stage") echo " selected"; ?>>Sales Stage</option>
<option value="region"<?php if($searchby2=="region") echo " selected"; ?>>Region</option>
</select>
</td>
</tr>
<tr>
<td width="240">
<input type="text" class="largerbox" name="searchfor2" value="<?=$searchfor2?>" size="45">
</td>

</tr>
</table>
<input type="submit" name="submit1" value="Search" style="width:310px;">
<input type="hidden" name="searchclick" value="Search">

</form>
<?php
$submit1 = $_GET['searchclick'];
if($submit1=="Search"){
  $sql = "SELECT * from sales_stage order by sales_stage";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $x = $record['sales_stage_id'];
	$sales_stage_array[$x] = stripslashes($record['sales_stage']);
  }

  $searchfor = go_escape_string($searchfor);
  switch($searchby){
  case "company_name":{
    $search_display = "company";
    $searchlable = "";
    $counter=0;
    $sql = "SELECT prospect_id, company_name, city, state, 
	phone1, mobile1, phone2, mobile2, phone3, mobile3, phone4, mobile4, phone5, mobile5, prospect_status, properties, property_type, 
	date_format(lastaction, \"%m/%d/%Y\") as lastaction_pretty
	from prospects where company_name like \"%$searchfor%\" and display=1 order by company_name";
	$result = executequery($sql);
	while($company = go_fetch_array($result)){
	  $prospect_id = $company['prospect_id'];
	  $company_name = stripslashes($company['company_name']);
	  $phone1 = stripslashes($company['phone1']);
	  $phone2 = stripslashes($company['phone2']);
	  $phone3 = stripslashes($company['phone3']);
	  $phone4 = stripslashes($company['phone4']);
	  $phone5 = stripslashes($company['phone5']);
	  $mobile1 = stripslashes($company['mobile1']);
	  $mobile2 = stripslashes($company['mobile2']);
	  $mobile3 = stripslashes($company['mobile3']);
	  $mobile4 = stripslashes($company['mobile4']);
	  $mobile5 = stripslashes($company['mobile5']);
	  $company_phone = $phone1 . "," . $phone2 . "," . $phone3 . "," . $phone4 . "," . $phone5 . ",";
	  $company_phone .= $mobile1 . "," . $mobile2 . "," . $mobile3 . "," . $mobile4 . "," . $mobile5;
	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = "";
	  $row[$counter]['property_id'] = "";
	  $row[$counter]['number'] = "";
	  $row[$counter]['searchfield'] = "";
	  $row[$counter]['phone'] = $company_phone;
	  $row[$counter]['prospect_status'] = stripslashes($company['prospect_status']);
	  $row[$counter]['city'] = stripslashes($company['city']);
	  $row[$counter]['state'] = stripslashes($company['state']);
	  $row[$counter]['properties'] = stripslashes($company['properties']);
	  $row[$counter]['property_type'] = stripslashes($company['property_type']);
	  $row[$counter]['lastaction'] = stripslashes($company['lastaction_pretty']);
	  $row[$counter]['region'] = $company['region'];
	  $counter++;

	}
	break;
  }
  
  case "site_name":{
    $searchlable = "";
	$search_display = "property";
    $counter=0;
	$sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, 
	a.phone, a.phone2, a.phone3, a.mobile, a.mobile2, a.mobile3, 
	a.address, a.city, a.state, a.identifier, b.prospect_status, a.sales_stage, a.roof_size, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region
	from 
	properties a, prospects b 
	where a.prospect_id = b.prospect_id and a.site_name like '%$searchfor%' and a.corporate=0 and a.display=1 order by a.site_name";
	$result = executequery($sql);
	while($property = go_fetch_array($result)){
	  $site_name = stripslashes($property['site_name']);
	  $property_id = stripslashes($property['property_id']);
	  $property_type = stripslashes($property['property_type']);
	  $company_name = stripslashes($property['company_name']);
	  $prospect_id = stripslashes($property['prospect_id']);
	  if($property_type=="Manville"){
		$sql = "SELECT key_number from properties_manville where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT comp_amount from properties_manville where property_id='$property_id'";
		$money = getsingleresult($sql);
	  }
	  if($property_type=="Beazer" || $property_type== "Beazer B"){
		$sql = "SELECT beazer_file from properties_beazer where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT id_status from properties_beazer where property_id='$property_id'";
		$id_status = getsingleresult($sql);
		$row[$counter]['id_status'] = $id_status;
		$sql = "SELECT settlement_number from properties_beazer where property_id='$property_id'";
		$money = getsingleresult($sql);
	  }
	  if($property_type=="Non-PFRI"){
		$number = "";
		$money = "";
	  }
	  $phone = stripslashes($property['phone']);
	  $phone2 = stripslashes($property['phone2']);
	  $phone3 = stripslashes($property['phone3']);
	  $mobile = stripslashes($property['mobile']);
	  $mobile2 = stripslashes($property['mobile2']);
	  $mobile3 = stripslashes($property['mobile3']);
	  $property_phone = $phone . "," . $phone2 . "," . $phone3 . "," . $mobile . "," . $mobile2 . "," . $mobile3;
	  
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
	  $row[$counter]['property_type'] = $property_type;
	  
	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = $site_name;
	  $row[$counter]['property_id'] = $property_id;
	  $row[$counter]['number'] = $number;
	  $row[$counter]['searchfield'] = "";
	  $row[$counter]['phone'] = $property_phone;
	  $row[$counter]['money'] = $money;
	  $row[$counter]['roof_size'] = stripslashes($property['roof_size']);
	  $row[$counter]['ssdate'] = $property['ssdate'];
	  $row[$counter]['lastaction'] = stripslashes($property['lastaction_pretty']);
	  $row[$counter]['region'] = $property['region'];
	  $counter++;
	}
	break;
  }
  
  case "contact":{
    $search_display = "property";
    $searchlable = "Contact" . sort_arrows('searchfield');
    $counter=0;
	
	$sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, a.contact, 
	a.phone, a.phone2, a.phone3, a.mobile, a.mobile2, a.mobile3, 
	a.address, a.city, a.state, a.identifier, b.prospect_status, a.sales_stage, a.roof_size, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region
	from 
	properties a, prospects b 
	where a.prospect_id = b.prospect_id and a.contact like '%$searchfor%' and a.corporate=0 and a.display=1 order by a.site_name";
	$result = executequery($sql);
	while($property = go_fetch_array($result)){
	  $site_name = stripslashes($property['site_name']);
	  $property_id = stripslashes($property['property_id']);
	  $property_type = stripslashes($property['property_type']);
	  $company_name = stripslashes($property['company_name']);
	  $prospect_id = stripslashes($property['prospect_id']);
	  $contact = stripslashes($property['contact']);
	  if($property_type=="Manville"){
		$sql = "SELECT key_number from properties_manville where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT comp_amount from properties_manville where property_id='$property_id'";
		$money = getsingleresult($sql);
	  }
	  if($property_type=="Beazer" || $property_type== "Beazer B"){
		$sql = "SELECT beazer_file from properties_beazer where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT id_status from properties_beazer where property_id='$property_id'";
		$id_status = getsingleresult($sql);
		$row[$counter]['id_status'] = $id_status;
		$sql = "SELECT settlement_number from properties_beazer where property_id='$property_id'";
		$money = getsingleresult($sql);
	  }
	  if($property_type=="Non-PFRI"){
		$number = "";
		$money = "";
	  }
	  $phone = stripslashes($property['phone']);
	  $phone2 = stripslashes($property['phone2']);
	  $phone3 = stripslashes($property['phone3']);
	  $mobile = stripslashes($property['mobile']);
	  $mobile2 = stripslashes($property['mobile2']);
	  $mobile3 = stripslashes($property['mobile3']);
	  $property_phone = $phone . "," . $phone2 . "," . $phone3 . "," . $mobile . "," . $mobile2 . "," . $mobile3;
	  $row[$counter]['phone'] = $property_phone;
	  
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
	  $row[$counter]['property_type'] = $property_type;
	  
	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = $site_name;
	  $row[$counter]['property_id'] = $property_id;
	  $row[$counter]['number'] = $number;
	  $row[$counter]['searchfield'] = $contact;
	  $row[$counter]['money'] = $money;
	  $row[$counter]['roof_size'] = stripslashes($property['roof_size']);
	  $row[$counter]['ssdate'] = $property['ssdate'];
	  $row[$counter]['lastaction'] = stripslashes($property['lastaction_pretty']);
	  $row[$counter]['region'] = $property['region'];
	  
	  $counter++;
	}
	
	break;
  }
  
  case "beazer_file":{
    $searchlable = "";
	$search_display = "property";
    $counter=0;
	$sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, c.beazer_file, 
	a.phone, a.phone2, a.phone3, a.mobile, a.mobile2, a.mobile3, 
	a.address, a.city, a.state, a.identifier, c.id_status, b.prospect_status, a.sales_stage, c.settlement_number as money, a.roof_size, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region
	from 
	properties a, prospects b, properties_beazer c
	where a.prospect_id = b.prospect_id 
	and a.property_id = c.property_id 
	and c.beazer_file like '$searchfor%' and a.corporate=0 and a.display=1 order by a.site_name";
	$result = executequery($sql);
	while($property = go_fetch_array($result)){
	  $site_name = stripslashes($property['site_name']);
	  $property_id = stripslashes($property['property_id']);
	  $property_type = stripslashes($property['property_type']);
	  $company_name = stripslashes($property['company_name']);
	  $prospect_id = stripslashes($property['prospect_id']);
	  $beazer_file = stripslashes($property['beazer_file']);
	  
	  $phone = stripslashes($property['phone']);
	  $phone2 = stripslashes($property['phone2']);
	  $phone3 = stripslashes($property['phone3']);
	  $mobile = stripslashes($property['mobile']);
	  $mobile2 = stripslashes($property['mobile2']);
	  $mobile3 = stripslashes($property['mobile3']);
	  $property_phone = $phone . "," . $phone2 . "," . $phone3 . "," . $mobile . "," . $mobile2 . "," . $mobile3;
	  $row[$counter]['phone'] = $property_phone;
	  
	  $address = stripslashes($property['address']);
	  $city = stripslashes($property['city']);
	  $state = stripslashes($property['state']);
	  $row[$counter]['address'] = $address;
	  $row[$counter]['city'] = $city;
	  $row[$counter]['state'] = $state;
	  $row[$counter]['identifier'] = stripslashes($property['identifier']);
		
	  $row[$counter]['id_status'] = stripslashes($property['id_status']);
	  $row[$counter]['prospect_status'] = stripslashes($property['propsect_status']);
	  
	  $row[$counter]['money'] = $property['money'];
	  
	  $sales_stage = $property['sales_stage'];
	  $row[$counter]['sales_stage'] = $sales_stage_array[$sales_stage];
	  
	  $row[$counter]['property_type'] = "Beazer";

	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = $site_name;
	  $row[$counter]['property_id'] = $property_id;
	  $row[$counter]['number'] = $beazer_file;
	  $row[$counter]['searchfield'] = "";
	  $row[$counter]['roof_size'] = stripslashes($property['roof_size']);
	  $row[$counter]['ssdate'] = $property['ssdate'];
	  $row[$counter]['lastaction'] = stripslashes($property['lastaction_pretty']);
	  $row[$counter]['region'] = $property['region'];
	  
	  $counter++;
	}
	break;
  }
  
  case "beazer_status":{
    $searchlable = "";
	$search_display = "property";
    $counter=0;
	$sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, c.beazer_file, 
	a.phone, a.phone2, a.phone3, a.mobile, a.mobile2, a.mobile3, 
	a.address, a.city, a.state, a.identifier, c.id_status, b.prospect_status, a.sales_stage, c.settlement_number as money, a.roof_size, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region
	from 
	properties a, prospects b, properties_beazer c
	where a.prospect_id = b.prospect_id 
	and a.property_id = c.property_id 
	and c.beazer_status like '$searchfor%' and a.corporate=0 and a.display=1 order by a.site_name";
	$result = executequery($sql);
	while($property = go_fetch_array($result)){
	  $site_name = stripslashes($property['site_name']);
	  $property_id = stripslashes($property['property_id']);
	  $property_type = stripslashes($property['property_type']);
	  $company_name = stripslashes($property['company_name']);
	  $prospect_id = stripslashes($property['prospect_id']);
	  $beazer_file = stripslashes($property['beazer_file']);
	  
	  $phone = stripslashes($property['phone']);
	  $phone2 = stripslashes($property['phone2']);
	  $phone3 = stripslashes($property['phone3']);
	  $mobile = stripslashes($property['mobile']);
	  $mobile2 = stripslashes($property['mobile2']);
	  $mobile3 = stripslashes($property['mobile3']);
	  $property_phone = $phone . "," . $phone2 . "," . $phone3 . "," . $mobile . "," . $mobile2 . "," . $mobile3;
	  $row[$counter]['phone'] = $property_phone;
	  
	  $address = stripslashes($property['address']);
	  $city = stripslashes($property['city']);
	  $state = stripslashes($property['state']);
	  $row[$counter]['address'] = $address;
	  $row[$counter]['city'] = $city;
	  $row[$counter]['state'] = $state;
	  $row[$counter]['identifier'] = stripslashes($property['identifier']);
		
	  $row[$counter]['id_status'] = stripslashes($property['id_status']);
	  $row[$counter]['prospect_status'] = stripslashes($property['propsect_status']);
	  
	  $row[$counter]['money'] = $property['money'];
	  
	  $sales_stage = $property['sales_stage'];
	  $row[$counter]['sales_stage'] = $sales_stage_array[$sales_stage];
	  
	  $row[$counter]['property_type'] = "Beazer";

	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = $site_name;
	  $row[$counter]['property_id'] = $property_id;
	  $row[$counter]['number'] = $beazer_file;
	  $row[$counter]['searchfield'] = "";
	  $row[$counter]['roof_size'] = stripslashes($property['roof_size']);
	  $row[$counter]['ssdate'] = $property['ssdate'];
	  $row[$counter]['lastaction'] = stripslashes($property['lastaction_pretty']);
	  $row[$counter]['region'] = $property['region'];
	  
	  $counter++;
	}
	break;
  }
  
  case "key_number":{
    $searchlable = "";
	$search_display = "property";
    $counter=0;
	$sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, c.key_number, 
	a.phone, a.phone2, a.phone3, a.mobile, a.mobile2, a.mobile3, 
	a.address, a.city, a.state, a.identifier, b.prospect_status, a.sales_stage, c.comp_amount as money, a.roof_size, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region
	from 
	properties a, prospects b, properties_manville c
	where a.prospect_id = b.prospect_id 
	and a.property_id = c.property_id 
	and c.key_number like '$searchfor%' and a.corporate=0 and a.display=1 order by a.site_name";
	$result = executequery($sql);
	while($property = go_fetch_array($result)){
	  $site_name = stripslashes($property['site_name']);
	  $property_id = stripslashes($property['property_id']);
	  $property_type = stripslashes($property['property_type']);
	  $company_name = stripslashes($property['company_name']);
	  $prospect_id = stripslashes($property['prospect_id']);
	  $key_number = stripslashes($property['key_number']);
	  
	  $phone = stripslashes($property['phone']);
	  $phone2 = stripslashes($property['phone2']);
	  $phone3 = stripslashes($property['phone3']);
	  $mobile = stripslashes($property['mobile']);
	  $mobile2 = stripslashes($property['mobile2']);
	  $mobile3 = stripslashes($property['mobile3']);
	  $property_phone = $phone . "," . $phone2 . "," . $phone3 . "," . $mobile . "," . $mobile2 . "," . $mobile3;
	  $row[$counter]['phone'] = $property_phone;
	  
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
	  $row[$counter]['money'] = $property['money'];
	  $row[$counter]['property_type'] = "Manville";

	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = $site_name;
	  $row[$counter]['property_id'] = $property_id;
	  $row[$counter]['number'] = $key_number;
	  $row[$counter]['searchfield'] = "";
	  $row[$counter]['roof_size'] = stripslashes($property['roof_size']);
	  $row[$counter]['ssdate'] = $property['ssdate'];
	  $row[$counter]['lastaction'] = stripslashes($property['lastaction_pretty']);
	  $row[$counter]['region'] = $property['region'];
	  
	  $counter++;
	}
	break;
  }
  
  case "phone":{
    $counter=0;
	$search_display = "property";
	$searchlable = "Phone" . sort_arrows('searchfield');

	$sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, a.phone, a.mobile, 
	a.address, a.city, a.state, a.identifier, b.prospect_status, a.sales_stage, a.roof_size, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region
	from 
	properties a, prospects b 
	where a.prospect_id = b.prospect_id and a.corporate=0 and a.display=1 
	and (a.phone like '%$searchfor%' or a.mobile like '%$searchfor%') order by a.site_name";
	$result = executequery($sql);
	while($property = go_fetch_array($result)){
	  $site_name = stripslashes($property['site_name']);
	  $property_id = stripslashes($property['property_id']);
	  $property_type = stripslashes($property['property_type']);
	  $company_name = stripslashes($property['company_name']);
	  $prospect_id = stripslashes($property['prospect_id']);
	  $phone = stripslashes($property['phone']);
	  $mobile = stripslashes($property['mobile']);
	  if($property_type=="Manville"){
		$sql = "SELECT key_number from properties_manville where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT comp_amount from properties_manville where property_id='$property_id'";
		$money = getsingleresult($sql);
	  }
	  if($property_type=="Beazer" || $property_type== "Beazer B"){
		$sql = "SELECT beazer_file from properties_beazer where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT id_status from properties_beazer where property_id='$property_id'";
		$id_status = getsingleresult($sql);
		$row[$counter]['id_status'] = $id_status;
		$sql = "SELECT settlement_number from properties_beazer where property_id='$property_id'";
		$money = getsingleresult($sql);
	  }
	  if($property_type=="Non-PFRI"){
		$number = "";
		$money = "";
	  }
	  
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
	  
	  $row[$counter]['property_type'] = $property_type;
		
	  $searchfield = $phone;
	  if($mobile != "") $searchfield .= "<br>" . $mobile;
	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = $site_name;
	  $row[$counter]['property_id'] = $property_id;
	  $row[$counter]['number'] = $number;
	  $row[$counter]['searchfield'] = $searchfield;
	  $row[$counter]['number'] = $searchfield;
	  $row[$counter]['money'] = $money;
	  $row[$counter]['roof_size'] = stripslashes($property['roof_size']);
	  $row[$counter]['ssdate'] = $property['ssdate'];
	  $row[$counter]['lastaction'] = stripslashes($property['lastaction_pretty']);
	  $row[$counter]['region'] = $property['region'];
	  
	  $counter++;
	}
	
	break;
  }
  
  case "city":{
    $counter=0;
	$search_display = "property";
	$searchlable = ucwords($searchby) . sort_arrows('searchfield');
	
	$propsql = "SELECT a.site_name, a.property_id, a.city, a.state, a.roof_size, a.identifier, 
	c.sales_stage as stage, a.sales_stage_change_date as stagechange, d.company_name, d.prospect_id, 
	a.phone, a.phone2, a.phone3, a.mobile, a.mobile2, a.mobile3, 
	a.address, a.identifier, a.property_type, d.prospect_status, a.sales_stage, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region
	from properties a, sales_stage c, prospects d
	where a.display=1 and a.corporate = 0 
	and a.sales_stage = c.sales_stage_id and a.prospect_id = d.prospect_id 
	and a.city like '%$searchfor%' and a.display=1 order by a.site_name";
	$sql = $propsql;
    $result = executequery($sql);
    $counter=0;
    while($property = go_fetch_array($result)){
	  $property_id = $property['property_id'];
	  $phone = stripslashes($property['phone']);
	  $phone2 = stripslashes($property['phone2']);
	  $phone3 = stripslashes($property['phone3']);
	  $mobile = stripslashes($property['mobile']);
	  $mobile2 = stripslashes($property['mobile2']);
	  $mobile3 = stripslashes($property['mobile3']);
	  $property_phone = $phone . "," . $phone2 . "," . $phone3 . "," . $mobile . "," . $mobile2 . "," . $mobile3;
	  $row[$counter]['phone'] = $property_phone;
	  
	  $address = stripslashes($property['address']);
	  $row[$counter]['address'] = $address;
	  $row[$counter]['identifier'] = stripslashes($property['identifier']);
	  $row[$counter]['prospect_status'] = stripslashes($property['propsect_status']);
	  
	  $sales_stage = $property['sales_stage'];
	  $row[$counter]['sales_stage'] = $sales_stage_array[$sales_stage];
	  
	  $property_type = $property['property_type'];
	  
	  if($property_type=="Manville"){
		$sql = "SELECT key_number from properties_manville where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT comp_amount from properties_manville where property_id='$property_id'";
		$money = getsingleresult($sql);
	  }
	  if($property_type=="Beazer" || $property_type== "Beazer B"){
		$sql = "SELECT beazer_file from properties_beazer where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT id_status from properties_beazer where property_id='$property_id'";
		$id_status = getsingleresult($sql);
		$row[$counter]['id_status'] = $id_status;
		$sql = "SELECT settlement_number from properties_beazer where property_id='$property_id'";
		$money = getsingleresult($sql);
		
	  }
	  if($property_type=="Non-PFRI"){
		$number = "";
		$money = "";
	  }
		
	  $row[$counter]['company_name'] = stripslashes($property['company_name']);
	  $row[$counter]['prospect_id'] = stripslashes($property['prospect_id']);
	  $row[$counter]['property_id'] = stripslashes($property['property_id']);
	  $row[$counter]['site_name'] = stripslashes($property['site_name']);
	  $row[$counter]['city'] = stripslashes($property['city']);
	  $row[$counter]['state'] = stripslashes($property['state']);
	  $row[$counter]['roof_size'] = stripslashes($property['roof_size']);
	  $row[$counter]['identifier'] = stripslashes($property['identifier']);
	  $row[$counter]['number'] = $number;
	  $row[$counter]['money'] = $money;
	  $row[$counter]['stage'] = $property['stage'];
	  $row[$counter]['stagechange'] = stripslashes($property['stagechange']);
	  $row[$counter]['property_type'] = $property_type;
	  $row[$counter]['ssdate'] = $property['ssdate'];
	  $row[$counter]['lastaction'] = stripslashes($property['lastaction_pretty']);
	  $row[$counter]['region'] = $property['region'];
	  
	  $counter++;
	}
	break;
  }
  
  default:{
    $counter=0;
	$search_display = "property";
	$searchlable = ucwords($searchby) . sort_arrows('searchfield');
	$sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, a.$searchby as searchfield, 
	a.phone, a.phone2, a.phone3, a.mobile, a.mobile2, a.mobile3, 
	a.address, a.city, a.state, a.identifier, b.prospect_status, a.sales_stage, a.roof_size, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region
	from 
	properties a, prospects b 
	where a.prospect_id = b.prospect_id and a.$searchby like '%$searchfor%' and a.corporate=0 and a.display=1 order by a.site_name";
	$result = executequery($sql);
	while($property = go_fetch_array($result)){
	  $site_name = stripslashes($property['site_name']);
	  $property_id = stripslashes($property['property_id']);
	  $property_type = stripslashes($property['property_type']);
	  $company_name = stripslashes($property['company_name']);
	  $prospect_id = stripslashes($property['prospect_id']);
	  $searchfield = stripslashes($property['searchfield']);
	  if($property_type=="Manville"){
		$sql = "SELECT key_number from properties_manville where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT comp_amount from properties_manville where property_id='$property_id'";
		$money = getsingleresult($sql);
	  }
	  if($property['property_type']=="Beazer" || $property['property_type']== "Beazer B"){
		$sql = "SELECT id_status from properties_beazer where property_id='$property_id'";
		$id_status = getsingleresult($sql);
		$row[$counter]['id_status'] = $id_status;
	  }
	  if($property_type=="Beazer" || $property_type== "Beazer B"){
		$sql = "SELECT beazer_file from properties_beazer where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT settlement_number from properties_beazer where property_id='$property_id'";
		$money = getsingleresult($sql);
	  }
	  if($property_type=="Non-PFRI"){
		$number = "";
		$money = "";
	  }
	  $phone = stripslashes($property['phone']);
	  $phone2 = stripslashes($property['phone2']);
	  $phone3 = stripslashes($property['phone3']);
	  $mobile = stripslashes($property['mobile']);
	  $mobile2 = stripslashes($property['mobile2']);
	  $mobile3 = stripslashes($property['mobile3']);
	  $property_phone = $phone . "," . $phone2 . "," . $phone3 . "," . $mobile . "," . $mobile2 . "," . $mobile3;
	  $row[$counter]['phone'] = $property_phone;
	  
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
	  
	  $counter++;
	}
	break;
  }

  case "id_status":{  // beazer only
    $searchlable = "ID Status" . sort_arrows('searchfield');
	$search_display = "property";
    $counter=0;
	
	$sql = "SELECT id, status from beazer_id_status where status like '$searchfor%'";
	$result_stat = executequery($sql);
	while($stat = go_fetch_array($result_stat)){
	$status = $stat['status'];
	$sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, c.beazer_file, c.beazer_id_status, 
	a.phone, a.phone2, a.phone3, a.mobile, a.mobile2, a.mobile3, 
	a.address, a.city, a.state, a.identifier, b.prospect_status, a.sales_stage, a.roof_size, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region
	from 
	properties a, prospects b, properties_beazer c
	where a.prospect_id = b.prospect_id 
	and a.property_id = c.property_id 
	and c.beazer_id_status ='" . $stat['id'] . "' and a.corporate=0 and a.display=1 order by a.site_name";
	$result = executequery($sql);
	while($property = go_fetch_array($result)){
	  $site_name = stripslashes($property['site_name']);
	  $property_id = stripslashes($property['property_id']);
	  $property_type = stripslashes($property['property_type']);
	  $company_name = stripslashes($property['company_name']);
	  $prospect_id = stripslashes($property['prospect_id']);
	  $beazer_file = stripslashes($property['beazer_file']);
	  $beazer_id_status = stripslashes($property['beazer_id_status']);
	  
	  $phone = stripslashes($property['phone']);
	  $phone2 = stripslashes($property['phone2']);
	  $phone3 = stripslashes($property['phone3']);
	  $mobile = stripslashes($property['mobile']);
	  $mobile2 = stripslashes($property['mobile2']);
	  $mobile3 = stripslashes($property['mobile3']);
	  $property_phone = $phone . "," . $phone2 . "," . $phone3 . "," . $mobile . "," . $mobile2 . "," . $mobile3;
	  $row[$counter]['phone'] = $property_phone;
	  
	  $money = "";
	  if($property['property_type']=="Beazer" || $property['property_type']== "Beazer B"){
		$sql = "SELECT id_status from properties_beazer where property_id='$property_id'";
		$id_status = getsingleresult($sql);
		$row[$counter]['id_status'] = $id_status;
		$sql = "SELECT settlement_number from properties_beazer where property_id='$property_id'";
		$money = getsingleresult($sql);
	  }
	  
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
	  $row[$counter]['number'] = $beazer_file;
	  $row[$counter]['searchfield'] = $status;
	  $row[$counter]['money'] = $money;
	  $row[$counter]['roof_size'] = stripslashes($property['roof_size']);
	  $row[$counter]['property_type'] = "Beazer";
	  $row[$counter]['ssdate'] = $property['ssdate'];
	  $row[$counter]['lastaction'] = stripslashes($property['lastaction_pretty']);
	  $row[$counter]['region'] = $property['region'];
	  
	  $counter++;
	}
	}
	break;
  }
  
  case "prospect_status":{
    $searchlable = "Company Status" . sort_arrows('searchfield');
	$search_display = "company";
    $counter=0;
    $sql = "SELECT prospect_id, company_name, prospect_status, city, state, 
	phone1, mobile1, phone2, mobile2, phone3, mobile3, phone4, mobile4, phone5, mobile5, properties, property_type, 
	date_format(lastaction, \"%m/%d/%Y\") as lastaction_pretty
	from prospects 
	where prospect_status =\"$prospect_status_searchby\" and display=1 order by company_name";
	$result = executequery($sql);
	while($company = go_fetch_array($result)){
	  $prospect_id = $company['prospect_id'];
	  $company_name = stripslashes($company['company_name']);
	  $phone1 = stripslashes($company['phone1']);
	  $phone2 = stripslashes($company['phone2']);
	  $phone3 = stripslashes($company['phone3']);
	  $phone4 = stripslashes($company['phone4']);
	  $phone5 = stripslashes($company['phone5']);
	  $mobile1 = stripslashes($company['mobile1']);
	  $mobile2 = stripslashes($company['mobile2']);
	  $mobile3 = stripslashes($company['mobile3']);
	  $mobile4 = stripslashes($company['mobile4']);
	  $mobile5 = stripslashes($company['mobile5']);
	  $company_phone = $phone1 . "," . $phone2 . "," . $phone3 . "," . $phone4 . "," . $phone5 . ",";
	  $company_phone .= $mobile1 . "," . $mobile2 . "," . $mobile3 . "," . $mobile4 . "," . $mobile5;
	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = "";
	  $row[$counter]['property_id'] = "";
	  $row[$counter]['number'] = "";
	  $row[$counter]['searchfield'] = "";
	  $row[$counter]['phone'] = $company_phone;
	  $row[$counter]['prospect_status'] = stripslashes($company['propsect_status']);
	  $row[$counter]['city'] = stripslashes($company['city']);
	  $row[$counter]['state'] = stripslashes($company['state']);
	  $row[$counter]['properties'] = stripslashes($company['properties']);
	  $row[$counter]['property_type'] = stripslashes($company['property_type']);
	  $row[$counter]['lastaction'] = stripslashes($company['lastaction_pretty']);
	  $row[$counter]['region'] = $company['region'];
		
	  $counter++;
	}
	break;
  }
  
  case "sales_stage":{
    $searchlable = "Sales Stage" . sort_arrows('searchfield');
	$search_display = "property";
    $counter=0;
	
    $sql = "SELECT sales_stage_id from sales_stage where sales_stage like '$searchfor%'";
	$result_stage = executequery($sql);
	while($rec_stage = go_fetch_array($result_stage)){
	
	
	$sales_stage_searchby = $rec_stage['sales_stage_id'];
    
	$sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, d.sales_stage, 
	a.phone, a.phone2, a.phone3, a.mobile, a.mobile2, a.mobile3, 
	a.address, a.city, a.state, a.identifier, b.prospect_status, d.sales_stage_id, a.roof_size, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region
	from 
	properties a, prospects b, sales_stage d
	where a.prospect_id = b.prospect_id 
	and a.sales_stage = d.sales_stage_id 
	and a.sales_stage = '$sales_stage_searchby' and a.corporate=0 and a.display=1 order by a.site_name";
	$result = executequery($sql);
	while($property = go_fetch_array($result)){
	  $site_name = stripslashes($property['site_name']);
	  $property_id = stripslashes($property['property_id']);
	  $property_type = stripslashes($property['property_type']);
	  $company_name = stripslashes($property['company_name']);
	  $prospect_id = stripslashes($property['prospect_id']);
	  $key_number = stripslashes($property['key_number']);
	  $sales_stage = stripslashes($property['sales_stage']);
	  
	  $phone = stripslashes($property['phone']);
	  $phone2 = stripslashes($property['phone2']);
	  $phone3 = stripslashes($property['phone3']);
	  $mobile = stripslashes($property['mobile']);
	  $mobile2 = stripslashes($property['mobile2']);
	  $mobile3 = stripslashes($property['mobile3']);
	  $property_phone = $phone . "," . $phone2 . "," . $phone3 . "," . $mobile . "," . $mobile2 . "," . $mobile3;
	  $row[$counter]['phone'] = $property_phone;
	  
	  if($property_type=="Manville"){
		$sql = "SELECT key_number from properties_manville where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT comp_amount from properties_manville where property_id='$property_id'";
		$money = getsingleresult($sql);
	  }
	  if($property_type=="Beazer" || $property_type== "Beazer B"){
		$sql = "SELECT beazer_file from properties_beazer where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT id_status from properties_beazer where property_id='$property_id'";
		$id_status = getsingleresult($sql);
		$row[$counter]['id_status'] = $id_status;
		$sql = "SELECT settlement_number from properties_beazer where property_id='$property_id'";
		$money = getsingleresult($sql);
	  }
	  if($property_type=="Non-PFRI"){
		$number = "";
		$money = "";
	  }
	  
	  $address = stripslashes($property['address']);
	  $city = stripslashes($property['city']);
	  $state = stripslashes($property['state']);
	  $row[$counter]['address'] = $address;
	  $row[$counter]['city'] = $city;
	  $row[$counter]['state'] = $state;
	  $row[$counter]['identifier'] = stripslashes($property['identifier']);
	  $row[$counter]['prospect_status'] = stripslashes($property['propsect_status']);
	  
	  $sales_stage_id = $property['sales_stage_id'];
	  $row[$counter]['sales_stage'] = $sales_stage_array[$sales_stage_id];

	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = $site_name;
	  $row[$counter]['property_id'] = $property_id;
	  $row[$counter]['number'] = $number;
	  $row[$counter]['searchfield'] = $sales_stage;
	  $row[$counter]['money'] = $money;
	  $row[$counter]['roof_size'] = stripslashes($property['roof_size']);
	  $row[$counter]['property_type'] = $property_type;
	  $row[$counter]['ssdate'] = $property['ssdate'];
	  $row[$counter]['lastaction'] = stripslashes($property['lastaction_pretty']);
	  $row[$counter]['region'] = $property['region'];
	  
	  $counter++;
	}
	
	}
	break;
  }
  
  } // end switch		
  
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
    if($searchfor2 != ""){
	  if(!(go_reg(strtoupper($searchfor2), strtoupper($row[$x][$real_searchby2])))) continue;
	}
	if($view_filter_property_type != ""){
	  if(!(go_reg($view_filter_property_type, $row[$x]['property_type']))) continue;
	}
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
    if($searchfor2 != ""){
	  if(!(go_reg(strtoupper($searchfor2), strtoupper($row[$x][$real_searchby2])))) continue;
	}
	if($view_filter_property_type != ""){
	  if(!(go_reg($view_filter_property_type, $row[$x]['property_type']))) continue;
	}
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
<script>
searchby_select(document.searchform.searchby);
</script>

<?php include "includes/footer.php"; ?>