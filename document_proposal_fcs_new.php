<?php include "includes/functions.php"; 
$section_id = $_GET['section_id'];
$property_id = $_GET['property_id'];
$user_id = $SESSION_USER_ID;
if($property_id == ""){
  $sql = "SELECT property_id from sections where section_id='$section_id'";
  $property_id = getsingleresult($sql);
}
//if($section_id=="") meta_redirect("welcome.php");
$multisection = $_GET['multisection'];
if($multisection=="") $multisection = $section_id;

$sql = "INSERT into document_proposal_fcs(section_id, user_id, intro_credit, property_id, multisection, proposal_date) values('$section_id', '$user_id', '0', '$property_id', '$multisection', now())";
executeupdate($sql);
$doc_id = go_insert_id();
meta_redirect("public_proposal_fcs.php?doc_id=$doc_id");
exit;

?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<form action="document_proposal_fcs_new_action.php" method="get">
<input type="hidden" name="section_id" value="<?=$section_id?>">
<input type="hidden" name="multisection" value="<?=$multisection?>">
<input type="hidden" name="property_id" value="<?=$property_id?>">
Please enter a value for Credit:<br>
$<input type="text" name="intro_credit" value="0">

<br><br>

<input type="submit" name="submit1" value="Generate Form">
</form>
<?php include "includes/footer.php"; ?>