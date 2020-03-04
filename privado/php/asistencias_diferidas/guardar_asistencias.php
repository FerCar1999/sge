<?php 	
// evalua los valores del POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$id_horario = isset($_POST['id_horario']) ? intval($_POST['id_horario']) : 0;
$hora = isset($_POST["hora"]) ? trim($_POST["hora"]) : ("");
$siempre = isset($_POST["siempre"]) ? trim($_POST["siempre"]) : false;

require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
// si es un id valido
if($id > 0 ){
	Database::executeRow("delete from asistencias_diferidas where id_personal = ? and date(fecha)< CURDATE() ", array($id));
	if(boolval($siempre)){
		$sql ="insert into asistencias_diferidas(id_personal,id_horario,siempre,fecha) values(?,?,'1',(select now()))";
		$params = array(strip_tags($id),$id_horario);
		Database::executeRow($sql, $params);				
		exit();
	}else {
		$sql ="insert into asistencias_diferidas(id_personal,id_horario,hora_limite,fecha) values(?,?,?,(select now()))";
		$params = array(strip_tags($id),$id_horario,$hora);
		Database::executeRow($sql, $params);
	}
	
	// Bitacora
	try{
		$aditionalDescription = " Action Details: id_horario = {$id_horario}";
		addToBitacora($aditionalDescription);
	}catch(Exception $e){}
}

function addToBitacora($aditionalDescription){
	try {

    require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
    
    session_start();
    BitacoraLogger::$currentUser = $_SESSION["id_personal"];
		BitacoraLogger::$function = 90;
    BitacoraLogger::$description = "Asistencia Diferida Guardada";
    BitacoraLogger::$aditionalDescription = $aditionalDescription;
    BitacoraLogger::setLogPersonal();    		
	} catch (Exception $e) {}	
}
?>