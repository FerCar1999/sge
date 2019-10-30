<?php 

try{
// evalua los valores del POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
if($id>0){
	$sql = "delete from horarios where id_horario=? and estado = 'Propuesta'";

	// si se puede eliminar retorna true sino que la clase no puede ser modificada ya que ya ha sido utilizada
	Database::executeRow($sql, array($id));
	echo "Exito";	
}
 }catch (Exception $error){
   		echo "La clase no puede eliminarse debido a que ha sido utilizada anteriormente.";
 } 
?>