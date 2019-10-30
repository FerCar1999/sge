let url_listar_alumnos = "/privado/php/disciplina/listarAlumnos.php";
let url_ingreso_alumno = "/privado/php/alumnos/ingreso.php";
let codigo_alumno= 0;
let autocompleteAlumnos = {};

$(document).ready(function () {    
  obtenerAlumnos();
  $('input.autocomplete').autocomplete({
    data: autocompleteAlumnos        
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
$("#btn-mostrar").click(function(e){
  mostrar();
});
// mostrar informacion del alumno selecionado
function mostrar(){

  codigo_alumno = $("#alumno").val().substring(0,8);
  $.ajax({
    data:{               
      codigo: codigo_alumno    
    },
    type:"POST",
    url: url_ingreso_alumno,
    dataType: "json",
  }).done(function(data,textStatus,jqXHR){    
    var lista = data.lista;
    $(".table").html("")
    // genera el cuerpo de la tabla
    $.each(lista, function(ind, elem){
      $('<tr>'+
        '<td>'+elem.fecha+'</td>'+        
        '</tr>').appendTo($(".table"));      
    });     
  })
}