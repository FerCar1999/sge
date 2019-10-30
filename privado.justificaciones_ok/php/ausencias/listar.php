<?php
    // obtiene los valores de la peticion y los valida
    $limit = isset($_POST["limit"]) && intval($_POST["limit"]) > 0 ? intval($_POST["limit"])    : 10;
    $offset = isset($_POST["offset"]) && intval($_POST["offset"])>=0    ? intval($_POST["offset"])  : 0;
    $busqueda = isset($_POST["busqueda"]) ? trim($_POST["busqueda"] ): "";
    $order_by = isset($_POST["orden"]) ? $_POST["orden"] : "nombres";
    $estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";

    $busqueda = $busqueda;

    //consulta que deseamos realizar a la db    
    $query = "select s.id_ausencia,e.codigo,e.nombres,e.apellidos,g.nombre as grado,es.nombre as espe,e.foto,s.inicio, s.fin,s.motivo,s.permiso  from estudiantes e, grados g, especialidades es, ausencias_justificadas s
where   s.id_estudiante= e.id_estudiante and e.id_grado=g.id_grado and  e.id_especialidad=es.id_especialidad and CURDATE() < s.fin ";
    //consulta para crear la paginacion 
    $query_count ="select s.id_ausencia,e.codigo,e.nombres,e.apellidos,g.nombre as grado,es.nombre as espe,e.foto,s.inicio, s.fin,s.motivo  from estudiantes e, grados g, especialidades es, ausencias_justificadas s
where   s.id_estudiante= e.id_estudiante and e.id_grado=g.id_grado and  e.id_especialidad=es.id_especialidad and CURDATE() < s.fin ";
    //busquedas: like nombre,id etc...
    $campos_like="nombres";

    
    // read.php prepara la consulta apartir de los parametros antes definidos
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/read.php");

    // read.php contiene $stmt para poder leer el resultado
    $stmt->bind_result($id_ausencia, $codigo, $nombres,$apellidos,$grado,$espe,$foto,$inicio,$fin,$motivo,$permiso);
    
    // asigna el resultado a jsondataList
    while ($stmt->fetch()) {
        $ausentes = array();
        $ausentes["id"] = $id_ausencia;
        $ausentes["codigo"] =$codigo;
        $ausentes["nombre"] =$apellidos.', '.$nombres;
        $ausentes["grado"] =$grado;
        $ausentes["espe"] =$espe;
        $ausentes["url"] =$foto;
        $ausentes["inicio"] =$inicio;
        $ausentes["fin"] =$fin;
        $ausentes["motivo"] =$motivo;
        $ausentes["permiso"] =$permiso;
        $jsondataList[]=$ausentes;
    }
    $stmt->close();
    // cierra la conexion 

    $jsondata["lista"] = array_values($jsondataList);   
    header("Content-type:application/json; charset = utf-8");
    echo json_encode($jsondata);
    exit();
?>