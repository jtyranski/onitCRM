<?php
include "includes/functions.php";

$id = $_GET['id'];
$leak_id = $_GET['leak_id'];
$serviceman = $_GET['serviceman'];

$sql = "SELECT b.timezone from am_leakcheck a, properties b where a.property_id=b.property_id and a.leak_id='$leak_id'";
$timezone = getsingleresult($sql);
$hour_now = date("H", mktime($timezone, 0, 0, 0, 0, 0));


$min = date("i");
if($min >= 0 && $min <=14) $min = 0;
if($min >= 15 && $min <=29) $min = 15;
if($min >= 30 && $min <=44) $min = 30;
if($min >=45) $min = 45;
  
if($id==0){
  $time_in = date("Y-m-d") . $hour_now . ":" . $min . ":00";
  $sql = "INSERT into am_leakcheck_time(leak_id, time_in, complete, servicemen_1_or_2, timezone) values('$leak_id', '$time_in', '0',
   '$serviceman', '$timezone')";
  executeupdate($sql);
}
else {
  $time_out = date("Y-m-d") . $hour_now . ":" . $min . ":00";
  $sql = "UPDATE am_leakcheck_time set time_out='$time_out', complete='1' where id='$id'";
  executeupdate($sql);
  
  $sql = "SELECT date_format(time_in, \"%k\") as in_hour, date_format(time_in, \"%i\") as in_minute, 
  date_format(time_out, \"%k\") as out_hour, date_format(time_out, \"%i\") as out_minute
  from am_leakcheck_time where id='$id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $in_hour = $record['in_hour'];
  $in_minute = $record['in_minute'];
  $out_hour = $record['out_hour'];
  $out_minute = $record['out_minute'];
  
  if($in_minute == 15) $in_minute = .25;
  if($in_minute == 30) $in_minute = .5;
  if($in_minute == 45) $in_minute = .75;
  
  if($out_minute == 15) $out_minute = .25;
  if($out_minute == 30) $out_minute = .5;
  if($out_minute == 45) $out_minute = .75;
  
  $in = $in_hour + $in_minute;
  $out = $out_hour + $out_minute;
  
  $total_hours = $out - $in;
  if($total_hours < 0) $total_hours += 24;
  
  $sql = "UPDATE am_leakcheck_time set total_hours='$total_hours' where id='$id'";
  executeupdate($sql);
}



$html = "<table class='main' width='100%'>";
$sql = "SELECT date_format(time_in, \"%m/%d/%Y\") as datepretty, date_format(time_in, \"%r\") as inpretty, id, timezone
from am_leakcheck_time where leak_id='$leak_id' and complete=0 and servicemen_1_or_2 = '$serviceman' order by time_in desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x_timezone = $record['timezone'];
  switch($x_timezone){
    case 1:{
	  $xtz_display = "EST";
	  break;
	}
	case 0:{
	  $xtz_display = "CST";
	  break;
	}
	case -1:{
	  $xtz_display = "MST";
	  break;
	}
	case -2:{
	  $xtz_display = "PST";
	  break;
	}
	default:{
	  $xtz_display = "CST";
	  break;
	}
  }
  $html .= "<tr>";
  $html .= "<td>" . $record['datepretty'] . " - </td>";
  $html .= "<td>In: " . $record['inpretty'] . " $xtz_display</td>";
  $html .= "<td>";
  $html .= "<input type='button' name='buttonout" . $record['id'] . "' value='Punch Out' onclick=\\\"Punch('" . $record['id'] . "', '$serviceman')\\\"";
  $html .= "</td>";

  $html .= "</tr>";
}
$html .= "</table>";



?>

div = document.getElementById('punch_div<?=$serviceman?>');
div.innerHTML = "<?php echo $html; ?>";

<?php
if($id != 0){  // if punching out, run the stuff for the regular add time


$subtotal_hours = 0;
$html = "<table class='main' width='100%'>";
$sql = "SELECT date_format(time_in, \"%m/%d/%Y\") as datepretty, date_format(time_in, \"%r\") as inpretty, 
date_format(time_out, \"%r\") as outpretty, total_hours, id, timezone, rate
from am_leakcheck_time where leak_id='$leak_id' and complete=1 and servicemen_1_or_2 = '$serviceman' order by time_out desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x_timezone = $record['timezone'];
  switch($x_timezone){
    case 1:{
	  $xtz_display = "EST";
	  break;
	}
	case 0:{
	  $xtz_display = "CST";
	  break;
	}
	case -1:{
	  $xtz_display = "MST";
	  break;
	}
	case -2:{
	  $xtz_display = "PST";
	  break;
	}
	default:{
	  $xtz_display = "CST";
	  break;
	}
  }
  $html .= "<tr>";
  if($_SESSION['ro_fcs_editreport'] == 1){
    $html .= "<td>";
	$html .= "<select name='rate' onchange=\\\"javascript:modifyrate('" . $record['id'] . "', this)\\\">";
	$html .= "<option value='1'";
	if($record['rate']==1) $html .= " selected";
	$html .= ">normal</option>";
	$html .= "<option value='1.5'";
	if($record['rate']==1.5) $html .= " selected";
	$html .= ">x 1.5</option>";
	$html .= "<option value='2'";
	if($record['rate']==2) $html .= " selected";
	$html .= ">x 2</option>";
	$html .= "</select></td>";
    $html .= "<td>";
    $html .= "<a href=\\\"javascript:DelTime('" . $record['id'] . "')\\\" style='color:red;'>X</a>";
    $html .= "</td>";
  }
  $html .= "<td>";
  $html .= $record['datepretty'] . " - </td>";
  $html .= "<td>In: " . $record['inpretty'] . " $xtz_display</td>";
  $html .= "<td>Out: " . $record['outpretty'] . " $xtz_display</td>";
  $html .= "<td>" . number_format($record['total_hours'], 2) . " hrs</td>";
  $html .= "</tr>";
  $subtotal_hours += $record['total_hours'];
}
$html .= "<tr><td colspan='4'></td><td align='right'>Total:</td><td>" . number_format($subtotal_hours, 2) . " hrs</td></tr>";
$html .= "</table>";

$sql = "SELECT date_format(time_in, \"%m/%d/%Y\") as datepretty, date_format(time_in, \"%r\") as inpretty, 
date_format(time_out, \"%r\") as outpretty, total_hours, id 
from am_leakcheck_time where leak_id='$leak_id' and complete=1 order by time_out desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $gtotal_hours += $record['total_hours'];
}


$sql = "UPDATE am_leakcheck set gtotal_hours = '$gtotal_hours' where leak_id='$leak_id'";
executeupdate($sql);

?>

div = document.getElementById('past_times<?=$serviceman?>');
div.innerHTML = "<?php echo $html; ?>";

div = document.getElementById('gtotal_hours');
div.value = "<?php echo $gtotal_hours; ?>";

CalcInvoice();

<?php } ?>