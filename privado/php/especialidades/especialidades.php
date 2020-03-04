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

		$sql = "INSERT INTO especialidades(nombre, descripcion) VALUES(?,?)";
		// consulta sql
 		$params = array(strip_tags($nombre),strip_tags($descripcion));
		// ejecuta la consulta
 		$last_id=Database::executeRow($sql, $params,"INSERT");
 		if($last_id>0){
			echo 'agregado';
			// Bitacora
			try{
				$aditionalDescription = " Action Details: nombre = {$nombre}";
				addToBitacora("Se ha agreado una nueva Especialidad",$aditionalDescription);
			}catch(Exception $e){}
		}
		else{
			echo 'Especialidad existente.';
		}

	}
	else if ($id > 0 && !empty($nombre) && strlen($nombre)>0 && strlen($nombre) < 36){
		
		$sql = "UPDATE especialidades SET nombre=?, descripcion=? WHERE id_especialidad=?";

		$params = array(strip_tags($nombre),strip_tags($descripcion),strip_tags($id));
		// ejecuta la consulta
		if(Database::executeRow($sql, $params)){
			echo 'modificado';
				// Bitacora
				try{
					$aditionalDescription = " Action Details: id = {$id}";
					addToBitacora("Se ha modificado una Especialidad",$aditionalDescription);
				}catch(Exception $e){}
		}
		else{
			echo 'existente';
		}
	}
	else{
		echo 'Ingrese una especialidad válida.';
	}

	function addToBitacora($description,$aditionalDescription){
		try {
	
			require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
			
			session_start();
			BitacoraLogger::$currentUser = $_SESSION["id_personal"];
			BitacoraLogger::$function = 61;
			BitacoraLogger::$description = $description;
			BitacoraLogger::$aditionalDescription = $aditionalDescription;
			BitacoraLogger::setLogPersonal();    		
		} catch (Exception $e) {}
	}
?>