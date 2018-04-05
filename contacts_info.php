<?php
require_once "includes/functions.php";

function clean($x){
  $x = go_reg_replace("\n", "", $x);
  $x = go_reg_replace("\r", "", $x);
  $x = go_reg_replace("\"", "&quot;", $x);
  $x = go_reg_replace("\<", "&lt;", $x);
  $x = go_reg_replace("\>", "&gt;", $x);
  return $x;
}

$cand_type = $_GET['cand_type'];

$firstrecord = $_GET['firstrecord'];
$direction = $_GET['direction'];

$prospecting = $_GET['prospecting'];

$row = $_SESSION[$sess_header . '_contacts_search_results'];
$order_by = $_SESSION[$sess_header . '_contacts_order_by'];
$order_by2 = $_SESSION[$sess_header . '_contacts_order_by2'];
$search_display = $_SESSION[$sess_header . '_contacts_search_display'];
$_SESSION[$sess_header . '_saved_selected_record'] = $firstrecord;

if(!(is_array($row))) exit;

/*
if($SESSION_USER_ID==1){
  $html = "test";
}
else {
*/

$nextrecord = $firstrecord + 1;
$prevrecord = $firstrecord - 1;
if($nextrecord == sizeof($row)) $nextrecord = 0;
if($prevrecord < 0) $prevrecord = sizeof($row) - 1;

$prospect_id = $row[$firstrecord]['prospect_id'];
$property_id = $row[$firstrecord]['property_id'];

switch($search_display){
  case "company":
  case "importidentifier":{
    $query = " prospect_id = '$prospect_id' ";
	$q2 = " company_name as site_name";
	$table = "prospects";
	//$link = "save_selected_record.php?record=$firstrecord&type=company&pid=$prospect_id";
	$link = "view_company.php?prospect_id=$prospect_id";
	/*
	$sql = "SELECT last_action_notes from prospects where prospect_id='$prospect_id'";
	$last_action_notes = stripslashes(getsingleresult($sql));
	$last_action_notes = clean($last_action_notes);
	*/
	break;
  }
  default:{
    $query = " property_id = '$property_id' ";
	$q2 = " site_name";
	$table = "properties";
	//$link = "save_selected_record.php?record=$firstrecord&type=property&pid=$property_id";
	$link = "view_property.php?property_id=$property_id";
	break;
  }
}
$sql = "SELECT $q2, address, city, state, zip from $table where $query";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);
$address = stripslashes($record['address']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$zip = stripslashes($record['zip']);

$site_name = go_reg_replace("\"", "", $site_name);
$address = go_reg_replace("\"", "", $address);
$city = go_reg_replace("\"", "", $city);
$state = go_reg_replace("\"", "", $state);
$zip = go_reg_replace("\"", "", $zip);

$html = "<div style='width:100%; position:relative;'>";
$html .= "<div style='float:left; width:100%;'>";
$html .= "<table width='100%' class='main'>";
$html .= "<tr>";
$html .= "<td valign='top' width='40%' align='left'>";
//if($cand_type==0) $html .= "<a href=\\\"javascript:completeProspect('$prospect_id')\\\">test</a><br>";
$html .= "<strong>" . $site_name . "</strong><br>";
$html .= $address . "<br>";
$html .= $city . ", " . $state . " " . $zip . "</td>";

$limit = 2;
/*
if($cand_type==1 || $cand_type==2){
  $sql = "SELECT regarding, date_format(date, \"%m/%d/%Y\") as datepretty from activities where $query and user_id='" . $SESSION_USER_ID . "' 
  and complete=0 order by date, act_id limit 1";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $regarding = stripslashes($record['regarding']);
  $regarding = superclean($regarding);
  $last_col = "<td valign='top'><table><tr><td valign='top' align='left'><strong>Next Activity:</strong><br>" . $record['datepretty'] . "</td><td valign='top' align='left'>" . $regarding . "</trd></tr></table></td>";
  $limit = 1;
}
if($cand_type==0){
  $last_col = "<td valign='top' width='30%'><table><tr><td valign='top' align='left'><strong>Notes:</strong><br><input type='button' name='buttoncomplete' value='Complete' onclick=\\\"completeProspect('$prospect_id')\\\"></td><td valign='top' align='left'>$last_action_notes</td></tr></table></td>";
  $limit = 1;
}
*/

$sql = "SELECT * from contacts where $query order by id limit $limit";
$result = executequery($sql);

while($record = go_fetch_array($result)){
  $firstname = stripslashes($record['firstname']);
  $lastname = stripslashes($record['lastname']);
  $title = stripslashes($record['title']);
  $phone = stripslashes($record['phone']);
  $mobile = stripslashes($record['mobile']);
  $email = stripslashes($record['email']);
  
  $firstname = go_reg_replace("\"", "", $firstname);
  $lastname = go_reg_replace("\"", "", $lastname);
  $title = go_reg_replace("\"", "", $title);
  $phone = go_reg_replace("\"", "", $phone);
  $mobile = go_reg_replace("\"", "", $mobile);
  $email = go_reg_replace("\"", "", $email);
  
  
  
  $html .= "<td valign='top' width='30%' align='left'>";
  $html .= "<strong>" . $firstname . " " . $lastname . "</strong><br>";
  if($title != "") $html .= "<strong>" . $title . "</strong><br>";
  if($phone != "") $html .= "P: " . $phone . "<br>";
  if($mobile != "") $html .= "C: " . $mobile . "<br>";
  if($email != "") $html .= "E: " . $email;
  $html .= "</td>";
}
if($last_col != ""){
  $html .= $last_col;
}
$html .= "</tr></table>";
$html .= "</div>";
$html .= "</div><div style='clear:both;'></div>";

//} // end of my test for user id 1

$expandarrow = "<div style='position:absolute; bottom:30px;'><a href='" . $link . "'><img src='images/arrow-button-down_off.png' border='0'></a></div>";

$rightarrow = ScrollingArrow("R", "contacts_info.php", $nextrecord, $cand_type);
$leftarrow = ScrollingArrow("L", "contacts_info.php", $prevrecord, $cand_type);

$record_number = $firstrecord + 1;
//if($test==1) $html = "testing 123";
?>

div = document.getElementById('contacts2');
div.innerHTML = "<?php echo $html; ?>";

div = document.getElementById('rightarrow');
div.innerHTML = "<?php echo $rightarrow; ?>";
div = document.getElementById('leftarrow');
div.innerHTML = "<?php echo $leftarrow; ?>";

div = document.getElementById('record_number');
div.innerHTML = "<?php echo $record_number; ?>";
div = document.getElementById('total_records');
div.innerHTML = "<?php echo sizeof($row); ?>";

<?php if($prospecting != 1){ ?>
highlightRecord('<?=$firstrecord?>');
<?php } ?>
slidedown("contacts2");
slideup("contacts1");
//pausecomp('500');
setTimeout("divswitch('contacts')",600);

div = document.getElementById('expandarrow');
div.innerHTML = "<?php echo $expandarrow; ?>";
