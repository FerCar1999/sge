<?php
	// obtiene las variables del metodo post
	$id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
	$nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]): "";
	$grupoTipo = isset($_POST["grupoTipo"]) ? trim($_POST["grupoTipo"]): "";


	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($grupoTipo)) {
		echo "Nivel inválido.";
		exit();
	}
	if (!validarNombre($nombre)) {
		echo "Nombre inválido.";
		exit();	
	}

	// require la clase dabase
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	if($id === null && !empty($nombre) && strlen($nombre)>0 && strlen($nombre) < 36){
		
		$sql = "";

		if ($nombre === "" || $grupoTipo === "") {
    		echo "camposFalta";
    		exit();
		}

		if ($grupoTipo == 1) {
			$sql = "INSERT INTO grupos_academicos(nombre) VALUES(?)";
		}
		if ($grupoTipo == 2) {
			$sql = "INSERT INTO grupos_tecnicos(nombre) VALUES(?)";
		}
		// consulta sql
 		$params = array(strip_tags($nombre));
		// ejecuta la consulta
 		$last_id=Database::executeRow($sql, $params,"INSERT");
 		if($last_id>0){
			try{
				$aditionalDescription = " Action Details: nombre = {$nombre}";
				addToBitacora("Se ha agregado un nuevo Grupo",$aditionalDescription);
			}catch(Exception $e){}

			echo 'agregado';
			// Bitacora
			
		}
		else{
			echo 'Grupo existente.';
		}

	}
	else if ($id > 0 && !empty($nombre) && strlen($nombre)>0 && strlen($nombre) < 36){
		
		$sql = "";

		if ($grupoTipo == 1) {
			$sql = "UPDATE grupos_academicos SET nombre=? WHERE id_grupo_academico=?";
		}
		if ($grupoTipo == 2) {
			$sql = "UPDATE grupos_tecnicos SET nombre=? WHERE id_grupo_tecnico=?";
		}

		$params = array(strip_tags($nombre),strip_tags($id));
		// ejecuta la consulta
		if(Database::executeRow($sql, $params)){
			try{
				$aditionalDescription = " Action Details: id = {$id}";
				addToBitacora("Se ha modificado un Grupo",$aditionalDescription);
			}catch(Exception $e){}
			
				echo 'modificado';			
			
		}
		else{
			echo 'existente';
		}
	}
	else{
		echo 'Ingrese un grupo válido.';
	}

	function addToBitacora($description,$aditionalDescription){
		try {
	
			require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
			
			session_start();
			BitacoraLogger::$currentUser = $_SESSION["id_personal"];
			BitacoraLogger::$function = 106;
			BitacoraLogger::$description = $description;
			BitacoraLogger::$aditionalDescription = $aditionalDescription;
			BitacoraLogger::setLogPersonal();    		
		} catch (Exception $e) {}
	}

?>