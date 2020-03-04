<?php 	
	session_start();
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
// si es un id valido
if($id > 0 ){
	Database::executeRow("delete from asistencias_diferidas where id_horario = ?", array($id));
}
$_SESSION["pk_horario"] = $id;	
 ?>