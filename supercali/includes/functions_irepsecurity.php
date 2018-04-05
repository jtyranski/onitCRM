<?php
if($_SESSION['ro_irep'] != ""){

  if($_GET['property_id'] != "" && $_GET['property_id'] != "new"){
    $sql = "SELECT account_manager from properties where property_id='" . $_GET['property_id'] . "'";
	$test = getsingleresult($sql);
	if($test != $_SESSION['ro_irep']) exit;
  }
  
  if($_POST['property_id'] != "" && $_POST['property_id'] != "new"){
    $sql = "SELECT account_manager from properties where property_id='" . $_POST['property_id'] . "'";
	$test = getsingleresult($sql);
	if($test != $_SESSION['ro_irep']) exit;
  }
  
  if($_GET['prospect_id'] != "" && $_GET['prospect_id'] != "new"){
    $sql = "SELECT prospect_id from prospects where prospect_id = '" . $_GET['prospect_id'] . "' and account_manager like '%," . $_SESSION['ro_irep'] . ",%'";
	$test = getsingleresult($sql);
	if($test =="") exit;
  }
  
  if($_POST['prospect_id'] != "" && $_POST['prospect_id'] != "new"){
    $sql = "SELECT prospect_id from prospects where prospect_id = '" . $_POST['prospect_id'] . "' and account_manager like '%," . $_SESSION['ro_irep'] . ",%'";
	$test = getsingleresult($sql);
	if($test =="") exit;
  }
  
  if($_GET['section_id'] != "" && $_GET['section_id'] != "new"){
    $sql = "SELECT b.account_manager from sections a, properties b where a.property_id=b.property_id and 
	a.section_id='" . $_GET['section_id'] . "'";
	$test = getsingleresult($sql);
	if($test != $_SESSION['ro_irep']) exit;
  }
  
  if($_POST['section_id'] != "" && $_POST['section_id'] != "new"){
    $sql = "SELECT b.account_manager from sections a, properties b where a.property_id=b.property_id and 
	a.section_id='" . $_POST['section_id'] . "'";
	$test = getsingleresult($sql);
	if($test != $_SESSION['ro_irep']) exit;
  }
  
  if($_GET['bid_id'] != "" && $_GET['bid_id'] != "new"){
    $sql = "SELECT b.account_manager from sections_bidinfo a, properties b where a.property_id=b.property_id and 
	a.bid_id='" . $_GET['bid_id'] . "'";
	$test = getsingleresult($sql);
	if($test != $_SESSION['ro_irep']) exit;
  }
  
  if($_POST['bid_id'] != "" && $_POST['bid_id'] != "new"){
    $sql = "SELECT b.account_manager from sections_bidinfo a, properties b where a.property_id=b.property_id and 
	a.bid_id='" . $_POST['bid_id'] . "'";
	$test = getsingleresult($sql);
	if($test != $_SESSION['ro_irep']) exit;
  }
  
}
?>