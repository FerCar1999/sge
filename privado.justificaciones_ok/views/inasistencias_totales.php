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
							<span class="card-title"><p class="center-align">ELIMINAR INASISTENCIA TOTAL</p></span>
							<div class="row">
								<form action="">
									<div class="input-field col s12 search-wrapper">
										<i class="material-icons prefix">person</i>
						          		<input id="alumno" type="text" class="autocomplete">
						          		<label for="alumno">Carnet o nombre del estudiante</label>
					        		</div>
								</form>
								<div class="center-align"><div class="col s12" id="noHayCodigos"></div></div>							
								<div>
									<div class="col s12">
								     	<table>
									        	<thead>
									        		<tr>
									        			<td><b>Carnet</b></td>
									        			<td><b>Nombre</b></td>
									        			<td><b>Especialidad</b></td>
									        			<td><b>Grado</b></td>
									        			<td><b>Fecha</b></td>
									        			
									        			<td><b>Acciones</b></td>
									        		</tr>
									        	</thead>
									        	<tbody class="table">
									        		
									        	</tbody>
									        </table>
									</div>
									<div class="col s12 right-align">
										<br>
										<a onclick="mostrar();" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Buscar">
											<i class="material-icons">search</i>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("justificaciones"); ?>">
		<?php require_once 'include/scripts.php' ?>
		<script src="/privado/js/inasistencias_totales.js"></script>
	</body>
</html>