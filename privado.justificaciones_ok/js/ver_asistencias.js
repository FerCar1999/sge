var url_listar_personal = "/privado/php/personal/personal_listar.php",
	url_ver_materias = "/privado/php/ver_asistencia_administrador/obtener_asignaturas.php",
	url_ver_asistencia="/privado/php/asistencias_diferidas/cargarListaAdministrador.php";

var ver_estado_personal = "Activo",pk_personal;
function get_data_callback_personal(){
	$.ajax({
		data:{
			limit: itemsPorPagina,              
			offset: desde,
			busqueda: $.trim($('input#buscar_personal').val()),
			estado: ver_estado_personal                  
		},
		type:"POST",
		url: url_listar_personal
	}).done(function(data,textStatus,jqXHR){

	// obtiene la clave lista del json data
	var lista = data.lista;
	$(".table").html("")
	
	// si es necesario actualiza la cantidad de paginas del paginador
	if(pagina==0){
		creaPaginador(data.cantidad);
	}

	// linea para llamar a las diferidas'<td><a onclick="ver_personal('+elem.id+',\''+elem.nombre+'\',\''+elem.apellido+'\',\''+elem.codigo+'\',\''+elem.correo+'\',\''+elem.foto+'\','+elem.permiso+')" class="btn-floating green" ><i  class="material-icons">edit</i></a> <a onclick="eliminar_personal('+elem.id+',\''+elem.nombre+'\',\''+ elem.apellido +'\')" class="btn-floating green"><i class="material-icons">delete</i></a> <a onclick="asistencias_diferidas('+elem.id+',\''+elem.nombre+'\',\''+ elem.apellido +'\')" class="btn-floating green"><i class="material-icons">alarm</i></a></td>'+
	// genera el cuerpo de la tabla
	$.each(lista, function(ind, elem){
		if (ver_estado_personal == 'Activo') {
			$('<tr id="+elem.id+">'+                      
				'<td>'+elem.codigo+'</td>'+
				'<td>'+elem.nombre+'</td>'+
				'<td>'+elem.apellido+'</td>'+
				'<td>'+elem.correo+'</td>'+
				'<td> <a onclick="ver_personal('+elem.id+',\''+elem.nombre+'\',\''+ elem.apellido +'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Asistencia diferida"><i class="material-icons">beenhere</i></a></td>'+
				'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}
	});     
}).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la petición dame".textError);

});
}

$('#buscar_personal').keyup(function() {  
	personal_init();
});

$(document).ready(function(){
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 10, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_personal); 
	personal_init();
	$('select').material_select();
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

// funcion para reicinar todo
function personal_init(){
	cargaPagina(0);
}

function ver_personal(pk,nombre,apellidos){
	pk_personal= pk;
	$("#id_profesor").text("Asistencia: "+nombre+", "+apellidos);
}
function mostrar(){
	$.ajax({ 		 		
		data:{
			id: pk_personal, 		
			fecha:$("#date_inicio").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),
		},
		type:"POST",
		url:url_ver_materias
	}).done(function(data,textStatus,jqXHR){

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
	}).fail(function(jqXHR,textStatus,textError){
		//alert("Error al realizar la peticion dame".textError);
	});

}

function cargar_asistencia(){
	$.ajaxSetup({async: false});
	for (var i = 0; i < $("#select_materias").val().length; i++) {		
		$.post(
			url_ver_asistencia,
			{
				id_personal: pk_personal,
				id: $("#select_materias").val()[i],				
				fecha:$("#date_inicio").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),
			},function(resp){	
				window.location = "/asistencia-administrador";			
			});
	}
}

