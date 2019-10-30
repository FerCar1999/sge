// url para las peticiones ajax 
var url_listar_asignatura = "/privado/php/asignaturas/asignaturasListar.php",
	url_asignatura = "/privado/php/asignaturas/asignaturas.php",
	//url_listar_asignaturas_checkbox = "/privado/php/asignaturas/asignaturasListarCheckBox.php",
	//url_obtener_grados = "/privado/php/asignaturas/gradosAsignaturas.php",
    url_eliminar_asignatura = "/privado/php/asignaturas/asignaturasEliminar.php";

// almace el id a modificar o eliminar
var pk_asignatura, estado_asignatura = "Inactivo", ver_estado_asignatura = "Activo", nombre_asignatura = "";

$(document).ready(function () {
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 10, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_asignaturas); 
	asignatura_init();
	$('select').material_select();
 });

function get_data_callback_asignaturas(){
  	$.ajax({
		data:{
		limit: itemsPorPagina,
		busqueda: $.trim($('input#buscar_asignatura').val()),
		offset: desde,
		estado: ver_estado_asignatura
		},
		type:"POST",
		url: url_listar_asignatura
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
			if (ver_estado_asignatura == 'Activo') {
				$('<tr id="+elem.id+">'+                      
			  		'<td>'+elem.nombre+'</td>'+
			  		'<td>'+elem.ngrado+'</td>'+
			  		'<td>'+elem.codigo+'</td>'+
			  		'<td><a onclick="ver_asignatura('+elem.id+',\''+elem.nombre+'\',\''+elem.codigo+'\','+elem.tipoAsignatura+','+elem.grado+',\''+elem.ngrado+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar" ><i  class="material-icons">edit</i></a> <a onclick="eliminarAsignatura('+elem.id+',\''+elem.nombre+'\',\''+elem.ngrado+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Inactivar"><i class="material-icons">delete</i></a></td>'+
				'</tr>').appendTo($(".table"));
        $('.tooltipped').tooltip();
			}else{
				$('<tr id="+elem.id+">'+                      
			  		'<td>'+elem.nombre+'</td>'+
			  		'<td>'+elem.ngrado+'</td>'+
			  		'<td>'+elem.codigo+'</td>'+
			  		'<td><a onclick="activar_asignatura('+elem.id+',\''+elem.nombre+'\',\''+elem.ngrado+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Reactivar" ><i  class="material-icons">autorenew</i></a></td>'+
				'</tr>').appendTo($(".table"));
        $('.tooltipped').tooltip();
			}
		});     
  	}).fail(function(jqXHR,textStatus,textError){
		alert("Error al realizar la petición dame".textError);
  	});
}

/*function llenarCheckboxAsignaturas(nombre) {
	$.ajaxSetup({async: false});
	$.ajax({
		type:'POST',
		url:url_listar_asignaturas_checkbox,
		data:{
		    nombre: nombre,
		    estado: estado_asignatura
		},
		success: function(valores){
			var resp = eval(valores);
			var idAsign = [];
			var nombreGrupo = [];
			var estadoAsign = [];
			var i = 0;
			$.each(resp,function(index, value){
				idAsign.push(value.id_asignatura);
			});
			$.each(resp,function(index, value){
				nombreGrupo.push(value.nombre);
			});
			$.each(resp,function (index,value) {
				estadoAsign.push(value.estado);
			})
			$("#gradosAsignar").html("");
			for (var m = 0; m < idAsign.length; m++) {
				$("<div class='col l3 s12 m6'>"
					+"<input type='checkbox' id='mod_"+idAsign[m]+"' name='mod_gradosAsignatura'"
					+ "class='"+idAsign[m]+"' /><label for='mod_"+idAsign[m]+"'>"+nombreGrupo[m]+"</label></div>").appendTo("#gradosAsignar");			
			}
			$.each($("input[name='mod_gradosAsignatura']"), function() {
				if (estadoAsign[i] == "Activo") {
					console.log(estadoAsign[i]);
					var idText = "mod_"+idAsign[i];
					$("input[id='"+idText+"'][name='mod_gradosAsignatura']").prop("checked",true);
				}
				i++;
	        });	
			return false;
		}
	});
	return false;
}*/

