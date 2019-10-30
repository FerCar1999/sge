<?php

include "libs/database.php";

$row = 1;
if (($handle = fopen("personal.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {    
    $codigo = $data[1];
    $email = $data[4];
    $password = $data[5];

    echo "Updating Email And Password for: {$codigo}\n";
        
    echo $email . "\n";
    echo $password . "\n";

    Database::executeRow("UPDATE personal set correo = ?, clave = ? WHERE codigo = ?",array(
      $email, 
      $password, 
      $codigo
    ));

    $row++;
  }
  fclose($handle);
}

?>