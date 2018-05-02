<?php 

class Common
{	
	var $conn;
	var $debug;
	
	var $db="database.cse.tamu.edu";
	var $dbname="blake.nelson";
	var $user="blake.nelson";
	var $pass="Tamu@2019";
	
	// --------------------------------
	// Name: Common
	// PreConditions:  A debug value must be present.
	// PostConditions: An API object will be created, which will be
	//				   connected to the database. It's debug level will
	//				   also be assigned based on the passed value
	//----------------------------------		
	function Common($debug)
	{
		$this->debug = $debug; 
		$rs = $this->connect($this->user); // db name really here
		return $rs;
	}

// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */
	

	// --------------------------------
	// Name: Connect
	// PreConditions:  None
	// PostConditions: A valud connection will be made to the database,
	//				   or a descriptive error on a failed connection will be presented.
	//----------------------------------
	function connect($db)// connect to MySQL DB Server
	{
		try
		{
			$this->conn = new PDO('mysql:host='.$this->db.';dbname='.$this->dbname, $this->user, $this->pass);
	    	} catch (PDOException $e) {
        	    print "Error!: " . $e->getMessage() . "<br/>";
	            die();
        	}
	}

// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */

	// --------------------------------
	// Name: ExecuteQuery
	// PreConditions:  A SQL query and file name must exist
	// PostConditions: A query will be executed to the database, or
	//				   a descrptive error including the filename will be presented.
	//----------------------------------
	function executeQuery($sql, $filename) // execute query
	{
		if($this->debug == true) { echo("$sql <br>\n"); }
		$rs = $this->conn->query($sql) or die("Could not execute query '$sql' in $filename"); 
		return $rs;
	}

	// --------------------------------
	// Name: All
	// PreConditions:  None
	// PostConditions: A value will be returned that is equal to the
	//				   number of entries in the database.
	//----------------------------------
	function all($DBname){
		$sql = "SELECT * FROM `$DBname`";
		$rs = $this->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
		return $rs;
	}

	// --------------------------------
	// Name: CountRs
	// PreConditions:  A list of results is presented
	// PostConditions: A value will be returned that is equal to the
	//				   length of the given result list.
	//----------------------------------
	function countrs($rs){
		$count = 0;
		while ($row = $rs->fetch(PDO::FETCH_ASSOC)){
			++$count;
		}
		return $count;
	}

	// --------------------------------
	// Name: daylist
	// PreConditions:  a date is given
	// PostConditions: An rs value from a day query will be returned
	//----------------------------------
	function daylist($date, $database){
		$sql = "SELECT * FROM `$database` WHERE date = '$date'";
		$rs = $this->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
		return $rs;
	}

	// --------------------------------
	// Name: AddDummyWeek
	// PreConditions:  takes in a database and a startdate object
	// PostConditions: Manually adds data on each day of a determined week.
	//----------------------------------
	function random_week_data($database, $startdate,$enddate){
		$dayarray = $this->daterange($startdate,$enddate);
		for ($i = 0; $i < sizeof($dayarray); ++$i){
			$this -> random_day_data($dayarray[$i],$database);
		}
		//$this -> random_day_data('2018-4-25',$database);
	}

	// --------------------------------
	// Name: random_day_data
	// PreConditions:  A date is given
	// PostConditions: Adds a fake day, with a random number of
	//				   people added to the database for that day
	//----------------------------------
	function random_day_data($date, $database){
		$ppl = rand(20,600);
		for ($i = 0; $i < $ppl; ++$i){
			$h = rand(0,23);
			$m = rand(0,59);
			$s = rand(0,59);
			$b = rand(0,1);
			$time = $h . ":" . $m . ":" . $s;
			$sql= "INSERT INTO `$database`(`counter`,`time`, `Direction`, `date`) VALUES (DEFAULT,'$time',$b,'$date')";
			$rs = $this-> executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
		}
	}

	// --------------------------------
	// Name: timerange
	// PreConditions:  Two timestamps are given
	// PostConditions: A list of entries that are assigned a time
	//				   between the two given timestamps is returned.
	//----------------------------------
	function timerange(dateTime $starttime, dateTime $endtime){
		$times = array();
		$period = new DatePeriod($starttime, new DateInterval('PT1H'), $endtime);
		foreach ($period as $key => $value) {
     		array_push($times, $value);
		}
		return $times;
	}

