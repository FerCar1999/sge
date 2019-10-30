<?php 
	$pk_alumno = isset($_POST['pk_alumno']) ? intval($_POST['pk_alumno']) : 0;
	$inicio = isset($_POST["inicio"]) ? $_POST["inicio"] : "";
	$fin = isset($_POST["fin"]) ? $_POST["fin"] : "";
	$token = isset($_POST["token"]) ? $_POST["token"] : "";
	$pk_motivo = isset($_POST["pk_motivo"]) ? $_POST["pk_motivo"] : "";
	$permiso = isset($_POST["permiso"]) ? $_POST["permiso"] : "";

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");	

	if($pk_alumno>0){
		Database::executeRow("insert into ausencias_justificadas(id_estudiante,inicio,fin,motivo,permiso) values((select id_estudiante from estudiantes where codigo=?),?,?,?,?)", array($pk_alumno,$inicio,$fin,$pk_motivo,$permiso));					
	
		// Bitacora
		try{
			$aditionalDescription = " Action Details: Alumno = {$pk_alumno}, inicio: {$inicio}, fin: {$fin}, motivo: {$pk_motivo}";
			addToBitacora($aditionalDescription);
		}catch(Exception $e){}
}


	function addToBitacora($aditionalDescription){
		try {
	
			require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
			
			session_start();
  	  BitacoraLogger::$currentUser = $_SESSION["id_personal"];
			BitacoraLogger::$function = 85;
			BitacoraLogger::$description = "Permiso anticipado por dias Asignado";
			BitacoraLogger::$aditionalDescription = $aditionalDescription;
			BitacoraLogger::setLogPersonal();    		
		} catch (Exception $e) {}	
	}
?>