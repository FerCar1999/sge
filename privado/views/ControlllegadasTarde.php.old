<?php 
date_default_timezone_set('America/El_Salvador');        
session_start();    
?>
<html>
<?php require_once 'include/header.php' ?>
<body>
<?php 
	require_once 'include/menu.php';
	if(isset($_SESSION["isAdmin"])){
		echo '<input type="hidden" name="" id="isAdmin" value="1">';
	}
?>
	<div class="content">
	
		<div class="row">
			<div class="col s12">
				<div class="card">
					<div class="card-content">
						<div class="row">
						<div class="input-field col s12 filtro">
												<i class="material-icons prefix">extension</i>
												<select name="" id="select_nivel">
													<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													
													$sql = 'select id_nivel,nombre from niveles where estado ="Activo"';
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_nivel"].'">'.$row["nombre"].'</option>';
													}
													?>
												</select>
												<label>Nivel</label>
											</div>
						<div class="input-field col s12">
												<i class="material-icons prefix">extension</i>
												<select name="" id="select_etapa">
													<?php 
													require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
													$sql = 'select id_etapa,nombre from etapas where estado ="Activo"';
													$params = array("");

													$data = Database::getRows($sql, $params);
													foreach($data as $row)
													{																	
														echo '<option value="'.$row["id_etapa"].'">'.$row["nombre"].'</option>';
													}
													?>
												</select>
												<label>Fecha</label>
											</div>						

						<div class="col s12 text-center">
								<span class="card-title">LLEGADAS TARDE ALUMNOS SIN PROCESAR</span>
							</div>
							
							<table class="striped">
								<thead>
									<tr>

										<th>#</th>
										<th>Carnet</th>
										<th>Estudiante</th>
										<th>Curso</th>								
										<th>Institución</th>	
										<th>Salón</th>	
										<th>Total</th>	
														
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody class="table2"> 
										
								</tbody>
							</table>
							<br>
							<br>																																			
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<div class="fixed-action-btn" id="floatMatriculado">
			<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped right" data-position="left" data-delay="50" data-tooltip="Buscar" id="buscarAlumnos">
				<i class="material-icons">search</i>
			</a>
</div>
<div id="modal1" class="modal">
	<div class="modal-content">
		
		<div class="row" id="observacionDiv">
			
			<div class="col s12 ">
				<br>
				<h5 class="text-center">ASIGNE LA OBSERVACIÓN</h5>	
				<div class="input-field col s12">
					<i class="material-icons prefix">mode_edit</i>
					<textarea id="observacion_text" class="materialize-textarea" row="100"></textarea>
					<label for="observacion_text">Observación</label>
				</div>

			</div>
		</div>
</div>
<div class="modal-footer">
	<a href="#!" onclick="guardar();" class=" modal-action modal-close waves-effect waves-green btn-flat">OK</a>
</div>
</div>

<div id="modalEtapas" class="modal">
			    <div class="modal-content">
			      	<h4>ETAPAS</h4>
			      	<p>Ver una etapa: </p>
			      	<select name="" id="selectEtapa">
						<option value="0" disabled selected>Selecciona una etapa</option>
						<?php 
							require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
							$sql = "select id_etapa,nombre from etapas where estado='Activo'";
							$params = array("");
							$data = Database::getRows($sql, $params);
							foreach($data as $row){
								echo '<option value="'.$row["id_etapa"].'">'.$row["nombre"].'</option>';
							}
						?>
					</select>
					<br>
					<br>
					<br>
					<br>
		    	</div>
			    <div class="modal-footer">
			      <a id="viewReport" class="modal-action waves-effect waves-green btn-flat">Aceptar</a>
			      <a id="allReport" class="modal-action waves-effect waves-green btn-flat">Ver reporte completo</a>
			    </div>
		  	</div>
		  	<form action="reporte-conducta" class="hide" method="post" target="_blank" id="reporteAlumnoForm">
									<input id="alumnoCod" type="text" name="codigo">
									<input id="etapaCod" type="text" name="etapa">
				</form>
<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("controldiciplinario"); ?>">

<?php require_once 'include/scripts.php' ?>
<script type="text/javascript" src="/privado/js/controlLLegadasTarde.js"></script>
</body>
</html>