<?php
include "includes/functions.php";

$filter_address = $_GET['filter_address'];
$filter_address_radio = $_GET['filter_address_radio'];
//$filter_address = "1822 Powers Rd Woodstock, IL 60098";

$check_rr = $_GET['check_rr'];
$check_rm = $_GET['check_rm'];
$check_qb = $_GET['check_qb'];
$check_complete = $_GET['check_complete'];
$check_sold = $_GET['check_sold'];


$filter_property_type = $_GET['filter_property_type'];
$filter_region = $_GET['filter_region'];
$filter_territory = $_GET['filter_territory'];
$submit1 = $_GET['submit1'];

?>

<form action="map_sales_status.php" method="get" name="form1">
<input type="hidden" name="lat" value="">
<input type="hidden" name="lng" value="">

<input type="hidden" name="check_rr" value="<?=$check_rr?>">
<input type="hidden" name="check_rm" value="<?=$check_rm?>">
<input type="hidden" name="check_qb" value="<?=$check_qb?>">
<input type="hidden" name="check_complete" value="<?=$check_complete?>">
<input type="hidden" name="check_sold" value="<?=$check_sold?>">
<input type="hidden" name="filter_property_type" value="<?=$filter_property_type?>">
<input type="hidden" name="filter_region" value="<?=$filter_region?>">
<input type="hidden" name="filter_territory" value="<?=$filter_territory?>">
<input type="hidden" name="filter_address" value="<?=$filter_address?>">
<input type="hidden" name="filter_address_radio" value="<?=$filter_address_radio?>">
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


$url = "map_sales_status.php?check_rr=$check_rr&check_rm=$check_rm&";
$url .= "check_qb=$check_qb&check_complete=$check_complete&check_sold=$check_sold&";
$url .= "filter_property_type=$filter_property_type&filter_region=$filter_region&filter_territory=$filter_territory&";
$url .= "filter_address=$filter_address&filter_address_radio=$filter_address_radio&";
$url .= "submit1=$submit1";
meta_redirect($url);
} 
?>