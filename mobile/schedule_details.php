<?php include "includes/header.php"; ?>
<?php include "includes/main_nav.php"; ?>
<br>
<?php
$event_id = $_GET['event_id'];
$sql = "SELECT a.event_id, a.value, date_format(b.date, \"%a %b %e, %Y - %r\") as datepretty, c.name, d.company_name, e.roof_size, f.firstname, 
c.color, a.what, a.prospect_id, a.property_id, e.site_name, a.act_id, e.city, e.state 
from supercali_events a, supercali_dates b, supercali_categories c, prospects d, properties e, users f
where a.event_id = b.event_id and a.category_id=c.category_id and a.prospect_id = d.prospect_id and a.property_id=e.property_id 
and a.ro_user_id = f.user_id 
and a.event_id='$event_id'";
$result = executequery($sql);
$record = go_fetch_array($result);

?>

<a href="company_details.php?prospect_id=<?=$record['prospect_id']?>"><?=stripslashes($record['company_name'])?></a><br>
<font color="<?=$record['color']?>"><?=$record['name']?></font> - <?=stripslashes($record['firstname'])?><br>
<a href="property_details.php?property_id=<?=$record['property_id']?>"><?=stripslashes($record['site_name'])?></a> 
<?=stripslashes($record['city'])?>, <?=stripslashes($record['state'])?><br>
<?=$record['roof_size']?> SQS 
<?php if($record['what']=="BP") { ?>
  $<?=number_format($record['value'], 2)?>
<?php } ?>
<br><br>
<?=$record['datepretty']?>
<table class="main" width="100%">
<tr>
<td width="33%">
<a href="schedule.php">Go Back</a>
</td>
<td width="33%">
<a href="calendar_complete.php?act_id=<?=$record['act_id']?>">Complete</a>
</td>
<td width="33%">
<a href="calendar_delete.php?act_id=<?=$record['act_id']?>">Delete</a>
</td>
</tr>
</table>
<?php
include "includes/footer.php";
?>