<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");


    $busqueda = isset($_POST['busqueda']) ? intval($_POST['busqueda']) : null;
    $estado = isset($_POST['estado']) ? intval($_POST['estado']) : 'Activo';
    
    $sql = "SELECT CONCAT(e.codigo,' ', e.apellidos, ', ', e.nombres) AS alumno, e.foto as foto FROM estudiantes e ORDER BY e.apellidos";
    $params = array($estado);
    //$params = array(2);

	$data = Database::getRowsAssoc($sql, $params);
	
	header('Content-Type: application/json');
	//echo json_encode(array('foo' => 'bar'));
    
    echo json_encode($data);
    exit();
?>