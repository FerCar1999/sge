<?php 
	$id = isset($_POST['id']) ? intval($_POST['id']) : 8;
	$id_tipo_asignatura = isset($_POST['id_tipo_asignatura']) ? intval($_POST['id_tipo_asignatura']) : 2;
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	$data=Database::getRows("select id_asignatura,nombre from asignaturas where id_tipo_asignatura=? and id_grado=? and estado='Activo'",array($id_tipo_asignatura,$id));

	
	foreach($data as $row)
	{
		$data_asignatura = array();
		$data_asignatura['id']=$row["id_asignatura"];
		$data_asignatura["nombre"] = $row["nombre"];
		
		$jsondataList[]=$data_asignatura;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	

 ?>