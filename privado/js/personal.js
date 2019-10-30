// url para las peticiones ajax 
var url_listar_personal = "/privado/php/personal/personal_listar.php",
url_personal = "/privado/php/personal/personal.php",
url_permisos_listar = "/privado/php/personal/permisosPersonal.php",
url_asistencias_diferidas = "/privado/php/asistencias_diferidas/obtener_asignaturas.php",
url_asistencias_diferidas_historial = "/privado/php/asistencias_diferidas/cargar_perfi.php",
url_guardar_asistencias_diferidas = "/privado/php/asistencias_diferidas/guardar_asistencias.php",
url_ver_horario = "/privado/php/reportes/horarios/reporteHorarioPersonal.php",
url_eliminar_personal = "/privado/php/personal/eliminar_personal.php",
url_ver_asistencia="/privado/php/asistencias_diferidas/cargarListaAdministrador.php";

// almace el id a modificar o eliminar
var pk_personal, estado_personal = "Inactivo", foto_antigua, ver_estado_personal = "Activo";check_siempre="";


function get_data_callback_personal(){
	$.ajax({
		data:{
			limit: itemsPorPagina,              
			offset: desde,
			busqueda: $.trim($('input#buscar_personal').val()),
			estado: ver_estado_personal                  
		},
		type:"POST",
		url: url_listar_personal
	}).done(function(data,textStatus,jqXHR){

	// obtiene la clave lista del json data
	var lista = data.lista;
	$(".table").html("")
	
	// si es necesario actualiza la cantidad de paginas del paginador
	if(pagina==0){
		creaPaginador(data.cantidad);
	}

	// linea para llamar a las diferidas'<td><a onclick="ver_personal('+elem.id+',\''+elem.nombre+'\',\''+elem.apellido+'\',\''+elem.codigo+'\',\''+elem.correo+'\',\''+elem.foto+'\','+elem.permiso+')" class="btn-floating green" ><i  class="material-icons">edit</i></a> <a onclick="eliminar_personal('+elem.id+',\''+elem.nombre+'\',\''+ elem.apellido +'\')" class="btn-floating green"><i class="material-icons">delete</i></a> <a onclick="asistencias_diferidas('+elem.id+',\''+elem.nombre+'\',\''+ elem.apellido +'\')" class="btn-floating green"><i class="material-icons">alarm</i></a></td>'+
	// genera el cuerpo de la tabla
	$.each(lista, function(ind, elem){
		if (ver_estado_personal == 'Activo') {
			$('<tr id="+elem.id+">'+                      
				'<td>'+elem.codigo+'</td>'+
				'<td>'+elem.nombre+'</td>'+
				'<td>'+elem.apellido+'</td>'+
				'<td>'+elem.correo+'</td>'+
				'<td><a onclick="ver_personal('+elem.id+',\''+elem.nombre+'\',\''+elem.apellido+'\',\''+elem.codigo+'\',\''+elem.correo+'\',\''+elem.foto+'\','+elem.permiso+')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar información"><i  class="material-icons">edit</i></a> <a onclick="eliminar_personal('+elem.id+',\''+elem.nombre+'\',\''+ elem.apellido +'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar"><i class="material-icons">delete</i></a> <a onclick="ver_horario('+elem.id+')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Horario"><i class="material-icons">alarm</i></a> <a onclick="asistencias_diferidas('+elem.id+',\''+elem.nombre+'\',\''+ elem.apellido +'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Asistencia diferida"><i class="material-icons">beenhere</i></a></td>'+
				'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}else{
			$('<tr id="+elem.id+">'+     
				'<td>'+elem.codigo+'</td>'+                 
				'<td>'+elem.nombre+'</td>'+
				'<td>'+elem.apellido+'</td>'+
				'<td>'+elem.correo+'</td>'+
				'<td><a onclick="activar_personal('+elem.id+',\''+elem.nombre+'\',\''+elem.apellido+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Activar"><i  class="material-icons">autorenew</i></a></td>'+
				'</tr>').appendTo($(".table"));
			$('.tooltipped').tooltip();
		}
	});     
}).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la petición dame".textError);

});
}
//funcion para ver el horario
function ver_horario(pk){
	//console.log(pk);
	var win = window.open((url_ver_horario+"?personal="+pk), '_blank');
  	win.focus();

}

$('#buscar_personal').keyup(function() {  
	personal_init();
});

$('#ver_personal_activo').click(function(e){
	if(ver_estado_personal != "Activo"){
		ver_estado_personal = "Activo";
		set_callback(get_data_callback_personal); 
		personal_init();    
	}

});

$('#ver_personal_inactivo').click(function(e){
	if(ver_estado_personal != "Inactivo"){
		ver_estado_personal = "Inactivo";
		set_callback(get_data_callback_personal); 
		personal_init();
	}
});

