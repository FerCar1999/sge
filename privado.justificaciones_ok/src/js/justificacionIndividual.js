let url_listar_alumnos = "/privado/php/disciplina/listarAlumnos.php";
let url_listar_inasistencias = "/privado/php/justificaciones/listar_inasistencia_clase.php";
let url_justificar = "/privado/php/justificaciones/justificar_inasistencia.php";
let url_justificar_ITR = "/privado/php/justificaciones/justificarInasistenciasClasesITR.php";
let url_justificar_suspendido = "/privado/php/justificaciones/JustificarSuspendido.php";
let url_eliminar = "/privado/php/justificaciones/eliminar_inasistencia.php";
let autocompleteAlumnos = {};
let codigo_alumno= 0;

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
        format: 'yyyy-mm-dd',
        formatSubmit: 'yyyy/mm/dd',
        
        selectMonths: true,
        selectYears: 100,

        closeOnSelect: true
      });
    obtenerAlumnos();   
    $('select').material_select();

    $('input.autocomplete').autocomplete({
      data: autocompleteAlumnos
        //data: autocompleteAlumnos
      });   
  });
function obtenerAlumnos() {
  $.ajaxSetup({async: false});
  $.ajax({
    type:'POST',
    url:url_listar_alumnos,
    data:{

    },
    dataType: "json",
    success: function(valores){
      var resp = eval(valores);
      $.each(resp, function(ind,elem) {
        var alumno = elem.alumno;
        var foto = elem.foto;
        autocompleteAlumnos[alumno] = foto;
      });
      return false;
    }
  });
  return false;   
}
$("#date_inicio").change(function() {    
  mostrarInasistencias();
});
$("#date_fin").change(function() {    
  mostrarInasistencias();
});
// mostrar inasistencias
function mostrarInasistencias(){
  codigo_alumno = $("#alumno").val().substring(0,8);
  $.ajax({
    data:{               
      id: codigo_alumno,
      inicio : $("#date_inicio").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),
      fin : $("#date_fin").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),                 
    },
    type:"POST",
    url: url_listar_inasistencias
  }).done(function(data,textStatus,jqXHR){
    var lista = data.lista;
    $("#select_materias").html("");            
    $.each(lista, function (i, item) {          

      let option = item.fecha +" - "+item.bloque+" - "+item.materia+" - "+item.estado;
      if(item.estado_inasistencia!= "") option += " - "+item.estado_inasistencia;
      $('#select_materias').append($('<option>', { 
        value: item.id,
        text : option
      }));
      $('select').material_select();
    });
    
  })
}
// justificar todas
function justificarTodas(){ 
  // select all options multiselect  
   selectAll();
  $.ajaxSetup({async: false});
  for (var i = 0; i < $("#select_materias").val().length; i++) {
    $.post(
      url_justificar,
      {
        id: $("#select_materias").val()[i],        
        token: $("#dpToken").val(),
      },function(resp){             
    });
  }
  swal('Éxito',"Inasistencias Guardadas Exitosamente.",'info');
}
// jutificar selecionadas
function justificarSelecionadas(){
  $.ajaxSetup({async: false});
  for (var i = 0; i < $("#select_materias").val().length; i++) {
    $.post(
      url_justificar,
      {
        id: $("#select_materias").val()[i],        
        token: $("#dpToken").val(),
      },function(resp){             
    });
  }
  swal('Éxito',"Inasistencias Guardadas Exitosamente.",'info');
}
// justificar ITR seleccionadas 
function justificarSelecionadasITR(){
  codigo_alumno = $("#alumno").val().substring(0,8);

  $.ajaxSetup({async: false});
  for (var i = 0; i < $("#select_materias").val().length; i++) {
    $.post(
      url_justificar_ITR,
      {
        item: $("#select_materias").val()[i],
        codigo: codigo_alumno,
        token: $("#dpToken").val(),
      },function(resp){             
    });
  }  
  swal('Éxito',"Inasistencias Guardadas Exitosamente.",'info');
}

