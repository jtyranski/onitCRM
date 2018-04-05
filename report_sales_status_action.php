<?php
include "includes/functions.php";

$opp_id = $_POST['opp_id'];
$amount = $_POST['amount'];
$paid_date_pretty = $_POST['paid_date_pretty'];
$action = $_POST['action'];

$submit1 = $_POST['submit1'];
if($action=="Save Changes"){
  for($x=0;$x<sizeof($opp_id);$x++){
    $x_amount = $amount[$x];
	$x_amount = go_reg_replace("\$", "", $x_amount);
	$x_amount = go_reg_replace("\,", "", $x_amount);
	
	$x_paid = $paid_date_pretty[$x];
	$date_parts = explode("/", $x_paid);
    $paid_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
	
    $sql = "UPDATE opportunities set tm_amount='" . $x_amount . "', paid_date='$paid_date' where opp_id='" . $opp_id[$x] . "'";
	executeupdate($sql);
	//echo $sql . "<br>";
  }
}

if($action=="test"){
  $_SESSION['sales_map_property_id'] = $_POST['property_id'];
  meta_redirect("map_sales_status.php");
}

meta_redirect("report_sales_status.php");
?>