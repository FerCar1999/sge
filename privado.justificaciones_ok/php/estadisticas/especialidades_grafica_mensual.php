<?php 
// importa la base de datos
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

// consultas para obtener la informacion
$tipos=Database::getRows("select id_especialidad,nombre from especialidades",array());

// almacena las columnas del chart y sus valores
$names = array();$valores = array();

// set valores a los meses 0 por default
$meses= array(0,0,0,0,0,0,0,0,0,0,0,0);

// obtiene la cantidad de cada tipo
foreach ($tipos as $row) {	
	$string_values = "";
	
	$cantidades=Database::getRows("select count(d.id_disciplina) as cantidad,MONTH(d.fecha_hora) as mes from disciplina d,codigos c,estudiantes e where  YEAR(d.fecha_hora) = YEAR(CURDATE()) and  d.id_codigo = c.id_codigo and d.id_estudiante =e.id_estudiante and e.id_especialidad = ? GROUP BY  MONTH(d.fecha_hora) ",array($row['id_especialidad']));
	foreach ($cantidades as $cantidad) {	
		// añade la cantidad al mes
		$meses[intval($cantidad['mes'])-1] = intval($cantidad['cantidad']);
		
	}
	// genera un array javascript
	$string_values.= json_encode($meses).",";
	$names[] = $row['nombre'];
	$valores[] = $string_values;
	// reinicia los valores de los meses para el proximo tipo
	$meses= array(0,0,0,0,0,0,0,0,0,0,0,0);
}
// genera el array de la serie completa
$java_script_series = "";
for ($i=0; $i <count($names) ; $i++) { 
	$java_script_series.= "{
            type: 'column',
            name:\"".$names[$i]."\",
            data: ".$valores[$i]."
        },";
}

// genera la funcion en javascript
$html = "<script>
function load_chart4(){
    Highcharts.chart('chart4', {

        title: {
            text: 'CODIGOS ASIGNADOS POR ESPECIALIDADES'
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
            categories: ['Ene', 'Feb', 'Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic']
        },
        yAxis: {
            title: {
                text: 'Cantidad códigos'
            }
        },
        
        series: [".$java_script_series."]
    });    
}</script>
";
echo $html;
?>