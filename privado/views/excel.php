<?php
session_start();
if(isset($_SESSION['id_personal'])){
    require_once('../../libs/database.php');
    require_once("estudiantes_excel.php");
}else{
header("location: /login");
}

?>
