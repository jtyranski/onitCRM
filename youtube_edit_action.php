<?php
include "includes/functions.php";

function genRandomString() {
    $length = 8;
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}

$video_id = go_escape_string($_POST['video_id']);
$prospect_id = go_escape_string($_POST['prospect_id']);
$master_id = go_escape_string($_POST['master_id']);
//$property_id = go_escape_string($_POST['property_id']);
$page = go_escape_string($_POST['page']);
$video_name = go_escape_string($_POST['video_name']);
$embed_code = go_escape_string($_POST['embed_code']);
$close_link = go_escape_string($_POST['close_link']);
$submit1 = go_escape_string($_POST['submit1']);

if($close_link != ""){
  if(!(go_reg("^http", $close_link))) $close_link = "http://" . $close_link;
}

if($submit1 != ""){
  if($video_id=="new"){
    $video_code = genRandomString();
	$sql = "SELECT count(*) from videos_embed where video_code='$video_code'";
	$test = getsingleresult($sql);
	while($test){
	  $video_code = genRandomString();
	  $sql = "SELECT count(*) from videos_embed where video_code='$video_code'";
	  $test = getsingleresult($sql);
	}
    $sql = "INSERT into videos_embed(prospect_id, video_code) values('$prospect_id', '$video_code')";
	executeupdate($sql);
	$video_id = go_insert_id();
  }
  

  
  if(!(go_reg("\?rel\=0", $embed_code))){
    $embed_code = str_replace("\" frameborder", "?rel=0\\\" frameborder", $embed_code);
  }
  
  $sql = "UPDATE videos_embed set master_id='$master_id', prospect_id='$prospect_id', property_id='$property_id', page=\"$page\", 
  video_name=\"$video_name\", embed_code=\"$embed_code\", close_link=\"$close_link\" 
  where video_id='$video_id'";
  //echo "<textarea rows='5' cols='90'>$sql</textarea>";
  //exit;
  executeupdate($sql);
  $sql = "SELECT video_code from videos_embed where video_id='$video_id'";
  $video_code = stripslashes(getsingleresult($sql));
  
  switch($page){
    case "FCS Control":{
	  $url = "<a href='http://www.fcscontrol.com/video/$video_code' target='_blank'>http://www.fcscontrol.com/video/$video_code</a>";
	  break;
	}
	case "EnciteGroup":{
	  $url = "<a href='http://www.encitegroup.com/" . $EMBED_VIDEO . "/$video_code' target='_blank'>http://www.encitegroup.com/" . $EMBED_VIDEO . "/$video_code</a>";
	  break;
	}
	default:{
	  $url = "<a href='http://www.encitegroup.com/" . $EMBED_VIDEO . "/$video_code' target='_blank'>http://www.encitegroup.com/" . $EMBED_VIDEO . "/$video_code</a>";
	  break;
	}
  }
  $note = "Video: $video_name<br>$url";
  
  $sql = "INSERT into notes(user_id, prospect_id, event, date, note, regarding) values(
  '" . $SESSION_USER_ID . "', '$prospect_id', 'Note', now(), \"$note\", 'YouTube link')";
  executeupdate($sql);
  
  
  meta_redirect("youtube_view.php?video_id=$video_id");
}
?>