	// --------------------------------
	// Name: HourList
	// PreConditions:  An hour timestamp is given
	// PostConditions: An rs for all entries from the given hour is returned
	//----------------------------------
	function hourlist(dateTime $datetime, $database){
		$hour = $datetime->format('H');
		$date = $datetime->format('Y-m-d');
		$dt1 = $datetime -> format('Y-m-d H:i:s');
		$dt2 = $datetime ->modify('+1 hour');
		$dt2 = $dt2 -> format('Y-m-d H:i:s');
		$sql = "SELECT * FROM `$database` WHERE timestamp(date,time) BETWEEN '$dt1' AND '$dt2'";
		$rs = $this->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
		return $rs;
	}

	// --------------------------------
	// Name: hourhistogram
	// PreConditions:  two datetimes and a particular database are given
	// PostConditions: an array of counts, representing a histogram of the hours inbetween those given
	//					is returned
	//----------------------------------
	function hourhistogram(dateTime $dt1, dateTime $dt2, $database){
		$datearray = $this->timerange($dt1,$dt2);
		$countarray = array();
		foreach ($datearray as $date){
			$rs = $this-> hourlist($date, $database);
			$daycount = $this->countrs($rs);
			array_push($countarray, $daycount);
		}
		return $countarray;
	}

	// --------------------------------
	// Name: polynomial_prediction
	// PreConditions:  two arrays representing the data from three days before is given
	// PostConditions: a y value of the prediction for the fourth day
	//----------------------------------
	function polynomial_prediction(array $x, array $y, $xi){
		$p1x = $x[0] + 0.5;
		$p2x = $x[1] + 0.5;
		$p1y = (($y[1] - $y[0]) / 2) + $y[0];
		$p2y = (($y[2] - $y[1]) / 2) + $y[1];
		$slope = ($p2y-$p1y)/($p2x-$p1x);
		$run = $xi - $p2x;
		$prediction = $p2y +  $slope * $run;
		if ($prediction >= 0){
			return $prediction;
		}
		else return 0;
	}

	// --------------------------------
	// Name: add_predicted_hour
	// PreConditions:  takes chart data array and an hour as input
	// PostConditions: adds predicted hour to the chardata (by reference)
	//----------------------------------
	function add_predicted_hour(&$chartdata,$hour,$date,$database){
		$lastthree = array_slice($chartdata, -4, 3);
		
		//create an array of helpful past days
		$startdate = $this -> firstdate($database);

		//polynomial method
		$x = array();
		$y = array();
		foreach($chartdata as $current){
			array_push($x, $current['label']);
			array_push($y, $current['y']);
		}
		$predictx = end($x) + 1;
		$polyprediction = $this-> polynomial_prediction($x, $y, $predictx);

		//average the hour prediction with past relevent dates
		$startdate = $this->firstdate($database);
		$pastdates = $this -> get_relevent_dates($startdate,$date);

		if (count($pastdates) > 0)echo("Relevent data found and factored in from "); echo("<br>");
		$hourdata = array();
		foreach($pastdates as $date){
			$dt1 = new datetime($date);
			$dt1 -> setTime($hour[0]-1,"00");
			array_push($hourdata, $this->countrs($this->hourlist($dt1,$database)));
			echo($dt1->format("Y-m-d H:i:s")); echo("<br>");
		}
		array_push($hourdata, $polyprediction);
		
		//average past hour data with the prediction thrown into the mix
		$prediction = array_sum($hourdata) / count($hourdata);

		//add the final prediction point to the data
		array_push($chartdata, array("label"=> "next hour", "y"=> $prediction, "indexLabel" => "Prediction"));

	}

	// --------------------------------
	// Name: firstdate
	// PreConditions:  the referenced database
	// PostConditions: returns the yyyy-mm-dd date of the first entry in the database
	//----------------------------------
	function firstdate($DBname){
		$sql = "SELECT * FROM `$DBname` LIMIT 1";
		$rs = $this->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
		//var_dump($rs);
		$row = $rs->fetch(PDO::FETCH_ASSOC);
		$date = $row['date'];
		return $date;
	}

