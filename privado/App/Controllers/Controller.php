<?php
require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Controllers/RequestProtocol.php");

abstract class Controller implements RequestProtocol
{
  // Helpers Functions

  function responseJSON($data, $code){
    header("Content-type:application/json; charset = utf-8");
    http_response_code($code);
    echo json_encode($data);
  }     
  
}
?>
