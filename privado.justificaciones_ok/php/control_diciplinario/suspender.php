<?php 
	// evalua los valores del POST
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$observacion = isset($_POST["observacion"]) ? trim($_POST["observacion"]) : ("");	
	//obtiene el token enviado por el navegador y lo compara con el de su session activa
	$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();
	if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	// si es un id valido
	if($id > 0 ){

		$sql ="update estudiantes set procesado = 0, fecha_procesado = (select now()) where id_estudiante=?";
 		$params = array(strip_tags($id));
 
		Database::executeRow($sql, $params);

		$sql ="insert into suspendidos(id_estudiante,observacion) values(?,?)";
 		$params = array(strip_tags($id),strip_tags($observacion));
 
		Database::executeRow($sql, $params);	
	}

?>