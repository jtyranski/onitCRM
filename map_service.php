<?php
ob_start("ob_gzhandler");
session_start();
include "includes/functions.php";

function compare($x, $y)
{
$order_by = "sort";
if ( strtoupper($x[$order_by]) == strtoupper($y[$order_by]) )
 return 0;
else if ( strtoupper($x[$order_by]) < strtoupper($y[$order_by]) )
 return -1;
else
 return 1;
}

function rcompare($x, $y)
{
$order_by = "sort";
if ( strtoupper($x[$order_by]) == strtoupper($y[$order_by]) )
 return 0;
else if ( strtoupper($x[$order_by]) < strtoupper($y[$order_by]) )
 return 1;
else
 return -1;
}





$check_dis = $_GET['check_dis'];
$check_inprogress = $_GET['check_inprogress'];
$check_eta = $_GET['check_eta'];
$check_res = $_GET['check_res'];
$check_conf = $_GET['check_conf'];
$filter_resource = $_GET['filter_resource'];
if($filter_resource=="") $filter_resource = "All";
$submit1 = $_GET['submit1'];
/*
if($submit1==""){
  $check_dis = 1;
  $check_inprogress = 1;
  $check_eta = 1;
  $check_res = 1;
  $check_conf = 1;
  $check_contractor = 1;
}
*/

if($check_dis =="") $check_dis = $_SESSION['map_service_check_dis'];
if($check_inprogress =="") $check_inprogress = $_SESSION['map_service_check_inprogress'];
if($check_eta =="") $check_eta = $_SESSION['map_service_check_eta'];
if($check_res =="") $check_res = $_SESSION['map_service_check_res'];
if($check_conf =="") $check_conf = $_SESSION['map_service_check_conf'];

if($check_dis =="") $check_dis = 1;
if($check_inprogress =="") $check_inprogress = 1;
if($check_eta =="") $check_eta = 1;
if($check_res =="") $check_res = 1;
if($check_conf =="") $check_conf = 1;
//echo "<!-- conf = $check_conf -->\n";
$filter_territory = $_GET['filter_territory'];


$lat = $_GET['lat'];
$lng = $_GET['lng'];
$zoom = $_GET['zoom'];
$filter_address = $_GET['filter_address'];
$filter_address_radio = $_GET['filter_address_radio'];
if($filter_address_radio == "") $filter_address_radio = "M";

$filter_user_id = $_GET['filter_user_id'];
$filter_radius = $_GET['filter_radius'];
if($filter_radius == "") $filter_radius=50;


if($lat != "" && $filter_address_radio == "M"){
  $latitude = $lat;
  $longitude = $lng;
  $zoomlevel = 10;
}
else {
  $latitude = $_SESSION['map_lat'];
  $longitude = $_SESSION['map_lng'];
  $zoomlevel = $_SESSION['map_zoom'];
  if($latitude == ""){
    $latitude = 37.25;
    $longitude = -96.857788;
    $zoomlevel = 5;
  }
}
if($zoom != "") $zoomlevel = $zoom;



if($filter_territory != ""){
  $territory_clause = " a.territory = '$filter_territory' ";
}
else {
  $territory_clause = " 1=1 ";
}

if($filter_address_radio == "P"){
  $site_name_clause = " a.site_name like \"%$filter_address%\" ";
  $company_name_clause = " a.company_name like \"%$filter_address%\" ";
}
else {
  $site_name_clause = " 1=1 ";
  $company_name_clause = " 1=1 ";
}

$counter=0;


$sql = "SELECT a.property_id, a.site_name, a.address, a.city, a.state, a.zip, 
concat('" . $SITE_URL . "view_property.php?property_id=', a.property_id) as URL, 
b.latitude, b.longitude, c.status, c.leak_id, c.servicemen_id
FROM properties a, geocode b, am_leakcheck c, prospects d
WHERE a.property_id=b.property_id and b.latitude != 0 AND a.display='1' and a.property_id=c.property_id 
and c.status != 'Invoiced' 
and c.status != 'Acknowledged' 
and c.status != 'Closed Out'
and c.display=1 
and c.demo = 0
and c.archive=0
and c.prospect_id = d.prospect_id 
and d.master_id='" . $SESSION_MASTER_ID . "'
and $site_name_clause