	// --------------------------------
	// Name: set_chart_hour_prediction
	// PreConditions:  takes all the variables that the JS chart needs to generate graph
	// PostConditions: modifies them so that the chart presents the prediction
	//----------------------------------	
	function set_chart_hour_prediction(&$chartdata, &$title, $hour, $date, $database){
		//divide the hour input
		$time = explode(":",$hour);
		$datearray = explode("-", $date);
		$year = $datearray[0];
		$month = $datearray[1];
		$day = $datearray[2];

		//creating and setting up the Datetime objects
		$dt1 = new datetime(date('m/d/Y', time()));
		$dt1 -> setDate($datearray[0],$datearray[1],$datearray[2]);
		$dt1 -> setTime($time[0],"00");
		$dt1 -> modify('-3 hour');
		
		
		$dt2 = new datetime(date('m/d/Y', time()));
		$dt2 -> setDate($datearray[0],$datearray[1],$datearray[2]);
		$dt2 -> setTime($time[0],"00");
		

		//set the chart data
		$times = $this->timerange($dt1,$dt2);
		$counts = $this -> hourhistogram($dt1,$dt2, $database);
		$newcdata = array();
		for ($i = 0; $i < sizeof($times); ++$i){
			array_push($newcdata, array(
				"label"=> $times[$i]->format("H:i"), 
				"y"=> $counts[$i]
			));
		}
		$chartdata = $newcdata;

		//setting the title
		$t1 = $dt1->format('H:i');
		$t2 = $dt2->format('H:i');
		$day = $dt1->format('m/d/Y');

		$timestring = $dt2 ->format('Y-m-d H:i:s');
		$title = "Prediction for $timestring";

		$this -> add_predicted_hour($chartdata, $time, $date, $database);
	}

	// --------------------------------
	// Name: getdatenames
	// PreConditions:  takes in two dates
	// PostConditions: outputs an array of date names (Monday, tuesday ect.)
	//----------------------------------	
	function getdatenames($startdate, $enddate){
		$date1 = new DateTime($startdate);
		$date2 = new DateTime($enddate);
		$date2->modify('+1 day');
		$datenames = array();
		$period = new DatePeriod(
		     $date1,
		     new DateInterval('P1D'),
		     $date2
		);
		foreach ($period as $key => $value) {
     		array_push($datenames, $value->format('l'));
		}
		return $datenames;
	}

	// --------------------------------
	// Name: dayhistogram
	// PreConditions:  two dates and a database are given
	// PostConditions: a histogram of cars that were recorded on those days is returned in array form
	//----------------------------------	
	function dayhistogram($startdate,$enddate,$database){
		$datearray = $this-> daterange($startdate,$enddate);
		$countarray = array();
		foreach ($datearray as $date){
			$rs = $this-> daylist("$date",$database);
			$daycount = $this->countrs($rs);
			array_push($countarray, $daycount);
		}
		return $countarray;
	}

	// --------------------------------
	// Name: daterange
	// PreConditions:  two dates are given
	// PostConditions: a lis of dates between those two dates is returned
	//----------------------------------	
	function daterange($startdate,$enddate){
		$date1 = new DateTime($startdate);
		$date2 = new DateTime($enddate);
		$date2->modify('+1 day');

		$dates = array();
		$period = new DatePeriod(
		     $date1,
		     new DateInterval('P1D'),
		     $date2
		);
		foreach ($period as $key => $value) {
     		array_push($dates, $value->format('Y-m-d'));
		}
		return $dates;
	}

	// --------------------------------
	// Name: get_relevent_dates
	// PreConditions:  two dates are given
	// PostConditions: a list of every day that
	//----------------------------------	
	function get_relevent_dates($startdate,$enddate){
		$date1 = new DateTime($startdate);
		$date2 = new DateTime($enddate);

		//skip the same day
		$date2 -> modify("-7 day");

		//step back a week while data is available
		$dates = array();
		while($date2 > $date1){
			array_push($dates, $date2->format('Y-m-d'));
			$date2 -> modify("-7 day");
		}
		return $dates;
	}

	// --------------------------------
	// Name: setchartdays
	// PreConditions:  chart information is passed by reference
	// PostConditions: that information is modified and prepared for use in a chart to present the frequency of cars counted between two dates. Note that the range cannot be greater than one week
	//----------------------------------
	function setchartdays(&$chartdata, &$title, $startdate, $enddate, $database){

		//setting the chart data
		$newcdata = array();
		$dates = $this-> daterange($startdate,$enddate);
		$counts = $this-> dayhistogram($startdate,$enddate,$database);
		$datenames = $this-> getdatenames($startdate, $enddate);
		for ($i = 0; $i < sizeof($dates); ++$i){
			array_push($newcdata, array("label"=> $datenames[$i], "y"=> $counts[$i]));
		}
		$chartdata = $newcdata;

		//setting the title
		$date1 = new DateTime($startdate);
		$date2 = new DateTime($enddate);
		$date1 = $date1->format('m-d-Y');
		$date2 = $date2->format('m-d-Y');
		$title = "Cars from $date1 to $date2";
	}

}

?>