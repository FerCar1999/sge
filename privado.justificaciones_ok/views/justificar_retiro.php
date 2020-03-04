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
            <span class="card-title"><p class="center-align">JUSTIFICAR INASISTENCIAS POR GRUPO</p></span>
            <div class="row">                
              <div class="col s12">
                <div class="input-field col s12" id="divSelect">
                  <i class="material-icons prefix">assignment_id</i>
                  <select name="" id="select_nivel">
                    <option value="0" disabled selected>Seleccione el nivel</option>
                    <?php 
                    require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
                    $sql = "select id_nivel,nombre from niveles where estado='Activo'";
                    $params = array("");

                    $data = Database::getRows($sql, $params);
                    foreach($data as $row)
                    {                                 
                      echo '<option value="'.$row["id_nivel"].'">'.$row["nombre"].'</option>';
                    }
                    ?>
                  </select>
                  <label>Nivel</label>
                </div>
                <div class="input-field col s12">
                  <i class="material-icons prefix">work</i>
                  <select name="" id="select_grado">
                    <option value="">No Disponible</option>
                  </select>
                  <label>Grados</label>
                </div>
                <div class="input-field col s12">
                  <i class="material-icons prefix">work</i>
                  <select name="" id="select_secciones">
                    <option value="">No Disponible</option>
                  </select>
                  <label>Sección</label>
                </div>
                <div class="input-field col s12">
                  <i class="material-icons prefix">work</i>
                  <select name="" id="select_especialidad">
                    <option value="">No Disponible</option>
                  </select>
                  <label>Especialidad</label>
                </div>
                <div class="input-field col s12">
                  <i class="material-icons prefix">local_library</i>
                  <select name="" id="select_grupos">
                    <option value="">No Disponible</option>
                  </select>
                  <label>Grupo</label>
                </div>
                <div class="input-field col s12">
                  <i class="material-icons prefix">timer</i>
                  <input id="fecha_retiro" type="date" class="datepicker">
                  <label for="fecha_retiro">FECHA</label>
                </div>
              </div>
              <div class="col s12 right-align">
                <br>
                <a onclick="justificar();" class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Justificar">
                  <i class="material-icons">add</i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="modal1" class="modal">
  <div class="modal-content">

    <div class="row" id="observacionDiv">

      <div class="col s12 ">
        <br>
        <h5 class="text-center">ASIGNE LA OBSERVACIÓN</h5>  
        <div class="input-field col s12">
          <i class="material-icons prefix">mode_edit</i>
          <textarea id="observacion_text" class="materialize-textarea" row="100"></textarea>
          <label for="observacion_text">Observación</label>
        </div>

      </div>
    </div>
  </div>
  <div class="modal-footer">
    <a href="#!" onclick="justificar_inasistencia();" class=" modal-action modal-close waves-effect waves-green btn-flat">Sin Observacion</a>
    <a href="#!" onclick="justificar_inasistencia();" class=" modal-action modal-close waves-effect waves-green btn-flat">OK</a>
  </div>
</div>    
<input type="hidden" id="access" value="<?php require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/relog.php"); comprobar_permiso("justificaciones"); ?>">

<?php require_once 'include/scripts.php' ?>
<script src="/privado/js/justificarRetiro.js"></script>
</body>
</html>