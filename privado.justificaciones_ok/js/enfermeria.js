var url_listar_alumnos_guia = "/privado/php/enfermeria/listarAlumnos.php";
var url_asignar_visita = "/privado/php/enfermeria/enfermeria.php";
var url_historial_enfermeria = "/privado/php/enfermeria/historialEnfermeria.php";
var url_eliminar_visita = "/privado/php/enfermeria/eliminarVisita.php";
var autocompleteAlumnos = {}, alumno, pk_enfermeria;

$(document).ready(function(){
	obtenerAlumnos();
	$('input.autocomplete').autocomplete({
	    data: autocompleteAlumnos
	});
	enfermeria_init();
	cargar_historial_enfermeria();
});

function enfermeria_init(){
	$("#selecAlumno").show();
	$("#asignarObservacion").hide();
	$("#modObservacion").hide();
	$(".observacionEnfermeria").hide();
	$("#alumno").val("");
}

function enfermeria_select(){
	$("#nombreEnf").text($("#alumno").val().substring(9,$("#alumno").val().length));
	$("#carnetEnf").text($("#alumno").val().substring(0,8));
	$("#fotoEnf").attr("src", autocompleteAlumnos[$("#alumno").val()]);
	$(".observacionEnfermeria").show();
	$("#asignarObservacion").show();
	$("#selecAlumno").hide();
	$("#observacion_text").text("");
	alumno = $("#carnetEnf").text();
}

$("#verReporte").click(function(){
	codigo_alumno = $("#alumno").val().substring(0,8);
	if (codigo_alumno != "") {
		$("#alumCod").val($("#alumno").val().substring(0,8));
		reporteAlumnoForm.submit();
	}else{
		Materialize.toast("Seleccione un estudiante para ver su reporte.", 3000, 'rounded');
	}
});

$("#selecAlumno").click(function(){
	enfermeria_select();
});

$("#asignarObservacion").click(function(){
	asignarVisita(alumno);
});

$("#modObservacion").click(function(){
	modificarVisita(pk_enfermeria);
});

$("#cancelarButton").click(function () {
	enfermeria_init();
	limpiar();
});

function obtenerAlumnos() {
	$.ajaxSetup({async: false});
	$.ajax({
		type:'POST',
		url:url_listar_alumnos_guia,
		data:{
		    
		},
		dataType: "json",
		success: function(valores){
			var resp = eval(valores);
			$.each(resp, function(ind,elem) {
				var alumno = elem.alumno;
				var foto = elem.foto;
				autocompleteAlumnos[alumno] = foto;
			});
			return false;
		}
	});
	return false;	
}

function modificarVisita(pk_enfermeria){

	swal({
  		title: "¿Modificar observación?",
  		text: "Se modificará la observación del historial del alumno",
  		type: "warning",
  		showCancelButton: true,
		confirmButtonColor: "#bbdefb",
  		confirmButtonText: "Sí, modificar",
		cancelButtonText: "Cancelar",
  		closeOnConfirm: true,
  		closeOnCancel: true
	},
	function(isConfirm){
  		if (isConfirm) {
    		$.post(
				url_asignar_visita,
				{
					observacion: $("#observacion_text").val(),
					modificar: pk_enfermeria
				},function(resp){
					switch (resp){
						case 'success':
							Materialize.toast('Visita a la enfermería editada.', 3000, 'rounded');
						break;
						case 'error':
							swal('Error al procesar petición.', 'error');
						break;
						default:
							Materialize.toast(resp, 3000, 'rounded');
							cargar_historial_enfermeria();
							limpiar();
							enfermeria_init();
						break;
					}
			});
  		} else {
    		Materialize.toast("No se modificó la observación", 3000, "rounded");
  		}
	});
	
}


function eliminar_visita(pk_enfermeria){
	$('.tooltipped').tooltip('remove');
	swal({
  		title: "¿Eliminar observación?",
  		text: "Se eliminará la observación del historial del alumno",
  		type: "warning",
  		showCancelButton: true,
		confirmButtonColor: "#bbdefb",
  		confirmButtonText: "Sí, eliminar",
		cancelButtonText: "Cancelar",
  		closeOnConfirm: true,
  		closeOnCancel: true
	},
	function(isConfirm){
  		if (isConfirm) {
    		$.post(
				url_eliminar_visita,
				{
					id: pk_enfermeria,
					token: $("#dpToken").val(),
				},function(resp){
					switch (resp){
						case 'success':
							Materialize.toast('Visita a la enfermería eliminada.', 3000, 'rounded');
						break;
						case 'error':
							swal('Error al procesar petición.', 'error');
						break;
						default:
							Materialize.toast(resp, 3000, 'rounded');
							cargar_historial_enfermeria();
							limpiar();
							enfermeria_init();
						break;
					}
			});
  		} else {
    		Materialize.toast("No se eliminó la observación", 3000, "rounded");
  		}
	});
	
}

function asignarVisita(alumno){
	$.post(
		url_asignar_visita,
		{
			pk_alumno: alumno,
			observacion: $("#observacion_text").val(),
			token: $("#dpToken").val(),
			modificar: 0
		},function(resp){
			switch (resp){
				case 'success':
					Materialize.toast('Visita a la enfermería asignada.', 3000, 'rounded');
					limpiar();
				break;
				case 'error':
					swal('Error al procesar petición.', 'error');
				break;
				default:
					Materialize.toast(resp, 3000, 'rounded');
					cargar_historial_enfermeria();
			}
	});
}

function cargar_historial_enfermeria(){
	$.ajax({
		data:{
			
		},
		type:"POST",
		url:url_historial_enfermeria
	}).done(function(data,textStatus,jqXHR){
		
		// obtiene la clave lista del json data
		var lista = data.lista;
		$(".lienfermeria").html("");

		// genera el cuerpo de la tabla
		$.each(lista, function(ind, elem){
			$('<tr>'
				+	'<td>'+elem.nombre+'</td>'
				+	'<td>'+elem.especialidad+'</td>'
				+	'<td>'+elem.fecha+'</td>'							
				+	'<td><a href="#!" class="btn-floating tooltipped" data-position="left" data-delay="50" data-tooltip="Quitar" onclick="eliminar_visita('+elem.id+')"><i class="material-icons">highlight_off</i></a> <a href="#!" class="btn-floating tooltipped" data-position="left" data-delay="50" data-tooltip="Información" onclick="ver_visita('+elem.id+',\''+elem.alumno+'\',\''+elem.observacion+'\')"><i class="material-icons">edit</i></a></td>'
			+ '<tr>').appendTo($(".lienfermeria"));
			$('.tooltipped').tooltip();
		});			
	}).fail(function(jqXHR,textStatus,textError){
		//alert("Error al realizar la peticion dame".textError);
		$(".lienfermeria").html("");
	});
}

function limpiar(){
	$('.tooltipped').tooltip();
	$("input.autocomplete").val("");
	$("#observacion_text").val("");
}

function ver_visita(id,alumno,observacion){
	pk_enfermeria = id;
	$("#modObservacion").show();
	$('input.autocomplete').val(alumno);
	$("#observacion_text").val(observacion);
	$("label").addClass("active");
	enfermeria_select();
	$("#asignarObservacion").hide();
}