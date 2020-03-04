<?php 
$id = isset($_POST['id']) ? intval($_POST['id']) : 8;
require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");

$data=Database::getRow("select cantidad_secciones from niveles where id_nivel=(select id_nivel from grados where id_grado=?)",array($id));

$secciones = Database::getRows("select id_seccion,nombre from secciones limit ".intval($data['cantidad_secciones']), array());


$html="";
$columnas=12/intval($data['cantidad_secciones']);
foreach ($secciones as $row) {
	$html.='
	<li class="tab col s'.$columnas.'"><a onclick="selecionar_seccion('.$row['id_seccion'].',\''.$row['id_seccion'].$row['nombre'].'\');" class="active" href="#'.$row['id_seccion'].'">'.$row['nombre'].'</a></li>
	';
}

$html.='</ul><br>';
foreach ($secciones as $row) {
	$html.='
	<div id="'.$row['id_seccion'].'">
		<!-- Tabla de actividades-->
		<table class="striped">
			<thead>
				<tr>									
					<th >Nombres</th>
					<th >Apellidos</th>
					<th >Codigo</th>							      			
					<th >Grupo</th>	
					<th >Acciones</th>     
				</tr>
			</thead>

			<tbody alias="'.$row['id_seccion'].'" class="'.$row['id_seccion'].' tablas-selecionados">        
			</tbody>
		</table>
	</div>	
	';
}
echo $html;
?>