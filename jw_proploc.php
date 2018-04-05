<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=<?=$GOOGLE_API?>" type="text/javascript">
    </script>
<script type="text/javascript">
    var addresses = [];
    var propertyId = [];
  </script>
<?php
exit;
include "includes/functions.php";

$sql = "SELECT property_id, address, city, state from properties where corporate=0 and display=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $address = stripslashes($record['address']);
  $city = stripslashes($record['city']);
  $state = stripslashes($record['state']);
  $property_id = stripslashes($record['property_id']);
  
  $fulladdress = $address .", ". $city .", ". $state;
  $fulladdress = go_reg_replace("\"", "", $fulladdress);
  echo "<script type='text/javascript' language='JavaScript'>";
  echo "addresses.push('$fulladdress');";
  echo "propertyId.push('$property_id');";
  echo "</script>";
}
?>


<script type="text/javascript">
    //<![CDATA[

    // delay between geocode requests - at the time of writing, 100 miliseconds seems to work well
    var delay = 100;
    
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
      function getAddress(propId, search, next) {
	    //alert("hello");
        geo.getLocations(search, function (result)
          { 
            // If that was successful
            if (result.Status.code == G_GEO_SUCCESS) {
              // Lets assume that the first marker is the one we want
              var p = result.Placemark[0].Point.coordinates;
              var lat=p[1];
              var lng=p[0];
              // Display the results in SQL format, write it back to our message div
			  url = "view_property_edit_latlng.php?property_id=" + propId + "&lat=" + lat + "&lng=" + lng;
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;
		

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
            }
            // ====== Decode the error status ======
            else {
              // === if we were sending the requests to fast, try this one again and increase the delay

                var reason="Code "+result.Status.code;
                if (reasons[result.Status.code]) {
                  reason = reasons[result.Status.code]
                } 
				
                //var sql = '&nbsp;&nbsp;&lt;address="' + search + '" error="' +reason+ '"&gt;<br>';
				url = "view_property_edit_latlng.php?property_id=" + propId + "&error_desc=" + reason;
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
            }
            next();
          }
        );
      }

      // ======= Global variable to remind us what to do next
      var nextAddress = 0;

      // ======= Function to call the next Geocode operation when the reply comes back

      function theNext() {
        if (nextAddress < addresses.length) {
          setTimeout('getAddress("'+propertyId[nextAddress]+'","'+addresses[nextAddress]+'",theNext)', delay);
          nextAddress++;
        }
      }

      // ======= Call that function for the first time =======
      theNext();
  
    }


    //]]>
    </script>
<?php
$_SESSION[$sess_header . '_property'] = "";
echo "Updating latitude/longitude information.  One moment, please.";
//meta_redirect("frame_prospect_properties.php?prospect_id=$prospect_id", 2);
?>
