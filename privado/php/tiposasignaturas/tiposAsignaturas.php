<?php
	// obtiene las variables del metodo post
	$id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
	$nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]): "";
	$descripcion = isset($_POST["descripcion"]) ? trim($_POST["descripcion"]): "";
//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();
	if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}
	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNombre($nombre)) {
		echo "Nombre inválido.";
		exit();
	}

	if (!validarTexto($descripcion)) {
		echo "Descripción inválida.";
		exit();
	}

	// require la clase dabase
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	if($id === null && !empty($nombre) && strlen($nombre)>0 && strlen($nombre) < 36){

		if ($nombre === "" || $descripcion === "") {
    		echo "camposFalta";
    		exit();
		}

		$sql = "INSERT INTO tipos_asignaturas(nombre, descripcion) VALUES(?,?)";
		// consulta sql
 		$params = array(strip_tags($nombre),strip_tags($descripcion));
		// ejecuta la consulta
 		$last_id=Database::executeRow($sql, $params,"INSERT");
 		if($last_id>0){
			echo 'agregado';
			// Bitacora
			try{
				$aditionalDescription = " Action Details: nombre = {$nombre}";
				addToBitacora("Se ha agregado un nuevo Tipo Asignatura",$aditionalDescription);
			}catch(Exception $e){}
		}
		else{
			echo 'Tipo de asignatura existente.';
		}

	}
	else if ($id > 0 && !empty($nombre) && strlen($nombre)>0 && strlen($nombre) < 36){
		
		$sql = "UPDATE tipos_asignaturas SET nombre=?, descripcion=? WHERE id_tipo_asignatura=?";

		$params = array(strip_tags($nombre),strip_tags($descripcion),strip_tags($id));
		// ejecuta la consulta
		if(Database::executeRow($sql, $params)){
			echo 'modificado';
			// Bitacora
			try{
				$aditionalDescription = " Action Details: id = {$id}";
				addToBitacora("Se ha modificado un Tipo Asignatura",$aditionalDescription);
			}catch(Exception $e){}
		}
		else{
			echo 'existente';
		}
	}
	else{
		echo 'Ingrese un tipo de asignatura válida.';
	}

	function addToBitacora($description,$aditionalDescription){
		try {
	
			require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
			
			session_start();
			BitacoraLogger::$currentUser = $_SESSION["id_personal"];
			BitacoraLogger::$function = 139;
			BitacoraLogger::$description = $description;
			BitacoraLogger::$aditionalDescription = $aditionalDescription;
			BitacoraLogger::setLogPersonal();    		
		} catch (Exception $e) {}	
	}
?>