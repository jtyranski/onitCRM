<?php
include "includes/functions.php";

$user_id = go_escape_string($_GET['user_id']);
$date = go_escape_string($_GET['date']);

$act_id = $_GET['act_id'];
$property_id = $_GET['property_id'];
$prospect_id = $_GET['prospect_id'];

$date_parts = explode("-", $date);
$pretty_date = $date_parts[1] . "/" . $date_parts[2] . "/" . $date_parts[0];

ob_start();

$sql = "SELECT concat(firstname, ' ', lastname) as fullname from users where user_id=\"$user_id\"";
$fullname = stripslashes(getsingleresult($sql));
?>
<?=$fullname?><br>Appointments on <?=$pretty_date?><br><br>
<?php
$sql = "SELECT date_format(a.date, \"%h:%i\") as datepretty, b.site_name, c.company_name from 
activities a, properties b, prospects c where a.property_id=b.property_id and a.prospect_id=c.prospect_id and a.user_id=\"$user_id\" 
and a.date like \"$date%\" and event='$RESAPPT' and a.display=1 order by a.date";
$result = executequery($sql);

while($record = go_fetch_array($result)){
  ?>
  <?=stripslashes($record['datepretty'])?> - <?=stripslashes($record['company_name'])?> - <?=stripslashes($record['site_name'])?><br>
  <?php
}


?>
<br>
<a href="javascript:SetResUser('<?=$user_id?>', '<?=$pretty_date?>')">Assign to <?=$fullname?></a>
<br>
<a href="activity_edit_info.php?act_id=<?=$act_id?>&property_id=<?=$property_id?>&prospect_id=<?=$prospect_id?>&map_user_id=<?=$user_id?>&map_date=<?=$pretty_date?>&openmap=1">Check Map</a>
<br>
<?php
$html = ob_get_contents();
ob_end_clean();

$html = jsclean($html);
?>
document.getElementById('schedzoom_cali').innerHTML = '<?=$html?>';