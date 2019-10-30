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
                <span class="card-title">INGRESO AL SISTEMA ESTUDIANTES</span>
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
            <div class="row">
              <div class="col s12">
                <table class="striped">
                <thead>
                  <tr>
                    <th>FECHA INGRESO</th>
                    
                  </tr>
                </thead>
                <tbody class="table">        
                </tbody>
              </table>
              </div>
              <div class="col s12 right-align">
                  <br>                  
                  <a class="btn-floating btn-large waves-effect waves-light amber darken-4 tooltipped" data-position="left" data-delay="50" data-tooltip="Mostrar" id="btn-mostrar">
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
  <script type="text/javascript" src="/privado/js/ingreso_estudiantes.js"></script>
</body>
</html>