<?php
	// obtiene las variables del metodo post
	$id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
	$nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]): "";
	$nivel = isset($_POST["nivel"]) ? trim($_POST["nivel"]): "";
	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();
	if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}


	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($nivel)) {
		echo "Nivel inválido.";
		exit();
	}
	if (!validarNombre($nombre)) {
		echo "Nombre inválido.";
		exit();	
	}

	// require la clase dabase
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	if($id === null && !empty($nombre) && strlen($nombre)>3 && strlen($nombre) < 36){

		if ($nombre === "") {
    		echo "camposFalta";
    		exit();
		}

		$sql = "INSERT INTO grados(nombre,id_nivel) VALUES(?,?)";
		// consulta sql
 		$params = array(strip_tags($nombre),strip_tags($nivel));
		// ejecuta la consulta
 		$last_id=Database::executeRow($sql, $params,"INSERT");
 		if($last_id>0){
			echo 'agregado';
		}
		else{
			echo 'Grado existente.';
		}

	}
	else if ($id > 0 && !empty($nombre) && strlen($nombre)>3 && strlen($nombre) < 36){
		
		$sql = "UPDATE grados SET nombre=?, id_nivel=? WHERE id_grado=?";

		$params = array(strip_tags($nombre),strip_tags($nivel),strip_tags($id));
		// ejecuta la consulta
		if(Database::executeRow($sql, $params)){
			echo 'modificado';
		}
		else{
			echo 'existente';
		}
	}
	else{
		echo 'Ingrese un grado válido.';
	}
?>