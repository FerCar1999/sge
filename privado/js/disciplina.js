var url_cargar_codigos = "/privado/php/disciplina/codigos_listar.php";
var url_asignar_codigo = "/privado/php/disciplina/asignar_codigos.php";
var url_asignar_observacion = "/privado/php/disciplina/asignar_observacion.php";
var url_quitar_codigo = "/privado/php/disciplina/quitar_codigo.php";
var url_quitar_ob = "/privado/php/disciplina/quitar_observacion.php";
var url_cargar_historial="/privado/php/disciplina/historial_codigos_listar.php";
var url_cargar_historial_ob="/privado/php/disciplina/historial_observaciones.php";
var url_listar_alumnos_guia = "/privado/php/disciplina/listarAlumnosGuia.php",
	url_listar_codigos_alumno_guia = "/privado/php/disciplina/listarCodigosAlumnoGuia.php",
	url_reporte = "/privado/php/reportes/conducta/conducta.php";
var url_listar_alumnos = "/privado/php/disciplina/listarAlumnos.php";
var autocompleteAlumnos = {};
var autocompleteAlumnosT = {};
var pk_alumno;
var codigo_alumno= 0, id_horario=0;
var codigo_aula = false;
var id_edit_ob = 0;
var observacion;

$(document).ready(function () {
	obtenerAlumnos();
	obtenerAlumnosT();
	codigosExaulaInit();
	$('ul.tabs').tabs();
	$('select').material_select();
	$('.modal').modal();
	$('input.autocomplete').autocomplete({
	    data: autocompleteAlumnos
	    //data: autocompleteAlumnos
	});

	$('input.autocompleteOb').autocomplete({
	    data: autocompleteAlumnosT
	    //data: autocompleteAlumnos
	});

});


function mostrarModalAula(codigo,nombre,url,pk_horario){
	$('.modal').modal();
	$('select').material_select();
	$('#modal1').modal('open');
	$("#codigo").text(codigo);
	$("#nombre").text(nombre);
	$("#foto").attr("src",url);
	$("#codigoOb").text(codigo);
	$("#nombreOb").text(nombre);
	$("#fotoOb").attr("src",url);
	codigo_alumno = codigo;
	id_horario = pk_horario;	
	codigo_aula = true;
}
function mostrarModalAulaHistorial(codigo,nombre,url,pk_horario){
	$('.modal').modal();
	$('#modal2').modal('open');
	$("#codigoHistorial").text(codigo);
	$("#nombreHistorial").text(nombre);
	codigo_alumno = codigo;
	$("#fotoHistorial").attr("src",url);
	cargar_historial_codigo(codigo,pk_horario);
}
function cargar_historial_codigo(codigo,pk_horario){
	$.ajax({
		data:{
			id:codigo,		
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
							+	'<td><a href="#!" class="btn-floating tooltipped" data-position="left" data-delay="50" data-tooltip="Quitar código" onclick="eliminar_codigo('+elem.id+')"><i class="material-icons">highlight_off</i></a> </td>'
							+ '<tr>').appendTo($(".licodigos"));
			$('.tooltipped').tooltip();
		});
	}).fail(function(jqXHR,textStatus,textError){
		alert("Error al realizar la peticion dame".textError);

	});
}
function eliminar_observacion(pk_disciplina){

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
				url_quitar_ob,
				{
					pk_disciplina:pk_disciplina,
					token: $("#dpToken").val(),				
				},function(resp){								
					Materialize.toast(resp, 3000, 'rounded');
					getObAlumno(codigo_alumno);
					//location.reload();
				});
  		} else {
    		Materialize.toast("No se eliminó la observación", 3000, "rounded");
  		}
	});
}

