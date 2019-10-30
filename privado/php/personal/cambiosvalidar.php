<?php 

	$clave = isset($_POST["p"]) ? trim($_POST["p"]): "";	
	$id;

	session_start();

	if (isset($_SESSION['id_personal']))
		$id = $_SESSION['id_personal'];

	if ($clave == ""){
		echo "Contraseña vacía.";
		exit();
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	
	$data=Database::getRows("SELECT clave FROM personal WHERE id_personal=? AND estado=?",array($id,'Activo'));

	foreach($data as $row)
	{	
		$data_personal = array();
		//$data_personal['clave']=$row["id_personal"];
		if (password_verify($clave,$row["clave"])) {
			$data_personal["resp"] = true;
		}else{
			$data_personal["resp"] = false;
		}
		$jsondataList[]=$data_personal;
	}
	// envia la informacion mediante json
	$jsondata["lista"] = array_values($jsondataList);
	header("Content-type:application/json; charset = utf-8");
	echo json_encode($jsondata);
	exit();	

 ?>