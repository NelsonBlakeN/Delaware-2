<?php

include('CommonMethods.php');

//create the common class
$debug = false;
$COMMON = new Common($debug);
	
//error checking
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//add and report dummy data 
$Location = ($_POST['Location']);
$COMMON-> random_week_data($Location);
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
