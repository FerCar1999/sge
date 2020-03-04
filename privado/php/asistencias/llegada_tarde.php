<?php 
	try{
	// evalua los valores del POST
	$id_horario = isset($_POST['id_horario']) ? intval($_POST['id_horario']) : 0;
	$asistencia = isset($_POST['asistencia']) ? json_decode($_POST['asistencia']) : null;
	
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	if($id_horario>0){
	
		$sql = "insert into impuntualidad(id_estudiante,fecha_hora,tipo,id_horario,estado) values(?,(select now()),'Salón',?,'Injustificada')";
		// parametros
		foreach($asistencia as $id_estudiante)
	    {
	    	if($id_estudiante!=null){
	    	$params = array($id_estudiante,$id_horario);
			// ejecuta la consulta
			Database::executeRow($sql, $params);
			}
	    }
	}
   }catch (Exception $error){
   		echo $error;
   } 
 ?>