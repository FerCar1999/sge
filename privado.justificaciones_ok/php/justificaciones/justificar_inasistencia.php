<?php 
// evalua los valores del POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$observacion = isset($_POST["observacion"]) ? trim($_POST["observacion"]) : "";

//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();	
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
if($id>0){
	$sql = "update inasistencias_clases set estado='Justificada' where id_inasistencia=?";
	Database::executeRow($sql, array($id));			

	if($observacion!=""){
		Database::executeRow("insert into observaciones(id_personal,id_estudiante,fecha,observacion) values(?,(select id_estudiante from inasistencias_clases where id_inasistencia=?),(select now()),?)", array($_SESSION["id_personal"],$id,$observacion));
	}
	
}
?>