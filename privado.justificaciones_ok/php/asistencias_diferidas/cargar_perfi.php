<?php 	
	$id = isset($_POST['id']) ? intval($_POST['id']) : 8;	
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	
	$data=Database::getRows("select id_horario,hora_limite,siempre from asistencias_diferidas where id_personal = ?",array($id));	
	$jsondataList = array();
	foreach($data as $row)
	{
		$data_perfil = array();
		$data_perfil['id']=$row["id_horario"];
		$data_perfil['hora']=$row["hora_limite"];
		$data_perfil['siempre']=$row["siempre"];	
		$jsondataList[]=$data_perfil;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	
 ?>