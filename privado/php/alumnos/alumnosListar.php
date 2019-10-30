<?php
    // obtiene los valores de la peticion y los valida
    $limit = isset($_POST["limit"]) && intval($_POST["limit"]) > 0 ? intval($_POST["limit"])    : 10;
    $offset = isset($_POST["offset"]) && intval($_POST["offset"])>=0    ? intval($_POST["offset"])  : 1;
    $busqueda = isset($_POST["busqueda"]) ? trim($_POST["busqueda"] ): "";
    $order_by = isset($_POST["orden"]) ? $_POST["orden"] : "apellidos";
    $estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";


    $busqueda = $busqueda;    
    if($estado=="Inactivo"){
        $estado="='Inactivo'";
    }else if($estado=="Activo"){
        $estado="<>'Inactivo'";    
    }

    //consulta que deseamos realizar a la db    
    $query = "SELECT id_estudiante,nombres,apellidos,codigo,correo,foto,id_especialidad,id_grado,id_seccion,id_grupo_academico,id_grupo_tecnico,id_personal from estudiantes where estado {$estado} ";
    //consulta para crear la paginacion 
    $query_count ="select count(*) as total from estudiantes where estado {$estado} ";
    //busquedas: like nombre,id etc...
    $campos_like="nombres,apellidos,codigo";

    
    // read.php prepara la consulta apartir de los parametros antes definidos
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/read.php");

    // read.php contiene $stmt para poder leer el resultado
    $stmt->bind_result($id_estudiante, $nombres, $apellidos, $codigo, $correo, $foto, $id_especialidad, $id_grado, $id_seccion,$id_grupo_academico,$id_grupo_tecnico, $id_personal);
    
    // asigna el resultado a jsondataList
    while ($stmt->fetch()) {
        $alumnos = array();
        $alumnos["id"] = $id_estudiante;
        $alumnos["nombres"] = $nombres;
        $alumnos["apellidos"] = $apellidos;
        $alumnos["codigo"] = $codigo;
        $alumnos["correo"] = $correo;
        $alumnos["foto"] = $foto;
        $alumnos["idGrado"] = $id_grado;
        $alumnos["idEspecialiad"] = $id_especialidad;
        $alumnos["idSeccion"] = $id_seccion;
        $alumnos["idGrupoTec"] = $id_grupo_tecnico;
        $alumnos["idGrupoAcad"] = $id_grupo_academico;
        $alumnos["idPersonal"] = $id_personal;

        $jsondataList[]=$alumnos;
    }
    $stmt->close();
    // cierra la conexion 

    $jsondata["lista"] = array_values($jsondataList);   
    header("Content-type:application/json; charset = utf-8");
    echo json_encode($jsondata);
    exit();
?>