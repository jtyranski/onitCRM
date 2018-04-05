<?php
include "../includes/functions.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<?php
$startdate = "2010-10-18";
$enddate = "2010-10-23";
$newmonth = 10;
$newyear = 2010;
$newday = 20;

$span = GetDays($startdate, $enddate, "+1 day");
	$number_bump = sizeof($span);
	$new_startdate = $newyear . "-" . $newmonth . "-" . $newday;
	$new_enddate = date("Y-m-d", mktime(0, 0, 0, $newmonth, $newday + ($number_bump - 1), $newyear));
	$span2 = GetDays($new_startdate, $new_enddate, "+1 day");
	$number_bump2 = sizeof($span2);

  echo "Old date span was $startdate to $enddate.  This is $number_bump days<br>";
  echo "Now starts $new_startdate and ends $new_enddate.  This is $number_bump2 days<br>";
  ?>
</body>
</html>
