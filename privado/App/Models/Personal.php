<?php
require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Models/Model.php");

class Personal extends Model
{
  // Instance Properties
  
  public $id;
  public $code;
  public $name;
  public $lastName;
  public $email;
  public $avatarURL;

  /**   
   * Return Personal from given id
   */
  public static function showFromID($id) {
    $data = Database::getRow('SELECT id_personal,codigo,nombre,apellido,correo,foto FROM personal WHERE id_personal = ?',array(
      $id  
    ));
    $personal = new Personal();
    $personal->id = $data["id_personal"];
    $personal->codigo = $data["codigo"];
    $persnal->name = $data["nombre"];
    $personal->lastName = $data["apellido"];
    $personal->email = $data["correo"];
    $persnal->$avatarURL = $data["foto"];
    return $persnal;
  }

  /**
   * Return Personal from given code
   */
  public static function showFromCode($code){
    $data = Database::getRow('SELECT id_personal FROM personal WHERE codigo =?',array(
      $code 
    ));
    return Personal::showFromID($data["id_personal"]);
  }
  
  public static function all(){
    return Database::getRows("select id_personal,codigo,nombre,apellido,correo,foto from personal where estado = 'Activo'",array());
  }
  
  /**
   * Return personals Atendance from given date
   */
  public static function fetchAllAttendanceStatusFromDate($date) {    
    $days = array('Lunes','Martes','Miercoles','Jueves','Viernes');
    $day_number = date('N', strtotime($date));
    $currentDay = $days[$day_number-1];      
    $personal_attendance = array();  

    $current = strtotime(date("Y-m-d"));
    $datediff = strtotime($date) - $current;
    $difference = floor($datediff/(60*60*24));   

    if($day_number >=0 && $day_number <= count($days)){
      
      $query = 'select h.id_horario,p.nombre, p.apellido,g.nombre as grado, t.hora_inicial, t.hora_final, p.correo, p.id_personal from horarios h, tiempos t, personal p, grados g where  h.dia=? and h.id_tiempo = t.id_tiempo  and date(?) between date(h.inicio) and date(h.fin) and h.id_personal = p.id_personal and h.id_grado = g.id_grado ';
      if ($difference == 0){
        $query = 'select h.id_horario,p.nombre, p.apellido,g.nombre as grado, t.hora_inicial, t.hora_final, p.correo, p.id_personal from horarios h, tiempos t, personal p, grados g where  h.dia=? and h.id_tiempo = t.id_tiempo and t.hora_final < time(now()) and date(?) between date(h.inicio) and date(h.fin) and h.id_personal = p.id_personal and h.id_grado = g.id_grado ';
      }
      // Get teachers and his attendances
      $subjects=Database::getRows($query,array($currentDay,$date));      
      
      foreach($subjects as $subject){
        $attendance=Database::getRow('select count(*) as cantidad from asistencias where id_horario= ? and date(fecha_hora) = ?',array($subject['id_horario'],$date));        
        if(intval($attendance['cantidad']) == 0){
          $personal= Database::getRow('SELECT ho.dia,pe.codigo,pe.nombre,pe.apellido,ho.id_grupo_tecnico,ho.id_grupo_academico, asig.nombre as materia, es.nombre as especialidad,sec.nombre as seccion,ga.nombre as grado,ti.hora_inicial,ti.hora_final,lo.nombre as local, ga.id_nivel 
          FROM  horarios ho, personal pe, asignaturas asig, especialidades es, secciones sec, grados ga, tiempos ti, locales lo 
          WHERE ho.id_horario = ? and ho.id_personal = pe.id_personal and ho.id_asignatura = asig.id_asignatura and ho.id_especialidad = es.id_especialidad and ho.id_seccion = sec.id_seccion and ho.id_grado = ga.id_grado and ho.id_tiempo = ti.id_tiempo and ho.id_local = lo.id_local',array($subject['id_horario']));
          $personal_attendance[] = $personal;          
        }
      }  
    }
    return $personal_attendance;
  }

  public function fetchAttendanceStatusFromDate($date){    
    $days = array('Lunes','Martes','Miercoles','Jueves','Viernes');
    $day_number = date('N', strtotime($date));
    $currentDay = $days[$day_number-1];      
    $personal_attendance = array();  

    if($day_number >=0 && $day_number <= count($days)){
    
      // Get teachers and his attendancese
      $subjects=Database::getRows('select h.id_horario,p.nombre, p.apellido,g.nombre as grado, t.hora_inicial, t.hora_final, p.correo, p.id_personal from horarios h, tiempos t, personal p, grados g where  h.dia=? and h.id_tiempo = t.id_tiempo and t.hora_final < time(now()) and date(?) between date(h.inicio) and date(h.fin) and  h.id_personal = p.id_personal and h.id_personal = ? and h.id_grado = g.id_grado ',array($currentDay,$date, $this->id));      
      foreach($subjects as $subject){
        $attendance=Database::getRow('select count(*) as cantidad from asistencias where id_horario= ? and date(fecha_hora) = ?',array($subject['id_horario'],$date));        
        if(intval($attendance['cantidad']) == 0){
          $personal= Database::getRow('SELECT ho.dia,pe.codigo,pe.nombre,pe.apellido,ho.id_grupo_tecnico,ho.id_grupo_academico, asig.nombre as materia, es.nombre as especialidad,sec.nombre as seccion,ga.nombre as grado,ti.hora_inicial,ti.hora_final,lo.nombre as local, ga.id_nivel 
          FROM  horarios ho, personal pe, asignaturas asig, especialidades es, secciones sec, grados ga, tiempos ti, locales lo 
          WHERE ho.id_horario = ? and ho.id_personal = pe.id_personal and ho.id_asignatura = asig.id_asignatura and ho.id_especialidad = es.id_especialidad and ho.id_seccion = sec.id_seccion and ho.id_grado = ga.id_grado and ho.id_tiempo = ti.id_tiempo and ho.id_local = lo.id_local',array($subject['id_horario']));
          $personal_attendance[] = $personal;          
        }
      }  
    }
    return $personal_attendance;
  }

  // Transferencia de carga academica

  static function transferAcademicLoad($idOldPersonal, $idNewPersonal) {
    Database::executeRow("update horarios set id_personal = ? where id_personal = ?",array(
      $idNewPersonal,
      $idOldPersonal
    ));
    return Database::executeRow("update estudiantes set id_personal = ? where id_personal = ?",array(
      $idNewPersonal,
      $idOldPersonal
    ));
  }

}
?>
