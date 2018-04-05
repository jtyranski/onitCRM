<style>
.main{
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:11px;
  color:#000000;
}
.bold{
  font-weight:bold;
}
</style>
<script language="Javascript1.2">
  <!--
  function printpage() {
  window.print();
  }
  //-->
</script>
<body onLoad="printpage()">
<table width="100%" class="main">
<?php 
include "includes/functions.php"; 


if($_SESSION['list_boxtype']=="property"){
  ?>
  <tr class="bold">
  <td>Property</td>
  <td>City</td>
  <td>State</td>
  <td>SQS</td>
  <td>Sales Status</td>
  <td>RO Status</td>
  <td>Comp</td>
  <td>Type</td>
  <td>Last Action</td>
  <td>Region</td>
  <td>RR/RM</td>
  </tr>
  <?php
  
  $counter=0;
  for($x=0;$x<sizeof($_SESSION['list_property_id']);$x++){
    $x_property_id = $_SESSION['list_property_id'][$x];

    $sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, 
	a.address, a.city, a.state, a.identifier, b.prospect_status, a.sales_stage, a.roof_size, a.territory, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, a.zip, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region, a.ro_status
	from 
	properties a, prospects b 
	where a.prospect_id = b.prospect_id and a.property_id='$x_property_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$property_type = $record['property_type'];
	  if($property_type=="Manville"){
		$sql = "SELECT comp_amount from properties_manville where property_id='$x_property_id'";
		$money = getsingleresult($sql);
	  }

	  if($property_type=="Beazer" || $property_type=="Beazer B"){
		$sql = "SELECT settlement_number from properties_beazer where property_id='$x_property_id'";
		$money = getsingleresult($sql);
	  }
	  if($property_type=="Non-PFRI"){
		$number = "";
		$money = "";
	  }
	  $rr_rm = "";
	  $sql = "SELECT count(*) from opportunities where property_id='$x_property_id' and display=1 and product='Roof Replacement'";
	  $rr = getsingleresult($sql);
	  if($rr != 0) $rr_rm = "RR";
	  $sql = "SELECT count(*) from opportunities where property_id='$x_property_id' and display=1 and product='Roof Management'";
	  $rm = getsingleresult($sql);
	  if($rm != 0) {
	    if($rr_rm==""){
		  $rr_rm = "RM";
		}
		else {
		  $rr_rm .= "/RM";
		}
	  }
	$counter++;
	?>
	<tr>
	<td><?=stripslashes($record['site_name'])?></td>
	<td><?=stripslashes($record['city'])?></td>
	<td><?=stripslashes($record['state'])?></td>
	<td><?=number_format($record['roof_size'], 0)?></td>
	<td><?=stripslashes($record['sales_stage'])?></td>
	<td><?=stripslashes($record['ro_status'])?></td>
	<td><?=number_format($money, 2)?></td>
	<td><?=stripslashes($record['property_type'])?></td>
	<td><?=stripslashes($record['lastaction_pretty'])?></td>
	<td><?=stripslashes($record['territory'])?></td>
	<td><?=stripslashes($rr_rm)?></td>
	</tr>
	<?php


  }
} 
  
if($_SESSION['list_boxtype']=="prospect"){
  ?>
  <tr class="bold">
  <td>Company</td>
  <td>City</td>
  <td>State</td>
  <td>Properties</td>
  <td>Last Action</td>
  </tr>
  <?php


  $counter = 0;
  for($x=0;$x<sizeof($_SESSION['list_prospect_id']);$x++){
    $x_prospect_id = $_SESSION['list_prospect_id'][$x];
	$sql = "SELECT company_name, city, state, zip, 
	properties, property_type, identifier, 
	date_format(lastaction, \"%m/%d/%Y\") as lastaction_pretty
	from prospects where prospect_id='$x_prospect_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);


	$counter++;
	?>
	<tr>
	<td><?=stripslashes($record['company_name'])?></td>
	<td><?=stripslashes($record['city'])?></td>
	<td><?=stripslashes($record['state'])?></td>
	<td><?=stripslashes($record['properties'])?></td>
	<td><?=stripslashes($record['lastaction_pretty'])?></td>
	</tr>
	<?php


  }
}  // end search display company
  
if($_SESSION['list_boxtype']=="contact"){
  ?>
  <tr class="bold">
  <td>Property</td>
  <td>City</td>
  <td>State</td>
  <td>Contact</td>
  <td>Position</td>
  <td>Phone</td>
  <td>Mobile</td>
  <td>Email</td>
  </tr>
  <?php


  $counter = 0;
  for($x=0;$x<sizeof($_SESSION['list_contact_id']);$x++){
    $x_contact_id = $_SESSION['list_contact_id'][$x];
  
    $sql = "SELECT concat(firstname, ' ', lastname) as fullname, position, phone, mobile, email, prospect_id, property_id 
    from contacts where id='$x_contact_id'";
    $result = executequery($sql);
    $record = go_fetch_array($result);
	$fullname = stripslashes($record['fullname']);
	$position = stripslashes($record['position']);
	$phone = stripslashes($record['phone']);
	$mobile = stripslashes($record['mobile']);
	$email = stripslashes($record['email']);
	$prospect_id = stripslashes($record['prospect_id']);
	$property_id = stripslashes($record['property_id']);
	
	if($prospect_id != 0){
	  $sql = "SELECT company_name as site_name, city, state from prospects where prospect_id='$prospect_id'";
	}
	if($property_id != 0){
	  $sql = "SELECT site_name, city, state from properties where property_id='$property_id'";
	}
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$site_name = stripslashes($record['site_name']);
	$city = stripslashes($record['city']);
	$state = stripslashes($record['state']);
	
	?>
	<tr>
	<td><?=$site_name?></td>
	<td><?=$city?></td>
	<td><?=$state?></td>
	<td><?=$fullname?></td>
	<td><?=$position?></td>
	<td><?=$phone?></td>
	<td><?=$mobile?></td>
	<td><?=$email?></td>
	</tr>
	<?php


  }
}  // end search display company

?>
</table>
</body>
