<?php

include('CommonMethods.php');
date_default_timezone_set("America/Chicago");

//create the common class
$debug = false;
$COMMON = new Common($debug);
	
//error checking
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//input variables
$startdate = ($_POST["Startdate"]);
$enddate = ($_POST["Enddate"]);

//sanitize the date input
$interval = date_diff(new datetime($startdate), new datetime($enddate));
if ($interval-> d > 30){
	global $enddate;
	$newED = new datetime($startdate);
	$newED = $newED -> modify('+30 day');
	$enddate = $newED ->format('Y-m-d');
}


$COMMON-> random_data_for_range("Lot 35", $startdate,$enddate);
echo("Dummy data added")

?>

<html>
<head>
</head>
<body>
<form>
<input type="button" value="Return" onclick="window.location.href='http://projects.cse.tamu.edu/amiller15/315P2/Websitephp/Xindex.php'" />
</form>
</body>
</html>
