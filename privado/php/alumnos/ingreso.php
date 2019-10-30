<?php
$codigo = isset($_POST["codigo"]) ? trim($_POST["codigo"]): "";
$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
session_start();
// consultas para obtener la informacion
$data = Database::getRows("select fecha from ingreso_estudiante where id_estudiante = (select id_estudiante from estudiantes where codigo = ?) ORDER BY fecha DESC",array($codigo));

$response = array();
// rellena la informacion 
foreach ($data as $row) {
  $dataRow['fecha'] = $row['fecha'];
  $response[]=$dataRow;
}
// envia como json
$jsondata["lista"] = array_values($response);
echo json_encode($jsondata);    
?>