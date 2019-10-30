<?php
// POST Params

$action = isset($_POST["action"]) && intval($_POST["action"]) >0 ? intval($_POST["action"]) : null;
$function = isset($_POST["function"]) && intval($_POST["function"]) >0 ? intval($_POST["function"]) : null;
$description = isset($_POST["description"]) ? trim(strip_tags($_POST["description"])): "";
$descriptionDetail = isset($_POST["description_detail"]) ? trim(strip_tags($_POST["description_detail"])): "";

require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Controllers/BitacoraController.php");
$controller = new BitacoraController(); 

// Checks if action was given
if($action == null){
  http_response_code(400);
  exit();
} 

// Add log to bitacora personal
if($action == 1){

  // TODO: validations    

  $request = array(
    "function" => $function,
    "description" => $description,
    "description_detail" => $descriptionDetail
  );  
  $controller->store($request);
}

?>