order by c.leak_id asc
";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  if($SESSION_IREP==1){
	    $sql = "SELECT count(*) from properties where property_id='" . $record['property_id'] . "' and display=1 and irep='" . $SESSION_USER_ID . "'";
        $test_irep = getsingleresult($sql);
		if($test_irep==0) continue;
  }
  $site_name = go_reg_replace("\'", "", stripslashes($record['site_name']));
  $address = go_reg_replace("\'", "", stripslashes($record['address']));
  $city = go_reg_replace("\'", "", stripslashes($record['city']));
  $state = $record['state'];
  $url = $record['URL'];
  $site_name = "<a href=$url target=_blank>$site_name</a>";
  $fulladdress = $address ."<br />". $city .", ". $state ." ". $zip;
  $classification = "SD";
  $x_latitude = $record['latitude'];
  $x_longitude = $record['longitude'];
  $status = $record['status'];
  $leak_id = $record['leak_id'];
  $servicemen_id = $record['servicemen_id'];
  $company_name = "";
  if($servicemen_id != 0){
    $sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='$servicemen_id'";
	$company_name = stripslashes(getsingleresult($sql));
  }
  
  if($status=="Dispatched" && $check_dis==0) continue;
  if($status=="In Progress" && $check_inprogress==0) continue;
  if($status=="Arrival ETA" && $check_eta==0) continue;
  if($status=="Resolved" && $check_res==0) continue;
  if($status=="Confirmed" && $check_conf==0) continue;
  
  if($status=="Dispatched") $sort = 6;
  if($status=="In Progress") $sort = 4;
  if($status=="Arrival ETA") $sort = 5;
  if($status=="Resolved") $sort = 3;
  if($status=="Confirmed") $sort = 2;
  
  if($status=="Arrival ETA"){
    $sql = "SELECT date_format(eta_date, \"%m\") as month, date_format(eta_date, \"%k\") as hour, 
    date_format(eta_date, \"%i\") as minute, date_format(eta_date, \"%d\") as day, 
	date_format(eta_date, \"%Y\") as year from am_leakcheck where leak_id='$leak_id'";
    $result2 = executequery($sql);
    $record2 = go_fetch_array($result2);
    $month = $record2['month'];
	$day = $record2['day'];
	$year = $record2['year'];
    $hour = $record2['hour'];
    $minute = $record2['minute'];
	$x_date = mktime($hour, $minute, 0, $month, $day, $year);
	if(time() >= $x_date){
	  $status = "Arrival ETA Late";
	}
  }
  // not doing timezone really, anymore
  $timezone = 0;
  
  switch($timezone){
    case 1:{
	  $tz_display = "EST";
	  break;
	}
	case 0:{
	  $tz_display = "CST";
	  break;
	}
	case -1:{
	  $tz_display = "MST";
	  break;
	}
	case -2:{
	  $tz_display = "PST";
	  break;
	}
	default:{
	  $tz_display = "CST";
	  break;
	}
  }
  
  $sql = "SELECT 
  date_format(date_add(a.dispatch_date, interval $timezone hour), \"%m/%d/%Y %r\") as dispatch, 
  date_format(date_add(a.fix_date, interval $timezone hour), \"%m/%d/%Y %r\") as resolved, 
  date_format(date_add(a.acknowledge_date, interval $timezone hour), \"%m/%d/%Y %r\") as acknowledge, 
  date_format(date_add(a.eta_date, interval $timezone hour), \"%m/%d/%Y %r\") as eta, 
  date_format(date_add(a.confirm_date, interval $timezone hour), \"%m/%d/%Y %r\") as confirm, 
  date_format(date_add(a.invoice_date, interval $timezone hour), \"%m/%d/%Y %r\") as invoice, 
  date_format(date_add(a.inprogress_date, interval $timezone hour), \"%m/%d/%Y %r\") as inprogress, 
  date_format(date_add(a.closed_date, interval $timezone hour), \"%m/%d/%Y %r\") as closed
  from am_leakcheck a where leak_id='$leak_id'";
  $result_times = executequery($sql);
  $record_times = go_fetch_array($result_times);
  $dispatch = stripslashes($record_times['dispatch']) . " " . $tz_display;
  $acknowledge = stripslashes($record_times['acknowledge']) . " " . $tz_display;
  $eta = stripslashes($record_times['eta']) . " " . $tz_display;
  $resolved = stripslashes($record_times['resolved']) . " " . $tz_display;
  $confirm = stripslashes($record_times['confirm']) . " " . $tz_display;
  $invoice = stripslashes($record_times['invoice']) . " " . $tz_display;
  $inprogress = stripslashes($record_times['inprogress']) . " " . $tz_display;
  $closed = stripslashes($record_times['closed']) . " " . $tz_display;
  
  if($status == "Dispatched" || $status=="New Project") $lastupdate = $dispatch;
  if($status == "Acknowledged") $lastupdate = $acknowledge;
  if($status == "Arrival ETA") $lastupdate = $eta;
  if($status == "Resolved") $lastupdate = $resolved;
  if($status == "Confirmed") $lastupdate = $confirm;
  if($status == "Invoiced") $lastupdate = $invoice;
  if($status == "In Progress") $lastupdate = $inprogress;
  if($status == "Closed Out") $lastupdate = $closed;
  
  
  $row[$counter]['site_name'] = $site_name;
  $row[$counter]['address'] = $address;
  $row[$counter]['city'] = $city;
  $row[$counter]['state'] = $state;
  $row[$counter]['zip'] = $zip;
  $row[$counter]['url'] = $url;
  $row[$counter]['fulladdress'] = $fulladdress;
  $row[$counter]['latitude'] = $x_latitude;
  $row[$counter]['longitude'] = $x_longitude;
  
  $row[$counter]['status'] = $status;
  $row[$counter]['leak_id'] = $leak_id;
  $row[$counter]['company_name'] = $company_name;
  $row[$counter]['rating'] = "";
  $row[$counter]['sort'] = $sort;
  $row[$counter]['lastupdate'] = $lastupdate;
  
  $counter++;
}

usort($row, "compare");

?>
<!DOCTYPE html "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	
    <title>Service Dispatch Map</title>
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
<script>


function refreshpage(url){
  setTimeout("document.location.href='" + url + "'",600000);
}
</script>
<noscript>
<meta http-equiv="refresh" content="600;url=<?=$_SERVER['SCRIPT_NAME']?>?<?=$_SERVER['QUERY_STRING']?>">
</noscript>
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
	  
	  
	  google.maps.event.addListener(map, 'zoom_changed', function() {
        setTimeout(newspot, 3000);
      });
	  google.maps.event.addListener(map, 'center_changed', function() {
        setTimeout(newspot, 3000);
      });
	  google.maps.event.addListener(map, 'bounds_changed', function() {
        setTimeout(newspot, 3000);
      });

	  
	  
    }
	
	function newspot(){
	  var z = map.getZoom();
	  var lat = map.getCenter().lat(); 
      var lng = map.getCenter().lng();
	  url = "map_service_savespot.php?zoom=" + z + "&lat=" + lat + "&lng=" + lng;
	  // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
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
	    //image = "<?=$SITE_URL?>googlemap/service_dis_star_blink.gif"; //blink no longer works on new google map
		image = "<?=$SITE_URL?>googlemap/service_dis_star.png";
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
	    //image = "<?=$SITE_URL?>googlemap/service_eta_blink.gif";
		image = "<?=$SITE_URL?>googlemap/service_eta_new.png";
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
	  
	  case "van":{
	    image = "<?=$SITE_URL?>googlemap/van.gif";
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
	<script>
	function closemenu(){
	  parent.top.document.getElementById("HEADER_DIV").style.display='none';
	  parent.top.document.getElementById("NAVIGATION_DIV").style.display='none';
	  parent.top.document.getElementById("toolbox_goback").style.display='none';
	  document.getElementById("MAP_MENU_DIV").style.display='none';
	  document.getElementById("expand_div").style.display='';
	  var url = "<?=$_SERVER['SCRIPT_NAME']?>?<?=$_SERVER['QUERY_STRING']?>";
	  url = url + "&collapse=1";
	  refreshpage(url);
	}
	function openmenu(){
	  parent.top.document.getElementById("HEADER_DIV").style.display='';
	  parent.top.document.getElementById("NAVIGATION_DIV").style.display='';
	  parent.top.document.getElementById("toolbox_goback").style.display='';
	  document.getElementById("MAP_MENU_DIV").style.display='';
	  document.getElementById("expand_div").style.display='none';
	  <?php
	  $QS = $_SERVER['QUERY_STRING'];
	  $QS = str_replace("collapse=1", "collapse=0", $QS);
	  ?>
	  var url = "<?=$_SERVER['SCRIPT_NAME']?>?<?=$QS?>";
	  refreshpage(url);
	}
	</script>
  </head>
  <body>  <!-- ==== unload API when finished ==== -->
<div id="MAP_MENU_DIV">
<div class="title">Service Dispatch Map</div>
<form action="map_service_action.php" method="get">
<input type="hidden" name="zoom" value="<?=$zoom?>">
<input type="hidden" name="lat" value="<?=$lat?>">
<input type="hidden" name="lng" value="<?=$lng?>">

<table class="main" width="100%">
<tr>
<td valign="top">


<table cellpadding="3" class="main">
<tr>
<td align="center">
<input type="checkbox" name="check_dis" value="1"<?php if($check_dis) echo " checked";?>>
</td>
<td align="center">
<input type="checkbox" name="check_eta" value="1"<?php if($check_eta) echo " checked";?>>
</td>
<td align="center">
<input type="checkbox" name="check_inprogress" value="1"<?php if($check_inprogress) echo " checked";?>>
</td>
<td align="center">
<input type="checkbox" name="check_res" value="1"<?php if($check_res) echo " checked";?>>
</td>
<td align="center">
<input type="checkbox" name="check_conf" value="1"<?php if($check_conf) echo " checked";?>>
</td>
</tr>
<tr>
<td align="center" valign="top">
<img src="googlemap/service_dis_star.png">
</td>
<td align="center" valign="top">
<img src="googlemap/service_eta_new.png">
</td>
<td align="center" valign="top">
<img src="googlemap/service_inprogress.png">
</td>
<td align="center" valign="top">
<img src="googlemap/service_res.png">
</td>
<td align="center" valign="top">
<img src="googlemap/service_conf.png">
</td>

</tr>
<tr>
<td align="center">
Dispatched
</td>
<td align="center">
Arrival ETA
</td>
<td align="center">
In Progress
</td>
<td align="center">
Resolved
</td>
<td align="center">
Confirmed
</td>


</tr>
<tr>
<td align="center" id="dis_total"></td>
<td align="center" id="eta_total"></td>
<td align="center" id="inprogress_total"></td>
<td align="center" id="res_total"></td>
<td align="center" id="conf_total"></td>
</tr>
</table>
</td>

<td valign="middle">
Search:<br>
<input type="text" name="filter_address" size="40" value="<?=$filter_address?>">
<br>
Serviceman
<select name="filter_user_id">
<option value="0"></option>
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname, googlemap_zipcode from users where (servicemen=1 or resource=1) and enabled=1 and master_id='" . $SESSION_MASTER_ID . "' order by resource desc, lastname, firstname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$filter_user_id) echo " selected";?><?php if($record['googlemap_zipcode']=="") echo " disabled";?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
<input type="text" name="filter_radius" value="<?=$filter_radius?>" size="2">miles<br>
<?php 
/*
if($SESSION_USE_RESOURCES==1){ ?>
Resources:
<select name="filter_resource">
<option value="None"<?php if($filter_resource=="None") echo " selected";?>>None</option>
<option value="All"<?php if($filter_resource=="All") echo " selected";?>>All</option>
<option value="Member"<?php if($filter_resource=="Member") echo " selected";?>>Member</option>
<option value="Associate"<?php if($filter_resource=="Associate") echo " selected";?>>Associate</option>
<option value="Affiliate"<?php if($filter_resource=="Affiliate") echo " selected";?>>Affiliate</option>
<option value="Strategic Partner"<?php if($filter_resource=="Strategic Partner") echo " selected";?>>Strategic Partner</option>
<option value="Resource"<?php if($filter_resource=="Resource") echo " selected";?>>Resource</option>
</select>
<?php } 
*/
?>
</td>
<td valign="middle">
<input type="radio" name="filter_address_radio" value="M"<?php if($filter_address_radio=="M") echo " checked";?>>Map<br>
<input type="radio" name="filter_address_radio" value="P"<?php if($filter_address_radio=="P") echo " checked";?>>Properties
</td>


<td valign="middle">
<input type="submit" name="submit1" value="Filter">
</td>

<td valign="bottom">
Will Not Map<br>
<span id="willnotmap_total"></span>
</td>
<td valign="bottom">
<a href="javascript:closemenu()"><img src="images/redarrow_up.png" border="0"></a>
</td>
</tr>
</table>
</form>
</div>
<div id="expand_div" style="display:none;" align="right">
<a href="javascript:openmenu()"><img src="images/redarrow_down.png" border="0"></a>
</div>
<div id="map_canvas" style="width:100%; height:800px;"></div>
  </body>
</html>
<script type='text/javascript' language='JavaScript'>
initialize();  // Call function to setup map

</script>

<?php
// Iterate through the property rows
$dis_total = 0;
$inprogress_total = 0;
$eta_total = 0;
$res_total = 0;
$contractor_total = 0;
$conf_total = 0;
$willnotmap = 0;
$counter = 0;
$already_lat = array();
$double_lat = 0;
echo "<script type='text/javascript' language='JavaScript'>";
for($x=0;$x<sizeof($row);$x++){
  $site_name = $row[$x]['site_name'];
  $address = $row[$x]['address'];
  $city = $row[$x]['city'];
  $state = $row[$x]['state'];
  $zip = $row[$x]['zip'];
  $url = $row[$x]['url'];
  $fulladdress = $row[$x]['fulladdress'];
  $latitude = $row[$x]['latitude'];
  $longitude = $row[$x]['longitude'];
  $LatLng = $latitude . "," . $longitude;
  $status = $row[$x]['status'];
  $leak_id = $row[$x]['leak_id'];
  $company_name = $row[$x]['company_name'];
  $rating = $row[$x]['rating'];
  $lastupdate = $row[$x]['lastupdate'];
  $sort = $row[$x]['sort'];
  
  $xlat = $row[$x]['latitude'];
  $xlng = $row[$x]['longitude'];
  
  /*
  if(!(in_array($latitude, $already_lat))){
    $already_lat[] = $latitude;
  }
  else {
    $double_lat++;
  }
  */
  
  $sd_html = "<b>$site_name</b><br />";
  $sd_html .= "$status $lastupdate<br>";
  $sd_html .= "$fulladdress<br /><br />";
  $sd_html .= "Contractor: $company_name<br />";
  $sd_html .= "Most Recent Dispatch: <a href=fcs_sd_report_view.php?leak_id='" . $leak_id . ">$leak_id</a><br />";
  $sd_html = go_reg_replace("\'", "", $sd_html);
  
  $cont_html = "<b>$site_name</b><br />";
  $cont_html .= "$fulladdress<br /><br />";
  $cont_html = go_reg_replace("\'", "", $cont_html);
  
  
    // choose which icon to display
  
  switch($status){
    case "New Project":
    case "Dispatched":{
	  echo "placeMarker(" . $xlat . ", " . $xlng . ",'$sd_html', 'dis');\n";
	  $dis_total++;
	  break;
	}
	case "In Progress":{
	  echo "placeMarker(" . $xlat . ", " . $xlng . ",'$sd_html', 'inprogress');\n";
	  $inprogress_total++;
	  break;
	}
	case "Arrival ETA":{
	  echo "placeMarker(" . $xlat . ", " . $xlng . ",'$sd_html', 'eta');\n";
	  $eta_total++;
	  break;
	}
	case "Arrival ETA Late":{
	  echo "placeMarker(" . $xlat . ", " . $xlng . ",'$sd_html', 'eta_blink');\n";
	  $eta_total++;
	  break;
	}
	case "Resolved":{
	  echo "placeMarker(" . $xlat . ", " . $xlng . ",'$sd_html', 'res');\n";
	  $res_total++;
	  break;
	}
	case "Confirmed":{
	  echo "placeMarker(" . $xlat . ", " . $xlng . ",'$sd_html', 'conf');\n";
	  $conf_total++;
	  break;
	}
	case "Contractor":{
	  echo "placeMarker(" . $xlat . ", " . $xlng . ",'$cont_html', 'con" . $rating . "');\n";
	  $contractor_total++;
	  break;
	}
	
    default:{
	  $willnotmap++;
	  break;
	}
  }
  
  
  
  echo "\n";
  
}
echo "</script>";
//$_SESSION['ro_notmapids'] = $notmapids;
//$_SESSION['ro_notmapids_reason'] = $notmapids_reason;
  include "map_markerservicemap.php";
  include "map_markervan.php";
if($lat != "" && $filter_address_radio == "M"){
    $html = "$filter_address";

  $LatLng = $lat . "," . $lng;
 
  echo "<script type='text/javascript' language='JavaScript'>\n";
  echo "placeMarker(" . $lat . ", " . $lng . ",'$html', 'googlemap');\n";
  echo "</script>\n";
  
}
  
?>
<script>

document.getElementById('dis_total').innerHTML = '<?=$dis_total?>';
document.getElementById('inprogress_total').innerHTML = '<?=$inprogress_total?>';
document.getElementById('eta_total').innerHTML = '<?=$eta_total?>';
document.getElementById('res_total').innerHTML = '<?=$res_total?>';
document.getElementById('conf_total').innerHTML = '<?=$conf_total?>';
document.getElementById('willnotmap_total').innerHTML = '<?=$willnotmap?>';

<?php
$collapse = $_GET['collapse'];
if($collapse==1){?>
closemenu();
<?php } ?>

var url="<?=$_SERVER['SCRIPT_NAME']?>?<?=$_SERVER['QUERY_STRING']?>";
refreshpage(url);

</script>


