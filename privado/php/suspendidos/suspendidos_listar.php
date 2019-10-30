<?php
    // obtiene los valores de la peticion y los valida
    $limit = isset($_POST["limit"]) && intval($_POST["limit"]) > 0 ? intval($_POST["limit"])    : 10;
    $offset = isset($_POST["offset"]) && intval($_POST["offset"])>=0    ? intval($_POST["offset"])  : 0;
    $busqueda = isset($_POST["busqueda"]) ? trim($_POST["busqueda"] ): "";
    $order_by = isset($_POST["orden"]) ? $_POST["orden"] : "nombres";
    $estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";

    $busqueda = $busqueda;

    //consulta que deseamos realizar a la db    
    $query = " select s.id_suspendido,e.codigo,e.nombres,e.apellidos,g.nombre as grado,es.nombre as espe,e.foto,s.inicio, s.fin  from estudiantes e, grados g, especialidades es, suspendidos s
where s.id_personal is not null and s.id_estudiante= e.id_estudiante and e.id_grado=g.id_grado and  e.id_especialidad=es.id_especialidad and CURDATE() between s.inicio and s.fin ";
    //consulta para crear la paginacion 
    $query_count ="select count(*) as total  from estudiantes e, grados g, especialidades es, suspendidos s
where s.id_personal is not null and s.id_estudiante= e.id_estudiante and e.id_grado=g.id_grado and  e.id_especialidad=es.id_especialidad and CURDATE() between s.inicio and s.fin ";
    //busquedas: like nombre,id etc...
    $campos_like="nombres";

    
    // read.php prepara la consulta apartir de los parametros antes definidos
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/read.php");

    // read.php contiene $stmt para poder leer el resultado
    $stmt->bind_result($id_suspendido, $codigo, $nombres,$apellidos,$grado,$espe,$foto,$inicio,$fin);
    
    // asigna el resultado a jsondataList
    while ($stmt->fetch()) {
        $supendidos = array();
        $supendidos["id"] = $id_suspendido;
        $supendidos["codigo"] =$codigo;
        $supendidos["nombre"] =$apellidos.', '.$nombres;
        $supendidos["grado"] =$grado;
        $supendidos["espe"] =$espe;
        $supendidos["url"] =$foto;
        $supendidos["inicio"] =$inicio;
        $supendidos["fin"] =$fin;
        $jsondataList[]=$supendidos;
    }
    $stmt->close();
    // cierra la conexion 

    $jsondata["lista"] = array_values($jsondataList);   
    header("Content-type:application/json; charset = utf-8");
    echo json_encode($jsondata);
    exit();
?>