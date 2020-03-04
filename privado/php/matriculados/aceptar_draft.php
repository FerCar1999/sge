<?php 
	//evalua los valores del POST	
	$id_especialidad = isset($_POST['id_especialidad']) ? intval($_POST['id_especialidad']) : 0;
	$id_grado = isset($_POST['id_grado']) ? intval($_POST['id_grado']) : 0;
	$id_grupo = isset($_POST['id_grupo']) ? intval($_POST['id_grupo']) : 0;
	$id_seccion = isset($_POST['id_seccion']) ? intval($_POST['id_seccion']) : 0;	
	$estado = isset($_POST["estado"]) ? $_POST["estado"] : "Activo";

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");	
	// si es un id valido 
	if($id_especialidad > 0 ){
		$sql ="update estudiantes set estado='Activo' where id_especialidad=? and id_grupo_tecnico=?  and id_grado=?  ";
 		$params = array($id_especialidad,$id_grupo,$id_grado);
		if(Database::executeRow($sql, $params)){		    
		    echo 'true';
		}	
	}
?>