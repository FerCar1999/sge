<?php 
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	class ControlBloque
	{
		
		function verificarAsistencia($fecha){
			date_default_timezone_set('UTC');
			$unixTimestamp = strtotime($fecha);
			$dayOfWeek = date("l", $unixTimestamp);
			$dia = "Lunes";
			$tabla = "";
			$n = 0;

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

			$alumnos = Database::getRows("
						SELECT
							e.nombres as nombres,
							e.apellidos as apellidos,
							e.codigo as codigo,
							g.nombre as grado,
							ep.nombre as especialidad,
							IF(ep.nombre != 'Ninguna',
							   CONCAT('Grupo: ', gt.nombre),
							   CONCAT('Grupo: 1')) AS grupo,
							IF(ep.nombre != 'Ninguna',
							   CONCAT(s.nombre,'-' ,ga.nombre),
							   (s.nombre)) AS seccion,
							e.id_estudiante as id_estudiante,
							e.id_grupo_academico as id_grupo_academico,
							e.id_grupo_tecnico as id_grupo_tecnico,
							e.id_seccion as id_seccion,
							e.id_grado as id_grado,
							e.id_especialidad as id_especialidad
							FROM estudiantes e, grupos_academicos ga, grupos_tecnicos gt, grados g, secciones s, especialidades ep
							WHERE e.id_grupo_academico = ga.id_grupo_academico
							AND e.id_grupo_tecnico = gt.id_grupo_tecnico
							AND e.id_seccion = s.id_seccion
							AND g.id_grado = e.id_grado
							AND ep.id_especialidad = e.id_especialidad
							AND e.estado='Activo'
							ORDER BY g.id_grado ASC,
							ep.nombre ASC,
							gt.nombre ASC,
							s.nombre ASC,
							ga.nombre ASC,
							e.apellidos ASC",array());

			$materias = array();
			$tiempos = array();
			$id_tiempos = array();
			$estado = array();
			$aparecer = "NO";	
			foreach ($alumnos as $alumno) {
				// obtiene materias academicas
				$materiasAcademicas = Database::getRows("SELECT 
					t.hora_inicial AS hora1,
					t.hora_final AS hora2,
					a.nombre AS asignatura,
					t.id_tiempo,
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
					t.id_tiempo,
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


				
			$nivel = Database::getRow("select id_nivel from grados where id_grado = ?",array($alumno['id_grado']));


		
		if(intval($nivel["id_nivel"]) == 2){
		// materias tecnicas
		foreach ($materiasTecnicas as $key) {
			$materias[]=$key["asignatura"];
			$tiempos[]=$key["hora1"]."-".$key["hora2"];	
			$id_tiempos[]= $key["id_tiempo"];		

			$asistencia = Database::getRow('select count(*) as cantidad from asistencias where date(fecha_hora) = ? and id_horario = ?',array($fecha,$key["id_horario"]));
			// si hay registros es porque si se ha pasado lista
			if(intval($asistencia['cantidad']) > 0) {
				$estadoTemporal="I";

				$asistenciaAlumno = Database::getRow('select count(*) as cantidad from asistencias where date(fecha_hora) = ? and id_horario = ? and id_estudiante=?',array($fecha,$key["id_horario"],$alumno["id_estudiante"]));
				if(intval($asistenciaAlumno["cantidad"])>0){
					$estadoTemporal="P";					
				}
				// obtiene si tiene inasistencia en la materia
				$inasistencia = Database::getRow('select * from inasistencias_clases where id_estudiante =? and id_horario =? and date(fecha_hora) = date(?)',array($alumno["id_estudiante"],$key["id_horario"],$fecha));
				
				// o si se le ha justificado el bloque
				$bloques_justificado = Database::getRow('select * from bloques_justificados where id_estudiante =? and id_horario =? and date(fecha) = date(?)',array($alumno["id_estudiante"],$key["id_horario"],$fecha));
				
				// si el bloque se le ha justificado
				if(intval($bloques_justificado['cantidad'])>0){
					$estadoTemporal="IJ";
				}
				// si tiene entonces 
				if(count($inasistencia)>0){
					// si esta justificada, sino se asigna inasistencia("I")
					if($inasistencia["estado"]==="Justificada") $estadoTemporal="IJ";					
				}				

				$estado[] = $estadoTemporal;
				if($estadoTemporal==="I"){
					$aparecer = "SI";
				}
			}else{
				$estado[] ="NA";
			}
		}
		}
			// materias academicas		
				foreach ($materiasAcademicas as $key) {		
					$materias[]=$key["asignatura"];
					$tiempos[]=$key["hora1"]."-".$key["hora2"];			
					$id_tiempos[]= $key["id_tiempo"];		

					$asistencia = Database::getRow('select count(*) as cantidad from asistencias where date(fecha_hora) = ? and id_horario = ?',array($fecha,$key["id_horario"]));

					// si hay registros es porque si se ha pasado lista
					if(intval($asistencia['cantidad']) > 0){
						$estadoTemporal="I";

						$asistenciaAlumno = Database::getRow('select count(*) as cantidad from asistencias where date(fecha_hora) = ? and id_horario = ? and id_estudiante=?',array($fecha,$key["id_horario"],$alumno["id_estudiante"]));
						if(intval($asistenciaAlumno["cantidad"])>0){
							$estadoTemporal="P";
							
						}
						// obtiene si tiene inasistencia en la materia
						$inasistencia = Database::getRow('select * from inasistencias_clases where id_estudiante =? and id_horario =? and date(fecha_hora) = date(?)',array($alumno["id_estudiante"],$key["id_horario"],$fecha));

						// o si se le ha justificado el bloque
						$bloques_justificado = Database::getRow('select count(*) as cantidad from bloques_justificados where id_estudiante =? and id_horario =? and date(fecha) = date(?)',array($alumno["id_estudiante"],$key["id_horario"],$fecha));
						
						// si el bloque se le ha justificado
						if(intval($bloques_justificado['cantidad'])>0){
							$estadoTemporal="IJ";
						}
						// si tiene entonces 				
						if(count($inasistencia)>0){
							// si esta justificada, sino se asigna inasistencia("I")
							if($inasistencia["estado"]==="Justificada") $estadoTemporal="IJ";					
						}				

						$estado[] =$estadoTemporal;
						if($estadoTemporal==="I" || $estadoTemporal==="IJ"){
							$aparecer = "SI";
						}
					} else{
						$estado[] ="NA";	
					}

				}

				if($aparecer==="SI"){
					$n++;
					// print everything 
				 	$tmp = Database::getRows('select * from tiempos where estado= "Activo"',array());

				 	$i = 0;
				 	$tabla.='
						<tr>
	                		<td style="border-right:0px;">'.$n.'</td>
	                		<td style="border-right:0px;">'.$alumno["codigo"].'</td>
	                		<td style="border-right:0px;">'.$alumno["apellidos"].', '.$alumno["nombres"].'</td>
	                		<td style="border-right:0px;">'.$alumno["grado"].'</td>
	                		<td style="border-right:0px;">'.$alumno["especialidad"].'</td>
	                		<td style="border-right:0px;">'.$alumno["grupo"].'</td>
	                		<td style="border-right:0px;text-align:center;">'.$alumno["seccion"].'</td>';
				 	foreach ($tmp as $tiempo) {

					 	$id_tiempo_a_evaluar= isset($id_tiempos[$i]) ? $id_tiempos[$i] : $id_tiempos[$i-1];
					 	if(intval($id_tiempo_a_evaluar)==intval($tiempo["id_tiempo"])){
 				    		$tabla .= '
		    					<td style="border-top:0px;text-align:center;width:7.5mm;">'.$estado[$i].'</td>';
	    					$i++;
				 		}
					 	else {
					 		$tabla .= '
		    					<td style="border-top:0px;text-align:center;width:7.5mm;"> </td>';	
					 	}
				 	}
				 	$tabla .= "</tr>";
				}		
				$materias = array();
				$tiempos = array();
				$estado = array();
				$id_tiempos = array();
				$aparecer = "NO";
			}
			return $tabla;
		}

		function GetSuspendidos($fecha)
		{
			$tablasp = "";
			$n = 0;
			$sql = "SELECT
						e.nombres as nombres,
						e.apellidos as apellidos,
						e.codigo as codigo,
						g.nombre as grado,
						ep.nombre as especialidad,
						IF(ep.nombre != 'Ninguna',
						   CONCAT('Grupo: ', gt.nombre),
						   CONCAT('Grupo: 1')) AS grupo,
						IF(ep.nombre != 'Ninguna',
						   CONCAT(s.nombre,'-' ,ga.nombre),
						   (s.nombre)) AS seccion,
						e.id_estudiante as id_estudiante,
						e.id_grupo_academico as id_grupo_academico,
						e.id_grupo_tecnico as id_grupo_tecnico,
						e.id_seccion as id_seccion,
						e.id_grado as id_grado,
						e.id_especialidad as id_especialidad
					FROM estudiantes e, grupos_academicos ga, grupos_tecnicos gt, grados g, secciones s, especialidades ep, suspendidos sp
					WHERE e.id_grupo_academico = ga.id_grupo_academico
						AND e.id_grupo_tecnico = gt.id_grupo_tecnico
						AND e.id_seccion = s.id_seccion
						AND g.id_grado = e.id_grado
						AND ep.id_especialidad = e.id_especialidad
						AND sp.id_estudiante = e.id_estudiante
						AND e.estado='Activo'
						AND ? BETWEEN sp.inicio AND sp.fin
					ORDER BY g.id_grado ASC,
						ep.nombre ASC,
						gt.nombre ASC,
						s.nombre ASC,
						ga.nombre ASC,
						e.apellidos ASC";
			$params = array($fecha);
            $suspendidos = Database::getRows($sql, $params);
            foreach ($suspendidos as $alumno) {
            	$n++;
            	$tablasp.='
					<tr>
                		<td style="border-right:0px;">'.$n.'</td>
                		<td style="border-right:0px;">'.$alumno["codigo"].'</td>
                		<td style="border-right:0px;">'.$alumno["apellidos"].', '.$alumno["nombres"].'</td>
                		<td style="border-right:0px;">'.$alumno["grado"].'</td>
                		<td style="border-right:0px;">'.$alumno["especialidad"].'</td>
                		<td style="border-right:0px;">'.$alumno["grupo"].'</td>
                		<td style="border-right:0px;text-align:center;">'.$alumno["seccion"].'</td>
                		<td style="border-top:0px;text-align:center;width:7.5mm;">S</td>
                		<td style="border-top:0px;text-align:center;width:7.5mm;">S</td>
                		<td style="border-top:0px;text-align:center;width:7.5mm;">S</td>
                		<td style="border-top:0px;text-align:center;width:7.5mm;">S</td>
                		<td style="border-top:0px;text-align:center;width:7.5mm;">S</td>
                		<td style="border-top:0px;text-align:center;width:7.5mm;">S</td>
                		<td style="border-top:0px;text-align:center;width:7.5mm;">S</td>
                		<td style="border-top:0px;text-align:center;width:7.5mm;">S</td>
                		<td style="border-top:0px;text-align:center;width:7.5mm;">S</td>
                		<td style="border-top:0px;text-align:center;width:7.5mm;">S</td>
                		<td style="border-top:0px;text-align:center;width:7.5mm;">S</td>
				 	</tr>';
            }
            return $tablasp;
		}

		function GetTiempos()
		{
			$sql = "SELECT 
						id_tiempo,
						hora_inicial,
						hora_final
					FROM tiempos
					WHERE estado=?
					ORDER BY hora_inicial";
			$params = array('Activo');
            $data = Database::getRows($sql, $params);
            return $data;
		}

		function GetAlumnos()
		{
			$sql = "
			SELECT 
				e.codigo AS carnet,
				CONCAT(e.apellidos, ', ', e.nombres) AS alumno,
				ep.nombre AS especialidad,
				s.nombre AS seccion,
				ga.nombre AS grupoAcademico,
				gt.nombre AS grupoTecnico,
				g.nombre AS grado
			FROM estudiantes e, secciones s, grupos_academicos ga, grupos_tecnicos gt, especialidades ep, grados g
			WHERE  e.id_seccion = s.id_seccion
				AND e.id_grado = g.id_grado
				AND e.id_especialidad = ep.id_especialidad
				AND e.id_grupo_academico = ga.id_grupo_academico
				AND e.id_grupo_tecnico = gt.id_grupo_tecnico
			ORDER BY g.id_grado ASC,
				s.nombre ASC,
				gt.nombre ASC,
				e.apellidos ASC";
			$params = array("");
            $data = Database::getRows($sql, $params);
            return $data;
		}
		function AsietenciaAlumno($value='')
		{
			$alumnos = GetAlumnos();
			foreach ($alumnos as $alumno) {
				$sql = "
				SELECT COUNT(id_asistencia)
				FROM asistencias a, estudiantes e
				WHERE e.codigo = ?
					AND a.fecha_hora LIKE '%?%'
					AND a.id_estudiante = e.id_estudiante";
				$params = array($alumno["carnet"],date("Y-m-d"));
            	$data = Database::getRows($sql, $params);
            	return $data;	
			}
		}
	}
?>