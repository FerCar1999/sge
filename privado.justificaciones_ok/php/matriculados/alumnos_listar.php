<?php
    // obtiene los valores de la peticion y los valida
    $limit = isset($_POST["limit"]) && intval($_POST["limit"]) > 0 ? intval($_POST["limit"])    : 10000;
    $offset = isset($_POST["offset"]) && intval($_POST["offset"])>=0 ? intval($_POST["offset"])  : 0;
    $busqueda = isset($_POST["busqueda"]) ? trim($_POST["busqueda"] ): "";
    $order_by = isset($_POST["orden"]) ? $_POST["orden"] : "nombres";
    $estado = isset($_POST["estado"]) ? $_POST["estado"] : "pendiente";

    $busqueda = $busqueda;

    //consulta que deseamos realizar a la db    
    $query = "select id_estudiante,nombres,apellidos,codigo from estudiantes where estado ='{$estado}' ";
    //consulta para crear la paginacion 
    $query_count ="select count(*) as total from estudiantes where estado ='{$estado}'  ";
    //busquedas: like nombre,id etc...
    $campos_like="nombres,apellidos,codigo";

    
    // read.php prepara la consulta apartir de los parametros antes definidos
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/read.php");

    // read.php contiene $stmt para poder leer el resultado
    $stmt->bind_result($id_estdiante, $nombre,$apellido,$codigo);   
    
    // asigna el resultado a jsondataList
    while ($stmt->fetch()) {
        $estdiantes = array();
        $estdiantes["id"] = $id_estdiante;
        $estdiantes["nombre"] = $nombre;        
        $estdiantes["apellido"] = $apellido;
        $estdiantes["codigo"] = $codigo;
        

        $jsondataList[]=$estdiantes;
    }
    $stmt->close();
    // cierra la conexion 

    $jsondata["lista"] = array_values($jsondataList);   
    header("Content-type:application/json; charset = utf-8");
    echo json_encode($jsondata);
    exit();
?>