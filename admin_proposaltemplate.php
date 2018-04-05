<?php include "includes/functions.php"; ?>
<?php
$sql = "SELECT proposal_template from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$proposal_template = getsingleresult($sql);
?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<form action="admin_proposaltemplate_action.php" method="post" name="form1">
<h3>Company Proposal Templates</h3>
<br>
<strong>Select a default proposal template</strong>
<br><br>
<div style="width:500px; position:relative;">
<div style="width:250px; float:left;" align="center">
<a href="images/proposal_template1.png" target="_blank">
<img src="images/proposal_template1_thumb.png" border="0">
</a>
<br>
<input type="radio" name="proposal_template" value="1"<?php if($proposal_template==1) echo " checked";?> onchange="document.form1.submit()">
<br>
<a href="images/proposal_template1.png" target="_blank">
Preview
</a>
</div>

<div style="width:250px; float:left;" align="center">
<a href="images/proposal_template2.png" target="_blank">
<img src="images/proposal_template2_thumb.png" border="0">
</a>
<br>
<input type="radio" name="proposal_template" value="2"<?php if($proposal_template==2) echo " checked";?> onchange="document.form1.submit()">
<br>
<a href="images/proposal_template2.png" target="_blank">
Preview
</a>
</div>

</div>
<div style="clear:both;"></div>
</form>