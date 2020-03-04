<?php 
	session_start();

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
	BitacoraLogger::$currentUser = $_SESSION["id_personal"];
	BitacoraLogger::$function = 2; // id for Login
	BitacoraLogger::$description = "Sesión finalizada";
	BitacoraLogger::setLogPersonal();

	session_destroy();
	header("Location: /login");	
 ?>