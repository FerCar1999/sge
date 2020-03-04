<?php
date_default_timezone_set("America/El_Salvador");
// obtiene las variables del metodo post
$id = isset($_POST["id"]) && intval($_POST["id"]) > 0 ? intval($_POST["id"]) : null;
$nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : "";
$apellido = isset($_POST["apellido"]) ? trim($_POST["apellido"]) : "";
$codigo = isset($_POST["codigo"]) ? trim($_POST["codigo"]) : "";
$correo = isset($_POST["correo"]) ? trim($_POST["correo"]) : "";
$clave = isset($_POST["clave"]) ? trim($_POST["clave"]) : "";
$claveR = isset($_POST["claveR"]) ? trim($_POST["claveR"]) : "";
$permiso = isset($_POST["permiso"]) ? trim($_POST["permiso"]) : "";
$url_foto = isset($_POST["url_foto"]) ? $_POST["url_foto"] : "";
//Campos para modificar estudiante 
$grado = isset($_POST["grado"]) ? $_POST["grado"] : "";
$grupoAca = isset($_POST["grupoAca"]) ? $_POST["grupoAca"] : "";
$grupoTec = isset($_POST["grupoTec"]) ? $_POST["grupoTec"] : "";
$especialdad = isset($_POST["especialidad"]) ? $_POST["especialidad"] : "";
$seccion = isset($_POST["seccion"]) ? $_POST["seccion"] : "";
$error = "";
//echo phpinfo();

require_once($_SERVER['DOCUMENT_ROOT'] . "/privado/php/validaciones.php");
/*if (!validarCodigo($codigo)) {
		echo "Código inválido.";
		exit();
	}*
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
	}*/
if ($claveR != $clave) {
	echo "Contraseñas no coinciden.";
	exit();
}

