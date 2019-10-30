<?php 
// evalua los valores del POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
//obtiene el token enviado por el navegador y lo compara con el de su session activa
$token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
	session_start();
	if(!Token::check($token)){
	    echo 'Acción sobre la información denegada.';
	    exit();
	}
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
if($id>0){
	$sql = "delete from inasistencias_clases  where id_inasistencia=?";
	Database::executeRow($sql, array($id));			
	echo "true";
}

?>