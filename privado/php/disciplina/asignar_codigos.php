<?php 	
	session_start();
	//evalua los valores del POST	
	$pk_alumno = isset($_POST['pk_alumno']) ? intval($_POST['pk_alumno']) : 0;
	$pk_horario = isset($_POST['pk_horario']) ? intval($_POST['pk_horario']) : null;
	$pk_codigo = isset($_POST['pk_codigo']) ? intval($_POST['pk_codigo']) : 0;
	$pk_horario = isset($_POST['pk_horario']) ? intval($_POST['pk_horario']) : null;	
	$pk_observacion = isset($_POST["pk_observacion"]) ? $_POST["pk_observacion"] : "";
	
	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/notificaciones/notificaciones.php");

	function GetEtapa($fecha, $nivel){
        $sql = "SELECT 
                    nombre AS nombre,
                    fecha_inicial AS inicio, 
                    fecha_final AS fin
                FROM etapas
                WHERE ? BETWEEN fecha_inicial AND fecha_final
                	AND id_nivel = ?";
        $params = array($fecha, $nivel);
        $data = Database::getRow($sql, $params);
        return $data;
    }

    function GetNivel($alumno){
    	$sql = "SELECT 
					n.id_nivel AS nivel
				FROM estudiantes e, niveles n, grados g
				WHERE codigo = ?
					AND g.id_grado = e.id_grado
					AND g.id_nivel = n.id_nivel";
    	$params = array($alumno);
        $data = Database::getRow($sql, $params);
        return $data;
    }

    $nivel = GetNivel($pk_alumno);

    $etapa = GetEtapa(date('Y-m-d'), $nivel[0]);

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($pk_alumno)) {
		echo "Estudiante no registrado.";
		exit();
	}
	if (!validarNumeroEntero($pk_horario) && $pk_horario != null){
		echo "Horario no registrado.";
		exit();
	}
	if (!validarNumeroEntero($pk_codigo)){
		echo "Código no registrado.";
		exit();
	}	

	//CONSULTA BUENA??
	/*INSERT INTO disciplina(id_estudiante, id_personal, fecha_hora, id_codigo,observacion) SELECT id_estudiante,2,(SELECT NOW()),1,'Ninguna' FROM estudiantes WHERE codigo='20140159'*/

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	
	// si es un id valido 
	if($pk_alumno > 0 && $pk_horario != null){
		$sql ="INSERT INTO disciplina(id_personal,id_estudiante,fecha_hora,id_horario,id_codigo,observacion) VALUES(?,(SELECT id_estudiante FROM estudiantes  WHERE codigo =?),(SELECT now()),?,?,?)";
 		$params = array($_SESSION["id_personal"],$pk_alumno,$pk_horario,$pk_codigo,$pk_observacion);
		if(Database::executeRow($sql, $params)){
			echo 'Código asignado correctamente.';
			try{
				addToBitacora("Se ha asignado un código disciplinario al alumno: {$pk_codigo} ","");
			}catch(Exception $e){}
			$sql = "SELECT COUNT(dc.id_disciplina) AS faltas, tp.nombre, tp.cantidad AS cantidad FROM disciplina dc, codigos c, tipos_codigos tp WHERE dc.id_estudiante=(SELECT id_estudiante FROM estudiantes WHERE codigo=?) AND c.id_tipo_codigo = tp.id_tipo_codigo AND dc.id_codigo = c.id_codigo AND dc.fecha_hora BETWEEN ? AND ? GROUP BY tp.id_tipo_codigo";
		    $params = array($pk_alumno, $etapa[1], $etapa[2]);
			$data = Database::getRows($sql, $params);
			foreach($data as $row)
			{
				if (($row["faltas"] % $row["cantidad"]) == 0 && $row['nombre'] != 'Positivo') {
					$sql ="UPDATE estudiantes SET procesado=? WHERE codigo=?";
	 				$params = array(1,$pk_alumno);
	 				Database::executeRow($sql, $params);
	 				/*notificaciones::insertNotificacion($_SESSION["id_personal"], "Estudiante por procesar", "Estimado docente guía, se le informa que cuenta con alumnos pendietes por procesar.");*/
	 				require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/mails/mail_procesados.php");
	 				require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/mails/mailProcesados.php");
				}
				if ($row["faltas"] == 6 && $row["nombre"] == "Leve") {
					//CODIGO NEGATIVO
					/*$sql = "INSERT INTO disciplina(id_estudiante, id_personal, fecha_hora, id_codigo,observacion) SELECT id_estudiante,?,(SELECT NOW()),?,? FROM estudiantes WHERE codigo=?";
					$params = array($_SESSION["id_personal"],60,$pk_observacion,$pk_alumno);
					Database::executeRow($sql, $params);*/
					echo("entraaa");
					require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/mails/mailCodigosGraves.php");
				}
			}
		}
	}	

	if ($pk_horario == null) {
		$sql = "INSERT INTO disciplina(id_estudiante, id_personal, fecha_hora, id_codigo,observacion) SELECT id_estudiante,?,(SELECT NOW()),?,? FROM estudiantes WHERE codigo=?";
		$params = array($_SESSION["id_personal"],$pk_codigo,$pk_observacion,$pk_alumno);
		if(Database::executeRow($sql, $params)){
			echo 'Código asignado correctamente.';
			try{
				addToBitacora("Se ha asignado un código disciplinario al alumno: {$pk_codigo} ","");
			}catch(Exception $e){}
			$sql = "SELECT COUNT(dc.id_disciplina) AS faltas, tp.nombre, tp.cantidad AS cantidad FROM disciplina dc, codigos c, tipos_codigos tp WHERE dc.id_estudiante=(SELECT id_estudiante FROM estudiantes WHERE codigo=?) AND c.id_tipo_codigo = tp.id_tipo_codigo AND dc.id_codigo = c.id_codigo AND dc.fecha_hora BETWEEN ? AND ? GROUP BY tp.id_tipo_codigo";
		    $params = array($pk_alumno, $etapa[1], $etapa[2]);
			$data = Database::getRows($sql, $params);
			foreach($data as $row)
			{
				if (($row["faltas"] % $row["cantidad"]) == 0 && $row['nombre'] != 'Positivo') {
					$sql ="UPDATE estudiantes SET procesado=? WHERE codigo=?";
	 				$params = array(1,$pk_alumno);
	 				Database::executeRow($sql, $params);
	 				/*notificaciones::insertNotificacion($_SESSION["id_personal"], "Estudiante por procesar", "Estimado docente guía, se le informa que cuenta con alumnos pendietes por procesar.");*/
	 				require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/mails/mail_procesados.php");
	 				require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/mails/mailProcesados.php");
				}
				if (($row["faltas"] % 6) == 0 && $row["nombre"] == "Leve") {
					
					//CODIGO NEGATIVO
					/*$sql = "INSERT INTO disciplina(id_estudiante, id_personal, fecha_hora, id_codigo,observacion) SELECT id_estudiante,?,(SELECT NOW()),?,? FROM estudiantes WHERE codigo=?";
					$params = array($_SESSION["id_personal"],60,$pk_observacion,$pk_alumno);
					Database::executeRow($sql, $params);*/
					require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/mails/mailCodigosGraves.php");
				}
			}
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