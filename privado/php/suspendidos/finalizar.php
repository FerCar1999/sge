<?php 
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	// si es un id valido
	if($id > 0 ){
		$sql ="update suspendidos set fin = SUBDATE((select now()),INTERVAL 1 DAY) where id_suspendido = ?";
 		$params = array(strip_tags($id)); 
		Database::executeRow($sql, $params);		   
	}

 ?>