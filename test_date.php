<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<?php
$date1 = "2012-05-06";
$date2 = "2012-05-07";
$date3 = "2012-05-08";

if($date1 < date("Y-m-d")) echo "date 1 is in the past<br>";
if($date2 < date("Y-m-d")) echo "date 2 is in the past<br>";
if($date3 > date("Y-m-d")) echo "date 3 is in the future<br>";
?>
</body>
</html>
