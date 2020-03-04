
// url peticiones ajax
var url_listar_alumnos_guia = "/privado/php/disciplina/listarAlumnos.php";
var url_listar_materias = "/privado/php/justificar_bloques/obtener_asignaturas.php";
var url_guardar_bloques = "/privado/php/justificar_bloques/guardarBloquesJustificados.php";

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
	$('select').material_select();

});
// mostrar informacion del alumno selecionado
function mostrar(){

	codigo_alumno = $("#alumno").val().substring(0,8);
	$.ajax({
	data:{	             
	id: codigo_alumno,
	tipo: $("#select_tipo").val(),	
	},
	type:"POST",
	url: url_listar_materias
  }).done(function(data,textStatus,jqXHR){
	
	// obtiene la clave lista del json data
	var lista = data.lista;
	

	// genera el cuerpo de la tabla
	$.each(lista, function(ind, elem){
	// obtiene la clave lista del json data
		var lista = data.lista;
		$("#select_materias").html("");
		
		// si es necesario actualiza la cantidad de paginas del paginador

		// genera el cuerpo de la tabla
		$.each(lista, function (i, item) {			

			$('#select_materias').append($('<option>', { 
				value: item.id,
				text : item.nombre 
			}));
			$('select').material_select();
		});

	});     
  }).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

  });
}

function guardar_bloques(){

	$.ajaxSetup({async: false});
	for (var i = 0; i < $("#select_materias").val().length; i++) {		
		$.post(
			url_guardar_bloques,
			{
				id: $("#alumno").val().substring(0,8),
				id_horario: $("#select_materias").val()[i],				
			},function(resp){							
			});
	}
	swal('Éxito',"Jutificacion Guardada Exitosamente.",'info');
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