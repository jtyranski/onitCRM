<?php
$w=$_GET['w'];
$h=isset($_GET['h'])?$_GET['h']:$w;    // h est facultatif, =w par défaut
$x=isset($_GET['x'])?$_GET['x']:0;    // x est facultatif, 0 par défaut
$y=isset($_GET['y'])?$_GET['y']:0;    // y est facultatif, 0 par défaut
$filename=$_GET['src'];
header('Content-type: image/jpg');
header('Content-Disposition: attachment; filename='.$src);
$image = imagecreatefromjpeg($filename); 
$crop = imagecreatetruecolor($w,$h);
imagecopy ( $crop, $image, 0, 0, $x, $y, $w, $h );
imagejpeg($crop);
?>