function eliminar_codigo(pk_disciplina){

	$('.tooltipped').tooltip('remove');

	swal({
  		title: "¿Eliminar código?",
  		text: "Se eliminará el código del historial del alumno",
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
				url_quitar_codigo,
				{
					id:10,
					pk_disciplina:pk_disciplina,
					token: $("#dpToken").val()		
				},function(resp){								
					Materialize.toast(resp, 3000, 'rounded');
					getCodigosAlumnoGuia(codigo_alumno);
					//location.reload();
				});
  		} else {
    		Materialize.toast("No eliminó el código", 3000, "rounded");
  		}
	});

}

function asignar_observacion(){

	swal({
  		title: "¿Asignar observación?",
  		text: "Se asignará una observación al alumno",
  		type: "warning",
  		showCancelButton: true,
		confirmButtonColor: "#bbdefb",
  		confirmButtonText: "Sí, asignar",
		cancelButtonText: "Cancelar",
  		closeOnConfirm: true,
  		closeOnCancel: true
	},
	function(isConfirm){
  		if (isConfirm) {
    		$.post(
				url_asignar_observacion,
				{
					pk_codigo:codigo_alumno,
					token: $("#dpToken").val(),
					//observacion: $("#obsPredefinida").val() + $("#observacion_text").val()
					observacion: $("#observacion_text").val()
				},function(resp){								
					Materialize.toast(resp, 3000, 'rounded');
					observacion:$("#observacion_text").val("");
					$('#obsPredefinida').val("");
					// re-initialize material-select
					$('#obsPredefinida').material_select();	
				});
    		$("#observacion_text").val("");
			$("#asignarObservacionDiv").hide();
  		} else {
  			Materialize.toast("No se asignó la observación", 3000, "rounded");
  		}
	});

}

function editOb(id, acc,ob){
	
	
	if (acc == 1) {
		$.post(
			url_asignar_observacion,
			{
				id:id_edit_ob,
				token: $("#dpToken").val(),
				observacion:$("#observacion_text").val()
			},function(resp){								
				Materialize.toast(resp, 3000, 'rounded');
				$("#asignarObservacionDiv").hide();
				$("#observacion_text").val("");
				$("#headerOb").text("Asigne la observación al estudiante");
				id_edit_ob = 0;
		});
	}else if (acc == 2) {
		id_edit_ob = id;
		$("#asignarObservacionDiv").show();
		$("#observacion_text").val(ob);
		$("label[for=observacion_text]").addClass('active');
		$("#fotoOb").attr("src",autocompleteAlumnosT[$("#alumno").val()]);
		$("#history").hide();
		$("#asignarObservacion").hide();
		$("#editarObservacion").removeClass("hide");
		$("#nombreOb").text($("#alumno").val().substring(9,$("#alumno").val().length));
		$("#codigoOb").text($("#alumno").val().substring(0,8));
		$("#headerOb").text("Editar observación al estudiante");
	}
  		
}

function asignar_codigo(){
	swal({
  		title: "¿Asignar código?",
  		text: "Se asignará un código a mejorar al estudiante",
  		type: "warning",
  		showCancelButton: true,
		confirmButtonColor: "#bbdefb",
  		confirmButtonText: "Sí, asignar",
		cancelButtonText: "Cancelar",
  		closeOnConfirm: true,
  		closeOnCancel: true
	},
	function(isConfirm){
  		if (isConfirm) {
    		if(codigo_aula){
				$.post(
					url_asignar_codigo,
					{
						pk_alumno:codigo_alumno,
						pk_horario:id_horario,			
						pk_codigo:$("#selectCodigo").val(),	
						token: $("#dpToken").val(),
						pk_observacion:$("#observacion_codigo").val(),
						token: $("#dpToken").val(),	 			
					},function(resp){								
						//Materialize.toast(resp, 3000, 'rounded');
						$("#observacion_codigo").val("");
						Materialize.toast('Código asignado correctamente.', 3000, 'rounded');
					});
			}else {
				$.post(
					url_asignar_codigo,
					{
						pk_alumno:codigo_alumno,
						pk_codigo:$("#selectCodigo").val(),	
						pk_observacion:$("#observacion_codigo").val(),
						token: $("#dpToken").val(),	 			
					},function(resp){								
						//Materialize.toast(resp, 3000, 'rounded');
						Materialize.toast('Código asignado correctamente.', 3000, 'rounded');
					});
			}
			$("#asignarExaulaDiv").hide();
  		} else {
    		Materialize.toast("No se asignó el código", 3000, "rounded");
  		}
	});
}
// carga las codigos
function cargar_codigos(){
	$.ajax({ 		 		
		data:{			
			id: pk_tipo_codigo, 
			token: $("#dpToken").val(),	 		
		},
		type:"POST",
		url:url_cargar_codigos
	}).done(function(data,textStatus,jqXHR){

		// obtiene la clave lista del json data
		var lista = data.lista;
		$("#selectCodigo").html("");

		// genera el cuerpo de la tabla
		$.each(lista, function (i, item) {

			$('#selectCodigo').append($('<option>', { 
				value: item.id,
				text : item.nombre 
			}));
			$('select').material_select();
		});
	}).fail(function(jqXHR,textStatus,textError){
		alert("Error al realizar la peticion dame".textError);
	});
}

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

