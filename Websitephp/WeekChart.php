<?php

include('CommonMethods.php');

//global variables
$debug = false;
$COMMON = new Common($debug);
date_default_timezone_set("America/Chicago");
$dataPoints;
$charttitle;

//input variables
$startdate = ($_POST["Startdate"]);
$enddate = ($_POST["Enddate"]);

//sanitize the date input (30 is my arbitrary limit for days on the chart)
$interval = date_diff(new datetime($startdate), new datetime($enddate));
if ($interval-> d > 30 || $interval-> m !=0){
	global $enddate;
	$newED = new datetime($startdate);
	$newED = $newED -> modify('+30 day');
	$enddate = $newED ->format('Y-m-d');
}
	
//error checking
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//setting the chart properties
$COMMON->setchartdays($dataPoints, $charttitle, $startdate, $enddate, "Lot 35");

?>


<!DOCTYPE HTML>
<html>
<head> 

</head>  
<script>

window.onload = function () {

setTimeout("location.reload(true);", 5000);
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	exportEnabled: true,
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	title:{
		text: <?php echo json_encode($charttitle); ?>
	},
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		//indexLabel: "{y}", //Shows y value on all Data Points
		indexLabelFontColor: "#5A5757",
		indexLabelPlacement: "outside",   
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
}
</script>
</head>
<body style="background-color:powderblue;">
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<form>
<br>
<input type="button" value="Return" onclick="window.location.href='http://projects.cse.tamu.edu/amiller15/315P2/Websitephp/Xindex.php'" />
</form>
</body>
</html> 