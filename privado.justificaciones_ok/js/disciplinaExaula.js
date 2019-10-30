var url_listar_alumnos_guia = "/privado/php/disciplina/listarAlumnosGuia.php",
	url_listar_codigos_alumno_guia = "/privado/php/disciplina/listarCodigosAlumnoGuia.php";
var autocompleteAlumnos = {};
var pk_alumno;

$(document).ready(function () {
	obtenerAlumnos();
	codigosExaulaInit();
	
	$('select').material_select();

	$('input.autocomplete').autocomplete({
	    data: autocompleteAlumnos
	    //data: autocompleteAlumnos
	});

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

$("#verCodigosAlumno").click(function () {
	pk_alumno = $("#alumno").val().substring(0,8);
	if (pk_alumno != "") {
		getCodigosAlumnoGuia(pk_alumno);		
	}
});

$("#loadCodigos").click(function () {
	codigosExaulaInit();
	$("#asignarExaulaDiv").show();
	$("#foto").attr("src", autocompleteAlumnos[$("#alumno").val()]);
	$("#nombre").text($("#alumno").val().substring(9,$("#alumno").val().length));
	$("#codigo").text($("#alumno").val().substring(0,8));
});

function getCodigosAlumnoGuia(pk_alumno) {
	
	codigosExaulaInit();

	var fotoHistorial = "", nombreHistorial = "", carnetHistorial = "";

	$.ajaxSetup({async: false});
	$.ajax({
		type:'POST',
		url:url_listar_codigos_alumno_guia,
		data:{
			carnet: pk_alumno
		},
		dataType: "json",
		success: function(valores){
			var listaCodigos = "";
			var infoObtenida = false;


			$("ul.collection").html("");

			var resp = eval(valores);
			$.each(resp, function(ind,elem) {

				if (!infoObtenida) {
					fotoHistorial = elem.foto;
					nombreHistorial = elem.alumno;
					carnetHistorial = elem.codigo;
					infoObtenida = true;

				}

				listaCodigos += '<li class="collection-item"><div>'+elem.descripcion+'<a href="#!" class="secondary-content tooltipped" data-position="left" data-delay="50" data-tooltip="Quitar código"><i class="material-icons">highlight_off</i></a></div></li>';

			});

			if (listaCodigos != "") {
				$("ul.collection").append('<li class="collection-header center-align"><h5>Historial de códigos</h5');
				$("ul.collection").append(listaCodigos);
			}

			if (listaCodigos != "") {
				$("#noHayCodigos").hide();
				$("#asignarExaulaDiv").hide();
				$("#infoCodigos").show();
				$("#fotoExaula").attr("src", autocompleteAlumnos[$("#alumno").val()]);
				$("#nombreExaula").text($("#alumno").val().substring(9,$("#alumno").val().length));
				$("#carnetExaula").text($("#alumno").val().substring(0,8));
			}else{
				$("#infoCodigos").hide();
				$("#asignarExaulaDiv").hide();
				$("#noHayCodigos").show();
				$("#noHayCodigos").append('<br><h5>No se han asignado códigos al estudiante</h5>');
			}
			return false;
		}
	});
	return false;	
}

function codigosExaulaInit() {
	$("#infoCodigos").hide();
	$("#noHayCodigos").hide();
	$("#asignarExaulaDiv").hide();
	$("#fotoExaula").attr("src", "");
	$("#nombreExaula").text("");
	$("#carnetExaula").text("");
}