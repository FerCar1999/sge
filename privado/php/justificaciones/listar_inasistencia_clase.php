<?php 
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$inicio = isset($_POST['inicio']) ? $_POST['inicio'] : "";
	$fin = isset($_POST['fin']) ? $_POST['fin'] : "";	
	
	$response = array();
	// obtiene el horario de cada dia
	for($i=$inicio;$i<=$fin;$i = date("Y/m/d", strtotime($i ."+ 1 days"))){
		
		$alumno = getAlumno($id);
    //OBTENER EL DÍA QUE SE QUIERE CONSULTAR
    $datetime = DateTime::createFromFormat('Y/m/d', $i);
    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$nombreDia =  $dias[$datetime->format('w')];
		
		// obtiene clase del alumno
		$claseDia = getDia($alumno[0][0], $alumno[0][1], $alumno[0][6], $alumno[0][3], $alumno[0][2], $nombreDia,$fin);		
		foreach($claseDia as $clase){

			$asistencias = Database::getRow('select count(*) as cantidad from asistencias where date(fecha_hora) = ? and id_horario = ?',array($i,$clase[0]));			
			// si hay registros es porque si se ha pasado lista
			if(intval($asistencias['cantidad']) > 0){
				$asistencia = Database::getRow('select count(*) as cantidad from asistencias where date(fecha_hora) = ? and id_horario = ? and id_estudiante = (select id_estudiante from estudiantes where codigo = ?)',array($i,$clase[0],$id));			
				if(intval($asistencia["cantidad"]) > 0){
					$data['id']=0;
					$data['estado']='ASISTENCIA';	
					$data['estado_inasistencia'] = "";
				}else {

					// verifica que la inasistencia realmente exista										
					$inasistencia = Database::getRow('select  id_inasistencia,estado from inasistencias_clases where id_horario = ? and id_estudiante = (select id_estudiante from estudiantes where codigo = ?)  and  date(fecha_hora) = ?',array($clase[0],$id,$i));	
					if($inasistencia['id_inasistencia'] != null) {
						$data['estado']='INASISTENCIA';
						$data['id']=$inasistencia["id_inasistencia"];
						$data['estado_inasistencia'] = $inasistencia["estado"] != null  ? $inasistencia["estado"] : "Injustificada";
					}else {
						$data['id']=$clase[0];
						$data['estado']='NO PASO LISTA';
						$data['estado_inasistencia'] = "";
					}
					
				}		
			}else {
				// id inasistencia
				$data['id']=$clase[0];
				$data['estado']='NO PASO LISTA';
				$data['estado_inasistencia'] = "";
			}
							
				$data['fecha']=$i;
				$data["bloque"] = $clase[1]." - ".$clase[2];
				$data["materia"] = $clase[3]; 								
				$data["id_horario"] = $clase[0]; 								
				$response[]=$data;
		}			
	}
	$jsondata["lista"] = array_values($response);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	function getAlumno($pk){
		$sql = "SELECT
								e.id_grupo_academico,
								e.id_grupo_tecnico,
								g.id_grado,
								s.id_seccion,
								IF(ep.nombre != 'Ninguna',
								CONCAT(g.nombre,' ',ep.nombre,' ', gt.nombre,s.nombre),
								CONCAT(g.nombre,' ',s.nombre)) AS encabezadoTec,
								IF(ep.nombre != 'Ninguna',
								CONCAT(g.nombre,' ', s.nombre, ga.nombre),
								CONCAT(g.nombre,' ', s.nombre)) AS encabezadoAcad,
								e.id_especialidad
						FROM estudiantes e, grupos_academicos ga, grupos_tecnicos gt, grados g, secciones s, especialidades ep
						WHERE e.codigo= ?
								AND e.id_grupo_academico = ga.id_grupo_academico
								AND e.id_grupo_tecnico = gt.id_grupo_tecnico
								AND e.id_seccion = s.id_seccion
								AND g.id_grado = e.id_grado								
								AND ep.id_especialidad = e.id_especialidad";
		$params = array($pk);
		$data = Database::getRows($sql, $params);
		return $data;
}

function getDia($grupo_academico, $grupo_tecnico, $especialidad, $seccion, $grado, $dia,$fecha){
		$sql = "SELECT
				h.id_horario, 
				t.hora_inicial AS hora1,
				t.hora_final AS hora2,
				a.nombre AS asignatura,
				CONCAT(p.nombre, ' ', p.apellido) AS docente,
				h.dia AS dia,
				l.nombre AS local
		FROM horarios h, tiempos t, asignaturas a, locales l, grados g, personal p
		WHERE h.id_tiempo = t.id_tiempo 
				AND h.id_asignatura = a.id_asignatura 
				AND h.id_local = l.id_local 
				AND h.id_grado = g.id_grado
				AND h.id_personal = p.id_personal
				AND (h.id_grupo_academico = ? OR h.id_grupo_tecnico = ? AND h.id_especialidad = ?)
				AND h.id_seccion = ?
				AND h.id_grado = ?
				AND h.dia = ?
				AND date(?) between h.inicio and h.fin
		ORDER BY t.hora_inicial";
		$params = array($grupo_academico, $grupo_tecnico, $especialidad, $seccion, $grado, $dia,$fecha);
		$data = Database::getRows($sql, $params);
		return $data;
}

 ?>