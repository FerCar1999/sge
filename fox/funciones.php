<?php
//10.0.1.198
class Funciones
{
    //funcion para recorrer cualquier DBF
    public function recorrerDatosDBF($archivo)
    {
        //arreglo para guardar los datos
        $arrData = array();
        //conectar con el archivo
      	//$conex       = dbase_open('//10.0.1.198/sisconta/Sistemas/Alumnos/Datos/2020/' . $archivo, 0);
        $conex       = dbase_open('fox/'.$archivo, 0);
        //verificar si se conecto
        if ($conex) {
            //obteniendo el total de datos
            $total_registros = dbase_numrecords($conex);
            //recorriendo los registros
            for ($i = 1; $i <= $total_registros; $i++) {
                //guardando los registros en el array 
                $arrData[] = dbase_get_record($conex, $i);
            }
        }else{
	return 'Nada';
	}
        return $arrData;
    }
    public function obtenerTablaPagos($codigo)
    {
        $arreglo = array();
        $resp = $this->recorrerDatosDBF('tablapagos.DBF');
        for ($i = 0; $i < count($resp); $i++) {
            //$arreglo[] = $resp[$i];
            if ($resp[$i][0] == $codigo) {
                $arreglo[] = $resp[$i][1];
            }
        }
        return $arreglo;
    }

    public function obtenerTodosPagos($codigo)
    {
        $arreglo = array();
        $resp = $this->recorrerDatosDBF('ALUMTALON.DBF');
        for ($i = 0; $i < count($resp); $i++) {
            if ($resp[$i][0] == $codigo) {
                $arreglo[] = $resp[$i];
            }
        }
        return $arreglo;
    }
    public function obtenerPagosRealizados($codigo, $tipopago)
    {
        $arreglo = array();
        $resp = $this->recorrerDatosDBF('alumcheq.DBF');
        for ($i = 0; $i < count($resp); $i++) {
            if ($resp[$i][0] == $codigo && $resp[$i][1] == $tipopago) {
                $arreglo[] = $resp[$i];
            }
        }
        return $arreglo;
    }
}
