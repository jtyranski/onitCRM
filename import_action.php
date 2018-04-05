<?php
include "includes/functions.php";

$submit1 = $_POST['submit1'];
if($submit1 != ""){
  if (is_uploaded_file($_FILES['company']['tmp_name']))
  {
	
	  $ext = explode(".", $_FILES['company']['name']);
  	  $ext = strtolower(array_pop($ext));
  	  $companyfilename = uniqueTimeStamp() . "." . $ext;
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
		$city = chop($items[3]);
		$state = chop($items[4]);
		$zip = chop($items[5]);
		$website = chop($items[6]);
		
		if($cust_id=="cust_id") continue;
		
		$company_name = ucwords(strtolower($company_name));
		$address = ucwords(strtolower($address));
		$city = ucwords(strtolower($city));
		
		$sql = "SELECT prospect_id from prospects where master_id='" . $SESSION_MASTER_ID . "' and address=\"$address\" and city=\"$city\" and state=\"$state\"";
		$test = getsingleresult($sql);
		if($test==""){
		  $sql = "INSERT into prospects(master_id, cust_id, company_name, address, city, state, zip, website, lastaction) values(
		  '" . $SESSION_MASTER_ID . "', \"$cust_id\", \"$company_name\", \"$address\", \"$city\", \"$state\", \"$zip\", \"$website\", now())";
		  executeupdate($sql);
		  //echo $sql . "<br>";
		  $prospect_id = go_insert_id();
		  $site_name = $company_name . " Corporate";
		  $sql = "INSERT into properties(prospect_id, site_name, address, city, state, zip, corporate) values(
		  '$prospect_id', \"$site_name\", \"$address\", \"$city\", \"$state\", \"$zip\", 1)";
		  executeupdate($sql);
		}
	}
	fclose($handle);
  }

  
  if (is_uploaded_file($_FILES['property']['tmp_name']))
  {
	
	  $ext = explode(".", $_FILES['property']['name']);
  	  $ext = strtolower(array_pop($ext));
  	  $propertyfilename = uniqueTimeStamp() . "." . $ext;
	  move_uploaded_file($_FILES['property']['tmp_name'], "uploaded_files/temp/". $propertyfilename);
  }
  
  $handle = @fopen("uploaded_files/temp/" . $propertyfilename, "r");
  if ($handle) {
    while (!feof($handle)) {

		$items = fgetcsv($handle, 4096, ",", "\"");
		
		$cust_id = chop($items[1]);
		$site_name = chop($items[2]);
		$address = chop($items[3]);
		$city = chop($items[4]);
		$state = chop($items[5]);
		$zip = chop($items[6]);
		$roof_size = chop($items[7]);
		
		if($cust_id=="cust_id") continue;
		
		$site_name = ucwords(strtolower($site_name));
		$address = ucwords(strtolower($address));
		$city = ucwords(strtolower($city));
		
		
		
		$sql = "SELECT prospect_id from prospects where cust_id=\"$cust_id\" and master_id='" . $SESSION_MASTER_ID . "' and display=1 order by prospect_id desc limit 1";
		$prospect_id = getsingleresult($sql);
		
		$sql = "SELECT property_id from properties where prospect_id='$prospect_id' and address=\"$address\" and city=\"$city\" and state=\"$state\"";
		$test = getsingleresult($sql);
		if($test==""){
		  $sql = "INSERT into properties(prospect_id, site_name, address, city, state, zip) values(
		  '$prospect_id', \"$site_name\", \"$address\", \"$city\", \"$state\", \"$zip\")";
		  executeupdate($sql);
		}
	}
	fclose($handle);
  }
  
  if($companyfilename != "") @unlink("uploaded_files/temp/" . $companyfilename);
  if($propertyfilename != "") @unlink("uploaded_files/temp/" . $propertyfilename);
  
  $_SESSION['sess_msg'] = "Congratulations! Your company/property information has been successfully imported.";
  meta_redirect("import.php");
}

	
?>