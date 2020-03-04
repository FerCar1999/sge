<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
date_default_timezone_set('America/Los_Angeles');

$dia = array('Lunes','Martes','Miercoles','Jueves','Viernes');
$numero_dia = date('N');

$materias = Database::getRows('select h.id_horario,t.hora_inicial,t.hora_final,a.nombre,g.nombre as grado from horarios h,asignaturas a, tiempos t,asistencias_diferidas ad, grados g where h.id_personal = ? and h.id_asignatura = a.id_asignatura and h.id_tiempo = t.id_tiempo and h.dia = ? and ad.id_horario = h.id_horario and h.id_grado = g.id_grado and date(ad.fecha) = CURDATE() ', array($_SESSION["id_personal"],$dia[$numero_dia-1]));

$table ="";
foreach ($materias as $row) {
	$table.= "<tr><td>".$row['nombre']."</td><td>".$row['grado']."</td><td>".$row['hora_inicial']."</td><td>".$row['hora_final']."</td><td><a class='btn-floating teal' onclick='habilitar_diferida(\"".$row['id_horario']."\");'><i class='material-icons'>add</i></a> ";
}
echo $table;
?>