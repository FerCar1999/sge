<?php 
// evalua los valores del POST
$codigo = isset($_POST['codigo']) ? intval($_POST['codigo']) : 0;
$observacion = isset($_POST["observacion"]) ? trim($_POST["observacion"]) : "";
$inicio = isset($_POST["inicio"]) ? trim($_POST["inicio"]) : "";
$fin = isset($_POST["fin"]) ? trim($_POST["fin"]) : "";
$items = isset($_POST['items']) ? json_decode($_POST['items']) : null;

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

foreach($items as $item)
{
	if($item!=null){
		$sql = "update inasistencias_clases set estado='Justificada' where id_inasistencia=?";
		Database::executeRow($sql, array($item));				
	}
}

// Justificar Inasistencias completas
Database::executeRow('update inasistencias_totales set estado ="Justificada" where id_estudiante = (select id_estudiante from estudiantes where codigo=?) and date(fecha) between ? and ?',array($codigo,$inicio,$fin));
?>