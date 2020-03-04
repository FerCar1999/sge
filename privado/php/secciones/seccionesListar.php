<?php
    // obtiene los valores de la peticion y los valida
    $limit = isset($_POST["limit"]) && intval($_POST["limit"]) > 0 ? intval($_POST["limit"])    : 10;
    $offset = isset($_POST["offset"]) && intval($_POST["offset"])>=0    ? intval($_POST["offset"])  : 0;
    $busqueda = isset($_POST["busqueda"]) ? trim($_POST["busqueda"] ): "";
    $order_by = isset($_POST["orden"]) ? $_POST["orden"] : "nombre";
    $estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";

    $busqueda = $busqueda;

    //consulta que deseamos realizar a la db    
    $query = "select id_seccion,nombre from secciones where estado ='{$estado}' ";
    //consulta para crear la paginacion 
    $query_count ="select count(*) as total from secciones where estado ='{$estado}'  ";
    //busquedas: like nombre,id etc...
    $campos_like="nombre";

    
    // read.php prepara la consulta apartir de los parametros antes definidos
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/read.php");

    // read.php contiene $stmt para poder leer el resultado
    $stmt->bind_result($id_seccion, $nombre);
    
    // asigna el resultado a jsondataList
    while ($stmt->fetch()) {
        $secciones = array();
        $secciones["id"] = $id_seccion;
        $secciones["nombre"] = $nombre;
        $jsondataList[]=$secciones;
    }
    $stmt->close();
    // cierra la conexion 

    $jsondata["lista"] = array_values($jsondataList);   
    header("Content-type:application/json; charset = utf-8");
    echo json_encode($jsondata);
    exit();
?>