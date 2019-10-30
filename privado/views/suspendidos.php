
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
			<div class="col s12 ">
				<div class="card">
					<div class="card-content">
						<div class="row">
							<div class="col s12 text-center">
								<span class="card-title">ESTUDIANTES POR SUSPENDER</span>
							</div>
							
							<table class="striped">
								<thead>
									<tr>
										<th>Código</th>
										<th>Estudiante</th>										
										<th>Grado</th>
										<th>Especialidad</th>							
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody id="table1"> 

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col s12 ">
				<div class="card">
					<div class="card-content">
						<div class="row">
							<div class="col s12 text-center">
								<span class="card-title">ESTUDIANTES SUSPENDIDOS</span>
							</div>
							
							<table class="striped">
								<thead>
									<tr>
										<th>Código</th>
										<th>Estudiante</th>										
										<th>Grado</th>
										<th>Especialidad</th>
										<th>Inicio</th>
										<th>Fin</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody id="table2"> 

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<div id="modal1" class="modal">
	<div class="modal-content">
		
		<div class="row" id="observacionDiv">
			<div class="col s8 m4 l4 center-align offset-s2">
				
				<img src="" alt="alumno" id="fotoOb" class="responsive-img circle">	
				<h5 id="nombreOb"></h5>
				<p id="codigoOb"></p>
			</div>
			<div class="col s12 m8 l8">
				<br>
				<h5 class="text-center">Suspender estudiante</h5>
				<div class="input-field col s12">
				<i class="material-icons prefix">date_range</i>					
					<input id="date1" type="date" data-date="" data-date-format="DD MMMM YYYY">
					<label for="date1">Inicio</label>
				</div>
				<div class="input-field col s12">					
				<i class="material-icons prefix">date_range</i>
					<input id="date2" type="date" data-date="" data-date-format="DD MMMM YYYY">
					<label for="date2">Fin</label>
				</div>	
				<div class="input-field col s12">
					<i class="material-icons prefix">mode_edit</i>
					<textarea id="observacion_text" class="materialize-textarea" row="100"></textarea>
					<label for="observacion_text">Observación</label>
				</div>

			</div>
		</div>


	
</div>
<div class="modal-footer">
	<a href="#!" onclick="guardar_suspencion();" class=" modal-action modal-close waves-effect waves-green btn-flat">Suspender</a>
</div>
</div>
<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("suspendidos"); ?>">

<?php require_once 'include/scripts.php' ?>

<script type="text/javascript" src="/privado/js/suspendidos.js"></script>
</body>
</html>