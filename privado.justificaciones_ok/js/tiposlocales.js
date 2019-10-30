// url para las peticiones ajax 
var url_listar_tipoLocal = "/privado/php/tiposlocales/tiposLocalesListar.php",
	url_tipoLocal = "/privado/php/tiposlocales/tiposLocales.php",
  	url_eliminar_tipoLocal = "/privado/php/tiposlocales/eliminarTiposLocales.php";

// almace el id a modificar o eliminar
var pk_tipoLocal, estado_tipoLocal = "Inactivo", ver_estado_tipoLocal = "Activo";

$(document).ready(function () {
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 3, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_tipoLocal);
	tipoLocal_init();
 });


function get_data_callback_tipoLocal(){
  $.ajax({
	data:{
	limit: itemsPorPagina,              
	offset: desde,
	busqueda: $.trim($('input#buscar_tipoLocal').val()),
	estado: ver_estado_tipoLocal,
	},
	type:"POST",
	url: url_listar_tipoLocal
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
		if (ver_estado_tipoLocal == 'Activo') {
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="ver_tipoLocal('+elem.id+',\''+elem.nombre+'\',\''+elem.descripcion+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar"><i  class="material-icons">edit</i></a> <a onclick="eliminarTipoLocal('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}else{
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="activar_tipoLocal('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Activar"><i  class="material-icons">autorenew</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}
	});     
  }).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

  });
}

$('#ver_tiposLocales_activos').click(function(e){
  	if(ver_estado_tipoLocal != "Activo"){
    	ver_estado_tipoLocal = "Activo";
    	set_callback(get_data_callback_tipoLocal); 
    	tipoLocal_init();    
  	}
  
});

$('#ver_tiposLocales_inactivos').click(function(e){
  	if(ver_estado_tipoLocal != "Inactivo"){
    	ver_estado_tipoLocal = "Inactivo";
    	set_callback(get_data_callback_tipoLocal);
    	tipoLocal_init();
  	}
});

$('#buscar_tipoCodigo').keyup(function() {  
	tipoCodigo_init();
});

//ACCIONES DE LOS BOTONES
$("#agregar").click(function (e) {
	$.post(
		url_tipoLocal,
		{
	  	nombre: $("#nombre").val(),
	  	descripcion: $("#descripcion").val(),
	  	token: $("#dpToken").val(),  
		},function(resp){
			switch(resp){
				case 'agregado':
					swal('Éxito','Tipo de local agregado.','success');
					tipoLocal_init();
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

$("#modificar").click(function (e) {
	$.post(
		url_tipoLocal,
		{
			id: pk_tipoLocal,
	  		nombre: $("#mod_nombre").val(),
	  		descripcion: $("#mod_descripcion").val(),
	  		token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'modificado':
					swal('Éxito','Tipo de local modificado.','success');
					tipoLocal_init();
					limpiarCampos();
					break;
				case 'camposFalta':
					swal('Error',resp,'info');
					break;
				case 'existente':
					swal('Error','Tipo de código existente.','error');
					break;
				default:
					swal('Error',resp,'error');
			}
	});
});

// funcion eliminar
$('#eliminar').click(function(e){
  	//peticion post
  	$.post(
		url_eliminar_tipoLocal,
		{
	  	id: pk_tipoLocal,
	  	estado:estado_tipoLocal,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	tipoLocal_init();  
	  	if (resp != "") {
	  	    swal("Éxito",resp,"info");
	  	}
	});
});
$("#activar").click(function(e){
	//peticion post
  	$.post(
		url_eliminar_tipoLocal,
		{
	  	id: pk_tipoLocal,
	  	estado:estado_tipoLocal,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	tipoLocal_init();  
	  	if (resp != "") {
	  	    swal("Éxito",resp,"info");
	  	}
	});
});

$("#cancelar_mod").click(function(e){
	limpiarCampos();
  	$('#formAgregar').show();
  	$('#formMod').hide();
  	$('#formElim').hide();
  	$('#formAct').hide();
});

$("#cancelar_eliminar").click(function(e){
	limpiarCampos();
  	$('#formAgregar').show();
  	$('#formMod').hide();
  	$('#formElim').hide();
  	$('#formAct').hide();
});

$("#cancelar_activar").click(function(e){
	limpiarCampos();
  	$('#formAgregar').show();
  	$('#formMod').hide();
  	$('#formElim').hide();
  	$('#formAct').hide();
});

//FIN DE ACCION DE BOTONES


function ver_tipoLocal(pk,nombre,descripcion){
	pk_tipoLocal = pk;
	$('#formAgregar').hide();
	$('#formMod').show();
  	$("label").addClass("active");
	$("#mod_nombre").val(nombre);
	$("#mod_descripcion").val(descripcion);
}

function eliminarTipoLocal(pk, nombre){
  	pk_tipoLocal = pk;
  	$('#formAgregar').hide();
  	$('#formMod').hide();
  	$('#formElim').show();	
	$('#confirmacion').text("¿Desea desactivar el tipo de local "+ nombre + "?");
  	estado_tipoLocal ="Inactivo";
  	limpiarCampos();
}


//Funcion para reactivar al personal
function activar_tipoLocal(pk, nombre){
	pk_tipoLocal = pk;
	estado_tipoLocal ="Activo";
	$('#formAct').show();
	$('#formElim').hide();
	$('#formMod').hide();
	$('#formAgregar').hide();
	$("#confirmacion_activar").text("¿Desea activar el tipo de local "+ nombre + "?");
}

// funcion para reicinar todo
function tipoLocal_init(){
	cargaPagina(0);  
  	$('#formAgregar').show();
	$('#formMod').hide();
	$("#formElim").hide();
	$('#formAct').hide();
}

//Funcion para limpar los campos del formulario
function limpiarCampos(){
	$("#nombre").val("");
	$("#mod_nombre").val("");
	$("#descripcion").val("");
	$("#mod_descripcion").val("");
  	$("label").removeClass("active");
}