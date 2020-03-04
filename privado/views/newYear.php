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
							<p>Se borraran los datos para iniciar un nuevo año escolar, si está seguro de realizar esta operación por favor, presione el botón "Continuar".</p>
                            <p><h5>NOTA</h5></p>
                            <p>- El sistema hará una copia de seguridad de la base de datos para conservar la información</p>
                            <br>
                            <a class="waves-effect waves-light amber darken-4 btn right" id="btnReiniciarDatos">Continuar</a>
                            <br>
                            <br>
						</div>
					</div>
				</div>
			</div>
		</div>
        <br>
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("grupos"); ?>">

		<?php require_once 'include/scripts.php' ?>
		<script type="text/javascript" src="/privado/js/reiniciarDatos.js"></script>
	</body>
</html>