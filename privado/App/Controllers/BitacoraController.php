<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Controllers/Controller.php");
require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Models/Bitacora.php");

class BitacoraController extends Controller
{
  /**
   * Fetch all
   */
  public function index($request){    
  }
  /**
   * Store
   */
  public function store($request){
    $bitacora = new Bitacora();    

    $bitacora->function =  $request["function"];
    $bitacora->description = $request["description"];
    $bitacora->id_personal = $_SESSION["id_personal"];
    $bitacora->descriptionDetail = $_SESSION["description_detail"];
    
    $bitacora->save();
    
    parent::responseJSON(
      array("message" => "Bitacora Guardada Exitosamente"),
      200
    );

  }
  /**
   * Edit
   */
  public function edit($request){

  }
  /**
   * Update
   */
  public function update($request){

  }
  /**
   * Delete
   */
  public function destroy($request){

  }    
}
?>