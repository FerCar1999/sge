<?php 	
// base de datos
require_once("database.php");
date_default_timezone_set('UTC');
$numero_dia = date('N');

if(($numero_dia-1) < 5){
	// obtiene los alumnos que no asistieron y agrege estado
	$data=Database::getRows('select e.id_estudiante from estudiantes e where  e.id_estudiante not in (select a.id_estudiante from asistencias a where date(a.fecha_hora) = "2017-05-04")  and e.estado="Activo" and e.id_estudiante not in (select id_estudiante from ausencias_justificadas auj where date("2017-05-04") between auj.inicio and auj.fin)',array());
	foreach ($data as $row) {
		//envia la fecha
		$date = "2017-05-04";

		if(verificarInasistenciaTotal($date,$row['id_estudiante'])){
			
			$lista=Database::executeRow('insert into inasistencias_totales(fecha,id_estudiante) values("2017-05-04",?)',array($row['id_estudiante']));		
		}
		
	}	
}else {
	echo "Fines de semana no soportados.";
}

function verificarInasistenciaTotal($fecha,$id_estudiante){
	
	date_default_timezone_set('UTC');	
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
	require_once("database.php");

	$alumno = Database::getRow('select * from estudiantes where id_estudiante=?',array($id_estudiante));


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
					ORDER BY t.hora_inicial",array($alumno['id_grupo_tecnico'],$alumno['id_seccion'],$alumno['id_grado'],$alumno['id_especialidad'],$dia));


	$inasistencia_total = false;
	// materias tecnicas
	foreach ($materiasTecnicas as $key) {
			
			$asistencia = Database::getRow('select count(*) as cantidad from asistencias where date(fecha_hora) = ? and id_horario = ?',array($fecha,$key["id_horario"]));

			// si hay registros es porque si se ha pasado lista			
			if(intval($asistencia['cantidad']) > 0) {
				$inasistencia_total  = true;
				
			}
	}
	// materias academicas		
		foreach ($materiasAcademicas as $key) {		

			
			$asistencia = Database::getRow('select count(*) as cantidad from asistencias where date(fecha_hora) = ? and id_horario = ?',array($fecha,$key["id_horario"]));
			$key["id_horario"];
			// si hay registros es porque si se ha pasado lista
			if(intval($asistencia['cantidad']) > 0){
				
				$inasistencia_total  = true;
			} 

		}
//	echo "Fecha ".$fecha." dia de la semana ".$dia;
//	var_dump($inasistencia_total);
	return $inasistencia_total;
}
?>
