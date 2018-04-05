<?php
ob_start("ob_gzhandler");
session_start();
include "includes/header.php";

$sql = "SELECT googlemap_id from users where googlemap_id != '' and googlemap_zipcode = ''";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $googlemap_ids .= stripslashes($record['googlemap_id']) . ",";
}
$googlemap_ids = go_reg_replace(",$", "", $googlemap_ids);

$check_rr = $_GET['check_rr'];
$check_rm = $_GET['check_rm'];
$check_sold = $_GET['check_sold'];

$filter_property_type = $_GET['filter_property_type'];
$filter_region = $_GET['filter_region'];
$filter_territory = $_GET['filter_territory'];

$submit1 = $_GET['submit1'];
$lat = $_GET['lat'];
$lng = $_GET['lng'];
$filter_address = $_GET['filter_address'];
$filter_address_radio = $_GET['filter_address_radio'];
if($filter_address_radio == "") $filter_address_radio = "M";

if($lat != "" && $filter_address_radio == "M"){
  $latitude = $lat;
  $longitude = $lng;
  $zoomlevel = 10;
}
else {
  $latitude = 37.25;
  $longitude = -96.857788;
  $zoomlevel = 5;
}

if($submit1==""){
  $check_rm = 1;
  $check_rr = 1;
  $check_qb = 1;
  $check_complete = 1;
  $check_sold = 1;
}

if($filter_property_type != ""){
  if($filter_property_type == "Manville"){
    $property_type_clause = " (a.property_type = 'Manville' or a.property_type='Beazer B') ";
  }
  else {
    $property_type_clause = " a.property_type = '$filter_property_type' ";
  }
}
else {
  $property_type_clause = " 1=1 ";
}

if($filter_region != ""){
  $region_clause = " a.region = '$filter_region' ";
}
else {
  $region_clause = " 1=1 ";
}

if($filter_territory != ""){
  $territory_clause = " a.territory = '$filter_territory' ";
}
else {
  $territory_clause = " 1=1 ";
}

if($filter_address_radio == "P"){
  $site_name_clause = " a.site_name like \"%$filter_address%\" ";
}
else {
  $site_name_clause = " 1=1 ";
}

$sql = "SELECT * from properties_prospectingtype";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospecting_type = $record['prospecting_type'];
  $prospecting_desc = stripslashes($record['prospecting_desc']);
  $prospecting[$prospecting_type] = $prospecting_desc;
}

$check_not_prospect = $_GET['check_not_prospect'];
if($check_not_prospect=="") $check_not_prospect=0;

if($check_not_prospect){
  $not_prospect_clause = " 1=1 ";
}
else {
  $not_prospect_clause = " a.prospecting_type != 2 ";
}

/*
==== This page will read properties from our database, and map them on our screen ====
==== Note: This code is created using the Google Maps API explained in their official ====
====  documentation at http://code.google.com/apis/maps/documentation/index.html ====
==== Jim Tyranski - May 8, 2009 ====
*/

