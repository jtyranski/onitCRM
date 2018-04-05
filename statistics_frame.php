<?php
include "includes/functions.php";
$uplink = "../";

if($mobile_browser > 0) { $class="class='stats'"; } else $class = "";
$report_array[] = "<a href='" . $uplink . "pbx/pbx_index.php'><img ". $class ." src='images/statistics/call_reporting.png' alt='Call Reporting' /></a>";
$report_array[] = "<a href='" . $uplink . "report_operations.php'><img ". $class ." src='images/statistics/operations_detail.png' alt='Operations Detail' /></a>";

// query database to see if user is a serviceman
$sql = "SELECT servicemen from users where user_id='" . $SESSION_USER_ID . "'";
$user_service = getsingleresult($sql);

if($user_service == 0 || $SESSION_ISADMIN) {  // only non-servicemen have access
  $report_array[] = "<a href='" . $uplink . "report_commission_individual.php'><img ". $class ." src='images/statistics/individual_commission_report.png' alt='Individual Commission Report' /></a>";
}
if($user_service == 1 || $SESSION_ISADMIN) {  // only servicemen have access
  $report_array[] = "<a href='" . $uplink . "report_serviceman_individual.php'><img ". $class ." src='images/statistics/individual_serviceman_report.png' alt='Individual Serviceman Report' /></a>";
}
$report_array[] = "<a href='" . $uplink . "report_salesandactivity.php'><img ". $class ." src='images/statistics/prospecting_report.png' alt='Prospecting Report' /></a>";
$report_array[] = "<a href='" . $uplink . "fcs_sd_report.php'><img ". $class ." src='images/statistics/fcs_dispatch_report.png' 'FCS Dispatch Report' /></a>";
$report_array[] = "<a href='" . $uplink . "service_report.php'><img ". $class ." src='images/statistics/service_agreement_report.png' alt='Serviceman Agreement Report' /></a>";
?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<table>
<?php for($x=0;$x<sizeof($report_array);$x++){
  if($x/4==1){
    echo "<tr>";
  }
  echo "<td>". $report_array[$x] ."</td>";
  }
?>
