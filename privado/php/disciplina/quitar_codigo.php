<?php 
	// evalua los valores del POST
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;	
	$pk_disciplina = isset($_POST['pk_disciplina']) ? intval($_POST['pk_disciplina']) : 0;	
	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	//session_start();
	/*if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}*/

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($id)) {
		echo "Registro inválido.";
		exit();
	}
	if (!validarNumeroEntero($pk_disciplina)) {
		echo "Registro inválido.";
		exit();
	}
	
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	
	//el profe guia
	if($pk_disciplina>0){
		session_start();
		$sql ="delete from disciplina where id_disciplina=?";
 		$params = array($pk_disciplina);
 
		if(Database::executeRow($sql, $params)){		    
			echo 'Código eliminado con éxito.';  
			try{
				addToBitacora("Se ha eliminado un código disciplinario con el ID: {$pk_disciplina} ","");
			}catch(Exception $e){}  
		    return;
		}else {
			echo 'Este código solo puede ser eliminado por el docente que lo aplico.';    
		}
	}
	// LO BORRA EL ADMINISTRADOR
	if($pk_disciplina > 0 ){
		$sql ="delete from disciplina where id_disciplina=?";
 		$params = array(strip_tags($pk_disciplina));
 
		if(Database::executeRow($sql, $params)){		    
			echo 'Código eliminado con éxito.';
			try{
				addToBitacora("Se ha eliminado un código disciplinario con el ID: {$pk_disciplina} ","");
			}catch(Exception $e){}  
		}	
	}

	function addToBitacora($description,$aditionalDescription){
		try {
	
			require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
			
			session_start();
			BitacoraLogger::$currentUser = $_SESSION["id_personal"];
			BitacoraLogger::$function = 70;
			BitacoraLogger::$description = $description;
			BitacoraLogger::$aditionalDescription = $aditionalDescription;
			BitacoraLogger::setLogPersonal();    		
		} catch (Exception $e) {}	
	}

?>