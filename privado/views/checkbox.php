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
                            <span class="card-title">Grados que recibirán la asignatura</span>
				            
				        </div>
				        <div class="row">
							<?php 
						    	require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
								$sql = "select id_grado,nombre from grados where estado='Activo'";
								$params = array("");
								$data = Database::getRows($sql, $params);
								foreach($data as $row)
								{																	
									echo '
									    <div class="col l3 s12 m6">
									        <input type="checkbox" id="'.$row['id_grado'].'" name="mod_gradosAsignatura"/>
									        <label for="'.$row['id_grado'].'">'.$row['nombre'].'</label>
	                                    </div>';
								}
							?>
				        </div>
				        <div class="input-field">
				        	<button class="btn waves-effect waves-light" id="cambio">Cambiar checkbox</button>
				        </div>
				        <br>
				    </div>
				</div>
			</div>
		</div>
		<?php require_once 'include/scripts.php' ?>
		<script>
			function cambiarCheck(){
				$('input[name="mod_gradosAsignatura"]').each(function() {
				   //$(this).prop("checked",true);
				});
				$.ajax({
					type:'POST',
					url:"/privado/php/asignaturas/asignaturasListar.php",
					data:{
					    nombre: 'Sociales',
					    estado: 'Activo'
					},
					success: function(valores){
					    var i = 0;
						var datos = eval(valores);
						for (var k in datos) {
							console.log(datos[k].id_grado);
							$.each($("input[name='mod_gradosAsignatura']"), function() {
				                if($(this).attr("id") == (datos[k].id_grado)){
				                    $(this).prop("checked",true);
				                }else{
				                    $(this).prop("checked",false);
				                }
				                i++;
				            });
						}
						return false;
					}
				});
				return false;
			}
			$("#cambio").click(function () {
				cambiarCheck();
			});

		</script>
	</body>
</html>