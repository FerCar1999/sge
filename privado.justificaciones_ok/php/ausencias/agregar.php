<?php 
	$pk_alumno = isset($_POST['pk_alumno']) ? intval($_POST['pk_alumno']) : 0;
	$inicio = isset($_POST["inicio"]) ? $_POST["inicio"] : "";
	$fin = isset($_POST["fin"]) ? $_POST["fin"] : "";
	$token = isset($_POST["token"]) ? $_POST["token"] : "";
	$pk_motivo = isset($_POST["pk_motivo"]) ? $_POST["pk_motivo"] : "";
	$permiso = isset($_POST["permiso"]) ? $_POST["permiso"] : "";

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");	

	if($pk_alumno>0){
		Database::executeRow("insert into ausencias_justificadas(id_estudiante,inicio,fin,motivo,permiso) values((select id_estudiante from estudiantes where codigo=?),?,?,?,?)", array($pk_alumno,$inicio,$fin,$pk_motivo,$permiso));					
	}
?>