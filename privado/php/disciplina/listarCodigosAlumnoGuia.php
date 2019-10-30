<?php
    
    $carnet = isset($_POST["carnet"]) && intval($_POST["carnet"]) >0 ? intval($_POST["carnet"]) : null;

    require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
    
    $sql = "SELECT d.id_disciplina,e.id_estudiante,CONCAT(e.nombres, ' ', e.apellidos) AS alumno,e.foto, e.codigo,CONCAT('<b>', c.nombre, '</b> ; ', p.nombre, ' ', p.apellido, ' ; ', d.fecha_hora) AS descripcion FROM disciplina d, estudiantes e, codigos c, personal p WHERE c.id_codigo = d.id_codigo AND e.id_estudiante = d.id_estudiante AND p.id_personal = d.id_personal AND e.codigo=? ORDER BY d.fecha_hora";
    
    //$sql = "SELECT grados.nombre AS nombre,asignaturas.id_grado AS id_grado FROM asignaturas,grados WHERE asignaturas.nombre=? AND asignaturas.id_grado = grados.id_grado";
    
    session_start();

    //$params = array($_SESSION['id_personal']);
    //$params = array(2);
    $params = array($carnet);

	$data = Database::getRowsAssoc($sql, $params);
	
	header('Content-Type: application/json');
    echo json_encode($data);
    exit();
?>