<?php include "includes/functions.php"; ?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">

<?php
$video_id = $_GET['video_id'];
$sql = "SELECT * from videos_embed where video_id='$video_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$video_code = stripslashes($record['video_code']);
$embed_code = stripslashes($record['embed_code']);
$page = stripslashes($record['page']);

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
?>

<div class="main">
Link has been created: <?=$url?><br><br>
This link is also recorded in properties Notes/History tab.
<br><br>
<?=$embed_code?>

<br><br>
<a href="youtube.php">Back to videos</a>
</div>