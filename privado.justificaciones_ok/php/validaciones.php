<?php

	function validarNombre($n) {
		if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]+$/", $n))
			return true;
		else
			return false;
	}

	function validarCodigo($n){
		if (preg_match("/^[A-Za-z]{2}[0-9]{2}$/",$n))
			return true;
		else
			return false;
	}

	function validarCodigoAlumno($n)
	{
		if (preg_match("/^[0-9]{8}$/", $n))
			return true;
		else
			return false;
	}

	function validarNumeroEntero($n) {
		if (preg_match("/^[\d]+$/", $n))
			return true;
		else
			return false;
	}

	function validarTelefono($n) {
		if (preg_match("/^[\d]{4}[-]{1}[\d]{4}$/", $n))
			return true;
		else
			return false;
	}

	function validarPrecio($n) {
		if (preg_match("/^[\d]+([.]{1}[\d]{2})?$/", $n))
			return true;
		else
			return false;
	}

	function validarTexto($n) {
		if (preg_match("/^([^<>])+$/", $n))
			return true;
		else
			return false;
	}

	function validarCorreo($n){
		//ANTIGUA V0.01 = ^[_a-z0-9-]+(.[_a-z0-9-]+)*@((^|)(gmail|hotmail|yahoo|outlook|))*(.((^|)(com|es|ar|mx|)))$
		//ANTIGUA VALIDACION V0.1= ^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$
	 	//ANTIGUA V0.2 [_a-z0-9-]+(.[_a-z0-9-]+)*@((^|)(gmail|hotmail|yahoo|outlook|ricaldone.edu|))*(.((^|)(com|es|ar|mx|sv|)))
		if (preg_match("/^(([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)(\s*;\s*|\s*$))$/", $n))
			return true;
		else
			return false;
	}

	function validarTiempo($n)
	{
		//VALIDACION ANTERIOR
		//*/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/
		if (preg_match("/^([0-9]|0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])$/", $n))
			return true;
		else
			return false;
	}

	function validarExistencia($n){
		if (preg_match("/^\d*[123456789]+\d*$/", $n))
			return true;
		else
			return false;
	}
	function validarClave($clave,&$error_clave){
		//SON EXPRESIONES REGULARES
		//PREG_MATCH PREPARA UNA SENTENCIA PARA COINCIDA EL FORMATO
        if(strlen($clave) < 6){
            $error_clave = "La clave debe tener al menos 6 caracteres.";
            return false;
        }
        if(strlen($clave) > 16){
            $error_clave = "La clave no puede tener más de 16 caracteres.";
            return false;
        }
        if (!preg_match('`[a-z]`',$clave)){
            $error_clave = "La clave debe tener al menos una letra minúscula.";
            return false;
        }
        if (!preg_match('`[A-Z]`',$clave)){
            $error_clave = "La clave debe tener al menos una letra mayúscula.";
            return false;
        }
        if (!preg_match('`[0-9]`',$clave)){
            $error_clave = "La clave debe tener al menos un digito.";
            return false;
        }
        $error_clave = "";
        return true;
    }
    /*function validarcaptcha($capchap){
		if (isset($capchap)){				
			$response = json_decode("https://www.google.com/recaptcha/api/siteverify?secret=6LeVzScTAAAAAMM7wQXkF1iRCL32wq_aw_QK2DJ-&response=".$capchap);
			var_dump($response);	
			if($response=="NULL")
		        {
		          // es un robot asi que no pasa xd
		          return false;
		        }else
		        {
		        	// es un HUMANO 
		          return true;
		        }	      
		}
	}*/
	function validarFecha($fecha){
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$fecha))
	        return true;
	    else
	        return false;
	}
	function validarURL($url){
		//
		//(?:http|https)?(?:\:\/\/)(([A-Za-z0-9-]+\.)*[A-Za-z0-9-]+\.[A-Za-z]+)(?:\/.*)?
		if (preg_match("/^(http|https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \?=.-]*)*\/?$/", $url))
			return true;
		else
			return false;
	}

	function longitudTextos($texto,$tipo){
		if ($tipo == 1){
			if (strlen($texto)<=20)
				return true;
			else
				return false;
		}
		if ($tipo == 2) {
			if (strlen($texto)<=100)
				return true;
			else
				return false;
		}
	}
?>
