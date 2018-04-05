<?php
include "includes/functions.php";

$prospect_id = go_escape_string($_POST['prospect_id']);
$property_id = go_escape_string($_POST['property_id']);
$prospect_result = go_escape_string($_POST['prospect_result']);
$opp_user_id = go_escape_string($_POST['opp_user_id']);
$hide = go_escape_string($_POST['hide']);
if($hide != 1) $hide=0;


$notes = go_escape_string($_POST['notes']);
$submit1 = go_escape_string($_POST['submit1']);


$prospecting_rating = go_escape_string($_POST['prospecting_rating']);
$prospecting_roof = go_escape_string($_POST['prospecting_roof']);
$prospecting_start_month = go_escape_string($_POST['prospecting_start_month']);
$prospecting_start_year = go_escape_string($_POST['prospecting_start_year']);
$prospecting_funded = go_escape_string($_POST['prospecting_funded']);
$prospecting_squares = go_escape_string($_POST['prospecting_squares']);
$prospecting_procurement = go_escape_string($_POST['prospecting_procurement']);
$prospecting_product = go_escape_string($_POST['prospecting_product']);
if($prospect_result != "Candidate") $prospecting_product = "";
$prospecting_start = $prospecting_start_year . "-" . $prospecting_start_month . "-01";

$newproperty_name = go_escape_string($_POST['newproperty_name']);
$newproperty_address = go_escape_string($_POST['newproperty_address']);
$newproperty_city = go_escape_string($_POST['newproperty_city']);
$newproperty_state = go_escape_string($_POST['newproperty_state']);
$newproperty_zip = go_escape_string($_POST['newproperty_zip']);
$newproperty_firstname = go_escape_string($_POST['newproperty_firstname']);
$newproperty_lastname = go_escape_string($_POST['newproperty_lastname']);
$newproperty_phone = go_escape_string($_POST['newproperty_phone']);


$redirect = "contacts";

