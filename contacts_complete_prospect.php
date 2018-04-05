<?php
include "includes/functions.php";
$prospect_id = $_GET['prospect_id'];

$html = "<iframe frameborder='0' src=\\\"prospecting.php?prospect_id=$prospect_id\\\" width='100%' height='760' style='border:none;'></iframe>";
//$html = "testing";
?>
div = document.getElementById('search_results');
div.innerHTML = "<?php echo $html; ?>";