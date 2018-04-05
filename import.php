<?php
include "includes/functions.php";
?>


<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<?php if($_SESSION['sess_msg'] != ""){?>
<div style="color:red;"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<div class="main">
<form action="import_action.php" method="post" enctype="multipart/form-data">
Company format: cust_id, company name, address, city, state, zip, website<br>
Please select the file for the company list:<br>
<input type="file" name="company">
<br><br>
Property format: loc_id, cust_id, site name, address, city, state, zip, roof size<br>
Please select the file for the property list:<br>
<input type="file" name="property">
<br><br>
<input type="submit" name="submit1" value="Import">
</form>
</div>
