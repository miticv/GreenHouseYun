<?php

ini_set("memory_limit","12M");

//shows linux command in header
//$debug = True;
$debug = False;

main();


function main() {

	header('Content-Type: application/json'); 

	if (isset($_GET["action"])) {
		switch ($_GET["action"])
	    {
	        case "get_info_log":
				$value = get_InfoLog($_GET["from"], $_GET["to"]);
	        	break;

	        case "get_error_log":
				$value = get_InfoError($_GET["from"], $_GET["to"]);
		        break;

	        case "get_sensor_log":
	        	$value = get_InfoSensor($_GET["from"], $_GET["to"], $_GET["freq"], false);
	        	break;

	        case "get_sensor_log_raw":
	        	$value = get_InfoSensor($_GET["from"], $_GET["to"], $_GET["freq"], true);
	        	break;

	    	case "light_on":
	    		$value = do_light("on");
	    		break;

	    	case "light_off":
	    		$value = do_light("off");
	    		break;

	    	case "light":
	    		$value = do_light_flick();
	    		break;

	  	 	case "get_uptime":
	        	$value = get_Uptime();
	    		break;

	    	case "get_sensor_data":
	    		$value = get_SensorData();    		
	    		break;

	    	case "get_sensor_definitions":
	    		$value = get_sensorsDatabseDefinitions();
    			break;

	    	case "get_sensor_and_uptime_data":
	    		$value = get_SensorUptimeData();    		
	    		break;

	    	case "reset_arduino":
	    		$value = do_arduino_reset();    		
	    		break;

	    	case "reset_yun":
	    		$value = do_yun_reset();
	    		break;

	       default:
	       		return_Error("Invalid Call");
	    }//end switch

	}else{

		return_Error("Invalid Call");
	}


	
	http_response_code(200);

	print '{ "result" : ';
	print $value;
	print '}';
	exit(0);
}

function do_light($onoff){

	$execStr = 'python /mnt/sda1/arduino/python/apiSetLight.py "' . $onoff . '"';
	  if($debug) { header('Debug-value: ' . $execStr); }

	  exec(trim($execStr), $result);  
	  return $result[0];
}

function do_light_flick(){

	$execStr = 'python /mnt/sda1/arduino/python/apiSetLight.py "flick"';
	  if($debug) { header('Debug-value: ' . $execStr); }

	  exec(trim($execStr), $result);  
	  return $result[0];
}

function get_InfoLog($from, $to){
	  if(isset($to) && !isset($from))  {
	  	//Just set some time in the past
	  	$from = "2000-01-01 01-00-00";
	  }
	  $execStr = 'python /mnt/sda1/arduino/python/apiGetInfo.py "' . $from . '" "' . $to . '"';
	  if($debug) { header('Debug-value: ' . $execStr); }

	  exec(trim($execStr), $result);  
	  return $result[0];
}

function get_InfoError($from, $to){
	  if(isset($to) && !isset($from))  {
	  	//Just set some time in the past
	  	$from = "2000-01-01 01-00-00";
	  }
	  $execStr = 'python /mnt/sda1/arduino/python/apiGetError.py "' . $from . '" "' . $to . '"';
	  if($debug) { header('Debug-value: ' . $execStr); }

	  exec(trim($execStr), $result);  
	  return $result[0];
}

function get_InfoSensor($from, $to, $freq, $raw){
	  if(!isset($to) && !isset($from)){
	  	//default values are for today
	  	$to = "";
	  	$from = "";
	  }

	  if(isset($to) && !isset($from))  {
	  	//Just set some time in the past
	  	$from = "2000-01-01 01-00-00";
	  }
	  if(!isset($freq) || ($freq != "10min" && $freq != "60min" )){
	  	//default value is less intense for server:
	  	$freq = "60min";
	  }

	  if($freq == "10min"){
	  	$programName = "apiGetSensor10minLog.py";
	  }
	  else{
	  	$programName = "apiGetSensor60minLog.py";
	  }

	  $execStr = 'python /mnt/sda1/arduino/python/' . $programName . ' "' . $from . '" "' . $to . '"';    
	  if($debug) { header('Debug-value: ' . $execStr); }
	  exec(trim($execStr), $result);  

	  if(!$raw){
		  ################### fix -999 values ###################
		  $incr = 9; #number of sensors returning (each 9th position contains same type sensor)
		  $adjustedresult = $result[0];
		  $jsonarray = json_decode($adjustedresult, true);	  
		  for($x=0; $x < count($jsonarray); $x++) {
			    if($jsonarray[$x]['value'] == -999) {
			    	$prev = $jsonarray[$x]['value']; # $jsonarray[$x-$incr]['value'];
			    	$next = $jsonarray[$x]['value'];
			    	$i = $incr;
			    	while($prev == -999 && ($x-$i >= 0)){		    		
			    		$prev = $jsonarray[$x-$i]['value'];
			    		$i = $i + $incr;			    		
			    	}
					$i = $incr;
			    	while($next == -999 && ($x+$i < count($jsonarray))){		    					    	
			    		$next = $jsonarray[$x+$i]['value'];
			    		$i = $i + $incr;
			    	}
			    	$jsonarray[$x]['value'] = ($prev + $next) / 2;

			    }
		  }
		  return json_encode($jsonarray);
		  ########################################################
	  } else {
	  	  return $result[0];
	  } 	  
}

