<?php 
	// evalua los valores del POST
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";
	$horaInicio = isset($_POST["horaInicio"]) ? trim($_POST["horaInicio"]): "";
	$horaFin = isset($_POST["horaFin"]) ? trim($_POST["horaFin"]): "";
	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();
	if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($id)) {
		echo "Registro inválido.";
		exit();
	}
	if (!validarTiempo($horaInicio) && $horaInicio != "") {
		echo "Hora inicial inválida.";
		exit();
	}
	if (!validarTiempo($horaFin) && $horaFin != "") {
		echo "Hora final inválida.";
		exit();
	}
	if ((!$estado == "Activo" || !$estado == "Inactivo")){
		echo "Estado inválido.";
		exit();
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	
	$sqlAprobada = "SELECT 
	                    IF(((AddTime(?,'00:00:01') BETWEEN hora_inicial AND hora_final)
                            OR(TIMEDIFF(?,'00:00:01') BETWEEN hora_inicial AND hora_final))
                            ,'repetida','aprobada') AS hora_aprobada
                    FROM tiempos t
                    WHERE t.estado = 'Activo'
                    GROUP BY hora_aprobada";
    
    $params = array($horaInicio,$horaFin);

	$data = Database::getRows($sqlAprobada, $params);
	
	if (count($data) > 1 && $estado == "Activo") {
	    echo "La hora ya está asignada.";
	    exit();
	}
	
	// si es un id valido
	if($id > 0 ){
		$sql ="UPDATE tiempos set estado=? where id_tiempo=?";
 		$params = array(strip_tags($estado),strip_tags($id));
 
		if(Database::executeRow($sql, $params)){
		    
		    echo 'Cambios guardados con éxito.';    
		}	
	}

?>