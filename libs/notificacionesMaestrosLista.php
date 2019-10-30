<?php 
require_once("database.php");
date_default_timezone_set('UTC');
$dia = array('Lunes','Martes','Miercoles','Jueves','Viernes');
$numero_dia = date('N');

$DiaActual = $dia[$numero_dia-1];

// Verifica que se ejecute de Lunes a Viernes
if($numero_dia >=0 && $numero_dia <= count($dia)){
	// obtiene los mestros y su horario
	$data=Database::getRows('select h.id_horario,p.nombre, p.apellido,g.nombre as grado, t.hora_inicial, t.hora_final, p.correo, p.id_personal from horarios h, tiempos t, personal p, grados g where  h.dia=? and h.id_tiempo = t.id_tiempo and t.hora_final < time(now()) and CURDATE() between date(h.inicio) and date(h.fin) and h.id_personal = p.id_personal and h.id_grado = g.id_grado ',array($DiaActual));

		Database::executeRow('delete from maestros_lista_inasistencias where fecha = CURDATE()',array());	
		
	foreach ($data as $row) {
		$lista=Database::getRow('select count(*) as cantidad from asistencias where id_horario= ? and date(fecha_hora) = CURDATE()',array($row['id_horario']));
		
		if(intval($lista['cantidad']) == 0){

			if($row['id_horario'] != null){
				$coincidenciaDatos = Database::getRow('select count(*)  as cantidad from maestros_lista_inasistencias where id_horario = ? and 	fecha = CURDATE()', $row['id_horario']);
				if(intval($coincidenciaDatos['cantidad']) == 0){

					Database::executeRow('insert into maestros_lista_inasistencias(id_horario,fecha) values(?,CURDATE())',array($row['id_horario']));	
			}
			

					// enviar correos si es necesario
					// enviar notificiaciones		
					//require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/notificaciones/nopasarlista.php");
					//notificaciones::insertNotificacion($row["id_personal"], "SIN REGISTRO DE ASISTENCIA", "Estimado docente, se le informa que no se cuenta con registro de asistencia en su anterior clase.");
			}
		}
	}
}
?>