if($submit1 != ""){
  $sql = "UPDATE prospects set prospect_hidden='$hide' where prospect_id='$prospect_id'";
executeupdate($sql);

  if($prospect_result=="Candidate" && $property_id != "*"){
    $sql = "SELECT corporate from properties where property_id='$property_id'";
	$test = getsingleresult($sql);
	if($test==1){
	  $_SESSION['sess_msg'] = "You cannot select a corporate property when processing to a Candidate.  Please select or create a property.";
	  meta_redirect("prospecting.php?prospect_id=$prospect_id");
	}
  }
  
  if($notes != ""){
    $sql = "UPDATE prospects set last_action_notes = \"$notes\" where prospect_id='$prospect_id'";
	executeupdate($sql);
  }
  
  if($property_id=="*"){
    $sql = "INSERT into properties(prospect_id, site_name, address, city, state, zip, property_type) values('$prospect_id', 
	\"$newproperty_name\", \"$newproperty_address\", \"$newproperty_city\", \"$newproperty_state\", \"$newproperty_zip\", 
	'Non-PFRI')";
	executeupdate($sql);
	$sql = "SELECT property_id from properties where site_name=\"$newproperty_name\" and prospect_id='$prospect_id' order by 
	property_id desc limit 1";
	$property_id = getsingleresult($sql);
	$sql = "INSERT into properties_nonpfri(property_id) values('$property_id')";
	executeupdate($sql);
	
	$sql = "INSERT into contacts(property_id, firstname, lastname, phone) values('$property_id', \"$newproperty_firstname\", 
	\"$newproperty_lastname\", \"$newproperty_phone\")";
	executeupdate($sql);
	$sql = "INSERT into contacts(prospect_id, firstname, lastname, phone) values('$prospect_id', \"$newproperty_firstname\", 
	\"$newproperty_lastname\", \"$newproperty_phone\")";
	executeupdate($sql);
	
	
	if (is_uploaded_file($_FILES['image']['tmp_name']))
      {
	  if(is_image_valid($_FILES['image']['name'])){
	
	    $ext = explode(".", $_FILES['image']['name']);
  	    $ext = array_pop($ext);
  	    $filename = uniqueTimeStamp() . "." . $ext;
	    move_uploaded_file($_FILES['image']['tmp_name'], $UPLOAD . "temp/". $filename);
  	    resizeimage($UPLOAD . "temp/$filename", $UPLOAD . "properties/", $PROPERTY_SIZE);
  	    @unlink($UPLOAD . "temp/". $filename);
	
	    $sql = "UPDATE properties set image='$filename' where property_id='$property_id'";
	    executeupdate($sql);
	  }
    }
  }
  

  // Jim says the stats should refelect who marked complete while logged on. 2/25/10 JW
  $sql = "INSERT into stats_prospects(prospect_id, property_id, user_id, prospect_type, prospect_date, notes, prospect_result, 
  prospect_product) values (
  '$prospect_id', '$property_id', '" . $SESSION_USER_ID . "', 'Dial', now(), \"$notes\", \"$prospect_result\", 
  \"$prospecting_product\")";
  executeupdate($sql);
  
  if($prospect_result != "Candidate"){
    $sql = "UPDATE properties set prospecting_type_changedate=now(),
	prospecting_type_changeby='" . $SESSION_USER_ID . "' where property_id='$property_id'";
    executeupdate($sql);

  }
  
  
  
  $addnotes = "";

  
  
  if($prospect_result=="Candidate"){
    $new_opp_product = "Roof Management - Candidate";
	if($prospecting_roof=="Yes") $new_opp_product = "Roof Replacement";
	if($prospecting_roof=="Solar") $new_opp_product = "Solar";
    $sql = "INSERT into opportunities(prospect_id, property_id, user_id, status, opp_stage_id, product, lastaction, opp_stage_changedate) values(
	'$prospect_id', '$property_id', '$opp_user_id', 'Candidate', '2', '$new_opp_product', now(), now())";
	executeupdate($sql);
	$sql = "UPDATE prospects set has_opp=1 where prospect_id='$prospect_id'";
	executeupdate($sql);
	
    $sql = "UPDATE properties set prospecting_rating=\"$prospecting_rating\", prospecting_roof=\"$prospecting_roof\", 
	prospecting_funded=\"$prospecting_funded\", prospecting_squares=\"$prospecting_squares\", prospecting_start=\"$prospecting_start\", 
	prospecting_procurement=\"$prospecting_procurement\", prospecting_prospect='1', prospecting_type_changedate=now(),
	prospecting_type_changeby='" . $SESSION_USER_ID . "'
	where property_id='$property_id'";
	executeupdate($sql);
	
	$addnotes = "Rating: $prospecting_rating\n";
	$addnotes .= "Doing a roof: $prospecting_roof\n";
	$addnotes .= "Project Start: " . $monthname[$prospecting_start_month] . " $prospecting_start_year\n";
	$addnotes .= "Funded: $prospecting_funded\n";
	$addnotes .= "Squares: $prospecting_squares\n";
	$addnotes .= "Procurement: $prospecting_procurement\n";
	
	$redirect = "prospecting_activity.php?prospect_id=$prospect_id&property_id=$property_id";
	

	//}
	
	$send_notification = $_POST['send_notification'];
	if($send_notification){
	  $notification_to = "chad@roofoptions.com";
	  
	  if($opp_user_id){
	    $sql = "SELECT email from users where user_id='$opp_user_id'";
		$email = stripslashes(getsingleresult($sql));
		$notification_to .= "," . $email;
	  }
	  /*
	  if($srmanager){
	    $sql = "SELECT email from users where user_id='$srmanager'";
		$email = stripslashes(getsingleresult($sql));
		$notification_to .= "," . $email;
	  }
	  */
	  
	  $sql = "SELECT site_name from properties where property_id='$property_id'";
	  $site_name = stripslashes(getsingleresult($sql));
	  $sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
	  $company_name = stripslashes(getsingleresult($sql));
	  $subject = "New Prospect: $company_name";
	  $message = "$company_name has been converted to a new '$prospecting_product' candidate. Click the following link to visit the project detail page: ";
	  $message .= "<a href=\"" . $SITE_URL . "ipad/view_property.php?property_id=" . $property_id . "&view=opportunities\">" . $site_name . "</a>";
	  $headers = "Content-type: text/html; charset=iso-8859-1\n";
	  $headers .= "From: info@roofoptions.com\n";
	  email_q($notification_to, $subject, $message, $headers);
	}
	
  }
  $filename = "";
  if (is_uploaded_file($_FILES['attachment']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['attachment']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['attachment']['tmp_name'], $UPLOAD . "attachments/". $filename);
	
	$sql = "UPDATE properties set prospecting_attachment='$filename' where property_id='$property_id'";
	executeupdate($sql);
	
	//$addnotes .= "<a href='" . $CORE_URL . "uploaded_files/attachments/$filename' target='_blank'>Attachment</a>";
  }
  
  if($addnotes != "") $notes = $addnotes . "\n" . $notes;
  
  if($notes != ""){
    $sql = "INSERT into notes(prospect_id, property_id, user_id, date, event, note, attachment) values('$prospect_id', '$property_id', '" . $SESSION_USER_ID . "', 
	now(), 'Note', \"$notes\", '$filename')";
	executeupdate($sql);
  }
  


  $send_email = $_POST['send_email'];  
    if($send_email==1){
	  
	  // send out email
	  
	  $email_users = $_POST['email_users'];
	  $email_company_logo = $_POST['email_company_logo'];
      $email_guests = $_POST['email_guests'];
      $email_guestname = $_POST['email_guestname'];
      $email_email = $_POST['email_email'];
      $email_totalguests = $_POST['email_totalguests'];
      $email_message = nl2br($_POST['email_message']);
	  $email_subject = $_POST['email_subject'];
	  $email_include_signature = $_POST['email_include_signature'];
	  if($email_include_signature != 1) $email_include_signature = 0;
	  
	  $email_imagelink = $_POST['email_imagelink'];
	  $attachimage = "";
	  if (is_uploaded_file($_FILES['email_attachimage']['tmp_name']))
      {
	
	    $ext = explode(".", $_FILES['email_attachimage']['name']);
  	    $ext = strtolower(array_pop($ext));
  	    $filename_attachimage = uniqueTimeStamp() . "." . $ext;
	    move_uploaded_file($_FILES['email_attachimage']['tmp_name'], "uploaded_files/attachments/". $filename_attachimage);
	  
	    $attachimage = "<img src='http://www.encitegroup.com/roofoptions/uploaded_files/attachments/$filename_attachimage' border='0'>";
      }
	
	  if($email_imagelink != ""){
	    if($attachimage == ""){
	      $attachimage = "<a href='$email_imagelink'>$email_imagelink</a>";
	    }
	    else {
	      $attachimage = "<a href='$email_imagelink'>" . $attachimage . "</a>";
	    }
	  }
	  
	  $email_movie = $_POST['email_movie'];
	  $email_banner = $_POST['email_banner'];
	  
	  $email_banner_message = "";
      if($email_banner){
        $sql = "SELECT banner_image from email_specials where id='$email_banner'";
        $banner_image = stripslashes(getsingleresult($sql));
        if($banner_image != ""){
          $email_banner_message = "<img src='" . $SITE_URL . "uploaded_files/emailspecials/" . $banner_image . "' border='0'>";
        }
      }
	

	  if($email_movie){
	    $sql = "SELECT movie_url, movie_linktext, thumbnail from email_specials where id='$email_movie'";
	    $result = executequery($sql);
	    $record = go_fetch_array($result);
	    $movie_url = stripslashes($record['movie_url']);
	    $movie_linktext = stripslashes($record['movie_linktext']);
	    $thumbnail = stripslashes($record['thumbnail']);
        $email_banner_message = "<a href='" . $movie_url . "'>" . $email_banner_message . "</a>";
	  
	  }
	  if($email_banner_message != "") $email_banner_message .= "<br><br>";
	  $email_movie_message = "";
	  if($attachimage) $email_banner_message = $attachimage; // if using their own, overwrite any banner selection
	  
	  $attachment = 0;
	  $filename = '';
	  if (is_uploaded_file($_FILES['emailattachment']['tmp_name']))
      {
	
	    $ext = explode(".", $_FILES['emailattachment']['name']);
  	    $ext = strtolower(array_pop($ext));
  	    $filename = uniqueTimeStamp() . "." . $ext;
	    move_uploaded_file($_FILES['emailattachment']['tmp_name'], $UPLOAD . "attachments/". $filename);
        include '../mail_attachment.php';
	    $attachment = 1;
      }
	  
	  
	  for($x=1;$x<=$email_totalguests;$x++){
        $y = $email_guests[$x];
	    if($y==1) {
	      $guestlist .= $email_guestname[$x] . ", ";
	      $guestlist_email .= $email_email[$x] . ",";
	    }
      }
      $guestlist = go_reg_replace(", $", "", $guestlist);
      $guestlist_email = go_reg_replace(",$", "", $guestlist_email);
  
      for($x=0;$x<sizeof($email_users);$x++){
        $xuser_id = $email_users[$x];
	    $sql = "SELECT concat(firstname, ' ', lastname) as fullname, email from users where user_id='$xuser_id'";
	    $result = executequery($sql);
	    $record = go_fetch_array($result);
	    $fullname = stripslashes($record['fullname']);
	    $user_email = stripslashes($record['email']);
	    $userlist .= $fullname . ", ";
	    $userlist_email .= $user_email . ",";
      }
      $userlist = go_reg_replace(", $", "", $userlist);
      $userlist_email = go_reg_replace(",$", "", $userlist_email);
	  
	  
	  $sql = "SELECT body from templates where name='Activity - Email'";
	  $body = stripslashes(getsingleresult($sql));
	  $body = str_replace("[MESSAGE]", $email_message, $body);
	  $body = str_replace("[PHOTOS]", "", $body);
	  $body = str_replace("[LOGO]", $email_company_logo, $body);
	  $body = str_replace("[URL]", "", $body);
	  $body = str_replace("[IMAGE]", "", $body);
	  $body = str_replace("[BANNERS]", $email_banner_message, $body);
	  $body = str_replace("[MOVIES]", $email_movie_message, $body);
	
	  $sql = "SELECT concat(firstname, ' ', lastname) as fullname, email, office, extension, title, cellphone, signature
	  from users where user_id='" . $SESSION_USER_ID . "'";
	  $result = executequery($sql);
	  $record = go_fetch_array($result);
	  $mail_user_name = stripslashes($record['fullname']);
	  $mail_user_email = stripslashes($record['email']);
	  $mail_user_office = stripslashes($record['office']);
	  $mail_user_office = substr($mail_user_office,0,3)."-".substr($mail_user_office,3,3)."-".substr($mail_user_office,6,4);
	  $mail_user_extension = stripslashes($record['extension']);
	  $mail_user_title = stripslashes($record['title']);
	  $mail_user_cellphone = stripslashes($record['cellphone']);
	  $mail_user_cellphone = substr($mail_user_cellphone,0,3)."-".substr($mail_user_cellphone,3,3)."-".substr($mail_user_cellphone,6,4);
	  $mail_user_signature = $record['signature'];
	
	  if($email_include_signature){
	    if($mail_user_signature != ""){
	      $signature = "<img src=\"" . $CORE_URL . "uploaded_files/headshots/" . $mail_user_signature . "\">";
	    }
	    else {
	      $signature = "Respectfully,<br>";
	      $signature .= $mail_user_name . "<br>";
	      if($mail_user_title != "") $signature .= $mail_user_title . "<br>";
	      $signature .= "<a href='mailto:" . $mail_user_email . "'>" . $mail_user_email . "</a><br>";
	      $signature .= "Office: " . $mail_user_office;
	      if($mail_user_extension != "") $signature .= " Ext: " . $mail_user_extension;
	      $signature .= "<br>";
	      if($mail_user_cellphone != "") $signature .= "Cell: " . $mail_user_cellphone . "<br>";
	      if($email_company_logo == "roofoptions_header.jpg"){
	        $signature .= "<a href='http://www.roofoptions.com'>www.roofoptions.com</a>";
	      }
	      if($email_company_logo == "phencon_logo.jpg"){
	        $signature .= "<a href='http://www.phencon.com'>www.phencon.com</a>";
	      }
	      if($email_company_logo == "fcs_header_800.jpg"){
	        $signature .= "<a href='http://www.fcscontrol.com'>www.fcscontrol.com</a>";
	      }
	    }
	  }
	  else {
	    $signature = "";
	  }
	
	  $body = str_replace("[SIGNATURE]", $signature, $body);
	
	  $to_list = $guestlist_email . "," . $userlist_email;
	  $to_list .= ",dpr@fcscontrol.com";
	  $email_others = $_POST['email_others'];
	  if($email_others != "") $to_list .= "," . $email_others;
	  
	  $to_list = str_replace($mail_user_email, "", $to_list);
	  if($attachment == 0){
	    $headers = "Content-type: text/html; charset=iso-8859-1\n";
	  }
	  else {
	    $headers = "";
	  }
      $headers .= "From: $mail_user_email\n";
      $headers .= "Return-Path: ". $mail_user_email ."\n";  // necessary for some emails such as aol
	  $headers .= "Bcc: $to_list";
	
	  if($attachment==0){
	    email_q($mail_user_email, $email_subject, $body, $headers);
	  }
	  else {
	    email_q($mail_user_email, $email_subject, $body, $headers, 'uploaded_files/attachments/' . $filename);
	  }
	  
	  $note = "Subject: " . $email_subject;
	  $note .= "\n\n" . $email_message;
	  if($attachment){
	    //$note .= "\n\n<a href='" . $CORE_URL . "uploaded_files/attachments/" . $filename . "' target='_blank'>Attachment</a>";
	  }
	  $note = go_escape_string($note);
	  $sql = "INSERT into notes(user_id, property_id, prospect_id, date, event, result, note, attachment) values (
	  '" . $SESSION_USER_ID . "', '$property_id', '$prospect_id', now(), 'Send Email', 'Completed', \"$note\", '$filename')";
	  executeupdate($sql);
	
	  // end of mailing, add $email_others to contact lists.
	  if($email_others != ""){
		$sql = "SELECT corporate from properties where property_id='$property_id'";
		$test = getsingleresult($sql);
		if($test){
		  $contact_prospect_id = $prospect_id;
		  $contact_property_id = 0;
		}
		else {
		  $contact_prospect_id = 0;
		  $contact_property_id = $property_id;
		}
		
		$others = explode(",", $email_others);
		for($x=0;$x<sizeof($others);$x++){
		  $newemail = $others[$x];
		  $sql = "SELECT count(*) from contacts where prospect_id='$contact_prospect_id' and property_id='$contact_property_id' and
		  email='$email'";
		  $exist = getsingleresult($sql);
		  if($exist==0 && $newemail != ""){
		    $sql = "INSERT into contacts(prospect_id, property_id, email) values('$contact_prospect_id', '$contact_property_id', \"$newemail\")";
		    executeupdate($sql);
		  }
		}
	  }
	  
    }

} // end if submit is pressed	

  


if($redirect=="contacts"){
?>
<script>
parent.location.href="contacts.php?cand_type=0";
</script>
<?php exit;
} ?>
<?php

meta_redirect($redirect);
?>
