<?php
header('Content-Type: application/json');    
http_response_code(200);



exec("(</proc/uptime awk '{print $1}')", $secs);
$intsecs = explode(".", $secs[0]);
#------------------------------UPTIME-------------------------------------------
exec("uptime", $system); // get the uptime stats 
# no hours example:
#" 18:14:43 up 2 days, 24 min, load average: 0.17, 0.18, 0.18"
#" 22:31:20 up 2 days, 4:41, load average: 0.12, 0.17, 0.14"
$string = $system[0]; // this might not be necessary 
$uptime = explode(" up ", $string); // break up the stats into an array 
$uptimeDetails = explode("load average: ", $uptime[1]); // grab the days from the array 
$uptimeDetails[0] = trim($uptimeDetails[0]);
$uptimeDetails[0] = trim($uptimeDetails[0], ",");
$loadAverage = explode(",", $uptimeDetails[1]);


#$arr = array('uptime' => $uptimeDetails[0], 'loadAverage' => $uptimeDetails[1]);
$arr = array('alive'        => array('secAlive' => $intsecs[0], 'aliveFor'=> secondsToTime($intsecs[0]), 'uptime' => $uptimeDetails[0]) , 
			  'loadAverage' => array('1min' => trim($loadAverage[0]), '5min' => trim($loadAverage[1]), '15min' => trim($loadAverage[2]), 'Description' => '0 is idle, 1 is fully utilized, 1.05 means 5% of processes waited for their turn.')
			);
exit(json_encode($arr));


function secondsToTime($seconds) {
    $dtF = new DateTime("@0");
    $dtT = new DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');	
}

?>