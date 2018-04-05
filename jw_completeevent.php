<?php
exit;
include "includes/functions.php";

$sql = "SELECT a.event_id, a.ro_user_id, c.color, concat(e.firstname, ' ', e.lastname) as fullname,
	a.what, a.act_id, a.title, a.description
	from supercali_events a, supercali_dates b, supercali_categories c, users e
	where a.event_id=b.event_id and 
	a.category_id=c.category_id
	and a.ro_user_id = e.user_id
	and e.master_id=1
	and e.programming_schedule=1
	and c.calendar_type='Programming'
	and a.complete=1
	group by b.event_id
	order by a.ro_user_id
	";
	$result = executequery($sql);
while($record = go_fetch_array($result)){
  $event_id = $record['event_id'];
  $sql = "SELECT date_format(date, \"%Y-%m-%d\") as datepretty 
	from supercali_dates where event_id='$event_id' order by date desc limit 1";
    $enddate = getsingleresult($sql);
	$sql = "UPDATE supercali_events set complete_date='$enddate' where event_id='$event_id'";
	executeupdate($sql);
}
?>
