
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
								<form action="/privado/php/reportes/conducta/conducta.php" class="hide" method="post" target="_blank" id="reporteAlumnoForm">
									<input id="alumnoCod" type="text" name="codigo">
									<input id="etapaCod" type="text" name="etapa">
								</form>
								<form action="">
									<div class="input-field col s12 search-wrapper">
										<i class="material-icons prefix">person</i>
						          		<input id="alumno" type="text" class="autocompleteOb">
						          		<label for="alumno">Carnet o nombre del estudiante</label>
					        		</div>
					        		<?php  
					        			//if ($_SESSION["permiso"] == "Administrador") {
					        			if (true) {
					        				echo
					        				'
					        					<div class="col m4 s12">
					        						<br>
		        				 					<a class="btn amber darken-4 right col s12" id="verObsAlumno">
		        										Historial
														<i class="right material-icons">list</i>
													</a>
												</div>
					        				';
					        			}
					        		?>
					        		<div class="col m4 s12">
					        			<br>
					        			<a class="btn amber darken-4 left col s12" id="reporteAlumno">
					        				Reporte
      										<i class="right material-icons">print</i>
    									</a>
					        		</div>
					        		<div class="col m4 s12">
					        			<br>
					        			<a class="btn amber darken-4 left col s12" id="loadObservacion">
					        				Observación
      										<i class="right material-icons">assignment</i>
    									</a>
					        		</div>
								</form>
								<br>
								<br>
								<div class="col s12" id="history">
									<br>
									<br>
									<ul class="collection with-header center-align"><li class="collection-header center-align"><h5>Historial de observaciones</h5></li></ul>
							     	<table class="historialObAlumno">
							        	<thead>
							        		<tr>									        			
							        			<td><b>Fecha</b></td>
							        			<td><b>Observación</b></td>
							        			<td><b>Acciones</b></td>
							        		</tr>
							        	</thead>
							        	<tbody class="table">
							        		<tr>
							        			<td></td>
							        			<td></td>
							        			<td></td>
							        		</tr>
							        	</tbody>
							        </table>
								</div>
								<br>
								<br>
								<div id="asignarObservacionDiv">
									<div class="row">
										<div class="col s8 m3 l3 center-align offset-s2">
											<br>
											<br>
											<img src="" alt="alumno" id="fotoOb" class="responsive-img circle">	
											<h5 id="nombreOb"></h5>
											<p id="codigoOb"></p>
										</div>
										<div class="col s12 m7 l7">
											<br>
											<br>
											<h5 class="text-center" id="headerOb">Asigne la observación al estudiante</h5>
											<br>
											<!--div class="input-field col s12">
												<i class="material-icons prefix">assignment_id</i>
												<select name="" id="obsPredefinida">
													<option value="">Seleccionar observación predefinida (No obligatorio)</option>
													<?php 
														require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
														$sql = "select descripcion from codigos where estado='Activo' and id_tipo_codigo='5'";
														$params = array("");

														$data = Database::getRows($sql, $params);
														foreach($data as $row)
														{																	
															echo '<option value="'.$row["descripcion"].'">'.$row["descripcion"].'</option>';
														}
													?>
												</select>
											</div>
											<br-->
											<div class="input-field col s12">
												<i class="material-icons prefix">mode_edit</i>
									          	<textarea id="observacion_text" class="materialize-textarea" row="500"></textarea>
									          	<label for="observacion_text">Observación</label>
									        </div>
										</div>
										<div class="col s12 right-align">
											<br>
	  										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Asignar observación" id="asignarObservacion">
												<i class="material-icons">add</i>
											</a>
											<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped hide" data-position="left" data-delay="50" data-tooltip="Editar observación" id="editarObservacion">
												<i class="material-icons">edit</i>
											</a>
											<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped cancelarButton" data-position="left" data-delay="50" data-tooltip="Cancelar">
												<i class="material-icons">close</i>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
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
			</div>
		</div>
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("codigosexaula"); ?>">

		<?php require_once 'include/modalHistorialCodigos.php' ?>
		<?php require_once 'include/scripts.php' ?>
		
		<script type="text/javascript" src="/privado/js/disciplina.js"></script>
	</body>
</html>