<?php
ob_start("ob_gzhandler");
session_start();
include "includes/header.php";
/*
$sql = "SELECT googlemap_id from users where googlemap_id != '' and googlemap_zipcode = ''";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $googlemap_ids .= stripslashes($record['googlemap_id']) . ",";
}
$googlemap_ids = go_reg_replace(",$", "", $googlemap_ids);
*/

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
  if($_SESSION['list_boxtype']=="property"){
    $site_name_clause = " a.site_name like \"%$filter_address%\" ";
  }
  else {
    $site_name_clause = " a.company_name like \"%$filter_address%\" ";
  }
}
else {
  $site_name_clause = " 1=1 ";
}



/*
$PM[0] = "None";
$sql = "SELECT user_id, concat(firstname, ' ' , lastname) as fullname from users where enabled=1 and project_manager=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $user_id = $record['user_id'];
  $PM[$user_id] = stripslashes($record['fullname']);
}
*/
$counter = 0;
if($_SESSION['list_boxtype']=="property"){
for($x=0;$x<sizeof($_SESSION['list_property_id']);$x++){
  $x_property_id = $_SESSION['list_property_id'][$x];
  
$sql = "SELECT a.property_id, site_name, address, city, state, zip, roof_size,  
concat('view_property.php?property_id=', a.property_id) as URL, 
b.latitude, b.longitude
FROM properties a, geocode b
WHERE a.property_id=b.property_id and b.latitude != 0  
and $site_name_clause and $territory_clause and $property_type_clause
and a.property_id='$x_property_id' limit 1
";
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
  $x_latitude = $record['latitude'];
  $x_longitude = $record['longitude'];
  
  $html = "<b>$site_name</b><br />";
  $html .= "$fulladdress<br /><br />";
  $html = go_reg_replace("\'", "", $html);
  
  
  
  $row[$counter]['site_name'] = $site_name;
  $row[$counter]['address'] = $address;
  $row[$counter]['city'] = $city;
  $row[$counter]['state'] = $state;
  $row[$counter]['zip'] = $zip;
  $row[$counter]['roof_size'] = $roof_size;
  $row[$counter]['url'] = $url;
  $row[$counter]['fulladdress'] = $fulladdress;
  $row[$counter]['latitude'] = $x_latitude;
  $row[$counter]['longitude'] = $x_longitude;
  $row[$counter]['territory'] = $territory;
  $row[$counter]['html'] = $html;
  
  $counter++;
}
} // end for loop
} // end if type property



if($_SESSION['list_boxtype']=="prospect"){
for($x=0;$x<sizeof($_SESSION['list_prospect_id']);$x++){
  $x_prospect_id = $_SESSION['list_prospect_id'][$x];
  
$sql = "SELECT company_name as site_name, address, city, state, zip,  
concat('view_company.php?prospect_id=', a.prospect_id) as URL, 
b.latitude, b.longitude
FROM prospects a, geocode b
WHERE a.prospect_id=b.prospect_id and b.latitude != 0  
and $site_name_clause
and a.prospect_id='$x_prospect_id' limit 1
";
echo "<!-- $sql -->\n";
$result = executequery($sql);

while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  $site_name = go_reg_replace("\'", "", $record['site_name']);
  $address = $record['address'];
  $city = $record['city'];
  $state = $record['state'];
  $zip = $record['zip'];
  $url = $record['URL'];
  //$url = "<a href=\'$url\' target=\'_blank\'>$url</a>";
  $site_name = "<a href=\"$url\" target=\'_blank\'>$site_name</a>";
  $fulladdress = $address ."<br />". $city .", ". $state ." ". $zip;
  $x_latitude = $record['latitude'];
  $x_longitude = $record['longitude'];
  
  $html = "<b>$site_name</b><br />";
  $html .= "$fulladdress<br /><br />";
  $html = go_reg_replace("\'", "", $html);
  
  
  
  $row[$counter]['site_name'] = $site_name;
  $row[$counter]['address'] = $address;
  $row[$counter]['city'] = $city;
  $row[$counter]['state'] = $state;
  $row[$counter]['zip'] = $zip;
  $row[$counter]['roof_size'] = $roof_size;
  $row[$counter]['url'] = $url;
  $row[$counter]['fulladdress'] = $fulladdress;
  $row[$counter]['latitude'] = $x_latitude;
  $row[$counter]['longitude'] = $x_longitude;
  $row[$counter]['territory'] = $territory;
  $row[$counter]['html'] = $html;
  
  $counter++;
}
} // end for loop
} // end if type prospect



