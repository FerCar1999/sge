<?php
require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Controllers/Controller.php");
require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Models/Personal.php");

class PersonalController extends Controller
{
  /**
   * Fetch all Personals
   */
  public function index($request){
    $response = array();
    foreach(Personal::all() as $personal){
      $data['id']=$personal["id_personal"];
      $data['code']=$personal["codigo"];
      $data['name']=$personal["nombre"];
      $data['lastName']=$personal["apellido"];
      $data['email']=$personal["correo"];
      $data['avatarURL']=$personal["foto"];
      $response[]=$data;    
    }
    $JSON["data"] = array_values($response);
    parent::responseJSON($JSON,200);
  }
  /**
   * Store new Personal
   */
  public function store($request){

  }
  /**
   * Edit Personal
   */
  public function edit($request){

  }
  /**
   * Update Personal
   */
  public function update($request){

  }
  /**
   * Delete Personal
   */
  public function destroy($request){

  }  

  /**
   * Feature: "TRANSFERENCIA CARGA ACADEMICA"   
   */

  public function transferAcademicLoad($request) {
    $response = array();
    Personal::transferAcademicLoad($request["idOldPersonal"], $request["idNewPersonal"]);    
    return parent::responseJSON(array('status' => "success" ), 200);
  } 


  /**
   * Feature: "USO DEL SISTEMA"
   * Get all attendances status from every teacher
   */
  public function AllAttendanceStatusFromDate($request){        
    $response = array();    
    $attendance = Personal::fetchAllAttendanceStatusFromDate($request["date"]);    
    foreach ($attendance as $row) {

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
      if ($row["codigo"] != null) $response[]=$data;    
    }
    $JSON["data"] = array_values($response);
    parent::responseJSON($JSON,200);    
  }
  /**
   * Feature: "USO DEL SISTEMA"
   * Get all attendances status from single teacher
   */
  public function attendanceStatusFromDate($request){
    $response = array();      
    $personal = new Personal();
    $personal->id = $request["id"];    
    $attendance = $personal->fetchAttendanceStatusFromDate($request["date"]);

    foreach ($attendance as $row) {
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
    $JSON["data"] = array_values($response);
    parent::responseJSON($JSON,200);
  }
}
?>