$PM[0] = "None";
$sql = "SELECT user_id, concat(firstname, ' ' , lastname) as fullname from users where enabled=1 and project_manager=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $user_id = $record['user_id'];
  $PM[$user_id] = stripslashes($record['fullname']);
}
//echo "<!-- " . sizeof($_SESSION['sales_map_property_id']) . " -->\n";
$counter = 0;
for($x=0;$x<sizeof($_SESSION['sales_map_property_id']);$x++){
  $x_property_id = $_SESSION['sales_map_property_id'][$x];
  
$sql = "SELECT a.property_id, site_name, address, city, state, zip, roof_size,  
concat('view_property.php?property_id=', a.property_id, '&view=opportunities') as URL, 
b.latitude, b.longitude, a.sales_stage, a.prospecting_type, 
c.project_manager, c.crew, c.product, c.status, e.stage_name, c.probability, a.territory
FROM properties a, geocode b, opportunities c, opportunities_stages e
WHERE a.property_id=b.property_id and b.latitude != 0  
and a.property_id=c.property_id
and c.opp_stage_id = e.opp_stage_id 
and $property_type_clause and $region_clause and $site_name_clause and $territory_clause
and c.display=1
and a.property_id='$x_property_id' limit 1
";
//echo "<!-- $sql -->\n";
$result = executequery($sql);

while($record = go_fetch_array($result)){
  $property_id = $record['property_id'];
  $site_name = go_reg_replace("\'", "", $record['site_name']);
  $address = $record['address'];
  $city = $record['city'];
  $state = $record['state'];
  $zip = $record['zip'];
  $roof_size = $record['roof_size'];
  $identifier = $record['identifier'];
  $property_type = $record['property_type'];
  $prospecting_type = $record['prospecting_type'];
  $url = $record['URL'];
  //$url = "<a href=\'$url\' target=\'_blank\'>$url</a>";
  $site_name = "<a href=\"$url\" target=\'_blank\'>$site_name</a>";
  $region = $record['region'];
  $ro_status = $record['ro_status'];
  $sales_stage = $record['sales_stage'];
  $public_type = $record['public_type'];
  $territory = $record['territory'];
  $fulladdress = $address ."<br />". $city .", ". $state ." ". $zip;
  $classification = $record['classification'];
  $x_latitude = $record['latitude'];
  $x_longitude = $record['longitude'];
  $prodstat_name = $record['prodstat_name'];
  $fullname = stripslashes($record['fullname']);
  $crew = stripslashes($record['crew']);
  $project_manager = $record['project_manager'];
  $probability = $record['probability'];
  if($probability > 5) $probability=5;
  if($probability==0) $probability = 1;
  
  $product = $record['product'];
  if(go_reg("Roof Management", $product)) $product = "Roof Management";
  $status = $record['status'];
  $stage_name = $record['stage_name'];
  if($stage_name=="") $stage_name = "None";
  $group = "";
  if($status=="Prospect" && $product == "Roof Management") {
    $group = "Roof Management";
	$group_sub = $probability;
  }
  if($status=="Prospect" && $product == "Roof Replacement") {
    $group = "Roof Replacement";
	$group_sub = $probability;
  }
  if(($status=="Bid Procurement" || $status == "Quoted") && $product == "Roof Management"){
    $group = "Roof Management";
	$group_sub = "Q";
  }
  if(($status=="Bid Procurement" || $status == "Quoted") && $product == "Roof Replacement"){
    $group = "Roof Replacement";
	$group_sub = "Q";
  }
  if($status=="Budgeted" && $product == "Roof Management"){
    $group = "Roof Management";
	$group_sub = "B";
  }
  if($status=="Budgeted" && $product == "Roof Replacement"){
    $group = "Roof Replacement";
	$group_sub = "B";
  }
  
  if($status=="Sold" || $status == "Sold-LOI" || $status=="Complete") $group = "Sold";
  
  
  if($check_rm==0 && $group=="Roof Management") continue;
  if($check_rr==0 && $group=="Roof Replacement") continue;
  if($check_sold==0 && $group=="Sold") continue;

  
  
  $row[$counter]['site_name'] = $site_name;
  $row[$counter]['address'] = $address;
  $row[$counter]['city'] = $city;
  $row[$counter]['state'] = $state;
  $row[$counter]['zip'] = $zip;
  $row[$counter]['roof_size'] = $roof_size;
  $row[$counter]['url'] = $url;
  $row[$counter]['fulladdress'] = $fulladdress;
  $row[$counter]['classification'] = $classification;
  $row[$counter]['latitude'] = $x_latitude;
  $row[$counter]['longitude'] = $x_longitude;
  $row[$counter]['prodstat_name'] = $prodstat_name;
  $row[$counter]['crew'] = $crew;
  $row[$counter]['project_manager'] = $PM[$project_manager];
  $row[$counter]['product'] = $product;
  $row[$counter]['status'] = $status;
  $row[$counter]['stage_name'] = $stage_name;
  $row[$counter]['group'] = $group;
  $row[$counter]['group_sub'] = $group_sub;
  $row[$counter]['territory'] = $territory;
  
  $counter++;
}
} // end for loop
?>

	<style>
	<!--
	.main{
	  font-family:Verdana, Arial, Helvetica, sans-serif;
	  font-size:11px;
	}
	.title{
	  font-family:Arial, Helvetica, sans-serif;
	  font-weight:bold;
	  font-size:16px;
	}
	-->
	</style>
<!-- ========= This is our Google Map API license key ========== -->
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true
    &amp;key=<?=$GOOGLE_API?>"
    type="text/javascript">
</script>
<script type="text/javascript">
    //<![CDATA[



