// url para las peticiones ajax 
var url_listar_locales = "/privado/php/locales/localesListar.php",
	url_locales = "/privado/php/locales/locales.php",
    url_eliminar_locales = "/privado/php/locales/eliminarLocales.php";

// almace el id a modificar o eliminar
var pk_local, estado_local = "Inactivo", ver_estado_local = "Activo";

$(document).ready(function () {
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 5, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_locales); 
	locales_init();
	$('select').material_select();
 });

function get_data_callback_locales(){
  $.ajax({
	data:{
	limit: itemsPorPagina,
	busqueda: $.trim($('input#buscar_local').val()),
	offset: desde,
	estado: ver_estado_local
	},
	type:"POST",
	url: url_listar_locales
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
		if (ver_estado_local == 'Activo') {
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td>'+elem.capacidad+'</td>'+
			  '<td><a onclick="ver_local('+elem.id+',\''+elem.nombre+'\',\''+elem.capacidad+'\','+elem.idTipoLocal+')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Activar"><i  class="material-icons">edit</i></a> <a onclick="eliminar_local('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}else{
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td>'+elem.capacidad+'</td>'+
			  '<td><a onclick="activar_local('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Activar"><i  class="material-icons">autorenew</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}
	});     
  }).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

  });
}

$('#ver_locales_activos').click(function(e){
  	if(ver_estado_local != "Activo"){
    	ver_estado_local = "Activo";
    	set_callback(get_data_callback_locales); 
    	locales_init();    
  	}
  
});

$('#ver_locales_inactivos').click(function(e){
  	if(ver_estado_local != "Inactivo"){
    	ver_estado_local = "Inactivo";
    	set_callback(get_data_callback_locales); 
    	locales_init();
  	}
});

$('#buscar_local').keyup(function() {  
	locales_init();
});

//ACCIONES DE LOS BOTONES
$('#agregar_local').click(function(e){
  	$.post(
		url_locales,
		{
	  	nombre: $("#nombre").val(),
	  	capacidad: $("#capacidad").val(),
	  	idTipoLocal: $("#selectTipoLocal").val(),
	  	token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'agregado':
					swal('Éxito','Local agregado.','success');
					locales_init();
					limpiarCampos();
					break;
				case 'camposFalta':
					swal('Error',resp,'error');
					break;
				default:
					swal('Error',resp,'error');
			}
	});
});
$('#modificar_local').click(function(e){
		$.post(
		url_locales,
		{
			id: pk_local,
	  		nombre: $("#mod_nombre").val(),
	  		capacidad: $("#mod_capacidad").val(),
	  		idTipoLocal: $("#selectTipoLocalMod").val(),
	  		token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'modificado':
					swal('Éxito','Local modificado.','success');
					locales_init();
					limpiarCampos();
					break;
				case 'camposFalta':
					swal('Error',resp,'info');
					break;
				case 'existente':
					swal('Error','Local existente.','error');
					break;
				default:
					swal('Error',resp,'error');
			}
	});
});
// funcion eliminar
$('#eliminar_local').click(function(e){
  	//peticion post
  	$.post(
		url_eliminar_locales,
		{
	  	id: pk_local,
	  	estado:estado_local,  
	  	token: $("#dpToken").val(),    
		},function(resp){
	  	if (resp != "") {
	        locales_init();
  	        swal("",resp,"info");    
	    }
	});
});
$("#activar_local").click(function(e){
	//peticion post
  	$.post(
		url_eliminar_locales,
		{
	  	id: pk_local,
	  	estado:estado_local,
	  	token: $("#dpToken").val(),      
		},function(resp){
		    if (resp != "") {
		        locales_init();
	  	        swal("",resp,"info");    
		    }
	});
});

$("#cancelar_mod").click(function(e){
  	limpiarCampos();
  	$('#formAgregarLocal').show();
	$('#formModLocal').hide();
	$('#formElimLocal').hide();
  	$('#formActLocal').hide();
});

$("#cancelar_eliminar").click(function(e){
    limpiarCampos();
  	$('#formAgregarLocal').show();
	$('#formModLocal').hide();
	$('#formElimLocal').hide();
  	$('#formActLocal').hide();
});

$("#cancelar_activar").click(function(e){
    limpiarCampos();
  	$('#formAgregarLocal').show();
	$('#formModLocal').hide();
	$('#formElimLocal').hide();
  	$('#formActLocal').hide();
});

//FIN DE ACCION DE BOTONES


function ver_local(pk,nombre,cantidad,tipoLocal){
	pk_local = pk;
	
	$('#formAgregarLocal').hide();
	$('#formModLocal').show();
	$('#formElimLocal').hide();
  	$('#formActLocal').hide();
	
  	$("label").addClass("active");
  	
	$("#mod_nombre").val(nombre);
  	$("#mod_capacidad").val(cantidad);
  	
  	$('#selectTipoLocalMod').find('option[value="'+tipoLocal+'"]').prop('selected', true);
	$("#selectTipoLocalMod").material_select();

}

// confirmacion si desea eliminar
function eliminar_local(pk, nombre){
  	pk_local=pk;
  	$('#formElimLocal').show();
  	$('#formActLocal').hide();
	$('#formModLocal').hide();
	$('#formAgregarLocal').hide();
  	$('#confirmacion').text("¿Desea desactivar el local " + nombre + " ?");
  	estado_local ="Inactivo";

}

//Funcion para reactivar al personal
function activar_local(pk, nombre){
	pk_local = pk;
	$('#formActLocal').show();
	$('#formElimLocal').hide();
	$('#formModLocal').hide();
	$('#formAgregarLocal').hide();
	estado_local ="Activo";
	$("#confirmacion_activar").text("¿Desea activar el local " + nombre + " ?");
}

// funcion para reicinar todo
function locales_init(){
	cargaPagina(0);  
  	$('#formElimLocal').hide();
	$('#formModLocal').hide();
	$('#formActLocal').hide();
	$('#formAgregarLocal').show();
}

//Funcion para limpar los campos del formulario
function limpiarCampos(){

	$("#nombre").val("");
  	$("#capacidad").val("");

  	$("label").removeClass("active");
  	$("input").removeClass("valid");
  	$("input").removeClass("invalid");

	$("#mod_nombre").val("");
  	$("#mod_capacidad").val("");
}