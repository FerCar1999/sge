<?php 
	// evalua los valores del POST
	$id = isset($_POST['pk_notificacion']) ? intval($_POST['pk_notificacion']) : 0;
	

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($id)) {
		echo "Registro inválido.";
		exit();
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	// si es un id valido
	if($id > 0 ){
		$sql ="DELETE FROM notificaciones WHERE id_notificacion=?";
 		$params = array(strip_tags($id));
		if(Database::executeRow($sql, $params)){
		    echo 'success';
		}	
	}

?>