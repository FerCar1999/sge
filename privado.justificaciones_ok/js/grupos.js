// url para las peticiones ajax 
var url_listar_grupos = "/privado/php/grupos/gruposListar.php",
	url_grupos = "/privado/php/grupos/grupos.php",
  	url_eliminar_grupos = "/privado/php/grupos/eliminarGrupo.php";

// almace el id a modificar o eliminar
var pk_grupo, tipoGrupo, tipoListar = 1, estado_grupo = "Inactivo", foto_antigua, ver_estado_grupo = "Activo";

$(document).ready(function () {
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 3, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_grupos);
	grupos_init();
 });


function get_data_callback_grupos(){
  $.ajax({
	data:{
	limit: itemsPorPagina,              
	offset: desde,
	estado: ver_estado_grupo,
	tipo: tipoListar             
	},
	type:"POST",
	url: url_listar_grupos
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
		if (ver_estado_grupo == 'Activo') {
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="ver_grupo('+elem.id+',\''+elem.nombre+'\','+tipoListar+')" class="btn-floating green" ><i  class="material-icons">edit</i></a> <a onclick="eliminarGrupo('+elem.id+',\''+elem.nombre+'\','+tipoListar+')" class="btn-floating green"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($(".table"));
		}else{
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td><a onclick="activar_grupo('+elem.id+',\''+elem.nombre+'\','+tipoListar+')" class="btn-floating green" ><i  class="material-icons">autorenew</i></a></td>'+
			'</tr>').appendTo($(".table"));
		}
	});     
  }).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

  });
}

$("#listarAcademicos").click(function(e){
	tipoListar = 1;
	paginador = $(".pagination");
	$("#nombreTipo").text("Nombre del grupo");
	// cantidad de items por pagina
	var items = 2, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_grupos);
	grupos_init();
});

$("#listarTecnicos").click(function(e){
	tipoListar = 2;
	$("#nombreTipo").text("Nombre del grupo");
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 2, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_grupos);
	grupos_init();
});

$('#ver_grupos_activos').click(function(e){
  	if(ver_estado_grupo != "Activo"){
    	ver_estado_grupo = "Activo";
    	set_callback(get_data_callback_grupos); 
    	grupos_init();    
  	}
  
});

$('#ver_grupos_inactivos').click(function(e){
  	if(ver_estado_grupo != "Inactivo"){
    	ver_estado_grupo = "Inactivo";
    	set_callback(get_data_callback_grupos); 
    	grupos_init();
  	}
});

//ACCIONES DE LOS BOTONES
$("#agregar_grupo").click(function (e) {
	$.post(
		url_grupos,
		{
	  	nombre: $("#nombre").val(),
	  	grupoTipo: $("input[name='grupos']:checked").val(),
	  	token: $("#dpToken").val(),      
		},function(resp){
			switch(resp){
				case 'agregado':
					swal('Éxito','Grupo agregado.','success');
					grupos_init();
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

$("#modificar_grupo").click(function (e) {
	$.post(
		url_grupos,
		{
			id: pk_grupo,
	  		nombre: $("#mod_nombre").val(),
	  		grupoTipo: tipoGrupo,
	  		token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'modificado':
					swal('Éxito','Grupo modificado.','success');
					grupos_init();
					limpiarCampos();
					break;
				case 'camposFalta':
					swal('Error',resp,'info');
					break;
				case 'existente':
					swal('Error','Grupo existente.','error');
					break;
				default:
					swal('Error',resp,'error');
			}
	});
});

// funcion eliminar
$('#eliminar_grupo').click(function(e){
  	//peticion post
  	$.post(
		url_eliminar_grupos,
		{
	  	id: pk_grupo,
	  	estado:estado_grupo,
	  	grupoTipo: tipoGrupo,
	  	token: $("#dpToken").val(),    
		},function(resp){
	  	grupos_init();  
	  	swal(resp);
	});
});
$("#activar_grupo").click(function(e){
	//peticion post
  	$.post(
		url_eliminar_grupos,
		{
	  	id: pk_grupo,
	  	estado:estado_grupo,
	  	grupoTipo: tipoGrupo,
	  	token: $("#dpToken").val(),     
		},function(resp){
	  	grupos_init();  
	  	swal(resp);
	});
});

$("#cancelar_mod").click(function(e){
	limpiarCampos();
  	$('#formAgregarGrupo').show();
  	$('#formModGrupo').hide();
  	$('#formElimGrupo').hide();
});

$("#cancelar_eliminar").click(function(e){
	limpiarCampos();
  	$('#formAgregarGrupo').show();
  	$('#formModGrupo').hide();
  	$('#formElimGrupo').hide();
});

$("#cancelar_activar").click(function(e){
	limpiarCampos();
  	$('#formAgregarGrupo').show();
  	$('#formModGrupo').hide();
  	$('#formElimGrupo').hide();
});

//FIN DE ACCION DE BOTONES


function ver_grupo(pk,nombre, tipo){
	pk_grupo = pk;
	tipoGrupo = tipo;
	$('#formAgregarGrupo').hide();
	$('#formModGrupo').show();
  	$("label").addClass("active");
	$("#mod_nombre").val(nombre);
}

function eliminarGrupo(pk, nombre, tipo){
  	pk_grupo = pk;
  	tipoGrupo = tipo;
  	$('#formAgregarGrupo').hide();
  	$('#formModGrupo').hide();
  	$('#formElimGrupo').show();
  	if (tipo == 1) {
  		$('#confirmacion').text("¿Desea desactivar el grupo académico "+ nombre + "?");
  	}
  	if (tipo == 2) {
  		$('#confirmacion').text("¿Desea desactivar el grupo ténico "+ nombre + "?");
  	}
  	estado_grupo ="Inactivo";
  	limpiarCampos();
}


//Funcion para reactivar al personal
function activar_grupo(pk, nombre, tipo){
	pk_grupo = pk;
	estado_grupo ="Activo";
	tipoGrupo = tipo;
	$('#formActGrupo').show();
	$('#formElimGrupo').hide();
	$('#formModGrupo').hide();
	$('#formAgregarGrupo').hide();
	if (tipoGrupo == 1) {
		$("#confirmacion_activar").text("¿Desea activar el grupo académico "+ nombre + "?");
	}
	if (tipoGrupo == 2) {
		$("#confirmacion_activar").text("¿Desea activar el grupo ténico "+ nombre + "?");
	}
}

// funcion para reicinar todo
function grupos_init(){
	cargaPagina(0);  
  	$('#formAgregarGrupo').show();
	$('#formModGrupo').hide();
	$("#formElimGrupo").hide();
	$('#formActGrupo').hide();
}

//Funcion para limpar los campos del formulario
function limpiarCampos(){
	$("#nombre").val("");
  	$("label").removeClass("active");
	$("#mod_nombre").val("");
	$("input[name='grupos']:checked").prop('checked', false);
}