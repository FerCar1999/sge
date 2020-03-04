var url_listar_alumnos = "/privado/php/impuntualidad/listarAlumnos.php";
var url_asignar_llegada = "/privado/php/impuntualidad/impuntualidad.php";
var url_asignar_codigo = "/privado/php/disciplina/asignar_codigos.php";
var url_cargar_historial = "/privado/php/impuntualidad/historialCodigos.php";
var url_cargar_historial_impuntual = "/privado/php/impuntualidad/historialImpuntualidad.php";
var url_quitar_codigo = "/privado/php/disciplina/quitar_codigo.php";
var url_quitar_impuntualidad = "/privado/php/impuntualidad/quitar_impuntualidad.php";
var autocompleteAlumnos = {};
var pk_alumno, alumno;

$(document).ready(function() {
    $('select').material_select();
    //obtenerAlumnos();
    impuntualidad_init();
    cargar_historial_tardanza();
    initChange();
    $("input.autocomplete").focus();
    $('.timepicker').pickatime({
    	default: 'now',
    	twelvehour: false, // change to 12 hour AM/PM clock from 24 hour
    	donetext: 'Aceptar',
  		autoclose: false,
  		vibrate: true,// vibrate the device when dragging clock hand
  		darktheme: true
	});
	$('.datepicker').pickadate({
    // Strings and translations 
    	monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    	monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    	weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    	weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sáb'],

		// Buttons
		today: 'Hoy',
		clear: 'Limpiar',
		close: 'Cerrar',

		// Accessibility labels
		labelMonthNext: 'Adelante',
		labelMonthPrev: 'Atras',
		labelMonthSelect: 'Selecciona un mes',
		labelYearSelect: 'Selecciona un año',

		// Formats
		format: 'yyyy-mm-dd',
		formatSubmit: 'yyyy/mm/dd',
		
		selectMonths: true,
		selectYears: 100,

		closeOnSelect: true
	});
});

function obtenerAlumnos() {
	$.ajax({
		type:'POST',
		url:url_listar_alumnos,
		async: false,
		data:{
		    busqueda: $('input.autocomplete').val(),
      		estado: 'Activo'
		},
		dataType: "json",
		success: function(valores){
			var resp = eval(valores);
			$.each(resp, function(ind,elem) {
				var alumno = elem.alumno;
				var foto = elem.foto;
				autocompleteAlumnos[alumno] = foto;
			});
			$('input.autocomplete').autocomplete({
				data: autocompleteAlumnos
			});
			return false;
		}
	});
	return false;
}

function breakChange(){
	$("input.autocomplete").change(function(){
		
	});
}

function initChange(){
	$("input.autocomplete").change(function(){
		pk_alumno = $("input.autocomplete").val().substring(0,8);
		if (pk_alumno > 7) setTimeout(function(){asignarLlegadaTarde(pk_alumno)},100);
	});
}

function asignarLlegadaSwal(alumno){
	swal({
  		title: "¿Asignar llegada tarde?",
  		text: "",
  		type: "info",
  		showCancelButton: true,
  		confirmButtonColor: "#0277bd",
  		confirmButtonText: "Sí",
  		cancelButtonText: "No",
  		closeOnConfirm: true,
  		closeOnCancel: true
	},
	function(confirmar){
  		if (confirmar) {
    		asignarLlegadaTarde(alumno);
  		}
	});
}

function asignarLlegadaTarde(alumno){
	document.getElementById("alumno").disabled = true;
	$.post(
		url_asignar_llegada,
		{
			pk_alumno: alumno,
			token: $("#dpToken").val(),
		},function(resp){
			switch (resp){
				case 'success':
					Materialize.toast('Llegada tarde asignada.', 3000, 'rounded');
					cargar_historial_tardanza();
					$("input.autocomplete").val("");
					$("input.autocomplete").focus();
				break;
				case 'error':
					swal('Error al procesar petición.', 'error');
				break;
				default:
					Materialize.toast('Ha ocurrido un error.', 3000, 'rounded');
			}
	});
	setTimeout(function(){
		document.getElementById("alumno").disabled = false;
		$("input.autocomplete").focus();
	},650);
}

