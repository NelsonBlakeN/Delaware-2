<!--
/* ****************************************************
    File:       HourPrediction.php
    Project:    CSCE 315 Project 2, Spring 2018
    Date:       4/19/2018
    Section:    504

    This page presents a chart that walks the user through a prediction model for a given input hour and date
******************************************************* */
-->

<?php

include('CommonMethods.php');

//global variables
$debug = false;
$COMMON = new Common($debug);
date_default_timezone_set("America/Chicago");
$dataPoints;
$charttitle;

//input variables
$hour = ($_POST["Hour"]);
$date = ($_POST["Date"]);
echo($starthour);
	
//error checking
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$COMMON -> SetChartHourPrediction($dataPoints, $charttitle, $hour, $date, "Lot 35");

?>
<!DOCTYPE HTML>
<html>
<head>  
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
		type: "area", //change type to bar, line, area, pie, etc
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
<br>
<form>
<input type="button" value="Return" onclick="window.location.href='http://projects.cse.tamu.edu/amiller15/315P2/Websitephp/Xindex.php'" />
</form>
</body>
</html> 