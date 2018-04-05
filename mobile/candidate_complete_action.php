<?php
include "includes/functions.php";

$prospect_id = go_escape_string($_POST['prospect_id']);
$property_id = go_escape_string($_POST['property_id']);
$todo_id = go_escape_string($_POST['todo_id']);
$user_id = go_escape_string($_POST['user_id']);
$prospect_type = go_escape_string($_POST['prospect_type']);
$prospect_result = go_escape_string($_POST['prospect_result']);
$prospecting_type = go_escape_string($_POST['prospecting_type']);
$beazer_claim_id = go_escape_string($_POST['beazer_claim_id']);

if(in_array($SESSION_USER_ID, $onlyjim)){
  $field = "beazer_claim_id";
  $field_value = $beazer_claim_id;
}
else {
  $field = "prospecting_type";
  $field_value = $prospecting_type;
}

$datepretty = go_escape_string($_POST['datepretty']);
$date_parts = explode("/", $datepretty);
$fixed_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];

$notes = go_escape_string($_POST['notes']);
$submit1 = go_escape_string($_POST['submit1']);


$prospecting_rating = go_escape_string($_POST['prospecting_rating']);
$prospecting_roof = go_escape_string($_POST['prospecting_roof']);
$prospecting_start_month = go_escape_string($_POST['prospecting_start_month']);
$prospecting_start_year = go_escape_string($_POST['prospecting_start_year']);
$prospecting_funded = go_escape_string($_POST['prospecting_funded']);
$prospecting_squares = go_escape_string($_POST['prospecting_squares']);
$prospecting_procurement = go_escape_string($_POST['prospecting_procurement']);
$prospecting_start = $prospecting_start_year . "-" . $prospecting_start_month . "-01";

$newproperty_name = go_escape_string($_POST['newproperty_name']);
$newproperty_address = go_escape_string($_POST['newproperty_address']);
$newproperty_city = go_escape_string($_POST['newproperty_city']);
$newproperty_state = go_escape_string($_POST['newproperty_state']);
$newproperty_zip = go_escape_string($_POST['newproperty_zip']);

$regarding = go_escape_string($_POST['regarding']);
$details = go_escape_string($_POST['details']);

$redirect = go_escape_string($_POST['redirect']);
$redirect = go_reg_replace("\*\*", "?", $redirect);
$redirect = go_reg_replace("\*", "&", $redirect);
if($redirect == "") $redirect = "candidate.php";


