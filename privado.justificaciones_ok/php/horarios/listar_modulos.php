<?php 
	$id = isset($_POST['id']) ? intval($_POST['id']) : 8;
	$dia = isset($_POST["dia"]) ? $_POST["dia"] : "";
	session_start();
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	$data=Database::getRows('
	select lo.nombre as lugar,id_grupo_academico,id_grupo_tecnico,sec.nombre as seccion,gr.nombre as grado,asig.nombre as materia,TIME_FORMAT(ts.hora_inicial, "%H:%i") as hinicio,TIME_FORMAT(ts.hora_final, "%H:%i") as final, esp.nombre as especialidad, h.id_horario as id,h.inicio, ts.id_tiempo,h.dia,h.fin as fecha_final
from horarios h,locales lo,secciones sec,grados gr,asignaturas asig, tiempos ts, especialidades esp 
where h.id_personal=? and dia=? and h.id_local=lo.id_local and h.id_seccion= sec.id_seccion and 
h.id_grado = gr.id_grado and h.id_asignatura=asig.id_asignatura and h.id_tiempo=ts.id_tiempo and h.id_especialidad=esp.id_especialidad   and ts.id_tiempo = ? order by ts.hora_inicial asc
		',array($_SESSION["id_personal"],$dia,$id));
	
	foreach($data as $row)
	{
		$data_seccion = array();
		$data_seccion['id']=$row["id"];
		$data_seccion["materia"] = $row["materia"];
		$data_seccion["inicio"] = $row["inicio"];
		$data_seccion["final"] = $row["fecha_final"];				
		$jsondataList[]=$data_seccion;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	
 ?>