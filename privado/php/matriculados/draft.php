<?php 
	// evalua los valores del POST
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$id_especialidad = isset($_POST['id_especialidad']) ? intval($_POST['id_especialidad']) : 0;
	$id_grado = isset($_POST['id_grado']) ? intval($_POST['id_grado']) : 0;
	$id_grupo = isset($_POST['id_grupo']) ? intval($_POST['id_grupo']) : 0;
	$id_seccion = isset($_POST['id_seccion']) ? intval($_POST['id_seccion']) : 0;
	$id_profesor = isset($_POST['id_profesor']) ? intval($_POST['id_profesor']) : 1;
	$estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	// si es un id valido
	if($id > 0 ){
		$sql ="update estudiantes set id_especialidad=?,id_grupo_tecnico=?,id_grado=?,id_seccion=?,id_personal=?,estado='draft' where id_estudiante=?";
 		$params = array($id_especialidad,$id_grupo,$id_grado,$id_seccion,$id_profesor,$id);
 
		if(Database::executeRow($sql, $params)){		    
		    echo 'true';    
		}	
	}

	
 ?>