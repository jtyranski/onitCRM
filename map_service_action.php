<?php
include "includes/functions.php";

$filter_address = $_GET['filter_address'];
$filter_address_radio = $_GET['filter_address_radio'];
//$filter_address = "1822 Powers Rd Woodstock, IL 60098";

$check_dis = $_GET['check_dis'];
$check_inprogress = $_GET['check_inprogress'];
$check_eta = $_GET['check_eta'];
$check_res = $_GET['check_res'];
$check_contractor = $_GET['check_contractor'];
$check_conf = $_GET['check_conf'];
$filter_resource = $_GET['filter_resource'];


if($check_dis != 1) $check_dis = 0;
if($check_inprogress != 1) $check_inprogress = 0;
if($check_eta != 1) $check_eta = 0;
if($check_res != 1) $check_res = 0;
if($check_contractor != 1) $check_contractor = 0;
if($check_conf != 1) $check_conf = 0;

$_SESSION['map_service_check_dis'] = $check_dis;
$_SESSION['map_service_check_inprogress'] = $check_inprogress;
$_SESSION['map_service_check_eta'] = $check_eta;
$_SESSION['map_service_check_res'] = $check_res;
$_SESSION['map_service_check_conf'] = $check_conf;

$lat = $_GET['lat'];
$lng = $_GET['lng'];
$zoom = $_GET['zoom'];

$filter_territory = $_GET['filter_territory'];

$filter_user_id = $_GET['filter_user_id'];
$filter_radius = $_GET['filter_radius'];

$submit1 = $_GET['submit1'];

?>
<form action="map_service.php" method="get" name="form1">
<input type="hidden" name="lat" value="<?=$lat?>">
<input type="hidden" name="lng" value="<?=$lng?>">
<input type="hidden" name="zoom" value="<?=$zoom?>">

<input type="hidden" name="check_dis" value="<?=$check_dis?>">
<input type="hidden" name="check_inprogress" value="<?=$check_inprogress?>">
<input type="hidden" name="check_eta" value="<?=$check_eta?>">
<input type="hidden" name="check_res" value="<?=$check_res?>">
<input type="hidden" name="filter_resource" value="<?=$filter_resource?>">
<input type="hidden" name="check_conf" value="<?=$check_conf?>">
<input type="hidden" name="filter_territory" value="<?=$filter_territory?>">
<input type="hidden" name="filter_address" value="<?=$filter_address?>">
<input type="hidden" name="filter_address_radio" value="<?=$filter_address_radio?>">
<input type="hidden" name="filter_user_id" value="<?=$filter_user_id?>">
<input type="hidden" name="filter_radius" value="<?=$filter_radius?>">
<input type="hidden" name="submit1" value="<?=$submit1?>">

</form>

<?php if($filter_address_radio == "M" && $filter_address != ""){ ?>

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=<?=$GOOGLE_API?>" type="text/javascript">
    </script>

<script>

if (GBrowserIsCompatible()) { 

      // ====== Create a Client Geocoder ======
      var geo = new GClientGeocoder();

      // ====== Array for decoding the failure codes ======
      var reasons=[];
      reasons[G_GEO_SUCCESS]            = "Success";
      reasons[G_GEO_MISSING_ADDRESS]    = "Missing Address: The address was either missing or had no value.";
      reasons[G_GEO_UNKNOWN_ADDRESS]    = "Unknown Address:  No corresponding geographic location could be found for the specified address.";
      reasons[G_GEO_UNAVAILABLE_ADDRESS]= "Unavailable Address:  The geocode for the given address cannot be returned due to legal or contractual reasons.";
      reasons[G_GEO_BAD_KEY]            = "Bad Key: The API key is either invalid or does not match the domain for which it was given";
      reasons[G_GEO_TOO_MANY_QUERIES]   = "Too Many Queries: The daily geocoding quota for this site has been exceeded.";
      reasons[G_GEO_SERVER_ERROR]       = "Server error: The geocoding request could not be successfully processed.";
      reasons[403]                      = "Error 403: Probably an incorrect error caused by a bug in the handling of invalid JSON.";

      // ====== Geocoding ======
	  var p;
	  var lat = 37.25;
	  var lng = -96.857788;
	  var zoomlevel = 5;
	  
	  var search = "<?=$filter_address?>";
        geo.getLocations(search, function (result)
          { 
            // If that was successful
			//alert("test"); 
            if (result.Status.code == G_GEO_SUCCESS) {
              // Lets assume that the first marker is the one we want
              p = result.Placemark[0].Point.coordinates;
              lat=p[1];
              lng=p[0];
			  zoomlevel = 10;
			  document.form1.lat.value = lat;
			  document.form1.lng.value = lng;
              

            }
			else {
			  //alert(result.Status.code);
			}
			document.form1.submit();
            // ====== Decode the error status ======
          }
	  );
	  
	 
    }

</script>

<?php } else { 
$url = "map_service.php?check_dis=$check_dis&check_inprogress=$check_inprogress&";
$url .= "check_eta=$check_eta&check_res=$check_res&filter_resource=$filter_resource&";
$url .= "check_conf=$check_conf&";
$url .= "filter_territory=$filter_territory&";
$url .= "filter_address=$filter_address&filter_address_radio=$filter_address_radio&submit1=$submit1";
$url .= "&lat=$lat&lng=$lng&zoom=$zoom";
$url .= "&filter_user_id=$filter_user_id&filter_radius=$filter_radius";

meta_redirect($url);
} ?>