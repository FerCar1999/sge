<?php 	
	session_start();
	date_default_timezone_set("America/El_Salvador");
	//evalua los valores del POST	
	$pk_alumno = isset($_POST['pk_alumno']) ? intval($_POST['pk_alumno']) : 0;
	$hora = isset($_POST["hora"]) ? trim($_POST["hora"]): "00:00";
	$fecha = isset($_POST["fecha"]) ? trim($_POST["fecha"]): "0000-00-00";
	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	//session_start();
	/*if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}*/

	if ($fecha == "0000-00-00") {
		$fecha = date('Y-m-d');
	}
	if ($hora == "00:00") {
		$hora = date("H:i");
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

	if (!validarNumeroEntero($pk_alumno)) {
		echo "Estudiante no registrado.";
		exit();
	}
	if (!validarTiempo($hora)) {
		echo "Hora inválida.";
		exit();
	}
	if (!validarFecha($fecha) || date('Y-m-d',strtotime($fecha)) > date('Y-m-d')) {
		echo "Fecha inválida o mayor a la actual.";
		exit();
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	$data=Database::getRow("SELECT COUNT(i.id_impuntualidad) AS llegadas FROM impuntualidad i WHERE i.id_estudiante=(SELECT id_estudiante FROM estudiantes WHERE codigo=?) AND i.fecha_hora = CURDATE()",array($pk_alumno));

   	if ($data[0] > 0) {
   		exit();
   	}

	if ($hora == "00:00") {
		$sql ="INSERT INTO impuntualidad(id_estudiante, fecha_hora, tipo) SELECT id_estudiante,(SELECT NOW()),? FROM estudiantes WHERE codigo=?";
		$params = array('Institución',$pk_alumno);
	}else{
		//$fecha_hora = date('Y/m/d')." ".$hora.(':00');
		$fecha_hora = date('Y/m/d',strtotime($fecha))." ".$hora.(':00');
		$sql ="INSERT INTO impuntualidad(id_estudiante, fecha_hora, tipo) SELECT id_estudiante,?,? FROM estudiantes WHERE codigo=?";
		$params = array($fecha_hora,'Institución',$pk_alumno);
	}
	if(Database::executeRow($sql, $params)){
		echo "success";
	}else{
		echo "error";
	}
	
?>