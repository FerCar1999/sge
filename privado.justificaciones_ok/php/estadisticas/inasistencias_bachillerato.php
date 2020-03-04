<?php 
// importa la base de datos
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

// consultas para obtener la informacion
$periodos=Database::getRows("select nombre,fecha_inicial,fecha_final from etapas where id_nivel = 2",array());

// almacena las columnas del chart y sus valores
$valores = array();
// set valores a los meses 0 por default
$meses= array(0,0,0,0);
$java_script_series = "";
// obtiene la cantidad de cada tipo
$contador =0;
foreach ($periodos as $row) {	
	$string_values = "";	
	$cantidades=Database::getRows("select count(*) as cantidad from inasistencias_totales it,estudiantes e, grados g where it.id_estudiante=e.id_estudiante and e.id_grado=g.id_grado and g.id_nivel = 2 and YEAR(it.fecha) = YEAR(CURDATE()) and it.fecha between ? and ?",array($row['fecha_inicial'],$row['fecha_final']));
	foreach ($cantidades as $cantidad) {	
		// añade la cantidad al mes
		$meses[$contador] = intval($cantidad['cantidad']);
		$contador++;
	}
	// genera un array javascript
	$string_values.= json_encode($meses).",";	
	$valores[] = $string_values;

	// reinicia los valores de los meses para el proximo tipo
	//$meses= array(0,0,0,0);
}
// genera el array de la serie completa
$java_script_series.= "{
    type: 'column',
    name:\"Inasistencias Totales\",
    data: ".$valores[0]."
},";

// genera la funcion en javascript
$html = "<script>
function load_chart5(){
    Highcharts.chart('chart5', {

        title: {
            text: 'INASISTENCIAS BACHILLERATO'
        },

        yAxis: {
            tickInterval: 10,
            breaks: [{
                from: 31,
                to: 110,
                breakSize: 5
            }]
        },
        
        xAxis: {
            categories: ['Primer periodo','Segundo periodo','Tercer periodo','Cuarto periodo']
        },
        yAxis: {
            title: {
                text: 'Cantidad inasistencias'
            }
        },
        
        series: [".$java_script_series."]
    });    
}</script>
";
echo $html;
?>