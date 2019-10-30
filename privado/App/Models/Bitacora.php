<?php
require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Models/Model.php");
require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");

class Bitacora extends Model
{
  // Instance Properties
  
  public $description;
  public $id_personal;
  public $function;
  public $descriptionDetail = "";

  /**   
   * Saves Log into bitacora personal
   */
  public function save() {    
    BitacoraLogger::$currentUser = $this->id_personal;
    BitacoraLogger::$function = $this->function; // id in database
    BitacoraLogger::$description = $this->description;
    BitacoraLogger::$aditionalDescription = $this->descriptionDetail;
    BitacoraLogger::setLogPersonal();
  }

}
?>
