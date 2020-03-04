<?php 
	$codigo = isset($_POST["codigo"]) ? trim($_POST["codigo"]): "";

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarCodigo($codigo)) {
		echo "Código no válido.";
		exit();
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	date_default_timezone_set("America/El_Salvador");

	$data = Database::getRow("SELECT fecha_clave FROM personal WHERE codigo = ?", array($codigo));

	if (date($data["fecha_clave"]) == date("Y-m-d")) {
		echo("Ya ha solicitado un cambio de contraseña este día");
		exit();
	}

	$data = Database::getRow("SELECT correo,nombre,apellido,id_personal FROM personal WHERE codigo = ?", array($codigo));
	$pk_personal = $data[3];
	$correo = $data[0];
	$nombre = $data[1].' '.$data[2];

	function randomPassword() {
	   $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
    	   $n = rand(0, $alphaLength);
    	   $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    
    $pass = randomPassword();
    $passHash = password_hash($pass, PASSWORD_BCRYPT);
    
    $sql = "UPDATE personal SET clave=?, fecha_clave=?, cambio_forzar=? WHERE codigo=?";

	$params = array(strip_tags($passHash),date("Y-m-d"),1,strip_tags($codigo));
	// ejecuta la consulta
	if(Database::executeRow($sql, $params)){
		require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/recuperar_clave/sendmail.php");
	}
	else{
		echo 'error';
	}

?>