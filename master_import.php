<?php
include "includes/header.php";
$master_id = $_GET['master_id'];

$sql = "SELECT master_name from master_list where master_id='$master_id'";
$master_name = stripslashes(getsingleresult($sql));
?>


<div align="center">
  <div class="whiteround" style="height:800px;">
  <div align="left">
<?php if($_SESSION['sess_msg'] != ""){?>
<div style="color:red;"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<div class="main">
Data Import for <?=$master_name?><br>
<a href="fcs_contractors_tools.php?master_id=<?=$master_id?>">Return to <?=$master_name?> Tools</a><br><br>

<form action="master_import_action.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="master_id" value="<?=$master_id?>">
Company format: cust_id, company name, address, address2, city, state, zip, website, extra field 1, billto address, billto address2, billto city, billto state, billto zip<br>
Please select the file for the company list:<br>
<input type="file" name="company">
<br><br>
Property format: property_id, cust_id (matching company cust_id), site name, address, address2, city, state, zip, roof size, extra field 1, billto address, billto address2, billto city, billto state, billto zip<br>
Please select the file for the property list:<br>
<input type="file" name="property">
<br><br>
Contacts format: cust_id, property_id, first name, last name, position, phone, fax, mobile, email<br>
Please select the file for the property list:<br>
<input type="file" name="contacts">
<br><br>
Import Identifier:<br>
<input type="text" name="identifier"><br><br>
<input type="checkbox" name="test_import" value="1">Test Import (only generate logs)<br>
<input type="checkbox" name="dup_check" value="1">Duplicate Check<br>
<input type="checkbox" name="orphan" value="1" checked="checked">Store "Orphan" properties in a holding company<br>
<br>
<input type="submit" name="submit1" value="Import">
</form>
</div>
<br>
<a href="master_import_error.php?master_id=<?=$master_id?>">View Property Import Errors for this core company</a>
<br>
<a href="master_import_error_contact.php?master_id=<?=$master_id?>">View Contact Import Errors for this core company</a>

<br><br>
<strong>Import History</strong>
<br>
<?php
$sql = "SELECT *, date_format(ts, \"%m/%d/%Y %r\") as datepretty from import_log where master_id='$master_id' order by id desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  Identifier - <?=stripslashes($record['import_identifier'])?>
  <?php if($record['test']==1) echo " [Test Import]";?>
  <br>
  Date - <?=$record['datepretty']?><br>
  <?=$record['prospects_good']?> of <?=$record['prospects_total']?> Companies import (<?=$record['prospects_fail']?> fail) 
  <?php if($record['prospects_file'] != "") { ?>
  <a href="uploaded_files/import_logs/<?=$record['prospects_file']?>" target="_blank">[File]</a>
  <?php } ?>
  <br>
  
  <?=$record['properties_good']?> of <?=$record['properties_total']?> Properties import (<?=$record['properties_fail']?> fail) 
  <?php if($record['properties_file'] != "") { ?>
  <a href="uploaded_files/import_logs/<?=$record['properties_file']?>" target="_blank">[File]</a>
  <?php } ?>
  <br>
  
  <?=$record['contacts_good']?> of <?=$record['contacts_total']?> Contacts import (<?=$record['contacts_fail']?> fail) 
  <?php if($record['contacts_file'] != "") { ?>
  <a href="uploaded_files/import_logs/<?=$record['contacts_file']?>" target="_blank">[File]</a>
  <?php } ?>
  <br>
  
  <br>
  <?php
}
?>
  
  
</div>
</div>
</div>