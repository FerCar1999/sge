// url para las peticiones ajax 
var url_listar_tipoCodigo = "/privado/php/tiposcodigos/tiposCodigosListar.php",
	url_tipoCodigo = "/privado/php/tiposcodigos/tiposCodigos.php",
  	url_eliminar_tipoCodigo = "/privado/php/tiposcodigos/eliminarTipoCodigos.php";

// almace el id a modificar o eliminar
var pk_tipoCodigo, estado_tipoCodigo = "Inactivo", ver_estado_tipoCodigo = "Activo";

$(document).ready(function () {
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 3, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_tipoCodigo);
	tipoCodigo_init();
 });


function get_data_callback_tipoCodigo(){
  $.ajax({
	data:{
	limit: itemsPorPagina,              
	offset: desde,
	busqueda: $.trim($('input#buscar_tipoCodigo').val()),
	estado: ver_estado_tipoCodigo,
	},
	type:"POST",
	url: url_listar_tipoCodigo
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
		if (ver_estado_tipoCodigo == 'Activo') {
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="ver_tipoCodigo('+elem.id+',\''+elem.nombre+'\',\''+elem.descripcion+'\','+elem.cantidad+','+elem.escala+')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar"><i  class="material-icons">edit</i></a> <a onclick="eliminarTipoCodigo('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}else{
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="activar_tipoCodigo('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Activar"><i  class="material-icons">autorenew</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}
	});     
  }).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

  });
}

$('#ver_tiposCodigos_activas').click(function(e){
  	if(ver_estado_tipoCodigo != "Activo"){
    	ver_estado_tipoCodigo = "Activo";
    	set_callback(get_data_callback_tipoCodigo); 
    	tipoCodigo_init();    
  	}
  
});

$('#ver_tiposCodigos_inactivas').click(function(e){
  	if(ver_estado_tipoCodigo != "Inactivo"){
    	ver_estado_tipoCodigo = "Inactivo";
    	set_callback(get_data_callback_tipoCodigo); 
    	tipoCodigo_init();
  	}
});

$('#buscar_tipoCodigo').keyup(function() {  
	tipoCodigo_init();
});

//ACCIONES DE LOS BOTONES
$("#agregar").click(function (e) {
	$.post(
		url_tipoCodigo,
		{
	  	nombre: $("#nombre").val(),
	  	descripcion: $("#descripcion").val(),
	  	cantidad: $("#cantidad").val(),
	  	escala: $("#escala").val(),
	  	token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'agregado':
					swal('Éxito','Tipo de código agregado.','success');
					tipoCodigo_init();
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
		url_tipoCodigo,
		{
			id: pk_tipoCodigo,
	  		nombre: $("#mod_nombre").val(),
	  		descripcion: $("#mod_descripcion").val(),
	  		cantidad: $("#mod_cantidad").val(),
	  		escala: $("#mod_escala").val()
		},function(resp){
			switch(resp){
				case 'modificado':
					swal('Éxito','Tipo de código modificado.','success');
					tipoCodigo_init();
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
		url_eliminar_tipoCodigo,
		{
	  	id: pk_tipoCodigo,
	  	estado:estado_tipoCodigo,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	tipoCodigo_init();  
	  	if (resp != "") {
	  	    swal("Éxito",resp,"info");
	  	}
	});
});
$("#activar").click(function(e){
	//peticion post
  	$.post(
		url_eliminar_tipoCodigo,
		{
	  	id: pk_tipoCodigo,
	  	estado:estado_tipoCodigo,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	tipoCodigo_init();  
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


function ver_tipoCodigo(pk,nombre,descripcion,cantidad,escala){
	pk_tipoCodigo = pk;
	$('#formAgregar').hide();
	$('#formMod').show();
  	$("label").addClass("active");
	$("#mod_nombre").val(nombre);
	$("#mod_descripcion").val(descripcion);
	$("#mod_cantidad").val(cantidad);
	$("#mod_escala").val(escala);
}

function eliminarTipoCodigo(pk, nombre){
  	pk_tipoCodigo = pk;
  	$('#formAgregar').hide();
  	$('#formMod').hide();
  	$('#formElim').show();	
	$('#confirmacion').text("¿Desea desactivar el tipo de código "+ nombre + "?");
  	estado_tipoCodigo ="Inactivo";
  	limpiarCampos();
}


//Funcion para reactivar al personal
function activar_tipoCodigo(pk, nombre){
	pk_tipoCodigo = pk;
	estado_tipoCodigo ="Activo";
	$('#formAct').show();
	$('#formElim').hide();
	$('#formMod').hide();
	$('#formAgregar').hide();
	$("#confirmacion_activar").text("¿Desea activar el tipo de código "+ nombre + "?");
}

// funcion para reicinar todo
function tipoCodigo_init(){
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