<?php 
date_default_timezone_set('America/El_Salvador');        
session_start();    
?>
<html>
<?php $nonavfixed=true; ?>
<?php require_once 'include/header.php' ?>
<body>
	<?php require_once 'include/menu.php' ?>
	<div class="content-full">
		<div class="row">
			<div class="card">
			</div> 
			<div class="col s12">
				<div class="card">						
					<div class="card-content">					
						<div class="row">
							<div class="col s12 text-center">
								<span class="card-title" id="tittle">CLASES SIN MARCAR ASISTENCIA</span>								
							</div>
							<div class="input-field col s12">
									<i class="material-icons prefix">timer</i>
									<input id="fecha" type="date" class="datepicker">
									<label for="fecha">Fecha</label>
							</div> 
							<table class= "datatable" class="striped">
								<thead>
									<tr>
										<th>Código</th>
										<th>Nombre</th>
										<th>Hora</th>
										<th>Materia</th>
										<th>Grado</th>										
										<th>Local</th>										
									</tr>
								</thead>
								<tbody id="table">        
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>						
		</div>
	<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("horarios"); ?>">
	<?php require_once 'include/scripts.php' ?>
	<script type="text/javascript" src="/privado/assets/js/datatables.js"></script>
	<script type="text/javascript" src="/privado/src/js/personalAttendance.js"></script>
	</body>
</html>		