// ======== New map variable ========
    var map = null;

    var FCSUser;  // FCS Users

      var lat = <?=$latitude?>;
	  var lng = <?=$longitude?>;
	  var zoomlevel = <?=$zoomlevel?>;
// ======== Setup icon parameters ========
    var baseIcon = new GIcon();
      baseIcon.iconSize=new GSize(16,16);
      baseIcon.shadowSize=new GSize(0,0);
      baseIcon.iconAnchor=new GPoint(8,16);
      baseIcon.infoWindowAnchor=new GPoint(8,0);
	var zoomIcon = new GIcon();
      zoomIcon.iconSize=new GSize(24,38);
      zoomIcon.shadowSize=new GSize(0,0);
      zoomIcon.iconAnchor=new GPoint(8,16);
      zoomIcon.infoWindowAnchor=new GPoint(8,0);
	<?php include "map_markerinfo.js"; ?>

// ======== Location of custom icons ===========  
    var rr_1 = new GIcon(baseIcon, "http://encitegroup.com/roofoptions/googlemap/orange_1.png", null, "http://encitegroup.com/roofoptions/googlemap/orange_1.png");
    var rr_2 = new GIcon(baseIcon, "http://encitegroup.com/roofoptions/googlemap/orange_2.png", null, "http://encitegroup.com/roofoptions/googlemap/orange_2.png");
    var rr_3 = new GIcon(baseIcon, "http://encitegroup.com/roofoptions/googlemap/orange_3.png", null, "http://encitegroup.com/roofoptions/googlemap/orange_3.png");
    var rr_4 = new GIcon(baseIcon, "http://encitegroup.com/roofoptions/googlemap/orange_4.png", null, "http://encitegroup.com/roofoptions/googlemap/orange_4.png");
    var rr_5 = new GIcon(baseIcon, "http://encitegroup.com/roofoptions/googlemap/orange_5.png", null, "http://encitegroup.com/roofoptions/googlemap/orange_5.png");
    var rr_Q = new GIcon(baseIcon, "http://encitegroup.com/roofoptions/googlemap/orange_q.png", null, "http://encitegroup.com/roofoptions/googlemap/orange_q.png");
    var rr_B = new GIcon(baseIcon, "http://encitegroup.com/roofoptions/googlemap/orange_b.png", null, "http://encitegroup.com/roofoptions/googlemap/orange_b.png");
	var rm_1 = new GIcon(baseIcon, "http://encitegroup.com/roofoptions/googlemap/white_1.png", null, "http://encitegroup.com/roofoptions/googlemap/white_1.png");
	var rm_2 = new GIcon(baseIcon, "http://encitegroup.com/roofoptions/googlemap/white_2.png", null, "http://encitegroup.com/roofoptions/googlemap/white_2.png");
	var rm_3 = new GIcon(baseIcon, "http://encitegroup.com/roofoptions/googlemap/white_3.png", null, "http://encitegroup.com/roofoptions/googlemap/white_3.png");
	var rm_4 = new GIcon(baseIcon, "http://encitegroup.com/roofoptions/googlemap/white_4.png", null, "http://encitegroup.com/roofoptions/googlemap/white_4.png");
	var rm_5 = new GIcon(baseIcon, "http://encitegroup.com/roofoptions/googlemap/white_5.png", null, "http://encitegroup.com/roofoptions/googlemap/white_5.png");
	var rm_Q = new GIcon(baseIcon, "http://encitegroup.com/roofoptions/googlemap/white_q.png", null, "http://encitegroup.com/roofoptions/googlemap/white_q.png");
	var rm_B = new GIcon(baseIcon, "http://encitegroup.com/roofoptions/googlemap/white_b.png", null, "http://encitegroup.com/roofoptions/googlemap/white_b.png");
	var sold = new GIcon(baseIcon, "http://encitegroup.com/roofoptions/googlemap/blue_bldg.png", null, "http://encitegroup.com/roofoptions/googlemap/blue_bldg.png");

	var googlemap = new GIcon(zoomIcon, "http://maps.gstatic.com/intl/en_us/mapfiles/icon_yellow1.png ", null, "http://maps.gstatic.com/intl/en_us/mapfiles/icon_yellow1.png ");

