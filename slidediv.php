<?php
$firstrecord = $_GET['firstrecord'];
$direction = $_GET['direction'];

if($direction==1){
  $nextrecord = $firstrecord + 1;
}
else {
  $nextrecord = $firstrecord - 1;
}

$rightarrow = "<a href=\\\"javascript:;\\\" onmousedown=\\\"slideGenerate('" . $nextrecord . "', '1', 'slidediv.php');\\\">-></a>";
$leftarrow = "<a href=\\\"javascript:;\\\" onmousedown=\\\"slideGenerate('" . $nextrecord . "', '-1', 'slidediv.php');\\\"><-</a>";
?>
//div = document.getElementById('mydiv1');
//div.innerHTML = "<?php echo $firstrecord; ?>";
div = document.getElementById('mydiv2');
div.innerHTML = "<?php echo $nextrecord; ?>";

div = document.getElementById('rightarrow');
div.innerHTML = "<?php echo $rightarrow; ?>";
div = document.getElementById('leftarrow');
div.innerHTML = "<?php echo $leftarrow; ?>";

slidedown("mydiv2");
slideup("mydiv1");
//pausecomp('500');
setTimeout("divswitch('mydiv')",600);
<?php //usleep(500000);?>
