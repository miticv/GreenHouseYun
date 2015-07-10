<?php
header('Content-Type: application/json');    
http_response_code(200);


if (isset($_GET["action"])) {
	switch ($_GET["action"])
    {
      case "get_data":
        $value = get_app_list();
        break;
      case "get_error_log":
        if (isset($_GET["from"])){
        	if (isset($_GET["to"])){

        	}
        }
         break;
       case "get_data_history_24h":
        $value = get_app_list();
        break;
       case "get_data_history_week":
        $value = get_app_list();
        break;
       case "get_data_history_month":
        $value = get_app_list();
        break;
    }//end switch

}else{
	exit(json_encode("invalid call"));
}



print '{ "result" : ';
print $value;
print '}';

//print trim($value, "[]");



function get_app_list()
{
  //normally this info would be pulled from a database.
  //build JSON array

  exec('python /mnt/sda1/arduino/python/apiGetInfo.py', $result);  
  return $result[0];
}

?>