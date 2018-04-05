<?php
include "includes/header.php";
$master_id = $_GET['master_id'];
$sql = "SELECT master_name from master_list where master_id='$master_id'";
$master_name = stripslashes(getsingleresult($sql));
?>
<script>
function openclose(id, x){
  if(x==1){
    document.getElementById("arrow_" + id).innerHTML = "<a href=\"javascript:openclose('" + id + "', '0')\">-</a>";
	document.getElementById("group_" + id).style.display="";
  }
  else {
    
    document.getElementById("arrow_" + id).innerHTML = "<a href=\"javascript:openclose('" + id + "', '1')\">+</a>";
	document.getElementById("group_" + id).style.display="none";
	
  }
}

function checkclass(classname, x){

  var allHTMLTags=document.getElementsByTagName("*");
//Loop through all tags using a for loop
  for (i=0; i<allHTMLTags.length; i++) {
//Get all tags with the specified class name.
    if (allHTMLTags[i].className==classname) {
	  if(x.checked==true){
        allHTMLTags[i].checked=true;
	  }
	  else{
	    allHTMLTags[i].checked=false;
	  }
    }
  }

}
</script>
<form action="master_import_error_export.php" method="post">
<input type="hidden" name="type" value="contact">
<div align="center">
  <div class="whiteround" style="height:800px;">
  <div align="left">
  Data Import Contact Errors for <?=$master_name?><br>
  <a href="fcs_contractors_tools.php?master_id=<?=$master_id?>">Return to <?=$master_name?> Tools</a><br><br>
  <?php
  $counter=0;
  $sql = "SELECT import_identifier, test from import_errors_contact where master_id='$master_id' group by import_identifier";
  $result = executequery($sql);
  while($record=go_fetch_array($result)){
    $counter++;
	$ii = stripslashes($record['import_identifier']);
	if($ii=="") $ii = "[BLANK]";
	$test = $record['test'];
	
	$sql = "SELECT count(*) as count from import_log where import_identifier=\"" . $record['import_identifier'] . "\" and master_id='$master_id'";
	$num_logs = getsingleresult($sql);
	
	$sql = "SELECT contacts_total, contacts_good, contacts_fail from import_log where import_identifier=\"" . $record['import_identifier'] . "\" and master_id='$master_id' order by id DESC limit 1";
	$res2 = executequery($sql);
	$rec2 = go_fetch_array($res2);
	$contacts_total = $rec2['contacts_total'];
	$contacts_good = $rec2['contacts_good'];
	$contacts_fail = $rec2['contacts_fail'];
	?>
	<span id="arrow_<?=$counter?>"><a href="javascript:openclose('<?=$counter?>', '1')">+</a></span>Identifier: <?=$ii?>
	<?php if($test==1) echo " [Test Import]";?>
	<?php if($contacts_total != "" && $contacts_total != 0){?>
	<?=$contacts_good?> out of <?=$contacts_total?> contacts will import (<?=$contacts_fail?> fail)
	<?php } ?>
	<?php if($num_logs > 1) echo " [multiple entries for " . $master_name . " with Identifier of $ii]"; ?>
	<br>
	<div id="group_<?=$counter?>" style="display:none; height:600px; overflow:auto;">
	<table width="100%" class="main">
	<tr>
	<td><input type="checkbox" onchange="checkclass('<?=$counter?>', this)"></td>
	<td>Property Id</td>
	<td>Cust Id</td>
	<td>First Name</td>
	<td>Last Name</td>
	<td>Position</td>
	<td>Phone</td>
	<td>Fax</td>
	<td>Mobile</td>
	<td>Email</td>
	<td>Reason</td>
	</tr>
	<?php
	$sql = "SELECT * from import_errors_contact where import_identifier='" . $record['import_identifier'] . "' and master_id='$master_id'";
	$res2 = executequery($sql);
	while($rec2 = go_fetch_array($res2)){
	 ?>
	 <tr>
	 <td><input type="checkbox" name="ids[]" value="<?=$rec2['id']?>" class="<?=$counter?>"></td>
	 <td><?=$rec2['custom_property_id']?></td>
	 <td><?=$rec2['cust_id']?></td>
	 <td><?=$rec2['firstname']?></td>
	 <td><?=$rec2['lastname']?></td>
	 <td><?=$rec2['position']?></td>
	 <td><?=$rec2['phone']?></td>
	 <td><?=$rec2['fax']?></td>
	 <td><?=$rec2['mobile']?></td>
	 <td><?=$rec2['email']?></td>
	 <td><?=$rec2['reason']?></td>
	 </tr>
	 <?php
   }
   ?>
   </table>
   </div>
   <br>
   <?php
  }
  ?>
  <input type="submit" name="submit1" value="Export Checked">
</div>
</div>
</div>
</form>
	 
	 