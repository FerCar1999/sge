<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//Nombre del fichero de fox pro con el que se
session_start();
require_once 'funciones.php';

$vfp = new Funciones();

//echo phpinfo();

if (isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'pagos':
            echo json_encode($vfp->obtenerTodosPagos($_SESSION["codigo"]));
            break;
        case 'obtenerTabla':
            echo json_encode($vfp->obtenerTablaPagos($_POST['codigo']));
            break;
        case 'pagosRealizados':
            echo json_encode($vfp->obtenerPagosRealizados($_SESSION["codigo"], $_POST['tipopago']));
            break;
    }
}
