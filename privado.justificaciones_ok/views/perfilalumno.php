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
							<form id="formAlumnoMod" enctype="multipart/form-data" method="post">
								<div class="modificar_alumno">
									<span class="card-title"></span>
									<div class="row">
										<div class="col s6 offset-s3 m4 offset-m4 offset-m3 l3 offset-l4" id="div-img-personal">
											<img class="circle" src="/media/img/user_default.jpg" id="img_alumno_mod" alt="Imagen">
											<div class="file-field input-field input-photo col l6 m6 s12 tooltipped" data-position="left" data-delay="50" data-tooltip="Agregar foto">
      											<div class="btn amber darken-4">
      												<i class="material-icons">photo</i>
        											<input type="file" id="fotoAlumnoMod" name="fotoAlumnoMod">
      											</div>
    										</div>
										</div>
									</div>
									<div class="row">
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">account_circle</i>
		          							<input id="mod_nombre" type="text" class="validate">
		          							<label for="mod_nombre">Nombres</label>
										</div>
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">account_circle</i>
		          							<input id="mod_apellido" type="text" class="validate">
		          							<label for="mod_apellido">Apellidos</label>
										</div>
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">assignment_ind</i>
		          							<input id="mod_codigo" type="text" class="validate">
		          							<label for="mod_codigo">Código</label>
										</div>
										<div class="input-field col l6 m6 s12">
											<i class="material-icons prefix">email</i>
		          							<input id="mod_correo" type="email" class="validate">
		          							<label for="mod_correo">Correo</label>
										</div>
										
										<div class="col s12 right-align">
											<br>
	  										<a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped rigth-align" data-position="left" data-delay="50" data-tooltip="Actualizar" id="modificar_alumno">
												<i class="material-icons">edit</i>
											</a>
											<a  id="cancelar_mod" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Cancelar">
												<i class="material-icons">cancel</i>
											</a>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("perfil"); ?>">

		<?php require_once 'include/scripts.php' ?>
	</body>
</html>