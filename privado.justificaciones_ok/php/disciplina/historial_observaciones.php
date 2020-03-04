<?php 
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;	
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	session_start();
	//if ($_SESSION["permiso"] == "Administrador") {
	if (true) {
		$data=Database::getRows("
		select ob.id_observacion as id,p.nombre as nombre,p.apellido as apellido,ob.observacion as observacion,date(ob.fecha) as fecha, CONCAT(e.nombres , ' ', e.apellidos) as alumno from observaciones ob, personal p, estudiantes e where ob.id_personal = p.id_personal and e.id_estudiante = ob.id_estudiante and ob.id_estudiante=(select id_estudiante from estudiantes  where codigo =?)",array($id));
	}else{
		$data=Database::getRows("
		select ob.id_observacion as id,p.nombre as nombre,p.apellido as apellido,ob.observacion as observacion,date(ob.fecha) as fecha, CONCAT(e.nombres , ' ', e.apellidos) as alumno from observaciones ob, personal p, estudiantes e where ob.id_personal = p.id_personal and e.id_estudiante = ob.id_estudiante and ob.id_estudiante=(select id_estudiante from estudiantes  where codigo =?) and ob.id_personal=?",array($id,$_SESSION["id_personal"]));	
	}
	foreach($data as $row)
	{
		$data_ob = array();
		$data_ob['id']=$row["id"];
		$data_ob["alumno"] = $row["alumno"];
		$data_ob["nombre"] = $row["nombre"].' '.$row["apellido"];
		$data_ob["fecha"] = $row["fecha"];
		$data_ob["observacion"] = $row["observacion"];		
		$jsondataList[]=$data_ob;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	

 ?>