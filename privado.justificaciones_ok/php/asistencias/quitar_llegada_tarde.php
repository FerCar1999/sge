<?php 
	try{
	// evalua los valores del POST
	$id_horario = isset($_POST['id_horario']) ? intval($_POST['id_horario']) : 0;
	$inicio = isset($_POST["inicio_del"]) ? $_POST["inicio_del"] : "";
	$final = isset($_POST["fin_del"]) ? $_POST["fin_del"] : "";
	$asistencia = isset($_POST['asistencia']) ? json_decode($_POST['asistencia']) : null;
	
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	if($id_horario>0){
		$sql = 'delete from impuntualidad where id_horario = ? and TIME_TO_SEC((fecha_hora)) between TIME_TO_SEC(?) and TIME_TO_SEC(?) and id_estudiante =?';
		
		// parametros
		foreach($asistencia as $id_estudiante)
	    {
	    	if($id_estudiante!=null){
	    	$params = array($id_horario,$inicio,$final,$id_estudiante);
			// ejecuta la consulta
			Database::executeRow($sql, $params);			
			}				
	    }
	}
   }catch (Exception $error){
   		echo $error;
   } 
 ?>