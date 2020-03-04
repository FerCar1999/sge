<?php 
// evalua los valores del POST
$codigo = isset($_POST['codigo']) ? intval($_POST['codigo']) : 0;
$observacion = isset($_POST["observacion"]) ? trim($_POST["observacion"]) : "";
$item = isset($_POST['item']) ? json_decode($_POST['item']) : null;

//obtiene el token enviado por el navegador y lo compara con el de su session activa
$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
session_start();
if(!Token::check($token)){
	echo 'Acción sobre la información denegada.';
	exit();
}

require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

if($observacion!=""){
	Database::executeRow("insert into observaciones(id_personal,id_estudiante,fecha,observacion) values(?,(select id_estudiante from estudiantes where codigo=?),(select now()),?)", array($_SESSION["id_personal"],$codigo,$observacion));
}


if($item!=null){
	$sql = "update inasistencias_clases set estado='Justificada', tipo='Justificada ITR' where id_inasistencia=?";
	Database::executeRow($sql, array($item));				
}
?>