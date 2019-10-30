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
            <div class="row">
              <div class="col s12 text-center">
                <span class="card-title">JUSTIFICACIONES</span>
              </div>                          
              <div class="row">
                <form action="">
                  <div class="input-field col s12 search-wrapper">
                    <i class="material-icons prefix">person</i>
                    <input id="alumno" type="text" class="autocomplete">
                    <label for="alumno">Carnet o nombre del estudiante</label>
                  </div>
                </form>                          
              </div>
              <div class="row">
                <div class="input-field col s6">
                  <i class="material-icons prefix">timer</i>
                  <input id="date_inicio" type="date" value="<?php echo date('Y-m-d')?>" class="datepicker">
                  <label for="date_inicio">Inicio</label>
                </div>
                <div class="input-field col s6">
                  <i class="material-icons prefix">timer</i>
                  <input id="date_fin" type="date" value="<?php echo date('Y-m-d')?>"class="datepicker">
                  <label for="date_fin">Fin</label>
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12">
                  <br>
                  <i class="material-icons prefix">mode_edit</i>
                  <select multiple id="select_materias">
                    <option value="" disabled selected>Selecionar Clases</option>           
                  </select>
                  <br>
                  <label>Clases</label>
                </div>
                <div class="row">
                  <div class="col m4 s12">
                    <br>
                    <a class="btn amber darken-4 right col s12" id="btn-justificar">
                      Justificar
                      <i class="right material-icons">list</i>
                    </a>
                  </div>
                  <div class="col m4 s12">
                    <br>
                    <a class="btn amber darken-4 right col s12" id="btn-justificar-todas">
                      Justificar Todas
                      <i class="right material-icons">list</i>
                    </a>
                  </div>
                  <div class="col m4 s12">
                    <br>
                    <a class="btn amber darken-4 right col s12" id="btn-justificar-itr">
                      Justificar ITR
                      <i class="right material-icons">list</i>
                    </a>
                  </div>
                  <div class="col m4 s12">
                    <br>
                    <a class="btn amber darken-4 right col s12" id="btn-justificar-itr-todas">
                      Justificar Todas ITR
                      <i class="right material-icons">list</i>
                    </a>
                  </div>
                  <div class="col m4 s12 ">
                    <br>
                    <a class="btn amber darken-4  col s12" id="btn-eliminar">
                      Eliminar 
                      <i class="right material-icons">list</i>
                    </a>
                  </div>
                  <div class="col m4 s12">
                    <br>
                    <a class="btn amber darken-4  col s12" id="btn-eliminar-todas">
                      Eliminar Todas 
                      <i class="right material-icons">list</i>
                    </a>
                  </div>
                  <div class="col m4  s12 offset-m2 ">
                    <br>
                    <a class="btn amber darken-4  col s12" id="btn-suspendidos">
                      Justificar Suspendido 
                      <i class="right material-icons">list</i>
                    </a>
                  </div>
                  <div class="col m4 s12">
                    <br>
                    <a class="btn amber darken-4  col s12" id="btn-suspendidos-todas">
                      Just. Suspendido Todas
                      <i class="right material-icons">list</i>
                    </a>
                  </div>
                </div>
              </div>

              <div>
              </div>
            </div>
            

          </div>  
        </div>
      </div>
    </div>

  </div>
  <input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); ?>">
  <?php require_once 'include/scripts.php' ?>
  <script type="text/javascript" src="/privado/src/js/justificacionIndividual.js"></script>
</body>
</html>