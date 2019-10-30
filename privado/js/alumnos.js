// url para las peticiones ajax 
var url_listar_alumnos = "/privado/php/alumnos/alumnosListar.php",
	url_alumnos = "/privado/php/alumnos/alumnos.php",
	url_eliminar_alumnos = "/privado/php/alumnos/eliminarAlumno.php",
	url_modificar_clave = "/privado/php/alumnos/cambioClave.php";

// almace el id a modificar o eliminar
var pk_alumno, estado_alumno = "Inactivo", foto_antigua, ver_estado_alumno = "Activo";

$(document).ready(function () {
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 10, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_alumnos); 
	alumno_init();
	$('select').material_select();
	$('.modal').modal();
 });

function get_data_callback_alumnos(){
  	$.ajax({
		data:{
		limit: itemsPorPagina,              
		offset: desde,
		busqueda: $.trim($('input#buscar_alumno').val()),
		estado: ver_estado_alumno
		},
		type:"POST",
		url: url_listar_alumnos
  	}).done(function(data,textStatus,jqXHR){
	// obtiene la clave lista del json data
	var lista = data.lista;
	$(".table").html("");
	// si es necesario actualiza la cantidad de paginas del paginador
	if(pagina==0){
	  	creaPaginador(data.cantidad);
	}
	// genera el cuerpo de la tabla
	$.each(lista, function(ind, elem){
		if (ver_estado_alumno == 'Activo') {
			$('<tr id="+elem.id+">'+                      
				'<td>'+elem.codigo+'</td>'+
			  	'<td>'+elem.nombres+'</td>'+
			  	'<td>'+elem.apellidos+'</td>'+
			  	'<td>'+elem.correo+'</td>'+
			  	'<td><a onclick="ver_alumno('+elem.id+',\''+elem.nombres+'\',\''+elem.apellidos+'\',\''+elem.codigo+'\',\''+elem.correo+'\',\''+elem.foto+'\','+elem.idEspecialiad+','+elem.idGrado+','+elem.idSeccion+','+elem.idGrupoAcad+','+elem.idGrupoTec+','+ elem.idPersonal +')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar" ><i  class="material-icons">edit</i></a> <a onclick="eliminar_alumno('+elem.id+',\''+elem.nombres+'\',\''+ elem.apellidos +'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar alumno"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}else{
			$('<tr id="+elem.id+">'+        
				'<td>'+elem.codigo+'</td>'+              
			  	'<td>'+elem.nombres+'</td>'+
			  	'<td>'+elem.apellidos+'</td>'+
			  	'<td>'+elem.correo+'</td>'+
			  	'<td><a onclick="activar_alumno('+elem.id+',\''+elem.nombres+'\',\''+elem.apellidos+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Reactivar alumno"><i  class="material-icons">autorenew</i></a></td>'+
			'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}
	});     
  	}).fail(function(jqXHR,textStatus,textError){
		alert("Error al realizar la petición dame".textError);
  	});
}

$('#buscar_alumno').keyup(function() {  
	alumno_init();
});

$('#ver_alumnos_activos').click(function(e){
  	if(ver_estado_alumno != "Activo"){
    	ver_estado_alumno = "Activo";
    	set_callback(get_data_callback_alumnos); 
    	alumno_init();    
  	}
  
});

$('#ver_alumnos_inactivos').click(function(e){
  	if(ver_estado_alumno != "Inactivo"){
    	ver_estado_alumno = "Inactivo";
    	set_callback(get_data_callback_alumnos); 
    	alumno_init();
  	}
});

$(document).ready(function(){
	$("#formAlumnos").on("submit", function(e){
		e.preventDefault();
		var formData = new FormData(this);
		///DATOS PARA GUARDAR EL PERSONAL
		formData.append('nombre',$("#nombre").val());
		formData.append('apellido',$("#apellido").val());
		formData.append('correo',$("#correo").val());
		formData.append('codigo',$("#codigo").val());
		formData.append('clave',$("#clave").val());
		formData.append('idEspecialidad',$("#selectEspecialiad").val());
		formData.append('idGrado',$("#selectGrado").val());
		formData.append('idSeccion',$("#selectSeccion").val());
		formData.append('idGrupoTec',$("#selectGrupoTec").val());
		formData.append('idGrupoAcad',$("#selectGrupoAcad").val());
		formData.append('idPersonal',$("#selectGuia").val());
		formData.append('token',$("#dpToken").val());
		
		//Función de AJAX para guardar el personal con foto
		$.ajax({
			url: url_alumnos,
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			success: function(resp){
				switch(resp){
					case 'agregado':
						Materialize.toast('Estudiante agregado exitosamente', 3000, 'rounded');
						alumno_init();
						limpiarCampos();
						break;
					case 'camposFalta':
						Materialize.toast(resp, 3000, 'rounded');
						break;
					default:
						Materialize.toast(resp, 3000, 'rounded');
				}
				//alert(resp);
			}
		});
	});
	$("#formAlumnoMod").on("submit",function(e){
		e.preventDefault();
		var formData = new FormData(this);
		console.log(pk_alumno);
		formData.append('id',pk_alumno);
		formData.append('nombre', $("#mod_nombre").val());
	  	formData.append('apellido',$("#mod_apellido").val());
	  	formData.append('codigo',$("#mod_codigo").val());
	  	formData.append('correo',$("#mod_correo").val());
	  	formData.append('url_foto', foto_antigua);
	  	formData.append('idEspecialidad',$("#selectEspecialiadMod").val());
		formData.append('idGrado',$("#selectGradoMod").val());
		formData.append('idSeccion',$("#selectSeccionMod").val());
		formData.append('idGrupoTec',$("#selectGrupoTecMod").val());
		formData.append('idGrupoAcad',$("#selectGrupoAcadMod").val());
		formData.append('idPersonal',$("#selectGuiaMod").val());
		formData.append('token',$("#dpToken").val());

	  	$.ajax({
	  		url: url_alumnos,
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			success: function(resp){
				Materialize.toast(resp, 3000, 'rounded');
				alumno_init();
				limpiarCampos();
			}
	  	});

	});
});

//ACCIONES DE LOS BOTONES
$('#agregar_alumno').click(function(e){
  	$("#formAlumnos").submit();
});
$('#modificar_alumno').click(function(e){
	$("#formAlumnoMod").submit();
});
// funcion eliminar
$('#eliminar_alumno').click(function(e){
  	//peticion post
  	$.post(
		url_eliminar_alumnos,
		{
	  	id: pk_alumno,
	  	estado:estado_alumno,  
	  	token: $("#dpToken").val(),    
		},function(resp){
	  	alumno_init();  
	  	Materialize.toast(resp, 3000, 'rounded');
	});
});
$("#activar_alumno").click(function(e){
	//peticion post
  	$.post(
		url_eliminar_alumnos,
		{
	  	id: pk_alumno,
	  	estado:estado_alumno, 
	  	token: $("#dpToken").val(),     
		},function(resp){
	  	alumno_init();  
	  	Materialize.toast(resp, 3000, 'rounded');
	});
});

$("#modificarClave").click(function(e){
	$.post(
		url_modificar_clave,
		{
			id: pk_alumno,
			clave: $("#mod_clave").val(),
			token: $("#dpToken").val()
		}, function(resp){
			alumno_init();
			Materialize.toast(resp, 3000, 'rounded');
			$('#modalClave').modal('close');
			$("#mod_clave").val("");
	});
});

$("#cancelar_mod").click(function(e){
  	$('.agregar_alumno').show();
  	limpiarCampos();
  	$('.modificar_alumno').hide();
  	$('.eliminar_alumno').hide();
});

$("#cancelar_eliminar").click(function(e){
  	$('.agregar_alumno').show();
  	$('.modificar_alumno').hide();
  	$('.eliminar_alumno').hide();
});

$("#cancelar_activar").click(function(e){
  	$('.agregar_alumno').show();
  	$('.modificar_alumno').hide();
  	$('.eliminar_alumno').hide();
});

//FIN DE ACCION DE BOTONES

function readURL(input) {
  	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('#img_alumno').attr('src', e.target.result);
			$('#img_alumno_mod').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
  	}
}

$("#fotoAlumno").change(function(){
  	readURL(this);
});

$("#fotoAlumnoMod").change(function(){
  	readURL(this);
});


function ver_alumno(pk,nombre,apellido,codigo,correo,foto,idEspecialiad,idGrado,idSeccion,idGrupoAcad,idGrupoTec,idPersonal){
	pk_alumno = pk;
	foto_antigua = foto;
	$('.agregar_alumno').hide();
	$('.modificar_alumno').show();
  	$("label").addClass("active");
  	$("label[for='select']").removeClass("active");
  	
	$("#mod_nombre").val(nombre);
  	$("#mod_apellido").val(apellido);
  	$("#mod_correo").val(correo);
  	$("#mod_codigo").val(codigo);

  	$('#selectEspecialiadMod').find('option[value="'+idEspecialiad+'"]').prop('selected', true);
	$('#selectGradoMod').find('option[value="'+idGrado+'"]').prop('selected', true);
	$('#selectSeccionMod').find('option[value="'+idSeccion+'"]').prop('selected', true);
	$('#selectGrupoAcadMod').find('option[value="'+idGrupoAcad+'"]').prop('selected', true);
	$('#selectGrupoTecMod').find('option[value="'+idGrupoTec+'"]').prop('selected', true);
	$('#selectGuiaMod').find('option[value="'+idPersonal+'"]').prop('selected', true);
	$("select").material_select();
	if ($("#selectGradoMod").find(":selected").text() == "Noveno Grado"
		|| $("#selectGradoMod").find(":selected").text() == "Octavo Grado"
		|| $("#selectGradoMod").find(":selected").text() == "Séptimo Grado") {
		$("#divModSelectAcad").hide('fast');
		$("#divModSelectTec").hide('fast');
		$("#divModSelectEsp").hide('fast');
	}else{
		$("#divModSelectAcad").show('fast');
		$("#divModSelectTec").show('fast');
		$("#divModSelectEsp").show('fast');
	}
  	$('#img_alumno_mod').attr('src',foto);
}

// confirmacion si desea eliminar
function eliminar_alumno(pk, nombre, apellido){
  	pk_alumno=pk;
  	$('.agregar_alumno').hide();
  	$('.modificar_alumno').hide();
  	$('.eliminar_alumno').show();
  	$('#confirmacion').text("¿Desea desactivar a "+ nombre + " " +apellido +"?");
  	estado_alumno ="Inactivo";

}

//Funcion para reactivar al personal
function activar_alumno(pk, nombre, apellido){
	pk_alumno = pk;
	$('.activar_alumno').show();
	$('.eliminar_alumno').hide();
	$('.modificar_alumno').hide();
	$('.agregar_alumno').hide();
	estado_alumno ="Activo";
	$("#confirmacion_activar").text("¿Desea activar a "+ nombre + " " + apellido +"?")
}

// funcion para reicinar todo
function alumno_init(){
	cargaPagina(0);  
  	$('.eliminar_alumno').hide();
	$('.modificar_alumno').hide();
	$('.activar_alumno').hide();
	$('.agregar_alumno').show();
}

//Funcion para limpar los campos del formulario
function limpiarCampos(){

	$("#nombre").val("");
  	$("#apellido").val("");
  	$("#correo").val("");
  	$("#codigo").val("");
  	$("#clave").val("");
  	$("#buscar_alumno").val("");

  	$("label").removeClass("active");
  	$("input").removeClass("valid");
  	$("input").removeClass("invalid");

  	$('#img_alumno').attr('src',"/media/img/user_default.jpg");

	$("#mod_nombre").val("");
  	$("#mod_apellido").val("");
  	$("#mod_correo").val("");
	$("#mod_codigo").val("");
	$("#mod_clave").val("");
  	$('#img_alumno_mod').attr('src',"/media/img/user_default.jpg");

  	$('#selectEspecialiad').find('option[value="0"]').prop('selected', true);
  	$('#selectGrado').find('option[value="0"]').prop('selected', true);
  	$('#selectGrupoAcad').find('option[value="0"]').prop('selected', true);
  	$('#selectGrupoTec').find('option[value="0"]').prop('selected', true);
	$('#selectSeccion').find('option[value="0"]').prop('selected', true);
	$('#selectGuia').find('option[value="0"]').prop('selected', true);

  	$("select").material_select();

  	$("#divSelectAcad").show('fast');
	$("#divSelectTec").show('fast');
	$("#divSelectEsp").show('fast');
	$("#divModSelectAcad").show('fast');
	$("#divModSelectTec").show('fast');
	$("#divModSelectEsp").show('fast');

}

$("#selectGrado").change(function(){
	if ($("#selectGrado").find(":selected").text() == "Noveno Grado"
		|| $("#selectGrado").find(":selected").text() == "Octavo Grado"
		|| $("#selectGrado").find(":selected").text() == "Séptimo Grado") {
		$("#divSelectAcad").hide('fast');
		$("#divSelectTec").hide('fast');
		$("#divSelectEsp").hide('fast');
	}else{
		$("#divSelectAcad").show('fast');
		$("#divSelectTec").show('fast');
		$("#divSelectEsp").show('fast');
	}
});

$("#selectGradoMod").change(function(){
	if ($("#selectGradoMod").find(":selected").text() == "Noveno Grado"
		|| $("#selectGradoMod").find(":selected").text() == "Octavo Grado"
		|| $("#selectGradoMod").find(":selected").text() == "Séptimo Grado") {
		$("#divModSelectAcad").hide('fast');
		$("#divModSelectTec").hide('fast');
		$("#divModSelectEsp").hide('fast');
	}else{
		$("#divModSelectAcad").show('fast');
		$("#divModSelectTec").show('fast');
		$("#divModSelectEsp").show('fast');
	}
});