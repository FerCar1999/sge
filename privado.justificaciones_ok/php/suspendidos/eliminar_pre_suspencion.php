<?php 
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	// si es un id valido
	if($id > 0 ){
		$sql ="delete from suspendidos where id_suspendido = ? ";
 		$params = array(strip_tags($id)); 
		Database::executeRow($sql, $params);		   
	}

 ?>