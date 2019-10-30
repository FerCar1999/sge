var url_listar_alumnos = "/privado/php/reiniciarDatos/listarAlumnos.php";
var year, codigo_alumno;
var autocompleteAlumnos = {};

$(document).ready(function () {
	$('select').material_select();
});

$("#select_anio").change(function() {
	autocompleteAlumnos = {};
	year = $("#select_anio").val();	
	obtenerAlumnos();
	$('input.autocomplete').autocomplete({
	    data: autocompleteAlumnos
	});	
	$('#select_anio').prop('disabled', 'disabled');
	$('select').material_select();
	$("#buscarAlumno").prop('disabled', false);
	$("#year").val($("#select_anio").val());
});

$("#buscarAlumno").click(function(){
	location.reload();
});

$("#reporteAlumno").click(function(){
	console.log($("#alumno").val());
	codigo_alumno = $("#alumno").val().substring(0,8);
	if (!(codigo_alumno != "")) Materialize.toast("Seleccione un estudiante para ver su reporte.", 3000, 'rounded');
	if (codigo_alumno != "") {
		$("#alumnoCod").val(codigo_alumno);
		console.log(codigo_alumno);
		//console.log($("#etapaCod").val());
		reporteAlumnoForm.submit();
	}else{
		Materialize.toast("Seleccione un estudiante para ver su reporte.", 3000, 'rounded');
	}
});

function obtenerAlumnos() {
	$.ajaxSetup({async: false});
	$.ajax({
		type:'POST',
		url:url_listar_alumnos,
		data:{
		    y: year 
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