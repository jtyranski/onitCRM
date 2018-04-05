<?php 
//include "resize.php";
//ini_set("session.gc_maxlifetime", "18000"); 
ini_set("session.cookie_lifetime", "18000"); 
session_start();
//ini_set("session.gc_maxlifetime", "18000"); 
ini_set("session.cookie_lifetime", "18000"); 
//error_reporting(E_ALL);
global $DB;
$QUERY_STRING = $_SERVER['QUERY_STRING'];
$SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
//$uplink = "../";

include "../includes/variables.php";

$filename_array = explode("/", $_SERVER['SCRIPT_NAME']);
$current_file_name = array_pop($filename_array);



if($current_file_name != "login.php" && $current_file_name != "login_action.php" && $current_file_name != "testdiv.php"){
  if($SESSION_USER_ID==""){
    meta_redirect("login.php");
  }
}

// for the prospecting pages.  Jim will have different fields than everyone else
$onlyjim = array("12");

$ALT_ROW_COLOR="#000000";
//$ALT_ROW_COLOR="#222222";
$ALT_ROW_COLOR2="#222222";
$LOGO_SIZE = 150;
$PROPERTY_SIZE = 250;
$BUILDING_PHOTO_SIZE = 440;
$SECTION_PHOTO_SIZE = 640;
$REPORT_PHOTO_SIZE = 640;
$CONTRACTOR_LOGO_SIZE = 150;
$CONTRACTOR_PHOTO_SIZE = 250;
$RESOURCE_FILTER = " resource = 0 ";
$UPLOAD = "../uploaded_files/";


$gradevalue['0'] = 0;
$gradevalue['A'] = 5;
$gradevalue['B'] = 4;
$gradevalue['C'] = 3;
$gradevalue['D'] = 2;
$gradevalue['F'] = 1;
$gradevalue_reverse[1] = 'F';
$gradevalue_reverse[2] = 'D';
$gradevalue_reverse[3] = 'C';
$gradevalue_reverse[4] = 'B';
$gradevalue_reverse[5] = 'A';

$monthname_full[1] = "January";
$monthname_full[2] = "February";
$monthname_full[3] = "March";
$monthname_full[4] = "April";
$monthname_full[5] = "May";
$monthname_full[6] = "June";
$monthname_full[7] = "July";
$monthname_full[8] = "August";
$monthname_full[9] = "September";
$monthname_full[10] = "October";
$monthname_full[11] = "November";
$monthname_full[12] = "December";

$monthname[1] = "JAN";
$monthname[2] = "FEB";
$monthname[3] = "MAR";
$monthname[4] = "APR";
$monthname[5] = "MAY";
$monthname[6] = "JUN";
$monthname[7] = "JUL";
$monthname[8] = "AUG";
$monthname[9] = "SEP";
$monthname[10] = "OCT";
$monthname[11] = "NOV";
$monthname[12] = "DEC";

$I = "Inspection";
$BP = "Bid Presentation";
$RRP = "Roof Report Presentation";
$UM = "Intro Meeting";
$PCO = "Project Close-Out";

$link = mysql_connect($DB["host"], $DB["user"], $DB["pass"]) or die("<span style='FONT-SIZE:11px; FONT-COLOR: #000000; font-family=tahoma;'><center>An Internal Error has Occured. Please report following error to the webmaster.<br><br>".mysql_error()."'</center>");
mysql_select_db($DB["dbName"]);

function executeQuery($sql)
{
	$result = mysql_query($sql) or die("<span style='FONT-SIZE:11px; FONT-COLOR: #000000; font-family=tahoma;'><center>An Internal Error has Occured. Please report following error to the webmaster.<br><br>".$sql."<br><br>".mysql_error()."'</center></FONT>");
	return $result;
} 

function getSingleResult($sql)
{
	$response = "";
	$result = mysql_query($sql) or die("<center>An Internal Error has Occured. Please report following error to the webmaster.<br><br>".$sql."<br><br>".mysql_error()."'</center>");
	if ($line = go_fetch_array($result)) {
		$response = $line[0];
	} 
	return $response;
} 

function executeUpdate($sql)
{
	mysql_query($sql) or die("<center>An Internal Error has Occured. Please report following error to the webmaster.<br><br>".$sql."<br><br>".mysql_error()."'</center>");
}

