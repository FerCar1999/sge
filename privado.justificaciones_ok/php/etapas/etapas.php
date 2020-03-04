<?php    
    // obtiene las variables del metodo post
    $id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
    $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]): "";
    $descripcion = isset($_POST["descripcion"]) ? trim($_POST["descripcion"]): "";
    $inicio = isset($_POST["inicio"]) ? trim($_POST["inicio"]): "";
    $fin = isset($_POST["fin"]) ? trim($_POST["fin"]): "";
    $nivel = isset($_POST["nivel"]) ? trim($_POST["nivel"]): "";
    //obtiene el token enviado por el navegador y lo compara con el de su session activa
    $token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
    session_start();
    /*if(!Token::check($token)){
        echo 'Acción sobre la información denegado';
        exit();
    }*/
    
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");
      
    if (!validarNombre($nombre)) {
        echo "Nombre inválido.";
        exit();
    }
    if (!validarTexto($descripcion)) {
        echo "Descripción inválida.";
        exit();
    }
    if (!validarFecha(date("Y-m-d",strtotime($inicio)))) {
        echo "Fecha inicial inválida.";
        exit();
    }
    if (!validarFecha(date("Y-m-d",strtotime($fin)))) {
        echo "Fecha final inválida.";
        exit();
    }
    if (strtotime($fin) < strtotime($inicio)) {
        echo "Fecha final menor a la fecha inicial.";
        exit();
    }
    
    // require la clase dabase
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
    
    if ($id != null) {
		$sqlAprobada = "SELECT 
    	                    IF(
                                (? BETWEEN fecha_inicial AND fecha_final)
                            OR
                                (? BETWEEN fecha_inicial AND fecha_final),
                            'Reprobada','Aprobada') AS etapa
                        FROM etapas e
                        WHERE e.estado = 'Activo' 
                        AND e.id_etapa != ?
                        AND e.id_nivel = ?
                        GROUP BY etapa";
        $params = array($inicio,$fin,$id,$nivel);
	}else{
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
	}
    
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
    
    
    if($id===null && !empty($nombre) && strlen($nombre)>2 && strlen($nombre) < 36){
    
        // consulta sql
        $sql ="insert into etapas(nombre,descripcion,fecha_inicial,fecha_final,id_nivel) values(?,?,?,?,?)";
        $params = array(strip_tags($nombre),strip_tags($descripcion),strip_tags($inicio),strip_tags($fin),strip_tags($nivel));
        // ejecuta la consulta
        $last_id=Database::executeRow($sql, $params,"INSERT");
        if($last_id>0){
            echo 'success';
        }
        else{
            echo 'Etapa existente.';
        }
    }
    else if ($id >0 && !empty($nombre) && strlen($nombre)>2 && strlen($nombre) < 36){
      //sql
      $sql ="update  etapas set nombre=?, descripcion=?, fecha_inicial=?, fecha_final =?, id_nivel =?  where id_etapa=?";
    
      $params = array(strip_tags($nombre),strip_tags($descripcion),strip_tags($inicio),strip_tags($fin),strip_tags($nivel),strip_tags($id));
        // ejecuta la consulta
        if(Database::executeRow($sql, $params)){
            echo 'success';
        }
        else{
            echo 'Etapa existente.';
        }
    }
    else{
      echo 'Ingrese una etapa válida.';
    }
?>