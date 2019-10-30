<?php 
date_default_timezone_set('UTC');
// evalua los valores del POST
$id_especialidad = isset($_POST['id_especialidad']) ? intval($_POST['id_especialidad']) : 0;
$id_grado = isset($_POST['id_grado']) ? intval($_POST['id_grado']) : 0;
$id_grupo = isset($_POST['id_grupo']) ? intval($_POST['id_grupo']) : 0;
$id_seccion = isset($_POST['id_secccion']) ? intval($_POST['id_secccion']) : 0;
$id_nivel = isset($_POST['id_nivel']) ? intval($_POST['id_nivel']) : 0;
$fecha = isset($_POST["fecha"]) ? $_POST["fecha"] : "notfound";
$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";

require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");

session_start();
if(!Token::check($token)){
  echo 'Acción sobre la información denegada.';
  exit();
}
// bachillerato
if($id_nivel==2){
  $data=Database::getRows("select id_estudiante from estudiantes where id_grado = ? and id_especialidad =? and id_grupo_tecnico = ? and estado ='Activo'",array($id_grado,$id_especialidad,$id_grupo));
  foreach ($data as $row) {
    $sql = "update inasistencias_clases set estado='Justificada' where date(fecha_hora) = ? and id_estudiante=?";
      Database::executeRow($sql, array($fecha,$row["id_estudiante"]));

      // jutifica inasistencia total
      Database::executeRow("UPDATE inasistencias_totales SET estado = 'Justificada' WHERE id_estudiante = ? AND date(fecha) = ?", array($row["id_estudiante"],$fecha));
  }

}
// tercer ciclo
else if($id_nivel==1){

$data=Database::getRows("select id_estudiante from estudiantes where id_grado = ? and id_seccion = ? and estado ='Activo'",array($id_grado,$id_seccion));
  foreach ($data as $row) {
    $sql = "update inasistencias_clases set estado='Justificada' where date(fecha_hora) = ? and id_estudiante=?";
      Database::executeRow($sql, array($fecha,$row["id_estudiante"]));             

      // jutifica inasistencia total
      Database::executeRow("UPDATE inasistencias_totales SET estado = 'Justificada' WHERE id_estudiante = ? AND date(fecha) = ?", array($row["id_estudiante"],$fecha));
  }
}



// Bitacora
try{
  $aditionalDescription = " Action Details: id_grado = {$id_grado}, id_grupo: {$id_grupo}, id_seccion: {$id_seccion}, id_especialidad: {$id_especialidad}";
  addToBitacora($aditionalDescription);
}catch(Exception $e){}

function addToBitacora($aditionalDescription){
	try {

    require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
    session_start();
    BitacoraLogger::$currentUser = $_SESSION["id_personal"];
		BitacoraLogger::$function = 86; // id for Login
    BitacoraLogger::$description = "Inasistencia Grupal Justificada";
    BitacoraLogger::$aditionalDescription = $aditionalDescription;
    BitacoraLogger::setLogPersonal();    		
	} catch (Exception $e) {}	
}
?>