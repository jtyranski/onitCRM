<?php

require_once "includes/functions.php";
$user_id=$SESSION_USER_ID;

$firstrecord = $_GET['firstrecord'];
$direction = $_GET['direction'];

if($direction==1){
  $nextrecord = $firstrecord + 5;
}
else {
  $nextrecord = $firstrecord - 5;
}

if($nextrecord < 0) {
  $sql = "SELECT count(a.prospect_id) 
  from prospects a, opportunities b where a.prospect_id=b.prospect_id and b.user_id='$user_id' 
  and b.status='Sold' and b.display=1 group by b.prospect_id";
  $result = executequery($sql);
  $test = mysql_num_rows($result);
  $nextrecord = (floor($test/5) * 5);
}


$sql = "SELECT count(a.prospect_id) 
from prospects a, opportunities b where a.prospect_id=b.prospect_id and b.user_id='$user_id' 
and b.status='Sold' and b.display=1 group by b.prospect_id limit $nextrecord, 5";
$result = executequery($sql);
$test = mysql_num_rows($result);
if($test==0) $nextrecord = 0;

$nexthtml = "";
$sql = "SELECT a.prospect_id, a.logo, a.company_name 
from prospects a, opportunities b where a.prospect_id=b.prospect_id and b.user_id='$user_id' 
and b.status='Sold' and b.display=1 group by b.prospect_id order by prospect_id limit $nextrecord, 5";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $company_name = stripslashes($record['company_name']);
  $company_name = go_reg_replace("\'", "", $company_name);
  $company_name = go_reg_replace("\"", "", $company_name);
  $company_name_sub = substr($company_name, 0, 18);
  $nexthtml .= "<div style=\\\"float:left;\\\">";
  $nexthtml .= "<a href='view_company.php?prospect_id=" . $record['prospect_id'] . "'>";
   if($record['logo'] != ""){
     
    //$nexthtml .= "<img src='" . $UPLOAD . "logos/" . $record['logo'] . "' border='0'>";
	$nexthtml .= "<img src='crop.php?x=0&y=0&w=64&h=65&src=" . $UPLOAD . "/logos/" . $record['logo'] . "'  onmouseover=\\\"return overlib('" . $company_name . "');\\\" onmouseout=\\\"return nd();\\\" border='0'>";
   } else {
    $nexthtml .= "<img src='images/fcs-button_off.png' onmouseover=\\\"return overlib('" . $company_name . "');\\\" onmouseout=\\\"return nd();\\\" border='0'>";
   }
   $nexthtml .= "</a>";
   $nexthtml .= "<br>" . $company_name_sub;
   $nexthtml .= "</div>";
   $nexthtml .= "<div style=\\\"float:left; width:40px;\\\">";
   $nexthtml .= "<img src='images/spacer.gif'>";
   $nexthtml .= "</div>";
	    
}

//$nexthtml = $nextrecord;
$rightarrow = ScrollingArrow("R", "index_company.php", $nextrecord);
$leftarrow = ScrollingArrow("L", "index_company.php", $nextrecord);
?>

div = document.getElementById('companies2');
div.innerHTML = "<?php echo $nexthtml; ?>";

div = document.getElementById('rightarrow');
div.innerHTML = "<?php echo $rightarrow; ?>";
div = document.getElementById('leftarrow');
div.innerHTML = "<?php echo $leftarrow; ?>";

slidedown("companies2");
slideup("companies1");
//pausecomp('500');
setTimeout("divswitch('companies')",600);
<?php //usleep(500000);?>
