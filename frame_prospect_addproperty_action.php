<?php

include "includes/functions.php";

$user_id = $SESSION_USER_ID;

$prospect_id = $_POST['prospect_id'];
$property_id = $_POST['property_id'];

$site_name = go_escape_string($_POST['site_name']);
$address = go_escape_string($_POST['address']);
$city = go_escape_string($_POST['city']);
$state = go_escape_string($_POST['state']);
$zip = go_escape_string($_POST['zip']);

$roof_size = go_escape_string($_POST['roof_size']);
$roof_type = go_escape_string($_POST['roof_type']);
$deck_type = go_escape_string($_POST['deck_type']);
$installation_type = go_escape_string($_POST['installation_type']);
$building_use = go_escape_string($_POST['building_use']);
$property_type = "Non-PFRI";


$submit1 = go_escape_string($_POST['submit1']);

$_SESSION[$sess_header . '_property'] = $_POST;

if (is_uploaded_file($_FILES['image']['tmp_name']))
{
  if(!(is_image_valid($_FILES['image']['name']))){
	$_SESSION['sess_msg'] = "You can only upload jpg, gif, or png images";
	meta_redirect("frame_prospect_addproperty.php?prospect_id=$prospect_id&property_id=$property_id");
  }
}
  
if($submit1 != ""){
  $sql = "SELECT timezone from master_list where master_id='" . $SESSION_MASTER_ID . "'";
  $timezone = getsingleresult($sql);
  if($timezone=="") $timezone = 0;
  
  if($property_id=="new"){
    $sql = "INSERT into properties(site_name, address, city, state, zip, 
	roof_size, roof_type, deck_type, installation_type, building_use, prospect_id, property_type, timezone) values (
	\"$site_name\", \"$address\", \"$city\", \"$state\", \"$zip\", 
    \"$roof_size\", \"$roof_type\", \"$deck_type\", \"$installation_type\", \"$building_use\", '$prospect_id', 
	\"$property_type\", '$timezone')";
	executeupdate($sql);
	$property_id = go_insert_id();
	
  }
  
  $sql = "SELECT comres from prospects where prospect_id='$prospect_id'";
  $comres = getsingleresult($sql);
  if($comres=="R"){
    $sqft = $roof_size * 100;
    $sql = "INSERT into sections(property_id, section_name, sqft) values('$property_id', 'Entire', $sqft)";
	executeupdate($sql);
  }
  
  if($SESSION_IREP==1){
    $sql = "UPDATE properties set irep='" . $SESSION_USER_ID . "' where property_id='$property_id'";
	executeupdate($sql);
  }
  
  $sql = "INSERT into check_latlng(property_id) values('$property_id')";
  executeupdate($sql);
  
  if (is_uploaded_file($_FILES['image']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['image']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['image']['tmp_name'], $UPLOAD . "temp/". $filename);
  	resizeimage($UPLOAD . "temp/$filename", $UPLOAD . "properties/", $PROPERTY_SIZE);
  	@unlink($UPLOAD . "temp/". $filename);
	
	$sql = "UPDATE properties set image='$filename' where property_id='$property_id'";
	executeupdate($sql);
  }
  
  
  
  $group_list = $_POST['group_list'];
    $subgroup_list = $_POST['subgroup_list'];
	
	$groups = "";
  for($x=0;$x<sizeof($group_list);$x++){
    $groups .= $group_list[$x];
  }
  
  $subgroups = "";
  for($x=0;$x<sizeof($subgroup_list);$x++){
    $subgroups .= $subgroup_list[$x];
  }
  
  $USEGROUP = 0;
  if($groups != "") $USEGROUP = $groups;
  if($subgroups != "") $USEGROUP = $subgroups;
  
  if($USEGROUP != 0){
    $sql = "SELECT timezone from groups where id='$USEGROUP'";
	$timezone = getsingleresult($sql);
	$sql = "UPDATE properties set timezone='$timezone' where property_id='$property_id'";
	executeupdate($sql);
  }
  
  $sql = "UPDATE properties set groups='$groups', subgroups='$subgroups' where property_id='$property_id'";
  executeupdate($sql);
  
  $sql = "SELECT prospect_id from properties where property_id='$property_id'";
  $prospect_id = getsingleresult($sql);
  
  $groups = "";
  $subgroups = "";
  $sql = "SELECT groups, subgroups from properties where prospect_id='$prospect_id' and display=1";
  
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $groups .= $record['groups'] . ",";
	$subgroups .= $record['subgroups'] . ",";
  }

  
  $group_array = explode(",", $groups);
  $subgroup_array = explode(",", $subgroups);
  
  $group_array = array_unique($group_array);
  $subgroup_array = array_unique($subgroup_array);
  
  $group_array = array_values($group_array);
  $subgroup_array = array_values($subgroup_array);
  
  $groups = "";
  for($x=0;$x<sizeof($group_array);$x++){
    if($group_array[$x]=="") continue;
	if($group_array[$x]==0) continue;
	$groups .= "," . $group_array[$x] . ",";
  }
  
  $subgroups = "";
  for($x=0;$x<sizeof($subgroup_array);$x++){
    if($subgroup_array[$x]=="") continue;
	if($subgroup_array[$x]==0) continue;
	$subgroups .= "," . $subgroup_array[$x] . ",";
  }
  
  $sql = "UPDATE prospects set groups='$groups', subgroups='$subgroups' where prospect_id='$prospect_id'";
  executeupdate($sql);
  
  
  
  
  
  
  $sql = "SELECT count(*) from properties where prospect_id='$prospect_id' and corporate=0 and display=1";
  $props = getsingleresult($sql);
  $sql = "UPDATE prospects set properties='$props' where prospect_id='$prospect_id'";
  executeupdate($sql);
  
  $fulladdress = $address .", ". $city .", ". $state;
  $fulladdress = go_reg_replace("\"", "", $fulladdress);
  echo "<script type='text/javascript' language='JavaScript'>";
  echo "addresses.push('$fulladdress');";
  echo "propertyId.push('$property_id');";
  echo "</script>";
}
?>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=<?=$GOOGLE_API?>" type="text/javascript">
    </script>
<script type="text/javascript">
    var addresses = [];
    var propertyId = [];
  </script>

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
meta_redirect("frame_prospect_properties.php?prospect_id=$prospect_id", 2);
?>
