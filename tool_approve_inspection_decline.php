<?php include "includes/header_white.php"; ?>
<?php
$act_id = $_GET['act_id'];

$sql = "SELECT a.property_id, a.event, b.site_name, b.address, b.city, b.state, b.zip, date_format(a.date, \"%m/%d/%Y %r\") as datepretty 
from activities a, properties b 
where a.property_id=b.property_id and a.act_id='$act_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);
$address = stripslashes($record['address']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$zip = stripslashes($record['zip']);
$datepretty = stripslashes($record['datepretty']);
$event = stripslashes($record['event']);
?>

<div class="main">
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div style="color:red;"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<form action="tool_approve_inspection_decline_action.php" method="post">
<input type="hidden" name="act_id" value="<?=$act_id?>">
I am unable to perform the <?=$event?> at <?=$site_name?> on <?=$datepretty?> for the following reason:<br>
<textarea name="reason" rows="5" cols="70"></textarea>
<br>
<input type="submit" name="submit1" value="Send">
</form>

</div>