<?php 
date_default_timezone_set('America/El_Salvador');        
session_start();    
?>
<html>
<?php require_once 'include/header.php' ?>
<body>
	<?php 

	require_once 'include/menu.php';
	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
	$imprimir_boton = false;
	$data = Database::getRow('select nombre from asuetos where CURDATE() between fecha_inicio and fecha_fin',array());
	if($data['nombre']!=null){
		$titulo_page = 'No esta habilitado pasar lista en '.$data['nombre'];
		$titulo ="";$tabla = ""; $contenido = ""; $observacion ="";
		$imprimir_boton = false;
	}else{
		$titulo_page = "Asistencias";
			$imprimir_boton = true;
		require_once($_SERVER['DOCUMENT_ROOT'].'/privado/php/asistencias/imprimir_asistencia.php');
	} 
	
	?>
	<div class="content">
		<div class="row">

			<div class="col s12 m12">
				<div class="card" id="formAgregarSeccion">
					<div class="card-content">
						<div class="col s12 text-center">
							<span class="card-title"><?php echo $titulo_page;  ?></span>
							<br>
							<span class="text"><?php echo $titulo; ?></span>
						</div>
						<div class="row">
							<div class="input-field col s12 m6">
								<i class="material-icons prefix">class</i>
								<input id="contenido" type="text" value="<?php echo $contenido; ?>">
								<label for="contenido">Situación de aprendizaje</label>
							</div>								
							<div class="input-field col s12 m6">
								<i class="material-icons prefix">import_contacts</i>
								<input id="observacion" type="text" value="<?php echo $observacion; ?>">
								<label for="observacion">Observación grupal</label>
							</div>								
						</div>
					</div>
				</div>

			</div>
			<div class="col s12 m12 ">
				<div class="card">
					<div class="card-content">
						<table class="striped">
							<thead>
								<tr>
									<th># Lista</th>
									<th>Carnet</th>
									<th>Estudiante</th>									
									<th>Asistencia</th>
									<th>Impuntualidad</th>
									<th>Códigos</th>
								</tr>
							</thead>
							<tbody class="table"> 
								<?php 
								echo $tabla;
								?>       

							</tbody>
						</table>




					</div>
				</div>
			</div>

		</div>
	</div>
	<?php 		
		if($imprimir_boton)
		echo ' 
		<div class="fixed-action-btn" id="floatMatriculado">
			<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped right" data-position="left" data-delay="50" data-tooltip="Agregar" id="guardar_asistencia">
				<i class="material-icons">add</i>
			</a>
		</div>
		';	
	?>
	<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("asistencias"); ?>">
	<?php require_once 'include/modal.php' ?>
	<?php require_once 'include/modalHistorialCodigos.php' ?>
	<?php require_once 'include/scripts.php' ?>		
	<script type="text/javascript" src="/privado/js/asistencias.js"></script>
	<script type="text/javascript" src="/privado/js/disciplina.js"></script>
</body>
</html>