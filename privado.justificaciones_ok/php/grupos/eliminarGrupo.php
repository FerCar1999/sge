<?php 
	// evalua los valores del POST
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";
	$grupoTipo = isset($_POST["grupoTipo"]) ? $_POST["grupoTipo"] : 0;

	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();
	if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($id)) {
		echo "Registro inválido.";
		exit();
	}
	if (!validarNumeroEntero($grupoTipo)) {
		echo "Tipo de grupo inválido.";
		exit();
	}
	if (!$estado == "Activo" || !$estado == "Inactivo"){
		echo "Estado inválido.";
		exit();
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	// si es un id valido
	if($id > 0 ){
		$sql = "";
		if ($grupoTipo == 1) {
			$sql ="UPDATE grupos_academicos SET estado=? WHERE id_grupo_academico=?";	
		}
		if ($grupoTipo == 2) {
			$sql ="UPDATE grupos_tecnicos SET estado=? WHERE id_grupo_tecnico=?";	
		}
 		$params = array(strip_tags($estado),strip_tags($id));
 
		if(Database::executeRow($sql, $params)){
		    
		    echo 'Cambios guardados con éxito.';    
		}	
	}
 ?>