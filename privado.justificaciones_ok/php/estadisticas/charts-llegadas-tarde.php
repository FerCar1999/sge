<?php 
// importa la base de datos
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

// consultas para obtener la informacion
$data1=Database::getRows("select count(*) as cantidad,MONTH(fecha_hora) as mes from impuntualidad where tipo = 'Institución' and  YEAR(fecha_hora) = YEAR(CURDATE()) GROUP BY  MONTH(fecha_hora)",array());
$data2=Database::getRows("select count(*) as cantidad,MONTH(fecha_hora) as mes from impuntualidad where tipo = 'Salón' and  YEAR(fecha_hora) = YEAR(CURDATE()) GROUP BY  MONTH(fecha_hora)",array());


// variables para almcenar la cantidad de llegadas tarde de los dos tipos
$meses1= array();$meses2  = array();
for ($i=0; $i <12 ; $i++) { 
	$meses1[$i] = 0;
	$meses2[$i] = 0;
}
// rellena la informacion 
foreach ($data1 as $key) {
	$meses1[intval($key['mes'])-1] = $key['cantidad'];
}
foreach ($data2 as $key) {
	$meses2[intval($key['mes'])-1] = $key['cantidad'];
}

// envia como json
$jsondata["lista1"] = array_values($meses1);
$jsondata["lista2"] = array_values($meses2);
echo json_encode($jsondata);	

?>
