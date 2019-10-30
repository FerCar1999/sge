<?php 
	$id_notificacion = isset($_POST['pk_notificacion']) ? intval($_POST['pk_notificacion']) : 0;
	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($id_notificacion)) {
		echo "Error al marcar notificación.";
		exit();
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/notificaciones/notificaciones.php"); 
	$resp = notificaciones::notificacionVista($id_notificacion);
	echo ($resp);
?>