<?php
include "includes/functions.php";

$leak_id = $_GET['leak_id'];

//$_SESSION['temp_redirect'] = "fcs_sd_report_view.php?leak_id=$leak_id";
// with the new resource stuff, I don't really see the point in auto redirecting back to the dispatch report.  You would still need to add users and such

meta_redirect("prospect_edit.php?resource=1", 0, 1);
?>