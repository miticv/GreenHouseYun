<?php
header('Content-Type: application/json');    
http_response_code(401);
$value = "An error has occurred";
if (isset($_GET["U"]))
{
  if($_GET["U"] == "1234")
  {
      http_response_code(200);
      $value = "OK";
  }
}
exit(json_encode($value))
?>