function obtenerAlumnosT() {
	$.ajaxSetup({async: false});
	$.ajax({
		type:'POST',
		url:url_listar_alumnos,
		data:{
		    
		},
		dataType: "json",
		success: function(valores){
			var resp = eval(valores);
			$.each(resp, function(ind,elem) {
				var alumno = elem.alumno;
				var foto = elem.foto;
				autocompleteAlumnosT[alumno] = foto;
				autocompleteAlumnos[alumno] = foto;
			});
			return false;
		}
	});
	return false;	
}

$("#verCodigosAlumno").click(function () {
	codigosExaulaInit();
	codigo_alumno = $("#alumno").val().substring(0,8);
	if (codigo_alumno != "") {
		getCodigosAlumnoGuia(codigo_alumno);		
	}
});

$("#verObsAlumno").click(function(){
	codigo_alumno = $("#alumno").val().substring(0,8);
	if (codigo_alumno != "") {
		getObAlumno(codigo_alumno);
	}
});

$("#limpiar_datos").click(function(){
	$("#history").hide();
	$("#alumno").val("");
});

$("#editarObservacion").click(function(){
	swal({
  		title: "¿Editar observación?",
  		text: "Se editará la observación del historial del estudiante",
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
			editOb(id_edit_ob,1,$("observacion_text").val());
  		} else {
    		Materialize.toast("No ha editado la observación", 3000, "rounded");
  		}
	});
});

$("#loadCodigos").click(function () {
	codigosExaulaInit();
	codigo_alumno = $("#alumno").val().substring(0,8);
	if (codigo_alumno != "") {
		$("#asignarExaulaDiv").show();
		$("#foto").attr("src", autocompleteAlumnos[$("#alumno").val()]);
		$("#nombre").text($("#alumno").val().substring(9,$("#alumno").val().length));
		$("#codigo").text($("#alumno").val().substring(0,8));
	}
});

$("#reporteAlumno").click(function(){
	codigo_alumno = $("#alumno").val().substring(0,8);
	if (codigo_alumno != "") $("#modalEtapas").modal("open");
	else Materialize.toast("Seleccione un estudiante para ver su reporte.", 3000, 'rounded');
});

$("#selectEtapa").change(function(){
	$("#etapaCod").val($("#selectEtapa").val());
});

$("#viewReport").click(function(){
	viewReport($("#selectEtapa").val());
});

$("#allReport").click(function(){
	viewReport(0);
});

function viewReportAlumnos(codigo){
	$("#modalEtapas").modal("open");
}

