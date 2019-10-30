<?php 
	// evalua los valores del POST
	
	$pk_impuntualidad = isset($_POST['pk_impuntualidad']) ? intval($_POST['pk_impuntualidad']) : 0;	
	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();
	if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($pk_impuntualidad)) {
		echo "Registro inválido.";
		exit();
	}
	
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	// para que lo borre el mero mero
	if($pk_impuntualidad > 0 ){
		$sql ="DELETE FROM impuntualidad where id_impuntualidad=?";
 		$params = array(strip_tags($pk_impuntualidad));
		if(Database::executeRow($sql, $params)){		    
		    echo 'Llegada tarde removida.';    
		}else{
			echo "Ha ocurrido un error.";
		}
	}else{
		echo "Registro inválido.";
	}

?>