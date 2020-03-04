<?php 
    date_default_timezone_set('America/El_Salvador');
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
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
                            <div class="input-field">
                                <i class="material-icons prefix">assignment_id</i>
                                <select name="" id="selectTipo">
                                    <option value="" disabled selected>Elija una opción</option>
                                    <option value="Personal">Personal</option>
                                    <!--option value="Alumnos">Alumnos</option-->
                                </select>
                                <label>Tipo</label>
                            </div>
                            <br>
                            <div class="input-field">
                                <i class="material-icons prefix">assignment_id</i>
                                <select name="" id="selectFuncion">
                                    <option value="" disabled selected>Elija una función</option>
                                </select>
                                <label>Funciones</label>
                            </div>
                            <br>
                            <p>
                                <input type="checkbox" id="fechas" />
                                <label for="fechas">Desde el principio</label>
                                <br><br>
                            </p>
                            <div class="row">
                                <div class="col m6 s12">
                                    <div class="input-field">
                                        <i class="material-icons prefix">date_range</i>
									    <input value="<?php echo date('Y-m-d')?>" id="date_inicio" type="text" class="datepicker" name="fin">
									    <label for="date_inicio">Fecha de inicio (AAAA-MM-DD)</label>
								    </div>
                                </div>
                                <div class="col m6 s12">
                                    <div class="input-field">
                                        <i class="material-icons prefix">date_range</i>
									    <input value="<?php echo date('Y-m-d')?>" id="date_fin" type="text" class="datepicker" name="fin">
									    <label for="date_fin">Fecha de fin (AAAA-MM-DD)</label>
								    </div>
                                </div>
                            </div>
                            <div class="row">
                                <p>
                                    <input type="checkbox" id="todos" />
                                    <label for="todos">Todo el personal</label>
                                </p>
                                <div class="input-field col s12">
                                    <i class="material-icons prefix">face</i>
                                    <input type="text" id="autocomplete-input" class="autocomplete">
                                    <label for="autocomplete-input">Personal</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12">
                                    <a class="waves-effect waves-light btn right amber darken-4" id="verBitacora" disabled>Ver bitácora</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12">
                                    <table class="striped">
	    							    <thead>
    									    <tr>
                                                <th>Fecha</th>
			    							    <th>Personal</th>
				    						    <th>Función</th>
					    					    <th>Descripción</th>
						    			    </tr>
							    	    </thead>
								        <tbody class="table">        
								        </tbody>
							        </table>

							        <!-- Paginacion de datos-->            
							        <ul class="pagination"></ul>
                                </div>
                            </div>
						</div>
					</div>
                    <div id="modalDetalle" class="modal">
                        <div class="modal-content">
                            <h4>Detalle de la acción</h4>
                            <p id="detalle"></p>
                        </div>
                        <div class="modal-footer">
                            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
                        </div>
                    </div>
				</div>
			</div>
		</div>
        <br>
		<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("grupos"); ?>">
		<?php require_once 'include/scripts.php' ?>
		<script type="text/javascript" src="/privado/js/bitacora.js"></script>
	</body>
</html>