<?php 
	session_start();
	// evalua los valores del POST
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$observacion = isset($_POST["observacion"]) ? trim($_POST["observacion"]) : null;
		
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();
	if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	// si es un id valido
	if($id > 0 ){

		$estudiante = Database::getRow('select id_procesado from impuntualidad_procesados where id_estudiante=(select id_estudiante from estudiantes where codigo =?)',array($id));

		
		if($estudiante["id_procesado"]!= null){
			Database::executeRow("update impuntualidad_procesados set fecha_procesado = (now()) where id_estudiante=(select id_estudiante from estudiantes where codigo =?)", array($id));
		}else{
			Database::executeRow("insert into impuntualidad_procesados(id_estudiante,fecha_procesado) values((select id_estudiante from estudiantes where codigo =?),(now()))", array($id));
		}
		if($observacion!=null){
			Database::executeRow("insert into observaciones(id_personal,id_estudiante,fecha,observacion) values(?,(select id_estudiante from estudiantes where codigo =?),(select now()),?)", array($_SESSION["id_personal"],$id,$observacion));
		} 		  
	}

?>