<?php 
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");	

	if($id>0){
		Database::executeRow("delete from ausencias_justificadas where id_ausencia=?", array($id));					
	}
 ?>