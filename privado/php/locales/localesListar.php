<?php
    // obtiene los valores de la peticion y los valida
    $limit = isset($_POST["limit"]) && intval($_POST["limit"]) > 0 ? intval($_POST["limit"])    : 10;
    $offset = isset($_POST["offset"]) && intval($_POST["offset"])>=0    ? intval($_POST["offset"])  : 1;
    $busqueda = isset($_POST["busqueda"]) ? trim($_POST["busqueda"] ): "";
    $order_by = isset($_POST["orden"]) ? $_POST["orden"] : "l.nombre";
    $estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";

    $busqueda = $busqueda;

    //consulta que deseamos realizar a la db    
    $query = "SELECT l.id_local,l.nombre,l.capacidad,tl.nombre,tl.id_tipo_local FROM locales l INNER JOIN tipos_locales tl ON l.id_tipo_local = tl.id_tipo_local AND l.estado ='{$estado}'";
    //consulta para crear la paginacion 
    $query_count ="SELECT COUNT(*) as total FROM locales l INNER JOIN tipos_locales tl ON l.id_tipo_local = tl.id_tipo_local AND l.estado ='{$estado}'";
    //busquedas: like nombre,id etc...
    $campos_like="l.nombre";

    
    // read.php prepara la consulta apartir de los parametros antes definidos
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/read.php");

    // read.php contiene $stmt para poder leer el resultado
    $stmt->bind_result($id_local, $nombre, $capacidad, $nombreTipoLocal, $idTipoLocal);
    
    // asigna el resultado a jsondataList
    while ($stmt->fetch()) {
        $locales = array();
        $locales["id"] = $id_local;
        $locales["nombre"] = $nombre;
        $locales["capacidad"] = $capacidad;
        $locales["nombreTipoLocal"] = $nombreTipoLocal;
        $locales["idTipoLocal"] = $idTipoLocal;
        $jsondataList[]=$locales;
    }
    $stmt->close();
    // cierra la conexion 

    $jsondata["lista"] = array_values($jsondataList);   
    header("Content-type:application/json; charset = utf-8");
    echo json_encode($jsondata);
    exit();
?>