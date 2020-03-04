<?php
    // obtiene los valores de la peticion y los valida
    $limit = isset($_POST["limit"]) && intval($_POST["limit"]) > 0 ? intval($_POST["limit"])    : 10;
    $offset = isset($_POST["offset"]) && intval($_POST["offset"])>=0    ? intval($_POST["offset"])  : 1;
    $busqueda = isset($_POST["busqueda"]) ? trim($_POST["busqueda"] ): "";
    $order_by = isset($_POST["orden"]) ? $_POST["orden"] : "g.nombre";
    $estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";

    $busqueda = $busqueda;

    //consulta que deseamos realizar a la db    
    //$query = "SELECT a.id_asignatura,a.nombre,ta.nombre,a.codigo FROM asignaturas a, tipos_asignaturas ta WHERE a.id_tipo_asignatura = ta.id_tipo_asignatura and a.estado ='{$estado}' GROUP BY a.nombre";
    

    $query = "SELECT a.id_asignatura,a.nombre,a.id_tipo_asignatura,a.codigo,a.id_grado,g.nombre FROM asignaturas a INNER JOIN grados g ON a.id_grado = g.id_grado WHERE a.estado = '{$estado}'";
    //consulta para crear la paginacion 
    $query_count ="select count(*) as total from asignaturas a INNER JOIN grados g ON a.id_grado = g.id_grado where a.estado ='{$estado}'";
    //busquedas: like nombre,id etc...
    $campos_like="a.nombre,a.codigo,g.nombre";

    
    // read.php prepara la consulta apartir de los parametros antes definidos
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/read.php");

    // read.php contiene $stmt para poder leer el resultado
    $stmt->bind_result($id_asignatura, $nombre, $id_tipo_asignatura, $codigo, $id_grado, $nombre_grado);
    
    // asigna el resultado a jsondataList
    while ($stmt->fetch()) {
        $tipoAsignaturas = array();
        $tipoAsignaturas["id"] = $id_asignatura;
        $tipoAsignaturas["nombre"] = $nombre;
        $tipoAsignaturas["tipoAsignatura"] = $id_tipo_asignatura;
        $tipoAsignaturas["codigo"] = $codigo;
        $tipoAsignaturas["grado"] = $id_grado;
        $tipoAsignaturas["ngrado"] = $nombre_grado;
        $jsondataList[]=$tipoAsignaturas;
    }
    $stmt->close();
    // cierra la conexion 

    $jsondata["lista"] = array_values($jsondataList);   
    header("Content-type:application/json; charset = utf-8");
    echo json_encode($jsondata);
    exit();
?>