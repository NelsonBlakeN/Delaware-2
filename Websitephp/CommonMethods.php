<?php 

class Common
{	
	var $conn;
	var $debug;
	
	var $db="database.cse.tamu.edu";
	var $dbname="blake.nelson";
	var $user="blake.nelson";
	var $pass="Tamu@2019";
			
	function Common($debug)
	{
		$this->debug = $debug; 
		$rs = $this->connect($this->user); // db name really here
		return $rs;
	}

// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */
	
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

	function executeQuery($sql, $filename) // execute query
	{
		if($this->debug == true) { echo("$sql <br>\n"); }
		$rs = $this->conn->query($sql) or die("Could not execute query '$sql' in $filename"); 
		return $rs;
	}			

} // ends class, NEEDED!!

?>