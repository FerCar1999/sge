
<?php 
date_default_timezone_set('America/El_Salvador');        
session_start();    
?>
<html>
	<?php require_once 'include/header.php' ?>
	<body class="grey lighten-3">
		<br>
		<br>
    	<form id="formRecuperar" method="post">
		  	<div class="row">
			 	<div class="col s12 m8 offset-m2 l4 offset-l4">
        			<div class="card-panel grey lighten-5 z-depth-1">
          				<div class="row">
          					<h4 class="center-align">DIARIO PEDAGÓGICO</h4>
            				<div class="col l4 offset-l4 s6 offset-s3 m4 offset-m4">
            					<img src="/media/img/logo.png" alt="" class="responsive-img">
            				</div>            				
          				</div>
          				<div class="divider"></div>
          				<br>
          				<h6 class="center-align">RECUPERAR CONTRASEÑA</h6>
          				<br>
          				<div class="row">
      						<div class="row">
          						<div class="input-field col s12">
          							<i class="material-icons prefix">assignment_ind</i>
          							<input id="codigo" type="text" autocomplete="off">
	          						<label for="codigo">Código</label>
          						</div>
      						</div>
      						<div class="row center-align">
      							<button class="btn-floating btn-large blue"><i class='material-icons'>verified_user</i></button>
      						</div>
          				</div>
        			</div>
      			</div>
	  		</div>
      	</form>
		<?php require_once 'include/scripts.php' ?>
    	<script type="text/javascript" src="/privado/js/recuperarpass.js"></script>
	</body>
</html>