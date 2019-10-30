<?php
    // obtiene los valores de la peticion y los valida
    $id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
    $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]): "";
    $estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";


    require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
    
    $sql = "SELECT g.nombre AS nombre,a.id_asignatura AS id_asignatura, a.estado AS estado FROM grados g, asignaturas a WHERE g.id_grado = a.id_grado AND a.nombre = ?";
    
    //$sql = "SELECT grados.nombre AS nombre,asignaturas.id_grado AS id_grado FROM asignaturas,grados WHERE asignaturas.nombre=? AND asignaturas.id_grado = grados.id_grado";
    
    $params = array($nombre);

	$data = Database::getRows($sql, $params);
	
    echo json_encode($data);
    exit();
?>