// =========== Initialize our map, set center, zoom level, hybrid view, UI controls, and allow info window =============
    function initialize() {
      if (GBrowserIsCompatible()) {
	// Add user badges to the map, by separating their user id with commas
        FCSUser = new GGeoXml("http://www.google.com/latitude/apps/badge/api?user=<?=$googlemap_ids?>&type=kml");
	map = new GMap2(document.getElementById("map_canvas"));
        map.setCenter(new GLatLng(lat, lng), zoomlevel);
	map.setMapType(G_PHYSICAL_MAP);
        map.setUIToDefault();
	map.enableInfoWindow();
        map.addOverlay(FCSUser);
      }
    }
	<?php
	/*
	* G_NORMAL_MAP displays the default road map view.
    * G_SATELLITE_MAP displays Google Earth satellite images. *
    * G_HYBRID_MAP displays a mixture of normal and satellite views.*
    * G_DEFAULT_MAP_TYPES contains an array of the above three types, useful for iterative processing.
    * G_PHYSICAL_MAP displays a physical map based on terrain information. 
	*/?>

// ============ display a new marker ================
    function showAddress(point,html,icon) {
      var marker = new GMarker(point,icon);
      GEvent.addListener(marker,"click",function() {
		  marker.openInfoWindowHtml("<span style='font-family:tahoma,helvetica,sans-serif;font-size:14px;'>"+ html +"<br>"+ point +"</span>");
		});
      map.addOverlay(marker);
     }
	 
	 function showAddressZoom(point,html,icon) {
      var marker = new GMarker(point,icon);
      GEvent.addListener(marker,"click",function() {
		  marker.openInfoWindowHtml("<span style='font-family:tahoma,helvetica,sans-serif;font-size:14px;'>"+ html +"<br>"+ point +"</span>");
		});
      map.addOverlay(marker);
     }

    </script>
  </head>
  <body onUnload="GUnload()" onLoad="runmefirst()">  <!-- ==== unload API when finished ==== -->
<div align="center">
  <div class="whiteround" style="height:800px;" id="map">
<form action="map_sales_status_action.php" method="get">
<table class="main" width="100%">
<tr>
<td valign="top">


<table cellpadding="3" class="main">
<tr>
<td align="center">
<input type="checkbox" name="check_rm" value="1"<?php if($check_rm) echo " checked";?>>
</td>
<td align="center">
<input type="checkbox" name="check_rr" value="1"<?php if($check_rr) echo " checked";?>>
</td>

<td align="center">
<input type="checkbox" name="check_sold" value="1"<?php if($check_sold) echo " checked";?>>
</td>

</tr>
<tr>
<td align="center" valign="top">
<img src="googlemap/rm_white.png">
</td>
<td align="center" valign="top">
<img src="googlemap/rr_orange.png">
</td>

<td align="center" valign="top">
<img src="googlemap/blue_bldg.png">
</td>

</tr>
<tr>
<td align="center">
Roof Management
</td>
<td align="center">
Roof Replacement
</td>

<td align="center">
Sold/Complete
</td>



</tr>
<tr>
<td align="center" id="rm_total"></td>
<td align="center" id="rr_total"></td>
<td align="center" id="sold_total"></td>
</tr>
</table>
</td>

<td valign="middle">
Search:<br>
<input type="text" name="filter_address" size="40" value="<?=$filter_address?>">
</td>
<td valign="middle">
<input type="radio" name="filter_address_radio" value="M"<?php if($filter_address_radio=="M") echo " checked";?>>Map<br>
<input type="radio" name="filter_address_radio" value="P"<?php if($filter_address_radio=="P") echo " checked";?>>Properties
</td>

<td valign="middle">
Property Type<br>
<select name="filter_property_type">
<option value="">All</option>
<option value="Beazer"<?php if($filter_property_type=="Beazer") echo " selected";?>>Beazer</option>
<option value="Manville"<?php if($filter_property_type=="Manville") echo " selected";?>>Manville</option>
<option value="Non-PFRI"<?php if($filter_property_type=="Non-PFRI") echo " selected";?>>Non-PFRI</option>
</select>
</td>
<?php /*
<td valign="middle">
Region<br>
<select name="filter_region">
<option value="">All</option>
<?php
$sql_region = "SELECT region from properties where display=1 and corporate=0 and region != '' group by region order by region";
$result_region = executequery($sql_region);
while($record_region = go_fetch_array($result_region)){
  ?>
  <option value="<?=$record_region['region']?>"<?php if($record_region['region']==$filter_region) echo " selected";?>><?=$record_region['region']?></option>
  <?php
}
?>
</select>

</td>
*/?>
<td valign="middle">
Region<br> <?php // actually territory ?>
<select name="filter_territory">
<option value="">All</option>
<?php
$sql = "SELECT territory from properties where territory != '' group by territory order by territory";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['territory']?>"<?php if($filter_territory==$record['territory']) echo " selected";?>><?=$record['territory']?></option>
  <?php
}
?>
</select>
</td>

