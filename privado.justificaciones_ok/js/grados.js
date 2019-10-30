// url para las peticiones ajax 
var url_listar_grados = "/privado/php/grados/gradosListar.php",
	url_grados = "/privado/php/grados/grados.php",
  	url_eliminar_grados = "/privado/php/grados/eliminarGrados.php";

// almace el id a modificar o eliminar
var pk_grado, estado_grado = "Inactivo", ver_estado_grado = "Activo";

$(document).ready(function () {
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 3, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_grados);
	grados_init();
	$('select').material_select();
 });


function get_data_callback_grados(){
  $.ajax({
	data:{
	limit: itemsPorPagina,              
	offset: desde,
	busqueda: $.trim($('input#buscar_grado').val()),
	estado: ver_estado_grado,
	},
	type:"POST",
	url: url_listar_grados
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
		if (ver_estado_grado == 'Activo') {
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="ver_grado('+elem.id+',\''+elem.nombre+'\','+elem.nivel+')" class="btn-floating green  tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar" ><i  class="material-icons">edit</i></a> <a onclick="eliminarGrado('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}else{
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="activar_grado('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green  tooltipped" data-position="left" data-delay="50" data-tooltip="Activar" ><i  class="material-icons">autorenew</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}
	});     
  }).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

  });
}

$('#buscar_grado').keyup(function() {  
	grados_init();
});

$('#ver_grados_activos').click(function(e){
  	if(ver_estado_grado != "Activo"){
    	ver_estado_grado = "Activo";
    	set_callback(get_data_callback_grados);
    	grados_init();    
  	}
  
});

$('#ver_grados_inactivos').click(function(e){
  	if(ver_estado_grado != "Inactivo"){
    	ver_estado_grado = "Inactivo";
    	set_callback(get_data_callback_grados);
    	grados_init();
  	}
});

//ACCIONES DE LOS BOTONES
$("#agregar_grado").click(function (e) {
	$.post(
		url_grados,
		{
	  	nombre: $("#nombre").val(),
	  	nivel: $("#selectNivel").val(),
	  	token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'agregado':
					swal('Éxito','Grado agregado.','success');
					grados_init();
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

$("#modificar_grado").click(function (e) {
	$.post(
		url_grados,
		{
			id: pk_grado,
	  		nombre: $("#mod_nombre").val(),
	  		nivel: $("#selectNivelMod").val(),
	  		token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'modificado':
					swal('Éxito','Grado modificado.','success');
					grados_init();
					limpiarCampos();
					break;
				case 'camposFalta':
					swal('Error',resp,'info');
					break;
				case 'existente':
					swal('Error','Grado existente.','error');
					break;
				default:
					swal('Error',resp,'error');
			}
	});
});

// funcion eliminar
$('#eliminar_grado').click(function(e){
  	//peticion post
  	$.post(
		url_eliminar_grados,
		{
	  	id: pk_grado,
	  	estado:estado_grado,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	grados_init();  
	  	swal(resp);
	});
});
$("#activar_grado").click(function(e){
	//peticion post
  	$.post(
		url_eliminar_grados,
		{
	  	id: pk_grado,
	  	estado:estado_grado,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	grados_init();  
	  	swal(resp);
	});
});

$("#cancelar_mod").click(function(e){
	limpiarCampos();
  	$('#formAgregarGrado').show();
  	$('#formModGrado').hide();
  	$('#formElimGrado').hide();
});

$("#cancelar_eliminar").click(function(e){
	limpiarCampos();
  	$('#formAgregarGrado').show();
  	$('#formModGrado').hide();
  	$('#formElimGrado').hide();
});

$("#cancelar_activar").click(function(e){
	limpiarCampos();
  	$('#formAgregarGrado').show();
  	$('#formModGrado').hide();
  	$('#formElimGrado').hide();
});

//FIN DE ACCION DE BOTONES


function ver_grado(pk,nombre,nivel){
	pk_grado = pk;
	$('#formAgregarGrado').hide();
	$('#formModGrado').show();
  	$("label").addClass("active");
	$("#mod_nombre").val(nombre);
	$('#selectNivelMod').find('option[value="'+nivel+'"]').prop('selected', true);
	$("#selectNivelMod").material_select();
}

function eliminarGrado(pk, nombre, tipo){
  	pk_grado = pk;
  	$('#formAgregarGrado').hide();
  	$('#formModGrado').hide();
  	$('#formElimGrado').show();
  	$('#confirmacion').text("¿Desea desactivar el grado "+ nombre + "?");
  	estado_grado ="Inactivo";
  	limpiarCampos();
}


//Funcion para reactivar al personal
function activar_grado(pk, nombre, tipo){
	pk_grado = pk;
	estado_grado ="Activo";
	$('#formActGrado').show();
	$('#formElimGrado').hide();
	$('#formModGrado').hide();
	$('#formAgregarGrado').hide();
	$("#confirmacion_activar").text("¿Desea activar el grado "+ nombre + "?");
}

// funcion para reicinar todo
function grados_init(){
	cargaPagina(0);  
  	$('#formAgregarGrado').show();
	$('#formModGrado').hide();
	$("#formElimGrado").hide();
	$('#formActGrado').hide();
}

//Funcion para limpar los campos del formulario
function limpiarCampos(){
	$("#nombre").val("");
  	$("label").removeClass("active");
	$("#mod_nombre").val("");
}