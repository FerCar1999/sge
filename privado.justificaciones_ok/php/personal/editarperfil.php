<?php
	// obtiene las variables del metodo post
	$id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
	$nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]): "";
	$apellido = isset($_POST["apellido"]) ? trim($_POST["apellido"]): "";
	$correo = isset($_POST["correo"]) ? trim($_POST["correo"]): "";
	$clave = isset($_POST["clave"]) ? trim($_POST["clave"]): "";
	$claveR = isset($_POST["claveR"]) ? trim($_POST["claveR"]): "";
	$url_foto = isset($_POST["url_foto"]) ? $_POST["url_foto"]: "";
	$error = "";

	session_start();

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNombre($nombre)){
		echo "Nombres inválidos.";
		exit();
	}
	if (!validarNombre($apellido)){
		echo "Apellidos inválidos.";
		exit();
	}
	if (!validarTexto($clave) && $clave != ""){
		echo "Primera contraseña inválida.";
		exit();
	}
	if (!validarTexto($claveR) && $claveR != ""){
		echo "Segunda contraseña inválida.";
		exit();
	}
	if ($claveR != $clave){
		echo "Contraseñas no coinciden.";
		exit();
	}
	if (!validarCorreo($correo)){
		echo "Correo inválido.";
		exit();
	}
	if (strcasecmp($clave, $_SESSION['codigo']) == 0) {
		echo "Digite una contraseña diferente a su código.";
		exit();
	}
	if (!validarClave($clave,$error) && $clave != "") {
		echo $error;
		exit();
	}
	// require la clase dabase
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	
	if ($id > 0 && !empty($nombre) && strlen($nombre)>2 && strlen($nombre) < 36){

		if (!is_uploaded_file($_FILES['fotoPersonalMod']['tmp_name'])) {

			//sql
			if ($clave != "") {
				$sql ="update  personal set nombre=?, apellido=?, correo=?, clave=? where id_personal=?";
				$params = array(strip_tags($nombre),strip_tags($apellido),strip_tags($correo),password_hash($clave,PASSWORD_DEFAULT),strip_tags($id));
			}else{
				$sql ="update  personal set nombre=?, apellido=?, correo=? where id_personal=?";
				$params = array(strip_tags($nombre),strip_tags($apellido),strip_tags($correo),strip_tags($id));
			}
			// ejecuta la consulta
			if(Database::executeRow($sql, $params)){
				echo 'Personal modificado.';
			}
			else{
				echo 'Personal existente.';
			}
		}else if (isset($_FILES['fotoPersonalMod'])) {

			$rutaTemporal = $_FILES['fotoPersonalMod']['tmp_name'];
			$rutaDestino = substr($url_foto, 0, -4);

			if ($url_foto === "/media/img/personal/personalDefault.png" && $rutaTemporal != "") {
				if (exif_imagetype($rutaTemporal) == IMAGETYPE_PNG) {
    				$rutaDestino = "/media/img/personal/".uniqid().".png";
	    		}
	    		else if(exif_imagetype($rutaTemporal) == IMAGETYPE_JPEG){
	        		$rutaDestino = "/media/img/personal/".uniqid().".jpg";
	    		}else {
	    			if($rutaDestino === ""){
		        		$rutaDestino = "/media/img/personal/".uniqid().".png"; 
	    			}else{
	    				echo 'Formato de imagen incorrecto.';
	            		exit();
	    			}
	        	}
			}else if (isset($_FILES['fotoPersonalMod'])) {

				$rutaTemporal = $_FILES['fotoPersonalMod']['tmp_name'];
				$rutaDestino = substr($url_foto, 0, -4);
	
				if ($url_foto === "/media/img/personal/personalDefault.png" && $rutaTemporal != "") {
					if (exif_imagetype($rutaTemporal) == IMAGETYPE_PNG) {
	    				$rutaDestino = "/media/img/personal/".uniqid().".png";
		    		}
		    		else if(exif_imagetype($rutaTemporal) == IMAGETYPE_JPEG){
		        		$rutaDestino = "/media/img/personal/".uniqid().".jpg";
		    		}else {
		    			if($rutaDestino === ""){
			        		$rutaDestino = "/media/img/personal/".uniqid().".png"; 
		    			}else{
		    				echo 'Formato de imagen incorrecto.';
		            		exit();
		    			}
		        	}
				}
			}

    		if (!move_uploaded_file($rutaTemporal, $_SERVER['DOCUMENT_ROOT'].$rutaDestino)){
            	echo "error";
            	exit();
        	}


        	if ($clave != "") {
        		$sql ="update  personal set nombre=?, apellido=?, clave=?, correo=?, foto=? where id_personal=?";	
        		$params = array(strip_tags($nombre),strip_tags($apellido),password_hash($clave,PASSWORD_DEFAULT),strip_tags($correo),$rutaDestino,strip_tags($id));
        	}else{
        		$sql ="update  personal set nombre=?, apellido=?, correo=?, foto=? where id_personal=?";
        		$params = array(strip_tags($nombre),strip_tags($apellido),strip_tags($correo),$rutaDestino,strip_tags($id));
        	}
				// ejecuta la consulta
			if(Database::executeRow($sql, $params)){
				echo 'Personal modificado.';
			}
			else{
				echo 'Personal existente.';
			}
		}
	}
	else{
		echo 'Ingrese un personal válido.';
	}
?>