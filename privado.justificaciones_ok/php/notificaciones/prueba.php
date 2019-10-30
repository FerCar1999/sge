<?php
	session_start();
	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/notificaciones/notificaciones.php");
	//$data = notificaciones::getNotificaciones(424);
	/*foreach ($data as $row) {}*/
	$data = notificaciones::getNotificacionesLeidas($_SESSION['id_personal']);
	/*foreach ($data as $row) {
		echo $row[0];
	}*/
	echo json_encode(count($data));
?>