<?php
	// obtiene los valores de la peticion y los valida
	$limit = isset($_POST["limit"]) && intval($_POST["limit"]) > 0 ? intval($_POST["limit"])	: 100;
	$offset = isset($_POST["offset"]) && intval($_POST["offset"])>=0	? intval($_POST["offset"])	: 0;
	$busqueda = isset($_POST["busqueda"]) ? trim($_POST["busqueda"]	): "";
	$order_by = isset($_POST["orden"]) ? $_POST["orden"] : "nombre";


	//consulta que deseamos realizar a la db	
	$query = "select id_modulo,nombre from modulos where id_modulo>0 ";
	//consulta para crear la paginacion 
	$query_count ="select count(*) as total from modulos where id_modulo>0 ";
	//busquedas: like nombre,id etc...
	$campos_like="nombre, id_modulo";
	
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/read.php");// read.php prepara la consulta apartir de los 
	// read.php contiene $stmt para poder leer el resultado
	$stmt->bind_result($id_modulo, $nombre);	
	
	// asigna el resultado a jsondataList
	while ($stmt->fetch()) {
        $modulos = array();
		$modulos["id"] = $id_modulo;
		$modulos["nombre"] = " ".$nombre;

		$jsondataList[]=$modulos;
    }
    $stmt->close();
    // cierra la conexion 

	$jsondata["lista"] = array_values($jsondataList);	
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();
?>