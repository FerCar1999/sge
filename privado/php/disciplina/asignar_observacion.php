<?php 	
	session_start();
	//evalua los valores del POST
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$pk_codigo = isset($_POST['pk_codigo']) ? intval($_POST['pk_codigo']) : 0;
	$observacion = isset($_POST["observacion"]) ? $_POST["observacion"] : "";
	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	//session_start();
	if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}
	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($pk_codigo)) {
		echo "Código no registrado.";
		exit();
	}
	if (!validarTexto($observacion)){
		echo "Observación con caracteres inválidos.";
		exit();
	}

	//CONSULTA BUENA??
	/*INSERT INTO disciplina(id_estudiante, id_personal, fecha_hora, id_codigo,observacion) SELECT id_estudiante,2,(SELECT NOW()),1,'Ninguna' FROM estudiantes WHERE codigo='20140159'*/

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");	
	// si es un id valido 

	if ($id > 0) {
		$sql = "UPDATE observaciones SET observacion = ? WHERE id_observacion=?";
		$params = array($observacion,$id);
		if(Database::executeRow($sql, $params)){
			echo 'Observación editada correctamente';
			try{
				addToBitacora("Se ha modificado una observacion con ID: {$id} ","Observación modificada: {$observacion}");
			}catch(Exception $e){}
		}
	}else{
		$sql = "INSERT INTO observaciones(id_personal, id_estudiante, fecha, observacion) SELECT ?,id_estudiante,(SELECT NOW()),? FROM estudiantes WHERE codigo=?";
		$params = array($_SESSION["id_personal"],$observacion,$pk_codigo);
		if(Database::executeRow($sql, $params)){
			echo 'Observación asignada correctamente.';
			try{
				addToBitacora("Se ha asignado una observacion al alumno: {$pk_codigo} ","Observación original: {$observacion}");
			}catch(Exception $e){}
		}
	}

	function addToBitacora($description,$aditionalDescription){
		try {
	
			require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
			
			session_start();
			BitacoraLogger::$currentUser = $_SESSION["id_personal"];
			BitacoraLogger::$function = 101;
			BitacoraLogger::$description = $description;
			BitacoraLogger::$aditionalDescription = $aditionalDescription;
			BitacoraLogger::setLogPersonal();    		
		} catch (Exception $e) {}	
	}

?>