<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/reiniciarDatos/databaseEx.php");
    $databaseName = $_POST["y"];
    //$databaseName = "diario_pedagogico_2018";
    //$con = new Database();
    Database::setDatabase("diario_pedagogico_" . $databaseName);
    $sql = "SELECT CONCAT(e.codigo,' ', e.apellidos, ', ', e.nombres) AS alumno, e.foto as foto FROM estudiantes e WHERE e.estado = 'Activo' ORDER BY e.apellidos";
    $params = array();
	$data = Database::getRowsAssoc($sql, $params);
	header('Content-Type: application/json');
    echo json_encode($data);
    exit();
?>