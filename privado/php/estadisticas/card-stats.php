<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
date_default_timezone_set('America/Los_Angeles');
$dia = array('Lunes','Martes','Miercoles','Jueves','Viernes');
$numero_dia = date('N');

function stat_llegadas_tarde(){
	$data=Database::getRow("select count(*) as cantidad from impuntualidad where date(fecha_hora) = CURDATE() and tipo ='Institución'",array());
	return $data['cantidad']; 
}
function stat_llegadas_tarde_aclase(){
	$data=Database::getRow("select count(*) as cantidad from impuntualidad where date(fecha_hora) = CURDATE() and tipo ='Salón'",array());
	return $data['cantidad']; 	
}

function stat_inasistencias(){
	$data=Database::getRow('select (select count(*) from estudiantes where estado = "Activo") - (select count(*) from vw_get_codigos_today) as cantidad',array());
	return $data['cantidad']; 	
}
function stat_codigos(){
	$data=Database::getRow('select count(*) as cantidad from disciplina where date(fecha_hora) = CURDATE() ',array());
	return $data['cantidad']; 	
}
function stat_clases_pendientes(){
	$dia = array('Lunes','Martes','Miercoles','Jueves','Viernes');
	$numero_dia = date('N');
	$data=Database::getRow('select count(*) as cantidad from horarios h, tiempos t where h.id_personal = ? and h.dia = ? and h.id_tiempo = t.id_tiempo and time(t.hora_final) > time(now()) ',array($_SESSION["id_personal"],$dia[$numero_dia-1]));
	return $data['cantidad']; 	
}
function stat_clases_sin_pasar_lista(){
	$dia = array('Lunes','Martes','Miercoles','Jueves','Viernes');
	$numero_dia = date('N');
	$cantidad = 0;
	$data=Database::getRows('select h.id_horario,h.id_personal from horarios h, tiempos t where h.id_personal = ? and h.dia = ? and CURDATE() between date(h.inicio) and date(h.fin) and h.id_tiempo = t.id_tiempo and time(t.hora_final) < time(now())',array($_SESSION["id_personal"],$dia[$numero_dia-1]));
	foreach ($data as $row) {
		$lista=Database::getRow('select count(*) as cantidad from asistencias where id_horario= ? and date(fecha_hora) = CURDATE()',array($row['id_horario']));
		if(intval($lista['cantidad']) == 0) $cantidad++;		
	}
	return $cantidad; 		
}
function stat_codigos_asiganados_maestro(){
	$data=Database::getRow('select count(*) as cantidad from disciplina where id_personal= ? and date(fecha_hora) = CURDATE()',array($_SESSION["id_personal"]));
	return $data['cantidad']; 	
}
function stat_observaciones_asignadas_maestro(){
	$data=Database::getRow('select count(*) as cantidad from observaciones where id_personal= ? and date(fecha) = CURDATE()',array($_SESSION["id_personal"]));
	return $data['cantidad']; 	
}

?>


