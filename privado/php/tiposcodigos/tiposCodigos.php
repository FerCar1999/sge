<?php
	// obtiene las variables del metodo post
	$id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
	$nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]): "";
	$descripcion = isset($_POST["descripcion"]) ? trim($_POST["descripcion"]): "";
	$cantidad = isset($_POST["cantidad"]) ? trim($_POST["cantidad"]): "";
	$escala = isset($_POST["escala"]) ? trim($_POST["escala"]): "";
	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();
	

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNombre($nombre)) {
		echo "Nombre inválido.";
		exit();
	}
	if (!validarNumeroEntero($escala)) {
		echo "Escala inválida.";
		exit();
	}
	if (!validarTexto($descripcion)) {
		echo "Descripción inválida.";
		exit();
	}
	if (!validarNumeroEntero($cantidad)) {
		echo "Cantidad inválida.";
		exit();
	}

	// require la clase dabase
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	$sql = "SELECT id_tipo_codigo FROM tipos_codigos  WHERE escala=? AND estado='Activo' AND id_tipo_codigo != ?";
    $params = array($escala,$id);
	$data = Database::getRows($sql, $params);
	foreach($data as $row)
	{
		if ($row["id_tipo_codigo"] != "") {
			echo "Ya existe una tipo de código en esa escala.";
			exit();
		}
	}

	if($id === null && !empty($nombre) && strlen($nombre)>0 && strlen($nombre) < 36){

		if ($nombre === "" || $descripcion === "") {
    		echo "camposFalta";
    		exit();
		}

		$sql = "INSERT INTO tipos_codigos(nombre, descripcion, cantidad, escala) VALUES(?,?,?,?)";
		// consulta sql
 		$params = array(strip_tags($nombre),strip_tags($descripcion),strip_tags($cantidad),strip_tags($escala));
		// ejecuta la consulta
 		$last_id=Database::executeRow($sql, $params,"INSERT");
 		if($last_id>0){			
			try{
				$aditionalDescription = " Action Details: nombre = {$nombre}";
				addToBitacora("Se ha agregado un nuevo Tipo Codigo",$aditionalDescription);
			}catch(Exception $e){}
			
			echo 'agregado';
		}
		else{
			echo 'Tipo de código existente.';
		}

	}
	else if ($id > 0 && !empty($nombre) && strlen($nombre)>0 && strlen($nombre) < 36){
		
		$sql = "UPDATE tipos_codigos SET nombre=?, descripcion=?, cantidad=?, escala=? WHERE id_tipo_codigo=?";

		$params = array(strip_tags($nombre),strip_tags($descripcion),strip_tags($cantidad),strip_tags($escala),strip_tags($id));
		// ejecuta la consulta
		if(Database::executeRow($sql, $params)){
			try{
				$aditionalDescription = " Action Details: id = {$id}";
				addToBitacora("Se ha modificado Un Tipo Codigo",$aditionalDescription);
			}catch(Exception $e){}
			echo 'modificado';			
		}
		else{
			echo 'existente';
		}
	}
	else{
		echo 'Ingrese un tipo de código válido.';
	}

	function addToBitacora($description,$aditionalDescription){
		try {
	
			require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
			
			session_start();
			BitacoraLogger::$currentUser = $_SESSION["id_personal"];
			BitacoraLogger::$function = 153;
			BitacoraLogger::$description = $description;
			BitacoraLogger::$aditionalDescription = $aditionalDescription;
			BitacoraLogger::setLogPersonal();    		
		} catch (Exception $e) {}
	}
?>