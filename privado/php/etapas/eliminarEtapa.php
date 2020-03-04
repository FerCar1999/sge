<?php 
	// evalua los valores del POST
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";
	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();
	/*if(!Token::check($token)){
	    echo 'Acción sobre la información denegado';
	    exit();
	}*/

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($id)) {
		echo "Registro inválido.";
		exit();
	}	
	if ((!$estado == "Activo" || !$estado == "Inactivo")){
		echo "Estado inválido.";
		exit();
	}

	$sqlAprobada = "SELECT 
	                    IF(
                            (? BETWEEN fecha_inicial AND fecha_final)
                        OR
                            (? BETWEEN fecha_inicial AND fecha_final),
                        'Reprobada','Aprobada') AS etapa
                    FROM etapas e
                    WHERE e.estado = 'Activo'
                    AND e.id_nivel = ?
                    GROUP BY etapa";
    $params = array($inicio,$fin,$nivel);

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	
	$data = Database::getRows($sqlAprobada, $params);
	
	if (count($data) > 1) {
	    foreach ($data as $valor) {
	        if($valor["etapa"] == "Reprobada"){
	            echo "La etapa ya está asignada.";
	            exit();       
	        }
	    }
	}else{
	    foreach ($data as $valor) {
	        if($valor["etapa"] == "Reprobada"){
	            echo "La etapa ya está asignada.";
	            exit();       
	        }
	    }
	}
	
	// si es un id valido
	if($id > 0 ){
		$sql ="UPDATE etapas set estado=? where id_etapa=?";
 		$params = array(strip_tags($estado),strip_tags($id));
 
		if(Database::executeRow($sql, $params)){
			echo 'success';    
			// Bitacora
			try{
				$aditionalDescription = " Action Details: id = {$id}";
				addToBitacora("Se ha eliminado una Etapa",$aditionalDescription);
			}catch(Exception $e){}
		}	
	}

	function addToBitacora($description,$aditionalDescription){
		try {
	
			require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
			
			session_start();
			BitacoraLogger::$currentUser = $_SESSION["id_personal"];
			BitacoraLogger::$function = 68;
			BitacoraLogger::$description = $description;
			BitacoraLogger::$aditionalDescription = $aditionalDescription;
			BitacoraLogger::setLogPersonal();    		
		} catch (Exception $e) {}	
	}

?>