// variables para almacenar las url
var url_listar_alumnos_guia = "/privado/php/disciplina/listarAlumnos.php",
	url_justificar= "/privado/php/justificaciones/justificar.php",
	url_eliminar= "/privado/php/justificaciones/eliminarLLegadaTardeSalon.php",
	url_listar_impuntualidades = "/privado/php/justificaciones/listar.php";

// datos de los alumnos
var autocompleteAlumnos = {};
var pk_alumno;
var codigo_alumno= 0;

$(document).ready(function () {
	obtenerAlumnos();	
	$('select').material_select();
	$('input.autocomplete').autocomplete({
	    data: autocompleteAlumnos
	    //data: autocompleteAlumnos
	});

});
// al selecionar un alumno en el search
function mostrar(){
	codigo_alumno = $("#alumno").val().substring(0,8);
	$.ajax({
	data:{	             
	id: codigo_alumno,
	tipo: $("#select_tipo").val(),
	},
	type:"POST",
	url: url_listar_impuntualidades
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
			  '<td>'+elem.materia+'</td>'+
			  '<td><a onclick="justificar('+elem.id+')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Justificar"><i  class="material-icons">check_circle</i></a> <a onclick="eliminar('+elem.id+')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Eliminar"><i  class="material-icons">delete</i></a> </td>'+
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
	  	swal("Impuntualidad Eliminada.");
	  	mostrar();
	});
}
// justiciar inasistencia
function justificar(pk){
	//peticion post
  	$.post(
		url_justificar,		
		{
	  	id: pk,	  	
	  	token: $("#dpToken").val(),
		},function(resp){ 
	  	swal("Impuntualidad justificada.");
	  	mostrar();
	});
}
// obtiene los alumnos de la db
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