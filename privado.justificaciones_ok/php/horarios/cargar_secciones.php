<?php 
	$id = isset($_POST['id']) ? intval($_POST['id']) : 8;
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	$data=Database::getRow("select cantidad_secciones,nombre from niveles where id_nivel=(select id_nivel from grados where id_grado=?)",array($id));

	$secciones = Database::getRows("select id_seccion,nombre from secciones limit ".intval($data['cantidad_secciones']), array());
	foreach($secciones as $row)
	{
		$data_seccion = array();
		$data_seccion['id']=$row["id_seccion"];
		$data_seccion["nombre"] = $row["nombre"];
		$data_seccion["nivel"] = $data["nombre"];
		
		$jsondataList[]=$data_seccion;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	

 ?>