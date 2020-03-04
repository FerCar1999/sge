<?php 

try{
// evalua los valores del POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
if($id>0){
	$sql = "delete from horarios where id_horario=? and estado = 'Propuesta'";

	// si se puede eliminar retorna true sino que la clase no puede ser modificada ya que ya ha sido utilizada
	Database::executeRow($sql, array($id));
	try{
		$aditionalDescription = " Action Details: id_horario = {$id}";
		addToBitacora($aditionalDescription);
	}catch(Exception $e){}

	echo "Exito";	
}
 }catch (Exception $error){
   		echo "La clase no puede eliminarse debido a que ha sido utilizada anteriormente.";
 }
 
 function addToBitacora($aditionalDescription){
	try {

		require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
		
		session_start();
    BitacoraLogger::$currentUser = $_SESSION["id_personal"];
		BitacoraLogger::$function = 90;
		BitacoraLogger::$description = "Clase eliminada del Horario";
		BitacoraLogger::$aditionalDescription = $aditionalDescription;
		BitacoraLogger::setLogPersonal();    		
	} catch (Exception $e) {}	
}
 
?>