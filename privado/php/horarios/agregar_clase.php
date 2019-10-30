<?php 
date_default_timezone_set('America/Los_Angeles');
// evalua los valores del POST	
$id_especialidad = isset($_POST['id_especialidad']) ? intval($_POST['id_especialidad']) : 0;
$id_grado = isset($_POST['id_grado']) ? intval($_POST['id_grado']) : 0;
$id_grupo = isset($_POST['id_grupo']) ? intval($_POST['id_grupo']) :0;
$id_seccion = isset($_POST['id_seccion']) ? intval($_POST['id_seccion']) : 0;
$id_tiempo = isset($_POST['id_tiempo']) ? intval($_POST['id_tiempo']) : 0;
$id_asignatura = isset($_POST['id_asignatura']) ? intval($_POST['id_asignatura']) : 0;
$id_local = isset($_POST['id_local']) ? intval($_POST['id_local']) : 0;
$id_especialidad = isset($_POST['id_especialidad']) ? intval($_POST['id_especialidad']) : 11;
$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : "";
$dia = isset($_POST["dia"]) ? $_POST["dia"] : "";
$inicio = isset($_POST["inicio"]) ? $_POST["inicio"] : date('Y-01-01');
$fin = isset($_POST["fin"]) ? $_POST["fin"] : date('Y-12-30');
$modulo = isset($_POST["modulo"]) ? $_POST["modulo"] : "NO";
$estado = isset($_POST["estado"]) ? $_POST["estado"] : "Propuesta";
$grupo_tecnico_completo = isset($_POST["grupo_tecnico_completo"]) ? $_POST["grupo_tecnico_completo"] : "NO";

$id_especialidad = ($id_especialidad == 0) ? 11:$id_especialidad;

if($grupo_tecnico_completo==="SI") {
	$grupo_tecnico_completo = "Grupo";
}else {
	$grupo_tecnico_completo = "Seccion";
}
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
session_start();
if($modulo==="NO"){
// validaciones 
$data = Database::getRow("select count(*) as cantidad from horarios where id_personal = ? and id_tiempo = ? and dia=?", array($_SESSION["id_personal"],$id_tiempo,$dia));
if (intval($data['cantidad']) > 0){

	echo "Se ha detectado que actualmente poseee una clase a esa hora.";
	exit();
}

// materia academica
$data = Database::getRow("select count(*) as cantidad from horarios where id_tiempo =? and id_grado = ? and id_grupo_academico = ? and id_seccion = ?  and dia = ? ", array($id_tiempo,$id_grado, $id_grupo, $id_seccion, $dia));
if (intval($data['cantidad']) > 0){
	echo "No se puede agregar esta clase académica debido a que ya hay otra en ese momento.";
	exit();
}
// materica tecnica
$data = Database::getRow("select count(*) as cantidad from horarios where id_tiempo =? and id_grado = ? and id_grupo_tecnico = ? and id_seccion = ? and id_especialidad = ? and dia = ? ", array($id_tiempo,$id_grado, $id_grupo, $id_seccion,$id_especialidad ,$dia));
if (intval($data['cantidad']) > 0){

	echo "No se puede agregar esta clase técnica debido a que ya hay otra en ese momento.";
	exit();
}

// si el lugar esta disponible

$data2 = Database::getRow("select count(*) as cantidad from horarios where id_tiempo = ? and id_local = ? and dia=?", array($id_tiempo,$id_local,$dia));
if (intval($data2['cantidad']) > 0){
	echo "No se puede agregar esta clase debido a que el local esta ocupado por otra clase.";
	exit();
}
}
// si es un id valido
$sql="";	
$params = array();
if($tipo=="Academico"){
	$sql ="insert into horarios(id_local,id_grupo_academico,id_seccion,id_grado,id_asignatura,id_tiempo,dia,id_especialidad,estado,id_personal,inicio,fin)values(?,?,?,?,?,?,?,?,?,?,CONCAT(YEAR(CURDATE()),'-01-01'),CONCAT(YEAR(CURDATE()),'-12-12'))";

	if($modulo==="SI"){
		$sql ="insert into horarios(id_local,id_grupo_academico,id_seccion,id_grado,id_asignatura,id_tiempo,dia,id_especialidad,estado,inicio,fin,id_personal)values(?,?,?,?,?,?,?,?,?,?,?,?)";	
		$params = array($id_local,$id_grupo,$id_seccion,$id_grado,$id_asignatura,$id_tiempo,$dia,$id_especialidad,$estado,$inicio,$fin,$_SESSION["id_personal"]);

	}else $params = array($id_local,$id_grupo,$id_seccion,$id_grado,$id_asignatura,$id_tiempo,$dia,$id_especialidad,$estado,$_SESSION["id_personal"]);
}else{
	$sql ="insert into horarios(id_local,id_grupo_tecnico,id_seccion,id_grado,id_asignatura,id_tiempo,dia,id_especialidad,estado,id_personal,inicio,fin,tipo)values(?,?,?,?,?,?,?,?,?,?,CONCAT(YEAR(CURDATE()),'-01-01'),CONCAT(YEAR(CURDATE()),'-12-12'),?)";
	// verifica que sea tercer ciclo 
	if($id_grado<4){
		$sql ="insert into horarios(id_local,id_grupo_academico,id_seccion,id_grado,id_asignatura,id_tiempo,dia,id_especialidad,estado,id_personal,inicio,fin,tipo)values(?,?,?,?,?,?,?,?,?,?,CONCAT(YEAR(CURDATE()),'-01-01'),CONCAT(YEAR(CURDATE()),'-12-12'),?)";
	}

	if($modulo==="SI"){
		$sql ="insert into horarios(id_local,id_grupo_tecnico,id_seccion,id_grado,id_asignatura,id_tiempo,dia,id_especialidad,estado,inicio,fin,id_personal,tipo)values(?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$params = array($id_local,$id_grupo,$id_seccion,$id_grado,$id_asignatura,$id_tiempo,$dia,$id_especialidad,$estado,$inicio,$fin,$_SESSION["id_personal"],$grupo_tecnico_completo);
	}else $params = array($id_local,$id_grupo,$id_seccion,$id_grado,$id_asignatura,$id_tiempo,$dia,$id_especialidad,$estado,$_SESSION["id_personal"],$grupo_tecnico_completo);
}
if(Database::executeRow($sql, $params)){		    
	echo 'true';    
	try{
		$aditionalDescription = " Action Details: id_grupo = {$id_grupo}, id_seccion: {$id_seccion}, id_grado: {$id_grado}, id_asignatura: {$id_asignatura}, id_dia: {$id_asignatura}, id_especialidad: {$id_especialidad}";
		addToBitacora($aditionalDescription);
	}catch(Exception $e){}

}else{
	echo "error";
}

function addToBitacora($aditionalDescription){
	try {

		require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
		
		session_start();
    BitacoraLogger::$currentUser = $_SESSION["id_personal"];
		BitacoraLogger::$function = 90;
		BitacoraLogger::$description = "Clase agregada a Horario";
		BitacoraLogger::$aditionalDescription = $aditionalDescription;
		BitacoraLogger::setLogPersonal();    		
	} catch (Exception $e) {}	
}

?>