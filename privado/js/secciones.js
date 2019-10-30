// url para las peticiones ajax 
var url_listar_secciones = "/privado/php/secciones/seccionesListar.php",
	url_secciones = "/privado/php/secciones/secciones.php",
  	url_eliminar_seccion = "/privado/php/secciones/eliminarSecciones.php";

// almace el id a modificar o eliminar
var pk_seccion, estado_seccion = "Inactivo", ver_estado_seccion = "Activo";

$(document).ready(function () {
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 3, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_secciones);
	seccion_init();
 });


function get_data_callback_secciones(){
  $.ajax({
	data:{
	limit: itemsPorPagina,              
	offset: desde,
	estado: ver_estado_seccion,
	},
	type:"POST",
	url: url_listar_secciones
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
		if (ver_estado_seccion == 'Activo') {
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="ver_seccion('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar"><i  class="material-icons">edit</i></a> <a onclick="eliminarSeccion('+elem.id+',\''+elem.nombre+'\')" class="btn-floating teal tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}else{
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="activar_seccion('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Activar" ><i  class="material-icons">autorenew</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}
	});     
  }).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

  });
}

$('#ver_secciones_activas').click(function(e){
  	if(ver_estado_seccion != "Activo"){
    	ver_estado_seccion = "Activo";
    	set_callback(get_data_callback_secciones); 
    	seccion_init();    
  	}
  
});

$('#ver_secciones_inactivas').click(function(e){
  	if(ver_estado_seccion != "Inactivo"){
    	ver_estado_seccion = "Inactivo";
    	set_callback(get_data_callback_secciones); 
    	seccion_init();
  	}
});

//ACCIONES DE LOS BOTONES
$("#agregar_seccion").click(function (e) {
	$.post(
		url_secciones,
		{
	  	nombre: $("#nombre").val(),
	  	token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'agregado':
					swal('Éxito','Sección agregada.','success');
					seccion_init();
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

$("#modificar_seccion").click(function (e) {
	$.post(
		url_secciones,
		{
			id: pk_seccion,
	  		nombre: $("#mod_nombre").val(),
	  		token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'modificado':
					swal('Éxito','Sección modificada.','success');
					seccion_init();
					limpiarCampos();
					break;
				case 'camposFalta':
					swal('Error',resp,'info');
					break;
				case 'existente':
					swal('Error','Sección existente.','error');
					break;
				default:
					swal('Error',resp,'error');
			}
	});
});

// funcion eliminar
$('#eliminar_seccion').click(function(e){
  	//peticion post
  	$.post(
		url_eliminar_seccion,
		{
	  	id: pk_seccion,
	  	estado:estado_seccion,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	seccion_init();  
	  	swal(resp);
	});
});
$("#activar_seccion").click(function(e){
	//peticion post
  	$.post(
		url_eliminar_seccion,
		{
	  	id: pk_seccion,
	  	estado:estado_seccion,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	seccion_init();  
	  	swal(resp);
	});
});

$("#cancelar_mod").click(function(e){
	limpiarCampos();
  	$('#formAgregarSeccion').show();
  	$('#formModSeccion').hide();
  	$('#formElimSeccion').hide();
});

$("#cancelar_eliminar").click(function(e){
	limpiarCampos();
  	$('#formAgregarSeccion').show();
  	$('#formModSeccion').hide();
  	$('#formElimSeccion').hide();
});

$("#cancelar_activar").click(function(e){
	limpiarCampos();
  	$('#formAgregarSeccion').show();
  	$('#formModSeccion').hide();
  	$('#formElimSeccion').hide();
});

//FIN DE ACCION DE BOTONES


function ver_seccion(pk,nombre){
	pk_seccion = pk;
	$('#formAgregarSeccion').hide();
	$('#formModSeccion').show();
  	$("label").addClass("active");
	$("#mod_nombre").val(nombre);
}

function eliminarSeccion(pk, nombre){
  	pk_seccion = pk;
  	$('#formAgregarSeccion').hide();
  	$('#formModSeccion').hide();
  	$('#formElimSeccion').show();	
	$('#confirmacion').text("¿Desea desactivar la sección "+ nombre + "?");
  	estado_seccion ="Inactivo";
  	limpiarCampos();
}


//Funcion para reactivar al personal
function activar_seccion(pk, nombre){
	pk_seccion = pk;
	estado_seccion ="Activo";
	$('#formActSeccion').show();
	$('#formElimSeccion').hide();
	$('#formModSeccion').hide();
	$('#formAgregarSeccion').hide();
	$("#confirmacion_activar").text("¿Desea activar la sección "+ nombre + "?");
}

// funcion para reicinar todo
function seccion_init(){
	cargaPagina(0);  
  	$('#formAgregarSeccion').show();
	$('#formModSeccion').hide();
	$("#formElimSeccion").hide();
	$('#formActSeccion').hide();
}

//Funcion para limpar los campos del formulario
function limpiarCampos(){
	$("#nombre").val("");
	$("#mod_nombre").val("");
  	$("label").removeClass("active");
}