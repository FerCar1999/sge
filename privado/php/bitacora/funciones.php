<?php
    $id = isset($_POST['id']) ? $_POST['id'] : "Personal";
    
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	$data=Database::getRows("SELECT id, nombre FROM funciones_bitacora WHERE tipo=?",array($id));	
	foreach($data as $row)
	{
		$data_funciones = array();
		$data_funciones["id"] = $row["id"];
		$data_funciones["nombre"] = $row["nombre"];		
		$jsondataList[] = $data_funciones;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();
?>