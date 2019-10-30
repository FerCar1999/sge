<?php 
	date_default_timezone_set('UTC');
	$id = isset($_POST['id']) ? intval($_POST['id']) : null;	
	$fecha = isset($_POST["fecha"]) ? trim($_POST["fecha"]) : null;
	
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	
	$unixTimestamp = strtotime($fecha);
	$dayOfWeek = date("l", $unixTimestamp);
	$dia = "Lunes";

	switch ($dayOfWeek) {
		case 'Monday':
			$dia = "Lunes";
		break;
		case 'Tuesday':
			$dia = "Martes";
		break;
		case 'Wednesday':
			$dia = "Miercoles";
		break;
		case 'Thursday':
			$dia = "Jueves";
		break;
		case 'Friday':
			$dia = "Viernes";
		break;			
	}

	$data=Database::getRows("select h.id_horario,t.hora_inicial,t.hora_final,a.nombre as materia from horarios h,asignaturas a, tiempos t  where h.id_personal = ? and h.id_asignatura = a.id_asignatura and h.id_tiempo = t.id_tiempo and h.dia = ? and ? between h.inicio and h.fin order by hora_inicial asc",array($id,$dia,$fecha));	
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