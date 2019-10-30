// url para las peticiones ajax 
var url_listar_codigos = "/privado/php/codigos/codigosListar.php",
	url_codigos = "/privado/php/codigos/codigos.php",
    url_eliminar_codigos = "/privado/php/codigos/eliminarCodigos.php";

// almace el id a modificar o eliminar
var pk_codigo, estado_codigo = "Inactivo", ver_estado_codigo = "Activo";

$(document).ready(function () {
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 10, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_codigo); 
	codigos_init();
	$('select').material_select();
 });

function get_data_callback_codigo(){
  $.ajax({
	data:{
	limit: itemsPorPagina,
	busqueda: $.trim($('input#buscar_codigo').val()),
	offset: desde,
	estado: ver_estado_codigo
	},
	type:"POST",
	url: url_listar_codigos
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
		if (ver_estado_codigo == 'Activo') {
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td>'+elem.nombreTipoCodigo+'</td>'+
			  '<td><a onclick="ver_codigo('+elem.id+',\''+elem.nombre+'\',\''+elem.descripcion+'\','+elem.idTipoCodigo+')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar" ><i  class="material-icons">edit</i></a> <a onclick="eliminar_codigo('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}else{
			$('<tr id="+elem.id+">'+                      
			  '<td>'+elem.nombre+'</td>'+
			  '<td>'+elem.nombreTipoCodigo+'</td>'+
			  '<td><a onclick="activar_codigo('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Reactivar" ><i  class="material-icons">autorenew</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}
	});     
  }).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

  });
}

$('#ver_codigos_activos').click(function(e){
  	if(ver_estado_codigo != "Activo"){
    	ver_estado_codigo = "Activo";
    	set_callback(get_data_callback_codigo); 
    	codigos_init();    
  	}
  
});

$('#ver_codigos_inactivos').click(function(e){
  	if(ver_estado_codigo != "Inactivo"){
    	ver_estado_codigo = "Inactivo";
    	set_callback(get_data_callback_codigo); 
    	codigos_init();
  	}
});

$('#buscar_codigo').keyup(function() {  
	codigos_init();
});

//ACCIONES DE LOS BOTONES
$('#agregar_codigo').click(function(e){

  	$.post(
		url_codigos,
		{
	  	nombre: $("#nombre").val(),
	  	descripcion: $("#descripcion").val(),
	  	token: $("#dpToken").val(),
	  	idTipoCodigo: $("#selectTipoCodigo").val()
		},function(resp){
			switch(resp){
				case 'agregado':
					swal('Éxito','Código agregado.','success');
					codigos_init();
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
$('#modificar_codigo').click(function(e){
	swal({
  		title: "¿Editar código?",
  		text: "Se editará el código",
  		type: "warning",
  		showCancelButton: true,
		confirmButtonColor: "#bbdefb",
  		confirmButtonText: "Sí, editar",
		cancelButtonText: "Cancelar",
  		closeOnConfirm: true,
  		closeOnCancel: true
	},
	function(isConfirm){
  		if (isConfirm) {
    		$.post(
				url_codigos,
				{
					id: pk_codigo,
			  		nombre: $("#mod_nombre").val(),
			  		token: $("#dpToken").val(),
			  		descripcion: $("#mod_descripcion").val(),
			  		idTipoCodigo: $("#selectTipoCodigoMod").val()
				},function(resp){
					switch(resp){
						case 'modificado':
							swal('Éxito','Código modificado.','success');
							codigos_init();
							limpiarCampos();
							break;
						case 'camposFalta':
							swal('Error',resp,'info');
							break;
						case 'existente':
							swal('Error','Código existente.','error');
							break;
						default:
							swal('Error',resp,'error');
					}
			});
  		} else {
    		Materialize.toast("No se editó el código", 3000, "rounded");
  		}
	});
});
// funcion eliminar
$('#eliminar_codigo').click(function(e){
  	//peticion post
  	$.post(
		url_eliminar_codigos,
		
		{
	  	id: pk_codigo,
	  	estado:estado_codigo,      
	  	token: $("#dpToken").val(),
		},function(resp){
	  	if (resp != "") {
	        codigos_init();
  	        swal("",resp,"info");    
	    }
	});
});
$("#activar_codigo").click(function(e){
	//peticion post
  	$.post(
		url_eliminar_codigos,
		{
	  	id: pk_codigo,
	  	estado:estado_codigo,   
	  	token: $("#dpToken").val(),   
		},function(resp){
		    if (resp != "") {
		        codigos_init();
	  	        swal("",resp,"info");    
		    }
	});
});

$("#cancelar_mod").click(function(e){
  	limpiarCampos();
  	$('#formAgregarCodigos').show();
	$('#formModCodigos').hide();
	$('#formElimCodigos').hide();
  	$('#formActCodigos').hide();
});

$("#cancelar_eliminar").click(function(e){
    limpiarCampos();
  	$('#formAgregarCodigos').show();
	$('#formModCodigos').hide();
	$('#formElimCodigos').hide();
  	$('#formActCodigos').hide();
});

$("#cancelar_activar").click(function(e){
    limpiarCampos();
  	$('#formAgregarCodigos').show();
	$('#formModCodigos').hide();
	$('#formElimCodigos').hide();
  	$('#formActCodigos').hide();
});

//FIN DE ACCION DE BOTONES


function ver_codigo(pk,nombre,descripcion,tipoCodigo){
	pk_codigo = pk;
	
	$('#formAgregarCodigos').hide();
	$('#formModCodigos').show();
	$('#formElimCodigos').hide();
  	$('#formActCodigos').hide();
	
  	$("label").addClass("active");
  	
	$("#mod_nombre").val(nombre);
  	$("#mod_descripcion").val(descripcion);
  	
  	$('#selectTipoCodigoMod').find('option[value="'+tipoCodigo+'"]').prop('selected', true);
	$("#selectTipoCodigoMod").material_select();

}

// confirmacion si desea eliminar
function eliminar_codigo(pk, nombre){
  	pk_codigo=pk;
  	$('#formElimCodigos').show();
  	$('#formActCodigos').hide();
	$('#formModCodigos').hide();
	$('#formAgregarCodigos').hide();
  	$('#confirmacion').text("¿Desea desactivar el código " + nombre + " ?");
  	estado_codigo ="Inactivo";

}

//Funcion para reactivar al personal
function activar_codigo(pk, nombre){
	pk_codigo = pk;
	$('#formActCodigos').show();
	$('#formElimCodigos').hide();
	$('#formModCodigos').hide();
	$('#formAgregarCodigos').hide();
	estado_codigo ="Activo";
	$("#confirmacion_activar").text("¿Desea activar el código " + nombre + " ?");
}

// funcion para reicinar todo
function codigos_init(){
	cargaPagina(0);  
  	$('#formElimCodigos').hide();
	$('#formModCodigos').hide();
	$('#formActCodigos').hide();
	$('#formAgregarCodigos').show();
}

//Funcion para limpar los campos del formulario
function limpiarCampos(){

	$("#nombre").val("");
  	$("#descripcion").val("");

  	$("label").removeClass("active");
  	$("input").removeClass("valid");
  	$("input").removeClass("invalid");

	$("#mod_nombre").val("");
  	$("#mod_descripcion").val("");
}