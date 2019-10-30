<?php 	
// evalua los valores del POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$id_horario = isset($_POST['id_horario']) ? intval($_POST['id_horario']) : 0;
$fecha = isset($_POST["fecha"]) ? trim($_POST["fecha"]) : "";
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
// si es un id valido
if($id > 0 ){
	Database::executeRow("insert into bloques_justificados(id_horario,id_estudiante,fecha) values(?,(select id_estudiante from estudiantes where codigo =?),date(?))", array($id_horario,$id,$fecha));
	Database::executeRow('INSERT INTO validar_justificacion(id_estudiante,id_horario,fecha,tipo) VALUES((select id_estudiante from estudiantes where codigo =?),?,(select now()),"Permiso")',array(
		$id,
		$id_horario));
}
?>