$(document).ready(function(){
	$('.modal').modal();	
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 10, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_personal); 
	personal_init();
	$('select').material_select();
	$("#formPersonal").on("submit", function(e){
		e.preventDefault();
		var formData = new FormData(this);
		///DATOS PARA GUARDAR EL PERSONAL
		formData.append('nombre',$("#nombre").val());
		formData.append('apellido',$("#apellido").val());
		formData.append('correo',$("#correo").val());
		formData.append('codigo',$("#codigo").val());
		formData.append('clave',$("#clave").val());
		formData.append('claveR',$("#claveR").val());
		formData.append('permiso',$("#selectPermiso").val());
		//Función de AJAX para guardar el personal con foto
		$.ajax({
			url: url_personal,
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			success: function(resp){
				switch(resp){
					case 'agregado':
						swal('Éxito','Personal agregado.','success');
						personal_init();
						limpiarCampos();
						break;
						case 'camposFalta':
							swal('Error',resp,'error');
							break;
							default:
							swal('Error',resp,'error');
						}
				//alert(resp);
			}
		});
	});
	$("#formPersonalMod").on("submit",function(e){
		e.preventDefault();
		var formData = new FormData(this);
		
		formData.append('id',pk_personal);
		formData.append('nombre', $("#mod_nombre").val());
		formData.append('apellido',$("#mod_apellido").val());
		formData.append('codigo',$("#mod_codigo").val());
		formData.append('correo',$("#mod_correo").val());
		formData.append('url_foto', foto_antigua);
		formData.append('permiso',$("#selectPermisoMod").val());

		$.ajax({
			url: url_personal,
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			success: function(resp){
				Materialize.toast(resp, 5000, 'rounded');
				personal_init();
				limpiarCampos();
			}
		});

	});
});

//ACCIONES DE LOS BOTONES
$('#agregar_personal').click(function(e){
	$("#formPersonal").submit();
});
$('#modificar_personal').click(function(e){
	$("#formPersonalMod").submit();
});
// funcion eliminar
$('#eliminar_personal').click(function(e){
  	//peticion post
  	$.post(
  		url_eliminar_personal,
  		{
  			id: pk_personal,
  			estado:estado_personal,      
  		},function(resp){
  			personal_init();  
  			Materialize.toast(resp, 3000, 'rounded');
  		});
  });
$("#activar_personal").click(function(e){
	//peticion post
	$.post(
		url_eliminar_personal,
		{
			id: pk_personal,
			estado:estado_personal,      
		},function(resp){
			personal_init();  
			swal(resp);
		});
});

$("#cancelar_mod").click(function(e){
	$('.agregar_personal').show();
	limpiarCampos();
	$('.modificar_personal').hide();
	$('.eliminar_personal').hide();
});

$("#cancelar_eliminar").click(function(e){
	$('.agregar_personal').show();
	$('.modificar_personal').hide();
	$('.eliminar_personal').hide();
});

$("#cancelar_activar").click(function(e){
	$('.agregar_personal').show();
	$('.modificar_personal').hide();
	$('.eliminar_personal').hide();
});

//FIN DE ACCION DE BOTONES

function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('#img_personal').attr('src', e.target.result);
			$('#img_personal_mod').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$("#fotoPersonal").change(function(){
	readURL(this);
});

$("#fotoPersonalMod").change(function(){
	readURL(this);
});