function viewReport(valor) {
	if (!($("#selectEtapa").val() == 0 || $("#selectEtapa").val() == null) || valor == 0) {
		codigo_alumno = $("#alumno").val().substring(0,8);
		$("#etapaCod").val(valor);
		if (codigo_alumno != "") {
			$("#alumnoCod").val(codigo_alumno);
			console.log($("#etapaCod").val());
			reporteAlumnoForm.submit();
			$("#modalEtapas").modal("close");
		}else{
			Materialize.toast("Seleccione un estudiante para ver su reporte.", 3000, 'rounded');
		}
	}else{
		Materialize.toast("Seleccione una etapa.", 3000, 'rounded');
	}
}

$("#loadObservacion").click(function () {
	codigosExaulaInit();
	codigo_alumno = $("#alumno").val().substring(0,8);
	if (codigo_alumno != "") {
		$("observacion_text").text("");
		$("#asignarObservacionDiv").show();
		$("#fotoOb").attr("src", autocompleteAlumnos[$("#alumno").val()]);
		$("#nombreOb").text($("#alumno").val().substring(9,$("#alumno").val().length));
		$("#codigoOb").text($("#alumno").val().substring(0,8));
		$("#history").hide();
	}
});


$(".cancelarButton").click(function () {
	codigosExaulaInit();
})

$("#asignarCodigo").click(function () {
	asignar_codigo();
	$("#observacion_codigo").val("");
	$('#selectCodigo').find('option[value=""]').prop('selected', true);
	$("#selectCodigo").material_select();
	$('#selectTipoCodigo').find('option[value=""]').prop('selected', true);
	$("#selectTipoCodigo").material_select();
});

$("#asignarObservacion").click(function () {
	asignar_observacion();
});

$("#observacionActive").click(function () {
	$("#buttonCodigo").hide();
	$("#buttonObservacion").show();
	$(".auto").show();
	$(".autoInfo").hide();
});


$("#codigoActive").click(function () {
	$("#buttonCodigo").show();
	$("#buttonObservacion").hide();
});

function getCodigosAlumnoGuia(pk_alumno_busq) {
	
	codigosExaulaInit();

	$.ajaxSetup({async: false});
	$.ajax({
		data:{
			id:pk_alumno_busq,		
		},
		type:"POST",
		url:url_cargar_historial
	}).done(function(data,textStatus,jqXHR){
		
		// obtiene la clave lista del json data
		var lista = data.lista;

		var listaCodigos = "";
		var infoObtenida = false;


		$(".table").html("");


		$.each(lista, function(ind,elem) {
			codigo_alumno = pk_alumno_busq;
			listaCodigos += '<tr>'
							+	'<td>'+elem.tipo+'</td>'
							+	'<td>'+elem.codigo+'</td>'
							+	'<td>'+elem.fecha+'</td>'
							+	'<td>'+elem.nombre+'</td>'
							+	'<td><a href="#!" class="btn-floating tooltipped" data-position="left" data-delay="50" data-tooltip="Quitar código" onclick="eliminar_codigo('+elem.id+')"><i class="material-icons">highlight_off</i></a> </td>'
							+ '<tr>';
		});

		if (listaCodigos != "") {
			$("ul.collection").html("");
			$("ul.collection").append('<li class="collection-header center-align"><h5>Historial de códigos</h5');
			$(".table").append(listaCodigos);
		}

		if (listaCodigos != "") {
			$("#noHayCodigos").hide();
			$("#asignarExaulaDiv").hide();
			$("#asignarObservacionDiv").hide();
			$("#infoCodigos").show();
			$("#fotoExaula").attr("src", autocompleteAlumnos[$("#alumno").val()]);
			$("#nombreExaula").text($("#alumno").val().substring(9,$("#alumno").val().length));
			$("#carnetExaula").text($("#alumno").val().substring(0,8));
			$('.tooltipped').tooltip();
		}else{
			$("#infoCodigos").hide();
			$("#asignarExaulaDiv").hide();
			$("#asignarObservacionDiv").hide();
			$("#noHayCodigos").show();
			$("#noHayCodigos").append('<br><h5>No se han asignado códigos al estudiante</h5>');
		}

	}).fail(function(jqXHR,textStatus,textError){
		//alert("Error al realizar la peticion dame".textError);
		
		$("#infoCodigos").hide();
		$("#asignarExaulaDiv").hide();
		$("#noHayCodigos").show();
		$("#noHayCodigos").html("");
		$("#noHayCodigos").append('<br><h5>No se han asignado códigos al estudiante</h5>');
		
	});	
}

