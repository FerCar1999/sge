<?php 
$id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : 0;
$fecha = isset($_POST["fecha"]) ? trim($_POST["fecha"]): "";

require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

$jsondataList = array();
if($id > 0){
  $maestros = Database::getRows('select g.nombre as grado, ma.nombre as materia,t.hora_inicial,t.hora_final,g.id_grado,s.nombre as seccion,e.nombre  as especialidad,h.id_grupo_academico, h.id_grupo_tecnico,g.id_nivel from horarios h, maestros_lista_inasistencias m , tiempos t, grados g, especialidades e, asignaturas ma,secciones s
where h.id_personal= ? and m.id_horario = h.id_horario and m.fecha = date(?)  and h.id_tiempo = t.id_tiempo and h.id_grado = g.id_grado and h.id_especialidad = e.id_especialidad and h.id_asignatura = ma.id_asignatura and h.id_seccion = s.id_seccion group by t.hora_inicial',array($id,$fecha));
  
  foreach ($maestros as $row) {


    $grupoa = $row['id_grupo_academico'];
    $grupot = $row['id_grupo_tecnico'];    
    $nivel = $row['id_nivel']; 
    $grado = $row["grado"];
    $especialidad = $row["especialidad"];

    if(intval($nivel) == 2){
      if($grupoa != null){
        $grado.= " ".$row["seccion"].$grupoa;  
      }else {
        $grado.=" ".$especialidad." Grupo ".$grupot;  
      }
      
    }
    // tercer ciclo
    else{
      $grado.=" ".$seccion;
    }

    $data['grado']=$grado;
    $data["materia"] = $row["materia"];
    $data["hora"] = $row['hora_inicial']." - ".$row['hora_final'];     
    $jsondataList[]=$data;    
  }        

}
$jsondata["lista"] = array_values($jsondataList);
header("Content-type:application/json; charset = utf-8");
echo json_encode($jsondata);
exit(); 
?> 