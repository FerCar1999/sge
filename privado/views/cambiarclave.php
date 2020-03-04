<?php 
date_default_timezone_set('America/El_Salvador');        
session_start();    
?>
<html>
	<?php 
		session_start();
		if ($_SESSION["pass"] == 0) header('Location: '.$_SERVER['DOCUMENT_ROOT']."/privado/views/dashboard.php");
	?>
	<?php require_once 'include/header.php' ?>
	<body class="grey lighten-3">
		<br>
		<br>
		<div class="row">
			<div class="col s12 m8 offset-m2 l4 offset-l4">
        		<div class="card-panel grey lighten-5 z-depth-1">
          			<div class="row">
          				<h4 class="center-align">DIARIO PEDAGÓGICO</h4>
          				<h6 class="center-align">NUEVA CONTRASEÑA</h6>
          			</div>
          			<div class="divider"></div>
          			<br>
          			<br>
          			<div class="row">
      					<div class="row">
          					<div class="input-field col s12">
          						<i class="material-icons prefix">vpn_key</i>
          						<input id="clave" type="password">
	          					<label for="clave">Nueva contraseña</label>
          					</div>
      					</div>
      					<div class="row">
          					<div class="input-field col s12">
          						<i class="material-icons prefix">vpn_key</i>
          						<input id="claveR" type="password">
	          					<label for="claveR">Repetir nueva contraseña</label>
          					</div>
      					</div>
      					<div class="row center-align">
      						<button class="btn-floating btn-large blue" id="newPass">
      							<i class='material-icons right'>verified_user</i>
      						</button>
      					</div>
          			</div>
        		</div>
      		</div>
  		</div>
		<?php require_once 'include/scripts.php' ?>
    <script type="text/javascript" src="/privado/js/actualizarpass.js"></script>
	</body>
</html>