if($_SESSION['list_boxtype']=="contact"){
for($x=0;$x<sizeof($_SESSION['list_contact_id']);$x++){
  $x_contact_id = $_SESSION['list_contact_id'][$x];
  
  $sql = "SELECT prospect_id, property_id from contacts where id='$x_contact_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $x_property_id = $record['property_id'];
  $x_prospect_id = $record['prospect_id'];
  
  $use = "prospect";
  if($x_prospect_id==0) $use = "property";
  
  if($use == "prospect"){
$sql = "SELECT company_name as site_name, address, city, state, zip,  
concat('view_company.php?prospect_id=', a.prospect_id) as URL, 
b.latitude, b.longitude
FROM prospects a, geocode b
WHERE a.prospect_id=b.prospect_id and b.latitude != 0  
and $site_name_clause
and a.prospect_id='$x_prospect_id' limit 1
";
}
if($use == "property"){
$sql = "SELECT  site_name, address, city, state, zip,  
concat('view_property.php?property_id=', a.property_id) as URL, 
b.latitude, b.longitude
FROM properties a, geocode b
WHERE a.property_id=b.property_id and b.latitude != 0  
and $site_name_clause
and a.property_id='$x_property_id' limit 1
";
}

$result = executequery($sql);

while($record = go_fetch_array($result)){
  $site_name = go_reg_replace("\'", "", $record['site_name']);
  $address = $record['address'];
  $city = $record['city'];
  $state = $record['state'];
  $zip = $record['zip'];
  $url = $record['URL'];
  //$url = "<a href=\'$url\' target=\'_blank\'>$url</a>";
  $site_name = "<a href=\"$url\" target=\'_blank\'>$site_name</a>";
  $fulladdress = $address ."<br />". $city .", ". $state ." ". $zip;
  $x_latitude = $record['latitude'];
  $x_longitude = $record['longitude'];
  
  $html = "<b>$site_name</b><br />";
  $html .= "$fulladdress<br /><br />";
  $html = go_reg_replace("\'", "", $html);
  
  
  
  $row[$counter]['url'] = $url;
  $row[$counter]['fulladdress'] = $fulladdress;
  $row[$counter]['latitude'] = $x_latitude;
  $row[$counter]['longitude'] = $x_longitude;
  $row[$counter]['territory'] = $territory;
  $row[$counter]['html'] = $html;
  
  $counter++;
}
} // end for loop
} // end if type contact

echo "<!-- " . sizeof($row) . "-->\n";
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
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script type="text/javascript">
    //<![CDATA[



// ======== New map variable ========
    var citymap = {};
  <?php
  if($filter_user_id != 0 && $filter_user_id != ""){
    $sql = "SELECT googlemap_zipcode from users where user_id='$filter_user_id'";
	$googlemap_zipcode = getsingleresult($sql);
	$sql = "SELECT concat(latitude, ', ', longitude) from zipcodes where zipcode='$googlemap_zipcode'";
	$office_latlng = getsingleresult($sql);
	$radius = 1609.344 * $filter_radius;
	?>
	citymap['city'] = {
      center: new google.maps.LatLng(<?=$office_latlng?>),
      radius: <?=$radius?>
    };
	<?php
  }
  ?>
  /*
  citymap['burleson'] = {
    center: new google.maps.LatLng(32.548, -97.320),
    radius: 142125
  };
  citymap['newyork'] = {
    center: new google.maps.LatLng(40.714352, -74.005973),
    radius: 407159
  };
  citymap['losangeles'] = {
    center: new google.maps.LatLng(34.052234, -118.243684),
    radius: 192241
  }
  */
  var cityCircle;
  var map;

// =========== Initialize our map, set center, zoom level, hybrid view, UI controls, and allow info window =============
    function initialize() {

      // defines zoom, center, map type, etc
      var latlng = new google.maps.LatLng(<?=$latitude?>, <?=$longitude?>);
      var mapOptions = {
        zoom: <?=$zoomlevel?>,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };
	  
	  
      map = new google.maps.Map(document.getElementById("map_canvas"),
	mapOptions);

      for (var city in citymap) {
        // Construct the circle for each value in citymap. We scale population by 20.
        var radiusOptions = {
          strokeColor: "#FF0000",
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: "#000000",
          fillOpacity: 0.0,
          map: map,
          center: citymap[city].center,
          radius: citymap[city].radius
        };
        cityCircle = new google.maps.Circle(radiusOptions);
      }
      
	  /*
      var georssLayer = new google.maps.KmlLayer("http://www.google.com/latitude/apps/badge/api?user=<?=$googlemap_ids?>&type=kml");
      georssLayer.setMap(map);
	  georssLayer.set('preserveViewport', true);
	  */
	  
	  
    }
	
	

