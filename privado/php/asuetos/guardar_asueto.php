<?php    
// obtiene las variables del metodo post
$id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
$nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]): "";
$inicio = isset($_POST["inicio"]) ? trim($_POST["inicio"]): "";
$fin = isset($_POST["fin"]) ? trim($_POST["fin"]): "";
//obtiene el token enviado por el navegador y lo compara con el de su session activa
  $token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
  require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
  session_start();
  if(!Token::check($token)){
      echo 'Acción sobre la información denegado';
      exit();
  }
require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");
  
  if (!validarNombre($nombre)) {
    echo "Nombre inválido";
    exit();
  }

// require la clase dabase
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

if($id===null && !empty($nombre) && strlen($nombre)>2 && strlen($nombre) < 36){

    // consulta sql
 $sql ="insert into asuetos(nombre,fecha_inicio,fecha_fin) values(?,?,?)";
 $params = array(strip_tags($nombre),strip_tags($inicio),strip_tags($fin));
    // ejecuta la consulta
 $last_id=Database::executeRow($sql, $params,"INSERT");
 if($last_id>0){
   // Bitacora
  try{
    $aditionalDescription = " Action Details: nombre = {$nombre}";
    addToBitacora("Se ha agregado un nuevo Asueto",$aditionalDescription);
  }catch(Exception $e){}
  echo 'Asueto agregado';  
}
else{
  echo 'Asueto existente';
}

}
else if ($id >0 && !empty($nombre) && strlen($nombre)>2 && strlen($nombre) < 36){
  //sql
  $sql ="update  asuetos set nombre=?, fecha_inicio=?, fecha_fin =?  where id_asueto=?";

  $params = array(strip_tags($nombre),strip_tags($inicio),strip_tags($fin),strip_tags($id));
    // ejecuta la consulta
  if(Database::executeRow($sql, $params)){
    try{
      $aditionalDescription = " Action Details: id = {$id}";
      addToBitacora("Se ha modificado un Asueto",$aditionalDescription);
    }catch(Exception $e){}

    echo 'Asueto modificado.';    
  }
  else{
    echo 'Asueto existente.';

  }
}
else{
  echo 'Ingrese un asueto válido.';
}

function addToBitacora($description,$aditionalDescription){
  try {

    require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
    
    session_start();
    BitacoraLogger::$currentUser = $_SESSION["id_personal"];
    BitacoraLogger::$function = 81;
    BitacoraLogger::$description = $description;
    BitacoraLogger::$aditionalDescription = $aditionalDescription;
    BitacoraLogger::setLogPersonal();    		
  } catch (Exception $e) {}
}
?>