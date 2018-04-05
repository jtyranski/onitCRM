<?php
include "includes/functions.php";

$sql = "DELETE from sessions where lastaction < date_sub(now(), interval 15 minute)";
executeupdate($sql);


$sql = "DELETE from sessions_core where lastaction < date_sub(now(), interval 15 minute)";
executeupdate($sql);

$sql = "DELETE from sessions_map where lastaction < date_sub(now(), interval 15 minute)";
executeupdate($sql);
?>