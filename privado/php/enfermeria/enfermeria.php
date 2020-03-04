<?php
	// obtiene las variables del metodo post
	$id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
	$pk_alumno = isset($_POST["pk_alumno"]) && intval($_POST["pk_alumno"]) >0 ? intval($_POST["pk_alumno"]) : null;
	$observacion = isset($_POST["observacion"]) ? trim($_POST["observacion"]): "";
	$modificar = isset($_POST["modificar"]) ? trim($_POST["modificar"]): null;
	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	//session_start();
	/*if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}*/

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($pk_alumno) && $pk_alumno != null) {
		echo "Estudiante no registrado.";
		exit();
	}
	if (!validarNumeroEntero($id) && $id != null) {
		echo "Observación no registrada.";
		exit();
	}
	if (!validarTexto($observacion)) {
		echo "Observación inválida.";
		exit();	
	}

	session_start();

	// require la clase dabase
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	if($modificar == 0){
		
		$sql = "INSERT INTO enfermeria(fecha_hora,id_personal,id_estudiante,observacion) VALUES((SELECT NOW()),?,(SELECT id_estudiante FROM estudiantes WHERE codigo = ?),?)";
		// consulta sql
 		$params = array(strip_tags($_SESSION["id_personal"]),strip_tags($pk_alumno),strip_tags($observacion));
		// ejecuta la consulta
 		$last_id=Database::executeRow($sql, $params,"INSERT");
 		if($last_id>0){
			echo 'Visita a la enfermería agregada.';
			try{
				addToBitacora("Se ha agregado una visita a enfermería al alumno: {$pk_alumno} ","");
			}catch(Exception $e){}
		}
		else{
			echo '';
		}

	}
	else if ($modificar != 0){
		
		$sql = "UPDATE enfermeria SET observacion=? WHERE id_enfermeria=?";
		// consulta sql
 		$params = array(strip_tags($observacion),strip_tags($modificar));
		// ejecuta la consulta
		if(Database::executeRow($sql, $params)){
			echo "Visita a la enfermería modificada.";
			try{
				addToBitacora("Se ha modificado una visita a enfermería al ID: {$modificar} ","");
			}catch(Exception $e){}
		}
		else{
			echo 'existente';
		}
	}
	else{
		echo 'Estudiante no registrado.';
	}

	function addToBitacora($description,$aditionalDescription){
		try {
	
			require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
			
			session_start();
			BitacoraLogger::$currentUser = $_SESSION["id_personal"];
			BitacoraLogger::$function = 54;
			BitacoraLogger::$description = $description;
			BitacoraLogger::$aditionalDescription = $aditionalDescription;
			BitacoraLogger::setLogPersonal();    		
		} catch (Exception $e) {}	
	}
?>