<?php
include "includes/functions.php";

$video_id = $_GET['video_id'];

$sql = "DELETE from videos_embed where video_id='$video_id'";
executeupdate($sql);

meta_redirect("youtube.php");
?>
