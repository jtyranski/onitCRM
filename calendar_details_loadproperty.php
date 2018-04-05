<?php
include "includes/functions.php";

$prospect_id = $_GET['prospect_id'];

if($prospect_id != 0 && $prospect_id != ""){
  $html = "<select name='property_id'>";
  $sql = "SELECT property_id, site_name from properties where prospect_id='$prospect_id' and display=1 order by corporate desc, site_name";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $site_name = stripslashes($record['site_name']);
	$site_name = superclean($site_name);
	$html .= "<option value='" . $record['property_id'] . "'>" . $site_name . "</option>";
  }
  $html .= "</select>";
}
else {
  $html = "<input type='hidden' name='property_id' value='0'>No Property Found";
}
?>

div = document.getElementById('property_area');
div.innerHTML = "<?php echo $html; ?>";