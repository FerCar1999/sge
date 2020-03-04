<?php
require_once("../../../libs/database.php");
require_once("../Models/nivel.class.php");
if(isset($_POST['accion'])){
    $object = new ComboReporte;
    switch ($_POST['accion']) {
        case 'nivel':
            $finalizado = $object->getNiveles();
            echo json_encode($finalizado);
            break;
        case 'grado':
            $finalizado = $object->getGrado($_POST['nivel']);
            echo json_encode($finalizado);
            break;
        case 'gradoEsp':
            $finalizado = $object->getGradoEsp();
            echo json_encode($finalizado);
            break;
        case 'seccion':
            $finalizado = $object->getSecciones($_POST['grado']);
            echo json_encode($finalizado);
            break;
        case 'especialidad':
            $finalizado = $object->getEspecilidades($_POST['grado']);
            echo json_encode($finalizado);
            break;
        case 'seccionEsp':
            $finalizado = $object->getSeccionEsp($_POST['esp'],$_POST['grTec']);
            echo json_encode($finalizado);
            break;
        case 'grTec':
            $finalizado = $object->getGrTec($_POST['esp']);
            echo json_encode($finalizado);
            break;
        case 'grAc':
            $finalizado = $object->getGrAc($_POST['esp']);
            echo json_encode($finalizado);
        default:
            break;
    }
}
?>
