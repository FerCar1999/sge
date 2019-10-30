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
			<div class="col s12">
				<div class="card">						
					<div class="card-content">
						<div class="row">
							<div class="col s12 text-center">
								<span class="card-title">Configuración de horario</span>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12 m2">
								<i class="material-icons prefix">extension</i>
								<select name="" id="select_grado">
									<?php 
									require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
									$sql = "select id_grado,nombre from grados where estado='Activo'";
									$params = array("");

									$data = Database::getRows($sql, $params);
									foreach($data as $row)
									{																	
										echo '<option value="'.$row["id_grado"].'">'.$row["nombre"].'</option>';
									}
									?>
								</select>
								<label>Grado</label>
							</div>
							<div class="input-field col s12 m3 ">
								<i class="material-icons prefix">book</i>
								<select name="" id="select_tipo_asignatura">
									<?php 
									require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
									$sql = "select id_tipo_asignatura,nombre from tipos_asignaturas where estado='Activo'";
									$params = array("");

									$data = Database::getRows($sql, $params);
									foreach($data as $row)
									{																	
										echo '<option value="'.$row["id_tipo_asignatura"].'">'.$row["nombre"].'</option>';
									}
									?>
								</select>
								<label>Tipo asignatura</label>
							</div>
							<div class="input-field col s12 m3">
								<i class="material-icons prefix">lightbulb_outline</i>
								<select name="" id="select_especialidad">
									<?php 
									require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
									$sql = "select id_especialidad,nombre from especialidades where estado='Activo'";
									$params = array("");

									$data = Database::getRows($sql, $params);
									foreach($data as $row)
									{																	
										echo '<option value="'.$row["id_especialidad"].'">'.$row["nombre"].'</option>';
									}
									?>

								</select>
								<label>Especialidad</label>
							</div>
							
							<div class="input-field col s12 m2">
								<i class="material-icons prefix">work</i>
								<select name="" id="select_secciones">
									<option value="">No Disponible</option>
								</select>
								<label>Sección</label>
							</div>
							<div class="input-field col s12 m2">
								<i class="material-icons prefix">local_library</i>
								<select name="" id="select_grupos">
									
								</select>
								<label>Grupo</label>
							</div>

							
							
						</div>
					</div>
				</div>
			</div>
			<div class="row search-wrapper">
				<div class="col s12 m4">
					<div class="card">
						<div class="card-content">
							<div class="row">



								<div class="input-field col s12">
									<i class="material-icons prefix">bookmark_border</i>
						    	<input id="select_asignaturas" type="text" class="autocomplete">
						    	<label for="select_asignaturas">Nombre Asignatura</label>
								</div>	
								<div class="input-field col s12">
									<i class="material-icons prefix">access_time</i>
									<select name="" id="select_hora">
										<?php 
										require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
										$sql = 'select id_tiempo, TIME_FORMAT(hora_inicial, "%H:%i") as inicio,TIME_FORMAT(hora_final, "%H:%i") as final from tiempos where estado="Activo" order by hora_inicial asc';
										$params = array("");

										$data = Database::getRows($sql, $params);
										foreach($data as $row)
										{																	
											echo '<option value="'.$row["id_tiempo"].'">'.$row["inicio"].' - '.$row["final"].'</option>';
										}
										?>
									</select>
									<label>Hora</label>
								</div>	
								<div class="input-field col s12 ">
									<i class="material-icons prefix">place</i>
									<select name="" id="select_local">
										<?php 
										require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
										$sql = "select l.id_local,l.nombre,tp.nombre as local from locales l, tipos_locales tp where l.id_tipo_local= tp.id_tipo_local and l.estado='Activo' order by l.nombre asc";
										$params = array("");

										$data = Database::getRows($sql, $params);
										foreach($data as $row)
										{																	
											echo '<option value="'.$row["id_local"].'">'.$row["local"]." - ".$row['nombre'].'</option>';
										}
										?>
									</select>
									<label>Lugar</label>
								</div>

							</div>
							
								<div class="switch">
								<label>
									
									<input id="grupo_completo" type="checkbox">
									<span class="lever"></span>							Grupo técnico completo			
								</label>
								</div>
							<br>
							<div class="switch">
								<label>
									
									<input id="check_modulo" type="checkbox">
									<span class="lever"></span>							Asignatura modular			
								</label>
							</div>
							

							<div class="row">
								<div class="input-field col s6">
									<i class="material-icons prefix">timer</i>
									<input id="date_inicio" type="date" class="datepicker">
									<label for="date_inicio">Inicio</label>
								</div>
								<div class="input-field col s6">
									<i class="material-icons prefix">timer</i>
									<input id="date_fin" type="date" class="datepicker">
									<label for="date_fin">Fin</label>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				<div class="col s12 m8">
					<div class="card">
						<div class="card-content">
							<ul class="tabs">
								<li class="tab col s2"><a onclick="tab_dias('Lunes');" class="active" href="#lunes">Lunes</a></li>
								<li class="tab col s2"><a onclick="tab_dias('Martes');" href="#martes">Martes</a></li>

								<li class="tab col s3"><a onclick="tab_dias('Miercoles');" href="#miercoles">Miércoles</a></li>
								<li class="tab col s2"><a onclick="tab_dias('Jueves');" href="#jueves">Jueves</a></li>					        	
								<li class="tab col s2"><a  onclick="tab_dias('Viernes');" href="#viernes">Viernes</a></li>					        	
							</ul>
							<br>
							<div id="lunes">
								<!-- Tabla de actividades-->
								<table class="striped">
									<thead>
										<tr>									
											<th >Asignatura</th>										
											<th >Tiempo</th>							      		
											<th >Grado</th>							      	
											<th >Sección</th>
											<th >Grupo</th>
											<th >Lugar</th>				 
											<th >Acciones</th>     
										</tr>
									</thead>

									<tbody class="table">        
										<?php  
										require_once($_SERVER['DOCUMENT_ROOT'].'/privado/php/horarios/imprimir_horarios.php');

										cargar_dia('Lunes');
										?>

									</tbody>
								</table>

							</div>
							<div id="martes">
								<!-- Tabla de actividades-->
								<table class="striped">
									<thead>
										<tr>									
											<th >Asignatura</th>										
											<th >Tiempo</th>							      		
											<th >Grado</th>							      	
											<th >Sección</th>
											<th >Grupo</th>
											<th >Lugar</th>				 
											<th >Acciones</th>       
										</tr>
									</thead>

									<tbody class="tablem">        
										<?php  
										require_once($_SERVER['DOCUMENT_ROOT'].'/privado/php/horarios/imprimir_horarios.php');

										cargar_dia('Martes');
										?>

									</tbody>
								</table>

							</div>

							<div id="miercoles">
								<!-- Tabla de actividades-->
								<table class="striped">
									<thead>
										<tr>									
											<th >Asignatura</th>										
											<th >Tiempo</th>							      		
											<th >Grado</th>							      	
											<th >Seccion</th>
											<th >Grupo</th>
											<th >Lugar</th>				 
											<th >Acciones</th>     
										</tr>
									</thead>

									<tbody class="table">        

										<?php  
										require_once($_SERVER['DOCUMENT_ROOT'].'/privado/php/horarios/imprimir_horarios.php');

										cargar_dia('Miércoles');
										?>
									</tbody>
								</table>

							</div>
							<div id="jueves">
								<!-- Tabla de actividades-->
								<table class="striped">
									<thead>
										<tr>									
											<th >Asignatura</th>										
											<th >Tiempo</th>							      		
											<th >Grado</th>							      	
											<th >Seccion</th>
											<th >Grupo</th>
											<th >Lugar</th>				 
											<th >Acciones</th>     
										</tr>
									</thead>

									<tbody class="table">        
										<?php  
										require_once($_SERVER['DOCUMENT_ROOT'].'/privado/php/horarios/imprimir_horarios.php');

										cargar_dia('Jueves');
										?>

									</tbody>
								</table>

							</div>
							<div id="viernes">
								<!-- Tabla de actividades-->
								<table class="striped">
									<thead>
										<tr>									
											<th >Asignatura</th>										
											<th >Tiempo</th>							      		
											<th >Grado</th>							      	
											<th >Seccion</th>
											<th >Grupo</th>
											<th >Lugar</th>				 
											<th >Acciones</th>     
										</tr>
									</thead>

									<tbody class="table">        
										<?php  
										require_once($_SERVER['DOCUMENT_ROOT'].'/privado/php/horarios/imprimir_horarios.php');

										cargar_dia('Viernes');
										?>

									</tbody>
								</table>

							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
		<div class="fixed-action-btn" id="floatMatriculado">
			<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped right" data-position="left" data-delay="50" data-tooltip="Agregar" id="agregar_clase">
				<i class="material-icons">add</i>
			</a>
		</div>
		<div class="fixed-action-btn" style="margin-right:65px;margin-bottom:20px;">
			<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped right" data-position="left" data-delay="50" data-tooltip="Imprimir horario" href="/privado/php/reportes/horarios/reporteHorario.php" target="_blank">
				<i class="material-icons">print</i>
			</a>
		</div>

		<!-- Modal Structure -->
		<div id="modal1" class="modal">
			<div class="modal-content">
				<h5 class="text-center">Asignaturas modulares</h5>
				<table>
					<thead>
						<tr>
						<td><b>Módulo</b></td>
							<td><b>Inicio</b></td>
							<td><b>Fin</b></td>							
							<td><b>Acciones</b></td>
						</tr>
					</thead>
					<tbody class="table_modulos">
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Ok</a>
			</div>
		</div>

		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("horarios"); ?>">

		<?php require_once 'include/scripts.php' ?>
		<script type="text/javascript" src="/privado/js/horarios@1.js"></script>
	</body>
	</html>		