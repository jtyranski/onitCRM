<?php
include "includes/functions.php";

$sd_results_per_page = go_escape_string($_POST['sd_results_per_page']);
$submit1 = go_escape_string($_POST['submit1']);

if($submit1 != ""){
  $sql = "UPDATE global_variables set sd_results_per_page='$sd_results_per_page'";
  executeupdate($sql);
}

meta_redirect("admin_index.php");
?>