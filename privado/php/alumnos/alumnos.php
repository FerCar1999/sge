<?php
	// obtiene las variables del metodo post
	$id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
	$nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]): "";
	$apellido = isset($_POST["apellido"]) ? trim($_POST["apellido"]): "";
	$codigo = isset($_POST["codigo"]) ? trim($_POST["codigo"]): "";
	$correo = isset($_POST["correo"]) ? trim($_POST["correo"]): "";
	$clave = isset($_POST["clave"]) ? trim($_POST["clave"]): "";
	$url_foto = isset($_POST["url_foto"]) ? $_POST["url_foto"]: "";
	$idEspecialidad = isset($_POST["idEspecialidad"]) ? $_POST["idEspecialidad"]: 0;
	$idGrado = isset($_POST["idGrado"]) ? $_POST["idGrado"]: "";
	$idGrupoAcad = isset($_POST["idGrupoAcad"]) ? $_POST["idGrupoAcad"]: 0;
	$idGrupoTec = isset($_POST["idGrupoTec"]) ? $_POST["idGrupoTec"]: 0;
	$idSeccion = isset($_POST["idSeccion"]) ? $_POST["idSeccion"]: "";
	$idPersonal = isset($_POST["idPersonal"]) ? $_POST["idPersonal"]: "";
	
	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();
	if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}
	
	if ($idGrupoAcad == "null") $idGrupoAcad = 1;
	if ($idGrupoTec == "null") $idGrupoTec = 4;
	if ($idEspecialidad == "null") $idEspecialidad = 11;

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarCodigoAlumno($codigo)) {
		echo "Código inválido.";
		exit();
	}
	if (!validarNombre($nombre)){
		echo "Nombres con caracteres inválidos.";
		exit();
	}
	if (!validarNombre($apellido)){
		echo "Apellidos con caracteres inválidos.";
		exit();
	}
	if (!validarTexto($clave) && $clave != ""){
		echo "Contraseña con caracteres inválidos.";
		exit();
	}
	if (!validarCorreo($correo) && $correo != "" && $correo != "null"){
		echo "Correo con caracteres inválidos o incorrecto.";
		exit();
	}
	if (!validarNumeroEntero($idGrado)){
		echo "Grado inválido.";
		exit();
	}
	if (!validarNumeroEntero($idSeccion)){
		echo "Sección inválida.";
		exit();
	}
	if (!validarNumeroEntero($idPersonal)){
		echo "Docente guía inválido.";
		exit();
	}
	if (!validarNumeroEntero($idGrupoTec)){
		echo "Grupo técnico inválido.";
		exit();
	}
	if (!validarNumeroEntero($idGrupoAcad)){
		echo "Grupo académico inválido.";
		exit();
	}
	if (!validarNumeroEntero($idEspecialidad)){
		echo "Especialidad inválida.";
		exit();
	}

	// require la clase dabase
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	if($id === null && !empty($nombre) && strlen($nombre)>2 && strlen($nombre) < 36){

		if ($nombre === "" || !isset($_FILES['fotoAlumno'])) {
    		echo "camposFalta";
    		exit();
		}

		$rutaDestino = "";

		if (isset($_FILES['fotoAlumno'])) {

			// Moviendo imagen a ruta de imagenes
			$rutaTemporal = $_FILES['fotoAlumno']['tmp_name'];

			if ($rutaTemporal != "") {
	        		$rutaDestino = "/media/img/alumnos/".uniqid().".jpg";
			}else{
				if($rutaDestino === ""){
					$rutaDestino = "/media/img/alumnos/alumnoDefault.jpg";
    			}
			}
		}
		// consulta sql
 		$sql ="INSERT INTO estudiantes(nombres, apellidos, correo, codigo, foto, clave, id_grado, id_especialidad, id_seccion, id_grupo_academico, id_grupo_tecnico, id_personal) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
 		$params = array(strip_tags($nombre),strip_tags($apellido),strip_tags($correo),strip_tags($codigo),$rutaDestino,password_hash($clave,PASSWORD_DEFAULT),strip_tags($idGrado), strip_tags($idEspecialidad),strip_tags($idSeccion), strip_tags($idGrupoAcad), strip_tags($idGrupoTec),  strip_tags($idPersonal));
		// ejecuta la consulta
 		$last_id=Database::executeRow($sql, $params,"INSERT");
 		if($last_id>0){
			echo 'agregado';
			
			// Bitacora
			try{
				$aditionalDescription = " Action Details: codigo = {$codigo}";
				addToBitacora("Estudiante Agregado",$aditionalDescription);
			}catch(Exception $e){}

			if ($rutaTemporal == "") {
				$rutaTemporal = "/media/img/alumnos/alumnoDefault.png";
				if (!move_uploaded_file($_SERVER['DOCUMENT_ROOT'].$rutaTemporal, $_SERVER['DOCUMENT_ROOT'].$rutaDestino)){
            		//echo "error";
            		exit();
        		}
			}
			else if (isset($_FILES['fotoAlumno'])) {
				if (!move_uploaded_file($rutaTemporal, $_SERVER['DOCUMENT_ROOT'].$rutaDestino)){
            		//echo "error";
            		exit();
        		}
			}
		}
		else{
			echo 'Estudiante existente.';
		}

	}
	else if ($id > 0 && !empty($nombre) && strlen($nombre)>2 && strlen($nombre) < 36){
		if (!is_uploaded_file($_FILES['fotoAlumnoMod']['tmp_name'])) {
			//sql
			$sql ="UPDATE estudiantes SET nombres=?, apellidos=?, codigo=?, correo=?, id_grado=?,id_especialidad=?,id_seccion=?, id_grupo_academico=?, id_grupo_tecnico=?, id_personal=? WHERE id_estudiante=?";
			$params = array(strip_tags($nombre),strip_tags($apellido),strip_tags($codigo),strip_tags($correo),strip_tags($idGrado), strip_tags($idEspecialidad),strip_tags($idSeccion), strip_tags($idGrupoAcad), strip_tags($idGrupoTec), strip_tags($idPersonal),strip_tags($id));
			// ejecuta la consulta
			if(Database::executeRow($sql, $params)){
				echo 'Estudiante modificado.';
					// Bitacora
				try{
					$aditionalDescription = " Action Details: codigo = {$codigo}";
					addToBitacora("Estudiante Modificado",$aditionalDescription);
				}catch(Exception $e){}
			}
			else{
				echo 'Estudiante existente.';
			}
		}else if (isset($_FILES['fotoAlumnoMod'])) {
			echo '4';
			$rutaTemporal = $_FILES['fotoAlumnoMod']['tmp_name'];
			$rutaDestino = substr($url_foto, 0, -4);
			if ($url_foto === "/media/img/alumnos/alumnoDefault.png" && $rutaTemporal != "") {
					$rutaDestino = "/media/img/alumnos/".uniqid().".png";
			}else{
					$rutaDestino = $rutaDestino.".jpg";
			}

    		if (!move_uploaded_file($rutaTemporal, $_SERVER['DOCUMENT_ROOT'].$rutaDestino)){
            	//echo "error";
            	exit();
			}

        	$sql ="UPDATE estudiantes SET nombres=?, apellidos=?, codigo=?, correo=?, foto=?, id_grado=?, id_especialidad=?, id_personal=? WHERE id_estudiante=?";

			$params = array(strip_tags($nombre),strip_tags($apellido),strip_tags($codigo),strip_tags($correo),$rutaDestino,strip_tags($idGrado), strip_tags($idEspecialidad), strip_tags($idPersonal),strip_tags($id));
				// ejecuta la consulta
			if(Database::executeRow($sql, $params)){
				echo 'Estudiante modificado.';
			}
			else{
				echo 'Estudiante existente.';
			}
		}
	}
	else{
		echo 'Ingrese un estudiante válido.';
	}



	function addToBitacora($description,$aditionalDescription){
		try {
	
			require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
			
			session_start();
			BitacoraLogger::$currentUser = $_SESSION["id_personal"];
			BitacoraLogger::$function = 45;
			BitacoraLogger::$description = $description;
			BitacoraLogger::$aditionalDescription = $aditionalDescription;
			BitacoraLogger::setLogPersonal();    		
		} catch (Exception $e) {}	
	}

?>