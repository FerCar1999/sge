let url 	= "/api/v1/personal";

$(document).ready(function() {
	setupCalendar();
  $('select').material_select(); 		
	obtenerMaestros();
});

function setupCalendar(){

	

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
	$('.datepicker').pickadate('picker').set('select', new Date(), { format: 'yyyy/mm/dd' }).trigger("change");    	
}

$("#fecha").change(function() {    
  obtenerMaestros();
});

function setupDataTable() {	
	$('.datatable').DataTable({
		"language": {
									"sProcessing":     "Procesando...",
									"sLengthMenu":     "Mostrar _MENU_ registros",
									"sZeroRecords":    "No se encontraron resultados",
									"sEmptyTable":     "Ningún dato disponible en esta tabla",
									"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
									"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
									"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
									"sInfoPostFix":    "",
									"sSearch":         "Buscar:",
									"sUrl":            "",
									"sInfoThousands":  ",",
									"sLoadingRecords": "Cargando...",
									"oPaginate": {
											"sFirst":    "Primero",
											"sLast":     "Último",
											"sNext":     "Siguiente",
											"sPrevious": "Anterior"
									},
									"oAria": {
											"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
											"sSortDescending": ": Activar para ordenar la columna de manera descendente"
									}
							}
	});
	$("select").val('10'); //seleccionar valor por defecto del select
	$('select').addClass("browser-default"); //agregar una clase de materializecss de esta forma ya no se pierde el select de numero de registros.
	$('select').material_select(); //inicializar el select de materialize
}

function obtenerMaestros(){	
	$.ajax({
		data:{			
		action: 1,
		date : $("#fecha").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),
	},
	type:"POST",
	url: url
  }).done(function(response){
	
	// obtiene la clave lista del json data	
	$("#table").html("");	
	$("#tittle").text("CLASES SIN MARCAR ASISTENCIA ("+response.data.length+")");
	// genera el cuerpo de la tabla
	var table = $('.datatable').DataTable();
	table.destroy();
	$.each(response.data, function(ind, elem){	
		if (elem.codigo != null) {
			$('<tr>'+                      
			  '<td>'+elem.codigo+'</td>'+
			  '<td>'+elem.nombre+'</td>'+
				'<td>'+elem.hora+'</td>'+
				'<td>'+elem.materia+'</td>'+
				'<td>'+elem.grado+'</td>'+
				'<td>'+elem.local+'</td>'+
			'</tr>').appendTo($("#table"));				
		}	
		console.log("a");
			
	}); 	
	setupDataTable()  
  });	
}