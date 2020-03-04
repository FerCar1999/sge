// url peticiones ajax
var url_listar_alumnos_guia = "/privado/php/disciplina/listarAlumnos.php";
var url_listar_inasistencias = "/privado/php/inasistencias_totales/listar_inasistencias.php";
var url_justificar = "/privado/php/justificaciones/justificar_inasistencia.php",
	url_eliminar = "/privado/php/inasistencias_totales/eliminar_inasistencia.php";

// datos de los alumnos
var autocompleteAlumnos = {};
var pk_alumno;
var codigo_alumno= 0;
var selectedPK;
$(document).ready(function () {
	obtenerAlumnos();	
	
	$('input.autocomplete').autocomplete({
	    data: autocompleteAlumnos
	    //data: autocompleteAlumnos
	});

});
// mostrar informacion del alumno selecionado
function mostrar(){
	codigo_alumno = $("#alumno").val().substring(0,8);
	$.ajax({
	data:{	             
	id: codigo_alumno,	
	},
	type:"POST",
	url: url_listar_inasistencias
  }).done(function(data,textStatus,jqXHR){
	
	// obtiene la clave lista del json data
	var lista = data.lista;
	$(".table").html("")
	

	// genera el cuerpo de la tabla
	$.each(lista, function(ind, elem){
	
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.codigo+'</td>'+
			  '<td>'+elem.nombre+'</td>'+
			  '<td>'+elem.espe+'</td>'+
			  '<td>'+elem.grado+'</td>'+
			  '<td>'+elem.fecha+'</td>'+
			  '<td> <a onclick="eliminar('+elem.id+')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Eliminar"><i  class="material-icons">clear</i></a> </td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
	});     
  }).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

  });
}
function eliminar(pk){
	$.post(
		url_eliminar,
		{
	  	id: pk,	  	
	  	token: $("#dpToken").val(),	  	
		},function(resp){ 
	  		swal("Inasistencia eliminada.");
	  	mostrar();
	});
}

// buscardor para obtener alumnos
function obtenerAlumnos() {
	$.ajaxSetup({async: false});
	$.ajax({
		type:'POST',
		url:url_listar_alumnos_guia,
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