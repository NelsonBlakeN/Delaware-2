
<!--
/* ****************************************************
    File:       Xindex.php
    Project:    CSCE 315 Project 2, Spring 2018
    Date:       4/19/2018
    Section:    504

    This file runs the main page of the website. It contains all the forms that lead to any other additional functionalities
******************************************************* */
-->

<?php

include("CommonMethods.php");
$debug = false;
$COMMON = new Common($debug);

//getting or setting the current location
if(isset($_POST['Location'])){
  $Location = ($_POST['Location']);
}
else $Location = "Lot 35";

$DBNAME = $Location;

//process query

$rows = -1;

$rs = $COMMON->executeQuery("SHOW TABLE STATUS", $_SERVER["SCRIPT_NAME"]);
while($row = $rs->fetch(PDO::FETCH_ASSOC)) {
  if($row["Name"] == $DBNAME) {
    $rows = $row["Auto_increment"] - 1;
  }
}

$min = 0; //exclusive; 1 = minimum possible counter value
$max = $rows + 1; //exclusive

if(isset($_GET['count'])) {
  //set the number of records
  $gcount = $_GET['count'];
  $min = $max - $gcount - 1;
} else if(isset($_GET['min']) && isset($_GET['max'])) {
  $min = $_GET['min'] - 1;
  $max = $_GET['max'] + 1;
}
$min = max($min, 0);
$max = min($max, $rows + 1);
if(!isset($gcount)) {
  $gcount = $max - $min - 1;
}

$min1 = $min + 1;
$max1 = $max - 1;

//getting the number of entries in the database
$count = $COMMON ->CountRs($COMMON -> All("Lot 35"));

?>

<!--
===========================================================================
SECTION 1: initial php and db
===========================================================================
-->
<head>

</head>


<style>
* {
  font-family: arial, sans-serif;
}
</style>

<div>

<!--
===========================================================================
SECTION 2: form and such
===========================================================================
-->

<div style="display: inline-block; padding: 0px 10px; vertical-align: top">

<h1>Forms</h1>

<fieldset>
  <table>
    <form method="get" action="Xindex.php">
      <tr>
        <td><label>Min:</label></td>
        <?php
          echo("<td><input type=\"number\" name=\"min\" value=\"$min1\"> </input></td>");
        ?>
      </tr>
      <tr>
        <td><label>Max:</label></td>
        <?php
          echo("<td><input type=\"number\" name=\"max\" value=\"$max1\"> </input></td>");
        ?>
      </tr>
      <tr>
        <td><input type="submit"></input></td>
      </tr>
    </form>
  </table>

</fieldset>
<br/>
<fieldset>
  <table>
    <form method="get" action="Xindex.php">
      <tr>
        <td><label>Count:</label></td>
        <?php
          echo("<td><input type=\"number\" name=\"count\" value=\"$gcount\"> </input></td>");
        ?>
      </tr>
      <tr>
        <td><input type="submit"></input></td>
      </tr>
    </form>
  </table>
</fieldset>
</fieldset>
<br/>

<fieldset>
  <table>
    <form action="WeekChart.php" method="post">
      Data Visualizer: <br/>
      Start Date: <input type="date" name="Startdate" value="2018-04-15"><br>
      End Date: <input type="date" name="Enddate" value="2018-05-15"><br>
      <input type="submit">
    </form>
  </table>
</fieldset>
<br/>

<fieldset>
  <table>
    <form action = "HourPrediction.php" method = "post">
      <tr>
        Predictive Model for Hour: <br>
        <input type="time" name="Hour" value="06:00"><br>
        <input type="date" name="Date" value ="2018-05-15"><br>
      </tr>
      <tr>
        <td><input type="submit"></input></td>
      </tr>
    </form>
  </table>
</fieldset>
<br/>

<fieldset>
  <table>
    <form action = "Xindex.php" method = "post" value= <?php echo $_POST['Location'] ?>>
      <tr>
        Change Location: <br>
        <input type="radio" name="Location" value="Lot 35" checked> Lot 35<br>
        <input type="radio" name="Location" value="Lot 54"> Lot 54<br>
      </tr>
      <tr>
        <td><input type="submit"></input></td>
      </tr>
    </form>
  </table>
</fieldset>
<br/>

<fieldset>
  <table>
    <form action = "DummyInput.php" method = "post">
      <tr>
        Add Dummy Data: <br>
        Start Date: <input type="date" name="Startdate" value="2018-04-22"><br>
        End Date: <input type="date" name="Enddate" value="2018-04-29">
      <tr>
        <td><input type="submit"></input></td>
      </tr>
    </form>
  </table>
</fieldset>
<br/>

</div>

<!-- vertical bar to separate sections -->
<div style="display: inline-block; margin: 0 auto; padding: 0px 0px; height: 100%; width: 1px; vertical-align: top; background-color: #000000">
</div>

<!-- TODO: get stuff to be centered... -->
<div style="display: inline-block; margin: 0 auto; padding: 0px 10px; vertical-align: top;">
<div style="margin: 0 auto;">

<!--
===========================================================================
SECTION 3: query
===========================================================================
-->

<?php

echo("<h1>Results</h1>");
echo("<p>Total Count: ".($count)."</p>");
echo("<p>Location: ".($Location)."</p>");

?>

<!-- https://www.w3schools.com/html/tryit.asp?filename=tryhtml_table_intro -->

<style>
#data {
  border-collapse: collapse;
}
#data td, #data th {
  border: 1px solid black;
  padding: 5px 10px;
  text-align: center;
}
#data tr:nth-child(even) {
    background-color: #e0e0e0;
}
</style>

<table id="data">
  <tr>
    <th>Entry</th>
    <th>Time</th>
    <th>Date</th>
    <th>Direction</th>

  </tr>

<?php

//sql

$sql = "SELECT * FROM `$DBNAME` WHERE counter > $min AND counter < $max";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

while($row = $rs->fetch(PDO::FETCH_ASSOC)) {
  $counter = $row['counter'];
  $time = $row['time'];
  $direction = $row['Direction'];
  $date = $row['date'];
  echo("<tr><td>$counter</td><td>$time</td><td>$date</td><td>$direction</td></tr>");
}

?>

</table>

</div>
</div>

</div>