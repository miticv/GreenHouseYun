<?php
#header('Content-Type: application/json');    

exec("uptime", $system); // get the uptime stats 
# no hours example:
#" 18:14:43 up 2 days, 24 min, load average: 0.17, 0.18, 0.18"
#" 22:31:20 up 2 days, 4:41, load average: 0.12, 0.17, 0.14"
$string = $system[0]; // this might not be necessary 
$uptime = explode(" up ", $string); // break up the stats into an array 
$uptimeDetails = explode("load average: ", $uptime[1]); // grab the days from the array 
$uptimeDetails[0] = trim($uptimeDetails[0]);
$uptimeDetails[0] = trim($uptimeDetails[0], ",");

#$load_average = $uptimeDetails[1];
http_response_code(200);
exit(json_encode($uptimeDetails[0]));
