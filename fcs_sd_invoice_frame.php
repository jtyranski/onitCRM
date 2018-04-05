<?php
require_once "includes/functions.php";

$leak_id = $_GET['leak_id'];

$sql = "SELECT a.property_id, a.prospect_id, a.correction, a.invoice_type, a.materials, a.labor_rate, a.gtotal_hours, a.extra_cost, 
a.invoice_total, a.rtm_billing, a.sub_total, a.promotional_amount, a.discount_amount, a.rtm_amount, a.billto, a.status from am_leakcheck a 
where a.leak_id='$leak_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$leak['property_id'] = stripslashes($record['property_id']);
$leak['prospect_id'] = stripslashes($record['prospect_id']);
$leak['correction'] = stripslashes($record['correction']);
$leak['invoice_type'] = stripslashes($record['invoice_type']);
$leak['materials'] = stripslashes($record['materials']);
$leak['labor_rate'] = stripslashes($record['labor_rate']);
$leak['gtotal_hours'] = stripslashes($record['gtotal_hours']);
$leak['extra_cost'] = stripslashes($record['extra_cost']);
$leak['invoice_total'] = stripslashes($record['invoice_total']);
$leak['rtm_billing'] = stripslashes($record['rtm_billing']);
$leak['sub_total'] = stripslashes($record['sub_total']);
$leak['promotional_amount'] = stripslashes($record['promotional_amount']);
$leak['discount_amount'] = stripslashes($record['discount_amount']);
$leak['rtm_amount'] = stripslashes($record['rtm_amount']);
$leak['labor_cost'] = $leak['labor_rate'] * $leak['gtotal_hours'];
$leak['billto'] = nl2br(stripslashes($record['billto']));
$leak['status'] = stripslashes($record['status']);
$total = number_format($leak['invoice_total'], 2);
if($leak['status']=="Closed Out") {
  $leak['materials'] = "Thank you for your business.";
  $total .= "<br>PAID IN FULL";
}

$sql = "SELECT company_name, address, city, state, zip, logo from prospects where prospect_id='" . $leak['prospect_id'] . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company['name'] = stripslashes($record['company_name']);
$company['address'] = stripslashes($record['address']);
$company['city'] = stripslashes($record['city']);
$company['state'] = stripslashes($record['state']);
$company['zip'] = stripslashes($record['zip']);


$sql = "SELECT site_name, address, city, state, zip from properties where property_id='" . $leak['property_id'] . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$property['site_name'] = stripslashes($record['site_name']);
$property['address'] = stripslashes($record['address']);
$property['city'] = stripslashes($record['city']);
$property['state'] = stripslashes($record['state']);
$property['zip'] = stripslashes($record['zip']);

$sql = "SELECT concat(firstname, ' ', lastname) as fullname, phone from contacts where prospect_id='" . $leak['prospect_id'] . "' 
order by id limit 1";
$result = executequery($sql);
$record = go_fetch_array($result);
$contact['fullname'] = stripslashes($record['fullname']);
$contact['phone'] = stripslashes($record['phone']);

$billto = $contact['fullname'] . "<br>";
$billto .= $company['name'] . "<br>";
$billto .= $company['address'] . "<br>";
$billto .= $company['city'] . ", " . $company['state'] . " " . $company['zip'] . "<br>";
$billto .= $contact['phone'];

$property_info = $property['site_name'] . "<br>";
$property_info .= $property['address'] . "<br>";
$property_info .= $property['city'] . ", " . $property['state'] . " " . $property['zip'] . "<br>";


$desc = "<table class='main' width='100%' cellpadding='0' cellspacing='0' style='border:1px solid black;'>";
$desc .= "<tr bgcolor='#004586'>";
$desc .= "<td class='main_white' width='80%' align='center'>DESCRIPTION</td>";
$desc .= "<td width='1'></td>";
$desc .= "<td class='main_white' align='center'>AMOUNT</td>";
$desc .= "</tr>";