// require la clase dabase
require_once($_SERVER['DOCUMENT_ROOT'] . "/libs/database.php");
if ($id === null && !empty($nombre) && strlen($nombre) > 2 && strlen($nombre) < 36) {
	if ($nombre === "" || !isset($_FILES['fotoPersonal'])) {
		echo "Campos obligatorios faltantes";
		exit();
	}
	$rutaDestino = "";

	if (isset($_FILES['fotoPersonal'])) {
		$rutaTemporal = $_FILES['fotoPersonal']['tmp_name'];
		if ($rutaTemporal != "") {
			// Moviendo imagen a ruta de imagenes
			/*if (exif_imagetype($rutaTemporal) == IMAGETYPE_PNG) {
        			$rutaDestino = "/media/img/personal/".uniqid().".png";
					exit();
	    		}
	    		else if(exif_imagetype($rutaTemporal) == IMAGETYPE_JPEG){
	        		$rutaDestino = "/media/img/personal/".uniqid().".jpg";
				}*/
			if ($_FILES["fotoPersonal"]["type"] == 'image/png') {
				$rutaDestino = "/media/img/personal/" . uniqid() . ".png";
			} else if ($_FILES["fotoPersonal"]["type"] == 'image/jpeg') {
				$rutaDestino = "/media/img/personal/" . uniqid() . ".jpg";
			} else {
				if ($rutaDestino === "") {
					$rutaDestino = "/media/img/personal/user_default.jpg";
				} else {
					echo 'Formato de imagen incorrecto.';
					exit();
				}
			}
		} else {
			if ($rutaDestino === "") {
				$rutaDestino = "/media/img/personal/user_default.jpg";
			}
		}
	}
	// consulta sql
	$sql = "insert into personal(nombre, apellido, correo, codigo, foto, clave, id_permiso, fecha_clave) values(?, ?, ?, ?, ?, ?, ?, ?)";
	$params = array(strip_tags($nombre), strip_tags($apellido), strip_tags($correo), strip_tags($codigo), $rutaDestino, password_hash($clave, PASSWORD_DEFAULT), $permiso, date('Y-m-d'));
	// ejecuta la consulta
	$last_id = Database::executeRow($sql, $params, "INSERT");
	if ($permiso == 4) {
		if ($grado > 3) {
			$sql2 = "update  estudiantes set id_personal=? where id_grado=? and id_grupo_tecnico=? and id_especialidad=?";
			$params2 = array($last_id, $grado, $grupoTec, $especialdad);
			$resp2 = Database::executeRow($sql2, $params2);
		} else {
			$sql1 = "update  estudiantes set id_personal=? where id_grado=? and id_grupo_academico=? and id_seccion=?";
			$params1 = array($last_id, $grado, $grupoAca, $seccion);
			$resp = Database::executeRow($sql1, $params1);
		}
	}
	if ($last_id > 0) {
		require_once($_SERVER['DOCUMENT_ROOT'] . "/utils/phpmailer/PHPMailerAutoload.php");
		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';

		$mail->SMTPDebug = 0;                               // Enable verbose debug output


		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;
		$mail->Username = 'sge@ricaldone.edu.sv';                 // SMTP username
		$mail->Password = 'Allofme_22$07';                          // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to
		$mail->setFrom('luxuryandstylesv@gmail.com', 'Diario Pedagógico');
		$mail->addAddress($correo, '');     // Add a recipient
		$mail->Subject = 'NUEVO USUARIO';
		$html = '
		<center><h1>Bienvenido</h1><center>
		</br>
		<center>
		<p>Saludos ' . $nombre . ' ' . $apellido . ', se le notifica por medio de este correo que se ha creado su cuenta con exito. Se le envia su contraseña : ' . $clave . '</p><center>
    ';
		$mail->Body   = $html;
		$mail->AltBody = 'Bienvenido';
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);                            // Enable SMTP authentication
		if (!$mail->send()) {
			echo "Hubo un error al enviar el correo, favor de entregar personalmente la contraseña";
		} else {
			echo 'agregado';
		}
		if ($rutaTemporal == "") {
			$rutaTemporal = "/media/img/user_default.jpg";
			if (!move_uploaded_file($_SERVER['DOCUMENT_ROOT'] . $rutaTemporal, $_SERVER['DOCUMENT_ROOT'] . $rutaDestino)) {
				echo "Error al subir la imagen al servidor";
				exit();
			}
		}
		if (isset($_FILES['fotoPersonal'])) {
			if (!move_uploaded_file($rutaTemporal, $_SERVER['DOCUMENT_ROOT'] . $rutaDestino)) {
				echo "Error al subir al subir la imagen al servidor";
				exit();
			}
		}
	} else {
		echo 'Personal existente.';
	}
} else if ($id > 0 && !empty($nombre) && strlen($nombre) > 2 && strlen($nombre) < 36) {

	if (!is_uploaded_file($_FILES['fotoPersonalMod']['tmp_name'])) {

		//sql
		$sql = "update  personal set nombre=?, apellido=?, codigo=?, correo=?, id_permiso=? where id_personal=?";

		$params = array(strip_tags($nombre), strip_tags($apellido), strip_tags($codigo), strip_tags($correo), $permiso, strip_tags($id));
		// ejecuta la consulta
		if (Database::executeRow($sql, $params)) {
			echo 'Personal modificado.';
		} else {
			echo 'Personal existente.';
		}
	} else if (isset($_FILES['fotoPersonalMod'])) {
		$rutaTemporal = $_FILES['fotoPersonalMod']['tmp_name'];
		$rutaDestino = substr($url_foto, 0, -4);
		if ($url_foto === "/media/img/user_default.jpg" && $rutaTemporal != "") {
			/*if (exif_imagetype($_FILES['fotoPersonalMod']['tmp_name']) == IMAGETYPE_PNG) {
    				$rutaDestino = "/media/img/personal/".uniqid().".png";
				}*/
			/*else if(exif_imagetype($rutaTemporal) == IMAGETYPE_JPEG){
	        		$rutaDestino = "/media/img/personal/".uniqid().".jpg";
				}*/
			if ($_FILES["fotoPersonalMod"]["type"] == 'image/png') {
				$rutaDestino = "/media/img/personal/" . uniqid() . ".png";
			} else if ($_FILES["fotoPersonalMod"]["type"] == 'image/jpeg') {
				$rutaDestino = "/media/img/personal/" . uniqid() . ".jpg";
			} else {
				if ($rutaDestino === "") {
					$rutaDestino = "/media/img/personal/" . uniqid() . ".png";
				} else {
					echo 'Formato de imagen incorrecto.';
					exit();
				}
			}
		} else {
			/*if (exif_imagetype($rutaTemporal) == IMAGETYPE_PNG) {
                	$rutaDestino = $rutaDestino.".png";
            	}if(exif_imagetype($rutaTemporal) == IMAGETYPE_JPEG){
	                $rutaDestino = $rutaDestino.".jpg";
				}*/
			if ($_FILES["fotoPersonalMod"]["type"] == 'image/png') {
				$rutaDestino = $rutaDestino . ".png";
			}
			if ($_FILES["fotoPersonalMod"]["type"] == 'image/jpeg') {
				$rutaDestino = $rutaDestino . ".jpg";
			} else {
				echo 'Formato de imagen incorrecto.';
				exit();
			}
			if ($rutaDestino === "") {
				echo "Something went wrong.";
				exit();
			}
		}

		if (!move_uploaded_file($rutaTemporal, $_SERVER['DOCUMENT_ROOT'] . $rutaDestino)) {
			echo "Error al subir el archivo";
			exit();
		}
		$sql = "update  personal set nombre=?, apellido=?, codigo=?, correo=?, foto=?, id_permiso=? where id_personal=?";
		$params = array(strip_tags($nombre), strip_tags($apellido), strip_tags($codigo), strip_tags($correo), $rutaDestino, strip_tags($permiso), strip_tags($id));
		// ejecuta la consulta
		if (Database::executeRow($sql, $params)) {
			echo 'Personal modificado.';
		} else {
			echo 'Personal existente.';
		}
	}
} else {
	echo 'Ingrese un personal válido.';
}