function getObAlumno(pk_alumno_busq) {
	

	$.ajaxSetup({async: false});
	$.ajax({
		data:{
			id:pk_alumno_busq,		
		},
		type:"POST",
		url:url_cargar_historial_ob
	}).done(function(data,textStatus,jqXHR){
		
		// obtiene la clave lista del json data
		var lista = data.lista;

		var listaCodigos = "";
		var infoObtenida = false;


		$(".historialObAlumno").html("");


		$.each(lista, function(ind,elem) {
			console.log(elem.observacion);
			observacion = elem.observacion;
			codigo_alumno = pk_alumno_busq;
			listaCodigos += '<tr>'
							+	'<td>'+elem.fecha+'</td>'
							+	'<td>'+elem.alumno+'</td>'
							+	'<td>'+elem.observacion+'</td>'
							+	'<td><a href="#!" class="btn-floating tooltipped" data-position="left" data-delay="50" data-tooltip="Quitar observacion" onclick="eliminar_observacion('+elem.id+',1)"><i class="material-icons">highlight_off</i></a> <a href="#!" class="btn-floating tooltipped" data-position="left" data-delay="50" data-tooltip="Editar observación" onclick="editOb('+elem.id+',2,'+  "\'" + elem.observacion + "\'" +')"><i class="material-icons">edit</i></a></td>'
							+ '<tr>';
		});

		if (listaCodigos != "") {
			$(".historialObAlumno").append(listaCodigos);
			$("#history").show();
		}

		if (listaCodigos != "") {
			$("#noHayCodigos").hide();
			$("#asignarExaulaDiv").hide();
			$("#asignarObservacionDiv").hide();
			$("#infoCodigos").show();
			$("#fotoExaula").attr("src", autocompleteAlumnos[$("#alumno").val()]);
			$("#nombreExaula").text($("#alumno").val().substring(9,$("#alumno").val().length));
			$("#carnetExaula").text($("#alumno").val().substring(0,8));
		}else{
			$("#infoCodigos").hide();
			$("#asignarExaulaDiv").hide();
			$("#asignarObservacionDiv").hide();
			$("#noHayCodigos").show();
			$("#noHayCodigos").append('<br><h5>No se han asignado códigos al estudiante</h5>');
			Materialize.toast("No se han asignado códigos al estudiante", 3000, 'rounded');
		}

	}).fail(function(jqXHR,textStatus,textError){
		//alert("Error al realizar la peticion dame".textError);
		
		$("#infoCodigos").hide();
		$("#asignarExaulaDiv").hide();
		$("#history").hide();
		$("#noHayCodigos").html("");
		$("#noHayCodigos").append('<br><h5>No se han asignado códigos al estudiante</h5>');
		$("#noHayCodigos").show();
		Materialize.toast("No se han asignado observaciones al estudiante", 3000, 'rounded');
		
	});
	$('.tooltipped').tooltip();
}

function codigosExaulaInit() {
	$(".autoInfo").hide();
	$("#infoCodigos").hide();
	$("#noHayCodigos").hide();
	$("#asignarExaulaDiv").hide();
	$("#asignarObservacionDiv").hide();
	$("#buttonObservacion").hide();
	$("#fotoExaula").attr("src", "");
	$("#nombreExaula").text("");
	$("#carnetExaula").text("");
	$("#history").hide();
	$("#asignarObservacion").show();
	$("#editarObservacion").addClass("hide");
}

$("#selectTipoCodigo").change(function() {	
	pk_tipo_codigo = $( "#selectTipoCodigo").val();	
	cargar_codigos();	
});