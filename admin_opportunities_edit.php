<?php
include "includes/functions.php";

$opp_product_id = $_GET['opp_product_id'];

if($opp_product_id != "new"){
  $sql = "SELECT * from opportunities_items where opp_product_id='$opp_product_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $opp_name = stripslashes($record['opp_name']);
  $opp_name = go_reg_replace("\"", "&quot;", $opp_name);
  $master_id = $record['master_id'];
  if($master_id != $SESSION_MASTER_ID) exit;
}

?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<script>
function checkform(f){
  var errmsg = "";
  if(f.opp_name.value==""){ errmsg += "Please enter a name for the product.\n";}
  
  if(errmsg==""){
    return true;
  }
  else {
    alert(errmsg);
	return false;
  }
}
</script>
<div class="main">
<form action="admin_opportunities_edit_action.php" method="post" name="form1" onsubmit="return checkform(this)">
<input type="hidden" name="opp_product_id" value="<?=$opp_product_id?>">
Product Name:
<input type="text" name="opp_name" value="<?=$opp_name?>">
<br>
<input type="submit" name="submit1" value="Update">
</form>
</div>