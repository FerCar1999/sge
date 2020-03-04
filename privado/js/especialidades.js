// url para las peticiones ajax 
var url_listar_especialidades = "/privado/php/especialidades/especialidadesListar.php",
	url_especialidades = "/privado/php/especialidades/especialidades.php",
  	url_eliminar_especialidad = "/privado/php/especialidades/eliminarEspecialidad.php";

// almace el id a modificar o eliminar
var pk_especialidad, estado_especialidad = "Inactivo", ver_estado_especialidad = "Activo";

$(document).ready(function () {
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 3, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_especialidades);
	especialidad_init();
 });


function get_data_callback_especialidades(){
  $.ajax({
	data:{
	limit: itemsPorPagina,              
	offset: desde,
	estado: ver_estado_especialidad,
	},
	type:"POST",
	url: url_listar_especialidades
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
		if (ver_estado_especialidad == 'Activo') {
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="ver_especialidad('+elem.id+',\''+elem.nombre+'\',\''+elem.descripcion+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar" ><i  class="material-icons">edit</i></a> <a onclick="eliminarEspecialidad('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}else{
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="activar_especialidad('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Reactivar" ><i  class="material-icons">autorenew</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}
	});     
  }).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

  });
}

$('#ver_especialidades_activas').click(function(e){
  	if(ver_estado_especialidad != "Activo"){
    	ver_estado_especialidad = "Activo";
    	set_callback(get_data_callback_especialidades); 
    	especialidad_init();    
  	}
  
});

$('#ver_especialidades_inactivas').click(function(e){
  	if(ver_estado_especialidad != "Inactivo"){
    	ver_estado_especialidad = "Inactivo";
    	set_callback(get_data_callback_especialidades); 
    	especialidad_init();
  	}
});

//ACCIONES DE LOS BOTONES
$("#agregar_especialidad").click(function (e) {
	$.post(
		url_especialidades,
		{
	  	nombre: $("#nombre").val(),
	  	descripcion: $("#descripcion").val(),
	  	token: $("#dpToken").val(),  
		},function(resp){
			switch(resp){
				case 'agregado':
					swal('Éxito','Especialidad agregada.','success');
					especialidad_init();
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

$("#modificar_especialidad").click(function (e) {
	$.post(
		url_especialidades,
		{
			id: pk_especialidad,
	  		nombre: $("#mod_nombre").val(),
	  		descripcion: $("#mod_descripcion").val(),
	  		token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'modificado':
					swal('Éxito','Especialidad modificada.','success');
					especialidad_init();
					limpiarCampos();
					break;
				case 'camposFalta':
					swal('Error',resp,'info');
					break;
				case 'existente':
					swal('Error','Especialidad existente.','error');
					break;
				default:
					swal('Error',resp,'error');
			}
	});
});

// funcion eliminar
$('#eliminar_especialidad').click(function(e){
  	//peticion post
  	$.post(
		url_eliminar_especialidad,
		{
	  	id: pk_especialidad,
	  	estado:estado_especialidad,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	especialidad_init();  
	  	swal(resp);
	});
});
$("#activar_especialidad").click(function(e){
	//peticion post
  	$.post(
		url_eliminar_especialidad,
		{
	  	id: pk_especialidad,
	  	estado:estado_especialidad,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	especialidad_init();  
	  	swal(resp);
	});
});

$("#cancelar_mod").click(function(e){
	limpiarCampos();
  	$('#formAgregarEsp').show();
  	$('#formModEsp').hide();
  	$('#formElimEsp').hide();
});

$("#cancelar_eliminar").click(function(e){
	limpiarCampos();
  	$('#formAgregarEsp').show();
  	$('#formModEsp').hide();
  	$('#formElimEsp').hide();
});

$("#cancelar_activar").click(function(e){
	limpiarCampos();
  	$('#formAgregarEsp').show();
  	$('#formModEsp').hide();
  	$('#formElimEsp').hide();
});

//FIN DE ACCION DE BOTONES


function ver_especialidad(pk,nombre,descripcion){
	pk_especialidad = pk;
	$('#formAgregarEsp').hide();
	$('#formModEsp').show();
  	$("label").addClass("active");
	$("#mod_nombre").val(nombre);
	$("#mod_descripcion").val(descripcion);
}

function eliminarEspecialidad(pk, nombre){
  	pk_especialidad = pk;
  	$('#formAgregarEsp').hide();
  	$('#formModEsp').hide();
  	$('#formElimEsp').show();	
	$('#confirmacion').text("¿Desea desactivar la especialidad "+ nombre + "?");
  	estado_especialidad ="Inactivo";
  	limpiarCampos();
}


//Funcion para reactivar al personal
function activar_especialidad(pk, nombre){
	pk_especialidad = pk;
	estado_especialidad ="Activo";
	$('#formActEsp').show();
	$('#formElimEsp').hide();
	$('#formModEsp').hide();
	$('#formAgregarEsp').hide();
	$("#confirmacion_activar").text("¿Desea activar la especialidad "+ nombre + "?");
}

// funcion para reicinar todo
function especialidad_init(){
	cargaPagina(0);  
  	$('#formAgregarEsp').show();
	$('#formModEsp').hide();
	$("#formElimEsp").hide();
	$('#formActEsp').hide();
}

//Funcion para limpar los campos del formulario
function limpiarCampos(){
	$("#nombre").val("");
	$("#mod_nombre").val("");
	$("#descripcion").val("");
	$("#mod_descripcion").val("");
  	$("label").removeClass("active");
}