function ver_personal(pk,nombre,apellido,codigo,correo,foto,permiso){
	pk_personal = pk;
	foto_antigua = foto;
	$('.agregar_personal').hide();
	$('.modificar_personal').show();
	$("label").addClass("active");

	$("#mod_nombre").val(nombre);
	$("#mod_apellido").val(apellido);
	$("#mod_correo").val(correo);
	$("#mod_codigo").val(codigo);

	$('#selectPermisoMod').find('option[value="'+permiso+'"]').prop('selected', true);
	$("#selectPermisoMod").material_select();

	$('#img_personal_mod').attr('src',foto);
}
function asistencias_diferidas(pk,nombre, apellido){
	$(".hidden-field").hide("fast");
	pk_personal = pk;	
	$('#modal1').modal('open');
	$("#profesor").text(nombre+" "+apellido);
	$.ajaxSetup({async: false});
	$.ajax({ 		 		
		data:{
			id: pk, 		
		},
		type:"POST",
		url:url_asistencias_diferidas
	}).done(function(data,textStatus,jqXHR){

		// obtiene la clave lista del json data
		var lista = data.lista;
		$("#select_materias").html("");
		
		// si es necesario actualiza la cantidad de paginas del paginador

		// genera el cuerpo de la tabla
		$.each(lista, function (i, item) {			

			$('#select_materias').append($('<option>', { 
				value: item.id,
				text : item.nombre 
			}));
			$('select').material_select();
		});
	}).fail(function(jqXHR,textStatus,textError){
		//alert("Error al realizar la peticion dame".textError);
	});
	$.ajax({ 		 		
		data:{
			id: pk, 		
		},
		type:"POST",
		url:url_asistencias_diferidas_historial
	}).done(function(data,textStatus,jqXHR){

		// obtiene la clave lista del json data
		var lista = data.lista;
		
		// genera el cuerpo de la tabla
		$.each(lista, function (i, item) {		
			
			$('#select_materias').find('option[value="'+item.id+'"]').prop('selected', true);
		 	$("#hora").val(item.hora);
			$("#horalbl").addClass("active");
			$('select').material_select();
			if(siempre==1){
				$('#check_siempre').val(true);
				check_siempre = true;
			}else {
				$('#check_siempre').val(false);
				check_siempre = false;
			}
		});
	}).fail(function(jqXHR,textStatus,textError){
		//alert("Error al realizar la peticion dame".textError);
	});
}
function guardar_asistencia_diferida(){

	$.ajaxSetup({async: false});
	for (var i = 0; i < $("#select_materias").val().length; i++) {		
		$.post(
			url_guardar_asistencias_diferidas,
			{
				id: pk_personal,
				id_horario: $("#select_materias").val()[i],
				hora: $("#hora").val(),
				siempre : check_siempre,
			},function(resp){				
			});
	}
	swal('Éxito',"Asistencias guardadas.",'info');
}
function ver_asistencia_maestro(){

	$.ajaxSetup({async: false});
	for (var i = 0; i < $("#select_materias").val().length; i++) {		
		$.post(
			url_ver_asistencia,
			{
				id_personal: pk_personal,
				id: $("#select_materias").val()[i],				
			},function(resp){	
				window.location = "/asistencia-administrador";			
			});
	}
	
}
// confirmacion si desea eliminar
function eliminar_personal(pk, nombre, apellido){
	pk_personal=pk;
	$('.agregar_personal').hide();
	$('.modificar_personal').hide();
	$('.eliminar_personal').show();
	$('#confirmacion').text("¿Desea desactivar a "+ nombre + " " +apellido +"?");
	estado_personal ="Inactivo";

}

//Funcion para reactivar al personal
function activar_personal(pk, nombre, apellido){
	pk_personal = pk;
	$('.activar_personal').show();
	$('.eliminar_personal').hide();
	$('.modificar_personal').hide();
	$('.agregar_personal').hide();
	estado_personal ="Activo";
	$("#confirmacion_activar").text("¿Desea activar a "+ nombre + " " + apellido +"?")
}
// funcion para reicinar todo
function personal_init(){
	cargaPagina(0);  
	$('.eliminar_personal').hide();
	$('.modificar_personal').hide();
	$('.activar_personal').hide();
	$('.agregar_personal').show();
}
$('#check_siempre').change(function() {
	if($(this).is(":checked")) {
		swal('Éxito','El docente podrá realizar la asistencia en cualquier momento.','success');
		$(this).attr("checked", true);		
	}
	$('#check_siempre').val($(this).is(':checked'));
	check_siempre=$('#check_siempre').prop("checked")
});
//Funcion para limpar los campos del formulario
function limpiarCampos(){

	$("#nombre").val("");
	$("#apellido").val("");
	$("#correo").val("");
	$("#codigo").val("");
	$("#clave").val("");
	$("#claveR").val("");
	$("#buscar_personal").val("");


	$("label").removeClass("active");
	$("input").removeClass("valid");
	$("input").removeClass("invalid");

	$('#img_personal').attr('src',"/media/img/user_default.jpg");

	$("#mod_nombre").val("");
	$("#mod_apellido").val("");transferir_carga
	$("#mod_correo").val("");
	$("#mod_codigo").val("");
	$('#img_personal_mod').attr('src',"/media/img/user_default.jpg");
}


// Transferencia Academica
$("#transferir_carga").click(function(){	
	$("#modalTransferirCargaAcademica").modal("open");

	$.post(
		"/api/v1/personal",
		{
			action: 3			
		},
		function(response) {	 
			$.each(response.data, function (i, item) {			

				$('#select_maestros_transferencia').append($('<option>', { 
					value: item.id,
					text : item.code+ ", "+item.lastName+" "+item.name
				}));
				$('select').material_select();
			});
		});
})

function transferAcademicLoad() {

	if ($("#select_maestros_transferencia").val() == null 
			|| $("#select_maestros_transferencia").val() == "" ) {
				swal('Alerta','Seleciona un Maestro','warning');
				return
			}

	$.post(
		"/api/v1/personal",
		{
			action: 4,
			idOldPersonal: pk_personal,
			idNewPersonal: $("#select_maestros_transferencia").val(),
		},
		function(response) {	 
			swal("Alerta","Carga Academica Transfererida Exitosamente","info");
		});	
}

