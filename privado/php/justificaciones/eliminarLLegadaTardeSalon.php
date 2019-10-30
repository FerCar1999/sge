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
	$sql = "delete from  impuntualidad  where id_impuntualidad=?";
	Database::executeRow($sql, array($id));			
	try{
		$aditionalDescription = " Action Details: id_impuntualidad = {$id}";
		addToBitacora($aditionalDescription);
	}catch(Exception $e){}
}

function addToBitacora($aditionalDescription){
	try {

		require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
		
		session_start();
    	BitacoraLogger::$currentUser = $_SESSION["id_personal"];
		BitacoraLogger::$function = 86;
		BitacoraLogger::$description = "Justifiacion Impuntualidad: Eliminada";
		BitacoraLogger::$aditionalDescription = $aditionalDescription;
		BitacoraLogger::setLogPersonal();    		
	} catch (Exception $e) {}	
}
?>