// url para las peticiones ajax 
var url_listar_niveles = "/privado/php/niveles/nivelesListar.php",
	url_niveles = "/privado/php/niveles/niveles.php",
  	url_eliminar_nivel = "/privado/php/niveles/eliminarNiveles.php";

// almace el id a modificar o eliminar
var pk_nivel, estado_nivel = "Inactivo", ver_estado_nivel = "Activo";

$(document).ready(function () {
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 2, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_nivel);
	nivel_init();
 });


function get_data_callback_nivel(){
  $.ajax({
	data:{
	limit: itemsPorPagina,              
	offset: desde,
	busqueda: $.trim($('input#buscar_nivel').val()),
	estado: ver_estado_nivel,
	},
	type:"POST",
	url: url_listar_niveles
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
		if (ver_estado_nivel == 'Activo') {
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+			  
			  '<td><a onclick="ver_nivel('+elem.id+',\''+elem.nombre+'\',\''+elem.descripcion+'\','+elem.cantidad+')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar"><i  class="material-icons">edit</i></a> <a onclick="eliminarNivel('+elem.id+',\''+elem.nombre+'\')" class="btn-floating teal tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}else{
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+			  
			  '<td><a onclick="activar_nivel('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Activar"><i  class="material-icons">autorenew</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}
	});     
  }).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

  });
}

$('#ver_especialidades_activas').click(function(e){
  	if(ver_estado_nivel != "Activo"){
    	ver_estado_nivel = "Activo";
    	set_callback(get_data_callback_nivel); 
    	nivel_init();    
  	}
  
});

$('#ver_especialidades_inactivas').click(function(e){
  	if(ver_estado_nivel != "Inactivo"){
    	ver_estado_nivel = "Inactivo";
    	set_callback(get_data_callback_nivel); 
    	nivel_init();
  	}
});

$('#buscar_nivel').keyup(function() {  
	nivel_init();
});

//ACCIONES DE LOS BOTONES
$("#agregar_nivel").click(function (e) {
	$.post(
		url_niveles,
		{
	  	nombre: $("#nombre").val(),
	  	cantidad: $("#cantNivel").val(),
	  	descripcion: $("#descripcion").val(),
	  	token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'agregado':
					swal('Éxito','Nivel agregado.','success');
					nivel_init();
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

$("#modificar_nivel").click(function (e) {
	$.post(
		url_niveles,
		{
			id: pk_nivel,
	  		nombre: $("#mod_nombre").val(),
	  		cantidad: $("#mod_cantNivel").val(),
	  		descripcion: $("#mod_descripcion").val(),
	  		token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'modificado':
					swal('Éxito','Nivel modificado.','success');
					nivel_init();
					limpiarCampos();
					break;
				case 'camposFalta':
					swal('Error',resp,'info');
					break;
				case 'existente':
					swal('Error','Nivel existente.','error');
					break;
				default:
					swal('Error',resp,'error');
			}
	});
});

// funcion eliminar
$('#eliminar_nivel').click(function(e){
  	//peticion post
  	$.post(
		url_eliminar_nivel,
		{
	  	id: pk_nivel,
	  	estado:estado_nivel,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	nivel_init();  
	  	swal(resp);
	});
});
$("#activar_nivel").click(function(e){
	//peticion post
  	$.post(
		url_eliminar_nivel,
		{
	  	id: pk_nivel,
	  	estado:estado_nivel,
	  	token: $("#dpToken").val(),
		},function(resp){
	  	nivel_init();  
	  	swal(resp);
	});
});

$("#cancelar_mod").click(function(e){
	limpiarCampos();
  	$('#formAgregarNivel').show();
  	$('#formModNivel').hide();
  	$('#formElimNivel').hide();
});

$("#cancelar_eliminar").click(function(e){
	limpiarCampos();
  	$('#formAgregarNivel').show();
  	$('#formModNivel').hide();
  	$('#formElimNivel').hide();
});

$("#cancelar_activar").click(function(e){
	limpiarCampos();
  	$('#formAgregarNivel').show();
  	$('#formModNivel').hide();
  	$('#formElimNivel').hide();
});

//FIN DE ACCION DE BOTONES


function ver_nivel(pk,nombre,descripcion,cantidad){
	pk_nivel = pk;
	$('#formAgregarNivel').hide();
	$('#formModNivel').show();
  	$("label").addClass("active");
	$("#mod_nombre").val(nombre);
	$("#mod_descripcion").val(descripcion);
	$("#mod_cantNivel").val(cantidad);
}

function eliminarNivel(pk, nombre){
  	pk_nivel = pk;
  	$('#formAgregarNivel').hide();
  	$('#formModNivel').hide();
  	$('#formElimNivel').show();	
	$('#confirmacion').text("¿Desea desactivar el nivel "+ nombre + "?");
  	estado_nivel ="Inactivo";
  	limpiarCampos();
}


//Funcion para reactivar al personal
function activar_nivel(pk, nombre){
	pk_nivel = pk;
	estado_nivel ="Activo";
	$('#formActNivel').show();
	$('#formElimNivel').hide();
	$('#formModNivel').hide();
	$('#formAgregarNivel').hide();
	$("#confirmacion_activar").text("¿Desea activar el nivel "+ nombre + "?");
}

// funcion para reicinar todo
function nivel_init(){
	cargaPagina(0);  
  	$('#formAgregarNivel').show();
	$('#formModNivel').hide();
	$("#formElimNivel").hide();
	$('#formActNivel').hide();
}

//Funcion para limpar los campos del formulario
function limpiarCampos(){
	$("#nombre").val("");
	$("#mod_nombre").val("");
	$("#descripcion").val("");
	$("#mod_descripcion").val("");
	$("#cantNivel").val("");
	$("#mod_cantNivel").val("");
  	$("label").removeClass("active");
}