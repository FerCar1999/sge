<?php
	// obtiene las variables del metodo post
	$id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
	$nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]): "";
	$capacidad = isset($_POST["capacidad"]) ? trim($_POST["capacidad"]): 0;
	$idTipoLocal = isset($_POST["idTipoLocal"]) ? trim($_POST["idTipoLocal"]): "";
	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();
	if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}


	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($capacidad)) {
		echo "Capacidad inválida.";
		exit();
	}
	if (!validarNumeroEntero($idTipoLocal)) {
		echo "Tipo de local inválido.";
		exit();
	}
	

	// require la clase dabase
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	if($id === null && !empty($nombre) && strlen($nombre)>0 && strlen($nombre) < 36){

		if ($nombre === "") {
    		echo "camposFalta";
    		exit();
		}

		$sql = "INSERT INTO locales(nombre, capacidad, id_tipo_local) VALUES(?,?,?)";
		// consulta sql
 		$params = array(strip_tags($nombre),strip_tags($capacidad),strip_tags($idTipoLocal));
		// ejecuta la consulta
 		$last_id=Database::executeRow($sql, $params,"INSERT");
 		if($last_id>0){
			echo 'agregado';
		}
		else{
			echo 'Local existente.';
		}

	}
	else if ($id > 0 && !empty($nombre) && strlen($nombre)>0 && strlen($nombre) < 36){
		
		$sql = "UPDATE locales SET nombre=?, capacidad=?, id_tipo_local=? WHERE id_local=?";

		$params = array(strip_tags($nombre),strip_tags($capacidad),strip_tags($idTipoLocal),strip_tags($id));
		// ejecuta la consulta
		if(Database::executeRow($sql, $params)){
			echo 'modificado';
		}
		else{
			echo 'existente';
		}
	}
	else{
		echo 'Ingrese un local válido.';
	}
?>