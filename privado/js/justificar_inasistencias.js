// url peticiones ajax
var url_listar_alumnos_guia = "/privado/php/disciplina/listarAlumnos.php";
var url_listar_impuntualidades = "/privado/php/justificaciones/listar_inasistencia_clase.php";
var url_justificar = "/privado/php/justificaciones/justificar_inasistencia.php",
	url_justificar_todas = "/privado/php/justificaciones/justificarTodasInasistenciasClases.php",
	url_justificar_todas_ITR = "/privado/php/justificaciones/justificarInasistenciasClasesITR.php",
	url_eliminar = "/privado/php/justificaciones/eliminar_inasistencia.php";

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

});
// mostrar informacion del alumno selecionado
function mostrar(){
	codigo_alumno = $("#alumno").val().substring(0,8);
	$.ajax({
	data:{	             
	id: codigo_alumno,
	inicio : $("#date_inicio").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),
	fin : $("#date_fin").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),	 				
	},
	type:"POST",
	url: url_listar_impuntualidades
  }).done(function(data,textStatus,jqXHR){
	
	// obtiene la clave lista del json data
	var lista = data.lista;
	$(".table").html("")
	

	// genera el cuerpo de la tabla
	$.each(lista, function(ind, elem){
	
			$('<tr class="ids_inasistencias" id="'+elem.id+'">'+                      
			  '<td>'+elem.codigo+'</td>'+
			  '<td>'+elem.nombre+'</td>'+
			  '<td>'+elem.espe+'</td>'+
			  '<td>'+elem.grado+'</td>'+
			  '<td>'+elem.fecha+'</td>'+
			  '<td>'+elem.bloque+'</td>'+
			  '<td>'+elem.materia+'</td>'+
			  '<td><a onclick="abrirObservacion('+elem.id+')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Justificar"><i  class="material-icons">check_circle</i></a> <a onclick="eliminar('+elem.id+')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Eliminar"><i  class="material-icons">clear</i></a> </td>'+
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
function abrirObservacion(pk){
	selectedPK = pk;
	$('.modal').modal();
	$('.modal').modal('open');	
}

function justificar_inasistencia(){
	justificar(selectedPK);
}
// justificar inasistencia
function justificar(pk){
	//peticion post
  	$.post(
		url_justificar,
		{
	  	id: pk,	  	
	  	token: $("#dpToken").val(),
	  	observacion: $("#observacion_text").val(),
		},function(resp){ 
	  	swal("Inasistencia justificada.");
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


function justificarTodas(){	
 codigo_alumno = $("#alumno").val().substring(0,8);
  // obtiene todos los id
  var items = $(".ids_inasistencias");
  // obtiene la cantidad
  var size = $(".ids_inasistencias").size();
  var arrayID = [];
  // recorre todos los items
  for (var i=0; i<size; i++) {      
        arrayID[i]=$(items[i]).attr("id");            
    }
  var items_json = JSON.stringify(arrayID);
  //peticion post
  	$.post(
		url_justificar_todas,
		{	  		  
	  	token: $("#dpToken").val(),
	  	items:items_json,
	  	codigo: codigo_alumno,
	  	observacion: $("#observacion_text").val(),	  	
			inicio : $("#date_inicio").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),
			fin : $("#date_fin").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),	 				
		},function(resp){ 
	  	swal("Inasistencias Justificadas.");
	  	mostrar();
	});
}
function justificarTodasITR(){	
 codigo_alumno = $("#alumno").val().substring(0,8);
  // obtiene todos los id
  var items = $(".ids_inasistencias");
  // obtiene la cantidad
  var size = $(".ids_inasistencias").size();
  var arrayID = [];
  // recorre todos los items
  for (var i=0; i<size; i++) {      
        arrayID[i]=$(items[i]).attr("id");            
    }
  var items_json = JSON.stringify(arrayID);
  //peticion post
  	$.post(
		url_justificar_todas_ITR,
		{	  		  
	  	token: $("#dpToken").val(),
	  	items:items_json,
	  	codigo: codigo_alumno,
	  	observacion: $("#observacion_text").val(),	  	
		},function(resp){ 
	  	swal("Inasistencias Justificadas.");
	  	mostrar();
	});
}