<?php
include "includes/functions.php";

$h1 = $_GET['h1'];
$h2 = $_GET['h2'];
$m1 = $_GET['m1'];
$m2 = $_GET['m2'];
$a1 = $_GET['a1'];
$a2 = $_GET['a2'];
$d = $_GET['d'];
$leak_id = $_GET['leak_id'];
$total_hours = $_GET['hours'];
$time_id = $_GET['time_id'];
$go = $_GET['go'];
$serviceman = $_GET['serviceman'];
$timezone = $_GET['timezone'];
if($timezone=="") $timezone = 0;

$sql = "SELECT labor_rate from am_leakcheck where leak_id='$leak_id'";
$labor_rate = getsingleresult($sql);

if($go==1){
  $date_parts = explode("/", $d);
  $datepretty = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];

  if($a1=="PM" && $h1 != 12) $h1 += 12;
  if($a1=="AM" && $h1 == 12) $h1 = 0;
  if($a2=="PM" && $h2 != 12) $h2 += 12;
  if($a2=="AM" && $h2 == 12) $h2 = 0;

  $time_in = $datepretty . " " . $h1 . ":" . $m1 . ":00";
  $time_out = $datepretty . " " . $h2 . ":" . $m2 . ":00";

  $sql = "INSERT into am_leakcheck_time(leak_id, time_in, time_out, total_hours, servicemen_1_or_2, timezone, from_app) values
  ('$leak_id', '$time_in', '$time_out', '$total_hours', '$serviceman', '$timezone', '0')";
  executeupdate($sql);
  $from_app = 0;
}

if($go==0){
  $sql = "SELECT from_app from am_leakcheck_time where id='$time_id' and leak_id='$leak_id'";
  $from_app = getsingleresult($sql);
  
  $sql = "DELETE from am_leakcheck_time where id='$time_id' and leak_id='$leak_id'";
  executeupdate($sql);
}


$subtotal_hours = 0;
$html = "<table class='main' width='100%'>";
$sql = "SELECT date_format(time_in, \"%m/%d/%Y\") as datepretty, date_format(time_in, \"%h:%i %p\") as inpretty, 
date_format(time_out, \"%h:%i %p\") as outpretty, total_hours, id, timezone, rate, travel
from am_leakcheck_time where leak_id='$leak_id' and complete=1 and from_app='$from_app' order by time_out desc";
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
    $html .= "<td>";
	$html .= "<select name='rate' onchange=\\\"javascript:modifyrate('" . $record['id'] . "', this)\\\">";
	$html .= "<option value='1'";
	if($record['rate']==1) {
	  $html .= " selected";
	  $actual_hours = $record['total_hours'];
	  $actual_rate = $labor_rate;
	}
	$html .= ">normal</option>";
	$html .= "<option value='1.5'";
	if($record['rate']==1.5) {
	  $html .= " selected";
	  $actual_hours = ($record['total_hours'] / 1.5);
	  $actual_rate = $labor_rate * 1.5;
	}
	$html .= ">x 1.5</option>";
	$html .= "<option value='2'";
	if($record['rate']==2) {
	  $html .= " selected";
	  $actual_hours = ($record['total_hours'] / 2);
	  $actual_rate = $labor_rate * 2;
	}
	$html .= ">x 2</option>";
	$html .= "</select></td>";
    $html .= "<td>";
    $html .= "<a href=\\\"javascript:DelTime('" . $record['id'] . "')\\\" style='color:red;'>X</a>";
    $html .= "</td>";
  $html .= "<td>";
  $html .= $record['datepretty'] . " - </td>";
  $html .= "<td>In: " . $record['inpretty'] . " $xtz_display</td>";
  $html .= "<td>Out: " . $record['outpretty'] . " $xtz_display</td>";
  $html .= "<td>" . number_format($record['total_hours'], 2) . " hrs ($actual_hours hrs x $" . number_format($actual_rate, 2) . "/hr)";
  $html .= "<input type='checkbox' name='travel[]' value='" . $record['id'] . "'";
  if($record['travel']==1) $html .= " checked";
  $html .= ">Travel";
  $html .= "</td>";
  $html .= "</tr>";
  $subtotal_hours += $record['total_hours'];
}
$html .= "<tr><td colspan='4'></td><td align='right'>Total:</td><td>" . number_format($subtotal_hours, 2) . " hrs</td></tr>";
$html .= "</table>";

$sql = "SELECT date_format(time_in, \"%m/%d/%Y\") as datepretty, date_format(time_in, \"%h:%i %p\") as inpretty, 
date_format(time_out, \"%h:%i %p\") as outpretty, total_hours, id 
from am_leakcheck_time where leak_id='$leak_id' and complete=1 order by time_out desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){

  $gtotal_hours += $record['total_hours'];
}

$gtotal_hours = number_format($gtotal_hours, 2);

$sql = "SELECT  total_hours, id 
from am_leakcheck_time where leak_id='$leak_id' and complete=1 and from_app=1 order by time_out desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){

  $app_hours += $record['total_hours'];
}

$app_hours = number_format($app_hours, 2);

$sql = "SELECT  total_hours, id 
from am_leakcheck_time where leak_id='$leak_id' and complete=1 and from_app=0 order by time_out desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){

  $manual_hours += $record['total_hours'];
}

$manual_hours = number_format($manual_hours, 2);

$sql = "UPDATE am_leakcheck set gtotal_hours = '$gtotal_hours' where leak_id='$leak_id'";
executeupdate($sql);


$sql = "SELECT sum(total_hours) from am_leakcheck_time where leak_id='$leak_id' and travel=0";
      $labor_time = getsingleresult($sql);
      $sql = "SELECT sum(total_hours) from am_leakcheck_time where leak_id='$leak_id' and travel=1";
      $travel_time = getsingleresult($sql);
	  
	  if($travel_time > 0 && $travel_time < .5) $travel_time = .5;
	  if($labor_time > 0 && $labor_time < .5) $labor_time = .5;
	  $labor_time = round($labor_time, 2);
	  $travel_time = round($travel_time, 2);
	  
	  $sql = "UPDATE am_leakcheck set labor_time='$labor_time', travel_time='$travel_time' where leak_id='$leak_id'";
	  executeupdate($sql);
	  
$manual = "";
if($from_app==0) $manual = "manual_";


?>

div = document.getElementById('<?=$manual?>past_times<?=$serviceman?>');
div.innerHTML = "<?php echo $html; ?>";

div = document.getElementById('gtotal_hours');
div.value = "<?php echo $gtotal_hours; ?>";

div = document.getElementById('app_hours');
div.value = "<?php echo $app_hours; ?>";

div = document.getElementById('manual_hours');
div.value = "<?php echo $manual_hours; ?>";

div = document.getElementById('gtotal_hours_display');
div.innerHTML = "<?php echo $gtotal_hours; ?>";

CalcInvoice();