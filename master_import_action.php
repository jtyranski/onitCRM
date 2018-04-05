<?php
include "includes/functions.php";

function CreateOrphanage($master_id){
  $sql = "SELECT address, city, state, zip from master_list where master_id='$master_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $address = go_escape_string(stripslashes($record['address']));
  $city = go_escape_string(stripslashes($record['city']));
  $state = go_escape_string(stripslashes($record['state']));
  $zip = go_escape_string(stripslashes($record['zip']));
  
  $sql = "INSERT into prospects(master_id, company_name, address, city, state, zip, orphan_holder) values(
  '$master_id', 'Lost Child Properties', \"$address\", \"$city\", \"$state\", \"$zip\", 1)";
  executeupdate($sql);
  $prospect_id = go_insert_id();
  
  $sql = "INSERT into properties(prospect_id, site_name, address, city, state, zip, corporate) values(
  '$prospect_id', 'Lost Child Properties Corporate', \"$address\", \"$city\", \"$state\", \"$zip\", 1)";
  executeupdate($sql);
  
  return($prospect_id);
}
  

$master_id = $_POST['master_id'];
$submit1 = $_POST['submit1'];
$identifier = go_escape_string($_POST['identifier']);
$dup_check = $_POST['dup_check'];
if($dup_check != 1) $dup_check = 0;

$orphan = $_POST['orphan'];
if($orphan != 1) $orphan = 0;

$prospects_table = "prospects";
$properties_table = "properties";
$contacts_table = "contacts";

$test_import = $_POST['test_import'];
if($test_import != 1) $test_import = 0;

if($test_import == 1){
  $prospects_table = "importtest_prospects";
  $properties_table = "importtest_properties";
  $contacts_table = "importtest_contacts";
}

$prospects_total = $prospects_good = $prospects_fail = $properties_total = $properties_good = $properties_fail = $contacts_total = $contacts_good = $contacts_fail = 0;

$sql = "SELECT prospect_id from $prospects_table where orphan_holder=1 and master_id='$master_id'";
$orphan_id = getsingleresult($sql);

$sql = "SELECT timezone from master_list where master_id='$master_id'";
$timezone = getsingleresult($sql);
if($timezone=="") $timezone = 0;

