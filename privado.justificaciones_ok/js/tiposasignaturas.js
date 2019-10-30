// url para las peticiones ajax 
var url_listar_tipoAsignatura = "/privado/php/tiposasignaturas/tiposAsignaturasListar.php",
	url_tipoAsignatura = "/privado/php/tiposasignaturas/tiposAsignaturas.php",
  	url_eliminar_tipoAsignatura = "/privado/php/tiposasignaturas/eliminarTipoAsignaturas.php";

// almace el id a modificar o eliminar
var pk_tipoAsignatura, estado_tipoAsignatura = "Inactivo", ver_estado_tipoAsignatura = "Activo";

$(document).ready(function () {
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 3, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_tipoAsignatura);
	tipoAsignatura_init();
 });


function get_data_callback_tipoAsignatura(){
  $.ajax({
	data:{
	limit: itemsPorPagina,              
	offset: desde,
	busqueda: $.trim($('input#buscar_tipoAsignatura').val()),
	estado: ver_estado_tipoAsignatura,
	},
	type:"POST",
	url: url_listar_tipoAsignatura
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
		if (ver_estado_tipoAsignatura == 'Activo') {
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="ver_tipoAsignatura('+elem.id+',\''+elem.nombre+'\',\''+elem.descripcion+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar"><i  class="material-icons">edit</i></a> <a onclick="eliminarTipoAsignatura('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}else{
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="activar_tipoAsignatura('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Activar"><i  class="material-icons">autorenew</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}
	});     
  }).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

  });
}

$('#ver_tiposAsignaturas_activas').click(function(e){
  	if(ver_estado_tipoAsignatura != "Activo"){
    	ver_estado_tipoAsignatura = "Activo";
    	set_callback(get_data_callback_tipoAsignatura); 
    	tipoAsignatura_init();    
  	}
  
});

$('#ver_tiposAsignaturas_inactivas').click(function(e){
  	if(ver_estado_tipoAsignatura != "Inactivo"){
    	ver_estado_tipoAsignatura = "Inactivo";
    	set_callback(get_data_callback_tipoAsignatura); 
    	tipoAsignatura_init();
  	}
});

$('#buscar_tipoAsignatura').keyup(function() {  
	tipoAsignatura_init();
});

//ACCIONES DE LOS BOTONES
$("#agregar").click(function (e) {
	$.post(
		url_tipoAsignatura,
		{
	  	nombre: $("#nombre").val(),
	  	descripcion: $("#descripcion").val(),
	  	token: $("#dpToken").val(),  
		},function(resp){
			switch(resp){
				case 'agregado':
					swal('Éxito','Tipo de asignatura agregada.','success');
					tipoAsignatura_init();
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

$("#modificar").click(function (e) {
	$.post(
		url_tipoAsignatura,
		{
			id: pk_tipoAsignatura,
	  		nombre: $("#mod_nombre").val(),
	  		descripcion: $("#mod_descripcion").val(),
	  		token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'modificado':
					swal('Éxito','Tipo de asignatura modificada.','success');
					tipoAsignatura_init();
					limpiarCampos();
					break;
				case 'camposFalta':
					swal('Error',resp,'info');
					break;
				case 'existente':
					swal('Error','Tipo de asignatura existente.','error');
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
		url_eliminar_tipoAsignatura,
		{
	  	id: pk_tipoAsignatura,
	  	estado:estado_tipoAsignatura,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	tipoAsignatura_init();  
	  	swal(resp);
	});
});
$("#activar").click(function(e){
	//peticion post
  	$.post(
		url_eliminar_tipoAsignatura,
		{
	  	id: pk_tipoAsignatura,
	  	estado:estado_tipoAsignatura,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	tipoAsignatura_init();  
	  	swal(resp);
	});
});

$("#cancelar_mod").click(function(e){
	limpiarCampos();
  	$('#formAgregar').show();
  	$('#formMod').hide();
  	$('#formElim').hide();
});

$("#cancelar_eliminar").click(function(e){
	limpiarCampos();
  	$('#formAgregar').show();
  	$('#formMod').hide();
  	$('#formElim').hide();
});

$("#cancelar_activar").click(function(e){
	limpiarCampos();
  	$('#formAgregar').show();
  	$('#formMod').hide();
  	$('#formElim').hide();
});

//FIN DE ACCION DE BOTONES


function ver_tipoAsignatura(pk,nombre,descripcion){
	pk_tipoAsignatura = pk;
	$('#formAgregar').hide();
	$('#formMod').show();
  	$("label").addClass("active");
	$("#mod_nombre").val(nombre);
	$("#mod_descripcion").val(descripcion);
}

function eliminarTipoAsignatura(pk, nombre){
  	pk_tipoAsignatura = pk;
  	$('#formAgregar').hide();
  	$('#formMod').hide();
  	$('#formElim').show();	
	$('#confirmacion').text("¿Desea desactivar el tipo de asignatura "+ nombre + "?");
  	estado_tipoAsignatura ="Inactivo";
  	limpiarCampos();
}


//Funcion para reactivar al personal
function activar_tipoAsignatura(pk, nombre){
	pk_tipoAsignatura = pk;
	estado_tipoAsignatura ="Activo";
	$('#formAct').show();
	$('#formElim').hide();
	$('#formMod').hide();
	$('#formAgregar').hide();
	$("#confirmacion_activar").text("¿Desea activar el tipo de asignatura "+ nombre + "?");
}

// funcion para reicinar todo
function tipoAsignatura_init(){
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