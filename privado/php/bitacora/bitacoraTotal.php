<?php  
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
    session_start();

    // obtiene los valores de la peticion y los valida
    $limit = isset($_POST["limit"]) && intval($_POST["limit"]) > 0 ? intval($_POST["limit"])    : 10;
    $offset = isset($_POST["offset"]) && intval($_POST["offset"])>=0    ? intval($_POST["offset"])  : 1;
    $busqueda = isset($_POST["busqueda"]) ? trim($_POST["busqueda"] ): "";
    $order_by = isset($_POST["orden"]) ? $_POST["orden"] : "fecha";
    $idFuncion = isset($_POST["funcion"]) && intval($_POST["funcion"]) > 0 ? intval($_POST["funcion"]) : 0;
    $fechaInicio = isset($_POST["inicio"]) ? trim($_POST["inicio"] ): "";
    $fechaFin = isset($_POST["fin"]) ? trim($_POST["fin"] ): "";
    $pkPersonal = isset($_POST["personal"]) ? trim($_POST["personal"] ): "";

    $busqueda = $busqueda;    

    //consulta que deseamos realizar a la db    
    $query = "SELECT CONCAT(p.codigo , ' ', p.apellido, ' ', p.nombre) AS nombre, f.nombre AS funcion, b.fecha AS fecha, b.descripcion AS descripcion, b.detalle_request AS detalle FROM personal p, funciones_bitacora f, bitacora_personal b WHERE b.id_personal = p.id_personal AND f.id = b.id_funcion";
    if($fechaFin != "" && $fechaInicio != ""){
        $query .= " AND b.fecha BETWEEN '" . $fechaInicio . "' AND DATE_ADD('" . $fechaFin . "', INTERVAL 1 DAY)";
    }
    if($idFuncion != 0){
        $query .= " AND b.id_funcion = " . $idFuncion;
    }
    if($pkPersonal != ""){
        $query .= " AND b.id_personal = (SELECT personal.id_personal FROM personal WHERE personal.codigo = '" . $pkPersonal . "')";
    }
    
    //consulta para crear la paginacion 
    $query_count ="SELECT COUNT(*) AS total FROM bitacora_personal";
    //busquedas: like nombre,id etc...
    $campos_like="nombre, funcion, descripcion";

    
    // read.php prepara la consulta apartir de los parametros antes definidos
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/read.php");

    // read.php contiene $stmt para poder leer el resultado
    $stmt->bind_result($nombre, $funcion, $fecha, $descripcion, $detalle);
    
    // asigna el resultado a jsondataList
    while ($stmt->fetch()) {
        $bitacora = array();
        $bitacora["nombre"] = $nombre;
        $bitacora["funcion"] = $funcion;
        $bitacora["fecha"] = $fecha;
        $bitacora["descripcion"] = $descripcion;
        $bitacora["detalle"] = $detalle;
        $jsondataList[]=$bitacora;
    }
    $stmt->close();
    // cierra la conexion 

    $jsondata["lista"] = array_values($jsondataList);   
    header("Content-type:application/json; charset = utf-8");
    echo json_encode($jsondata);
    exit();

?>