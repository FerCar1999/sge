<?php    
// obtiene las variables del metodo post
$id = isset($_POST["id"]) && intval($_POST["id"]) >0 ? intval($_POST["id"]) : null;
$nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]): "";
//obtiene el token enviado por el navegador y lo compara con el de su session activa
  $token = isset($_POST["token"]) ? $_POST["token"] : "notfound";
  require_once($_SERVER['DOCUMENT_ROOT']."/libs/Token.php");
  session_start();
  if(!Token::check($token)){
      echo 'Acción sobre la información denegada.';
      exit();
  }

require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/validaciones.php");

  /*if (!validarNumeroEntero($id)) {
    echo "Registro inválido.";
    exit();
  }
  if (!validarNombre($nombre)) {
    echo "Nombre inválido.";
    exit();
  }*/

// require la clase dabase
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

if($id===null && !empty($nombre) && strlen($nombre)>2 && strlen($nombre) < 36){

    // consulta sql
 $sql ="insert into permisos(nombre) values(?)";
 $params = array(strip_tags($nombre));
    // ejecuta la consulta
 $last_id=Database::executeRow($sql, $params,"INSERT");
 if($last_id>0){
  echo 'Permiso agregado.';
  try{
    $aditionalDescription = " Action Details: nombre = {$nombre}";
    addToBitacora("Permiso Agregado",$aditionalDescription);
  }catch(Exception $e){}
}
else{
  echo 'Permiso existente.';
}

}
else if ($id >0 && !empty($nombre) && strlen($nombre)>2 && strlen($nombre) < 36){
  //sql
  $sql ="update  permisos set nombre=? where id_permiso=?";

  $params = array(strip_tags($nombre),strip_tags($id));
    // ejecuta la consulta
  if(Database::executeRow($sql, $params)){
    echo 'Permiso modificado.';
    try{
      $aditionalDescription = " Action Details: id = {$id}";
      addToBitacora("Permiso Modificado",$aditionalDescription);
    }catch(Exception $e){}

  }
  else{
    echo 'Permisos existente.';

  }
}
else{
  echo 'Ingrese un permiso válido.';
}

function addToBitacora($description,$aditionalDescription){
  try {

    require_once($_SERVER['DOCUMENT_ROOT']."/privado/App/Library/BitacoraLogger.php");
    
    session_start();
    BitacoraLogger::$currentUser = $_SESSION["id_personal"];
    BitacoraLogger::$function = 201;
    BitacoraLogger::$description = $description;
    BitacoraLogger::$aditionalDescription = $aditionalDescription;
    BitacoraLogger::setLogPersonal();    		
  } catch (Exception $e) {}
}
?>