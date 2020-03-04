<?php 
	// evalua los valores del POST
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$inicio = isset($_POST["inicio"]) ? $_POST["inicio"] : "Activo";
	$fin = isset($_POST["fin"]) ? $_POST["fin"] : "Activo";
	$observacion = isset($_POST["observacion"]) ? $_POST["observacion"] : "Activo";

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	// si es un id valido
	if($id > 0 ){
		session_start();
		$sql ="update suspendidos set id_personal=?,inicio=?,fin=?,observacion=? where id_suspendido=?";
 		$params = array($_SESSION["id_personal"],$inicio,$fin,$observacion,$id);
		Database::executeRow($sql, $params);
		require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/mails/mailSuspendido.php");
	}

?>