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
	// Name: AddDummyWeek
	// PreConditions:  None
	// PostConditions: Manually adds data on each day of a determined week.
	//----------------------------------
	function random_week_data($database){
		$this -> random_day_data('2018-4-25',$database);
		$this -> random_day_data('2018-4-26',$database);
		$this -> random_day_data('2018-4-27',$database);
		$this -> random_day_data('2018-4-28',$database);
		$this -> random_day_data('2018-4-29',$database);
		$this -> random_day_data('2018-4-30',$database);
		$this -> random_day_data('2018-4-31',$database);
	}

	// --------------------------------
	// Name: random_day_data
	// PreConditions:  A date is given
	// PostConditions: Adds a fake day, with a random number of
	//				   people added to the database for that day
	//----------------------------------
	function random_day_data($date, $database){
		$ppl = rand(20,100);
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
	// PostConditions: A list of all entries from the given hour is returned
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
		$run = $xi - end($x);
		$prediction = end($y) +  $slope * $run;
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
	function add_predicted_hour(&$chartdata,$hour){
		$lastthree = array_slice($chartdata, -4, 3);
		

		//average method
		$countarray = array();
		for ($i = 0; $i < count($lastthree); ++$i){
			array_push($countarray, $lastthree[$i]["y"]);
		}
		$total = array_sum($countarray);
		$average = $total / 3;

		//polynomial method
		$x = array();
		$y = array();

		foreach($chartdata as $current){
			array_push($x, $current['label']);
			array_push($y, $current['y']);
		}
		//echo(count($x));
		//echo("<br>");
		//echo(count($y));
		//echo("<br>");

		$predictx = end($x) + 1;
		$polyprediction = $this-> polynomial_prediction($x, $y, $predictx);

		$prediction = $polyprediction;

		array_push($chartdata, array("label"=> "next hour", "y"=> $prediction, "indexLabel" => "Prediction"));

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

		//creating and setting up the datetime objects
		$dt1 = new datetime(date('m/d/Y', time()));
		$dt1 -> setTime($time[0]-3,"00");
		$dt1 -> setDate($datearray[0],$datearray[1],$datearray[2]);
		
		$dt2 = new datetime(date('m/d/Y', time()));
		$dt2 -> setTime($time[0],"00");
		$dt2 -> setDate($datearray[0],$datearray[1],$datearray[2]);

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
		$title = "Prediction Based On Data Between $t1 to $t2 on $day";

		$this -> add_predicted_hour($chartdata,$time);
	}

}

?>