// file functions
function checkfile($str)
{
	global $path;  // make path variable global
	$path = "/home/mxr/public_html/uploaded_files/";  // set initial path variable

	// change to lower case, take out whitespace, replace spaces with underscores, trim to 20 char
	$str = trim($str);
	$str = strtolower(str_replace(" ", "_", $str));
	$str = substr($str, 0, 20);
	
	// checks for any of characters in $pattern and removes them
	$pattern = "//";
	
	$path = $path . $str;  // add the filename to the end of the path

	// renames the new path directory if it exists already
	if(is_dir($path))
	{
		$counter = 1;
		while(is_dir($path . $counter))  // while the path and counter exist, keep increasing the counter
			{
			$counter = $counter + 1;
			}
		$path = $path . $counter;  // append the counter variable to the path
	}
		
	if(!is_dir($path))
	{
			//mkdir($path);
			//chmod($path, 0777);  // write permissions set
			return $path;	// returns path
	}

}

// if path directory doesn't exist, it will be created here
function create_dir($path)
{
	$path = "uploaded_files/". $path;
	if(!is_dir($path))
	{
		mkdir($path);
		chmod($path, 0777);  // write permissions set
	}
}

// if path directory doesn't exist, it will be created here
function delete_file($filename)
{
	$path = "/home/mxr/public_html/";  // set initial path variable
	$filename = $path . $filename;
	if(file_exists($filename))
	{
		unlink($filename);
	}

}

function piclist($dir)
{
	// set counter
	$count = 0;
	
	// open directory and parse list
	if(is_dir($dir))
	{
		if($dh = opendir($dir))
		{
			// print filenames
			while(($filename = readdir($dh)) !== false)
			{
				if(($filename != ".") && ($filename != "..") && $filename != "building.jpg")
				{
					$files[] = $filename;
					$count++;
				}
			}
			
			// close directory
			closedir($dh);
		}
	}
	
	return $files;
}

function meta_redirect($url){
  ?>
  <meta http-equiv="refresh" content="0;url=<?=$url?>">
  <?php
  exit();
}

function deleteDir($dir)
{
   if (substr($dir, strlen($dir)-1, 1) != '/')
       $dir .= '/';

   //echo $dir;

   if ($handle = opendir($dir))
   {
       while ($obj = readdir($handle))
       {
           if ($obj != '.' && $obj != '..')
           {
               if (is_dir($dir.$obj))
               {
                   if (!deleteDir($dir.$obj))
                       return false;
               }
               elseif (is_file($dir.$obj))
               {
                   if (!unlink($dir.$obj))
                       return false;
               }
           }
       }

       closedir($handle);

       if (!@rmdir($dir))
           return false;
       return true;
   }
   return false;
}

function is_image_valid($file_name)
{
	$pos = strrpos($file_name, ".");
	$len = strlen($file_name);
	$ext = substr($file_name , $pos + 1, $len);
	$ext = strtolower($ext);
	if ($ext == "gif" || $ext == "jpg" || $ext == "jpeg" || $ext == "png") {
		return true;
	} else {
		return false;
	} 
}

function is_video_valid($file_name)
{
	$pos = strrpos($file_name, ".");
	$len = strlen($file_name);
	$ext = substr($file_name , $pos + 1, $len);
	$ext = strtolower($ext);
	if ($ext == "mpg" || $ext == "mpeg" || $ext == "asf" || $ext == "avi" || $ext == "wmv" || $ext == "mov" || $ext == "mod") {
		return true;
	} else {
		return false;
	} 
}

function resizeimage($source, $dest_dir, $maxwidth){
  if(!go_reg("/$", $dest_dir)) $dest_dir .= "/";
  if(is_image_valid($source)){
    // Get new dimensions
    list($width, $height) = getimagesize($source);
  
    if($width > $maxwidth){
      $new_width = $maxwidth;
      $ratio = $new_width/$width;
      $new_height = $height * $ratio;
    }
    else {
      $new_width = $width;
  	  $new_height = $height;
    }
	
	$pos = strrpos($source, ".");
	$len = strlen($source);
	$ext = substr($source , $pos + 1, $len);
	$ext = strtolower($ext);
	
	$file_array = explode("/", $source);
	$sourcefilename = array_pop($file_array);
	$dest_filename = $dest_dir . $sourcefilename;

    // Resample
    $image_p = imagecreatetruecolor($new_width, $new_height);
	
	switch(true){
	  case ($ext == "gif"):{
        $image = imagecreatefromgif($source);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagegif($image_p, $dest_filename, 100);
		break;
	  }
	  case ($ext=="jpg" || $ext=="jpeg"):{
        $image = imagecreatefromjpeg($source);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($image_p, $dest_filename, 100);
		break;
	  }
	  case ($ext == "png"):{
        $image = imagecreatefrompng($source);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($image_p, $dest_filename, 100);
		break;
	  }
	}
	  
	return true;
  }
  else {
    return false;
  }
}

function get_qry_str($over_write_key = array(), $over_write_value= array())
{
	global $_GET;
	$m = $_GET;
	if(is_array($over_write_key)){
		$i=0;
		foreach($over_write_key as $key){
			$m[$key] = $over_write_value[$i];
			$i++;
		}
	}else{
		$m[$over_write_key] = $over_write_value;
	}
	$qry_str = qry_str($m);
	return $qry_str;
} 

