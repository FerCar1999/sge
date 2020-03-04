<?php 
// POST Params
$fecha = isset($_POST["fecha"]) ? trim($_POST["fecha"]): "";
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

// Maestros sin pasar lista
// $maestros = Database::getRows('SELECT ho.dia,pe.codigo,pe.nombre,pe.apellido,ho.id_grupo_tecnico,ho.id_grupo_academico, asig.nombre as materia, es.nombre as especialidad,sec.nombre as seccion,ga.nombre as grado,ti.hora_inicial,ti.hora_final,lo.nombre as local, ga.id_nivel
// FROM maestros_lista_inasistencias mi, horarios ho, personal pe, asignaturas asig, especialidades es, secciones sec, grados ga, tiempos ti, locales lo 
// WHERE mi.fecha = date (?) and mi.id_horario = ho.id_horario and ho.id_personal = pe.id_personal and ho.id_asignatura = asig.id_asignatura and ho.id_especialidad = es.id_especialidad and ho.id_seccion = sec.id_seccion and ho.id_grado = ga.id_grado and ho.id_tiempo = ti.id_tiempo and ho.id_local = lo.id_local',array($fecha));

$response = array();

foreach (obtenerClases($fecha) as $row) {
  $data['codigo']=$row["codigo"];
  $data["nombre"] = $row["apellido"].", ".$row['nombre'];
  $data["hora"] = $row["hora_inicial"]." ".$row['hora_final'];
  $data["materia"] = $row['materia']; 
  $data["local"] = $row['local']; 

  // Tercer ciclo o Bachillerato 
  if ($row["id_nivel"] == "1") $data["grado"] = $row["grado"]." sección ".$row["seccion"];
  else {
    // Materia Tecnica o academica
    if($row["id_grupo_tecnico"] != null){
      $data["grado"] = $row["grado"].", ".$row["especialidad"]." Grupo ".$row["id_grupo_tecnico"]." Sección ".$row["seccion"];
    }        
    else {
      $data["grado"] = $row["grado"]." Sección "." ".$row["seccion"].$row["id_grupo_academico"];
    }    
  }
  $response[]=$data;    
}

$jsondata["lista"] = array_values($response);
header("Content-type:application/json; charset = utf-8");
echo json_encode($jsondata);
exit(); 


function obtenerClases($fecha){
  $dia = array('Lunes','Martes','Miercoles','Jueves','Viernes');
  $numero_dia = date('N', strtotime($fecha));
  $DiaActual = $dia[$numero_dia-1];  
  $maestros_inasistencias = array();  
  if($numero_dia >=0 && $numero_dia <= count($dia)){
    // obtiene los mestros y su horario
    $clases=Database::getRows('select h.id_horario,p.nombre, p.apellido,g.nombre as grado, t.hora_inicial, t.hora_final, p.correo, p.id_personal from horarios h, tiempos t, personal p, grados g where  h.dia=? and h.id_tiempo = t.id_tiempo and t.hora_final < time(now()) and date(?) between date(h.inicio) and date(h.fin) and h.id_personal = p.id_personal and h.id_grado = g.id_grado ',array($DiaActual,$fecha));
    foreach($clases as $clase){
      $lista=Database::getRow('select count(*) as cantidad from asistencias where id_horario= ? and date(fecha_hora) = ?',array($clase['id_horario'],$fecha));
      if(intval($lista['cantidad']) == 0){
        $maestro= Database::getRow('SELECT ho.dia,pe.codigo,pe.nombre,pe.apellido,ho.id_grupo_tecnico,ho.id_grupo_academico, asig.nombre as materia, es.nombre as especialidad,sec.nombre as seccion,ga.nombre as grado,ti.hora_inicial,ti.hora_final,lo.nombre as local, ga.id_nivel 
        FROM  horarios ho, personal pe, asignaturas asig, especialidades es, secciones sec, grados ga, tiempos ti, locales lo 
        WHERE ho.id_horario = ? and ho.id_personal = pe.id_personal and ho.id_asignatura = asig.id_asignatura and ho.id_especialidad = es.id_especialidad and ho.id_seccion = sec.id_seccion and ho.id_grado = ga.id_grado and ho.id_tiempo = ti.id_tiempo and ho.id_local = lo.id_local',array($clase['id_horario'],));
        $maestros_inasistencias[] = $maestro;
      }
    }

  }
  return $maestros_inasistencias;
}
?> 