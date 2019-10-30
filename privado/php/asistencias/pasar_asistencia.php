<?php 
try{
	// evalua los valores del POST
	$id_horario = isset($_POST['id_horario']) ? intval($_POST['id_horario']) : 0;
	$contenido = isset($_POST["contenido"]) ? $_POST["contenido"] : "";
	$observacion = isset($_POST["observacion"]) ? $_POST["observacion"] : "";
	$asistencia = isset($_POST['asistencia']) ? json_decode($_POST['asistencia']) : null;
	
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	if($id_horario>0){
		/*$sql_limpiar = "delete from permiso_horario where id_permiso =?";
		$params_limpiar = array($id_permiso);
		Database::executeRow($sql_limpiar, $params_limpiar);*/

		$sql = " insert into asistencias(id_horario,fecha_hora,id_estudiante,contenido,observacion) values(?,(select now()),?,?,?)";
		// parametros
		foreach($asistencia as $id_estudiante)
		{
			if($id_estudiante!=null){
				$params = array($id_horario, $id_estudiante,$contenido,$observacion);
				// ejecuta la consulta
				Database::executeRow($sql, $params);
			
			}
		}
		// manda a llamar la funccion para revisar si tiene la misma clase despues
		materias_anidadas($id_horario,$asistencia,$contenido,$observacion);
		materia_anterior($id_horario,$asistencia,$contenido,$observacion);		
		session_start();
		if(isset($_SESSION["pk_horario"])){
			unset($_SESSION["pk_horario"]);
		}
		$aditionalDescription = " Action Details: id_horario = {$id_horario}, contenido: {$contenido}, observacion: {$observacion}";
		addToBitacora($aditionalDescription);
	}
}catch (Exception $error){
	echo $error;
} 

function materias_anidadas($pk_horario,$asistencia,$contenido,$observacion){
	
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
	$sql_pasar_lista = " insert into asistencias(id_horario,fecha_hora,id_estudiante,contenido,observacion) values(?,(ADDTIME(CONCAT(CURDATE(), ?), '00:10:00')),?,?,?)";
	// parametros
		foreach($asistencia as $id_estudiante)
		{
			if($id_estudiante!=null){
				$params = array($clase["id_horario"]," ".$clase["hora_inicial"],$id_estudiante,$contenido,$observacion);
			
			$validacion = Database::getRow("select id_asistencia from asistencias where id_horario = ? and TIME_TO_SEC((fecha_hora)) between TIME_TO_SEC(?) and TIME_TO_SEC(?) and id_estudiante =? and date(fecha_hora) = CURDATE() group by id_estudiante",array($clase["id_horario"],$clase["hora_inicial"],$clase["hora_final"],$id_estudiante));
			if($validacion["id_asistencia"]==null) Database::executeRow($sql_pasar_lista, $params);
			// vuelve a llamarse a si misma para revisar si hay otras
			//materias_anidadas($clase["id_horario"],$asistencia,$contenido,$observacion);		
			}
		}	
}   
function materia_anterior($pk_horario,$asistencia,$contenido,$observacion){
	// obtiene la informacion de la clase actual
	$data = Database::getRow("select h.id_asignatura,h.id_especialidad,h.dia,h.id_tiempo,h.id_grado,h.id_seccion,h.id_grupo_tecnico,h.id_grupo_academico, t.hora_inicial,t.hora_final from horarios h,tiempos t   where h.id_tiempo = t.id_tiempo  and  h.id_horario = ?", array($pk_horario));	

	// genera la consulta para la clase anterior
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

	

	$sql_pasar_lista = " insert into asistencias(id_horario,fecha_hora,id_estudiante,contenido,observacion) values(?,(ADDTIME(CONCAT(CURDATE(), ?), '00:10:00')),?,?,?)";
	// parametros
		foreach($asistencia as $id_estudiante)
		{

			// ingresa la asistencia, si la cantidad en asistencias es 0 y en inasistencias_clase tambien lo es
			if($id_estudiante!=null){

				$cantidadAsistencia = Database::getRow('select count(*) cantidad from asistencias where id_horario = ? and date(fecha_hora) = CURDATE() and id_estudiante = ?',array($clase["id_horario"],$id_estudiante));
				$cantidadAusencias = Database::getRow('select count(*) as cantidad from inasistencias_clases where date(fecha_hora) =  CURDATE() and id_horario = ? and id_estudiante = ?',array($clase["id_horario"],$id_estudiante));

				if(intval($cantidadAsistencia["cantidad"]) == 0 &&  intval($cantidadAusencias["cantidad"]) == 0) {
					$params = array($clase["id_horario"]," ".$clase["hora_inicial"],$id_estudiante,$contenido,$observacion);
			
					$validacion = Database::getRow("select id_asistencia from asistencias where id_horario = ? and TIME_TO_SEC((fecha_hora)) between TIME_TO_SEC(?) and TIME_TO_SEC(?) and id_estudiante =? and date(fecha_hora) = CURDATE() group by id_estudiante",array($clase["id_horario"],$clase["hora_inicial"],$clase["hora_final"],$id_estudiante));			
			
					if($validacion["id_asistencia"]==null) Database::executeRow($sql_pasar_lista, $params);
				}							
			}
		}	
}


function addToBitacora($aditionalDescription){
	try {

    require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
    
    session_start();
    BitacoraLogger::$currentUser = $_SESSION["id_personal"];
		BitacoraLogger::$function = 90;
    BitacoraLogger::$description = "Asistencia Guardada";
    BitacoraLogger::$aditionalDescription = $aditionalDescription;
    BitacoraLogger::setLogPersonal();    		
	} catch (Exception $e) {}	
}

?>