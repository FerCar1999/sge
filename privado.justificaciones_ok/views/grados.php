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
				<div class="col s12 m12 l6">
					<div class="card">
						<div class="card-content">
							<div class="input-field col  s12">									
								<input id="buscar_grado" type="text" class="validate">
								<label for="icon_prefix">Buscar</label>
							</div>
							<table class="striped">
								<thead>
									<tr>
										<th id="nombreTipo">Nombre</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody class="table">        
								</tbody>
							</table>

							<!-- Paginacion de datos-->            
							<ul class="pagination"></ul>

							<div class="footer">
								<div class="stats">
									<a id="ver_grados_activos" href="#!">Activos</a> |
									<a id="ver_grados_inactivos" href="#!">Inactivos</a>
								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="col s12 m12 l6">
					<div class="card" id="formAgregarGrado">
						<div class="card-content">
							<span class="card-title">Grado</span>
							<div class="row">
								<div class="input-field col s12">
									<i class="material-icons prefix">group</i>
          							<input id="nombre" type="text">
          							<label for="nombre">Nombre</label>
								</div>

								<div class="input-field col s12" id="divSelect">
									<i class="material-icons prefix">assignment_id</i>
									<select name="" id="selectNivel">
										<?php 
											require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
											$sql = "select id_nivel,nombre,descripcion from niveles where estado='Activo'";
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
								<div class="col s12 right-align">
									<br>
										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Agregar" id="agregar_grado">
										<i class="material-icons">add</i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formModGrado">
						<div class="card-content">
							<span class="card-title">Modificar grado</span>
							<div class="row">
								<div class="input-field col s12">
									<i class="material-icons prefix">group</i>
          							<input id="mod_nombre" type="text">
          							<label for="mod_nombre">Nombre</label>
								</div>
								
                                <div class="input-field col s12" id="divSelect">
									<i class="material-icons prefix">assignment_id</i>
									<select name="" id="selectNivelMod">
										<?php 
											require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
											$sql = "select id_nivel,nombre,descripcion from niveles where estado='Activo'";
											$params = array("");

											$data = Database::getRows($sql, $params);
											foreach($data as $row)
											{																	
												echo '<option value="'.$row["id_nivel"].'">'.$row["nombre"].'</option>';
											}
										?>
									</select>
								</div>
								
								<div class="col s12 right-align">
									<br>
										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Modificar" id="modificar_grado">
										<i class="material-icons">edit</i>
										<a  id="cancelar_mod" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
										<i class="material-icons">cancel</i>
									</a>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formElimGrado">
						<div class="card-content">
							<span class="card-title">Desactivar grado</span>
							<div class="row">
								<h5 id="confirmacion"></h5>
								<div class="col s12 right-align">
									<a  id="eliminar_grado" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Desactivar">
										<i class="material-icons">delete</i>
									</a>
									<a  id="cancelar_eliminar" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
										<i class="material-icons">cancel</i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card" id="formActGrado">
						<div class="card-content">
							<span class="card-title">Activar grado</span>
							<br>
							<div class="row">
								<h5 id="confirmacion_activar"></h5>
								<div class="col s12 right-align">
									<a  id="activar_grado" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Activar">
										<i class="material-icons">autorenew</i>
									</a>
									<a  id="cancelar_activar" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
										<i class="material-icons">cancel</i>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("grados"); ?>">

		<?php require_once 'include/scripts.php' ?>
		<script type="text/javascript" src="/privado/js/grados.js"></script>
	</body>
</html>