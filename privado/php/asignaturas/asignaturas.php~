<?php
	// obtiene las variables del metodo post
	$id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
	$nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]): "";
	$codigo = isset($_POST["codigo"]) ? trim($_POST["codigo"]): "";
	$tipoAsignatura = isset($_POST["tipoAsignatura"]) ? trim($_POST["tipoAsignatura"]): "";
	$id_grado = isset($_POST["id_grado"]) ? trim($_POST["id_grado"]): "";
	$estado = isset($_POST["estado"]) ? trim($_POST["estado"]): "";
	
	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();
	if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}
	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");
	
	if (!validarTexto($codigo)) {
		echo "Código inválido.";
		exit();
	}
	if (!validarNombre($nombre)){
		echo "Nombre con caracteres inválidos.";
		exit();
	}
	/*if (!$estado == "Activo" || !$estado == "Inactivo")){
		echo "Estado inválido.";
		exit();
	}*/
	if (!validarNumeroEntero($id_grado)){
		echo "Grado inválido.";
		exit();
	}
	if (!validarNumeroEntero($tipoAsignatura)){
		echo "Asignatura inválida.";
		exit();
	}

	// require la clase dabase
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	if($id === null && !empty($nombre) && strlen($nombre)>0 && strlen($nombre) < 100){

		if ($nombre === "" || $codigo === "") {
    		echo "camposFalta";
    		exit();
		}

		$sql = "INSERT INTO asignaturas(nombre, codigo, id_tipo_asignatura, id_grado, estado) VALUES(?,?,?,?,?)";
		// consulta sql
 		$params = array(strip_tags($nombre),strip_tags($codigo), strip_tags($tipoAsignatura),strip_tags($id_grado),strip_tags($estado));
		// ejecuta la consulta
 		$last_id=Database::executeRow($sql, $params,"INSERT");
 		if($last_id>0){
			echo 'agregado';
		}
		else{
			echo 'Asignatura existente.';
		}

	}
	else if ($id > 0 && !empty($nombre) && strlen($nombre)>0 && strlen($nombre) < 100){
		
		$sql = "UPDATE asignaturas SET nombre=?,codigo=?,id_tipo_asignatura=?,estado=?  WHERE id_asignatura=?";

		$params = array(strip_tags($nombre),strip_tags($codigo), strip_tags($tipoAsignatura),strip_tags($estado),strip_tags($id));
		// ejecuta la consulta
		if(Database::executeRow($sql, $params)){
			echo 'modificado';
		}
		else{
			echo 'existente';
		}
	}
	else{
		echo 'Ingrese una asignatura válida.';
	}
?>
