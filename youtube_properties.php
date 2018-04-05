<?php
include "includes/functions.php";

$prospect_id = $_GET['prospect_id'];
$property_id = $_GET['property_id'];
$video_id = $_GET['video_id'];


$html = "<select name='property_id'>";
if($video_id != "new"){
$sql = "SELECT property_id, site_name from properties where display=1 and corporate=0 and prospect_id='$prospect_id' order by site_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $html .= "<option value='" . $record['property_id'] . "'";
  if($property_id==$record['property_id']) $html .= " selected";
  $html .= ">" . jsclean(stripslashes($record['site_name'])) . "</option>";
}
}
$html .= "</select>";
$html = jsclean($html);
?>

div = document.getElementById('propertyarea');
div.innerHTML = '<?php echo $html; ?>';