// ============ display a new marker ================
    function placeMarker(lat, lng, html, icon) {
	  
	  //alert(foo);
	  switch(icon){
	  case "ack":{
	    image = "<?=$SITE_URL?>googlemap/service_ack.png";
		imagesize1 = 10;
	    imagesize2 = 10;
		break;
	  }
	  case "dis":{
	    image = "<?=$SITE_URL?>googlemap/service_dis_star_blink.gif";
		imagesize1 = 10;
	    imagesize2 = 10;
		break;
	  }
	  case "eta":{
	    image = "<?=$SITE_URL?>googlemap/service_eta_new.png";
		imagesize1 = 10;
	    imagesize2 = 10;
		break;
	  }
	  case "eta_blink":{
	    image = "<?=$SITE_URL?>googlemap/service_eta_blink.gif";
		imagesize1 = 10;
	    imagesize2 = 10;
		break;
	  }
	  case "inprogress":{
	    image = "<?=$SITE_URL?>googlemap/service_inprogress.png";
		imagesize1 = 10;
	    imagesize2 = 10;
		break;
	  }
	  case "res":{
	    image = "<?=$SITE_URL?>googlemap/service_res.png";
		imagesize1 = 10;
	    imagesize2 = 10;
		break;
	  }
	  case "conf":{
	    image = "<?=$SITE_URL?>googlemap/service_conf.png";
		imagesize1 = 10;
	    imagesize2 = 10;
		break;
	  }
	  
	  case "con1":{
	    image = "<?=$SITE_URL?>googlemap/service_con1.png";
		imagesize1 = 13;
	    imagesize2 = 13;
		break;
	  }
	  case "con2":{
	    image = "<?=$SITE_URL?>googlemap/service_con2.png";
		imagesize1 = 13;
	    imagesize2 = 13;
		break;
	  }
	  case "con3":{
	    image = "<?=$SITE_URL?>googlemap/service_con3.png";
		imagesize1 = 13;
	    imagesize2 = 13;
		break;
	  }
	  case "con4":{
	    image = "<?=$SITE_URL?>googlemap/service_con4.png";
		imagesize1 = 13;
	    imagesize2 = 13;
		break;
	  }
	  case "con5":{
	    image = "<?=$SITE_URL?>googlemap/service_con5.png";
		imagesize1 = 13;
	    imagesize2 = 13;
		break;
	  }

	  
	  case "googlemap":{
	    image = "<?=$SITE_URL?>googlemap/thumb_red.png";
		imagesize1 = 13;
	    imagesize2 = 13;
		break;
	  }
	  
	  case "orange":{
	    image = "<?=$SITE_URL?>googlemap/ro_public_orange.gif";
		imagesize1 = 13;
	    imagesize2 = 13;
		break;
	  }
	  
	  default:{
	    image = "<?=$SITE_URL?>googlemap/thumb_red.png";
		imagesize1 = 13;
	    imagesize2 = 13;
		break;
      }
	  }
     
     var iconimage = new google.maps.MarkerImage(image, null, null, null, new google.maps.Size(imagesize1, imagesize2));

	  var infowindow = new google.maps.InfoWindow({
        content: html
      });

	  var latlng = new google.maps.LatLng(lat, lng);
      var marker = new google.maps.Marker({
        position: latlng, 
        map: map,
		icon: iconimage
      });
	  
	  google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map,marker);
      });
	  
	  //map.setCenter(location);

    }
	
	function placeBadge(lat, lng, html, icon) {
      var iconimage2 = new google.maps.MarkerImage(icon, null, null, null, new google.maps.Size(24, 24));

	  var infowindow2 = new google.maps.InfoWindow({
        content: html
      });

	  var latlng2 = new google.maps.LatLng(lat, lng);
      var marker2 = new google.maps.Marker({
        position: latlng2, 
        map: map,
		icon: iconimage2
      });
	  
	  google.maps.event.addListener(marker2, 'click', function() {
        infowindow2.open(map,marker2);
      });
	  
	  //map.setCenter(location);

    }

    </script>
  <body>  <!-- ==== unload API when finished ==== -->
<div style="height:5px;"><img src="images/spacer.gif"></div>

<div align="center">
  <div class="whiteround" style="height:800px;" id="map">
<form action="map_generic_action.php" method="get">
<table class="main" width="100%">
<tr>
<td valign="top">


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
<input type="submit" name="submit1" value="Filter">
</td>


<td valign="bottom">
</td>
</tr>
</table>
</form>
<div id="map_canvas" style="width:100%; height:95%"></div>
</div>
</div>
<?php include "includes/footer.php"; ?>
<script type='text/javascript' language='JavaScript'>
initialize();  // Call function to setup map

</script>

<?php
echo "<script type='text/javascript' language='JavaScript'>";
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
  $xlat = $row[$x]['latitude'];
  $xlng = $row[$x]['longitude'];
  $LatLng = $latitude . "," . $longitude;
  $html = $row[$x]['html'];

  $territory = $row[$x]['territory'];
  
  
  
  
  
  
  echo "placeMarker(" . $xlat . ", " . $xlng . ",'$html', 'orange');\n";
  
  echo "\n";
  
}
echo "</script>";
if($lat != "" && $filter_address_radio == "M"){
    $html = "$filter_address";

  $LatLng = $lat . "," . $lng;
 
  echo "<script type='text/javascript' language='JavaScript'>\n";
  echo "placeMarker(" . $lat . ", " . $lng . ",'$html', 'googlemap');\n";
  echo "</script>\n";
  
}

//include "map_markerfcs.php";
?>


