var url_procesar_alumno="/privado/php/control_diciplinario/procesar.php";
var url_suspender_alumno="/privado/php/control_diciplinario/suspender.php";
var pk_alumno = 0;
var tipo = "";
let AlumnoCodigo = "";
$(document).ready(function () {
	$('select').material_select();
	$('.modal').modal();
});

function cargarEtapas(codigo){
		AlumnoCodigo = codigo;
		$("#modalEtapas").modal("open");
}
$("#viewReport").click(function(){
	viewReport($("#selectEtapa").val());
});

$("#allReport").click(function(){
	viewReport(0);
});

function viewReport(valor) {
	if (!($("#selectEtapa").val() == 0 || $("#selectEtapa").val() == null) || valor == 0) {		
		$("#etapaCod").val(valor);
		if (AlumnoCodigo != "") {
			$("#alumnoCod").val(AlumnoCodigo);
			reporteAlumnoForm.submit();			
			$("#modalEtapas").modal("close");
		}else{
			Materialize.toast("Seleccione un estudiante para ver su reporte.", 3000, 'rounded');
		}
	}else{
		Materialize.toast("Seleccione una etapa.", 3000, 'rounded');
	}
}

$("#buscarGrado").click(function (e) {
	e.preventDefault();

	$.ajax({ 		 		
		data:{
			id: $("#select_grado").val(),			
		},
		type:"POST",
		url:"/privado/php/control_diciplinario/imprimirControlAdmin.php"
	}).done(function(data,textStatus,jqXHR){
			
		$(".table").html("");
		$(".table").html(data);
		
		
	}).fail(function(jqXHR,textStatus,textError){
		//	alert("Error al realizar la peticion dame".textError);
	});
});

function procesar(codigo){
	swal({   title: "¿Deseas procesar al estudiante?",   text: "Si el estudiante debe ser suspendio, selecione la opción de suspender.",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Si, procesar",   cancelButtonText: "No, cancelar",   closeOnConfirm: false,   closeOnCancel: false }, function(isConfirm){   if (isConfirm) { 
		$.post(
		url_procesar_alumno,
		{
			id:codigo,
			observacion: $("#observacion_text").val(),
			token: $("#dpToken").val(),			
		},function(resp){
			location.reload();			
   	});
	} else {     swal("Cancelado", "Al suspender al estudiante se procesara automáticamente.", "error");   } });
	
}
function suspender(codigo){
	swal({   title: "¿Deseas suspender al estudiante?",   text: "Al suspender al estudiante se procesara automáticamente.",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Si, suspender",   cancelButtonText: "No, cancelar",   closeOnConfirm: false,   closeOnCancel: false }, function(isConfirm){   if (isConfirm) { 
	$.post(
		url_suspender_alumno,
		{
			id:codigo,
			observacion: $("#observacion_text").val(),
			token: $("#dpToken").val(),
		},function(resp){
			location.reload();			
   });
} else {     swal("Cancelado", "No se suspendera al estudiante.", "error");   } });
}

function procesar_alumno(pk,_tipo){
	pk_alumno = pk;	
	$('#modal1').modal('open');
	tipo = _tipo;   	
}
function guardar(){
	if(tipo=="procesar"){
	   procesar(pk_alumno);
	}else if(tipo =="suspender"){
		suspender(pk_alumno);
	}
}