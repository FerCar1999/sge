var url_listar_alumnos = "/privado/php/disciplina/listarAlumnos.php",
url_asignar_ausencia = "/privado/php/ausencias/agregar.php",
url_listar="/privado/php/ausencias/listar.php",
url_eliminar="/privado/php/ausencias/eliminar.php";

var url_listar_materias = "/privado/php/justificar_bloques/obtener_asignaturas.php";
var url_guardar_bloques = "/privado/php/justificar_bloques/guardarBloquesJustificados.php";

var pk_alumno;
var codigo_alumno= 0;
var selectedPK;

var autocompleteAlumnos = {};

var codigo_alumno= 0;

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
	obtenerAlumnos();	
	$('select').material_select();

	$('input.autocomplete').autocomplete({
		data: autocompleteAlumnos
		//data: autocompleteAlumnos
	});
	cargar_ausentes();
});
function cargar_ausentes(){
	$.ajax({
		data:{},
		type:"POST",
		url: url_listar
	}).done(function(data,textStatus,jqXHR){
		
		// obtiene la clave lista del json data
		var lista = data.lista;
		$("#table").html("")
		
		// si es necesario actualiza la cantidad de paginas del paginador	
		// genera el cuerpo de la tabla
		$.each(lista, function(ind, elem){	
			$('<tr id="+elem.id+">'+                      
				'<td>'+elem.codigo+'</td>'+
				'<td>'+elem.nombre+'</td>'+
				'<td>'+elem.grado+'</td>'+
				'<td>'+elem.espe+'</td>'+ 
				'<td>'+elem.inicio+'</td>'+ 
				'<td>'+elem.fin+'</td>'+ 
				'<td>'+elem.permiso+'</td>'+ 			
				'<td>  <a onclick="eliminar('+elem.id+')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Remover"><i  class="material-icons">delete</i></a> <a onclick="swal(\''+elem.motivo+'\');" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Justificar"><i class="material-icons">gavel</i></a></td>'+
				'</tr>').appendTo($("#tablePermi"));
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
			id:pk,													
			token: $("#dpToken").val(),				
		},function(resp){								
			location.reload();			
			
		});
}
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

$('#asginarAusencia').click(function(e){
	$.post(
		url_asignar_ausencia,
		{
			pk_alumno:$("#alumno").val().substring(0,8),									
			pk_motivo:$("#motivo_text").val(),
			permiso:$("#select_permiso").val(),
			token: $("#dpToken").val(),
			inicio : $("#date_inicio").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),
			fin : $("#date_fin").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),	 			
		},function(resp){								
			swal('Éxito',"Permiso Inasistencia Guardado Exitosamente.",'info');
			
		});
});

// mostrar informacion del alumno selecionado
function mostrar(){

	codigo_alumno = $("#alumno").val().substring(0,8);
	$.ajax({
	data:{	             
	id: codigo_alumno,
	tipo: $("#select_tipo").val(),	
	fecha : $("#fecha_inasistencia_clase").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),
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
  })
}

function guardar_bloques(){

	$.ajaxSetup({async: false});
	for (var i = 0; i < $("#select_materias").val().length; i++) {		
		$.post(
			url_guardar_bloques,
			{
				id: $("#alumno").val().substring(0,8),
				id_horario: $("#select_materias").val()[i],
				fecha : $("#fecha_inasistencia_clase").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),				
			},function(resp){							
			});
	}
	swal('Éxito',"Permiso Inasistencia Guardado Exitosamente.",'info');
}
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

