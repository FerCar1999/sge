var url_pasar_asistencia = "/privado/php/asistencias/pasar_asistencia.php";
var url_quitar_asistencia = "/privado/php/asistencias/quitar_asistencia.php";
var url_quitar_llegada_tarde="/privado/php/asistencias/quitar_llegada_tarde.php";
var url_asignar_llegada_tarde = "/privado/php/asistencias/llegada_tarde.php";
var url_habilitar_referida = "/privado/php/asistencias_diferidas/habilitar_asistencia.php";
var url_asignar_inasistencia = "/privado/php/asistencias/asignar_inasistencia.php";
var url_quitar_inasistencia = "/privado/php/asistencias/quitar_inasistencias.php";

$('#guardar_asistencia').click(function(e){
  Materialize.toast('Asistencia Guardada Exitosamente', 3000, 'rounded');
  // obtiene todos los checkbox
  var checkbox = $(".access_check");
  // obtiene la cantidad
  var size = $(".access_check").size();

  var arrayID = [];
  var QuitarArrayID = [];
  
  var pk_horario = 0;
  // recorre todos los checkbox
  for (var i=0; i<size; i++) {
      // obtiene el id de los que estan selecionados y los gurda
      if($(checkbox[i]).parent().children().prop('checked')){              
        if($(checkbox[i]).attr("guardado")=="no"){
          arrayID[i]=$(checkbox[i]).attr("id");      
          pk_horario = $(checkbox[i]).attr("id_horario");

        }
      }
      else if($(checkbox[i]).attr("guardado")=="si"){
          QuitarArrayID[i] =$(checkbox[i]).attr("id");      
          pk_horario = $(checkbox[i]).attr("id_horario");          
        }     
    }
    var asistencia_json = JSON.stringify(arrayID);
    $.post(
      url_pasar_asistencia,
      {
        id_horario:pk_horario, 
        contenido:$('#contenido').val(),
        observacion:$('#observacion').val() ,     
        asistencia:asistencia_json,
      },function(resp){
        
      });  
      var quitar_asistencia_json = JSON.stringify(QuitarArrayID);
    $.post(
      url_quitar_asistencia,
      {
        id_horario:pk_horario,
        inicio_del:$('#inicio').val(),
        fin_del:$('#fin').val() ,     
        asistencia:quitar_asistencia_json,
      },function(resp){
        
      });  
  // muestra la notificacion
  guardar_impuntualidad();
  guardar_inasistencias();
  
  location.reload();
});
function guardar_inasistencias(){
  
  // obtiene todos los checkbox
  var checkbox = $(".access_check");
  // obtiene la cantidad
  var size = $(".access_check").size();

  var arrayID = [];
  var QuitarArrayID = [];
  
  var pk_horario = 0;
  // recorre todos los checkbox
  for (var i=0; i<size; i++) {
      // obtiene el id de los que estan selecionados y los gurda
      if($(checkbox[i]).parent().children().prop('checked')){              
        if($(checkbox[i]).attr("guardado")=="no"){
          arrayID[i]=$(checkbox[i]).attr("id");      
          pk_horario = $(checkbox[i]).attr("id_horario");             
        }
      }
      else {
          QuitarArrayID[i] =$(checkbox[i]).attr("id");      
          pk_horario = $(checkbox[i]).attr("id_horario");             
        }     
    }

   var borrar_inacistencia_json = JSON.stringify(arrayID);
    $.post(
      url_quitar_inasistencia,
      {
        id_horario:pk_horario, 
        inicio_del:$('#inicio').val(),
        fin_del:$('#fin').val() ,     
        asistencia:borrar_inacistencia_json,
      },function(resp){
        
      });  
      var colocar_inasistencia_json = JSON.stringify(QuitarArrayID);
    $.post(
      url_asignar_inasistencia,
      {
        id_horario:pk_horario,        
        asistencia:colocar_inasistencia_json,
      },function(resp){
        
      }); 
}
function guardar_impuntualidad(){
   // obtiene todos los checkbox
  var checkbox = $(".access_check_tarde");
  // obtiene la cantidad
  var size = $(".access_check_tarde").size();

  var arrayID = [];
  var QuitarArrayID = [];
  
  var pk_horario = 0;
  // recorre todos los checkbox
  for (var i=0; i<size; i++) {
      // obtiene el id de los que estan selecionados y los gurda
      if($(checkbox[i]).parent().children().prop('checked')){              
        if($(checkbox[i]).attr("guardado")=="no"){
          arrayID[i]=$(checkbox[i]).attr("pk");      
          pk_horario = $(checkbox[i]).attr("id_horario");
        }
      }
      else if($(checkbox[i]).attr("guardado")=="si"){
          QuitarArrayID[i] =$(checkbox[i]).attr("pk");      
          pk_horario = $(checkbox[i]).attr("id_horario");          
        }     
    }
   var llegadas_tarde_json = JSON.stringify(arrayID);
    $.post(
      url_asignar_llegada_tarde,
      {
        id_horario:pk_horario,         
        asistencia:llegadas_tarde_json,
      },function(resp){
        
      });  

    var quitar_llegada_tarde_json = JSON.stringify(QuitarArrayID);
    $.post(
      url_quitar_llegada_tarde,
      {
        id_horario:pk_horario,
        inicio_del:$('#inicio').val(),
        fin_del:$('#fin').val() ,     
        asistencia:quitar_llegada_tarde_json,
      },function(resp){
        
      });  
}

function habilitar_diferida(pk){
    $.post(
      url_habilitar_referida,
      {
        id:pk,        
      },function(resp){window.location = "/asistencia";});
}
