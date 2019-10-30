<?php 
	// evalua los valores del POST	
$id = isset($_POST['id']) ? intval($_POST['id']) : 8;
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

$tipo=Database::getRow("select tipo from tipos_asignaturas where id_tipo_asignatura=?",array($id));


if($tipo['tipo']=="Academica"){
	$data=Database::getRows("select id_grupo_academico,nombre from grupos_academicos where estado ='Activo'",array());	
	foreach($data as $row)
	{
		$data_grupo = array();
		$data_grupo['id']=$row["id_grupo_academico"];
		$data_grupo["nombre"] = $row["nombre"];
		$data_grupo["tipo"] ="Academico";
		
		$jsondataList[]=$data_grupo;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	
}
else if($tipo['tipo']=="Tecnica"){
	$data=Database::getRows("select id_grupo_tecnico,nombre from grupos_tecnicos where estado ='Activo'",array());
	
	foreach($data as $row)
	{
		$data_grupo = array();
		$data_grupo['id']=$row["id_grupo_tecnico"];
		$data_grupo["nombre"] = $row["nombre"];
		$data_grupo["tipo"] = "Tecnico";
		
		$jsondataList[]=$data_grupo;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	
}


?>