function qry_str($arr, $skip = 'start')
{
	$s = "?";
	$i = 0;
	foreach($arr as $key => $value) {
		if ($key != $skip) {
			if ($i == 0) {
				$s .= "$key=$value";
				$i = 1;
			} else {
				$s .= "&$key=$value";
			} 
		} 
	} 

	return $s;
} 

function sort_arrows($column){
	global $_SERVER;
	return '<A HREF="'.$_SERVER['PHP_SELF'].get_qry_str(array('order_by','order_by2'), array($column,'asc')).'"><IMG SRC="images/up_arrow.gif" BORDER="0" alt="Sort Ascending"></A><A HREF="'.$_SERVER['PHP_SELF'].get_qry_str(array('order_by','order_by2'), array($column,'desc')).'"><IMG SRC="images/down_arrow.gif" BORDER="0" alt="Sort Descending"></A>';
}

function sponsor_sort_arrows($column){
	global $_SERVER;
	return '<A HREF="'.$_SERVER['PHP_SELF'].get_qry_str(array('order_by','order_by2'), array($column,'asc')).'"><IMG SRC="images/sp_up_arrow.jpg" BORDER="0" alt="Sort Ascending" align="absmiddle"></A><A HREF="'.$_SERVER['PHP_SELF'].get_qry_str(array('order_by','order_by2'), array($column,'desc')).'"><IMG SRC="images/sp_down_arrow.jpg" BORDER="0" alt="Sort Descending" align="absmiddle"></A>';
}

function uniqueTimeStamp() {
  $milliseconds = microtime();
  $timestring = explode(" ", $milliseconds);
  $sg = $timestring[1];
  $mlsg = substr($timestring[0], 2, 4);
  $timestamp = $sg.$mlsg;
  return $timestamp; 
}

function GetContacts($text, $prospect_id, $has_email=0){

  $listArray = array();
  $i=0;
  
  if($prospect_id=="" || $prospect_id=="0") return;

  if($has_email){
    $email_clause = " email != '' ";
  }
  else {
    $email_clause = " 1=1 ";
  }
  
  $strSQL = "SELECT concat(firstname, ' ', lastname) as fullname, email FROM contacts WHERE concat(firstname, ' ', lastname) LIKE '$text%' and prospect_id='$prospect_id' and $email_clause";
  $result = executequery($strSQL);
  while($record = go_fetch_array($result))
  {
  $artist_name = stripslashes($record['fullname']);
  if($artist_name != " "){
    $listArray[$i]['name'] = $artist_name;
	$listArray[$i]['email'] = stripslashes($record['email']);
	//echo $artist_name . " " . stripslashes($record['email']) . "<br>";
    $i++;
  }
  }

  $sql = "SELECT property_id from properties where prospect_id ='$prospect_id' and corporate=0";
  $result = executequery($sql);
  while($record = go_fetch_array($result))
  {
    $property_id=$record['property_id'];
    $sql2 = "SELECT concat(firstname, ' ', lastname) as fullname, email FROM contacts WHERE concat(firstname, ' ', lastname) LIKE '$text%' and property_id='$property_id' and $email_clause";
    $result2 = executequery($sql2);
    while($record2 = go_fetch_array($result2))
    {
      $artist_name = stripslashes($record2['fullname']);
      if($artist_name != " "){
        $listArray[$i]['name'] = $artist_name;
		$listArray[$i]['email'] = stripslashes($record2['email']);
		//echo $artist_name . " " . stripslashes($record2['email']) . "<br>";
        $i++;
      }
    }
  }



asort( $listArray );

return $listArray;

}


function PropertyEmails($property_id){
  $listArray = array();
  
  $sql = "SELECT email from contacts where property_id='$property_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $email = $record['email'];
	if($email != "") $listArray[] = $email;
  }
  
  return $listArray;
}

function ProspectEmails($prospect_id){
  $listArray = array();
  
  $sql = "SELECT email from contacts where prospect_id='$prospect_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $email = $record['email'];
	if($email != "") $listArray[] = $email;
  }
  
  return $listArray;
}

function EverybodyEmail(){
  $listArray = array();
 
  $sql = "SELECT email from contacts where email != '' group by email";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $email = $record['email'];
	if($email != "") $listArray[] = $email;
  }
  
  return array_unique($listArray);
}

function GetStatus($text){

$listArray = array();
$i=0;

$strSQL = "SELECT id_status FROM properties_beazer WHERE id_status LIKE '$text%' group by id_status";
$result = executequery($strSQL);
while($record = go_fetch_array($result))
{
$id_status = stripslashes($record['id_status']);
if($id_status != ""){
  $listArray[$i] = $id_status;
  $i++;
}
}


asort( $listArray );

return $listArray;

}

