<?php 
// importa la base de datos
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

// consultas para obtener la informacion
$data1=Database::getRows("select count(*) as cantidad,MONTH(fecha_hora) as mes from disciplina where  YEAR(fecha_hora) = YEAR(CURDATE()) GROUP BY  MONTH(fecha_hora)",array());

// variable para almcenar la cantidad 
$meses1= array();
for ($i=0; $i <12 ; $i++) { 
	$meses1[$i] = 0;	
}
// rellena la informacion 
foreach ($data1 as $key) {
	$meses1[intval($key['mes'])-1] = $key['cantidad'];
}

// envia como json
$jsondata["lista1"] = array_values($meses1);
echo json_encode($jsondata);	
?>
