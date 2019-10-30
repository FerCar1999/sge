<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
$estudiantes = Database::getRows('select * from vwCantidadLLegadasTardeProcesados',array());
$contador = 0;
foreach ($estudiantes as $key) {
	
	$salon = Database::getRow('select count(*) as cantidad from impuntualidad i,impuntualidad_procesados ip  where  ip.id_estudiante=i.id_estudiante  and i.tipo= "Salón" and estado="Injustificada" and date(i.fecha_hora) between ip.fecha_procesado and CURDATE() and ip.id_estudiante =(select id_estudiante from estudiantes where codigo =?)',array($key['codigo']));		
		$cantidad = (intval($salon["cantidad"])+intval($key['cantidad']));
		
	if($cantidad>=5){
		$contador++;
		$tabla.= "<tr><td>".$contador."</td><td>".$key['codigo']."</td><td>".$key['apellidos'].", ".$key['nombres']."</td><td>".$key['grado']." ".$key['especialidad']." ".$key['gt']." ".$key['seccion'].$key['ga']."</td><td>".$key['cantidad']."</td><td>".$salon['cantidad']."</td><td>".$cantidad."</td><td>".$key['fecha']."</td><td><a class='btn-floating green tooltipped' data-position='bottom' data-delay='50' data-tooltip='Procesar' onclick='procesar_alumno(\"".$key['codigo']."\",\"procesar\");'><i class='material-icons'>check</i></a></td></tr>";			
	}	
	
	
}
echo $tabla;
?>