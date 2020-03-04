var url_listar_etapas = "/privado/php/etapas/etapasListar.php";
var url_guardar_etapa = "/privado/php/etapas/etapas.php";
var url_eliminar_etapa = "/privado/php/etapas/eliminarEtapa.php";

var pk_etapa, estado_etapa = "Inactivo", ver_estado_etapa = "Activo", nivel = 0;
$(document).ready(function() {  
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
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 3, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_asuetos);
	etapas_init();
	$('select').material_select();
});

// funcion para reicinar todo
function etapas_init(){
	cargaPagina(0);  
  	$('#formAgregar').show();
	$('#formMod').hide();
	$("#formElim").hide();
	$('#formAct').hide();
}
$("#agregar").click(function (e) {
	$.post(
		url_guardar_etapa,
		{
	  	nombre: $("#nombre").val(),
	  	descripcion: $("#descripcion").val(),
	  	inicio: $("#date_inicio").val(),
	  	fin: $("#date_fin").val(),
	  	nivel: $("#selectNivel").val()
		},function(resp){
			switch (resp){
  				case 'success':
    					Materialize.toast('Etapa agregada.', 5000, 'rounded');
    					etapas_init();
 				break;
 				case 'error':
    					Materialize.toast('Ha ocurrido un error en su solicitud.', 10000, 'rounded');
 				break;
				default:
					Materialize.toast(resp, 5000, 'rounded');
				break;
			}
	});
});
$("#modificar").click(function (e) {
	$.post(
		url_guardar_etapa,
		{
		id:pk_etapa,
	  	nombre: $("#mod_nombre").val(),
	  	descripcion: $("#mod_descripcion").val(),
	  	inicio: $("#mod_date_inicio").val(),
	  	fin: $("#mod_date_fin").val(),
	  	nivel: $("#selectModNivel").val()
		},function(resp){
			switch (resp){
  				case 'success':
    					Materialize.toast('Etapa modificada.', 5000, 'rounded');
    					etapas_init();
 				break;
 				case 'error':
    					Materialize.toast('Ha ocurrido un error en su solicitud.', 10000, 'rounded');
 				break;
				default:
					Materialize.toast(resp, 5000, 'rounded');
				break;
			}
	});
});

$('#ver_etapas_activas').click(function(e){
  	if(ver_estado_etapa != "Activo"){
    	ver_estado_etapa = "Activo";
    	set_callback(get_data_callback_asuetos); 
    	etapas_init();    
  	}
  
});

$('#ver_etapas_inactivas').click(function(e){
  	if(ver_estado_etapa != "Inactivo"){
    	ver_estado_etapa = "Inactivo";
    	set_callback(get_data_callback_asuetos);
    	etapas_init();
  	}
});

$('#buscar_etapa').keyup(function() {  
	etapas_init();
});
// funcion eliminar
$('#eliminar').click(function(e){
  	//peticion post
  	$.post(
		url_eliminar_etapa,
		{
	  	id: pk_etapa,
	  	estado:estado_etapa,
		},function(resp){
	  	etapas_init();  
	  	if (resp != "") {
	  	    switch (resp){
  				case 'success':
    					Materialize.toast('Etapa desactivada.', 5000, 'rounded');
    					etapas_init(); 
 				break;
 				case 'error':
    					Materialize.toast('Ha ocurrido un error en su solicitud.', 10000, 'rounded');
 				break;
				default:
					Materialize.toast(resp, 5000, 'rounded');
				break;
			}
	  	}
	});
});
$('#activar').click(function(e){
  	//peticion post
  	$.post(
		url_eliminar_etapa,
		{
	  	id: pk_etapa,
	  	estado:estado_etapa,
	  	nivel:nivel,
		},function(resp){
	  	switch (resp){
  			case 'success':
					Materialize.toast('Etapa activada.', 5000, 'rounded');
					etapas_init(); 
 			break;
 			case 'error':
					Materialize.toast('Ha ocurrido un error en su solicitud.', 10000, 'rounded');
 			break;
			default:
				Materialize.toast(resp, 5000, 'rounded');
			break;
		}
	});
});
function get_data_callback_asuetos(){
  $.ajax({
	data:{
	limit: itemsPorPagina,              
	offset: desde,
	busqueda: $.trim($('input#buscar_asueto').val()),
	estado: ver_estado_etapa,
	},
	type:"POST",
	url: url_listar_etapas
  }).done(function(data,textStatus,jqXHR){
	
	// obtiene la clave lista del json data
	var lista = data.lista;
	$(".table").html("")
	
	// si es necesario actualiza la cantidad de paginas del paginador
	if(pagina==0){
	  creaPaginador(data.cantidad);
	}
	// genera el cuerpo de la tabla
	$.each(lista, function(ind, elem){
		if (ver_estado_etapa == 'Activo') {
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td>'+elem.inicio+'</td>'+
			  '<td>'+elem.fin+'</td>'+
			  '<td><a onclick="ver_etapa('+elem.id+',\''+elem.nombre+'\',\''+elem.inicio+'\',\''+elem.fin+'\',\''+elem.descripcion+'\','+elem.nivel+')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar"><i  class="material-icons">edit</i></a> <a onclick="eliminaretapa('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}else{
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td>'+elem.inicio+'</td>'+
			  '<td>'+elem.fin+'</td>'+
			  '<td><a onclick="activar_etapa('+elem.id+',\''+elem.nombre+'\','+elem.nivel+')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Activar" ><i  class="material-icons">autorenew</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip(); 		}
	});     
  }).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

  });
}
function eliminaretapa(pk,nombre){
	pk_etapa = pk;
	$('#formAgregar').hide();
  	$('#formMod').hide();
  	$('#formElim').show();	
	$('#confirmacion').text("¿Desea desactivar la etapa "+ nombre + "?");
  	estado_asueto ="Inactivo";
}
function activar_etapa(pk,nombre,nivel_pk){
	pk_etapa = pk;
	nivel = nivel_pk;
	estado_etapa ="Activo";
	$('#formAct').show();
	$('#formElim').hide();
	$('#formMod').hide();
	$('#formAgregar').hide();
	$("#confirmacion_activar").text("¿Desea activar la etapa "+ nombre + "?");
}
function ver_etapa(pk,nombre,inicio,fin,descripcion,nivel){
	pk_etapa = pk;
	$("#mod_nombre").val(nombre);
	$("#mod_descripcion").val(descripcion);
	$("#mod_date_inicio").val(inicio);
	$("#mod_date_fin").val(fin);
	
	$("label").addClass("active");
	
    $('#selectModNivel').find('option[value="'+nivel+'"]').prop('selected', true);
	$("#selectModNivel").material_select();
	
   $("label.selectlbl").removeClass("active");
	
	$('#formAgregar').hide();
	$('#formMod').show();
	$("#formElim").hide();
	$('#formAct').hide();
}