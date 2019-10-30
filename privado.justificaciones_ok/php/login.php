<?php 
session_start();

// Get user data
$user = isset($_POST["user"]) ? trim($_POST["user"]) : ("");
$pass = isset($_POST["pass"]) ? trim($_POST["pass"]) : ("");

if ($user === "" || $pass === "") {
	echo 'Ingrese usuario y contraseña.'; 
}
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");

$data = Database::getRow("select id_personal,nombre,apellido,clave,correo,foto,id_permiso,codigo,cambio_forzar from personal where codigo=?", array($user));

if(isset($data['id_personal'])){
			
		if (password_verify($pass, $data['clave'])) {
		$cargo = Database::getRow("select nombre from permisos where id_permiso=?", array($data['id_permiso']));

		$_SESSION["id_personal"] = $data['id_personal'];
		$_SESSION["nombre"] = $data['nombre'];
		$_SESSION["apellido"] = $data['apellido'];
		$_SESSION["correo"] = $data['correo'];
		$_SESSION["foto"] = $data['foto'];
		$_SESSION["codigo"] = $data['codigo'];
		$_SESSION["permiso"] = $cargo['nombre'];
		$_SESSION["username"] = $user;

		if($cargo['nombre']==="Administrador"){
			$_SESSION["isAdmin"] = true;			
		}

		$_SESSION["pass"] = 0;
		if ($user == $pass) {
			echo 'pass';
			$_SESSION["pass"] = 1;
		}else{
			if ($data["cambio_forzar"] == 1) {
				echo("cambio");
				$_SESSION["pass"] = 1;
			}else{
				$_SESSION['start'] = time();
				// expira en 10 minutos = 10min*60seg
				$_SESSION['expire'] = $_SESSION['start'] + (50 * 60); 
				Token::generate();
				echo 'true';
			}
		}
	}else {
		echo 'Clave incorrecta.';
	}
}else echo 'El usuario no existe.';
?>