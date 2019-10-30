<?php 
	// clase que genera y valida los tokens
	class Token{
		// en el login genera un nuevo token para la session
		public static function generate(){
			return $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
		}
		// funcion para verificar la existencia del token
		public static function check($token){
			//verifica que exista y sea igual al de la sesion
			if(isset($_SESSION['token']) && $token===$_SESSION['token']){
				return true;
			}else return false;
		}

	}

 ?>