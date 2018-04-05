<?php
include "includes/functions.php";

$master_id = $_GET['master_id'];
$to = $_GET['to'];
$from = $_GET['from'];
$sent = $_GET['sent'];
$searchby = $_GET['searchby'];
if($searchby=="") $searchby = "todate";


$wd = date('w') ;
$daterange = "1=1";
// this week
switch($searchby){
  case "todate":{
    $daterange = "1=1";
	break;
  }
  case "today":{
    $daterange = "XXX like '" . date("Y") . "-" . date("m") . "-" . date("d") . "%'";
	break;
  }
  case "yesterday":{
    $yesterday = date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
	$daterange = "XXX like '" . $yesterday . "%' ";
	break;
  }
case "thisweek":{
switch ($wd)
{

  case 1: {
    $daterange = 'XXX >= date_add(curdate(), interval 0 DAY) and XXX <= date_add(curdate(), interval 7 DAY)';
    break;
  }

  case 2: {
    $daterange = 'XXX >= date_add(curdate(), interval -1 DAY) and XXX <= date_add(curdate(), interval 6 DAY)';
    break;
  }

  case 3: {
    $daterange = 'XXX >= date_add(curdate(), interval -2 DAY) and XXX <= date_add(curdate(), interval 5 DAY)';
    break;
  }

  case 4: {
    $daterange = 'XXX >= date_add(curdate(), interval -3 DAY) and XXX <= date_add(curdate(), interval 4 DAY)';
    break;
  }

  case 5: {
    $daterange = 'XXX >= date_add(curdate(), interval -4 DAY) and XXX <= date_add(curdate(), interval 3 DAY)';
    break;
  }

  case 6: {
    $daterange = 'XXX >= date_add(curdate(), interval -5 DAY) and XXX <= date_add(curdate(), interval 2 DAY)';
    break;
  }

  case 0: {
    $daterange = 'XXX >= date_add(curdate(), interval -6 DAY) and XXX <= date_add(curdate(), interval 1 DAY)';
    break;
  }
}
// end this week
break;
}

case "lastweek":{
// last week
switch ($wd)
{

  case 1: {
    $daterange = 'XXX >= date_add(curdate(), interval -7 DAY) and XXX <= date_add(curdate(), interval 0 DAY)';
    break;
  }

  case 2: {
    $daterange = 'XXX >= date_add(curdate(), interval -8 DAY) and XXX <= date_add(curdate(), interval -1 DAY)';
    break;
  }

  case 3: {
    $daterange = 'XXX >= date_add(curdate(), interval -9 DAY) and XXX <= date_add(curdate(), interval -2 DAY)';
    break;
  }

  case 4: {
    $daterange = 'XXX >= date_add(curdate(), interval -10 DAY) and XXX <= date_add(curdate(), interval -3 DAY)';
    break;
  }

  case 5: {
    $daterange = 'XXX >= date_add(curdate(), interval -11 DAY) and XXX <= date_add(curdate(), interval -4 DAY)';
    break;
  }

  case 6: {
    $daterange = 'XXX >= date_add(curdate(), interval -12 DAY) and XXX <= date_add(curdate(), interval -5 DAY)';
    break;
  }

  case 0: {
    $daterange = 'XXX >= date_add(curdate(), interval -13 DAY) and XXX <= date_add(curdate(), interval -6 DAY)';
    break;
  }
}
// end last week
break;
}
  case "thismonth":{
	$timesearch = date("Y-m");
	$daterange = "XXX like '$timesearch%'";
	break;
  }
  
  case "lastmonth":{
    $d = date("j");
    $foo = 0;
    if($d==30 || $d==31) $foo = 3;
    $lastmonth = mktime(0, 0, 0, date("m")-1, date("d") - $foo,   date("Y"));
	$timesearch = date("Y-m", $lastmonth);
	$daterange = "XXX like '$timesearch%'";
	break;
  }
  
  case "ytd":{
	$timesearch = date("Y");
	$daterange = "XXX like '$timesearch%'";
	break;
  }
  case "Custom":{
    $custom_startdate = $_GET['custom_startdate'];
	$custom_enddate = $_GET['custom_enddate'];
	$start_parts = explode("/", $custom_startdate);
    $startdate_pretty = $start_parts[2] . "-" . $start_parts[0] . "-" . $start_parts[1];
	$end_parts = explode("/", $custom_enddate);
    $enddate_pretty = $end_parts[2] . "-" . $end_parts[0] . "-" . $end_parts[1];
	$daterange = " XXX >= '$startdate_pretty' and XXX <= '$enddate_pretty 23:59:59' ";
	break;
  }
  default:{
    $daterange = "XXX like '$searchby%'";
	break;
  }
  
} // end switch of searchby

