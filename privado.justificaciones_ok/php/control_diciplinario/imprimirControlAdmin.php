<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
$id_grado = isset($_POST["id"]) ? trim($_POST["id"]): "1";

$grado = Database::getRow('select id_especialidad, id_grado,id_grupo_tecnico from estudiantes where id_grado=? limit 1 ', array($id_grado));
 
 // obtiene el nivel del grado
$nivel = Database::getRow('select id_nivel from grados where id_grado = ?',array($id_grado));
$fechas = Database::getRow('select fecha_inicial,fecha_final from etapas where id_nivel=(select id_nivel from grados where id_grado=?) and CURDATE() between fecha_inicial and fecha_final',array($id_grado));

$estudiantes;
// si es tercer ciclo 
if(intval($nivel['id_nivel']) ==1){
	$estudiantes = Database::getRows('select id_estudiante,fecha_procesado from estudiantes where procesado =1 and id_grado=?  order by apellidos desc',array($id_grado));	
}else {
	$estudiantes = Database::getRows('select id_estudiante,fecha_procesado from estudiantes where procesado =1 and id_grado=? order by apellidos desc',array($id_grado));
}
$sql = "select e.id_estudiante,e.codigo,e.nombres, e.apellidos,e.foto,tc.nombre as tipo, count(e.nombres) as cantidad,tc.cantidad as cantidad_codigos,tc.id_tipo_codigo  from estudiantes e,disciplina d, codigos c, tipos_codigos tc  where d.id_estudiante = e.id_estudiante and d.id_codigo= c.id_codigo and c.id_tipo_codigo =tc.id_tipo_codigo and browseable = 'SI' and date(d.fecha_hora) between ? and ? and e.procesado = 1  and e.id_estudiante =? group by tipo";
// variables
$tabla="";$reincidencia=0;$total=0;
foreach ($estudiantes as $key) {
	$fecha_inico = "";$reincidencias = false;
	//si ya ha sido procesado en el periodo
	if($key['fecha_procesado'] !=null){
	 	$fecha_inico = $key['fecha_procesado'];
	 	$reincidencias= true;
	}
	else $fecha_inico = $fechas['fecha_inicial'];

// hace que codigos sean diferente de positivos
	// reincidencia
	$data_codigos = Database::getRows($sql,array($fecha_inico,$fechas['fecha_final'],$key['id_estudiante']));
	
	foreach ($data_codigos as $data) {
		$reincidencia = $data['cantidad'];
		$data_total = Database::getRow("select d.id_estudiante, count(e.nombres) as cantidad from estudiantes e,disciplina d, codigos c, tipos_codigos tc  where d.id_estudiante = e.id_estudiante and d.id_codigo= c.id_codigo and c.id_tipo_codigo =tc.id_tipo_codigo and date(d.fecha_hora) between ? and ? and e.procesado = 1  and tc.id_tipo_codigo= ?  group by id_estudiante ",array($fechas['fecha_inicial'],$fechas['fecha_final'],$data['id_tipo_codigo']));

		//$total=$data_total['cantidad'];	
		// agregado los tooltip
		$tabla.= "<tr><td><a onclick=\"cargarEtapas('".$data['codigo']."');\" href='#'>".$data['codigo']."</a></td><td>".$data['apellidos'].", ".$data['nombres']."</td><td>".$data['tipo']."</td><td>$reincidencia</td><td><a class='btn-floating green tooltipped' data-position='bottom' data-delay='50' data-tooltip='Procesar' onclick='procesar_alumno(\"".$data['id_estudiante']."\",\"procesar\");'><i class='material-icons'>check</i></a>  <a class='btn-floating green tooltipped' data-position='bottom' data-delay='50' data-tooltip='Procesar y Suspender' onclick='procesar_alumno(\"".$data['id_estudiante']."\",\"suspender\");'><i class='material-icons'>gavel</i></a>  </td>";		
		$reincidencia=0;
	}	
}
echo $tabla;
?>