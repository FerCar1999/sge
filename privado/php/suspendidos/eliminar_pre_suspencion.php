<?php 
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	// si es un id valido
	if($id > 0 ){
		$sql ="delete from suspendidos where id_suspendido = ? ";
 		$params = array(strip_tags($id)); 
		Database::executeRow($sql, $params);		   
		try{
			$aditionalDescription = " Action Details: id_suspension = {$id}";
			addToBitacora("Suspension Alumno Eliminada",$aditionalDescription);
		}catch(Exception $e){}
	}

	function addToBitacora($description,$aditionalDescription){
		try {
		
			require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
			
			session_start();
			BitacoraLogger::$currentUser = $_SESSION["id_personal"];
			BitacoraLogger::$function = 105;
			BitacoraLogger::$description = $description;
			BitacoraLogger::$aditionalDescription = $aditionalDescription;
			BitacoraLogger::setLogPersonal();    		
		} catch (Exception $e) {}
	}

 ?>