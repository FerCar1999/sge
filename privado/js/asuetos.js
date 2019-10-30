var url_listar_asuetos = "/privado/php/asuetos/asuetos_listar.php";
var url_guardar_asueto = "/privado/php/asuetos/guardar_asueto.php";
var url_eliminar_asueto = "/privado/php/asuetos/eliminar_asueto.php";

var pk_asueto, estado_asueto = "Inactivo", ver_estado_asueto = "Activo";
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
		labelMonthSelect: 'Seleccione un mes',
		labelYearSelect: 'Seleccione un año',

		// Formats
		format: 'dd !de mmmm !de yyyy',
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
	asuetos_init();
});

// funcion para reicinar todo
function asuetos_init(){
	cargaPagina(0);  
  	$('#formAgregar').show();
	$('#formMod').hide();
	$("#formElim").hide();
	$('#formAct').hide();
}
$("#agregar").click(function (e) {
	$.post(
		url_guardar_asueto,
		{
	  	nombre: $("#nombre").val(),
	  	token: $("#dpToken").val(),
	  	inicio: $("#date_inicio").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),
	  	fin: $("#date_fin").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),
		},function(resp){
			swal(resp);
	});
});
$("#modificar").click(function (e) {
	$.post(
		url_guardar_asueto,
		{
		id:pk_asueto,
	  	nombre: $("#mod_nombre").val(),
	  	token: $("#dpToken").val(),
	  	inicio: $("#mod_date_inicio").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),
	  	fin: $("#mod_date_fin").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),
		},function(resp){
			swal(resp);
	});
});

$('#ver_asuetos_activos').click(function(e){
  	if(ver_estado_asueto != "Activo"){
    	ver_estado_asueto = "Activo";
    	set_callback(get_data_callback_asuetos); 
    	asuetos_init();    
  	}
  
});

$('#ver_asuetos_inactivos').click(function(e){
  	if(ver_estado_asueto != "Inactivo"){
    	ver_estado_asueto = "Inactivo";
    	set_callback(get_data_callback_asuetos);
    	asuetos_init();
  	}
});

$('#buscar_asueto').keyup(function() {  
	asuetos_init();
});
// funcion eliminar
$('#eliminar').click(function(e){
  	//peticion post
  	$.post(
		url_eliminar_asueto,
		{
	  	id: pk_asueto,
	  	estado:estado_asueto,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	asuetos_init();  
	  	if (resp != "") {
	  	    swal("Éxito",resp,"info");
	  	}
	});
});
$('#activar').click(function(e){
  	//peticion post
  	$.post(
		url_eliminar_asueto,
		{
	  	id: pk_asueto,
	  	estado:estado_asueto,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	asuetos_init();  
	  	if (resp != "") {
	  	    swal("Éxito",resp,"info");
	  	}
	});
});
function get_data_callback_asuetos(){
  $.ajax({
	data:{
	limit: itemsPorPagina,              
	offset: desde,
	busqueda: $.trim($('input#buscar_asueto').val()),
	estado: ver_estado_asueto,
	},
	type:"POST",
	url: url_listar_asuetos
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
		if (ver_estado_asueto == 'Activo') {
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="ver_asueto('+elem.id+',\''+elem.nombre+'\',\''+elem.inicio+'\',\''+elem.fin+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar"><i  class="material-icons">edit</i></a> <a onclick="eliminarasueto('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}else{
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="activar_asueto('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Activar"><i  class="material-icons">autorenew</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}
	});     
  }).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

  });
}
function eliminarasueto(pk,nombre){
	pk_asueto = pk;
	$('#formAgregar').hide();
  	$('#formMod').hide();
  	$('#formElim').show();	
	$('#confirmacion').text("¿Desea desactivar el asueto "+ nombre + "?");
  	estado_asueto ="Inactivo";
}
function activar_asueto(pk,nombre){
	pk_asueto = pk;
	estado_asueto ="Activo";
	$('#formAct').show();
	$('#formElim').hide();
	$('#formMod').hide();
	$('#formAgregar').hide();
	$("#confirmacion_activar").text("¿Desea activar el asueto "+ nombre + "?");
}
function ver_asueto(pk,nombre,inicio,fin){
	pk_asueto = pk;
	$("#mod_nombre").val(nombre);
	$("#mod_date_inicio").val(inicio);
	$("#mod_date_fin").val(fin);
	$("label").addClass("active");
	$('#formAgregar').hide();
	$('#formMod').show();
	$("#formElim").hide();
	$('#formAct').hide();
}