<?php 
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;	
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	$data=Database::getRows("select dc.id_disciplina as id,p.nombre as nombre,p.apellido as apellido,c.nombre as codigo,tc.nombre as tipo,date(dc.fecha_hora) as fecha,dc.observacion from disciplina dc, personal p, codigos c, tipos_codigos tc where dc.id_personal = p.id_personal and dc.id_codigo=c.id_codigo and c.id_tipo_codigo=tc.id_tipo_codigo and dc.id_estudiante=(select id_estudiante from estudiantes  where codigo =?)",array($id));	
	foreach($data as $row)
	{
		$data_codigo = array();
		$data_codigo['id']=$row["id"];
		$data_codigo["nombre"] = $row["nombre"].' '.$row["apellido"];
		$data_codigo["codigo"] = $row["codigo"];
		$data_codigo["tipo"] = $row["tipo"];
		$data_codigo["fecha"] = $row["fecha"];
		$data_codigo["observacion"] = $row["observacion"];		
		$jsondataList[]=$data_codigo;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	

 ?>