$('#ver_asignaturas_activas').click(function(e){
  	if(ver_estado_asignatura != "Activo"){
    	ver_estado_asignatura = "Activo";
    	set_callback(get_data_callback_asignaturas); 
    	asignatura_init();    
  	}
  
});

$('#ver_asignaturas_inactivas').click(function(e){
  	if(ver_estado_asignatura != "Inactivo"){
    	ver_estado_asignatura = "Inactivo";
    	set_callback(get_data_callback_asignaturas); 
    	asignatura_init();
  	}
});

$('#buscar_asignatura').keyup(function() {  
	asignatura_init();
});

//ACCIONES DE LOS BOTONES
$("#agregar_asignatura").click(function (e) {
    $.ajaxSetup({async: false});
    /*$.each($("input[name='gradosAsignatura[]']:checked"), function() {
        //alert($(this).attr("id"));
        
    });*/
    $.post(
    	url_asignatura,
    	{
      	codigo: $("#codigoAsignatura").val(),
      	nombre: $("#nombre").val(),
      	tipoAsignatura: $("#selectTipoAsignatura").val(),
      	id_grado: $("#selectGrado").val(),
        token: $("#dpToken").val(),
      	estado: 'Activo'
    	},function(resp){
    		console.log(resp);
    		switch(resp){
    			case 'agregado':
    				swal('Éxito','Asignatura agregada.','success');
					asignatura_init();
					limpiarCampos();
    				break;
    			case 'camposFalta':
    				swal('Error',resp,'info');
    				break;
    			default:
    			    swal('Error',resp,'error');
    			    break;
    		}
    });
    /*$.each($("input[name='gradosAsignatura[]']:not(:checked)"), function() {
        //alert($(this).attr("id"));
        $.post(
        	url_asignatura,
        	{
          	codigo: $("#codigoAsignatura").val(),
          	nombre: $("#nombre").val(),
          	tipoAsignatura: $("#selectTipoAsignatura").val(),
          	id_grado: $(this).attr("id"),
          	estado: 'Inactivo'
          	
        	},function(resp){
        		switch(resp){
        			case 'agregado':
        				agregado = true;
        				break;
        			case 'camposFalta':
        				camposFalta = true;
        				respM = resp;
        				break;
        			default:
        			    defaultSwitch = true;
        			    respM = resp;
        			    break;
        		}
        });
    });*/
    
});

$('#modificar_asignatura').click(function(e){
	$.ajaxSetup({async: false});
	var modificado = false, camposFalta = false, defaultSwitch = false;
    var respM = "";
    /*$.each($("input[name='mod_gradosAsignatura']:checked"), function() {
    });*/
    $.post(
    	url_asignatura,
    	{
    	id: pk_asignatura,
      	codigo: $("#mod_codigoAsignatura").val(),
      	nombre: $("#mod_nombre").val(),
        token: $("#dpToken").val(),
      	tipoAsignatura: $("#mod_selectTipoAsignatura").val(),
      	id_grado: $("#mod_selectGrado").val(),
      	estado: 'Activo'
      	
    	},function(resp){
    		switch(resp){
    			case 'modificado':
    				modificado = true;
    				break;
    			case 'camposFalta':
    				camposFalta = true;
    				respM = resp;
    				break;
    			default:
    			    defaultSwitch = true;
    			    respM = resp;
    			    break;
    		}
    });
    /*$.each($("input[name='mod_gradosAsignatura']:not(:checked)"),function(){
    	$.post(
        	url_asignatura,
        	{
        	id: pk_asignatura,
          	codigo: $("#mod_codigoAsignatura").val(),
          	nombre: $("#mod_nombre").val(),
          	tipoAsignatura: $("#mod_selectTipoAsignatura").val(),
          	estado: 'Inactivo'
          	
        	},function(resp){
        		switch(resp){
        			case 'modificado':
        				modificado = true;
        				break;
        			case 'camposFalta':
        				camposFalta = true;
        				respM = resp;
        				break;
        			default:
        			    defaultSwitch = true;
        			    respM = resp;
        			    break;
        		}
        });
    });*/
    if(modificado){
        swal('Éxito','Asignatura modificada.','success');
		asignatura_init();
		limpiarCampos();
    }
    if(camposFalta){
        swal('Error',respM,'info');
    }
    if(defaultSwitch){
        swal('Error',respM,'error');
    }
});
// funcion eliminar
$('#eliminar_asginatura').click(function(e){
  	//peticion post
  	$.ajaxSetup({async: false});
  	var respE = "";
  	$.post(
		url_eliminar_asignatura,
		{
	  	id: pk_asignatura,
	  	estado:estado_asignatura,    
      token: $("#dpToken").val(),  
		},function(resp){
			respE = resp;
			asignatura_init();
	});
	swal("",respE,"info");
});
$("#activar_asignatura").click(function(e){
	/*swal({
		title: "¿Seguro de activar?",
		text: "",
		type: "info",
		showCancelButton: true,
		confirmButtonColor: "#ff6f00",
		confirmButtonText: "Aceptar",
		closeOnConfirm: true
	},
	function(){
		
	});*/

	//peticion post
	$.post(
			url_eliminar_asignatura,
			{
		  	id: pk_asignatura,
		  	estado:estado_asignatura,      
			},function(resp){
		  	asignatura_init();  
			swal("",resp,"info");	  	
		});
});

