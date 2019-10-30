var url_procesar_alumno="/privado/php/controlLlegadasTarde/procesar.php";
var url_suspender_alumno="/privado/php/control_diciplinario/suspender.php";
var pk_alumno = 0;
var tipo = "";
var codigo ="";
$(document).ready(function () {
	$('select').material_select();
	$('.modal').modal();
	if($("#isAdmin").val() != "1"){
		$(".filtro").hide('fast');
		alumnosDocenteGuia();		
	}
 });

 // obtener alumnos docente guia
 function alumnosDocenteGuia(){
	$.ajax({ 		 		
		data:{
			id_etapa: $("#select_etapa").val(),
		},
		type:"POST",
		url:"/privado/php/controlLlegadasTarde/listarSinProcesarAlumnosMaestroGuia.php"
	}).done(function(data,textStatus,jqXHR){			
		$(".table2").html("");
		$(".table2").html(data);				
	});
 }

function procesar_alumno(pk){
	codigo = pk;
	$('.modal').modal();
	$('#modal1').modal('open');

	  	
}
$("#buscarAlumnos").click(function(e){
	if($("#isAdmin").val() != "1"){
		alumnosDocenteGuia();
	}else {
		if($("#select_nivel").val() == "1") obtenerAlumnosTercerCiclo();
		else obtenerAlumnosBachillerato();
	}
});
function obtenerAlumnosBachillerato(){
	$.ajax({ 		 		
		data:{
			id_nivel: $("#select_nivel").val(),
			id_etapa: $("#select_etapa").val(),			
		},
		type:"POST",
		url:"/privado/php/controlLlegadasTarde/listarSinProcesarBachillerato.php"
	}).done(function(data,textStatus,jqXHR){			
		$(".table2").html("");
		$(".table2").html(data);				
	});
}function obtenerAlumnosTercerCiclo(){
	$.ajax({ 		 		
		data:{
			id_nivel: $("#select_nivel").val(),
			id_etapa: $("#select_etapa").val(),			
		},
		type:"POST",
		url:"/privado/php/controlLlegadasTarde/listarSinProcesarTercerCiclo.php"
	}).done(function(data,textStatus,jqXHR){			
		$(".table2").html("");
		$(".table2").html(data);				
	});
}
function guardar(){
	
		$.post(
		url_procesar_alumno,
		{
			id:codigo,			
			token: $("#dpToken").val(),	
			observacion: $("#observacion_text").val(),		
		},function(resp){
			$("#observacion_text").val("");
			location.reload();						
   		});
}
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