if($submit1 != ""){
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
	

  }
  

  $sql = "SELECT user_id, prospect_type from prospecting_todo where id='$todo_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $todo_user_id = $record['user_id'];
  $todo_prospect_type = $record['prospect_type'];
  // Jim says the stats should refelect who marked complete while logged on. 2/25/10 JW
  $sql = "INSERT into stats_prospects(prospect_id, property_id, user_id, prospect_type, prospect_date, notes, prospect_result) values (
  '$prospect_id', '$property_id', '" . $SESSION_USER_ID . "', '$todo_prospect_type', now(), \"$notes\", \"$prospect_result\")";
  executeupdate($sql);
  
  if($prospect_result != "Prospect"){
    $sql = "UPDATE properties set $field='$field_value', prospecting_type_changedate=now(),
	prospecting_type_changeby='" . $SESSION_USER_ID . "' where property_id='$property_id'";
    executeupdate($sql);
  }
  
  $sql = "SELECT corporate from properties where property_id='$property_id'";
  $corporate = getsingleresult($sql);
  if($corporate && $field=="prospecting_type"){
    $sql = "UPDATE prospects set prospecting_type='$prospecting_type' where prospect_id='$prospect_id'";
    executeupdate($sql);
  }
  
  
  $addnotes = "";
  // prospecting_rating now on all entries, not just prospect_result of Prospect
  
  $sql = "UPDATE properties set prospecting_rating=\"$prospecting_rating\" 
  where property_id='$property_id'";
  executeupdate($sql);
  
  
  if($prospect_result=="Prospect"){
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
	
	
	//if(in_array($SESSION_USER_ID, $PROSPECTING_TO_OPP)){ // Only Jim and Brian, for now
	  $code = uniqueTimeStamp();
	  if($prospecting_procurement == "Yes"){
	    $opp_status = "Bid Procurement";
	  }
	  else {
	    $opp_status = "Prospect";
	  }
      $sql = "INSERT into opportunities(prospect_id, property_id, user_id, product, status, description, code, probability, 
	  lastaction, projected_replacement, srmanager) values (
	  '$prospect_id', '$property_id', '" . $SESSION_USER_ID . "', \"Roof Replacement\", '$opp_status', \"$notes\", \"$code\", \"$prospecting_rating\", 
	  now(), '$prospecting_start_year', 0)";
	  executeupdate($sql);
	  $sql = "SELECT opp_id from opportunities where user_id = \"" . $SESSION_USER_ID . "\" order by opp_id desc limit 1";
	  $opp_id = getsingleresult($sql);
	
	  //stats
	  $sql = "INSERT into closing_stats(user_id, opp_id, date, status, amount) values (
	  '" . $SESSION_USER_ID . "', '$opp_id', now(), '$opp_status', '0')";
	  executeupdate($sql);
	//}
	
  }
  
  
  if($addnotes != "") $notes = $addnotes . "\n" . $notes;
  
  if($notes != ""){
    $sql = "INSERT into notes(prospect_id, property_id, user_id, date, event, note) values('$prospect_id', '$property_id', '$todo_user_id', 
	now(), 'Note', \"$notes\")";
	executeupdate($sql);
  }
  
  
  if($todo_id){
    $sql = "UPDATE prospecting_todo set result='$prospect_result', last_action=now(), notes=\"$notes\", complete=1
	where id='$todo_id' and complete=0";
	executeupdate($sql);
	// Jim says the stats should refelect who marked complete while logged on. 2/25/10 JW
	if($todo_prospect_type=="Inspection" || $todo_prospect_type=="Bid Presentation" || $todo_prospect_type=="Risk Report Presentation" || $todo_prospect_type=="FCS Meetings"){
	  $sql = "INSERT into activities(user_id, prospect_id, property_id, event, date, complete_date, complete, scheduled_by) values(
	  '" . $SESSION_USER_ID . "', '$prospect_id', '$property_id', '$todo_prospect_type', now(), now(), 1, '$todo_user_id')";
	  executeupdate($sql);
	}
	
	$sql = "SELECT supercali_event_id from prospecting_todo where id='$todo_id'";
	$event_id = getsingleresult($sql);
	$sql = "UPDATE supercali_events set complete=1 where event_id='$event_id'";
	executequery($sql);
  }
  
  // don't do for prospect or not a candidate or Beazer contingency
  if($prospect_result != "Prospect" && $prospecting_type != 2 && $beazer_claim_id !=3){  
  /*
    if($prospect_type=="Email"){
	  $sql = "INSERT into prospecting_todo(prospect_id, property_id, date_added, last_action, user_id, prospect_type) values(
	  '$prospect_id', '$property_id', '$fixed_date', now(), '$user_id', 'Dial')";
	  executeupdate($sql);
	  
	  $sql = "INSERT into prospecting_todo(prospect_id, property_id, date_added, last_action, user_id, prospect_type, complete) values(
	  '$prospect_id', '$property_id', now(), now(), '$user_id', 'Email', 1)";
	  executeupdate($sql);
	  
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
	  if (is_uploaded_file($_FILES['emailattachment']['tmp_name']))
      {
	
	    $ext = explode(".", $_FILES['emailattachment']['name']);
  	    $ext = strtolower(array_pop($ext));
  	    $filename = uniqueTimeStamp() . "." . $ext;
	    move_uploaded_file($_FILES['emailattachment']['tmp_name'], "uploaded_files/attachments/". $filename);
        include 'mail_attachment.php';
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
	  from users where user_id='" . $user_id . "'";
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
	      $signature = "<img src=\"" . $SITE_URL . "uploaded_files/headshots/" . $mail_user_signature . "\">";
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
	    $note .= "\n\n<a href='uploaded_files/attachments/" . $filename . "' target='_blank'>Attachment</a>";
	  }
	  $note = go_escape_string($note);
	  $sql = "INSERT into notes(user_id, property_id, prospect_id, date, event, result, note) values (
	  '$user_id', '$property_id', '$prospect_id', now(), 'Send Email', 'Completed', \"$note\")";
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
	else {
      $sql = "INSERT into prospecting_todo(prospect_id, property_id, date_added, last_action, user_id, notes, details, prospect_type) values(
	  '$prospect_id', '$property_id', '$fixed_date', now(), '$user_id', \"$regarding\", \"$details\", '$prospect_type')";
	  executeupdate($sql);
	}
	*/
	
	// commented out email section above, so just do this
	$sql = "INSERT into prospecting_todo(prospect_id, property_id, date_added, last_action, user_id, notes, details, prospect_type) values(
	  '$prospect_id', '$property_id', '$fixed_date', now(), '$user_id', \"$regarding\", \"$details\", '$prospect_type')";
	  executeupdate($sql);
	
	$sql = "SELECT id from prospecting_todo where prospect_id='$prospect_id' and property_id='$property_id' order by id desc limit 1";
	$new_todo_id = getsingleresult($sql);
	$new_supercali = 0;
	
	if($prospect_type=="Inspection"){
		$event_name = "Inspection";
		$category_id = 23;
		$what = "I";
		$new_supercali = 1;
    }
	if($prospect_type=="FCS Meetings"){
		$event_name = "FCS Meetings";
		$category_id = 25;
		$what = "UM";
		$new_supercali = 1;
    }
	if($prospect_type=="Bid Presentation"){
		$event_name = "Bid Presentation";
		$category_id = 22;
		$what = "BP";
		$new_supercali = 1;
    }
	if($prospect_type=="Risk Report Presentation"){
		$event_name = "Risk Report Presentation";
		$category_id = 24;
		$what = "RRP";
		$new_supercali = 1;
    }
	
	if($new_supercali){
		
		$sauce = md5(time());
        $sql = "INSERT into supercali_events (title, venue_id, contact_id, description, category_id, user_id, group_id, status_id, 
        quick_approve, prospect_id, property_id, what, value, ro_user_id, act_id) 
        values 
        (\"$event_name\", '1', '1', \"$event_name\", '$category_id', '2', '1', '1', '".$sauce."', '".$prospect_id."', 
         '".$property_id."', '$what','', '".$user_id."', '')";
        executeupdate($sql);
  
        $sql = "SELECT event_id from supercali_events where property_id='$property_id' order by event_id desc limit 1";
        $event_id = getsingleresult($sql);
  
        $sql = "INSERT into supercali_dates(event_id, date, end_date) values ('$event_id', '$fixed_date', '$fixed_date')";
        executeupdate($sql);
	  
	    $sql = "UPDATE prospecting_todo set supercali_event_id='$event_id' where id='$new_todo_id'";
	    executeupdate($sql);

    }
	
  }
  

}


meta_redirect($redirect);
?>