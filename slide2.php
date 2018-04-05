<?php include "../includes/functions.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Scroll</title>
<script language="JavaScript" src="motionpack.js"></script>
</head>

<body>


<?php $counter = 3; ?>
<?php $firstrecord = 74; ?>
<div style="position:relative; width:100%;">
<div style="float:left; vertical-align:middle; height:95px; width:50px;" id="leftarrow">
<a href="javascript:;" onmousedown="slideGenerate('<?=$firstrecord?>', '-1', 'slidediv.php');"><-</a>
</div>

<div id="mydiv1" style="display:block; overflow:hidden; height:95px; border:1px solid black; width:80%; float:left;">
<?=$firstrecord?>
</div>
<div id="mydiv2" style="display:none; overflow:hidden; height:95px; border:1px solid black; width:80%; float:left;">
</div>





<div style="float:right; vertical-align:middle; height:95px; width:50px;" id="rightarrow">
<a href="javascript:;" onmousedown="slideGenerate('<?=$firstrecord?>', '1', 'slidediv.php');">-></a>
</div>
</div>
<div style="clear:both;"></div>


<div id="debug"></div>
</body>
</html>
