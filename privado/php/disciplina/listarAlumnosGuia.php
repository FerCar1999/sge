<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
    
    
    //$sql = "SELECT grados.nombre AS nombre,asignaturas.id_grado AS id_grado FROM asignaturas,grados WHERE asignaturas.nombre=? AND asignaturas.id_grado = grados.id_grado";
    
    session_start();

    $params = array();

    if ($_SESSION['permiso'] != "Administrador") {
        $sql = "SELECT CONCAT(e.codigo,' ', e.apellidos, ', ', e.nombres) AS alumno, e.foto as foto FROM estudiantes e WHERE e.id_personal=? AND e.estado = 'Activo' ORDER BY e.apellidos";
        $params = array($_SESSION['id_personal']);
    }else{
        $sql = "SELECT CONCAT(e.codigo,' ', e.apellidos, ', ', e.nombres) AS alumno, e.foto as foto FROM estudiantes e  WHERE e.estado = 'Activo' ORDER BY e.apellidos";
        $params = array();
    }
    //$params = array(2);

	$data = Database::getRowsAssoc($sql, $params);
	
	header('Content-Type: application/json');
	//echo json_encode(array('foo' => 'bar'));
    
    echo json_encode($data);
    exit();
?>