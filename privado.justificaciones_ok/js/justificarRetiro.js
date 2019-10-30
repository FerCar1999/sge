const showGrados = "/privado/php/grados/gradosListar.php",
showSecciones = "/privado/php/secciones/seccionesListar.php",
showEspecialidad="/privado/php/especialidades/especialidadesListar.php",
showGrupos="/privado/php/grupos/gruposListar.php",
justificarRetiro="/privado/php/JustificarRetiros/justificar.php";

let pkNivel = "";
let pkGrado = "";
$(document).ready(function () {
  // carga los calendarios con textos en español
  $('.datepicker').pickadate({
    // Strings and translations
    monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sáb'],

    // Buttons
    today: 'Hoy',
    clear: 'Limpiar',
    close: 'Cerrar',

    // Accessibility labels
    labelMonthNext: 'Adelante',
    labelMonthPrev: 'Atras',
    labelMonthSelect: 'Selecciona un mes',
    labelYearSelect: 'Selecciona un año',

    // Formats
    format: 'dd !de mmmm !de yyyy',
    formatSubmit: 'yyyy/mm/dd',
    
    selectMonths: true,
    selectYears: 100,

    closeOnSelect: true
  });
  $('select').material_select();  
  pkNivel = $( "#selectGrado").val();  
});
function cargarGrupos(){
    $.ajax({         
    data:{
      tipo:2,           
    },
    type:"POST",
    url:showGrupos
  }).done(function(data,textStatus,jqXHR){

    // obtiene la clave lista del json data
    var lista = data.lista;
    $("#select_grupos").html("");
    
    // si es necesario actualiza la cantidad de paginas del paginador

    // genera el cuerpo de la tabla
    $.each(lista, function (i, item) {
      tipo=item.tipo;
      $('#select_grupos').append($('<option>', { 
        value: item.id,
        text : item.nombre,        
      }));
      $('select').material_select();
    });
  }).fail(function(jqXHR,textStatus,textError){
    //alert("Error al realizar la peticion dame".textError);
  });
}
function cargarEspecialidades(){
    $.ajax({         
    data:{            
    },
    type:"POST",
    url:showEspecialidad
  }).done(function(data,textStatus,jqXHR){

    // obtiene la clave lista del json data
    var lista = data.lista;
    $("#select_especialidad").html("");
    
    // si es necesario actualiza la cantidad de paginas del paginador

    // genera el cuerpo de la tabla
    $.each(lista, function (i, item) {
      tipo=item.tipo;
      $('#select_especialidad').append($('<option>', { 
        value: item.id,
        text : item.nombre,        
      }));
      $('select').material_select();
    });
  }).fail(function(jqXHR,textStatus,textError){
    //alert("Error al realizar la peticion dame".textError);
  });
}
function cargarSecciones(){
    $.ajax({         
    data:{            
    },
    type:"POST",
    url:showSecciones
  }).done(function(data,textStatus,jqXHR){

    // obtiene la clave lista del json data
    var lista = data.lista;
    $("#select_secciones").html("");
    
    // si es necesario actualiza la cantidad de paginas del paginador

    // genera el cuerpo de la tabla
    $.each(lista, function (i, item) {
      tipo=item.tipo;
      $('#select_secciones').append($('<option>', { 
        value: item.id,
        text : item.nombre,        
      }));
      $('select').material_select();
    });
  }).fail(function(jqXHR,textStatus,textError){
    //alert("Error al realizar la peticion dame".textError);
  });
}
function cargarGrados(){
 $.ajax({         
    data:{      
      busqueda:pkNivel   
    },
    type:"POST",
    url:showGrados
  }).done(function(data,textStatus,jqXHR){

    // obtiene la clave lista del json data
    var lista = data.lista;
    $("#select_grado").html("");
    
    // si es necesario actualiza la cantidad de paginas del paginador

    // genera el cuerpo de la tabla
    $.each(lista, function (i, item) {
      tipo=item.tipo;
      $('#select_grado').append($('<option>', { 
        value: item.id,
        text : item.nombre,        
      }));
      $('select').material_select();
    });
  }).fail(function(jqXHR,textStatus,textError){
    //alert("Error al realizar la peticion dame".textError);
  });
}
function justificar(){
  //peticion post
    $.post(
    justificarRetiro,
    {
      id_especialidad:$('#select_especialidad').val(),  
      id_grado: $('#select_grado').val(),  
      id_grupo: $("#select_grupos").val(),  
      id_secccion: $("#select_secciones").val(),  
      id_nivel:pkNivel,  
      fecha: $("#fecha_retiro").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),  
      token: $("#dpToken").val(),      
    },function(resp){ 
      swal("Alerta","Inasistencias Justificadas al grupo exitosamente","info");      
  });
}
// envento changue al cambiar el nivel
$( "#select_nivel" ).change(function() {  
    pkNivel = $( "#select_nivel").val();    
    cargarGrados();     
    if(pkNivel=="2"){      
      $("#select_secciones").prop('disabled', 'disabled');
      $( "#select_especialidad").removeAttr('disabled');    
      $( "#select_grupos").removeAttr('disabled');          

      cargarEspecialidades();
      cargarGrupos();
    }else{
      $( "#select_secciones").removeAttr('disabled');    
      $("#select_especialidad").prop('disabled', 'disabled');
      $("#select_grupos").prop('disabled', 'disabled');
      
      cargarSecciones();
    }
  });