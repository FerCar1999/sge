<?php 
	$id = isset($_POST['id']) ? intval($_POST['id']) : 8;	
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	$data=Database::getRows("select id_codigo,nombre from codigos where id_tipo_codigo=? and estado='Activo'",array($id));	
	foreach($data as $row)
	{
		$data_codigo = array();
		$data_codigo['id']=$row["id_codigo"];
		$data_codigo["nombre"] = $row["nombre"];		
		$jsondataList[]=$data_codigo;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	

 ?>