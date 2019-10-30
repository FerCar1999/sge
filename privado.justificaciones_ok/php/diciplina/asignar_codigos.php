<?php 	
	session_start();
	//evalua los valores del POST	
	$pk_alumno = isset($_POST['pk_alumno']) ? intval($_POST['pk_alumno']) : 0;
	$pk_horario = isset($_POST['pk_horario']) ? intval($_POST['pk_horario']) : null;
	$pk_codigo = isset($_POST['pk_codigo']) ? intval($_POST['pk_codigo']) : 0;
	$pk_horario = isset($_POST['pk_horario']) ? intval($_POST['pk_horario']) : null;	
	$pk_observacion = isset($_POST["pk_observacion"]) ? $_POST["pk_observacion"] : "";
	

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
		$sql ="insert into disciplina(id_personal,id_estudiante,fecha_hora,id_horario,id_codigo,observacion) values(?,(select id_estudiante from estudiantes  where codigo =?),(select now()),?,?,?)";
 		$params = array($_SESSION["id_personal"],$pk_alumno,$pk_horario,$pk_codigo,$pk_observacion);
		if(Database::executeRow($sql, $params)){
			echo 'Código asignado correctamente.';
		}	
	}	

	if ($pk_horario == null) {
		$sql = "INSERT INTO disciplina(id_estudiante, id_personal, fecha_hora, id_codigo,observacion) SELECT id_estudiante,?,(SELECT NOW()),?,? FROM estudiantes WHERE codigo=?";
 		$params = array($_SESSION["id_personal"],$pk_codigo,$pk_observacion,$pk_alumno);
		if(Database::executeRow($sql, $params)){
			echo 'Código asignado correctamente.';
		}
	}
?>