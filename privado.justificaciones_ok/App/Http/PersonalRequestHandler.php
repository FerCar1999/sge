<?php
// POST Params
$action = isset($_POST["action"]) && intval($_POST["action"]) >0 ? intval($_POST["action"]) : null;
$id = isset($_POST["id"]) ? trim(strip_tags($_POST["id"])): "";
$date = isset($_POST["date"]) ? trim(strip_tags($_POST["date"])): "";

require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Controllers/PersonalController.php");
$controller = new PersonalController(); 

// Checks if action was given
if($action == null){
  http_response_code(400);
  exit();
} 

// Uso del sistema - Mostrar Maestros que no han pasado lista
if($action == 1){
  // validations  
  if ($date == "") {
    http_response_code(400);
    exit();
  }

  $request = array(
    "date" => $date
  );  
  $controller->AllAttendanceStatusFromDate($request);
}
// Uso del sistema - Mostrar Maestro estatus de sus asistencias
else if($action == 2){
  // validations
  if ($date == "" || $id == null) {
    http_response_code(400);
    exit();
  }

  $request = array(
    "date" => $date,
    "id" => $id
  );  
  $controller->attendanceStatusFromDate($request);
}
// Listar todos los Maestros
else if($action == 3){
  $request = array();  
  $controller->index($request);
}
?>