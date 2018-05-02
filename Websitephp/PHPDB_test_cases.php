<!--
/ ****************************************************
    File:       Testing.php
    Project:    CSCE 315 Project 1, Spring 2018
    Date:       3/19/2018
    Section:    504

    This file serves to test all major functions of the front
	end pedestrian counter portal.
******************************************************* */
-->

<?php

	include('CommonMethods.php');

	// Create API object
	$debug = false;
	$COMMON = new Common($debug);
	date_default_timezone_set("America/Chicago");

	// Set error checking values
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	// --------------------------------
	// Name: TestDataLoad
	// PreConditions:  None
	// PostConditions: Simulated data will be added, and the
	//				   success of the request will be evaluated.
	//----------------------------------
	function TestDataLoad(){
		global $COMMON;
		$COMMON-> Random_Day_Data("2018-04-14","Lot 35");
		return ($COMMON->CountRs($COMMON->DayList("2018-04-14","Lot 35")));
	}

	// --------------------------------
	// Name: TestRanges
	// PreConditions:  None
	// PostConditions: A range of dates will be attempted to be
	//				   created, and the success will be evaluated.
	//----------------------------------
	function TestRanges(){
		global $COMMON;
		$dates = $COMMON -> DateRange("2018-03-18","2018-03-22");
		foreach ($dates as $date) {
     		echo("$date<br>");
		}

		$date1 = new datetime(date('m/d/Y', time()));
		$date1 -> setTime("06","00");
		$date1 -> setDate("2018","03","18");

		$date2 = new datetime(date('m/d/Y', time()));
		$date2 -> setTime("10","00");
		$date2 -> setDate("2018","03","18");

		$hours = $COMMON -> TimeRange($date1,$date2);
		foreach($hours as $hour){
			echo($hour->format("H:i"));
			echo("<br>");
		}
	}

	// --------------------------------
	// Name: TestRanges
	// PreConditions:  None
	// PostConditions: A range of dates will be attempted to be
	//				   created, and the success will be evaluated.
	//----------------------------------
	function TestPrediction(){
		global $COMMON;
		$x = array(1,2,3);
		$y = array(30,40,30);
		$xi = 4;
		echo("here are the x and y arrays: <br>");
		var_dump($x); echo("<br>");
		var_dump($y); echo("<br>");
		echo($COMMON -> PolynomialPrediction($x, $y, $xi));
		echo (" ... should be 35<br>");
	}

	// --------------------------------
	// Name: TestMain
	// PreConditions:  None
	// PostConditions: All previous tests will be run, and
	//				   their success rate will be clear.
	//----------------------------------
	function TestMain(){
		$clearRs = TestDataLoad();
		echo("Testing AddDummyDay(), AddDummyWeek(), AddPerson()... should be more than 100 or so: $clearRs<br>");
		echo("<br>");
		echo("Date and hour range test...<br>");
		TestRanges();
		echo("<br>");
		echo("Testing Prediction:<br>");
		TestPrediction();
	}

	TestMain();

?>