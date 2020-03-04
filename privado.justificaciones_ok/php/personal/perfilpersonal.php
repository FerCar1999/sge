<?php 
	
	session_start();

	if (isset($_SESSION['id_personal']))
		$id = $_SESSION['id_personal'];

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

	$data=Database::getRows("SELECT id_personal,nombre,apellido,foto,correo FROM personal WHERE id_personal=? AND estado=?",array($id,'Activo'));	
	foreach($data as $row)
	{
		$data_personal = array();
		$data_personal['id']=$row["id_personal"];
		$data_personal["nombre"] = $row["nombre"];
		$data_personal["apellido"] = $row["apellido"];
		$data_personal["foto"] = $row["foto"];
		$data_personal["correo"] = $row["correo"];
		$jsondataList[]=$data_personal;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	

 ?>