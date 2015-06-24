<html>
 <head>
  <title>PHP Test</title>
 </head>
 <body>
 <?php 
 
  echo 'Current PHP version: ' . phpversion();
  echo '<br/>';

exec("uptime", $system); // get the uptime stats 
# no hours example:
#" 18:14:43 up 2 days, 24 min, load average: 0.17, 0.18, 0.18"
#" 22:31:20 up 2 days, 4:41, load average: 0.12, 0.17, 0.14"
$string = $system[0]; // this might not be necessary 
$uptime = explode(" up ", $string); // break up the stats into an array 
$uptimeDetails = explode("load average: ", $uptime[1]); // grab the days from the array 
$up_time = trim($uptimeDetails[0]);
$up_time = trim($up_time, ",");

$load_average = $uptimeDetails[1];


echo "The server has been up for " . $up_time . " and load average is " . $load_average; 
// echo the results 
 ?> 
 </body>
</html>