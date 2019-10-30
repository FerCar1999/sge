<?php
    // obtiene los valores de la peticion y los valida
    $limit = isset($_POST["limit"]) && intval($_POST["limit"]) > 0 ? intval($_POST["limit"])    : 10000;
    $offset = isset($_POST["offset"]) && intval($_POST["offset"])>=0    ? intval($_POST["offset"])  : 0;
    $busqueda = isset($_POST["busqueda"]) ? trim($_POST["busqueda"] ): "";
    $order_by = isset($_POST["orden"]) ? $_POST["orden"] : "nombres";
    $estado = isset($_POST["estado"]) ? $_POST["estado"] : "draft";
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $id_grupo = isset($_POST['id_grupo']) ? intval($_POST['id_grupo']) : 0;

    $busqueda = $busqueda;

    //consulta que deseamos realizar a la db    
    $query = "select id_estudiante,nombres,apellidos,codigo,gt.nombre as grupo from estudiantes e, grupos_tecnicos gt where e.id_grupo_tecnico=gt.id_grupo_tecnico and id_seccion={$id} and e.id_grupo_tecnico={$id_grupo} and e.estado ='{$estado}' ";
    //consulta para crear la paginacion 
    $query_count ="select count(*) as total from estudiantes e, grupos_tecnicos gt where e.id_grupo_tecnico=gt.id_grupo_tecnico and id_seccion={$id}  and e.id_grupo_tecnico={$id_grupo} and e.estado ='{$estado}'  ";
    //busquedas: like nombre,id etc...
    $campos_like="nombres,apellidos,codigo";

    
    // read.php prepara la consulta apartir de los parametros antes definidos
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/read.php");

    // read.php contiene $stmt para poder leer el resultado
    $stmt->bind_result($id_estudiante, $nombre,$apellido,$codigo,$grupo);   
    
    // asigna el resultado a jsondataList
    while ($stmt->fetch()) {
        $estdiantes = array();
        $estdiantes["id"] = $id_estudiante;
        $estdiantes["nombre"] = $nombre;        
        $estdiantes["apellido"] = $apellido;
        $estdiantes["codigo"] = $codigo;
        $estdiantes["grupo"] = $grupo;
        

        $jsondataList[]=$estdiantes;
    }
    $stmt->close();
    // cierra la conexion 

    $jsondata["lista"] = array_values($jsondataList);   
    header("Content-type:application/json; charset = utf-8");
    echo json_encode($jsondata);
    exit();
?>