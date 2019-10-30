<?php
    // obtiene los valores de la peticion y los valida
    $estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";

    //consulta que deseamos realizar a la db    
    $query = "SELECT id_permiso, nombre FROM permisos where estado ='{$estado}' ";
    
    // read.php prepara la consulta apartir de los parametros antes definidos
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/read.php");

    // read.php contiene $stmt para poder leer el resultado
    $stmt->bind_result($id_permiso, $nombre);
    
    // asigna el resultado a jsondataList
    while ($stmt->fetch()) {
        $permisolista = array();
        $permisolista["id"] = $id_permiso;
        $permisolista["nombre"] = $nombre;

        $jsondataList[]=$permisolista;
    }
    $stmt->close();
    // cierra la conexion 

    $jsondata["lista"] = array_values($jsondataList);   
    header("Content-type:application/json; charset = utf-8");
    echo json_encode($jsondata);
    exit();
?>