<?php 	
	session_start();
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$id_personal = isset($_POST['id_personal']) ? intval($_POST['id_personal']) : 0;
	$fecha = isset($_POST["fecha"]) ? trim($_POST["fecha"]) : null;

	$_SESSION["id_horarioAdmin"] = $id;	
	$_SESSION["id_maestro"] = $id_personal;
	$_SESSION["ver_fecha_asistencia"] = $fecha;
	
	

 ?>