<?php
	// obtiene las variables del metodo post
	
	$clave = isset($_POST["clave"]) ? trim($_POST["clave"]): "";
	$claveR = isset($_POST["claveR"]) ? trim($_POST["claveR"]): "";
	$error = "";

	session_start();

	if ($_SESSION["pass"] == 0) {
		header("Location: ".$_SERVER['DOCUMENT_ROOT']."/privado/views/dashboard.php");
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (strcasecmp($clave, $_SESSION['codigo']) == 0 || strcasecmp($claveR, $_SESSION['codigo']) == 0) {
		echo "Digite una contraseña diferente a su código.";
		exit();
	}
	if (!($clave == $claveR)) {
		echo "Las claves no son iguales.";
		exit();
	}

	if (!validarClave($clave,$error)) {
		echo $error;
		exit();
	}

	// require la clase dabase
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

		
	$sql = "UPDATE personal SET clave=?,cambio_forzar=? WHERE id_personal=?";
	// consulta sql
		$params = array(password_hash($clave,PASSWORD_DEFAULT),0,$_SESSION['id_personal']);
	// ejecuta la consulta
	if(Database::executeRow($sql, $params)){
		echo "success";
	}	
?>