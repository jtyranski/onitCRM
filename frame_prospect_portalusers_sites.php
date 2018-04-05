<?php
require_once "includes/functions.php";
$divisions = $_GET['divisions'];
$edit_sites = $_GET['edit_sites'];
$prospect_id = $_GET['prospect_id'];

$html = "";
$sql = "SELECT property_id, site_name, division_id from properties where prospect_id='" . $prospect_id . "' 
and display=1 and corporate=0 order by site_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  if(!go_reg("\," . $record['division_id'] . "\,", $divisions) && $record['division_id'] != 0) continue;
  $html .= "<input type='checkbox' name='sites[]' value='" . $record['property_id'] . "'";
  if(go_reg("\," . $record['property_id'] . "\,", $edit_sites)) $html .= " checked";
  $html .= ">" . stripslashes($record['site_name']) . "<br>";
}
?>

div = document.getElementById('sitelist');
div.innerHTML = "<?php echo $html; ?>";