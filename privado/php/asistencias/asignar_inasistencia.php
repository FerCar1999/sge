<?php 
	try{
	// evalua los valores del POST
	$id_horario = isset($_POST['id_horario']) ? intval($_POST['id_horario']) : 0;
	$asistencia = isset($_POST['asistencia']) ? json_decode($_POST['asistencia']) : null;
	
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	if($id_horario>0){
	

		$sql = "insert into inasistencias_clases(id_estudiante,fecha_hora,id_horario,estado) values(?,(select now()),?,'Injustificada')";
		// parametros
		foreach($asistencia as $id_estudiante)
	  {
	    	Database::executeRow("delete from inasistencias_clases where id_estudiante =? and id_horario=? and DATE(fecha_hora) = CURDATE()",array($id_estudiante,$id_horario) );
	    	if($id_estudiante!=null){
	    	$params = array($id_estudiante,$id_horario);
				// ejecuta la consulta
				Database::executeRow($sql, $params);			
			}
		}
		materias_anidadas($id_horario, $asistencia);
		materia_anterior($id_horario, $asistencia);
	}
   }catch (Exception $error){
   		echo $error;
	 } 
	 
	 function materias_anidadas($pk_horario,$asistencia){
	
		// obtiene la informacion de la clase actual
		$data = Database::getRow("select h.id_asignatura,h.id_especialidad,h.dia,h.id_tiempo,h.id_grado,h.id_seccion,h.id_grupo_tecnico,h.id_grupo_academico, t.hora_inicial,t.hora_final from horarios h,tiempos t   where h.id_tiempo = t.id_tiempo  and  h.id_horario = ?", array($pk_horario));	
	
		// genera la consulta para la proxima clase
		$sql = "select h.id_horario,t.hora_inicial,t.hora_final from horarios h, tiempos t where h.id_tiempo = t.id_tiempo and h.id_grado = ? and id_seccion = ? and ";
	
		$id_grupo = 0;
		if($data["id_grupo_academico"] != null){
			$sql.= "h.id_grupo_academico = ? "; 
			$id_grupo = $data["id_grupo_academico"];
		}else {
			$sql.= "h.id_grupo_tecnico = ? and h.id_especialidad=".$data["id_especialidad"]." "; 
			$id_grupo = $data["id_grupo_tecnico"];
		}
		// termina de generar la consulta
		$sql.="and ADDTIME(?, '00:10:00') between t.hora_inicial and t.hora_final and h.dia=? and h.id_asignatura=?";
		$clase = Database::getRow($sql,array($data["id_grado"],$data["id_seccion"],$id_grupo,$data["hora_final"],$data["dia"],$data["id_asignatura"]));
	
		if($clase["id_horario"]==null){
			return;
		}
		//pendiente si es es necesario que la materia sea igual o solo con tener el mismo grupo
	
		// ingresa la asistencia tambien 
		$sql = "insert into inasistencias_clases(id_estudiante,fecha_hora,id_horario,estado) values(?,(select now()),?,'Injustificada')";
		// parametros
			foreach($asistencia as $id_estudiante)
			{
				Database::executeRow("delete from inasistencias_clases where id_estudiante =? and id_horario=? and DATE(fecha_hora) = CURDATE()",array($id_estudiante,$clase["id_horario"]));
	    	if($id_estudiante!=null){
	    	$params = array($id_estudiante,$clase["id_horario"]);
				// ejecuta la consulta
				Database::executeRow($sql, $params);
			}	
	}
}
function materia_anterior($pk_horario,$asistencia){
	// obtiene la informacion de la clase actual
	$data = Database::getRow("select h.id_asignatura,h.id_especialidad,h.dia,h.id_tiempo,h.id_grado,h.id_seccion,h.id_grupo_tecnico,h.id_grupo_academico, t.hora_inicial,t.hora_final from horarios h,tiempos t   where h.id_tiempo = t.id_tiempo  and  h.id_horario = ?", array($pk_horario));	
	
	// genera la consulta para la proxima clase
	$sql = "select h.id_horario,t.hora_inicial,t.hora_final from horarios h, tiempos t where h.id_tiempo = t.id_tiempo and h.id_grado = ? and id_seccion = ? and ";

	$id_grupo = 0;
	if($data["id_grupo_academico"] != null){
		$sql.= "h.id_grupo_academico = ? "; 
		$id_grupo = $data["id_grupo_academico"];
	}else {
		$sql.= "h.id_grupo_tecnico = ? and h.id_especialidad=".$data["id_especialidad"]." "; 
		$id_grupo = $data["id_grupo_tecnico"];
	}
	// termina de generar la consulta
	$sql.="and ADDTIME(?, '-00:10:00') between t.hora_inicial and t.hora_final and h.dia=? and h.id_asignatura=?";
	$clase = Database::getRow($sql,array($data["id_grado"],$data["id_seccion"],$id_grupo,$data["hora_inicial"],$data["dia"],$data["id_asignatura"]));

	if($clase["id_horario"]==null){
		return;
	}
	//pendiente si es es necesario que la materia sea igual o solo con tener el mismo grupo

	// ingresa la asistencia tambien 
	$sql = "insert into inasistencias_clases(id_estudiante,fecha_hora,id_horario,estado) values(?,(select now()),?,'Injustificada')";
	// parametros
		foreach($asistencia as $id_estudiante)
		{

			// Agrega la inasistencia si la cantidad en asistencias es 0 y en inasistencias_clase tambien lo es 						
			if($id_estudiante!=null){
				$cantidadAsistencia = Database::getRow('select count(*) as cantidad from asistencias where id_horario = ? and date(fecha_hora) = CURDATE() and id_estudiante = ?',array($clase["id_horario"],$id_estudiante));
				$cantidadAusencias = Database::getRow('select count(*)  as cantidad from inasistencias_clases where date(fecha_hora) =  CURDATE() and id_horario = ? and id_estudiante = ?',array($clase["id_horario"],$id_estudiante));
				if(intval($cantidadAsistencia["cantidad"]) == 0 &&  intval($cantidadAusencias["cantidad"]) == 0) {
					Database::executeRow("delete from inasistencias_clases where id_estudiante =? and id_horario=? and DATE(fecha_hora) = CURDATE()",array($id_estudiante,$clase["id_horario"]));
					$params = array($id_estudiante,$clase["id_horario"]);
					Database::executeRow($sql, $params);
				}			
			}	
		}
}
?>