function asignarLlegadaTardeManual(alumno){
	$.post(
		url_asignar_llegada,
		{
			pk_alumno: alumno,
			hora: $("#horaLlegada").val(),
			fecha: $("#fechaLlegada").val(),
			token: $("#dpToken").val(),
		},function(resp){
			switch (resp){
				case 'success':
					Materialize.toast('Llegada tarde asignada.', 3000, 'rounded');
					cargar_historial_tardanza();
				break;
				case 'error':
					swal('Error al procesar petición.', 'error');
				break;
				default:
					Materialize.toast('Ha ocurrido un error. ' + resp, 3000, 'rounded');
			}
	});
}

function cargar_historial_codigo(codigo){
	$.ajax({
		data:{
			id:codigo
		},
		type:"POST",
		url:url_cargar_historial
	}).done(function(data,textStatus,jqXHR){
		
		// obtiene la clave lista del json data
		var lista = data.lista;
		$(".licodigos").html("");

		// genera el cuerpo de la tabla
		$.each(lista, function(ind, elem){
			$('<tr>'
				+	'<td>'+elem.tipo+'</td>'
				+	'<td>'+elem.codigo+'</td>'
				+	'<td>'+elem.fecha+'</td>'							
				+	'<td><a href="#!" class="btn-floating tooltipped" data-position="left" data-delay="50" data-tooltip="Quitar" onclick="eliminar_codigo('+elem.id+')"><i class="material-icons">highlight_off</i></a> <a href="#!" class="btn-floating tooltipped" data-position="left" data-delay="50" data-tooltip="Ver observación" onclick="swal(\'Observación\',\''+elem.observacion+'\')"><i class="material-icons">list</i></a></td>'
				+ '<tr>').appendTo($(".licodigos"));
			$('.tooltipped').tooltip();
		});			
	}).fail(function(jqXHR,textStatus,textError){
		//alert("Error al realizar la peticion dame".textError);
		$(".licodigos").html("");
	});
}

function cargar_historial_tardanza(){
	$.ajax({
		data:{
			
		},
		type:"POST",
		url:url_cargar_historial_impuntual
	}).done(function(data,textStatus,jqXHR){
		
		// obtiene la clave lista del json data
		var lista = data.lista;
		$(".liimpuntual").html("");

		// genera el cuerpo de la tabla
		$.each(lista, function(ind, elem){
			$('<tr>'
				+	'<td>'+elem.nombre+'</td>'
				+	'<td>'+elem.fecha+'</td>'
				+	'<td><a href="#!" class="btn-floating tooltipped" data-position="left" data-delay="50" data-tooltip="Quitar llegada tarde" onclick="eliminar_impuntualidad('+elem.id+')"><i class="material-icons">highlight_off</i></a> <a href="#!" class="btn-floating tooltipped" data-position="left" data-delay="50" data-tooltip="Quitar código" onclick="asignar_codigo(\''+elem.alumno+'\')"><i class="material-icons">gavel</i></a></td>'
			+ '<tr>').appendTo($(".liimpuntual"));
			$('.tooltipped').tooltip();
		});			
	}).fail(function(jqXHR,textStatus,textError){
		//alert("Error al realizar la peticion dame".textError);
		$(".liimpuntual").html("");
	});
}


function asignar_codigo(alumno){
	$("#codigos").show();
	$("#asginarCodigo").show();
	$("#cancelarCodigo").show();
	$("#mostrarCodigo").hide();
	$("#cleanText").hide();
	$("input.autocomplete").val(alumno);
	pk_alumno = $("input.autocomplete").val().substring(0,8);
}

function eliminar_impuntualidad(pk_impuntualidad){
	$.post(
		url_quitar_impuntualidad,
		{
			pk_impuntualidad:pk_impuntualidad,
			token: $("#dpToken").val(),
		},function(resp){
			Materialize.toast(resp, 3000, 'rounded');	
			cargar_historial_tardanza();
		});
}

