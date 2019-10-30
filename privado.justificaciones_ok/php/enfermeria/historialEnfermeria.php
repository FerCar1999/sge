<?php 
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	$data=Database::getRows("SELECT e.id_enfermeria AS id,CONCAT(s.nombres,' ',s.apellidos) AS nombre, p.nombre AS especialidad, e.fecha_hora AS fecha, CONCAT(s.codigo,' ',s.apellidos,', ',s.nombres) AS alumno, e.observacion AS observacion FROM enfermeria e, estudiantes s, especialidades p WHERE e.fecha_hora BETWEEN DATE_SUB(NOW(), INTERVAL 12 HOUR) AND (SELECT NOW()) AND s.id_estudiante = e.id_estudiante AND p.id_especialidad = s.id_especialidad",array());
	foreach($data as $row)
	{
		$data_enfermeria = array();
		$data_enfermeria['id']=$row["id"];
		$data_enfermeria["nombre"] = $row["nombre"];
		$data_enfermeria["fecha"] = $row["fecha"];
		$data_enfermeria["alumno"] = $row["alumno"];
		$data_enfermeria["especialidad"] = $row["especialidad"];
		$data_enfermeria["observacion"] = $row["observacion"];
		$jsondataList[]=$data_enfermeria;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	
?>