if($submit1 != ""){
  if (is_uploaded_file($_FILES['company']['tmp_name']))
  {
	
	  $ext = explode(".", $_FILES['company']['name']);
  	  $ext = strtolower(array_pop($ext));
  	  $companyfilename = uniqueTimeStamp() . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . "." . $ext;
	  move_uploaded_file($_FILES['company']['tmp_name'], "uploaded_files/temp/". $companyfilename);
  }
  
  $handle = @fopen("uploaded_files/temp/" . $companyfilename, "r");
  if ($handle) {
    while (!feof($handle)) {
	    /*
        $buffer = fgets($handle, 4096);
        $items = explode(",", $buffer);
		*/
		$items = fgetcsv($handle, 4096, ",", "\"");
		
		
		$cust_id = chop($items[0]);
		$company_name = chop($items[1]);
		$address = chop($items[2]);
		$address2 = chop($items[3]);
		$city = chop($items[4]);
		$state = chop($items[5]);
		$zip = chop($items[6]);
		$website = chop($items[7]);
		$extra1 = chop($items[8]);
		$billto_address = chop($items[9]);
		$billto_address2 = chop($items[10]);
		$billto_city = chop($items[11]);
		$billto_state = chop($items[12]);
		$billto_zip = chop($items[13]);
		$billto_full = "";
		if($billto_address != ""){
		  $billto_full = $billto_address . "\n";
		  if($billto_address2 != "") $billto_full .= $billto_address2 . "\n";
		  $billto_full .= $billto_city . ", " . $billto_state . " " . $billto_zip;
		}
		
		if($cust_id=="cust_id") continue;
		if($company_name=="") continue;
		
		$company_name = ucwords(strtolower($company_name));
		$address = ucwords(strtolower($address));
		$city = ucwords(strtolower($city));
		
		$company_name = go_escape_string($company_name);
		$address = go_escape_string($address);
		$city = go_escape_string($city);
		$state = go_escape_string($state);
		$extra1 = go_escape_string($extra1);
		
		$prospects_total++;
		$sql = "SELECT prospect_id from prospects where master_id='" . $master_id . "' and address=\"$address\" and city=\"$city\" and state=\"$state\" and display=1";
		$test = getsingleresult($sql);
		if($dup_check==0) $test = "";
		if($test==""){
		  $prospects_good++;
		  $sql = "INSERT into $prospects_table(master_id, cust_id, company_name, address, address2, city, state, zip, website, lastaction, identifier, extra_field1, billto_address) values(
		  '" . $master_id . "', \"$cust_id\", \"$company_name\", \"$address\", \"$address2\", \"$city\", \"$state\", \"$zip\", \"$website\", now(), \"$identifier\", \"$extra1\", \"$billto_address\")";
		  executeupdate($sql);
		  //echo $sql . "<br>";
		  $prospect_id = go_insert_id();
		  $site_name = $company_name . " Corporate";
		  $sql = "INSERT into $properties_table(prospect_id, site_name, address, address2, city, state, zip, corporate, timezone) values(
		  '$prospect_id', \"$site_name\", \"$address\", \"$address2\", \"$city\", \"$state\", \"$zip\", 1, '$timezone')";
		  executeupdate($sql);
		  if($test_import==0 && $extra1 != ""){
		    $sql = "INSERT into notes(prospect_id, date, event, note, regarding) values('$prospect_id', now(), 'Note', \"Import field: $extra1\", 'Import')";
			executeupdate($sql);
		  }
		}
		else {
		  $prospects_fail++;
		}
	}
	fclose($handle);
  }

  
  if (is_uploaded_file($_FILES['property']['tmp_name']))
  {
	
	  $ext = explode(".", $_FILES['property']['name']);
  	  $ext = strtolower(array_pop($ext));
  	  $propertyfilename = uniqueTimeStamp() . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . "." . $ext;
	  move_uploaded_file($_FILES['property']['tmp_name'], "uploaded_files/temp/". $propertyfilename);
  }
  
  $handle = @fopen("uploaded_files/temp/" . $propertyfilename, "r");
  if ($handle) {
    while (!feof($handle)) {

		$items = fgetcsv($handle, 4096, ",", "\"");
		
		$cust_id = chop($items[1]);
		$site_name = chop($items[2]);
		$address = chop($items[3]);
		$address2 = chop($items[4]);
		$city = chop($items[5]);
		$state = chop($items[6]);
		$zip = chop($items[7]);
		$roof_size = chop($items[8]);
		$custom_property_id = chop($items[0]);
		$extra1 = chop($items[9]);
		$billto_address = chop($items[10]);
		$billto_address2 = chop($items[11]);
		$billto_city = chop($items[12]);
		$billto_state = chop($items[13]);
		$billto_zip = chop($items[14]);
		$billto_full = "";
		if($billto_address != ""){
		  $billto_full = $billto_address . "\n";
		  if($billto_address2 != "") $billto_full .= $billto_address2 . "\n";
		  $billto_full .= $billto_city . ", " . $billto_state . " " . $billto_zip;
		}
		
		if($cust_id=="cust_id") continue;
		if($site_name=="") continue;
		
		$site_name = ucwords(strtolower($site_name));
		$address = ucwords(strtolower($address));
		$city = ucwords(strtolower($city));
		
		$site_name = go_escape_string($site_name);
		$address = go_escape_string($address);
		$city = go_escape_string($city);
		$state = go_escape_string($state);
		$extra1 = go_escape_string($extra1);
		
		$properties_total++;
		$sql = "SELECT prospect_id from $prospects_table where cust_id=\"$cust_id\" and master_id='" . $master_id . "' and display=1 order by prospect_id desc limit 1";
		$prospect_id = getsingleresult($sql);
		
		if($prospect_id != ""){
		  $sql = "SELECT property_id from $properties_table where prospect_id='$prospect_id' and address=\"$address\" and city=\"$city\" and state=\"$state\" and display=1";
		  $test = getsingleresult($sql);
		  if($dup_check==0) $test = "";
		  if($test==""){
		    $properties_good++;
		    $sql = "INSERT into $properties_table(prospect_id, site_name, address, address2, city, state, zip, custom_id, roof_size, extra_field1, timezone, billto_address) values(
		    '$prospect_id', \"$site_name\", \"$address\", \"$address2\", \"$city\", \"$state\", \"$zip\", \"$custom_property_id\", \"$roof_size\", \"$extra1\", '$timezone', \"$billto_address\")";
		    executeupdate($sql);
			$property_id = go_insert_id();
			if($test_import==0 && $extra1 != ""){
		      $sql = "INSERT into notes(property_id, prospect_id, date, event, note, regarding) values('$property_id', '$prospect_id', now(), 'Note', \"Import field: $extra1\", 'Import')";
			  executeupdate($sql);
		    }
		  }
		  else {
		    $properties_fail++;
		    $sql = "INSERT into import_errors_property(master_id, import_identifier, reason, cust_id, custom_property_id, address, city, state, zip, roof_size, site_name, test) values(
			'$master_id', \"$identifier\", 'Duplicate', \"$cust_id\", \"$custom_property_id\", \"$address\", \"$city\", \"$state\", \"$zip\", \"$roof_size\", \"$site_name\", '$test_import')";
			executeupdate($sql);
		  }
		}
		else {
		    $properties_fail++;
		    $sql = "INSERT into import_errors_property(master_id, import_identifier, reason, cust_id, custom_property_id, address, city, state, zip, roof_size, site_name, test) values(
			'$master_id', \"$identifier\", 'No Matching Company', \"$cust_id\", \"$custom_property_id\", \"$address\", \"$city\", \"$state\", \"$zip\", \"$roof_size\", \"$site_name\", '$test_import')";
			executeupdate($sql);
			
			if($orphan==1){
			  if($orphan_id=="") $orphan_id = CreateOrphanage($master_id);
			  $sql = "INSERT into $properties_table(prospect_id, site_name, address, address2, city, state, zip, custom_id, roof_size, extra_field1, timezone, billto_address) values(
		      '$orphan_id', \"$site_name\", \"$address\", \"$address2\", \"$city\", \"$state\", \"$zip\", \"$custom_property_id\", \"$roof_size\", \"$extra1\", '$timezone', \"$billto_address\")";
		      executeupdate($sql);
			}
			  
		}
	}
	fclose($handle);
  }
  
  if (is_uploaded_file($_FILES['contacts']['tmp_name']))
  {
	
	  $ext = explode(".", $_FILES['contacts']['name']);
  	  $ext = strtolower(array_pop($ext));
  	  $contactsfilename = uniqueTimeStamp() . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . "." . $ext;
	  move_uploaded_file($_FILES['contacts']['tmp_name'], "uploaded_files/temp/". $contactsfilename);
  }
  
  $handle = @fopen("uploaded_files/temp/" . $contactsfilename, "r");
  if ($handle) {
    while (!feof($handle)) {

		$items = fgetcsv($handle, 4096, ",", "\"");
		
		$cust_id = chop($items[0]);
		$cust_property_id = chop($items[1]);
		$firstname = chop($items[2]);
		$lastname = chop($items[3]);
		$position = chop($items[4]);
		$phone = chop($items[5]);
		$fax = chop($items[6]);
		$mobile = chop($items[7]);
		$email = chop($items[8]);
		
		if($phone=="phone") continue;
		
		$firstname = go_escape_string($firstname);
		$lastname = go_escape_string($lastname);
		$position = go_escape_string($position);
		
	    if($cust_id != "" && $cust_id != "0"){
		  $contacts_total++;
		  $sql = "SELECT prospect_id from $prospects_table where cust_id=\"$cust_id\" and master_id='" . $master_id . "' and display=1 order by prospect_id desc limit 1";
		  $prospect_id = getsingleresult($sql);
		
		  if($prospect_id != ""){
		    $sql = "SELECT id from $contacts_table where prospect_id='$prospect_id' and firstname=\"$firstname\" and lastname=\"$lastname\" and email=\"$email\" and phone=\"$phone\"";
		    $test = getsingleresult($sql);
		    if($test==""){
			  $contacts_good++;
		      $sql = "INSERT into $contacts_table(master_id, prospect_id, firstname, lastname, position, phone, fax, mobile, email) values(
		      '$master_id', '$prospect_id', \"$firstname\", \"$lastname\", \"$position\", \"$phone\", \"$fax\", \"$mobile\", \"$email\")";
		      executeupdate($sql);
		    }
			else { //duplicate
			  $contacts_fail++;
			  $sql = "INSERT into import_errors_contact(master_id, import_identifier, reason, cust_id, custom_property_id, firstname, lastname, position, phone, fax, mobile, email, test) values(
			  '$master_id', \"$identifier\", 'Duplicate', \"$cust_id\", \"$cust_property_id\", \"$firstname\", \"$lastname\", \"$position\", \"$phone\", \"$fax\", \"$mobile\", \"$email\", '$test_import')";
			  executeupdate($sql);
			}
		  }
		  else { // no matching prospect id
		    $contacts_fail++;
		    $sql = "INSERT into import_errors_contact(master_id, import_identifier, reason, cust_id, custom_property_id, firstname, lastname, position, phone, fax, mobile, email, test) values(
			'$master_id', \"$identifier\", 'No Matching Prospect ID', \"$cust_id\", \"$cust_property_id\", \"$firstname\", \"$lastname\", \"$position\", \"$phone\", \"$fax\", \"$mobile\", \"$email\", '$test_import')";
			executeupdate($sql);
		  }
		} // end if a custom prospect id is detected
		
		
		if($cust_property_id != "" && $cust_property_id != "0"){
		  $contacts_total++;
		  $sql = "SELECT a.property_id from $properties_table a, $prospects_table b where a.prospect_id=b.prospect_id and a.custom_id=\"$cust_property_id\" and b.master_id='" . $master_id . "' and a.display=1 order by a.property_id desc limit 1";
		  $property_id = getsingleresult($sql);
		
		  if($property_id != ""){
		    $sql = "SELECT id from $contacts_table where property_id='$property_id' and firstname=\"$firstname\" and lastname=\"$lastname\" and email=\"$email\" and phone=\"$phone\"";
		    $test = getsingleresult($sql);
		    if($test==""){
			  $contacts_good++;
		      $sql = "INSERT into $contacts_table(master_id, property_id, firstname, lastname, position, phone, fax, mobile, email) values(
		      '$master_id', '$property_id', \"$firstname\", \"$lastname\", \"$position\", \"$phone\", \"$fax\", \"$mobile\", \"$email\")";
		      executeupdate($sql);
		    }
			else { //duplicate
			  $contacts_fail++;
			  $sql = "INSERT into import_errors_contact(master_id, import_identifier, reason, cust_id, custom_property_id, firstname, lastname, position, phone, fax, mobile, email, test) values(
			  '$master_id', \"$identifier\", 'Duplicate', \"$cust_id\", \"$cust_property_id\", \"$firstname\", \"$lastname\", \"$position\", \"$phone\", \"$fax\", \"$mobile\", \"$email\", '$test_import')";
			  executeupdate($sql);
			}
		  }
		  else { // no matching prospect id
		    $contacts_fail++;
		    $sql = "INSERT into import_errors_contact(master_id, import_identifier, reason, cust_id, custom_property_id, firstname, lastname, position, phone, fax, mobile, email, test) values(
			'$master_id', \"$identifier\", 'No Matching Property ID', \"$cust_id\", \"$cust_property_id\", \"$firstname\", \"$lastname\", \"$position\", \"$phone\", \"$fax\", \"$mobile\", \"$email\", '$test_import')";
			executeupdate($sql);
		  }
		} // end if a custom property id is detected
		
		
	}
	fclose($handle);
  }
  
  $sql = "INSERT into import_log(import_identifier, master_id, ts, prospects_file, properties_file, contacts_file, prospects_total, prospects_good, properties_total, properties_good, contacts_total, 
  contacts_good, test, prospects_fail, properties_fail, contacts_fail) values(
  \"$identifier\", '$master_id', now(), \"$companyfilename\", \"$propertyfilename\", \"$contactsfilename\", '$prospects_total', '$prospects_good', '$properties_total', '$properties_good', '$contacts_total', 
  '$contacts_good', '$test_import', '$prospects_fail', '$properties_fail', '$contacts_fail')";
  executeupdate($sql);
  
  if($companyfilename != ""){
    @copy("uploaded_files/temp/" . $companyfilename, "uploaded_files/import_logs/" . $companyfilename);
    @unlink("uploaded_files/temp/" . $companyfilename);
  }
  if($propertyfilename != ""){
    @copy("uploaded_files/temp/" . $propertyfilename, "uploaded_files/import_logs/" . $propertyfilename);
    @unlink("uploaded_files/temp/" . $propertyfilename);
  }
  if($contactsfilename != ""){
    @copy("uploaded_files/temp/" . $contactsfilename, "uploaded_files/import_logs/" . $contactsfilename);
    @unlink("uploaded_files/temp/" . $contactsfilename);
  }
  
  if($test_import==1){
    $sql = "TRUNCATE table importtest_prospects";
	executeupdate($sql);
	$sql = "TRUNCATE table importtest_properties";
	executeupdate($sql);
	$sql = "TRUNCATE table importtest_contacts";
	executeupdate($sql);
  }
	
  
  $sql = "SELECT prospect_id from prospects where master_id='$master_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $prospect_id = $record['prospect_id'];
	$sql = "SELECT count(*) from properties where prospect_id='$prospect_id' and corporate=0 and display=1";
	$properties = getsingleresult($sql);
	$sql = "UPDATE prospects set properties='$properties' where prospect_id='$prospect_id'";
	executeupdate($sql);
  }
  
  $sql = "SELECT count(property_id) from prospects a, properties b where a.prospect_id=b.prospect_id and a.display=1 and b.display=1
  and b.corporate=0 and a.master_id='$master_id'";
  $properties = getsingleresult($sql);
  $sql = "UPDATE master_list set properties='$properties' where master_id='$master_id'";
  executeupdate($sql);
  
  
  $_SESSION['sess_msg'] = "Congratulations! Your company/property information has been successfully imported.";
  meta_redirect("master_import.php?master_id=$master_id");
}

	
?>