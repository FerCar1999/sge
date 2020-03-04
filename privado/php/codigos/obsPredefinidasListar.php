<?php
    // obtiene los valores de la peticion y los valida
    $limit = isset($_POST["limit"]) && intval($_POST["limit"]) > 0 ? intval($_POST["limit"])    : 10;
    $offset = isset($_POST["offset"]) && intval($_POST["offset"])>=0    ? intval($_POST["offset"])  : 1;
    $busqueda = isset($_POST["busqueda"]) ? trim($_POST["busqueda"] ): "";
    $order_by = isset($_POST["orden"]) ? $_POST["orden"] : "c.descripcion";
    $estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";

    $busqueda = $busqueda;

    //consulta que deseamos realizar a la db    
    //$query = "SELECT c.id_codigo,c.nombre,c.descripcion,tp.nombre,tp.id_tipo_codigo FROM codigos c, tipos_codigos tp WHERE c.id_tipo_codigo = tp.id_tipo_codigo AND c.estado ='{$estado}'";
    $query = "SELECT c.id_codigo,c.nombre,c.descripcion,tp.nombre,tp.id_tipo_codigo FROM codigos c INNER JOIN tipos_codigos tp ON c.id_tipo_codigo = tp.id_tipo_codigo AND c.estado ='{$estado}' AND tp.nombre='Observaciones'";
    //consulta para crear la paginacion 
    $query_count ="SELECT COUNT(*) as total FROM codigos c INNER JOIN tipos_codigos tp ON c.id_tipo_codigo = tp.id_tipo_codigo AND c.estado ='{$estado}' AND tp.nombre='Observaciones'";
    //busquedas: like nombre,id etc...
    $campos_like="c.descripcion";

    
    // read.php prepara la consulta apartir de los parametros antes definidos
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/read.php");

    // read.php contiene $stmt para poder leer el resultado
    $stmt->bind_result($id_codigo, $nombre, $descripcion, $nombreTipoCodigo, $idTipoCodigo);
    
    // asigna el resultado a jsondataList
    while ($stmt->fetch()) {
        $codigos = array();
        $codigos["id"] = $id_codigo;
        $codigos["nombre"] = $nombre;
        $codigos["descripcion"] = $descripcion;
        $codigos["nombreTipoCodigo"] = $nombreTipoCodigo;
        $codigos["idTipoCodigo"] = $idTipoCodigo;
        $jsondataList[]=$codigos;
    }
    $stmt->close();
    // cierra la conexion 

    $jsondata["lista"] = array_values($jsondataList);   
    header("Content-type:application/json; charset = utf-8");
    echo json_encode($jsondata);
    exit();
?>