$daterange_query = $daterange;
$daterange_query = str_replace("XXX", "ts", $daterange_query);

$use_timerange = $_GET['use_timerange'];
if($use_timerange==1){
  $custom_starttime = $_GET['custom_starttime'];
  $custom_endtime = $_GET['custom_endtime'];
  $custom_starttime_ampm = $_GET['custom_starttime_ampm'];
  $custom_endtime_ampm = $_GET['custom_endtime_ampm'];
  
  $parts = explode(":", $custom_starttime);
  $hours = $parts[0];
  $minutes = $parts[1];
  if($custom_starttime_ampm=="PM" && $hours < 12) $hours += 12;
  if($custom_starttime_ampm=="AM" && $hours == 12) $hours = 0;
  $starttime = $hours . ":" . $minutes;
  
  $parts = explode(":", $custom_endtime);
  $hours = $parts[0];
  $minutes = $parts[1];
  if($custom_endtime_ampm=="PM" && $hours < 12) $hours += 12;
  if($custom_endtime_ampm=="AM" && $hours == 12) $hours = 0;
  $endtime = $hours . ":" . $minutes;
  
  $timerange_query = " justtime > '$starttime' and justtime < '$endtime' ";
}
else {
  $timerange_query = " 1=1 ";
}
  
$sql = "SELECT user_id, firstname from users where master_id=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $user_id = $record['user_id'];
  $FCS_USERS[$user_id] = stripslashes($record['firstname']);
}
$FCS_USERS[0] = "SYSTEM";

$master_clause = " 1=1 ";
if($master_id != 0) $master_clause = " master_id = '$master_id' ";

ob_start();
?>

<table class="main" cellpadding="2" cellspacing="0">
<tr height="1">
<td width="30"></td>
<td width="200"></td>
<td width="350"></td>
<td width="100"></td>
<td width="30"></td>
<td width="350"></td>
<td width="300"></td>
<?php if($sent==1){?>
<td width="100"></td>
<?php }?>
</tr>
<?php
$counter=0;
$sql = "SELECT *, date_format(ts, \"%m/%d/%Y %r\") as ts_pretty, date_format(ts_sent, \"%m/%d/%Y %r\") as ts_sent_pretty 
from email_q where (to_field like \"%" . $to . "%\" or bcc like \"%" . $to . "%\" or cc like \"%" . $to . "%\") 
and from_field like \"%" . $from . "%\" and $master_clause and sent='$sent' 
and $daterange_query and $timerange_query order by id";
if($sent==1) $sql .= " DESC";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $counter++;
  if($counter % 2){
        $class = "altrow";
      }
      else {
        $class = "mainrow";
  }
  $type = "";
  if($record['to_field'] != "") $type .= "To ";
  if($record['cc'] != "") $type .= "Cc ";
  if($record['bcc'] != "") $type .= "Bcc ";
  $to_field = $record['to_field'];
  $to_field = go_reg_replace(",", "<br>", $to_field);
  $sent_user_id = $record['sent_user_id'];
  $approved = $FCS_USERS[$sent_user_id];
  ?>
  <tr class="<?=$class?>" id="<?=$record['id']?>" onclick="selectRecordRow('<?=$record['id']?>', '<?=$class?>')">
  <td valign="top"><input type="checkbox" name="email_ids[]" value="<?=$record['id']?>" id="check_<?=$record['id']?>" onchange="selectRecord('<?=$record['id']?>', this, '<?=$class?>')"></td>
  <td valign="top"><?=$record['ts_pretty']?></td>
  <td valign="top"><?=stripslashes($record['from_field'])?></td>
  <td valign="top"><?=stripslashes($type)?></td>
  <td valign="top"><?=stripslashes($record['num_recipients'])?></td>
  <td valign="top"><?=stripslashes($to_field)?></td>
  <td valign="top"><?=stripslashes($record['subject'])?></td>
  <?php if($sent==1){?>
  <td valign="top"><?=$approved?> - <?=$record['ts_sent_pretty']?></td>
  <?php } ?>
  </tr>
  <?php
}
?>
</table>
<?php
$html = ob_get_contents();
ob_end_clean();
$html = jsclean($html);
?>
div = document.getElementById('contentdiv');
div.innerHTML = '<?php echo $html; ?>';