<?php
	// obtiene las variables del metodo post
	$id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
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

    if((strtotime($horaInicio) > strtotime($horaFin))){
        echo "La hora inicial no puede ser mayor a la hora final.";
        exit();
    }
    
    if((strtotime($horaInicio) == strtotime($horaFin))){
        echo "La hora termina en el mismo instante, intente con otra hora.";
        exit();
    }

    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarTiempo($horaInicio)) {
		//echo "Registro inválido";
		echo $horaInicio;
		exit();
	}
	if (!validarTiempo($horaFin)) {
		//echo "Registro inválido";
		echo $horaFin;
		exit();
	}

	// require la clase dabase
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	if ($id != null) {
		$sqlAprobada = "SELECT 
	                    IF(((AddTime(?,'00:00:01') BETWEEN hora_inicial AND hora_final)
                            OR(TIMEDIFF(?,'00:00:01') BETWEEN hora_inicial AND hora_final))
                            ,'repetida','aprobada') AS hora_aprobada
                    FROM tiempos t 
                    WHERE t.estado = 'Activo' AND id_tiempo != ? 
                    GROUP BY hora_aprobada";
        $params = array($horaInicio,$horaFin,$id);
	}else{
		$sqlAprobada = "SELECT 
	                    IF(((AddTime(?,'00:00:01') BETWEEN hora_inicial AND hora_final)
                            OR(TIMEDIFF(?,'00:00:01') BETWEEN hora_inicial AND hora_final))
                            ,'repetida','aprobada') AS hora_aprobada
                    FROM tiempos t 
                    WHERE t.estado = 'Activo' 
                    GROUP BY hora_aprobada";
        $params = array($horaInicio,$horaFin);
	}


	$data = Database::getRows($sqlAprobada, $params);
	
	if (count($data) > 1) {
	    echo "La hora ya está asignada.";
	    exit();
	}


	if($id === null && !empty($horaFin) && strlen($horaFin)>0 && strlen($horaFin) <= 5 && !empty($horaInicio) && strlen($horaInicio)>0 && strlen($horaInicio) <= 5){

		if ($horaInicio === "" && $horaFin === "") {
    		echo "camposFalta";
    		exit();
		}

		$sql = "INSERT INTO tiempos(hora_inicial, hora_final) VALUES(?,?)";
		// consulta sql
 		$params = array(strip_tags($horaInicio), strip_tags($horaFin));
		// ejecuta la consulta
 		$last_id=Database::executeRow($sql, $params,"INSERT");
 		if($last_id>0){
			echo 'agregado';
		}
		else{
			echo 'Tiempo existente.';
		}

	}
	else if ($id > 0 && !empty($horaFin) && strlen($horaFin)>0 && strlen($horaFin) <= 5 && !empty($horaInicio) && strlen($horaInicio)>0 && strlen($horaInicio) <= 5){
		
		
		$sql = "UPDATE tiempos SET hora_inicial=?, hora_final=? WHERE id_tiempo=?";

		$params = array(strip_tags($horaInicio),strip_tags($horaFin),strip_tags($id));
		// ejecuta la consulta
		if(Database::executeRow($sql, $params)){
			echo 'modificado';
		}
		else{
			echo 'existente';
		}
	}
	else{
		echo 'Ingrese un tiempo valido.';
	}
?>