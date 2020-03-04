<?php
	// obtiene las variables del metodo post
	$id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
	$nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]): "";
	$descripcion = isset($_POST["descripcion"]) ? trim($_POST["descripcion"]): "";
	$idTipoCodigo = isset($_POST["idTipoCodigo"]) ? trim($_POST["idTipoCodigo"]): "";

	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();
	if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarTexto($nombre)) {
		echo "Nombre inválido.";
		exit();
	}
	if (!validarTexto($descripcion)) {
		echo "Descripción inválida.";
		exit();
	}
	if (!validarNumeroEntero($idTipoCodigo)){
		echo "Tipo de código inválido.";
		exit();
	}

	// require la clase dabase
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	if($id === null && !empty($nombre) && strlen($nombre)>0){

		if ($nombre === "" || $descripcion === "") {
    		echo "camposFalta";
    		exit();
		}

		$sql = "INSERT INTO codigos(nombre, descripcion, id_tipo_codigo) VALUES(?,?,?)";
		// consulta sql
 		$params = array(strip_tags($nombre),strip_tags($descripcion),strip_tags($idTipoCodigo));
		// ejecuta la consulta
 		$last_id=Database::executeRow($sql, $params,"INSERT");
 		if($last_id>0){
			echo 'agregado';
		}
		else{
			echo 'Código existente.';
		}

	}
	else if ($id > 0 && !empty($nombre) && strlen($nombre)>0){
		
		$sql = "UPDATE codigos SET nombre=?, descripcion=?, id_tipo_codigo=? WHERE id_codigo=?";

		$params = array(strip_tags($nombre),strip_tags($descripcion),strip_tags($idTipoCodigo),strip_tags($id));
		// ejecuta la consulta
		if(Database::executeRow($sql, $params)){
			echo 'modificado';
		}
		else{
			echo 'existente';
		}
	}
	else{
		echo 'Nombre de código extenso.';
	}
?>