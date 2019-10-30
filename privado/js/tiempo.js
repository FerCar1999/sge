$('.timepicker').pickatime({
    default: 'now',
    twelvehour: false, // change to 12 hour AM/PM clock from 24 hour
    donetext: 'Aceptar',
  	autoclose: false,
  	vibrate: true,// vibrate the device when dragging clock hand
  	darktheme: true
});

// url para las peticiones ajax 
var url_listar_tiempo = "/privado/php/tiempos/tiemposListar.php",
	url_tiempo = "/privado/php/tiempos/tiempos.php",
  	url_eliminar_tiempo = "/privado/php/tiempos/eliminarTiempo.php";

// almace el id a modificar o eliminar
var pk_tiempo, estado_tiempo = "Inactivo", ver_estado_tiempo = "Activo", horaInicioAct = "", horaFinAct = "";

$(document).ready(function () {
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 5, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_tiempo);
	tiempo_init();
 });


function get_data_callback_tiempo(){
  $.ajax({
	data:{
	limit: itemsPorPagina,              
	offset: desde,
	busqueda: $.trim($('input#buscar_tiempo').val()),
	estado: ver_estado_tiempo,
	},
	type:"POST",
	url: url_listar_tiempo
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
		if (ver_estado_tiempo == 'Activo') {
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.horaInicio+'</td>'+
			  '<td>'+elem.horaFin+'</td>'+
			  '<td><a onclick="ver_tiempo('+elem.id+',\''+elem.horaInicio+'\',\''+elem.horaFin+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar"><i  class="material-icons">edit</i></a> <a onclick="eliminarTiempo('+elem.id+',\''+elem.horaInicio+'\',\''+elem.horaFin+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}else{
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.horaInicio+'</td>'+
			  '<td>'+elem.horaFin+'</td>'+
			  '<td><a onclick="activar_tiempo('+elem.id+',\''+elem.horaInicio+'\',\''+elem.horaFin+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Activar"><i  class="material-icons">autorenew</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}
	});     
  }).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

  });
}

$('#ver_tiempos_activos').click(function(e){
  	if(ver_estado_tiempo != "Activo"){
    	ver_estado_tiempo = "Activo";
    	set_callback(get_data_callback_tiempo); 
    	tiempo_init();
  	}
  
});

$('#ver_tiempos_inactivos').click(function(e){
  	if(ver_estado_tiempo != "Inactivo"){
    	ver_estado_tiempo = "Inactivo";
    	set_callback(get_data_callback_tiempo); 
    	tiempo_init();
  	}
});

$('#buscar_tiempo').keyup(function() {  
	tiempo_init();
});

//ACCIONES DE LOS BOTONES
$("#agregar_tiempo").click(function (e) {
	$.post(
		url_tiempo,
		{
	  	horaInicio: $("#horaInicio").val(),
	  	horaFin: $("#horaFin").val(),
	  	token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'agregado':
					swal('Éxito','Tiempo agregado.','success');
					tiempo_init();
					limpiarCampos();
					break;
				case 'camposFalta':
					swal('Error',resp,'info');
					break;
				default:
					swal('Error',resp,'error');
			}
	});
});

$("#modificar_tiempo").click(function (e) {
	$.post(
		url_tiempo,
		{
			id: pk_tiempo,
	  		horaInicio: $("#mod_horaInicio").val(),
	  	    horaFin: $("#mod_horaFin").val(),
	  	    token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'modificado':
					swal('Éxito','Tiempo modificado.','success');
					tiempo_init();
					limpiarCampos();
					break;
				case 'camposFalta':
					swal('Error',resp,'info');
					break;
				case 'existente':
					swal('Error','Tiempo existente.','error');
					break;
				default:
					swal('Error',resp,'error');
			}
	});
});

// funcion eliminar
$('#eliminar_tiempo').click(function(e){
  	//peticion post
  	$.post(
		url_eliminar_tiempo,
		{
	  	id: pk_tiempo,
	  	estado:estado_tiempo,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	tiempo_init();  
	  	swal(resp);
	});
});
$("#activar_tiempo").click(function(e){
	//peticion post
  	$.post(
		url_eliminar_tiempo,
		{
	  	id: pk_tiempo,
	  	estado:estado_tiempo,
	  	horaInicio: horaInicioAct,
	  	horaFin: horaFinAct,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	tiempo_init();  
	  	swal(resp);
	});
});

$("#cancelar_mod").click(function(e){
	limpiarCampos();
  	$('#formAgregarTiempo').show();
  	$('#formModTiempo').hide();
  	$('#formElimTiempo').hide();
  	$('#formActTiempo').hide();
});

$("#cancelar_eliminar").click(function(e){
	limpiarCampos();
  	$('#formAgregarTiempo').show();
  	$('#formModTiempo').hide();
  	$('#formElimTiempo').hide();
  	$('#formActTiempo').hide();
});

$("#cancelar_activar").click(function(e){
	limpiarCampos();
  	$('#formAgregarTiempo').show();
  	$('#formModTiempo').hide();
  	$('#formElimTiempo').hide();
  	$('#formActTiempo').hide();
});

//FIN DE ACCION DE BOTONES


function ver_tiempo(pk,horaInicio,horaFin){
	pk_tiempo = pk;
	$('#formAgregarTiempo').hide();
	$('#formModTiempo').show();
  	$("label").addClass("active");
	$("#mod_horaInicio").val(horaInicio);
	$("#mod_horaFin").val(horaFin);
}

function eliminarTiempo(pk, horaInicio, horaFin){
  	pk_tiempo = pk;
  	$('#formAgregarTiempo').hide();
  	$('#formModTiempo').hide();
  	$('#formElimTiempo').show();
	$('#confirmacion').text("¿Desea desactivar el tiempo de " + horaInicio + " a "+ horaFin + "?");
  	estado_tiempo ="Inactivo";
  	limpiarCampos();
}


//Funcion para reactivar al personal
function activar_tiempo(pk, horaInicio, horaFin){
	pk_tiempo = pk;
	estado_tiempo ="Activo";
	$('#formActTiempo').show();
	$('#formElimTiempo').hide();
	$('#formModTiempo').hide();
	$('#formAgregarTiempo').hide();
	$('#confirmacion_activar').text("¿Desea activar el tiempo de " + horaInicio + " a "+ horaFin + "?");
	horaFinAct = horaInicio;
  	horaFinAct = horaFin;
}

// funcion para reicinar todo
function tiempo_init(){
	cargaPagina(0);  
  	$('#formAgregarTiempo').show();
	$('#formModTiempo').hide();
	$("#formElimTiempo").hide();
	$('#formActTiempo').hide();
}

//Funcion para limpar los campos del formulario
function limpiarCampos(){
	$("#buscar_tiempo").val("");
	$("#horaInicio").val("");
	$("#mod_horaInicio").val("");
	$("#horaFin").val("");
	$("#mod_horaFin").val("");
  	$("label").removeClass("active");
}