function getContactInfo($name, $prospect_id, $property_id){
  $array['phone'] = "";
  $array['mobile'] = "";
  $array['email'] = "";

  
  $sql = "SELECT email, phone, mobile from contacts where prospect_id='$prospect_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $phone = stripslashes($record['phone']);
	$mobile = stripslashes($record['mobile']);
	$email = stripslashes($record['email']);
	if($phone != "") $array['phone'] = $phone;
	if($mobile != "") $array['mobile'] = $mobile;
	if($email != "") $array['email'] = $email;
  }
  
  $sql = "SELECT email, phone, mobile from contacts where property_id='$property_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $phone = stripslashes($record['phone']);
	$mobile = stripslashes($record['mobile']);
	$email = stripslashes($record['email']);
	if($phone != "") $array['phone'] = $phone;
	if($mobile != "") $array['mobile'] = $mobile;
	if($email != "") $array['email'] = $email;
  }
  
  return $array;
}

function great_circle_distance($lat1,$lat2,$lon1,$lon2)
	{
		/* Convert all the degrees to radians */
		$lat1 = deg_to_rad($lat1);
		$lon1 = deg_to_rad($lon1);
		$lat2 = deg_to_rad($lat2);
		$lon2 = deg_to_rad($lon2);

		/* Find the deltas */
		$delta_lat = $lat2 - $lat1;
		$delta_lon = $lon2 - $lon1;
	
		/* Find the Great Circle distance */
		$temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
		
		$EARTH_RADIUS = 3956;

		$distance = $EARTH_RADIUS * 2 * atan2(sqrt($temp),sqrt(1-$temp));
		
		return $distance;
	}

function deg_to_rad($deg)
	{
		$radians = 0.0;	
		$radians = $deg * M_PI/180.0;
		return($radians);
	}

function remove_non_numeric($string) {

return preg_replace('/\D/', '', $string);

}

$welcome_user_id = $SESSION_USER_ID;
$sql = "SELECT firstname, concat(firstname, ' ', lastname) as fullname, photo, master_id from users where user_id='$welcome_user_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$firstname_welcome = stripslashes($record['firstname']);
$fullname_welcome = stripslashes($record['fullname']);
$master_id = $record['master_id'];

if($_GET['prospect_id'] != "") $test_prospect_id = $_GET['prospect_id'];
if($_POST['prospect_id'] != "") $test_prospect_id = $_POST['prospect_id'];

if($_GET['property_id'] != "") $test_property_id = $_GET['property_id'];
if($_POST['property_id'] != "") $test_property_id = $_POST['property_id'];

if($_GET['section_id'] != "") $test_section_id = $_GET['section_id'];
if($_POST['section_id'] != "") $test_section_id = $_POST['section_id'];

if($_GET['leak_id'] != "") $test_leak_id = $_GET['leak_id'];
if($_POST['leak_id'] != "") $test_leak_id = $_POST['leak_id'];

if($current_file_name != "iphone_upload.php"){
if($test_prospect_id != "" && $test_prospect_id != "new" && $test_prospect_id != 0 && !(is_array($test_prospect_id))){
  $sql = "SELECT master_id from prospects where prospect_id='$test_prospect_id'";
  $test_master_id = getsingleresult($sql);
  if($test_master_id != $master_id) meta_redirect("../logout.php");
}

if($test_property_id != "" && $test_property_id != "new" && $test_property_id != 0 && !(is_array($test_property_id))){
  $sql = "SELECT a.master_id from prospects a, properties b where b.property_id='$test_property_id' and a.prospect_id=b.prospect_id";
  $test_master_id = getsingleresult($sql);
  if($test_master_id != $master_id) meta_redirect("../logout.php");
}

if($test_section_id != "" && $test_section_id != "new" && $test_section_id != 0 && !(is_array($test_section_id))){
  $sql = "SELECT a.master_id from prospects a, properties b, sections c where c.section_id='$test_section_id' and a.prospect_id=b.prospect_id and b.property_id=c.property_id";
  $test_master_id = getsingleresult($sql);
  if($test_master_id != $master_id) meta_redirect("../logout.php");
}

if($test_leak_id != "" && $test_leak_id != "new" && $test_leak_id != 0 && !(is_array($test_leak_id))){
  $sql = "SELECT a.master_id from prospects a, am_leakcheck b where a.prospect_id=b.prospect_id and b.leak_id='$test_leak_id'";
  $test_master_id = getsingleresult($sql);
  if($test_master_id != $master_id) meta_redirect("../logout.php");
}
}
//include "../includes/functions_irepsecurity.php";
?>