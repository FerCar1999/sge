<?php
	// obtiene el id_permiso
	$id_permiso = isset($_POST["id_permiso"]) && intval($_POST["id_permiso"]) > 0 ? intval($_POST["id_permiso"])	: 0;
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	
	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($id_permiso)) {
		echo "Registro inválido.";
		exit();
	}

	if($id_permiso>0){
		//consulta que deseamos realizar a la db	
		$query = "select id_modulo from permiso_modulo where id_permiso={$id_permiso}";		
		require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
		$con = Database::conect_read();
		
		//prepara y ejecuta la consulta 
		$stmt = $con->prepare($query);
		$stmt->execute();
		$jsondataList = array();
		$stmt->bind_result($id_modulo);	
		
		// asigna el resultado a jsondataList
		while ($stmt->fetch()) {
	        $modulos = array();
			$modulos["id"] = $id_modulo;
			$jsondataList[]=$modulos;
	    }
	    $stmt->close();
	    // cierra la conexion 

		$jsondata["lista"] = array_values($jsondataList);	
		header("Content-type:application/json; charset = utf-8");
		echo json_encode($jsondata);
	}
	exit();
?>