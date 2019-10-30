<?php 
	date_default_timezone_set('UTC');
	$id = isset($_POST['id']) ? intval($_POST['id']) : null;	
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	
	$dia = array('Lunes','Martes','Miercoles','Jueves','Viernes');
	$numero_dia = date('N');
	$dias = $dia[$numero_dia-1];
	//$dias ="Lunes";

	$data=Database::getRows("select h.id_horario,t.hora_inicial,t.hora_final,a.nombre as materia from horarios h,asignaturas a, tiempos t  where h.id_personal = ? and h.id_asignatura = a.id_asignatura and h.id_tiempo = t.id_tiempo and h.dia = ? and CURDATE() between h.inicio and h.fin",array($id,$dias));	
	foreach($data as $row)
	{
		$data_materia = array();
		$data_materia['id']=$row["id_horario"];
		$data_materia["nombre"] = $row["hora_inicial"].' - '.$row["hora_final"].' '.$row['materia'];		
		$jsondataList[]=$data_materia;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	

 ?>