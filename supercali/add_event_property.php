<?php
include "../includes/functions.php";

$prospect_id = $_GET['prospect_id'];

$html = "<select name=\"property_id\">";
$sql = "SELECT property_id, site_name from properties where prospect_id='$prospect_id' order by corporate desc, site_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $html .= "<option value=\"" . $record['property_id'] . "\">" . stripslashes($record['site_name']) . "</option>";
}
$html .= "</select>";

$contacts = GetContacts('', $prospect_id);
if(is_array($contacts)){
  for($x=0;$x<sizeof($contacts);$x++){
    $html2 .= "<input type=\"checkbox\" name=\"contactlist[]\" value=\"" . $contacts[$x]['name'] . "\">" . $contacts[$x]['name'] . "<br>";
  }
}
?>

div = document.getElementById('property_div');
div.innerHTML = '<?php echo $html; ?>';

div = document.getElementById('contacts_div');
div.innerHTML = '<?php echo $html2; ?>';