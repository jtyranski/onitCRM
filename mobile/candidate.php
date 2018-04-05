<?php include "includes/header.php"; ?>
<?php
$level = $_GET['level'];
if($level == "") $level = $_SESSION['cand_level'];
if($level == "") $level = 0;
$_SESSION['cand_level'] = $level;

$level_clause = " 1=1 ";
if($level > 0) $level_clause = " b.prospecting_rating = '$level' ";

$filter_array = array();

$prospect_type = $_SESSION['cand_prospect_type'];
if($prospect_type){
  $prospect_type_clause = " a.originated='$prospect_type' ";
  $filter_array[] = $prospect_type;
}
else {
  $prospect_type_clause = " 1=1 ";
}

/*
$import = $_SESSION['cand_import'];
if($import){
  $import_clause = " c.identifier= '$import' ";
  $filter_array[] = $import;
}
else {
  $import_clause = " 1=1 ";
}
*/
$region = $_SESSION['cand_region'];
if($region){
  $region_clause = " b.territory = '$region' ";
  $filter_array[] = "Region: " . $region;
}
else {
  $region_clause = " 1=1 ";
}

$zip = $_SESSION['cand_zip'];
$distance = $_SESSION['cand_distance'];

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
		$sql_zip="SELECT b.zip, b.prospect_id from prospecting_todo a, prospects b 
		where a.prospect_id=b.prospect_id and a.user_id='" . $SESSION_USER_ID . "' group by a.prospect_id";
		$result_zip=executeQuery($sql_zip);
		while($line=go_fetch_array($result_zip))
		{
			$x = $line['zip'];
			$x = go_reg_replace("\-.*", "", $x);
			//$distance2=great_circle_distance($search_latitude,$line['latitude'],$search_longitude,$line['longitude']);
			$distance2=great_circle_distance($search_latitude,$latitude[$x],$search_longitude,$longitude[$x]);
			//echo "<br>distance2:$distance2<br>";
			if($distance2 <= $distance)
			{
				$srch_serv_prov[]= $line['prospect_id'];
			}
		}
		$zip_clause.=" a.prospect_id in ('".implode("','", $srch_serv_prov)."') ";
		$filter_array[] = $zip . " (" . $distance . ")mi";
  }
  else {
    $zip_clause = " c.zip = '$zip' ";
	$filter_array[] = $zip;
  }
}
else {
  $zip_clause = " 1=1 ";
}

if(sizeof($filter_array)==0){
  $filter_display = "Add Filter";
}
else {
  for($x=0;$x<sizeof($filter_array);$x++){
    $filter_display .= $filter_array[$x] . "/";
  }
}
$filter_display = go_reg_replace("\/$", "", $filter_display);
?>

<table class="main" width="100%">
<tr>
<td valign="top">
<?php include "includes/main_nav.php"; ?>
</td>
<td align="right" valign="top">
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="form1">
<select name="level" onChange="document.form1.submit()">
<option value="0"<?php if($level==0) echo " selected";?>>All</option>
<option value="1"<?php if($level==1) echo " selected";?>>Level 1</option>
<option value="2"<?php if($level==2) echo " selected";?>>Level 2</option>
<option value="3"<?php if($level==3) echo " selected";?>>Level 3</option>
</select>
</form>
<br />

</td>
</tr>
</table>
<table class="main" width="100%">
<tr>
<td colspan="2" align="center">
<a href="candidate_filter.php" class="large"><?=$filter_display?></a>
</td>
</tr>
<tr>
<td colspan="2" align="center" bgcolor="#666666" class="main_large">
<?=date("D, M d, Y")?>
</td>
</tr>
<?php
$user_id = $SESSION_USER_ID;

$sql = "SELECT a.id, a.property_id, a.result, b.site_name, 
b.city, b.state, a.prospect_id, c.company_name, c.city as ccity, 
c.state as cstate, b.corporate, a.originated
from prospecting_todo a, properties b, prospects c
where a.property_id=b.property_id and a.prospect_id = c.prospect_id 
and a.user_id='$user_id' 
and a.complete=0  
and $prospect_type_clause 
and $region_clause 
and $level_clause 
and $zip_clause 
and a.date_added like '" . date("Y-m-d") . "%' 
order by c.company_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){

  switch($record['originated']){
    case "Dial":{
	  $image = "contact.jpg";
	  break;
	}
	case "Visit":{
	  $image = "meeting.jpg";
	  break;
	}
	default:{
	  $image = "contact.jpg";
	  break;
	}
  }
  $corporate = $record['corporate'];
  /*
  if($corporate){
    $city = stripslashes($record['ccity']);
	$state = stripslashes($record['cstate']);
  }
  else {
    $city = stripslashes($record['city']);
	$state = stripslashes($record['state']);
  }
  */
  $city = stripslashes($record['ccity']);
  $state = stripslashes($record['cstate']);

	$company_name = stripslashes($record['company_name']);
	if(strlen($company_name) > 28) $company_name = substr($company_name, 0, 28) . "&hellip;";
    ?>
	<tr>
	<td valign="top">
	<a href="candidate_details.php?id=<?=$record['id']?>">
	<img src="images/<?=$image?>" border="0">
	</a>
	</td>
	<td valign="top">
	<a href="candidate_details.php?id=<?=$record['id']?>" class="large"><?=$company_name?></a><br>
	<a href="candidate_details.php?id=<?=$record['id']?>"><?=$city?>, <?=$state?></a>
	</td>
	</tr>
	<?php
}
?>
</table>
	
<?php include "includes/footer.php"; ?>