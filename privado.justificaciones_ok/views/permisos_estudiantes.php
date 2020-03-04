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
            <ul class="tabs">
              <li class="tab col s2"><a class="active" href="#dias">Dias</a></li>
              <li class="tab col s2"><a  href="#clases">Clases</a></li>
            </ul>
            <div class="row">
              <div class="col s12 text-center">
                <span class="card-title">PERMISO PREVIO INASISTENCIA</span>
              </div>                          
              <div class="row">
                <form action="">
                  <div class="input-field col s12 search-wrapper">
                    <i class="material-icons prefix">person</i>
                    <input id="alumno" type="text" class="autocomplete">
                    <label for="alumno">Carnet o nombre del estudiante</label>
                  </div>
                </form>
                <div class="center-align"><div class="col s12" id="noHayCodigos"></div>
              </div>                          
              <div>
              </div>
            </div>
            <div id="dias">
              <div class="row">
                <div class="input-field col s6">
                  <i class="material-icons prefix">timer</i>
                  <input id="date_inicio" type="date" class="datepicker">
                  <label for="date_inicio">Inicio</label>
                </div>
                <div class="input-field col s6">
                  <i class="material-icons prefix">timer</i>
                  <input id="date_fin" type="date" class="datepicker">
                  <label for="date_fin">Fin</label>
                </div>
                <div class="input-field col s12">
                  <i class="material-icons prefix">gavel</i>
                  <select id="select_permiso" >                                       
                    <option value="Permiso Externo">Permiso Externo</option>
                    <option value="Permiso Institucional">Permiso Institucional</option>                                        
                    <option value="Suspendido">Suspendido</option>                                        
                  </select>
                  <label>Permiso</label>
                </div>
              </div>
              <div class="input-field col s12">
                <i class="material-icons prefix">mode_edit</i>
                <textarea id="motivo_text" class="materialize-textarea" row="100"></textarea>
                <label for="motivo_text">Motivo</label>
              </div>
              <div class="col s12 right-align">
                <br>
                <a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Agregar Ausencia Justificada" id="asginarAusencia">
                  <i class="material-icons">add</i>
                </a>                                    
              </div>    
            </div>
            <div id="clases">
              <div class="input-field col s12">
                  <i class="material-icons prefix">timer</i>
                  <input id="fecha_inasistencia_clase" type="date" class="datepicker">
                  <label for="fecha_inasistencia_clase">Fecha</label>
                </div>
              <div class="input-field col s12">
                <i class="material-icons prefix">gavel</i>
                <select id="select_tipo" >            <option value="Academicas">Academicas</option>
                <option value="Tecnicas">Tecnicas</option>                    
                </select>
                <label>Clases</label>
              </div>

              <div class="input-field col s12">
                <br>
                <i class="material-icons prefix">mode_edit</i>
                <select multiple id="select_materias">
                  <option value="" disabled selected>Selecionar Clases</option>           
                </select>
                <br>
                <label>Clases</label>
              </div>

              <div class="col s12 right-align">
                <br>
                <a onclick="guardar_bloques();" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Guardar">
                  <i class="material-icons">add</i>
                </a>
                <a onclick="mostrar();" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Mostrar Clases">
                  <i class="material-icons">search</i>
                </a>
              </div>
            </div>

          </div>  
        </div>
      </div>r
    </div>

  </div>
  <input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); ?>">
  <?php require_once 'include/scripts.php' ?>
  <script type="text/javascript" src="/privado/js/ausentes.js"></script>
</body>
</html>