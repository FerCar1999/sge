<div id="modal1" class="modal">
	<div class="row">
		<ul class="tabs">
			<li id="codigoActive" class="tab col s5 offset-s1"><a class="active" href="#codigoDiv">Código</a></li>
			<li id="observacionActive" class="tab col s5"><a href="#observacionDiv">Observación</a></li>
		</ul>
	</div>
	<div class="modal-content">
		<div class="row" id="codigoDiv">
			<div class="col s8 m4 l4 center-align offset-s2">
				<br>
				<img src="" alt="alumno" id="foto" class="responsive-img circle">	
				<h5 id="nombre"></h5>
				<p id="codigo"></p>
			</div>
			<div class="col s12 m7 l7 offset-m1 offset-l1">
				<br>
				<div class="input-field col s12">
					<i class="material-icons prefix">assignment_id</i>
					<select name="" id="selectTipoCodigo">
						<option value="" disabled selected>Selecciona un tipo de código</option>
						<?php 
							require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
							$sql = "select id_tipo_codigo,nombre from tipos_codigos where estado='Activo'";
							$params = array("");

							$data = Database::getRows($sql, $params);
							foreach($data as $row)
							{																	
								echo '<option value="'.$row["id_tipo_codigo"].'">'.$row["nombre"].'</option>';
							}
						?>
					</select>
				</div>
			</div>
			<div class="col s12 m7 l7 offset-m1 offset-l1">
				<div class="input-field col s12">
					<i class="material-icons prefix">assignment_id</i>
					<select name="" id="selectCodigo">
						<option value="" disabled selected>Selecciona un código</option>						
					</select>
				</div>
			</div>
			<!--div class="col s12 m7 l7 offset-m1 offset-l1">
				<div class="input-field col s12">
					<i class="material-icons prefix">mode_edit</i>
		          	<textarea id="observacion_codigo" class="materialize-textarea" row="100"></textarea>
		          	<label for="observacion">Observación</label>
		        </div>
			</div-->
		</div>
		<div class="row" id="observacionDiv">
			<div class="col s8 m4 l4 center-align offset-s2">
				
				<img src="" alt="alumno" id="fotoOb" class="responsive-img circle">	
				<h5 id="nombreOb"></h5>
				<p id="codigoOb"></p>
			</div>
			<div class="col s12 m8 l8">
				<br>
				<h5 class="text-center">Asigne la observación al estudiante</h5>
				<div class="input-field col s12">
					<i class="material-icons prefix">mode_edit</i>
		          	<textarea id="observacion_text" class="materialize-textarea" row="100"></textarea>
		          	<label for="observacion_text">Observación</label>
		        </div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" id="buttonCodigo" onclick="asignar_codigo();" class=" modal-action modal-close waves-effect waves-green btn-flat">Aplicar código</a>
		<a href="#!" id="buttonObservacion" onclick="asignar_observacion();" class=" modal-action modal-close waves-effect waves-green btn-flat">Aplicar observación</a>
		<a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
	</div>
</div>