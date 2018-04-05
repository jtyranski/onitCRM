<?php
      $sql = "SELECT word from capitalize where master_id='$master_id'";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $replace[] = $record['word'];
	  }
	  $sql = "SELECT section_id from sections where property_id='$property_id'";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $section_id = $record['section_id'];
		
		$sql = "SELECT photo_id, description from sections_interiorphotos where section_id='$section_id'";
		$res_photo = executequery($sql);
		while($photo = go_fetch_array($res_photo)){
		  $photo_id = $photo['photo_id'];
		  $description = stripslashes($photo['description']);
		  $description = capitalizer($replace, $description);
		  $description = go_escape_string($description);
		  $sql = "UPDATE sections_interiorphotos set description=\"$description\" where photo_id='$photo_id'";
		  executeupdate($sql);
		}
		
		$sql = "SELECT photo_id, description, comments from sections_photos where section_id='$section_id'";
		$res_photo = executequery($sql);
		while($photo = go_fetch_array($res_photo)){
		  $photo_id = $photo['photo_id'];
		  $description = stripslashes($photo['description']);
		  $description = capitalizer($replace, $description);
		  $description = go_escape_string($description);
		  $comments = stripslashes($photo['comments']);
		  $comments = capitalizer($replace, $comments);
		  $comments = go_escape_string($comments);
		  $sql = "UPDATE sections_photos set description=\"$description\", comments=\"$comments\" where photo_id='$photo_id'";
		  executeupdate($sql);
		}
		
		$sql = "SELECT cut_id, comments, name from sections_testcuts where section_id='$section_id'";
		$res_photo = executequery($sql);
		while($photo = go_fetch_array($res_photo)){
		  $cut_id = $photo['cut_id'];
		  $comments = stripslashes($photo['comments']);
		  $comments = capitalizer($replace, $comments);
		  $comments = go_escape_string($comments);
		  $name = stripslashes($photo['name']);
		  $name = capitalizer($replace, $name);
		  $name = go_escape_string($name);
		  $sql = "UPDATE sections_testcuts set comments=\"$comments\", name=\"$name\" where cut_id='$cut_id'";
		  executeupdate($sql);
		}
		
		$sql = "SELECT video_id, description from sections_videos where section_id='$section_id'";
		$res_photo = executequery($sql);
		while($photo = go_fetch_array($res_photo)){
		  $video_id = $photo['video_id'];
		  $description = stripslashes($photo['description']);
		  $description = capitalizer($replace, $description);
		  $description = go_escape_string($description);
		  $sql = "UPDATE sections_videos set description=\"$description\" where video_id='$video_id'";
		  executeupdate($sql);
		}
		
		$sql = "SELECT def_id, def, action from sections_def where section_id='$section_id'";
		$res_photo = executequery($sql);
		while($photo = go_fetch_array($res_photo)){
		  $def_id = $photo['def_id'];
		  $def = stripslashes($photo['def']);
		  $def = capitalizer($replace, $def);
		  $def = go_escape_string($def);
		  $action = stripslashes($photo['action']);
		  $action = capitalizer($replace, $action);
		  $action = go_escape_string($action);
		  $sql = "UPDATE sections_def set action=\"$action\", def=\"$def\" where def_id='$def_id'";
		  executeupdate($sql);
		}
		
		$sql = "SELECT recommendation_text from sections where section_id='$section_id'";
		$recommendation_text = stripslashes(getsingleresult($sql));
		$recommendation_text = capitalizer($replace, $recommendation_text);
		$recommendation_text = go_escape_string($recommendation_text);
		$sql = "UPDATE sections set recommendation_text=\"$recommendation_text\" where section_id='$section_id'";
		executeupdate($sql);
		
		$sql = "SELECT section_name from sections where section_id='$section_id'";
		$section_name = stripslashes(getsingleresult($sql));
		$section_name = capitalizer($replace, $section_name);
		$section_name = go_escape_string($section_name);
		$sql = "UPDATE sections set section_name=\"$section_name\" where section_id='$section_id'";
		executeupdate($sql);
		
	  }
?>