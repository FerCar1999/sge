<?php 
  	session_start();
  	// si tiene una session activa muestra el dashboard
  	if(isset($_SESSION['id_estudiante'])){
	    header("location: /estudiante");
  	}
?>
<html>
	<?php require_once '../privado/views/include/header.php' ?>
	<body class="grey lighten-3">		
		<br>
		<div class="row">
			<div class="col s12 m8 offset-m2 l4 offset-l4">
        		<div class="card-panel grey lighten-5 z-depth-1">
          			<div class="row">
          				<h4 class="center-align">SISTEMA DE GESTIÓN ESTUDIANTIL</h4>
            			<div class="col l4 offset-l4 s6 offset-s3 m4 offset-m4">
            				<img src="/media/img/logo.png" alt="" class="responsive-img">
            			</div>
          			</div>
          			<div class="divider"></div>
          			<br>
          			<br>
          			<div class="row">
      					<div class="row">
          					<div class="input-field col s12">
          						<i class="material-icons prefix">assignment_ind</i>
          						<input id="codigo" type="text">
	          					<label for="codigo">Correo</label>
          					</div>
      					</div>
      					<div class="row">
          					<div class="input-field col s12">
          						<i class="material-icons prefix">vpn_key</i>
          						<input id="clave" type="password">
	          					<label for="clave">Clave</label>
          					</div>
      					</div>
      					<div class="row center-align">
      						<button id="btn_login" class="btn-floating btn-large blue"><i class='material-icons right'>verified_user</i></button>
      					</div>                
          			</div>
        		</div>
      		</div>
  		</div>
		<?php require_once '../privado/views/include/scripts.php' ?>
    <script type="text/javascript" src="/publico/js/login.js"></script>
	</body>
</html>
