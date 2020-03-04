<?php 
	// evalua los valores del POST
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";
	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();
	if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($id)) {
		echo "Registro inválido.";
		exit();
	}
	if ((!$estado == "Activo" || !$estado == "Inactivo")){
		echo "Estado inválido.";
		exit();
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	// si es un id valido
	if($id > 0 ){
		$sql ="update permisos set estado=? where id_permiso=?";
 		$params = array(strip_tags($estado),strip_tags($id));
 
		if(Database::executeRow($sql, $params)){
		    
		    echo 'Cambios guardados con éxito.';    
		}	
	}
 ?>