$("#cancelar_mod").click(function(e){
    limpiarCampos();
  	$('.formAsignatura').show();
  	$('.formAsignaturaMod').hide();
  	$('.formAsignaturaElim').hide();
  	$('.formAsignaturaAct').hide();
});

$("#cancelar_eliminar").click(function(e){
  	limpiarCampos();
  	$('.formAsignatura').show();
  	$('.formAsignaturaMod').hide();
  	$('.formAsignaturaElim').hide();
  	$('.formAsignaturaAct').hide();
});

$("#cancelar_activar").click(function(e){
  	limpiarCampos();
  	$('.formAsignatura').show();
  	$('.formAsignaturaMod').hide();
  	$('.formAsignaturaElim').hide();
  	$('.formAsignaturaAct').hide();
});

//FIN DE ACCION DE BOTONES



function ver_asignatura(pk,nombre,codigo,tipoAsignatura,grado){
	pk_asignatura = pk;
	nombre_asignatura = nombre;
	
	$('.formAsignatura').hide();
	$('.formAsignaturaMod').show();
	$("label").addClass("active");
	$("label#labelGrado").removeClass("active");
	$("label#labelTipo").removeClass("active");
  	
	$("#mod_nombre").val(nombre);
  	$("#mod_codigoAsignatura").val(codigo);
  	
  	$('#mod_selectTipoAsignatura').find('option[value="'+tipoAsignatura+'"]').prop('selected', true);
	$("#mod_selectTipoAsignatura").material_select();

	$('#mod_selectGrado').find('option[value="'+grado+'"]').prop('selected', true);
	$("#mod_selectGrado").material_select();

	$("select").material_select();
	
	//llenarCheckboxAsignaturas(nombre);

}

// confirmacion si desea eliminar
function eliminarAsignatura(pk, nombre, ngrado){
  	pk_asignatura=pk;
  	nombre_asignatura = nombre;
  	
  	$('.formAsignatura').hide();
  	$('.formAsignaturaMod').hide();
  	$('.formAsignaturaElim').show();
  	$('#confirmacion').text("¿Desea desactivar la asignatura "+ nombre + " de "+ ngrado + "?");
  	estado_asignatura ="Inactivo";

}

//Funcion para reactivar al personal
function activar_asignatura(pk, nombre, ngrado){
	
	pk_asignatura = pk;
	nombre_asignatura = nombre;
	
	$('.formAsignaturaAct').show();
	$('.formAsignaturaElim').hide();
	$('.formAsignaturaMod').hide();
	$('.formAsignatura').hide();
	estado_asignatura = "Activo";
	$("#confirmacion_activar").text("¿Desea activar la asignatura "+ nombre + " de "+ ngrado +"?")
}

// funcion para reicinar todo
function asignatura_init(){
	cargaPagina(0);  
  	$('.formAsignaturaElim').hide();
	$('.formAsignaturaMod').hide();
	$('.formAsignaturaAct').hide();
	$('.formAsignatura').show();
}

//Funcion para limpar los campos del formulario
function limpiarCampos(){

    $("#nombre").val("");
  	$("#codigoAsignatura").val("");

  	$("label").removeClass("active");
  	$("input").removeClass("valid");
  	$("input").removeClass("invalid");

	$("#mod_nombre").val("");
  	$("#mod_codigoAsignatura").val("");
}