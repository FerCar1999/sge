<?php 
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;	
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	$data=Database::getRows("SELECT i.id_impuntualidad AS id,CONCAT(e.nombres,' ',e.apellidos) AS nombre, i.fecha_hora AS fecha, CONCAT(e.codigo,' ', e.apellidos, ', ', e.nombres) AS alumno FROM impuntualidad i, estudiantes e WHERE i.fecha_hora BETWEEN DATE_SUB(NOW(), INTERVAL 12 HOUR) AND (SELECT NOW()) AND i.id_estudiante = e.id_estudiante and tipo = 'Institución' ORDER BY i.fecha_hora",array());	
	foreach($data as $row)
	{
		$data_tardanza = array();
		$data_tardanza['id']=$row["id"];
		$data_tardanza["nombre"] = $row["nombre"];
		$data_tardanza["fecha"] = $row["fecha"];
		$data_tardanza["alumno"] = $row["alumno"];
		$jsondataList[]=$data_tardanza;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	
?>
