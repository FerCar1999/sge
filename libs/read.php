<?php 
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	$con = Database::conect_read();
	// si hay palabras 
	if ($busqueda) {
		// Separar cada palabra
		$palabras = explode(" ", trim($busqueda));
		// Eliminar palabras vacias "" y las agrega al array busqueda
		unset($busqueda);
		foreach ($palabras as $pal) {
			if ($pal)
				$busqueda[] = "%".$pal."%";		// Agrega los %% para poder buscar con like
			}
	}

	$tipos = "";
	// Si hay palabras que buscar
	if ($busqueda) {

		$query .= " and ";
		$query_count .= " and ";

		// A todos los campos juntos, un like por cada palabra
		for ($i = 0; $i < count($busqueda); $i++) {
			$query .= "concat({$campos_like}) like ? and ";
			$query_count .= "concat({$campos_like}) like ? and ";
			$tipos .= "s";
		}
		// Eliminar los ultimos 4 caracteres "and "
		$query = substr($query, 0, -4);
		$query_count = substr($query_count, 0, -4);
	}
	
	// campo a ordenar
	$query .= " order by {$order_by} ";
	
	// Devolver la pagina pedida
	$query .= " limit {$limit} offset {$offset} ";

	//prepara ambas consultas
	$stmt = $con->prepare($query);
	$stmt_count = $con->prepare($query_count);

	// Meter todos los parametros en un solo array
	if ($busqueda) {
		$parametros = array();	$parametros[] = &$tipos;
		for ($i = 0; $i < count($busqueda); $i++)
			$parametros[] = &$busqueda[$i];

		// Insertar valores a buscar
		call_user_func_array(array($stmt, "bind_param"), $parametros);
		call_user_func_array(array($stmt_count, "bind_param"), $parametros);
	}
	
	// array para añadir los resultados
	$jsondataList = array();
	// array para añadir jsondataList
	$jsondata = array();

	//ejecuta y obtiene la cantidad total registros para calcular la paginacion
	$stmt_count->execute();
    $stmt_count->bind_result($total);
    $cantidad = 0;
   	while($stmt_count->fetch()){
   		$cantidad= $total;
   	}
   	$stmt_count->close();
   	// agrega la llave cantidad al futuro json
    $jsondata["cantidad"] = $cantidad;
	
	// ejecuta la consulta para obtener los datos que han solicitado
	$stmt->execute();
	?>