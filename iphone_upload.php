<?php
require_once "includes/functions.php";

//*************************************************
//
// user_id is now passed on every query, in case you need it
//
//***************************************************

  switch($_POST['query']){
	case "addtestcut":{
	  $name = go_escape_string($_POST['name']);
	  $comments = go_escape_string($_POST['comments']);
	  $section_id = go_escape_string($_POST['section_id']);
	  
	  
	  $sql = "INSERT into sections_testcuts(name, comments, section_id) values(\"$name\", \"$comments\", 
	  \"$section_id\")";
	  executeupdate($sql);
	  $sql = "SELECT cut_id from sections_testcuts where section_id='$section_id' order by cut_id desc limit 1";
	  $cut_id = getsingleresult($sql);
	  
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	
	   $ext = explode(".", $_FILES['photo']['name']);
  	   $ext = array_pop($ext);
  	   $filename = uniqueTimeStamp() . "." . $ext;
	   move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	   resizeimage("uploaded_files/temp/$filename", "uploaded_files/testcuts/", $REPORT_PHOTO_SIZE);
	   @copy("uploaded_files/testcuts/$filename", "uploaded_files/testcuts/th_" . $filename);
	   resizeimage("uploaded_files/testcuts/th_" . $filename, "uploaded_files/testcuts/", $THUMBNAIL);
  	   @unlink("uploaded_files/temp/". $filename);
	
	   $sql = "UPDATE sections_testcuts set photo='$filename' where cut_id='$cut_id'";
	   executeupdate($sql);
      }
	  echo "id: $section_id<br>";
	  echo "error: " . $_FILES['photo']['error'];
	  
	  break;
	}
	
	case "edittestcut":{
	  $name = go_escape_string($_POST['name']);
	  $comments = go_escape_string($_POST['comments']);
	  $cut_id = go_escape_string($_POST['cut_id']);
	  $sql = "SELECT photo from sections_testcuts where cut_id='$cut_id'";
	  $oldphoto = getsingleresult($sql);
	  if($oldphoto) @unlink("uploaded_files/testcuts/$oldphoto");
	  if($oldphoto) @unlink("uploaded_files/testcuts/th_" . $oldphoto);
	  
	  $sql = "UPDATE sections_testcuts set name=\"$name\", comments=\"$comments\" where cut_id='$cut_id'";
	  executeupdate($sql);
	  
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	
	   $ext = explode(".", $_FILES['photo']['name']);
  	   $ext = array_pop($ext);
  	   $filename = uniqueTimeStamp() . "." . $ext;
	   move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	   resizeimage("uploaded_files/temp/$filename", "uploaded_files/testcuts/", $REPORT_PHOTO_SIZE);
	   @copy("uploaded_files/testcuts/$filename", "uploaded_files/testcuts/th_" . $filename);
	   resizeimage("uploaded_files/testcuts/th_" . $filename, "uploaded_files/testcuts/", $THUMBNAIL);
  	   @unlink("uploaded_files/temp/". $filename);
	
	   $sql = "UPDATE sections_testcuts set photo='$filename' where cut_id='$cut_id'";
	   executeupdate($sql);
      }
	  
	  echo "id: $cut_id<br>";
	  echo "error: " . $_FILES['photo']['error'];
	  
	  break;
	}
	
	case "deltestcut":{

	  $cut_id = go_escape_string($_POST['cut_id']);
	  $sql = "SELECT photo from sections_testcuts where cut_id='$cut_id'";
	  $oldphoto = getsingleresult($sql);
	  if($oldphoto) @unlink("uploaded_files/testcuts/$oldphoto");
	  if($oldphoto) @unlink("uploaded_files/testcuts/th_" . $oldphoto);
	  
	  $sql = "DELETE from sections_testcuts where cut_id='$cut_id'";
	  executeupdate($sql);
	  
	  break;
	}

	case "addobservation":{
	  //$name = go_escape_string($_POST['name']);
	  $description = go_escape_string($_POST['description']);
	  $section_id = go_escape_string($_POST['section_id']);
	  
	  
	  $sql = "INSERT into sections_photos(description, section_id) values(\"$description\", 
	  \"$section_id\")";
	  executeupdate($sql);
	  $sql = "SELECT photo_id from sections_photos where section_id='$section_id' order by photo_id desc limit 1";
	  $photo_id = getsingleresult($sql);
	  
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	
	   $ext = explode(".", $_FILES['photo']['name']);
  	   $ext = array_pop($ext);
  	   $filename = uniqueTimeStamp() . "." . $ext;
	   move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	   resizeimage("uploaded_files/temp/$filename", "uploaded_files/photos/", $REPORT_PHOTO_SIZE);
	   @copy("uploaded_files/photos/$filename", "uploaded_files/photos/th_" . $filename);
	   resizeimage("uploaded_files/photos/th_" . $filename, "uploaded_files/photos/", $THUMBNAIL);
  	   @unlink("uploaded_files/temp/". $filename);
	
	   $sql = "UPDATE sections_photos set photo='$filename' where photo_id='$photo_id'";
	   executeupdate($sql);
      }
	  echo "id: $section_id<br>";
	  echo "error: " . $_FILES['photo']['error'];
	  
	  break;
	}
	
	case "editobservation":{
	  //$name = go_escape_string($_POST['name']);
	  $description = go_escape_string($_POST['description']);
	  $photo_id = go_escape_string($_POST['photo_id']);
	  
	  $sql = "SELECT photo from sections_photos where photo_id='$photo_id'";
	  $oldphoto = getsingleresult($sql);
	  if($oldphoto) @unlink("uploaded_files/photos/$oldphoto");
	  if($oldphoto) @unlink("uploaded_files/photos/th_" . $oldphoto);
	  
	  
	  $sql = "UPDATE sections_photos set description=\"$description\" where photo_id='$photo_id'";
	  executeupdate($sql);
	  
	  
  
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	
	   $ext = explode(".", $_FILES['photo']['name']);
  	   $ext = array_pop($ext);
  	   $filename = uniqueTimeStamp() . "." . $ext;
	   move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	   resizeimage("uploaded_files/temp/$filename", "uploaded_files/photos/", $REPORT_PHOTO_SIZE);
	   @copy("uploaded_files/photos/$filename", "uploaded_files/photos/th_" . $filename);
	   resizeimage("uploaded_files/photos/th_" . $filename, "uploaded_files/photos/", $THUMBNAIL);
  	   @unlink("uploaded_files/temp/". $filename);
	
	   $sql = "UPDATE sections_photos set photo='$filename' where photo_id='$photo_id'";
	   executeupdate($sql);
      }
	  echo "id: $photo_id<br>";
	  echo "error: " . $_FILES['photo']['error'];
	  
	  break;
	}
	
	case "delobservation":{
	  $photo_id = go_escape_string($_POST['photo_id']);
	  
	  $sql = "SELECT photo from sections_photos where photo_id='$photo_id'";
	  $oldphoto = getsingleresult($sql);
	  if($oldphoto) @unlink("uploaded_files/photos/$oldphoto");
	  if($oldphoto) @unlink("uploaded_files/photos/th_" . $oldphoto);
	  
	  
	  $sql = "DELETE from sections_photos where photo_id='$photo_id'";
	  executeupdate($sql);

	  
	  break;
	}

	case "adddef":{
	// I was informed we're not using adddef, only editdef. 2/3/12 JW
	  $name = go_escape_string($_POST['name']);
	  $quantity = go_escape_string($_POST['quantity']);
	  $quantity_unit = go_escape_string($_POST['quantity_unit']);
	  $def_type = go_escape_string($_POST['def_type']);
	  $section_id = go_escape_string($_POST['section_id']);
	  $cost = go_escape_string($_POST['cost']);
	  
	  $sql = "SELECT def, corrective_action from def_list where def_name = \"$name\"";
	  $result = executequery($sql);
	  $record = go_fetch_array($result);
	  $def = go_escape_string($record['def']);
	  $action=go_escape_string($record['corrective_action']);
	  
	  
	  $sql = "INSERT into sections_def(section_id, name, def, action, cost, quantity, quantity_unit, def_type, date_recorded) values (
      \"$section_id\", \"$name\", \"$def\", \"$action\", \"$cost\", \"$quantity\", \"$quantity_unit\", \"$def_type\", now())";
      executeupdate($sql);
      $sql = "SELECT def_id from sections_def where section_id='$section_id' order by def_id desc limit 1";
      $def_id = getsingleresult($sql);
	  
	  $sql = "SELECT property_id from sections where section_id='$section_id'";
	  $property_id = getsingleresult($sql);
  
      $description = $name;
      $sql = "INSERT into asset_management (section_id, property_id, def_id, date, item, description, expense_type, amount) values (
      '$section_id', '$property_id', '$def_id', now(), 'Quote', \"$description\", 'Expense', \"$cost\")";
      executeupdate($sql);
	  
	  
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	
	   $ext = explode(".", $_FILES['photo']['name']);
  	   $ext = array_pop($ext);
  	   $filename = uniqueTimeStamp() . "." . $ext;
	   move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	   resizeimage("uploaded_files/temp/$filename", "uploaded_files/def/", $REPORT_PHOTO_SIZE);
	   @copy("uploaded_files/def/$filename", "uploaded_files/def/th_" . $filename);
	   resizeimage("uploaded_files/def/th_" . $filename, "uploaded_files/def/", $THUMBNAIL);
  	   @unlink("uploaded_files/temp/". $filename);
	
	   $sql = "UPDATE sections_def set photo='$filename' where def_id='$def_id'";
	   executeupdate($sql);
      }
	  echo "id: $section_id<br>";
	  echo "error: " . $_FILES['photo']['error'];
	  break;
	}
	
	case "editdef":{
	  $name = go_escape_string($_POST['name']);
	  $quantity = go_escape_string($_POST['quantity']);
	  $quantity_unit = go_escape_string($_POST['quantity_unit']);
	  $def_type = go_escape_string($_POST['def_type']);
	  $def_id = go_escape_string($_POST['def_id']);
	  $cost = go_escape_string($_POST['cost']);
	  $action = go_escape_string($_POST['action']);
	  $def = go_escape_string($_POST['def']);
	  $category_id = go_escape_string($_POST['category_id']);
	  if($category_id=="") $category_id = 0;
	  
	  /*
	  $sql = "SELECT def, corrective_action from def_list where def_name = \"$name\"";
	  $result = executequery($sql);
	  $record = go_fetch_array($result);
	  $def = go_escape_string($record['def']);
	  $action=go_escape_string($record['corrective_action']);
	  */
	  
	  $sql = "SELECT photo from sections_def where def_id='$def_id'";
	  $oldphoto = getsingleresult($sql);
	  if($oldphoto) @unlink("uploaded_files/def/$oldphoto");
	  if($oldphoto) @unlink("uploaded_files/def/th_" . $oldphoto);
	  
	  
	  $sql = "UPDATE sections_def set def_id='$def_id', category_id='$category_id'";
	  if($name != "") $sql .= ",name=\"$name\"";
	  if($quantity != "") $sql .= ", quantity=\"$quantity\"";
	  if($quantity_unit != "") $sql .= ", quantity_unit=\"$quantity_unit\"";
	  if($def_type != "") $sql .= ", def_type=\"$def_type\"";
	  if($def != "") $sql .= ", def=\"$def\"";
	  if($action != "") $sql .= ", action=\"$action\"";
	  if($cost != "") $sql .= ", cost=\"$cost\"";
	  $sql .= " where def_id='$def_id'";
	  executeupdate($sql);
	  
	  $sql = "SELECT section_id from sections_def where def_id='$def_id'";
	  $section_id = getsingleresult($sql);
	  
	  $sql = "SELECT count(*) from sections_def where section_id='$section_id' and number=0";
	  $test = getsingleresult($sql);
	  while($test){
	    $sql = "SELECT def_id from sections_def where section_id='$section_id' and number=0 order by def_id limit 1";
	    $test_def_id = getsingleresult($sql);
	    $sql = "SELECT number from sections_def where section_id='$section_id' order by number desc limit 1";
		$number = getsingleresult($sql);
		$number += 1;
		$sql = "UPDATE sections_def set number='$number' where def_id='$test_def_id'";
		executeupdate($sql);
		$sql = "SELECT count(*) from sections_def where section_id='$section_id' and number=0";
	    $test = getsingleresult($sql);
	  }
		
	  
      $description = $name;
	  $sql = "UPDATE asset_management set description=\"$description\", date=now() where def_id='$def_id'";
	  executeupdate($sql);
	  
	  
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	
	   $ext = explode(".", $_FILES['photo']['name']);
  	   $ext = array_pop($ext);
  	   $filename = uniqueTimeStamp() . "." . $ext;
	   move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	   resizeimage("uploaded_files/temp/$filename", "uploaded_files/def/", $REPORT_PHOTO_SIZE);
	   @copy("uploaded_files/def/$filename", "uploaded_files/def/th_" . $filename);
	   resizeimage("uploaded_files/def/th_" . $filename, "uploaded_files/def/", $THUMBNAIL);
  	   @unlink("uploaded_files/temp/". $filename);
	
	   $sql = "UPDATE sections_def set photo='$filename' where def_id='$def_id'";
	   executeupdate($sql);
      }
	  
	  echo "id: $def_id<br>";
	  echo "error: " . $_FILES['photo']['error'];
	  break;
	}
	
	case "deldef":{
	  $def_id = go_escape_string($_POST['def_id']);
	  
	  $sql = "SELECT photo from sections_def where def_id='$def_id'";
	  $oldphoto = getsingleresult($sql);
	  if($oldphoto) @unlink("uploaded_files/def/$oldphoto");
	  if($oldphoto) @unlink("uploaded_files/def/th_" . $oldphoto);
	  
	  
	  $sql = "DELETE from sections_def where def_id='$def_id'";
	  executeupdate($sql);
	  
	  
	  $sql = "DELETE from asset_management where def_id='$def_id'";
	  executeupdate($sql);
	  
	  
	  break;
	}
	
	case "addvideo":{
	  $section_id = go_escape_string($_POST['section_id']);
	  if($_POST['name']) {
	  	$name = go_escape_string($_POST['name']);
	  } else {
	  	$name = "Video";
	  }
      $sql = "INSERT into sections_videos (section_id, name) values('$section_id', \"$name\")";
      executeupdate($sql);
      $sql = "SELECT video_id from sections_videos where section_id='$section_id' order by video_id desc limit 1";
      $video_id = getsingleresult($sql);
  
      if (is_uploaded_file($_FILES['video']['tmp_name']))
      {
	
	    $ext = explode(".", $_FILES['video']['name']);
  	    $ext = strtolower(array_pop($ext));
  	    $filename = uniqueTimeStamp() . "." . $ext;
	    move_uploaded_file($_FILES['video']['tmp_name'], "uploaded_files/videos/". $filename);
	
	    $sql = "UPDATE sections_videos set video='$filename' where video_id='$video_id' and section_id='$section_id'";
	    executeupdate($sql);
      }
	  echo "id: $section_id<br>";
	  echo "error: " . $_FILES['video']['error'];
	  break;
	}
	
	case "editvideo":{
	  $video_id = go_escape_string($_POST['video_id']);
	  if($_POST['name']) {
	  	$name = go_escape_string($_POST['name']);
	  } else {
		$name = "Video";
	  }
	  
	  $sql = "SELECT video from sections_videos where video_id='$video_id'";
	  $oldphoto = getsingleresult($sql);
	  if($oldphoto) @unlink("uploaded_files/videos/$oldphoto");
	  
	  
	  $sql = "UPDATE sections_videos set name=\"$name\" where video_id='$video_id'";
	  executeupdate($sql);
      
  
      if (is_uploaded_file($_FILES['video']['tmp_name']))
      {
	
	    $ext = explode(".", $_FILES['video']['name']);
  	    $ext = strtolower(array_pop($ext));
  	    $filename = uniqueTimeStamp() . "." . $ext;
	    move_uploaded_file($_FILES['video']['tmp_name'], "uploaded_files/videos/". $filename);
	
	    $sql = "UPDATE sections_videos set video='$filename' where video_id='$video_id'";
	    executeupdate($sql);
      }
	  echo "id: $video_id<br>";
	  echo "error: " . $_FILES['video']['error'];
	  break;
	}
	
	case "delvideo":{
	  $video_id = go_escape_string($_POST['video_id']);
	  
	  $sql = "SELECT video from sections_videos where video_id='$video_id'";
	  $oldphoto = getsingleresult($sql);
	  if($oldphoto) @unlink("uploaded_files/videos/$oldphoto");
	  
	  
	  $sql = "DELETE from sections_videos where video_id='$video_id'";
	  executeupdate($sql);

	  break;
	}
	
	
	case "editpropertyimage":{
	  $property_id = go_escape_string($_POST['property_id']);
	  
	  
	  
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	
	    $ext = explode(".", $_FILES['photo']['name']);
  	    $ext = array_pop($ext);
  	    $filename = uniqueTimeStamp() . "." . $ext;
	    move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	    resizeimage("uploaded_files/temp/$filename", "uploaded_files/properties/", $PROPERTY_SIZE);
	    @copy("uploaded_files/properties/$filename", "uploaded_files/properties/th_" . $filename);
	    resizeimage("uploaded_files/properties/th_" . $filename, "uploaded_files/properties/", $THUMBNAIL);
  	    @unlink("uploaded_files/temp/". $filename);
	
	    $sql = "UPDATE properties set image='$filename' where property_id='$property_id'";
	    executeupdate($sql);
		$sql = "INSERT into properties_images(property_id, image, type) values('$property_id', '$filename', 'roof')";
		executeupdate($sql);
      }
	  echo "id: $property_id<br>";
	  echo "error: " . $_FILES['photo']['error'];
	  break;
	}
	
	case "editfrontimage":{
	  $property_id = go_escape_string($_POST['property_id']);
	  
	  
	  
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	
	    $ext = explode(".", $_FILES['photo']['name']);
  	    $ext = array_pop($ext);
  	    $filename = uniqueTimeStamp() . "." . $ext;
	    move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	    resizeimage("uploaded_files/temp/$filename", "uploaded_files/properties/", $PROPERTY_SIZE);
	    @copy("uploaded_files/properties/$filename", "uploaded_files/properties/th_" . $filename);
	    resizeimage("uploaded_files/properties/th_" . $filename, "uploaded_files/properties/", $THUMBNAIL);
  	    @unlink("uploaded_files/temp/". $filename);
	
	    $sql = "UPDATE properties set image_front='$filename' where property_id='$property_id'";
	    executeupdate($sql);
		/*
		$sql = "INSERT into properties_images(property_id, image, type) values('$property_id', '$filename', 'roof')";
		executeupdate($sql);
		*/
      }
	  echo "id: $property_id<br>";
	  echo "error: " . $_FILES['photo']['error'];
	  break;
	}
	
	case "editsectionimage":{
	  $section_id = go_escape_string($_POST['section_id']);
	  
	  
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {

  
        $ext = explode(".", $_FILES['photo']['name']);
  	    $ext = array_pop($ext);
  	    $filename = uniqueTimeStamp() . "." . $ext;
	    move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	    resizeimage("uploaded_files/temp/$filename", "uploaded_files/sections/", $SECTION_PHOTO_SIZE);
  	    @unlink("uploaded_files/temp/". $filename);
	
	    $sql = "UPDATE sections set main_photo='$filename' where section_id='$section_id'";
	    executeupdate($sql);
      }
	  echo "id: $section_id<br>";
	  echo "error: " . $_FILES['photo']['error'];
	  break;
	}
	
	case "readyforapproval":{
	  $property_id = go_escape_string($_POST['property_id']);
	  $user_id = go_escape_string($_POST['user_id']);
	  
	  $sql = "UPDATE properties set ready_for_pre_approval=1, pre_approval=0, text_alert_sent=0, final_approval=0 where property_id='$property_id'";
	  executeupdate($sql);
	  
	  $sql = "SELECT master_id from users where user_id='$user_id'";
	  $master_id = getsingleresult($sql);
	  $sql = "SELECT count(*) from toolbox_items where tool_master_id=4 and master_id='" . $master_id . "'"; // check for capital tool
      $test = getsingleresult($sql);
	  if($test){
	    $sql = "SELECT disable_caps from master_list where master_id='" . $master_id . "'";
        $dc = getsingleresult($sql);
        if($dc==0){
	      include "capitalize_main.php"; // script to capitalize stuff
		}
	  }
	  
	  $sql = "SELECT require_pre_approval from users where user_id='$user_id'";
	  $require_pre_approval = getsingleresult($sql);
	  if($require_pre_approval==0){
	    $sql = "UPDATE properties set pre_approval='1', pre_approval_reason='', 
        ready_for_pre_approval=0 where property_id='$property_id'";
        executeupdate($sql);
        $sql = "UPDATE activities set complete=1, complete_date=now() where property_id='$property_id' and event='Inspection'";
        executeupdate($sql);
	  }
	  
	  break;
	}
	
	case "readyforemail":{
	  $property_id = go_escape_string($_POST['property_id']);
	  $user_id = go_escape_string($_POST['user_id']);
	  
	  $sql = "UPDATE properties set ready_for_email=1, text_alert_sent=0, final_approval=0 where property_id='$property_id'";
	  executeupdate($sql);
	  
	  $sql = "SELECT require_final_approval from users where user_id='$user_id'";
	  $require_final_approval = getsingleresult($sql);
	  if($require_final_approval==0){
	    $sql = "UPDATE properties set final_approval=1, ready_for_email=0, pre_approval_reason='', final_approval_reason=''
	    where property_id='$property_id'";
        executeupdate($sql);
	  }
	  
	  
	  break;
	}
	
	case "email":{
	  $property_id = go_escape_string($_POST['property_id']);
	  $email_address = go_escape_string($_POST['email_address']);
	  $comments = go_escape_string($_POST['comments']);
	  
	  $user_id = go_escape_string($_POST['user_id']);
	  
	  $sql = "SELECT email from users where user_id='$user_id'";
	  $email_from = stripslashes(getsingleresult($sql));
	  if($email_from != $email_address) $bcc = $email_from;
	  
	  $ipod=1;
	  $dis_id=1; // temp, I assume, manually set dis to 1
	  
	  
	  
	  $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $FCS_URL . "report_print_presentation_pdf_property.php?property_id=$property_id&ipod=1&dis_id=$dis_id");
      curl_setopt($ch, CURLOPT_HEADER, 0);

      // grab URL and pass it to the browser
      curl_exec($ch);

      // close cURL resource, and free up system resources
      curl_close($ch);
	  
	  //include $UP_FCSVIEW . "report_print_presentation_pdf_property.php";
	  
	  require_once 'mail_attachment.php';
	  $headers = "";
      $headers .= "From: $email_from\n";
      $headers .= "Return-Path: $email_from\n";  // necessary for some emails such as aol
	  $headers .= "Bcc: $bcc";
	  
	  $sql = "SELECT filename from roof_report_pdf where property_id='$property_id' and dis_id='$dis_id'";
	  $filename = stripslashes(getsingleresult($sql));

	  
	  
	  $sql = "SELECT prospect_id from properties where property_id='$property_id'";
	  $prospect_id = getsingleresult($sql);
	  $sql = "SELECT master_id from users where user_id='$user_id'";
	  $master_id = getsingleresult($sql);
	  
	  $sql = "INSERT into notes(prospect_id, property_id, date, event, regarding, note, attachment) values(
	  '$prospect_id', '$property_id', now(), 'Note', 'Final Approval Email', \"$comments\", '$filename')";
	  executeupdate($sql);
	  
	  $sql = "INSERT into opportunities(user_id, scheduled_by, prospect_id, property_id, status, product, opp_product_id, amount, lastaction) values(
	  '$user_id', '$user_id', '$prospect_id', '$property_id', 'Quoted', '', -2, 0, now())";
	  executeupdate($sql);
	  
	  $sql = "SELECT scheduled_by from activities where event='Inspection' and property_id='$property_id' order by act_id desc limit 1";
	  $scheduled_by = getsingleresult($sql);
	  if($scheduled_by==0 || $scheduled_by =="") $scheduled_by = $user_id;
	  $sql = "INSERT into activities(prospect_id, property_id, date, event, scheduled_by, user_id, regarding) values('$prospect_id', '$property_id', now(), 'To Do',
	  '$scheduled_by', '$scheduled_by', 'Schedule a Follow Up')";
	  executeupdate($sql);
	  $act_id = go_insert_id();
	  $sauce = md5(time());
	  $category_id = 40;
	  $what = "TD";
      $sql = "INSERT into supercali_events (title, venue_id, contact_id, description, category_id, user_id, group_id, status_id, 
      quick_approve, prospect_id, property_id, what, value, ro_user_id, act_id, master_id) 
      values 
      (\"To Do - $act_id\", '1', '1', \"Schedule a Follow Up\", '$category_id', '2', '1', '1', '".$sauce."', '".$prospect_id."', 
       '".$property_id."', '".$what."','".$bp_value."', '".$scheduled_by."', '$act_id', '" . $master_id . "')";
      executeupdate($sql);
  
      $event_id = go_insert_id();
  
      $sql = "INSERT into supercali_dates(event_id, date, end_date) values ('$event_id', now(), now())";
      executeupdate($sql);
	  
	  //$newfilename = uniqueTimeStamp() . ".pdf";
	  //@copy($UP_FCSVIEW . "uploaded_files/roofreport/$filename", $UP_FCSVIEW . "uploaded_files/roofreport/$newfilename");
	  if($email_address != "") email_q($email_address, "Roof Report", $comments, $headers, 'uploaded_files/roofreport/' . $filename, $FCS_URL);
	  @copy($UP_FCSVIEW . "uploaded_files/roofreport/$filename", "uploaded_files/attachments/$filename");
	  //@unlink($UP_FCSVIEW . "uploaded_files/roofreport/$filename");
	  
	  break;
	}
	
	
	case "appdata":{
	  $property_id = go_escape_string($_POST['property_id']);
	  
	  if (is_uploaded_file($_FILES['appdata']['tmp_name']))
      {

  
        $ext = explode(".", $_FILES['appdata']['name']);
  	    $ext = array_pop($ext);
  	    $filename = $property_id . "." . $ext;
	    move_uploaded_file($_FILES['appdata']['tmp_name'], "uploaded_files/appdata/". $filename);
	
      }
	  echo "id: $property_id<br>";
	  echo "error: " . $_FILES['photo']['error'];
	  break;
	}
	
	case "deleteappdata":{
	  $property_id = go_escape_string($_POST['property_id']);
	  
	  if($property_id != ""){
	    $files = glob("uploaded_files/appdata/$property_id" . ".*");
	  }
	  
	  if(is_array($files)){
	    for($x=0;$x<sizeof($files);$x++){
	      @unlink($files[$x]);
	    }
	  }
	  
	  break;
	}
	
	
	case "addsdphoto":{
	  $leak_id = go_escape_string($_POST['leak_id']);
	  $problem_id = go_escape_string($_POST['problem_id']);
	  $type = go_escape_string($_POST['type']);
	  
	  if($problem_id=="") $problem_id = 0;
	  if($type=="") $type = 1;
	  
	  $sql = "INSERT into am_leakcheck_photos(leak_id, problem_id, type) values(\"$leak_id\", 
	  \"$problem_id\", \"$type\")";
	  executeupdate($sql);
	  $sql = "SELECT photo_id from am_leakcheck_photos where leak_id='$leak_id' order by photo_id desc limit 1";
	  $photo_id = getsingleresult($sql);
	  
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	
	   $ext = explode(".", $_FILES['photo']['name']);
  	   $ext = array_pop($ext);
  	   $filename = uniqueTimeStamp() . "." . $ext;
	   move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	   resizeimage("uploaded_files/temp/$filename", $UP_FCSVIEW . "uploaded_files/leakcheck/", 400);
  	   @unlink("uploaded_files/temp/". $filename);
	
	   $sql = "UPDATE am_leakcheck_photos set photo='$filename' where photo_id='$photo_id'";
	   executeupdate($sql);
      }
	  echo "id: $leak_id<br>";
	  echo "error: " . $_FILES['photo']['error'];
	  
	  break;
	}
	
	case "editsdphoto":{
	  $photo_id = go_escape_string($_POST['photo_id']);
	  $leak_id = go_escape_string($_POST['leak_id']);
	  $problem_id = go_escape_string($_POST['problem_id']);
	  $type = go_escape_string($_POST['type']);
	  
	  if($problem_id=="") $problem_id = 0;
	  if($type=="") $type = 1;
	  
	  
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	
	   $ext = explode(".", $_FILES['photo']['name']);
  	   $ext = array_pop($ext);
  	   $filename = uniqueTimeStamp() . "." . $ext;
	   move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	   resizeimage("uploaded_files/temp/$filename", $UP_FCSVIEW . "uploaded_files/leakcheck/", 400);
  	   @unlink("uploaded_files/temp/". $filename);
	
	   $sql = "UPDATE am_leakcheck_photos set photo='$filename' where photo_id='$photo_id'";
	   executeupdate($sql);
      }
	  echo "id: $photo_id<br>";
	  echo "error: " . $_FILES['photo']['error'];
	  
	  break;
	}
	
	case "delsdphoto":{
	  $photo_id = go_escape_string($_POST['photo_id']);
	  
	  $sql = "SELECT photo from am_leakcheck_photos where photo_id='$photo_id'";
	  $photo = getsingleresult($sql);
	  
	  if($photo != "") @unlink($UP_FCSVIEW . "uploaded_files/leakcheck/" . $photo);
	  
	  $sql = "DELETE from am_leakcheck_photos where photo_id='$photo_id'";
	  executeupdate($sql);
	  
	  break;
	}
	
	case "sdsignature":{
	  $leak_id = go_escape_string($_POST['leak_id']);
	  $signature_name = go_escape_string($_POST['signature_name']);
	  
	  $sql = "UPDATE am_leakcheck set signature_name='$signature_name', signature_date=now() where leak_id='$leak_id'";
	  executeupdate($sql);
	  
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	
	   $ext = explode(".", $_FILES['photo']['name']);
  	   $ext = array_pop($ext);
  	   $filename = uniqueTimeStamp() . "." . $ext;
	   move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	   resizeimage("uploaded_files/temp/$filename", "uploaded_files/signatures/", 200);
  	   @unlink("uploaded_files/temp/". $filename);
	
	   $sql = "UPDATE am_leakcheck set signature_image='$filename', signature_date=now() where leak_id='$leak_id'";
	   executeupdate($sql);
	   $sql = "UPDATE document_proposal_fcs set signature_image='$filename', sign_date=now() where leak_id='$leak_id'";
	   executeupdate($sql);
      }
	  echo "id: $leak_id<br>";
	  echo "error: " . $_FILES['photo']['error'];
	  break;
	}
	
	case "sdapproval":{
	  $leak_id = go_escape_string($_POST['leak_id']);
	  $user_id = go_escape_string($_POST['user_id']);
	  
	  $sql = "UPDATE am_leakcheck set ready_for_approval=1, approval_response=0 where leak_id='$leak_id'";
	  executeupdate($sql);
	  
	  $sql = "SELECT require_sd_approval from users where user_id='$user_id'";
	  $require_sd_approval = getsingleresult($sql);
	  if($require_sd_approval==0){
	    $sql = "UPDATE am_leakcheck set approval_response='1', approval_reason=\"\", 
        ready_for_approval=0 where leak_id='$leak_id'";
        executeupdate($sql);
	  }
	  
	  
	  
	  break;
	  
	}
	
	case "sdemail":{
	  $leak_id = go_escape_string($_POST['leak_id']);
	  $email = $_POST['email_address'];
	  $message = $_POST['comments'];
	  
	  $user_id = go_escape_string($_POST['user_id']);
	  
	  //$sql = "SELECT servicemen_id from am_leakcheck where leak_id='$leak_id'";
	  //$user_id = getsingleresult($sql);
	  
	  $sql = "SELECT resource_id from am_leakcheck where leak_id='$leak_id'";
	  $resource_id = getsingleresult($sql);
	  if($resource_id != 0) $resource = "resource_";
	  
	  $mat_sub = 0;
	  $materials = "";
      $sql = "SELECT * from am_leakcheck_materials where leak_id='$leak_id'";
      $result = executequery($sql);
      while($record = go_fetch_array($result)){
       $qty = $record['quantity'];
       $cost = $record['cost'];
       $line = $qty * $cost;
       $mat_sub += $line;
	   $materials .= stripslashes($record['description']) . " (" . $qty . ") @ $" . number_format($cost, 2) . " " . $record['units'] . " = " . number_format($line, 2) . "\n";
      }
	  $materials = go_escape_string($materials);
	  $sql = "UPDATE am_leakcheck set " . $resource . "extra_cost='$mat_sub', " . $resource . "materials=\"$materials\", " . $resource . "invoice_total=" . $resource . "invoice_total + $mat_sub, 
	  " . $resource . "materials_cost='$mat_sub' where leak_id='$leak_id'";
	  executeupdate($sql);
      
	  
	  // something about getting the pdf?
	  // have to generate a new one.
	  
	  $sql = "SELECT property_id, section_id from am_leakcheck where leak_id='$leak_id'";
	  $result = executequery($sql);
	  $record = go_fetch_array($result);
	  $property_id = $record['property_id'];
	  $section_id = $record['section_id'];
	  
	  $sql = "SELECT id, send_unsigned from document_proposal_fcs where property_id='$property_id' order by id desc limit 1";
	  $result = executequery($sql);
	  $record = go_fetch_array($result);
	  $doc_id = $record['id'];
	  $send_unsigned = $record['send_unsigned'];
	  
	  if($send_unsigned==1){
	    $filename = "PROPOSAL_UNSIGNED_" . $doc_id . ".pdf";
		$urlscript = "public_proposal_fcs_pdf_unsigned.php";
		$opp_status = "Quoted";
	  }
	  else {
	    $filename = "PROPOSAL_SIGNED_" . $doc_id . ".pdf";
		$urlscript = "public_proposal_fcs_pdf_signed.php";
	    $sql = "UPDATE document_proposal_fcs set sign_date=now(), email=\"$email\" where id='$doc_id'";
	    executeupdate($sql);
		$opp_status = "Sold";
	  }
	  
	  $sql = "SELECT email from users where user_id='$user_id'";
	  $bcc_email = getsingleresult($sql);
	  $email_from = $bcc_email;
	  
	  $sql = "SELECT master_id from users where user_id='$user_id'";
	  $master_id = getsingleresult($sql);
	  /*
	  $sql = "SELECT id from toolbox_items where tool_master_id=3 and master_id='$master_id'";
	  $tool_id = getsingleresult($sql);
	  */
	  $sql = "SELECT email from users where master_id='$master_id' and gets_sdemail=1 and user_id != '$user_id' and enabled=1";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $bcc_email .= "," . stripslashes($record['email']);
	  }
	  
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $CORE_URL . $urlscript . "?pdf_output=F&doc_id=$doc_id&iphone=54321");
      curl_setopt($ch, CURLOPT_HEADER, 0);

      // grab URL and pass it to the browser
      curl_exec($ch);

      // close cURL resource, and free up system resources
      curl_close($ch);
	  
	  if($email != ""){
	    require_once 'mail_attachment.php';
	    //$headers = "Content-type: text/html; charset=iso-8859-1\n";
        $headers = "From: $email_from\n";
        $headers .= "Return-Path: $email_from";  // necessary for some emails such as aol
	    if($bcc_email != "") $headers .= "\nBcc: $bcc_email";
	  
	    //email_q($email, "Service Dispatch #" . $leak_id, $message, $headers);
	    email_q($email, "Service Dispatch Proposal", $message, $headers, 'uploaded_files/proposals/' . $filename);
	  }
	  $message = go_escape_string($message);
	  
	  @copy("uploaded_files/proposals/$filename", "uploaded_files/attachments/$filename");
	  $sql = "SELECT prospect_id from properties where property_id='$property_id'";
	  $prospect_id = getsingleresult($sql);
	  $sql = "INSERT into notes(prospect_id, property_id, date, event, regarding, note, attachment) values(
	  '$prospect_id', '$property_id', now(), 'Note', 'Service Dispatch Proposal', \"$message\", '$filename')";
	  executeupdate($sql);
	  
  

	  break;
	}
	
	case "sdproposal":{
	  $leak_id = go_escape_string($_POST['leak_id']);
	  
	  $checked = $_POST['checked'];
	  
	  $array = explode(",", $checked);
	  
	  if($leak_id != ""){
	    $sql = "SELECT section_id, property_id, servicemen_id from am_leakcheck where
	    leak_id='$leak_id'";
	    $result = executequery($sql);
	    $record = go_fetch_array($result);
	    $section_id = $record['section_id'];
	    $property_id = $record['property_id'];
		$servicemen_id = $record['servicemen_id'];
	    //$user_id=176; // our am ghost login
		$send_unsigned=1;
		if($checked != "") $send_unsigned=0;
	  
	    $sql = "INSERT into document_proposal_fcs(property_id, multisection, intro_credit, send_unsigned, proposal_date, user_id, leak_id) values(
	    '$property_id', '$section_id', 0, '$send_unsigned', now(), '$servicemen_id', '$leak_id')";
	    executeupdate($sql);
		$sql = "SELECT id from document_proposal_fcs where property_id='$property_id' order by id desc limit 1";
		$doc_id = getsingleresult($sql);
		
		$def_selected = "";
		$total = 0;
		for($x=0;$x<sizeof($array);$x++){
		  if($array[$x]=="") continue;
		  $def_id = str_replace(" ", "", $array[$x]);
		  
		  $def_selected .= "," . $def_id . ",";
		  $sql = "SELECT cost from sections_def where def_id='" . $def_id . "'";
		  $total += getsingleresult($sql);
		}
		$sql = "UPDATE document_proposal_fcs set def_selected='$def_selected', subtotal='$total', total='$total'";
		//if($signature_image != "") $sql .= ", signature_image='$signature_image'";
		$sql .= " where id='$doc_id'";
		executeupdate($sql);
		
		echo "id: $doc_id";
	  }
	  
	  break;
	  
	  
	}
	  
	
	case "sdnotify":{
	  $leak_id = go_escape_string($_POST['leak_id']);
	  $user_id = go_escape_string($_POST['user_id']);
	  
	  $sql = "SELECT master_id from users where user_id='$user_id'";
	  $master_id = getsingleresult($sql);
	  $sql = "SELECT date_format(invoice_due_date, \"%Y\") from am_leakcheck where leak_id='$leak_id'";
      $test = getsingleresult($sql);
      if($test=="0000"){
	    $sql = "SELECT prospect_id from am_leakcheck where leak_id='$leak_id'";
        $prospect_id = getsingleresult($sql);
        $sql = "SELECT payment_terms from prospects where prospect_id='$prospect_id'";
	    $payment_terms = remove_non_numeric(stripslashes(getsingleresult($sql)));
	    if($payment_terms==""){
          $sql = "SELECT payment_terms from master_list where master_id='" . $master_id . "'";
          $payment_terms = remove_non_numeric(stripslashes(getsingleresult($sql)));
	    }
	    if($payment_terms != ""){
	      $sql = "UPDATE am_leakcheck set invoice_due_date=date_add(confirm_date, interval $payment_terms day) where leak_id='$leak_id'";
	      executeupdate($sql);
	    }
      }
	  
	  
	  $sql = "SELECT sum(total_hours) from am_leakcheck_time where leak_id='$leak_id' and travel=0";
      $labor_time = getsingleresult($sql);
      $sql = "SELECT sum(total_hours) from am_leakcheck_time where leak_id='$leak_id' and travel=1";
      $travel_time = getsingleresult($sql);
	  
	  //if($travel_time > 0 && $travel_time < .5) $travel_time = .5;
	  //if($labor_time > 0 && $labor_time < .5) $labor_time = .5;
	  $labor_time = round($labor_time, 2);
	  $travel_time = round($travel_time, 2);
	  
	  $sql = "UPDATE am_leakcheck set labor_time='$labor_time', travel_time='$travel_time' where leak_id='$leak_id'";
	  executeupdate($sql);
	  
	  $sql = "SELECT b.site_name, b.city, b.state, a.priority, a.eta_message, a.status, a.invoice_type, a.property_id, a.prospect_id
	  from am_leakcheck a, properties b where a.property_id=b.property_id 
      and a.leak_id='$leak_id'";
      $result = executequery($sql);
      $record = go_fetch_array($result);
      $site_name = stripslashes($record['site_name']);
      $city = stripslashes($record['city']);
      $state = stripslashes($record['state']);
      $priority = stripslashes($record['priority']);
      $eta_message = stripslashes($record['eta_message']);
	  $status = stripslashes($record['status']);
	  $invoice_type = stripslashes($record['invoice_type']);
	  $property_id = stripslashes($record['property_id']);
	  $prospect_id = stripslashes($record['prospect_id']);
	  
	  $sql = "SELECT b.master_name, b.dispatch_from_email, b.master_id from prospects a, master_list b where a.master_id=b.master_id 
	  and a.prospect_id='$prospect_id'";
	  $result = executequery($sql);
      $record = go_fetch_array($result);
      $master_name = stripslashes($record['master_name']);
	  $dispatch_email_from = stripslashes($record['dispatch_from_email']);
	  $master_id = stripslashes($record['master_id']);
  
      $subject = "Service Dispatch $status";
	  $message .= "$site_name<br>$city, $state<br>Dispatch ID: $leak_id<br>Priority: $priority";
  
      $mail_from_email = $dispatch_email_from;
      $mail_from_name = $master_name;
  
  
      include "sd_email.php";
      $sql = "SELECT body from templates where name='Leakcheck - General'";
      $body = stripslashes(getsingleresult($sql));
      $body = str_replace("[MESSAGE]", $table, $body);
  

      $headers = "Content-type: text/html; charset=iso-8859-1\n";
      $headers .= "From: $mail_from_name <$mail_from_email>\n";
      $headers .= "Return-Path: $mail_from_email\n";  // necessary for some emails such as aol
	  $headers_fcs = $headers . "Bcc: $fcs_email\n";
      $headers_ro = $headers . "Bcc: $ro_email\n";
  
      if($fcs_email != "") email_q("", $subject, $body, $headers_fcs);
  
      $ro_body = $body;
      if($status=="Arrival ETA") $ro_body .= "<br><br>Internal ETA Notes:<br>" . nl2br($eta_message);
      $ro_body .= "<br>This message sent to following $MAIN_CO_NAME view users: $fcs_email";
      if($ro_email != "") email_q("", $subject, $ro_body, $headers_ro);
  


	  break;
    }
	
		
	case "sdinvoice":{
	  $leak_id = go_escape_string($_POST['leak_id']);
	  $email = $_POST['email_address'];
	  $message = $_POST['comments'];
	  
	  $user_id = go_escape_string($_POST['user_id']);
	  

  
	  $sql = "SELECT email from users where user_id='$user_id'";
	  $bcc_email = getsingleresult($sql);
	  $email_from = $bcc_email;
	  
	  $sql = "SELECT master_id from users where user_id='$user_id'";
	  $master_id = getsingleresult($sql);
	  /*
	  $sql = "SELECT id from toolbox_items where tool_master_id=3 and master_id='$master_id'";
	  $tool_id = getsingleresult($sql);
	  */
	  $sql = "SELECT email from users where master_id='$master_id' and gets_sdemail=1 and user_id != '$user_id' and enabled=1";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $bcc_email .= "," . stripslashes($record['email']);
	  }
	  
      
	  $sql = "SELECT invoice_type from master_list where master_id='$master_id'";
	  $invoice_type = getsingleresult($sql);
	  $special_invoice = "";
	  if($invoice_type==2) $special_invoice = 2;
  
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $CORE_URL . "fcs_sd_invoice_pdf" . $special_invoice . ".php?leak_id=$leak_id&iphone=54321&pdf_output=F");
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_exec($ch);
      curl_close($ch);
	  
      $sql = "SELECT invoice_filename from am_leakcheck where leak_id='$leak_id'";
      $invoice_filename = getsingleresult($sql);


	  
	  if($email != ""){
	    //$headers = "Content-type: text/html; charset=iso-8859-1\n";
        $headers = "From: $email_from\n";
        $headers .= "Return-Path: $email_from";  // necessary for some emails such as aol
	    if($bcc_email != "") $headers .= "\nBcc: $bcc_email";
	  
	    //email_q($email, "Service Dispatch #" . $leak_id, $message, $headers);
	    email_q($email, "Proposal", $message, $headers, "uploaded_files/download/" . $invoice_filename);
	  }
	  else {
	    echo "url:" . $CORE_URL . "uploaded_files/download/" . $invoice_filename;
	  }

	  break;
	}
	
	case "clearpropertyimages":{
	  $property_id = go_escape_string($_POST['property_id']);
	  
	  $sql = "SELECT image from properties_images where property_id='$property_id'";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $image = $record['image'];
		if($image != "") @unlink("uploaded_files/properties/$image");
	  }
	  $sql = "DELETE from properties_images where property_id='$property_id'";
	  executeupdate($sql);
	  break;
	}
	  
	case "businesscard":{
	  //$prospect_id = go_escape_string($_POST['prospect_id']);
	  $user_id = go_escape_string($_POST['user_id']);
	  $company_name = go_escape_string($_POST['company_name']);
	  $site_name = go_escape_string($_POST['property_name']);
	  $company_address = go_escape_string($_POST['company_address']);
	  $company_city = go_escape_string($_POST['company_city']);
	  $company_state = go_escape_string($_POST['company_state']);
	  $company_zip = go_escape_string($_POST['company_zip']);
	  
	  $property_address = go_escape_string($_POST['property_address']);
	  $property_city = go_escape_string($_POST['property_city']);
	  $property_state = go_escape_string($_POST['property_state']);
	  $property_zip = go_escape_string($_POST['property_zip']);
	  
	  $company_firstname = go_escape_string($_POST['company_firstname']);
	  $company_lastname = go_escape_string($_POST['company_lastname']);
	  $company_phone = go_escape_string($_POST['company_phone']);
	  $company_email = go_escape_string($_POST['company_email']);
	  
	  $property_firstname = go_escape_string($_POST['property_firstname']);
	  $property_lastname = go_escape_string($_POST['property_lastname']);
	  $property_phone = go_escape_string($_POST['property_phone']);
	  $property_email = go_escape_string($_POST['property_email']);
	  
	  $schedule_activity = go_escape_string($_POST['schedule_activity']);
	  if($schedule_activity != 1) $schedule_activity = 0;
	  
	  $event = "Inspection"; // we may be getting this from app in future.
	  $regarding = $event;
	  
	  if($site_name=="") $site_name = $company_name;
	  
	  $sql = "SELECT master_id from users where user_id='$user_id'";
	  $master_id = getsingleresult($sql);
	  
	  $sql = "INSERT into prospects(company_name, address, city, state, zip, user_id, property_type, 
	  master_id, industry) values (
	  \"$company_name\", \"$company_address\", \"$company_city\", \"$company_state\", \"$company_zip\", '$user_id', \"Non-PFRI\",
	  '$master_id', '0')";
	  executeupdate($sql);
	  $prospect_id = go_insert_id();
	  // add corporate property
	
	  $sql = "INSERT into properties (prospect_id, site_name, corporate, address, city, state, zip) values(
	  '$prospect_id', \"$company_name Corporate\", 1, \"$address\", \"$city\", \"$state\", \"$zip\")";
	  executeupdate($sql);
	  
	  $sql = "INSERT into properties (prospect_id, site_name, corporate, address, city, state, zip) values(
	  '$prospect_id', \"$site_name\", 0, \"$property_address\", \"$property_city\", \"$property_state\", \"$property_zip\")";
	  executeupdate($sql);
	  
	  $property_id = go_insert_id();
	  
	
	  $sql = "INSERT into contacts(prospect_id, firstname, lastname, phone, email) values('$prospect_id', \"$company_firstname\", 
	  \"$company_lastname\", \"$company_phone\", \"$company_email\")";
	  executeupdate($sql);
	  $id1 = go_insert_id();
	  
	  $sql = "INSERT into contacts(property_id, firstname, lastname, phone, email) values('$property_id', \"$property_firstname\", 
	  \"$property_lastname\", \"$property_phone\", \"$property_email\")";
	  executeupdate($sql);
	  $id2 = go_insert_id();
	  
	
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	
	   $ext = explode(".", $_FILES['photo']['name']);
  	   $ext = array_pop($ext);
  	   $filename = uniqueTimeStamp() . "." . $ext;
	   move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/businesscards/". $filename);
	
	   $sql = "UPDATE prospects set businesscard='$filename' where prospect_id='$prospect_id'";
	   executeupdate($sql);
	   
	   $sql = "UPDATE contacts set businesscard='$filename' where id='$id1'";
	   executeupdate($sql);
	   $sql = "UPDATE contacts set businesscard='$filename' where id='$id2'";
	   executeupdate($sql);
	   
      }
	  
	  if (is_uploaded_file($_FILES['logo']['tmp_name']))
     {
	 if(is_image_valid($_FILES['logo']['name'])){
	
	   $ext = explode(".", $_FILES['logo']['name']);
  	   $ext = array_pop($ext);
  	   $filename = uniqueTimeStamp() . "." . $ext;
	   move_uploaded_file($_FILES['logo']['tmp_name'], "uploaded_files/temp/". $filename);
       resizeimage("uploaded_files/temp/$filename", "uploaded_files/logos/", 150);
  	   @unlink("uploaded_files/temp/". $filename);
	
	   $sql = "UPDATE prospects set logo='$filename' where prospect_id='" . $prospect_id . "'";
	   executeupdate($sql);
     }
	 }
	  
	  if($schedule_activity==1){
	    $sql = "INSERT into activities(prospect_id, property_id, user_id, date, event, contact, regarding, scheduled_by) values (
	    '$prospect_id', '$property_id', '$user_id', now(), '$event', \"$firstname $lastname\", \"$regarding\", '$user_id')";
	    executeupdate($sql);
	    $act_id = go_insert_id();
	  
	    if($event=="Bid Presentation" || $event == "Inspection" || $event=="Risk Report Presentation" || $event == "FCS Meetings" || $event == "Calendar - Other" || $event == "Project Close-Out" || $event=="Meeting" || $event=="Contact" || $event=="Quickbid"){
  
          if($master_id != 1){
	        $sql = "UPDATE prospects set has_act=1 where prospect_id='$prospect_id'";
            executeupdate($sql);
	      }
          switch($event){
            case "Bid Presentation":{
	          $what = "BP";
	          $category_id = 22;
	          $description = $BP;
	          break;
	        }
	        case "Inspection":{
	          $what = "I";
	          $category_id = 34;
	          $description = $I;
	          // when an inspection is scheduled, final approval should be zero - 4/6/11 JW
	          $sql = "UPDATE properties set final_approval=0, final_approval_reason='', pre_approval=0, pre_approval_reason='', ready_for_pre_approval=0, ready_for_email=0, text_alert_sent=0 
              where property_id='$property_id'";
	          executeupdate($sql);
	          break;
	        }
			case "Quickbid":{
	          $what = "Q";
	          $category_id = 38;
	          $description = "Quickbid";
	          // when an inspection is scheduled, final approval should be zero - 4/6/11 JW
	          $sql = "UPDATE properties set final_approval=0, final_approval_reason='', pre_approval=0, pre_approval_reason='', ready_for_pre_approval=0, ready_for_email=0, text_alert_sent=0 
              where property_id='$property_id'";
	          executeupdate($sql);
	          break;
	        }
	        case "Risk Report Presentation":{
	          $what = "RRP";
	          $category_id = 24;
	          $description = $RRP;
	          break;
	        }
	        case "FCS Meetings":{
	          $what = "UM";
	          $category_id = 25;
	          $description = $UM;
	          break;
	        }
	        case "Project Close-Out":{
	          $what = "PCO";
	          $category_id = 30;
	          $description = $PCO;
	          break;
	        }
	        case "Calendar - Other":{
	          $what = "O";
	          $category_id = 26;
	          $description = $regarding;
	          break;
	        }
	        case "Meeting":{
	          $category_id = 35;
	          $what = "M";
	          $description = $regarding;
	          break;
	        }
	        case "Contact":{
	          $category_id = 36;
	          $what = "C";
	          $description = $regarding;
	          break;
	        }
	
          }
  
  
          $test=1;
          if($master_id==1){
            $sql = "SELECT count(*) from activities where act_result='Objective Met' and property_id='$property_id'";
	        $test = getsingleresult($sql);
			$sql = "SELECT always_supercali from users where user_id='$user_id'";
	        $test = getsingleresult($sql);
          }
          if($test != 0){
            $sauce = md5(time());
            $sql = "INSERT into supercali_events (title, venue_id, contact_id, description, category_id, user_id, group_id, status_id, 
            quick_approve, prospect_id, property_id, what, value, ro_user_id, act_id, master_id) 
            values 
            (\"$event - $act_id\", '1', '1', \"$description\", '$category_id', '2', '1', '1', '".$sauce."', '".$prospect_id."', 
             '".$property_id."', '".$what."','".$bp_value."', '".$user_id."', '$act_id', '$master_id')";
            executeupdate($sql);
  
            $event_id = go_insert_id();
  
            $sql = "INSERT into supercali_dates(event_id, date, end_date) values ('$event_id', now(), now())";
            executeupdate($sql);
          } // end if test is not zero
        } // end if a supercali event type
	  
	    echo "id: $act_id";
	  } // end if schedule activity is checked
	  else {
	    echo "property id: $property_id";
	  }
	  
	  break;
	}


   case "editcompanyinfo":{
     $user_id = go_escape_string($_POST['user_id']);
	 $master_name = go_escape_string($_POST['master_name']);
     $dispatch_from_email = go_escape_string($_POST['dispatch_from_email']);
     $address = go_escape_string($_POST['address']);
     $city = go_escape_string($_POST['city']);
     $state = go_escape_string($_POST['state']);
     $zip = go_escape_string($_POST['zip']);
     $invoice_contact = go_escape_string($_POST['invoice_contact']);
     $invoice_contact_number = go_escape_string($_POST['invoice_contact_number']);
     $phone = go_escape_string($_POST['phone']);
     $fax = go_escape_string($_POST['fax']);
	 
	 $sql = "SELECT master_id from users where user_id='$user_id'";
	 $master_id = getsingleresult($sql);
	 
	 $sql = "UPDATE master_list set dispatch_from_email=\"$dispatch_from_email\", address=\"$address\", city=\"$city\", state=\"$state\", 
     zip=\"$zip\", invoice_contact=\"$invoice_contact\", invoice_contact_number=\"$invoice_contact_number\", 
     phone=\"$phone\", fax=\"$fax\", master_name=\"$master_name\"
     where master_id='" . $master_id . "'";
     executeupdate($sql);
	 
	 if (is_uploaded_file($_FILES['logo']['tmp_name']))
     {
	 if(is_image_valid($_FILES['logo']['name'])){
	
	   $ext = explode(".", $_FILES['logo']['name']);
  	   $ext = array_pop($ext);
  	   $filename = uniqueTimeStamp() . "." . $ext;
	   move_uploaded_file($_FILES['logo']['tmp_name'], "uploaded_files/temp/". $filename);
       resizeimage("uploaded_files/temp/$filename", "uploaded_files/master_logos/", 400);
  	   @unlink("uploaded_files/temp/". $filename);
	
	   $sql = "UPDATE master_list set logo='$filename' where master_id='" . $master_id . "'";
	   executeupdate($sql);
     }
	 }
   
     break;
   }

    case "businesscard2":{
	  //$prospect_id = go_escape_string($_POST['prospect_id']);
	  $user_id = go_escape_string($_POST['user_id']);
	  $company_name = go_escape_string($_POST['company_name']);
	  $site_name = go_escape_string($_POST['property_name']);
	  $company_address = go_escape_string($_POST['company_address']);
	  $company_city = go_escape_string($_POST['company_city']);
	  $company_state = go_escape_string($_POST['company_state']);
	  $company_zip = go_escape_string($_POST['company_zip']);
	  
	  $property_address = go_escape_string($_POST['property_address']);
	  $property_city = go_escape_string($_POST['property_city']);
	  $property_state = go_escape_string($_POST['property_state']);
	  $property_zip = go_escape_string($_POST['property_zip']);
	  
	  $company_firstname = go_escape_string($_POST['company_firstname']);
	  $company_lastname = go_escape_string($_POST['company_lastname']);
	  $company_phone = go_escape_string($_POST['company_phone']);
	  $company_email = go_escape_string($_POST['company_email']);
	  
	  $property_firstname = go_escape_string($_POST['property_firstname']);
	  $property_lastname = go_escape_string($_POST['property_lastname']);
	  $property_phone = go_escape_string($_POST['property_phone']);
	  $property_email = go_escape_string($_POST['property_email']);
	  
	  
	  if($site_name=="") $site_name = $company_name;
	  
	  $sql = "SELECT master_id from users where user_id='$user_id'";
	  $master_id = getsingleresult($sql);
	  
	  $sql = "INSERT into prospects(company_name, address, city, state, zip, user_id, property_type, 
	  master_id, industry) values (
	  \"$company_name\", \"$company_address\", \"$company_city\", \"$company_state\", \"$company_zip\", '$user_id', \"Non-PFRI\",
	  '$master_id', '0')";
	  executeupdate($sql);
	  $prospect_id = go_insert_id();
	  // add corporate property
	
	  $sql = "INSERT into properties (prospect_id, site_name, corporate, address, city, state, zip) values(
	  '$prospect_id', \"$company_name Corporate\", 1, \"$property_address\", \"$property_city\", \"$property_state\", \"$property_zip\")";
	  executeupdate($sql);
	  
	  $sql = "INSERT into properties (prospect_id, site_name, corporate, address, city, state, zip) values(
	  '$prospect_id', \"$site_name\", 0, \"$property_address\", \"$property_city\", \"$property_state\", \"$property_zip\")";
	  executeupdate($sql);
	  
	  $property_id = go_insert_id();
	  
	
	  $sql = "INSERT into contacts(prospect_id, firstname, lastname, phone, email) values('$prospect_id', \"$company_firstname\", 
	  \"$company_lastname\", \"$company_phone\", \"$company_email\")";
	  executeupdate($sql);
	  $id1 = go_insert_id();
	  
	  $sql = "INSERT into contacts(property_id, firstname, lastname, phone, email) values('$property_id', \"$property_firstname\", 
	  \"$property_lastname\", \"$property_phone\", \"$property_email\")";
	  executeupdate($sql);
	  $id2 = go_insert_id();
	  
	
	  if (is_uploaded_file($_FILES['logo']['tmp_name']))
     {
	 if(is_image_valid($_FILES['logo']['name'])){
	
	   $ext = explode(".", $_FILES['logo']['name']);
  	   $ext = array_pop($ext);
  	   $filename = uniqueTimeStamp() . "." . $ext;
	   move_uploaded_file($_FILES['logo']['tmp_name'], "uploaded_files/temp/". $filename);
       resizeimage("uploaded_files/temp/$filename", "uploaded_files/master_logos/", 400);
  	   @unlink("uploaded_files/temp/". $filename);
	
	   $sql = "UPDATE master_list set logo='$filename' where master_id='" . $master_id . "'";
	   executeupdate($sql);
     }
	 }
	  
	  
	  break;
	}
	
	
	case "qbproposal":{
	  $user_id = go_escape_string($_POST['user_id']);
	  $property_id = go_escape_string($_POST['property_id']);
	  $section_id = go_escape_string($_POST['section_id']);
	  
	  $buy_now = go_escape_string($_POST['buy_now']);
	  $authorize_all = go_escape_string($_POST['authorize_all']);
	  $credit_card_pmt = go_escape_string($_POST['credit_card_pmt']);
	  
	  if($buy_now != 1) $buy_now = 0;
	  if($authorize_all != 1) $authorize_all = 0;
	  if($credit_card_pmt != 1) $credit_card_pmt = 0;
	  
	  $buy_now_value = go_escape_string($_POST['buy_now_value']);
	  $authorize_all_value = go_escape_string($_POST['authorize_all_value']);
	  $credit_card_pmt_value = go_escape_string($_POST['credit_card_pmt_value']);
	  
	  $payment_terms = go_escape_string($_POST['payment_terms']);
	  $maintenance_program = go_escape_string($_POST['maintenance_program']); //0=monthly, 1=quarterly, 2=bi-annually.
	  $program_cost = go_escape_string($_POST['program_cost']);
	  
	  $subtotal = go_escape_string($_POST['subtotal']);
	  $total = go_escape_string($_POST['total']);
	  $discount = go_escape_string($_POST['discount']);
	  
	  $maintenance_program_check = go_escape_string($_POST['maintenance_program_check']);
	  if($maintenance_program_check != 1) $maintenance_program_check = 0;
	  
	  $checked = $_POST['checked'];
	  
	  $array = explode(",", $checked);
	  

		$send_unsigned=1;
		if($checked != "") $send_unsigned=0;
	  
	    $sql = "INSERT into document_proposal_fcs(property_id, multisection, intro_credit, send_unsigned, proposal_date, user_id, 
		buy_now, authorize_all, credit_card_pmt, buy_now_value, authorize_all_value, credit_card_pmt_value, payment_terms, 
		maintenance_program, program_cost, discount, subtotal, total, maintenance_program_check) values(
	    '$property_id', '$section_id', 0, '$send_unsigned', now(), '$user_id', 
		\"$buy_now\", \"$authorize_all\", \"$credit_card_pmt\", \"$buy_now_value\", \"$authorize_all_value\", \"$credit_card_pmt_value\", \"$payment_terms\", 
		\"$maintenance_program\", \"$program_cost\", \"$discount\", \"$subtotal\", \"$total\", \"$maintenance_program_check\")";
	    executeupdate($sql);
		$doc_id = go_insert_id();
		
		$def_selected = "";
		$total = 0;
		for($x=0;$x<sizeof($array);$x++){
		  if($array[$x]=="") continue;
		  $def_id = str_replace(" ", "", $array[$x]);
		  
		  $def_selected .= "," . $def_id . ",";
		  $sql = "SELECT cost from sections_def where def_id='" . $def_id . "'";
		  $total += getsingleresult($sql);
		}
		$sql = "UPDATE document_proposal_fcs set def_selected='$def_selected'";
		//if($signature_image != "") $sql .= ", signature_image='$signature_image'";
		$sql .= " where id='$doc_id'";
		executeupdate($sql);
		
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	
	   $ext = explode(".", $_FILES['photo']['name']);
  	   $ext = array_pop($ext);
  	   $filename = uniqueTimeStamp() . "." . $ext;
	   move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	   resizeimage("uploaded_files/temp/$filename", "uploaded_files/signatures/", 200);
  	   @unlink("uploaded_files/temp/". $filename);
	
	   $sql = "UPDATE document_proposal_fcs set signature_image='$filename', sign_date=now() where id='$doc_id'";
	   executeupdate($sql);
      }
		
		echo "id: $doc_id";

	  
	  break;
	  
	  
	}
	
	case "qbemail":{
	  $doc_id = go_escape_string($_POST['doc_id']);
	  $email = $_POST['email_address'];
	  $message = $_POST['comments'];
	  
	  $user_id = go_escape_string($_POST['user_id']);
	  
	  
	  
	  $sql = "SELECT property_id, multisection, send_unsigned, total from document_proposal_fcs where id='$doc_id'";
	  $result = executequery($sql);
	  $record = go_fetch_array($result);
	  $send_unsigned = $record['send_unsigned'];
	  $property_id = $record['property_id'];
	  $section_id = $record['multisection'];
	  $total = $record['total'];
	  
	  if($send_unsigned==1){
	    $filename = "QUICKBID_UNSIGNED_" . $doc_id . ".pdf";
		$urlscript = "public_quickbid_pdf_unsigned.php";
		$opp_status = "Quoted";
	  }
	  else {
	    $filename = "QUICKBID_SIGNED_" . $doc_id . ".pdf";
		$urlscript = "public_quickbid_pdf_signed.php";
	    $sql = "UPDATE document_proposal_fcs set sign_date=now(), email=\"$email\" where id='$doc_id'";
	    executeupdate($sql);
		$opp_status = "Sold";
	  }
	  $opp_status = "Quoted";
	  $total = 0;
	  // all qwikbids are now quoted with zero dollar amount, per Will, Dennis, Jim 6/6/12 JW
	  
	  $sql = "SELECT email from users where user_id='$user_id'";
	  $bcc_email = getsingleresult($sql);
	  $email_from = $bcc_email;
	  
	  $sql = "SELECT master_id from users where user_id='$user_id'";
	  $master_id = getsingleresult($sql);
	  $sql = "SELECT id from toolbox_items where tool_master_id=3 and master_id='$master_id'";
	  $tool_id = getsingleresult($sql);
	  $sql = "SELECT email from users where master_id='$master_id' and tools_available like '%," . $tool_id . ",%' and user_id != '$user_id' and enabled=1";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $bcc_email .= "," . stripslashes($record['email']);
	  }
	  
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $CORE_URL . $urlscript . "?pdf_output=F&doc_id=$doc_id&iphone=54321");
      curl_setopt($ch, CURLOPT_HEADER, 0);

      // grab URL and pass it to the browser
      curl_exec($ch);

      // close cURL resource, and free up system resources
      curl_close($ch);
	  if($email != ""){
	    //require_once 'mail_attachment.php';
	    //$headers = "Content-type: text/html; charset=iso-8859-1\n";
        $headers = "From: $email_from\n";
        $headers .= "Return-Path: $email_from";  // necessary for some emails such as aol
	    if($bcc_email != "") $headers .= "\nBcc: $bcc_email";
	  
	    email_q($email, "Quickbid Proposal", $message, $headers, 'uploaded_files/proposals/' . $filename);
	  }
	  
	  $message = go_escape_string($message);
	  
	  @copy("uploaded_files/proposals/$filename", "uploaded_files/attachments/$filename");
	  $sql = "SELECT prospect_id from properties where property_id='$property_id'";
	  $prospect_id = getsingleresult($sql);
	  $sql = "INSERT into notes(prospect_id, property_id, date, event, regarding, note, attachment) values(
	  '$prospect_id', '$property_id', now(), 'Note', 'Quickbid Proposal', \"$message\", '$filename')";
	  executeupdate($sql);
	  
	  $sql = "INSERT into opportunities(user_id, scheduled_by, prospect_id, property_id, status, product, opp_product_id, amount, lastaction) values(
	  '$user_id', '$user_id', '$prospect_id', '$property_id', '$opp_status', 'Qwikbid $doc_id', -4, $total, now())";
	  executeupdate($sql);
	  
	  $sql = "UPDATE supercali_events set complete=1 where what='Q' and property_id='$property_id' and ro_user_id='$user_id'";
	  executeupdate($sql);
	  $sql = "UPDATE activities set complete=1, complete_date=now() where property_id='$property_id' and event='Qwikbid' and complete=0";
	  executeupdate($sql);
	  
	  // add a followup activity
	  $scheduled_by = $user_id;
	  $sql = "INSERT into activities(prospect_id, property_id, date, event, scheduled_by, user_id, regarding) values('$prospect_id', '$property_id', now(), 'To Do',
	  '$scheduled_by', '$scheduled_by', 'Schedule a Follow Up')";
	  executeupdate($sql);
	  $act_id = go_insert_id();
	  $sauce = md5(time());
	  $category_id = 40;
	  $what = "TD";
	  $sql = "SELECT master_id from prospects where prospect_id='$prospect_id'";
	  $master_id = getsingleresult($sql);
	  
      $sql = "INSERT into supercali_events (title, venue_id, contact_id, description, category_id, user_id, group_id, status_id, 
      quick_approve, prospect_id, property_id, what, value, ro_user_id, act_id, master_id) 
      values 
      (\"To Do - $act_id\", '1', '1', \"Schedule a Follow Up\", '$category_id', '2', '1', '1', '".$sauce."', '".$prospect_id."', 
       '".$property_id."', '".$what."','".$bp_value."', '".$scheduled_by."', '$act_id', '" . $master_id . "')";
      executeupdate($sql);
  
      $event_id = go_insert_id();
  
      $sql = "INSERT into supercali_dates(event_id, date, end_date) values ('$event_id', now(), now())";
      executeupdate($sql);
	  
	  

	  break;
	}
	
	case "qbapproval":{
	  $doc_id = go_escape_string($_POST['doc_id']);
	  
	  $sql = "UPDATE document_proposal_fcs set ready_for_approval=1, approval_response=0 where id='$doc_id'";
	  executeupdate($sql);
	  
	  break;
	  
	}
	
	case "addschedule":{
	  $user_id = go_escape_string($_POST['user_id']);
	  $property_id = go_escape_string($_POST['property_id']);
	  $event = go_escape_string($_POST['type']);
	  
	  $sql = "SELECT prospect_id from properties where property_id='$property_id'";
	  $prospect_id = getsingleresult($sql);
	  
	  $sql = "SELECT master_id from users where user_id='$user_id'";
	  $master_id = getsingleresult($sql);
	  
	  $schedule_activity = 1;
	  
	  if($schedule_activity==1){
	    $sql = "INSERT into activities(prospect_id, property_id, user_id, date, event, contact, regarding, scheduled_by) values (
	    '$prospect_id', '$property_id', '$user_id', now(), '$event', \"$firstname $lastname\", \"$regarding\", '$user_id')";
	    executeupdate($sql);
	    $act_id = go_insert_id();
	  
	    if($event=="Bid Presentation" || $event == "Inspection" || $event=="Risk Report Presentation" || $event == "FCS Meetings" || $event == "Calendar - Other" || $event == "Project Close-Out" || $event=="Meeting" || $event=="Contact" || $event=="Quickbid"){
  
          if($master_id != 1){
	        $sql = "UPDATE prospects set has_act=1 where prospect_id='$prospect_id'";
            executeupdate($sql);
	      }
          switch($event){
            case "Bid Presentation":{
	          $what = "BP";
	          $category_id = 22;
	          $description = $BP;
	          break;
	        }
	        case "Inspection":{
	          $what = "I";
	          $category_id = 34;
	          $description = $I;
	          // when an inspection is scheduled, final approval should be zero - 4/6/11 JW
	          $sql = "UPDATE properties set final_approval=0, final_approval_reason='', pre_approval=0, pre_approval_reason='', ready_for_pre_approval=0, ready_for_email=0, text_alert_sent=0 
              where property_id='$property_id'";
	          executeupdate($sql);
	          break;
	        }
			case "Quickbid":{
	          $what = "Q";
	          $category_id = 38;
	          $description = "Quickbid";
	          // when an inspection is scheduled, final approval should be zero - 4/6/11 JW
	          $sql = "UPDATE properties set final_approval=0, final_approval_reason='', pre_approval=0, pre_approval_reason='', ready_for_pre_approval=0, ready_for_email=0, text_alert_sent=0 
              where property_id='$property_id'";
	          executeupdate($sql);
	          break;
	        }
	        case "Risk Report Presentation":{
	          $what = "RRP";
	          $category_id = 24;
	          $description = $RRP;
	          break;
	        }
	        case "FCS Meetings":{
	          $what = "UM";
	          $category_id = 25;
	          $description = $UM;
	          break;
	        }
	        case "Project Close-Out":{
	          $what = "PCO";
	          $category_id = 30;
	          $description = $PCO;
	          break;
	        }
	        case "Calendar - Other":{
	          $what = "O";
	          $category_id = 26;
	          $description = $regarding;
	          break;
	        }
	        case "Meeting":{
	          $category_id = 35;
	          $what = "M";
	          $description = $regarding;
	          break;
	        }
	        case "Contact":{
	          $category_id = 36;
	          $what = "C";
	          $description = $regarding;
	          break;
	        }
	
          }
  
  
          $test=1;
          if($master_id==1){
            $sql = "SELECT count(*) from activities where act_result='Objective Met' and property_id='$property_id'";
	        $test = getsingleresult($sql);
			$sql = "SELECT always_supercali from users where user_id='$user_id'";
	        $test = getsingleresult($sql);
          }
          if($test != 0){
            $sauce = md5(time());
            $sql = "INSERT into supercali_events (title, venue_id, contact_id, description, category_id, user_id, group_id, status_id, 
            quick_approve, prospect_id, property_id, what, value, ro_user_id, act_id, master_id) 
            values 
            (\"$event - $act_id\", '1', '1', \"$description\", '$category_id', '2', '1', '1', '".$sauce."', '".$prospect_id."', 
             '".$property_id."', '".$what."','".$bp_value."', '".$user_id."', '$act_id', '$master_id')";
            executeupdate($sql);
  
            $event_id = go_insert_id();
  
            $sql = "INSERT into supercali_dates(event_id, date, end_date) values ('$event_id', now(), now())";
            executeupdate($sql);
          } // end if test is not zero
        } // end if a supercali event type
	  
	    echo "id: $act_id";
	  } // end if schedule activity is checked
	  break;
	}
	
	
	case "add_opm_entry_photo":{
	  $opm_entry_id = go_escape_string($_POST['opm_entry_id']);
	  $description = go_escape_string($_POST['description']);
	  
	  $sql = "INSERT into opm_entry_photos(opm_entry_id, description) values(\"$opm_entry_id\", \"$description\")";
	  executeupdate($sql);
	  $photo_id = go_insert_id();
	  
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	
	    $ext = explode(".", $_FILES['photo']['name']);
  	    $ext = array_pop($ext);
  	    $filename = uniqueTimeStamp() . "." . $ext;
	    move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	    resizeimage("uploaded_files/temp/$filename", "uploaded_files/opm_photos/", 640);
	
	    $sql = "UPDATE opm_entry_photos set photo='$filename' where photo_id='$photo_id'";
	    executeupdate($sql);
      }
	  echo "id: $photo_id<br>";
	  echo "error: " . $_FILES['photo']['error'];
	  break;
	}
	
	case "edit_opm_entry_photo":{
	  $photo_id = go_escape_string($_POST['photo_id']);
	  
	  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	
	    $ext = explode(".", $_FILES['photo']['name']);
  	    $ext = array_pop($ext);
  	    $filename = uniqueTimeStamp() . "." . $ext;
	    move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	    resizeimage("uploaded_files/temp/$filename", "uploaded_files/opm_photos/", 640);
	
	    $sql = "UPDATE opm_entry_photos set photo='$filename' where photo_id='$photo_id'";
	    executeupdate($sql);
      }
	  echo "id: $photo_id<br>";
	  echo "error: " . $_FILES['photo']['error'];
	  break;
	}
	
	case "delete_opm_entry_photo":{
	  $photo_id = go_escape_string($_POST['photo_id']);
	  
	  $sql = "SELECT photo from opm_entry_photos where photo_id='$photo_id'";
	  $photo = stripslashes(getsingleresult($sql));
	  if($photo != "") @unlink("uploaded_files/opm_photos/$photo");
	  $sql = "DELETE from opm_entry_photos where photo_id='$photo_id'";
	  executeupdate($sql);

	  break;
	}
	
  }
  
 

?>
