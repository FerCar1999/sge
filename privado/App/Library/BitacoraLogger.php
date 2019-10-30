<?php
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
date_default_timezone_set("America/El_Salvador");

class BitacoraLogger {

  // Instance properties
  public static $currentUser;
  public static $function;
  public static $description;  
  public static $aditionalDescription = "";

  // Save log after personal's task completed
  public static function setLogPersonal(){    
    return Database::executeRow('INSERT INTO bitacora_personal(id_personal,id_funcion,descripcion,detalle_request,fecha)
    VALUES (?,?,?,?,(SELECT now()))',self::getParamsForQuery());
  }

  // Save log after estudiante's task completed
  public static function setLogEstudiante(){
    return Database::executeRow('INSERT INTO bitacora_alumno(id_personal,id_funcion,descripcion,detalle_request,fecha)
    VALUES (?,?,?,?,(SELECT now()))', self::getParamsForQuery());
  }

  // Get prepared params for query
  private static function getParamsForQuery(){
    $requestHeader = "";
    if (count(self::$aditionalDescription) > 0) {
      $requestHeader = self::$aditionalDescription.", Headers:";
    }
    
    $headers =  getallheaders();
    foreach($headers as $key=>$val){
      $requestHeader .=  $key . ': ' . $val . ', ';
    }    
    return array(
      self::$currentUser,
      self::$function,
      self::$description,
      $requestHeader  
    );
  }
}
?>