function eliminar_codigo(pk_disciplina){
	$.post(
		url_quitar_codigo,
		{
			id:10,
			pk_disciplina:pk_disciplina,	
			token: $("#dpToken").val(),			
		},function(resp){
			Materialize.toast(resp, 3000, 'rounded');	
			cargar_historial_codigo(pk_alumno);
		});
}

$("#cleanText").click(function () {
	breakChange();
	setTimeout(function(){initChange();},100);
	$("input.autocomplete").val("");
	$("input.autocomplete").focus();
	/*pk_alumno = $("input.autocomplete").val().substring(0,8);
	if (pk_alumno > 7) {
		asignarLlegadaTarde(pk_alumno);
		$("input.autocomplete").val("");
		impuntualidad_init();
	}else{
		Materialize.toast('Alumno no seleccionado', 3000, 'rounded');
	}*/
});

$("#mostrarCodigo").click(function () {
	$("#codigos").show();
	$("#asginarCodigo").show();
	$("#cancelarCodigo").show();
	$("#mostrarCodigo").hide();
	$("#cleanText").hide();
});

$("#asginarCodigo").click(function(){
	$.ajaxSetup({async: false});
	var codigos = false;
	for (var i = 0; i < $("#selectCodigos").val().length; i++) {
		//console.log($("#selectCodigos").val()[i]);
		$.post(
		url_asignar_codigo,
		{
			pk_alumno: pk_alumno,
			pk_codigo: $("#selectCodigos").val()[i],
			pk_observacion: "Pendiente",
			token: $("#dpToken").val(),
		},function(resp){
			switch(resp){
				case 'Código asignado correctamente.':
					codigos = true;
					break;
				case 'camposFalta':
					swal('Error',resp,'error');
					break;
				default:
					swal('Error',resp,'error');
			}
		});
	}
	if (codigos) {
		swal("Códigos asignados.","","success");
		$("#selectCodigos").val("");
		$('select').material_select();
		cargar_historial_codigo(pk_alumno);
	}
});

$("#asginarTardanza").click(function(){
	pk_alumno = $("input.autocomplete").val().substring(0,8);
	if (pk_alumno > 7){
		asignarLlegadaTardeManual(pk_alumno);
	}else{
		Materialize.toast('Seleccione un estudiante.', 3000, 'rounded');	
	}
});

$("#cancelarCodigo").click(function(){
	$("#codigos").hide();
	breakChange();
	setTimeout(function(){initChange();},100);
	$("input.autocomplete").val("");
	$("input.autocomplete").focus();
	impuntualidad_init();
});

$("#manualRadio").click(function(){
	obtenerAlumnos();
	$("input.autocomplete").attr("placeholder","Carnet o nombre del estudiante");
	$("input.autocomplete").off("change");
	$("input.autocomplete").on("change",function(){
		pk_alumno = $("input.autocomplete").val().substring(0,8);
	});
	$("#horaLlegadaDiv").show();
	$("#fechaLlegadaDiv").show();
	$("#asginarTardanza").show();
});

$("#automaticRadio").click(function(){
	location.reload();
	//autocompleteAlumnos = {};
	$('input.autocomplete').autocomplete({
		data: {}
	});
	$("input.autocomplete").on("change",function(){
		//pk_alumno = $("input.autocomplete").val().substring(0,8);
		pk_alumno = $("input.autocomplete").val();
		if (pk_alumno > 7) setTimeout(function(){asignarLlegadaTarde(pk_alumno)},100)
	});
	$("#horaLlegadaDiv").hide();
	$("#fechaLlegadaDiv").hide();
	$("#asginarTardanza").hide();
});

function impuntualidad_init() {
	$("#codigos").hide();
	$("#asginarCodigo").hide();
	$("#cancelarCodigo").hide();
	$("#asginarTardanza").hide();
	$("#horaLlegadaDiv").hide();
	$("#fechaLlegadaDiv").hide();
	$("#mostrarCodigo").show();
	$("#cleanText").show();
	$("input.autocomplete").focus();
}

/*function buscarAlumnos(argument) {
	
	obtenerAlumnos();

}*/