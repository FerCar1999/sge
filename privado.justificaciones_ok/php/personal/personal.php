<?php
	// obtiene las variables del metodo post
	$id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
	$nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]): "";
	$apellido = isset($_POST["apellido"]) ? trim($_POST["apellido"]): "";
	$codigo = isset($_POST["codigo"]) ? trim($_POST["codigo"]): "";
	$correo = isset($_POST["correo"]) ? trim($_POST["correo"]): "";
	$clave = isset($_POST["clave"]) ? trim($_POST["clave"]): "";
	$claveR = isset($_POST["claveR"]) ? trim($_POST["claveR"]): "";
	$permiso = isset($_POST["permiso"]) ? trim($_POST["permiso"]): "";
	$url_foto = isset($_POST["url_foto"]) ? $_POST["url_foto"]: "";
	$error = "";

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarCodigo($codigo)) {
		echo "Código inválido.";
		exit();
	}
	if (!validarNombre($nombre)){
		echo "Nombres inválidos.";
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
	if (!validarNombre($apellido)){
		echo "Apellidos inválidos.";
		exit();
	}
	if (!validarCorreo($correo)){
		echo "Correo inválido.";
		exit();
	}
	if (!validarClave($clave,$error) && $clave != "") {
		echo $error;
		exit();
	}

	// require la clase dabase
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	if($id === null && !empty($nombre) && strlen($nombre)>2 && strlen($nombre) < 36){

		if ($nombre === "" || !isset($_FILES['fotoPersonal'])) {
    		echo "camposFalta";
    		exit();
		}


		$rutaDestino = "";

		if (isset($_FILES['fotoPersonal'])) {
			$rutaTemporal = $_FILES['fotoPersonal']['tmp_name'];

			if ($rutaTemporal != "") {
				// Moviendo imagen a ruta de imagenes
    			if (exif_imagetype($rutaTemporal) == IMAGETYPE_PNG) {
        			$rutaDestino = "/media/img/personal/".uniqid().".png";
					exit();
	    		}
	    		else if(exif_imagetype($rutaTemporal) == IMAGETYPE_JPEG){
	        		$rutaDestino = "/media/img/personal/".uniqid().".jpg";
	    		}else {
	    			if($rutaDestino === ""){
		        		$rutaDestino = "/media/img/personal/personalDefault.png";
	    			}else{
	    				echo 'Formato de imagen incorrecto.';
	            		exit();
	    			}
	        	}
			}else{
				if($rutaDestino === ""){
					$rutaDestino = "/media/img/personal/personalDefault.png";
    			}
			}
		}


		// consulta sql
 		$sql ="insert into personal(nombre, apellido, correo, codigo, foto, clave, id_permiso) values(?, ?, ?, ?, ?, ?, ?)";
 		$params = array(strip_tags($nombre),strip_tags($apellido),strip_tags($correo),strip_tags($codigo),$rutaDestino,password_hash($clave,PASSWORD_DEFAULT),$permiso);
		// ejecuta la consulta
 		$last_id=Database::executeRow($sql, $params,"INSERT");
 		if($last_id>0){
			echo 'agregado';
			if ($rutaTemporal == "") {
				echo($rutaTemporal);
				exit();
				$rutaTemporal = "/media/img/personal/personalDefault.png";
				if (!move_uploaded_file($_SERVER['DOCUMENT_ROOT'].$rutaTemporal, $_SERVER['DOCUMENT_ROOT'].$rutaDestino)){
            		//echo "error";
            		exit();
        		}
			}
			if (isset($_FILES['fotoPersonal'])) {
				if (!move_uploaded_file($rutaTemporal, $_SERVER['DOCUMENT_ROOT'].$rutaDestino)){
            		//echo "error";
            		exit();
        		}
			}
		}
		else{
			echo 'Personal existente.';
		}

	}
	else if ($id > 0 && !empty($nombre) && strlen($nombre)>2 && strlen($nombre) < 36){

		if (!is_uploaded_file($_FILES['fotoPersonalMod']['tmp_name'])) {

			//sql
			$sql ="update  personal set nombre=?, apellido=?, codigo=?, correo=?, id_permiso=? where id_personal=?";

			$params = array(strip_tags($nombre),strip_tags($apellido),strip_tags($codigo),strip_tags($correo),$permiso,strip_tags($id));
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
			}else{
				if (exif_imagetype($rutaTemporal) == IMAGETYPE_PNG) {
                	$rutaDestino = $rutaDestino.".png";
            	}if(exif_imagetype($rutaTemporal) == IMAGETYPE_JPEG){
	                $rutaDestino = $rutaDestino.".jpg";
            	}else {
                	echo 'Formato de imagen incorrecto.';
                	exit();
            	}if($rutaDestino === ""){
                	echo "Something went wrong.";
                	exit();
            	}
			}

    		if (!move_uploaded_file($rutaTemporal, $_SERVER['DOCUMENT_ROOT'].$rutaDestino)){
            	echo "error";
            	exit();
        	}

        	$sql ="update  personal set nombre=?, apellido=?, codigo=?, correo=?, foto=?, id_permiso=? where id_personal=?";

			$params = array(strip_tags($nombre),strip_tags($apellido),strip_tags($codigo),strip_tags($correo),$rutaDestino,strip_tags($permiso),strip_tags($id));
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