// justificar ITR TODAS 
function justificarSelecionadasTodasITR(){
  codigo_alumno = $("#alumno").val().substring(0,8);
  // select all options multiselect  
  selectAll();
  $.ajaxSetup({async: false});
  for (var i = 0; i < $("#select_materias").val().length; i++) {
    $.post(
      url_justificar_ITR,
      {
        item: $("#select_materias").val()[i],
        codigo: codigo_alumno,
        token: $("#dpToken").val(),
      },function(resp){             
    });
  }  
  swal('Éxito',"Inasistencias Guardadas Exitosamente.",'info');
}
// eliminar inasistencias selecionadas
function eliminarInasistencia(){
  $.ajaxSetup({async: false});
  for (var i = 0; i < $("#select_materias").val().length; i++) {
    $.post(
      url_eliminar,
      {
        id: $("#select_materias").val()[i],
        token: $("#dpToken").val(),
      },function(resp){
    }); 
  }
  swal('Éxito',"Inasistencias Eliminadas Exitosamente.",'info');
}

// eliminar inasistencias todas
function eliminarInasistenciaTodas(){
  $.ajaxSetup({async: false});
  // select all options multiselect  
  selectAll();
  for (var i = 0; i < $("#select_materias").val().length; i++) {
    $.post(
      url_eliminar,
      {
        id: $("#select_materias").val()[i],
        token: $("#dpToken").val(),
      },function(resp){
    }); 
  }
  swal('Éxito',"Inasistencias Eliminadas Exitosamente.",'info');
}
function justificarSuspendidos(){
  $.ajaxSetup({async: false});
  for (var i = 0; i < $("#select_materias").val().length; i++) {
    $.post(
      url_justificar_suspendido,
      {
        item: $("#select_materias").val()[i],
        token: $("#dpToken").val(),
      },function(resp){
    }); 
  }
  swal('Éxito',"Inasistencias Eliminadas Exitosamente.",'info');
}
function justificarSuspendidosTodas(){
  $.ajaxSetup({async: false});
  // select all options multiselect  
  selectAll();
  for (var i = 0; i < $("#select_materias").val().length; i++) {
    $.post(
      url_justificar_suspendido,
      {
        item: $("#select_materias").val()[i],
        token: $("#dpToken").val(),
      },function(resp){
    }); 
  }
  swal('Éxito',"Inasistencias Eliminadas Exitosamente.",'info');
}
$("#btn-justificar-todas").click(function(e){
  justificarTodas();
});
$("#btn-justificar").click(function(e){
  justificarSelecionadas();
});
$("#btn-justificar-itr").click(function(e){
  justificarSelecionadasITR();
});
$("#btn-justificar-itr-todas").click(function(e){
  justificarSelecionadasTodasITR();
});
$("#btn-eliminar").click(function(e){
  eliminarInasistencia();
});
$("#btn-eliminar-todas").click(function(e){
  eliminarInasistenciaTodas();
});
$("#btn-suspendidos").click(function(e){
  justificarSuspendidos();
});
$("#btn-suspendidos-todas").click(function(e){
  justificarSuspendidosTodas();
});
function selectAll() {
    $('#select_materias option:not(:disabled)').not(':selected').prop('selected', true);
    $('.dropdown-content.multiple-select-dropdown input[type="checkbox"]:not(:checked)').not(':disabled').prop('checked', 'checked');
    $('.dropdown-content.multiple-select-dropdown input[type="checkbox"]:not(:checked)').not(':disabled').trigger('click');
    var values = $('.dropdown-content.multiple-select-dropdown input[type="checkbox"]:checked').not(':disabled').parent().map(function() {
        return $(this).text();
    }).get();
    $('#select_materias input.select-dropdown').val(values.join(', '));
    console.log($('select').val());
};