function get_SensorData(){
	$execStr = 'python /mnt/sda1/arduino/python/apiGetSensorLive.py';    
  	if($debug) { header('Debug-value: ' . $execStr); }

  	exec(trim($execStr), $result);  
  	return $result[0];
}

function get_Uptime(){

	#------------------------------alive-------------------------------------------
	$execStr = "(</proc/uptime awk '{print $1}')";
	exec($execStr, $secs);
	$intsecs = explode(".", $secs[0]);
	if($debug) { header('Debug-value1: ' . $execStr); }
	#------------------------------loadAverage-------------------------------------
	$execStr = "uptime";
	exec($execStr, $system); // get the uptime stats 
	if($debug) { header('Debug-value2: ' . $execStr); }
	# no hours example:
	#" 18:14:43 up 2 days, 24 min, load average: 0.17, 0.18, 0.18"
	#" 22:31:20 up 2 days, 4:41, load average: 0.12, 0.17, 0.14"
	$string = $system[0]; // this might not be necessary 
	$uptime = explode(" up ", $string); // break up the stats into an array 
	$uptimeDetails = explode("load average: ", $uptime[1]); // grab the days from the array 
	$uptimeDetails[0] = trim($uptimeDetails[0]);
	$uptimeDetails[0] = trim($uptimeDetails[0], ",");
	$loadAverage = explode(",", $uptimeDetails[1]);
	#------------------------------ date time -------------------------------------
	$execStr = 'date +"%Y-%m-%d %H:%M:%S"';
	exec($execStr, $devicetime);
	if($debug) { header('Debug-value3: ' . $execStr); }

	$arr = array('alive'       => array('secAlive' => $intsecs[0], 'aliveFor'=> secondsToTime($intsecs[0]), 'uptime' => $uptimeDetails[0]) , 
				 'loadAverage' => array('min1' => trim($loadAverage[0]), 'min5' => trim($loadAverage[1]), 'min15' => trim($loadAverage[2]), 'Description' => '0 is idle, 1 is fully utilized, 1.05 means 5% of processes waited for their turn.'),
				 'deviceTime'  => array('dateTime' =>  trim($devicetime[0]) ) 
				);
	return json_encode($arr);
}

function get_SensorUptimeData(){
	$var1 = get_SensorData();
    $var2 = get_Uptime();
    $var3 = get_sensorsDatabseDefinitions();

    if (empty($var1)) { $var1 = "null"; }
   	if (empty($var2)) { $var2 = "null"; }
    if (empty($var3)) { $var3 = "null"; }

	return '{ "sensors" : ' . $var1 . ', "uptime": ' . $var2 . ', "sensorLabels" : ' . $var3 . '}';
}

function get_sensorsDatabseDefinitions(){
	$execStr = 'python /mnt/sda1/arduino/python/apiGetSensorDefinitions.py';    
  	if($debug) { header('Debug-value: ' . $execStr); }

  	exec(trim($execStr), $result);  
  	return $result[0];
}
//function removeJsonBrackets($jsonVar){
//	$jsonVar = trim($jsonVar);
//	return substr($jsonVar, 1, strlen($jsonVar)-1);
//}

function secondsToTime($seconds) {
    $dtF = new DateTime("@0");
    $dtT = new DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');	
}   

function return_Error($errorStr){
	header('Content-Type: application/json'); 
	http_response_code(500);

  	print '{ "error" : "';
  	print $errorStr;
  	print '"}';
  
	exit(0);
}

function do_arduino_reset(){

	$execStr = 'reset-mcu';    
  	if($debug) { header('Debug-value: ' . $execStr); }

  	exec(trim($execStr), $result);  
  	return '{"Reboot" : "Completed"}';

}

function do_yun_reset(){


	// try run both at the same time: ?
	//$execStr = 'reset-mcu; /sbin/reboot'; 	

	/* do arduino first so it doesnt interfeer with yun reboot: */
	//do_arduino_reset();

	/* now reboot yun itself: */
	//$execStr = '/sbin/reboot';    
  	
  	$execStr = 'python /mnt/sda1/arduino/python/apiGetBoot.py';    

  	if($debug) { header('Debug-value: ' . $execStr); }
  	exec(trim($execStr), $result); 

  	return '{"Reboot" : "Completed"}';
}


?>