<?php 
date_default_timezone_set('America/El_Salvador');        
session_start();    
?>
<html>
<?php require_once 'include/header.php' ?>
<body>
	<?php require_once 'include/menu.php' ?>
	<div class="content">
		<div class="row">
			<div class="col s12">
				<div class="card">
					<div class="card-content">
						<div class="row">
							<div class="col s12 text-center">
								<span class="card-title">ASISTENCIAS DIFERIDAS</span>
							</div>
							
							<table class="striped">
								<thead>
									<tr>
										<th>Asignatura</th>
										<th>Grado</th>
										<th>Inicio</th>										
										<th>Fin</th>										
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody class="table"> 
										<?php 
											require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/asistencias_diferidas/imprimir_asistencias_diferidas.php");

										 ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<?php require_once 'include/scripts.php' ?>

<script type="text/javascript" src="/privado/js/asistencias.js"></script>
</body>
</html>