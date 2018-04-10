<!--
===========================================================================
TODO:
===========================================================================
-->

<?php

include("CommonMethods.php");
$debug = false;
$COMMON = new Common($debug);

$NAME = "Project1";

//process query

$rows = -1;

$rs = $COMMON->executeQuery("SHOW TABLE STATUS", $_SERVER["SCRIPT_NAME"]);
while($row = $rs->fetch(PDO::FETCH_ASSOC)) {
  if($row["Name"] == $NAME) {
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

?>

<!--
===========================================================================
SECTION 1: initial php and db
===========================================================================
-->


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
    <form method="get" action="index.php">
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
    <form method="get" action="index.php">
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
<br/>
<fieldset>
  <table>
    <tr>
      <td>
        <input type="reset" onclick="window.location.assign(window.location.origin + window.location.pathname)"></input>
      </td>
    </tr>
  </table>
</fieldset>

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
echo("<p>Min: ".($min+1)."</p>");
echo("<p>Max: ".($max-1)."</p>");

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
  </tr>

<?php

//sql

$sql = "SELECT * FROM `$NAME` WHERE counter > $min AND counter < $max";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

while($row = $rs->fetch(PDO::FETCH_ASSOC)) {
  $counter = $row['counter'];
  $time = $row['time'];
  echo("<tr><td>$counter</td><td>$time</td></tr>");
}

?>

</table>

</div>
</div>

</div>