<?php 
	date_default_timezone_set('UTC');
	$id = isset($_POST['id']) ? intval($_POST['id']) : null;	
	$tipo = isset($_POST["tipo"]) ? trim($_POST["tipo"]) : "Academicas";
	$fecha = isset($_POST["fecha"]) ? trim($_POST["fecha"]) : date('Y-m-d');


	date_default_timezone_set('UTC');
	$date = date($fecha);
	$unixTimestamp = strtotime($date);
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
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	$alumno = Database::getRow('select * from estudiantes where id_estudiante =(select id_estudiante from estudiantes where codigo = ?)',array($id));

	$horario = array();

	if($tipo==="Academicas"){
		// obtiene materias academicas
			$materiasAcademicas = Database::getRows("SELECT 
						t.hora_inicial AS hora1,
						t.hora_final AS hora2,
						a.nombre AS asignatura,
					    CONCAT(p.nombre, ' ', p.apellido) AS docente,
						h.dia AS dia,
						l.nombre AS local,
						h.id_horario 
					FROM horarios h, tiempos t, asignaturas a, locales l, grados g, personal p
					WHERE h.id_tiempo = t.id_tiempo 
						AND h.id_asignatura = a.id_asignatura 
						AND h.id_local = l.id_local 
						AND h.id_grado = g.id_grado
					    AND h.id_personal = p.id_personal
					    AND h.id_grupo_academico = ?
					    AND h.id_seccion = ?
					    AND h.id_grado = ?					    
					    AND h.dia = ?
					ORDER BY t.hora_inicial",array($alumno['id_grupo_academico'],$alumno['id_seccion'],$alumno['id_grado'],$dia));
		foreach ($materiasAcademicas as $row) {		
			$data_materia = array();
			$data_materia['id']=$row["id_horario"];
			$data_materia["nombre"] = $row["hora1"].' - '.$row["hora2"].' '.$row['asignatura'];		
			$jsondataList[]=$data_materia;
		}


	}else{
	
	// obtiene materias tecnicas
	$materiasTecnicas = Database::getRows("SELECT 
						t.hora_inicial AS hora1,
						t.hora_final AS hora2,
						a.nombre AS asignatura,
					    CONCAT(p.nombre, ' ', p.apellido) AS docente,
						h.dia AS dia,
						l.nombre AS local,
						h.id_horario 
					FROM horarios h, tiempos t, asignaturas a, locales l, grados g, personal p
					WHERE h.id_tiempo = t.id_tiempo 
						AND h.id_asignatura = a.id_asignatura 
						AND h.id_local = l.id_local 
						AND h.id_grado = g.id_grado
					    AND h.id_personal = p.id_personal
					    AND h.id_grupo_tecnico = ?
					    AND h.id_seccion = ?
					    AND h.id_grado = ?
					    AND h.id_especialidad = ?
					    AND h.dia = ?
					ORDER BY t.hora_inicial
		",array($alumno['id_grupo_tecnico'],$alumno['id_seccion'],$alumno['id_grado'],$alumno['id_especialidad'],$dia));

	// materias tecnicas
	foreach ($materiasTecnicas as $row) {
			$data_materia = array();
			$data_materia['id']=$row["id_horario"];
			$data_materia["nombre"] = $row["hora1"].' - '.$row["hora2"].' '.$row['asignatura'];		
			$jsondataList[]=$data_materia;
	}

	}

	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	
	
		
 ?>