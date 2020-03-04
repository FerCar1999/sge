<?php
    // obtiene los valores de la peticion y los valida
    $limit = isset($_POST["limit"]) && intval($_POST["limit"]) > 0 ? intval($_POST["limit"])    : 10;
    $offset = isset($_POST["offset"]) && intval($_POST["offset"])>=0    ? intval($_POST["offset"])  : 0;
    $busqueda = isset($_POST["busqueda"]) ? trim($_POST["busqueda"] ): "";
    $order_by = isset($_POST["orden"]) ? $_POST["orden"] : "nombre";
    $estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";
    $tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : 1;

    //obtiene el token enviado por el navegador y lo compara con el de su session activa
    $token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
    session_start();
    /*if(!Token::check($token)){
        echo 'Acción sobre la información denegado';
        exit();
    }*/

    $busqueda = $busqueda;
    $query = "";

    if ($tipo == 1) {
        //consulta que deseamos realizar a la db    
        $query = "SELECT id_grupo_academico,nombre from grupos_academicos where estado ='{$estado}' ";
        //consulta para crear la paginacion 
        $query_count ="select count(*) as total from grupos_academicos where estado ='{$estado}'  ";
    }
    if ($tipo == 2) {
        //consulta que deseamos realizar a la db    
        $query = "SELECT id_grupo_tecnico,nombre from grupos_tecnicos where estado ='{$estado}' ";
        //consulta para crear la paginacion 
        $query_count ="select count(*) as total from grupos_tecnicos where estado ='{$estado}'  ";
    }

    
    //busquedas: like nombre,id etc...
    $campos_like ="nombre";

    
    // read.php prepara la consulta apartir de los parametros antes definidos
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/read.php");

    // read.php contiene $stmt para poder leer el resultado
    $stmt->bind_result($id_grupo, $nombre);
    
    // asigna el resultado a jsondataList
    while ($stmt->fetch()) {
        $grupos = array();
        $grupos["id"] = $id_grupo;
        $grupos["nombre"] = $nombre;
        $jsondataList[] = $grupos;
    }
    $stmt->close();
    // cierra la conexion 
    $jsondata["lista"] = array_values($jsondataList);   
    header("Content-type:application/json; charset = utf-8");
    echo json_encode($jsondata);
    exit();
?>