<td valign="middle">
<input type="submit" name="submit1" value="Filter">
</td>


<td valign="bottom">
Will Not Map<br>
<span id="willnotmap_total"></span>
</td>
</tr>
</table>
</form>
<div id="map_canvas" style="width:100%; height:80%"></div>
</div>
</div>

<script type='text/javascript' language='JavaScript'>
initialize();  // Call function to setup map

</script>

<?php
// Iterate through the property rows
$rr_total = 0;
$rm_total = 0;
$qb_total = 0;
$complete_total = 0;
$sold_total = 0;
$willnotmap = 0;
for($x=0;$x<sizeof($row);$x++){
  $site_name = $row[$x]['site_name'];
  $address = $row[$x]['address'];
  $city = $row[$x]['city'];
  $state = $row[$x]['state'];
  $zip = $row[$x]['zip'];
  $roof_size = $row[$x]['roof_size'];
  $url = $row[$x]['url'];
  $fulladdress = $row[$x]['fulladdress'];
  $latitude = $row[$x]['latitude'];
  $longitude = $row[$x]['longitude'];
  $LatLng = $latitude . "," . $longitude;
  $classification = $row[$x]['classification'];
  $crew = $row[$x]['crew'];
  $project_manager = $row[$x]['project_manager'];
  $prodstat_name = $row[$x]['prodstat_name'];
  $product = $row[$x]['product'];
  $status = $row[$x]['status'];
  $stage_name = $row[$x]['stage_name'];
  $group = $row[$x]['group'];
  $group_sub = $row[$x]['group_sub'];
  $territory = $row[$x]['territory'];
  
  $html = "<b>$site_name</b><br />";
  $html .= "$fulladdress<br /><br />";
  $html .= "Squares = $roof_size<br />";
  $html .= "Status = $status<br />";
  $html .= "Product = $product<br />";
  $html .= "Stage = $stage_name<br />";
  $html .= "Project Manager = $project_manager<br />";
  $html .= "Territory = $territory<br />";
  $html = go_reg_replace("\'", "", $html);
  
  
  echo "<script type='text/javascript' language='JavaScript'>";
  echo "var point = new GLatLng(". $LatLng .");";
    // choose which icon to display
  
  switch($group){
    case "Roof Management":{
      echo "showAddress(point,'$html',rm_" . $group_sub . ");";
	  $rm_total++;
	  break;
	}
	case "Roof Replacement":{
      echo "showAddress(point,'$html',rr_" . $group_sub . ");";
	  $rr_total++;
	  break;
	}

	case "Sold":{
      echo "showAddress(point,'$html',sold);";
	  $sold_total++;
	  break;
	}

	
    default:{
	  $willnotmap++;
	  break;
	}
  }
  
  
  
  echo "\n";
  echo "</script>";
}

$_SESSION['ro_notmapids'] = $notmapids;
$_SESSION['ro_notmapids_reason'] = $notmapids_reason;
if($lat != "" && $filter_address_radio == "M"){
    $html = "$filter_address<br /><br />";

  $LatLng = $lat . "," . $lng;
  
  echo "<script type='text/javascript' language='JavaScript'>";
  echo "var point = new GLatLng(". $LatLng .");";
  echo "showAddressZoom(point,'$html',googlemap);";
  echo "</script>\n";
}

include "map_markerfcs.php";
?>

<script>
document.getElementById('rr_total').innerHTML = '<?=$rr_total?>';
document.getElementById('rm_total').innerHTML = '<?=$rm_total?>';
document.getElementById('sold_total').innerHTML = '<?=$sold_total?>';
document.getElementById('willnotmap_total').innerHTML = '<?=$willnotmap?>';
</script>
<?php include "includes/footer.php"; ?>
