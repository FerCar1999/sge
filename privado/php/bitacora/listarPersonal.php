<?php  
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
    session_start();
    $sql = "SELECT CONCAT(p.codigo,' ', p.apellido, ', ', p.nombre) AS persona, p.foto as foto FROM personal p WHERE p.estado = 'Activo' ORDER BY p.apellido";
    $params = array();
	$data = Database::getRowsAssoc($sql, $params);
	header('Content-Type: application/json');
    echo json_encode($data);
    exit();
?>