switch($leak['invoice_type']){
  case "Billable - TM":{
    $desc .= "<tr>";
	$desc .= "<td>Billable - T&amp;M</td>";
	$desc .= "<td bgcolor='black'><img src='" . $CORE_URL . "images/spacer.gif'></td>";
	$desc .= "<td align='right'></td>";
	$desc .= "</tr>";
	$desc .= "<tr>";
	$desc .= "<td>Material Cost</td>";
	$desc .= "<td bgcolor='black'><img src='" . $CORE_URL . "images/spacer.gif'></td>";
	$desc .= "<td align='right'>" . number_format($leak['extra_cost'], 2) . "</td>";
	$desc .= "</tr>";
	$desc .= "<tr>";
	$desc .= "<td>Labor: " . $leak['gtotal_hours'] . "hrs @ $" . $leak['labor_rate'] . "/hr</td>";
	$desc .= "<td bgcolor='black'><img src='" . $CORE_URL . "images/spacer.gif'></td>";
	$desc .= "<td align='right'>" . number_format($leak['labor_cost'], 2) . "</td>";
	$desc .= "</tr>";
	break;
  }
  case "Billable - Contract":{
    $desc .= "<tr>";
	$desc .= "<td>Billable - Contract</td>";
	$desc .= "<td bgcolor='black'><img src='" . $CORE_URL . "images/spacer.gif'></td>";
	$desc .= "<td align='right'>" . number_format($leak['sub_total'], 2) . "</td>";
	$desc .= "</tr>";
	break;
  }
  case "Billable - Contract(minor)":{
    $desc .= "<tr>";
	$desc .= "<td>Billable - Contract</td>";
	$desc .= "<td bgcolor='black'><img src='" . $CORE_URL . "images/spacer.gif'></td>";
	$desc .= "<td align='right'>" . number_format($leak['sub_total'], 2) . "</td>";
	$desc .= "</tr>";
	break;
  }
  case "Billable - Contract(major)":{
    $desc .= "<tr>";
	$desc .= "<td>Billable - Contract</td>";
	$desc .= "<td bgcolor='black'><img src='" . $CORE_URL . "images/spacer.gif'></td>";
	$desc .= "<td align='right'>" . number_format($leak['sub_total'], 2) . "</td>";
	$desc .= "</tr>";
	break;
  }
  case "2 Year":
  case "Warranty":
  case "Billable - Warranty":{
    $desc .= "<tr>";
	$desc .= "<td>" . $leak['invoice_type'] . "</td>";
	$desc .= "<td bgcolor='black'><img src='" . $CORE_URL . "images/spacer.gif'></td>";
	$desc .= "<td align='right'></td>";
	$desc .= "</tr>";
	$desc .= "<tr>";
	$desc .= "<td>Material Cost</td>";
	$desc .= "<td bgcolor='black'><img src='" . $CORE_URL . "images/spacer.gif'></td>";
	$desc .= "<td align='right'>" . number_format($leak['extra_cost'], 2) . "</td>";
	$desc .= "</tr>";
	$desc .= "<tr>";
	$desc .= "<td>Labor: " . $leak['gtotal_hours'] . "hrs @ $" . $leak['labor_rate'] . "/hr</td>";
	$desc .= "<td bgcolor='black'><img src='" . $CORE_URL . "images/spacer.gif'></td>";
	$desc .= "<td align='right'>" . number_format($leak['labor_cost'], 2) . "</td>";
	$desc .= "</tr>";
	break;
  }
  case "RTM":{
    $desc .= "<tr>";
	$desc .= "<td>RoofTop Maintenance - " . $leak['rtm_billing'] . "</td>";
	$desc .= "<td bgcolor='black'><img src='" . $CORE_URL . "images/spacer.gif'></td>";
	$desc .= "<td align='right'>" . number_format($leak['rtm_amount'], 2) . "</td>";
	$desc .= "</tr>";
	break;
  }
}
$desc .= "<tr>";
$desc .= "<td></td>";
$desc .= "<td bgcolor='black'><img src='" . $CORE_URL . "images/spacer.gif'></td>";
$desc .= "<td align='right'></td>";
$desc .= "</tr>";

$desc .= "<tr>";
$desc .= "<td valign='top' style='padding:3px 3px 3px 3px;'>Correction:<br>" . nl2br($leak['correction']) . "</td>";
$desc .= "<td bgcolor='black'><img src='" . $CORE_URL . "images/spacer.gif'></td>";
$desc .= "<td align='right'></td>";
$desc .= "</tr>";
$desc .= "</table>";
	

$sql = "SELECT body from templates where name='SD Invoice'";
$body = stripslashes(getsingleresult($sql));

$body = str_replace("[INVOICE DATE]", date("m/d/Y"), $body);
$body = str_replace("[LEAK ID]", $leak_id, $body);
$body = str_replace("[PROSPECT ID]", $leak['prospect_id'], $body);
$body = str_replace("[BILL TO]", $leak['billto'], $body);
$body = str_replace("[PROPERTY INFO]", $property_info, $body);
$body = str_replace("[DESCRIPTION TABLE]", $desc, $body);
$body = str_replace("[OTHER COMMENTS]", nl2br($leak['materials']), $body);
$body = str_replace("[SUBTOTAL]", number_format($leak['sub_total'], 2), $body);
$body = str_replace("[DISCOUNT]", number_format($leak['discount_amount'], 2), $body);
$body = str_replace("[PROMOTIONAL]", number_format($leak['promotional_amount'], 2), $body);
$body = str_replace("[TOTAL]", $total, $body);
echo $body;

?>